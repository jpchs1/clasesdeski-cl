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
            error_log( 'CDSKI MP: access token not configured' );
            return false;
        }

        $title = $data['concepto'] ?: 'Pago reserva clase - Clasesdeski';

        $preference = [
            'items' => [
                [
                    'title'       => mb_substr( $title, 0, 256 ),
                    'quantity'    => 1,
                    'currency_id' => 'CLP',
                    'unit_price'  => intval( $amount ),
                ],
            ],
            'back_urls' => [
                'success' => home_url( "/cdski-callback/mercadopago/{$payment_id}/" ),
                'failure' => home_url( "/cdski-callback/mercadopago/{$payment_id}/" ),
                'pending' => home_url( "/cdski-callback/mercadopago/{$payment_id}/" ),
            ],
            'auto_return'          => 'all',
            'external_reference'   => (string) $payment_id,
            'notification_url'     => home_url( "/cdski-webhook/mercadopago/" ),
            'statement_descriptor' => 'CLASESDESKI',
            'binary_mode'          => true,
        ];

        // Only add payer if we have valid email
        if ( ! empty( $data['email'] ) && is_email( $data['email'] ) ) {
            $preference['payer'] = [
                'email' => $data['email'],
            ];
            if ( ! empty( $data['cliente'] ) ) {
                $names = explode( ' ', trim( $data['cliente'] ), 2 );
                $preference['payer']['name']    = $names[0];
                $preference['payer']['surname'] = $names[1] ?? '';
            }
        }

        $response = wp_remote_post( 'https://api.mercadopago.com/checkout/preferences', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
            ],
            'body'    => wp_json_encode( $preference ),
            'timeout' => 30,
        ] );

        if ( is_wp_error( $response ) ) {
            error_log( 'CDSKI MP preference error: ' . $response->get_error_message() );
            return false;
        }

        $code = wp_remote_retrieve_response_code( $response );
        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( $code < 200 || $code >= 300 ) {
            error_log( 'CDSKI MP preference failed: HTTP ' . $code . ' - ' . wp_json_encode( $body ) );
            return false;
        }

        if ( ! empty( $body['id'] ) ) {
            CDSKI_DB::update_payment( $payment_id, [
                'transaction_id' => $body['id'],
            ] );
        }

        $url = $body['init_point'] ?? $body['sandbox_init_point'] ?? false;
        if ( ! $url ) {
            error_log( 'CDSKI MP: no init_point in response: ' . wp_json_encode( $body ) );
        }
        return $url;
    }

    /**
     * Handle return callback from Mercado Pago (user redirect back).
     */
    public static function handle_callback( $payment_id ) {
        $payment = CDSKI_DB::get_payment( $payment_id );
        if ( ! $payment ) {
            wp_die( 'Pago no encontrado.', 'Error', [ 'response' => 404 ] );
        }

        $mp_status     = sanitize_text_field( $_GET['collection_status'] ?? $_GET['status'] ?? '' );
        $mp_payment_id = sanitize_text_field( $_GET['collection_id'] ?? $_GET['payment_id'] ?? '' );

        $estado_map = [
            'approved'   => 'approved',
            'pending'    => 'pending',
            'in_process' => 'pending',
            'rejected'   => 'rejected',
            'cancelled'  => 'cancelled',
            'refunded'   => 'refunded',
            'null'       => 'pending',
        ];

        $estado = $estado_map[ $mp_status ] ?? 'pending';

        CDSKI_DB::update_payment( $payment_id, [
            'estado'         => $estado,
            'transaction_id' => $mp_payment_id ?: $payment->transaction_id,
        ] );

        cdski_pagos_after_status_change( $payment_id, $estado );

        wp_redirect( add_query_arg( [
            'cdski_status' => $estado,
            'cdski_pid'    => $payment_id,
        ], home_url( '/resultado-pago/' ) ) );
        exit;
    }

    /**
     * Handle IPN webhook from Mercado Pago (called directly, not through WP rewrite).
     */
    public static function handle_webhook_direct( $input ) {
        $data = json_decode( $input, true );

        if ( empty( $data['type'] ) || $data['type'] !== 'payment' ) {
            status_header( 200 );
            echo 'OK';
            exit;
        }

        $mp_payment_id = $data['data']['id'] ?? '';
        if ( ! $mp_payment_id ) {
            status_header( 200 );
            echo 'OK';
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

        $mp_data      = json_decode( wp_remote_retrieve_body( $response ), true );
        $external_ref = $mp_data['external_reference'] ?? '';
        $mp_status    = $mp_data['status'] ?? '';

        if ( ! $external_ref ) {
            status_header( 200 );
            echo 'OK';
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

        $estado     = $estado_map[ $mp_status ] ?? 'error';
        $payment_id = intval( $external_ref );

        CDSKI_DB::update_payment( $payment_id, [
            'estado'         => $estado,
            'transaction_id' => (string) $mp_payment_id,
        ] );

        cdski_pagos_after_status_change( $payment_id, $estado );

        status_header( 200 );
        echo 'OK';
        exit;
    }
}
