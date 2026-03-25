<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CDSKI_MercadoPago {

    private static function access_token() {
        return defined( 'CDSKI_MP_ACCESS_TOKEN' ) ? CDSKI_MP_ACCESS_TOKEN : '';
    }

    /**
     * Create Mercado Pago preference and return checkout URL.
     */
    public static function create_preference( $payment_id, $amount, $data ) {
        $token = self::access_token();
        if ( ! $token ) {
            return false;
        }

        $preference = [
            'items' => [
                [
                    'title'       => $data['concepto'] ?: 'Pago reserva clase - Clasesdeski',
                    'quantity'    => 1,
                    'currency_id' => 'CLP',
                    'unit_price'  => intval( $amount ),
                ],
            ],
            'payer' => [
                'name'  => $data['cliente'] ?: '',
                'email' => $data['email'] ?: '',
            ],
            'back_urls' => [
                'success' => home_url( "/cdski-callback/mercadopago/{$payment_id}/" ),
                'failure' => home_url( "/cdski-callback/mercadopago/{$payment_id}/" ),
                'pending' => home_url( "/cdski-callback/mercadopago/{$payment_id}/" ),
            ],
            'auto_return'     => 'approved',
            'external_reference' => (string) $payment_id,
            'notification_url'   => home_url( "/cdski-webhook/mercadopago/" ),
        ];

        $response = wp_remote_post( 'https://api.mercadopago.com/checkout/preferences', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
            ],
            'body'    => wp_json_encode( $preference ),
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

        // Use sandbox or production init point
        return $body['init_point'] ?? $body['sandbox_init_point'] ?? false;
    }

    /**
     * Handle return callback from Mercado Pago.
     */
    public static function handle_callback( $payment_id ) {
        // Check if this is a webhook notification (IPN)
        $input = file_get_contents( 'php://input' );
        if ( $input ) {
            self::handle_webhook( $input );
            return;
        }

        $payment = CDSKI_DB::get_payment( $payment_id );
        if ( ! $payment ) {
            wp_die( 'Pago no encontrado.', 'Error', [ 'response' => 404 ] );
        }

        $mp_status     = sanitize_text_field( $_GET['collection_status'] ?? $_GET['status'] ?? '' );
        $mp_payment_id = sanitize_text_field( $_GET['collection_id'] ?? $_GET['payment_id'] ?? '' );

        $estado_map = [
            'approved' => 'approved',
            'pending'  => 'pending',
            'in_process' => 'pending',
            'rejected' => 'rejected',
            'cancelled' => 'cancelled',
            'refunded' => 'refunded',
        ];

        $estado = $estado_map[ $mp_status ] ?? 'error';

        CDSKI_DB::update_payment( $payment_id, [
            'estado'         => $estado,
            'transaction_id' => $mp_payment_id ?: $payment->transaction_id,
        ] );

        wp_redirect( add_query_arg( [
            'cdski_status' => $estado,
            'cdski_pid'    => $payment_id,
        ], home_url( '/resultado-pago/' ) ) );
        exit;
    }

    /**
     * Handle IPN webhook from Mercado Pago.
     */
    private static function handle_webhook( $input ) {
        $data = json_decode( $input, true );
        if ( empty( $data['type'] ) || $data['type'] !== 'payment' ) {
            status_header( 200 );
            exit;
        }

        $mp_payment_id = $data['data']['id'] ?? '';
        if ( ! $mp_payment_id ) {
            status_header( 200 );
            exit;
        }

        // Fetch payment details from MP API
        $response = wp_remote_get( "https://api.mercadopago.com/v1/payments/{$mp_payment_id}", [
            'headers' => [
                'Authorization' => 'Bearer ' . self::access_token(),
            ],
            'timeout' => 30,
        ] );

        if ( is_wp_error( $response ) ) {
            status_header( 500 );
            exit;
        }

        $mp_data = json_decode( wp_remote_retrieve_body( $response ), true );
        $external_ref = $mp_data['external_reference'] ?? '';
        $mp_status    = $mp_data['status'] ?? '';

        if ( ! $external_ref ) {
            status_header( 200 );
            exit;
        }

        $estado_map = [
            'approved'   => 'approved',
            'pending'    => 'pending',
            'in_process' => 'pending',
            'rejected'   => 'rejected',
            'cancelled'  => 'cancelled',
            'refunded'   => 'refunded',
        ];

        CDSKI_DB::update_payment( intval( $external_ref ), [
            'estado'         => $estado_map[ $mp_status ] ?? 'error',
            'transaction_id' => (string) $mp_payment_id,
        ] );

        status_header( 200 );
        exit;
    }
}
