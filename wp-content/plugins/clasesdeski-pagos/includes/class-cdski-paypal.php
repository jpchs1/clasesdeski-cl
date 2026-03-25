<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CDSKI_PayPal {

    public static function client_id() {
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

        $body = json_decode( wp_remote_retrieve_body( $response ), true );
        return $body['access_token'] ?? false;
    }

    /**
     * Convert CLP to USD.
     */
    public static function clp_to_usd( $clp_amount ) {
        $rate = 950;
        $response = wp_remote_get( 'https://open.er-api.com/v6/latest/USD', [ 'timeout' => 5 ] );
        if ( ! is_wp_error( $response ) ) {
            $data = json_decode( wp_remote_retrieve_body( $response ), true );
            if ( ! empty( $data['rates']['CLP'] ) ) {
                $rate = floatval( $data['rates']['CLP'] );
            }
        }
        $usd = round( $clp_amount / $rate, 2 );
        return max( $usd, 1.00 );
    }

    /**
     * Create PayPal order server-side (called via AJAX from JS SDK).
     * Returns order ID (not a redirect URL).
     */
    public static function create_order_for_js( $payment_id, $amount, $description ) {
        $token = self::get_access_token();
        if ( ! $token ) {
            return false;
        }

        $usd_amount = self::clp_to_usd( $amount );

        $order_data = [
            'intent'         => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => (string) $payment_id,
                    'description'  => mb_substr( $description ?: 'Pago reserva - Clasesdeski', 0, 127 ),
                    'amount'       => [
                        'currency_code' => 'USD',
                        'value'         => (string) $usd_amount,
                    ],
                ],
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
            error_log( 'CDSKI PayPal create_order error: ' . $response->get_error_message() );
            return false;
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( ! empty( $body['id'] ) ) {
            CDSKI_DB::update_payment( $payment_id, [
                'transaction_id' => $body['id'],
            ] );
            return $body['id'];
        }

        error_log( 'CDSKI PayPal create_order failed: ' . wp_remote_retrieve_body( $response ) );
        return false;
    }

    /**
     * Capture PayPal order server-side (called via AJAX after JS SDK approval).
     */
    public static function capture_order( $order_id ) {
        $token = self::get_access_token();
        if ( ! $token ) {
            return [ 'success' => false, 'error' => 'No access token' ];
        }

        $response = wp_remote_post( self::api_base() . "/v2/checkout/orders/{$order_id}/capture", [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
            ],
            'body'    => '{}',
            'timeout' => 30,
        ] );

        if ( is_wp_error( $response ) ) {
            return [ 'success' => false, 'error' => $response->get_error_message() ];
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( ( $body['status'] ?? '' ) === 'COMPLETED' ) {
            $capture_id = $body['purchase_units'][0]['payments']['captures'][0]['id'] ?? $order_id;
            return [ 'success' => true, 'capture_id' => $capture_id ];
        }

        error_log( 'CDSKI PayPal capture failed: ' . wp_remote_retrieve_body( $response ) );
        return [ 'success' => false, 'error' => $body['message'] ?? 'Capture failed' ];
    }

    /**
     * Legacy redirect callback handler (kept for backwards compat).
     */
    public static function handle_callback( $payment_id ) {
        $payment = CDSKI_DB::get_payment( $payment_id );
        if ( ! $payment ) {
            wp_die( 'Pago no encontrado.', 'Error', [ 'response' => 404 ] );
        }

        if ( isset( $_GET['cancelled'] ) ) {
            CDSKI_DB::update_payment( $payment_id, [ 'estado' => 'cancelled' ] );
            cdski_pagos_after_status_change( $payment_id, 'cancelled' );
        }

        wp_redirect( add_query_arg( [
            'cdski_status' => $payment->estado !== 'pending' ? $payment->estado : 'cancelled',
            'cdski_pid'    => $payment_id,
        ], home_url( '/resultado-pago/' ) ) );
        exit;
    }
}
