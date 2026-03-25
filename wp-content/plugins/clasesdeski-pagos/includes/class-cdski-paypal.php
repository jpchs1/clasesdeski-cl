<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CDSKI_PayPal {

    private static function business_email() {
        return 'contacto@imporlan.cl';
    }

    private static function is_sandbox() {
        return defined( 'CDSKI_PAYPAL_SANDBOX' ) && CDSKI_PAYPAL_SANDBOX;
    }

    private static function paypal_base() {
        return self::is_sandbox()
            ? 'https://www.sandbox.paypal.com'
            : 'https://www.paypal.com';
    }

    /**
     * Convert CLP to USD.
     */
    private static function clp_to_usd( $clp_amount ) {
        $rate = 950;
        $response = wp_remote_get( 'https://open.er-api.com/v6/latest/USD', [ 'timeout' => 5 ] );
        if ( ! is_wp_error( $response ) ) {
            $data = json_decode( wp_remote_retrieve_body( $response ), true );
            if ( ! empty( $data['rates']['CLP'] ) ) {
                $rate = floatval( $data['rates']['CLP'] );
            }
        }
        return max( round( $clp_amount / $rate, 2 ), 1.00 );
    }

    /**
     * Create PayPal payment link (xclick — supports guest checkout).
     */
    public static function create_order( $payment_id, $amount, $data ) {
        $usd_amount  = self::clp_to_usd( $amount );
        $description = $data['concepto'] ?: 'Clase de Ski - Clasesdeski';
        $return_url  = home_url( "/cdski-callback/paypal/{$payment_id}/" );
        $cancel_url  = home_url( "/cdski-callback/paypal/{$payment_id}/?cancelled=1" );
        $notify_url  = home_url( "/cdski-webhook/paypal/" );

        // Store the USD amount for reference
        CDSKI_DB::update_payment( $payment_id, [
            'transaction_id' => 'pending-' . $payment_id,
        ] );

        $params = [
            'cmd'            => '_xclick',
            'business'       => self::business_email(),
            'item_name'      => mb_substr( $description, 0, 127 ),
            'item_number'    => 'CDSKI-' . $payment_id,
            'amount'         => $usd_amount,
            'currency_code'  => 'USD',
            'no_shipping'    => '1',
            'no_note'        => '1',
            'return'         => $return_url,
            'cancel_return'  => $cancel_url,
            'notify_url'     => $notify_url,
            'custom'         => (string) $payment_id,
            'charset'        => 'utf-8',
            'lc'             => 'ES',
        ];

        return self::paypal_base() . '/cgi-bin/webscr?' . http_build_query( $params );
    }

    /**
     * Handle return from PayPal (user redirect back).
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

        // PayPal return — mark as pending (IPN will confirm)
        // If already approved by IPN, don't downgrade
        if ( $payment->estado !== 'approved' ) {
            CDSKI_DB::update_payment( $payment_id, [ 'estado' => 'pending' ] );
        }

        $status = $payment->estado === 'approved' ? 'approved' : 'pending';

        wp_redirect( add_query_arg( [
            'cdski_status' => $status,
            'cdski_pid'    => $payment_id,
        ], home_url( '/resultado-pago/' ) ) );
        exit;
    }

    /**
     * Handle PayPal IPN (Instant Payment Notification).
     */
    public static function handle_ipn( $input ) {
        // Verify with PayPal
        $verify_url = self::paypal_base() . '/cgi-bin/webscr';
        $verify_body = 'cmd=_notify-validate&' . $input;

        $response = wp_remote_post( $verify_url, [
            'body'    => $verify_body,
            'timeout' => 30,
        ] );

        if ( is_wp_error( $response ) ) {
            status_header( 500 );
            exit;
        }

        $verify_result = wp_remote_retrieve_body( $response );
        if ( $verify_result !== 'VERIFIED' ) {
            error_log( 'CDSKI PayPal IPN: not verified' );
            status_header( 200 );
            exit;
        }

        // Parse IPN data
        parse_str( $input, $ipn );

        $payment_id    = intval( $ipn['custom'] ?? 0 );
        $payment_status = strtolower( $ipn['payment_status'] ?? '' );
        $txn_id        = $ipn['txn_id'] ?? '';

        if ( ! $payment_id ) {
            status_header( 200 );
            exit;
        }

        $estado_map = [
            'completed' => 'approved',
            'pending'   => 'pending',
            'failed'    => 'rejected',
            'denied'    => 'rejected',
            'refunded'  => 'refunded',
            'reversed'  => 'refunded',
        ];

        $estado = $estado_map[ $payment_status ] ?? 'pending';

        CDSKI_DB::update_payment( $payment_id, [
            'estado'         => $estado,
            'transaction_id' => $txn_id,
        ] );

        cdski_pagos_after_status_change( $payment_id, $estado );

        status_header( 200 );
        echo 'OK';
        exit;
    }
}
