<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CDSKI_PayPal {

    private static function client_id() {
        return defined( 'CDSKI_PAYPAL_CLIENT_ID' ) ? CDSKI_PAYPAL_CLIENT_ID : '';
    }

    private static function secret() {
        return defined( 'CDSKI_PAYPAL_SECRET' ) ? CDSKI_PAYPAL_SECRET : '';
    }

    private static function is_sandbox() {
        return defined( 'CDSKI_PAYPAL_SANDBOX' ) && CDSKI_PAYPAL_SANDBOX;
    }

    private static function api_base() {
        return self::is_sandbox()
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';
    }

    private static function get_access_token() {
        $response = wp_remote_post( self::api_base() . '/v1/oauth2/token', [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode( self::client_id() . ':' . self::secret() ),
                'Content-Type'  => 'application/x-www-form-urlencoded',
            ],
            'body'    => 'grant_type=client_credentials',
            'timeout' => 30,
        ] );

        if ( is_wp_error( $response ) ) {
            error_log( 'CDSKI PayPal token error: ' . $response->get_error_message() );
            return false;
        }

        $code = wp_remote_retrieve_response_code( $response );
        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( $code !== 200 || empty( $body['access_token'] ) ) {
            error_log( 'CDSKI PayPal token failed: HTTP ' . $code . ' - ' . wp_remote_retrieve_body( $response ) );
            return false;
        }

        return $body['access_token'];
    }

    /**
     * Convert CLP to USD using a conservative rate.
     * PayPal may not support CLP directly on all accounts.
     */
    private static function clp_to_usd( $clp_amount ) {
        // Use a conservative rate; PayPal handles final conversion
        // ~950 CLP = 1 USD (approximate). Using round number.
        $rate = 950;

        // Try to get live rate from a free API
        $response = wp_remote_get( 'https://open.er-api.com/v6/latest/USD', [ 'timeout' => 5 ] );
        if ( ! is_wp_error( $response ) ) {
            $data = json_decode( wp_remote_retrieve_body( $response ), true );
            if ( ! empty( $data['rates']['CLP'] ) ) {
                $rate = floatval( $data['rates']['CLP'] );
            }
        }

        $usd = round( $clp_amount / $rate, 2 );
        return max( $usd, 1.00 ); // Minimum $1 USD
    }

    /**
     * Create PayPal order and return approval URL.
     */
    public static function create_order( $payment_id, $amount, $data ) {
        $token = self::get_access_token();
        if ( ! $token ) {
            return false;
        }

        $return_url = home_url( "/cdski-callback/paypal/{$payment_id}/" );
        $cancel_url = home_url( "/cdski-callback/paypal/{$payment_id}/?cancelled=1" );

        $description = mb_substr( $data['concepto'] ?: 'Pago reserva clase - Clasesdeski', 0, 127 );

        // Try CLP first, fallback to USD if it fails
        $order_data = [
            'intent'         => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => (string) $payment_id,
                    'description'  => $description,
                    'amount'       => [
                        'currency_code' => 'USD',
                        'value'         => (string) self::clp_to_usd( $amount ),
                    ],
                ],
            ],
            'application_context' => [
                'return_url'  => $return_url,
                'cancel_url'  => $cancel_url,
                'brand_name'  => 'Clasesdeski',
                'user_action' => 'PAY_NOW',
                'locale'      => 'es-CL',
            ],
        ];

        $response = wp_remote_post( self::api_base() . '/v2/checkout/orders', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
            ],
            'body'    => wp_json_encode( $order_data ),
            'timeout' => 30,
        ] );

        if ( is_wp_error( $response ) ) {
            error_log( 'CDSKI PayPal order error: ' . $response->get_error_message() );
            return false;
        }

        $code = wp_remote_retrieve_response_code( $response );
        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( $code < 200 || $code >= 300 ) {
            error_log( 'CDSKI PayPal order failed: HTTP ' . $code . ' - ' . wp_json_encode( $body ) );
            return false;
        }

        if ( ! empty( $body['id'] ) ) {
            CDSKI_DB::update_payment( $payment_id, [
                'transaction_id' => $body['id'],
            ] );
        }

        // Find approval link
        foreach ( ( $body['links'] ?? [] ) as $link ) {
            if ( $link['rel'] === 'approve' ) {
                return $link['href'];
            }
        }

        error_log( 'CDSKI PayPal: no approve link found in: ' . wp_json_encode( $body ) );
        return false;
    }

    /**
     * Handle return from PayPal — capture the order.
     */
    public static function handle_callback( $payment_id ) {
        $payment = CDSKI_DB::get_payment( $payment_id );
        if ( ! $payment ) {
            wp_die( 'Pago no encontrado.', 'Error', [ 'response' => 404 ] );
        }

        if ( isset( $_GET['cancelled'] ) ) {
            CDSKI_DB::update_payment( $payment_id, [ 'estado' => 'cancelled' ] );
            cdski_pagos_after_status_change( $payment_id, 'cancelled' );
            wp_redirect( add_query_arg( [
                'cdski_status' => 'cancelled',
                'cdski_pid'    => $payment_id,
            ], home_url( '/resultado-pago/' ) ) );
            exit;
        }

        $paypal_token = sanitize_text_field( $_GET['token'] ?? '' );
        if ( ! $paypal_token ) {
            CDSKI_DB::update_payment( $payment_id, [ 'estado' => 'error' ] );
            cdski_pagos_after_status_change( $payment_id, 'error' );
            wp_redirect( add_query_arg( [
                'cdski_status' => 'error',
                'cdski_pid'    => $payment_id,
            ], home_url( '/resultado-pago/' ) ) );
            exit;
        }

        // Capture order
        $token = self::get_access_token();
        if ( ! $token ) {
            CDSKI_DB::update_payment( $payment_id, [ 'estado' => 'error' ] );
            cdski_pagos_after_status_change( $payment_id, 'error' );
            wp_redirect( add_query_arg( [
                'cdski_status' => 'error',
                'cdski_pid'    => $payment_id,
            ], home_url( '/resultado-pago/' ) ) );
            exit;
        }

        $response = wp_remote_post( self::api_base() . "/v2/checkout/orders/{$paypal_token}/capture", [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
            ],
            'body'    => '{}',
            'timeout' => 30,
        ] );

        $body   = json_decode( wp_remote_retrieve_body( $response ), true );
        $status = $body['status'] ?? '';

        if ( $status === 'COMPLETED' ) {
            $capture_id = $body['purchase_units'][0]['payments']['captures'][0]['id'] ?? $paypal_token;
            CDSKI_DB::update_payment( $payment_id, [
                'estado'         => 'approved',
                'transaction_id' => $capture_id,
            ] );
            cdski_pagos_after_status_change( $payment_id, 'approved' );
            $redirect_status = 'approved';
        } else {
            CDSKI_DB::update_payment( $payment_id, [ 'estado' => 'rejected' ] );
            cdski_pagos_after_status_change( $payment_id, 'rejected' );
            $redirect_status = 'rejected';
        }

        wp_redirect( add_query_arg( [
            'cdski_status' => $redirect_status,
            'cdski_pid'    => $payment_id,
        ], home_url( '/resultado-pago/' ) ) );
        exit;
    }
}
