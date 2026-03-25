<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CDSKI_Webpay {

    private static function commerce_code() {
        return defined( 'CDSKI_WEBPAY_COMMERCE_CODE' ) ? CDSKI_WEBPAY_COMMERCE_CODE : '';
    }

    private static function api_key() {
        return defined( 'CDSKI_WEBPAY_API_KEY' ) ? CDSKI_WEBPAY_API_KEY : '';
    }

    private static function is_sandbox() {
        return defined( 'CDSKI_WEBPAY_SANDBOX' ) && CDSKI_WEBPAY_SANDBOX;
    }

    private static function api_base() {
        return self::is_sandbox()
            ? 'https://webpay3gint.transbank.cl/rswebpaytransaction/api/webpay/v1.2'
            : 'https://webpay3g.transbank.cl/rswebpaytransaction/api/webpay/v1.2';
    }

    private static function headers() {
        if ( self::is_sandbox() ) {
            return [
                'Tbk-Api-Key-Id'     => '597055555532',
                'Tbk-Api-Key-Secret' => '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C',
                'Content-Type'       => 'application/json',
            ];
        }
        return [
            'Tbk-Api-Key-Id'     => self::commerce_code(),
            'Tbk-Api-Key-Secret' => self::api_key(),
            'Content-Type'       => 'application/json',
        ];
    }

    /**
     * Create Webpay Plus transaction.
     */
    public static function create_transaction( $payment_id, $amount, $data ) {
        $return_url = home_url( "/cdski-callback/webpay/{$payment_id}/" );

        $buy_order  = 'CDSKI-' . $payment_id . '-' . time();
        $session_id = 'S-' . $payment_id;

        $body = [
            'buy_order'  => substr( $buy_order, 0, 26 ),
            'session_id' => substr( $session_id, 0, 61 ),
            'amount'     => intval( $amount ),
            'return_url' => $return_url,
        ];

        $response = wp_remote_post( self::api_base() . '/transactions', [
            'headers' => self::headers(),
            'body'    => wp_json_encode( $body ),
            'timeout' => 30,
        ] );

        if ( is_wp_error( $response ) ) {
            error_log( 'CDSKI Webpay create error: ' . $response->get_error_message() );
            return false;
        }

        $result = json_decode( wp_remote_retrieve_body( $response ), true );

        $token = $result['token'] ?? '';
        $url   = $result['url'] ?? '';

        if ( ! $token || ! $url ) {
            error_log( 'CDSKI Webpay create failed: ' . wp_remote_retrieve_body( $response ) );
            return false;
        }

        CDSKI_DB::update_payment( $payment_id, [
            'transaction_id' => $token,
        ] );

        // Webpay requires POST redirect with token_ws
        return $url . '?token_ws=' . $token;
    }

    /**
     * Handle Webpay return callback — confirm transaction.
     */
    public static function handle_callback( $payment_id ) {
        $payment = CDSKI_DB::get_payment( $payment_id );
        if ( ! $payment ) {
            wp_die( 'Pago no encontrado.', 'Error', [ 'response' => 404 ] );
        }

        // Webpay sends token_ws via POST or GET
        $token_ws = sanitize_text_field( $_POST['token_ws'] ?? $_GET['token_ws'] ?? '' );

        // If TBK_TOKEN is present, it means the user aborted
        $tbk_token = sanitize_text_field( $_POST['TBK_TOKEN'] ?? $_GET['TBK_TOKEN'] ?? '' );

        if ( $tbk_token || empty( $token_ws ) ) {
            CDSKI_DB::update_payment( $payment_id, [ 'estado' => 'cancelled' ] );
            cdski_pagos_after_status_change( $payment_id, 'cancelled' );
            wp_redirect( add_query_arg( [
                'cdski_status' => 'cancelled',
                'cdski_pid'    => $payment_id,
            ], home_url( '/resultado-pago/' ) ) );
            exit;
        }

        // Confirm transaction with Transbank
        $response = wp_remote_request( self::api_base() . "/transactions/{$token_ws}", [
            'method'  => 'PUT',
            'headers' => self::headers(),
            'timeout' => 30,
        ] );

        if ( is_wp_error( $response ) ) {
            error_log( 'CDSKI Webpay confirm error: ' . $response->get_error_message() );
            CDSKI_DB::update_payment( $payment_id, [ 'estado' => 'error' ] );
            cdski_pagos_after_status_change( $payment_id, 'error' );
            wp_redirect( add_query_arg( [
                'cdski_status' => 'error',
                'cdski_pid'    => $payment_id,
            ], home_url( '/resultado-pago/' ) ) );
            exit;
        }

        $result = json_decode( wp_remote_retrieve_body( $response ), true );

        $response_code = $result['response_code'] ?? -1;
        $tbk_status    = $result['status'] ?? '';

        if ( $response_code === 0 && $tbk_status === 'AUTHORIZED' ) {
            CDSKI_DB::update_payment( $payment_id, [
                'estado'         => 'approved',
                'transaction_id' => $result['authorization_code'] ?? $token_ws,
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
