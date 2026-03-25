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
            'body' => 'grant_type=client_credentials',
            'timeout' => 30,
        ] );

        if ( is_wp_error( $response ) ) {
            return false;
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );
        return $body['access_token'] ?? false;
    }

    /**
     * Create PayPal order and return approval URL.
     */
    public static function create_order( $payment_id, $amount, $data ) {
        $token = self::get_access_token();
        if ( ! $token ) {
            return false;
        }

        // PayPal works in USD; convert CLP to USD if needed.
        // For simplicity, we send CLP directly — PayPal supports CLP.
        $return_url = home_url( "/cdski-callback/paypal/{$payment_id}/" );
        $cancel_url = home_url( "/cdski-callback/paypal/{$payment_id}/?cancelled=1" );

        $order_data = [
            'intent'         => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => (string) $payment_id,
                    'description'  => $data['concepto'] ?: 'Pago reserva clase - Clasesdeski',
                    'amount'       => [
                        'currency_code' => 'CLP',
                        'value'         => (string) intval( $amount ),
                    ],
                ],
            ],
            'application_context' => [
                'return_url' => $return_url,
                'cancel_url' => $cancel_url,
                'brand_name' => 'Clasesdeski',
                'user_action' => 'PAY_NOW',
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
            return false;
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );

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
            wp_redirect( add_query_arg( [
                'cdski_status' => 'cancelled',
                'cdski_pid'    => $payment_id,
            ], home_url( '/resultado-pago/' ) ) );
            exit;
        }

        $paypal_token = sanitize_text_field( $_GET['token'] ?? '' );
        if ( ! $paypal_token ) {
            CDSKI_DB::update_payment( $payment_id, [ 'estado' => 'error' ] );
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
            $redirect_status = 'approved';
        } else {
            CDSKI_DB::update_payment( $payment_id, [ 'estado' => 'rejected' ] );
            $redirect_status = 'rejected';
        }

        wp_redirect( add_query_arg( [
            'cdski_status' => $redirect_status,
            'cdski_pid'    => $payment_id,
        ], home_url( '/resultado-pago/' ) ) );
        exit;
    }
}
