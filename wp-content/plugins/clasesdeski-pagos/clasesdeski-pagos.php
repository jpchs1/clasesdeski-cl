<?php
/**
 * Plugin Name: Clasesdeski Pagos
 * Description: Pago de monto libre para reservas de clases de ski/snowboard. Soporta PayPal, Mercado Pago y Webpay.
 * Version: 1.1.0
 * Author: Clasesdeski
 * Text Domain: cdski-pagos
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CDSKI_PAGOS_VERSION', '1.2.0' );
define( 'CDSKI_PAGOS_PATH', plugin_dir_path( __FILE__ ) );
define( 'CDSKI_PAGOS_URL', plugin_dir_url( __FILE__ ) );

// Includes
require_once CDSKI_PAGOS_PATH . 'includes/class-cdski-db.php';
require_once CDSKI_PAGOS_PATH . 'includes/class-cdski-emails.php';
require_once CDSKI_PAGOS_PATH . 'includes/class-cdski-paypal.php';
require_once CDSKI_PAGOS_PATH . 'includes/class-cdski-mercadopago.php';
require_once CDSKI_PAGOS_PATH . 'includes/class-cdski-webpay.php';
require_once CDSKI_PAGOS_PATH . 'includes/class-cdski-admin.php';

/**
 * Activation: create DB table.
 */
function cdski_pagos_activate() {
    CDSKI_DB::create_table();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'cdski_pagos_activate' );

/**
 * Enqueue frontend assets on pages with our shortcodes.
 */
function cdski_pagos_enqueue_assets() {
    if ( ! is_singular() ) {
        return;
    }
    global $post;
    if ( ! $post ) {
        return;
    }
    $has_form   = has_shortcode( $post->post_content, 'cdski_pago' );
    $has_result = has_shortcode( $post->post_content, 'cdski_pago_resultado' );
    if ( ! $has_form && ! $has_result ) {
        return;
    }
    wp_enqueue_style(
        'cdski-pagos',
        CDSKI_PAGOS_URL . 'assets/css/cdski-pagos.css',
        [],
        CDSKI_PAGOS_VERSION
    );
    if ( $has_form ) {
        // PayPal JS SDK for inline card fields
        $pp_client_id = defined( 'CDSKI_PAYPAL_CLIENT_ID' ) ? CDSKI_PAYPAL_CLIENT_ID : '';
        if ( $pp_client_id ) {
            wp_enqueue_script(
                'paypal-sdk',
                'https://www.paypal.com/sdk/js?client-id=' . $pp_client_id . '&currency=USD&intent=capture&components=buttons&disable-funding=paylater,venmo',
                [],
                null,
                true
            );
        }

        wp_enqueue_script(
            'cdski-pagos',
            CDSKI_PAGOS_URL . 'assets/js/cdski-pagos.js',
            $pp_client_id ? [ 'paypal-sdk' ] : [],
            CDSKI_PAGOS_VERSION,
            true
        );
        wp_localize_script( 'cdski-pagos', 'cdskiPagos', [
            'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
            'nonce'        => wp_create_nonce( 'cdski_pagos_nonce' ),
            'resultUrl'    => home_url( '/resultado-pago/' ),
            'hasPaypalSdk' => ! empty( $pp_client_id ),
        ] );
    }
}
add_action( 'wp_enqueue_scripts', 'cdski_pagos_enqueue_assets' );

/**
 * Shortcode [cdski_pago]
 */
function cdski_pago_shortcode( $atts ) {
    ob_start();
    include CDSKI_PAGOS_PATH . 'templates/payment-form.php';
    return ob_get_clean();
}
add_shortcode( 'cdski_pago', 'cdski_pago_shortcode' );

/**
 * Shortcode [cdski_pago_resultado]
 */
function cdski_pago_resultado_shortcode( $atts ) {
    ob_start();
    include CDSKI_PAGOS_PATH . 'templates/payment-result.php';
    return ob_get_clean();
}
add_shortcode( 'cdski_pago_resultado', 'cdski_pago_resultado_shortcode' );

/**
 * AJAX: Create payment and redirect to gateway.
 */
function cdski_pagos_create_payment() {
    check_ajax_referer( 'cdski_pagos_nonce', 'nonce' );

    $amount = floatval( sanitize_text_field( $_POST['amount'] ?? 0 ) );
    if ( $amount < 1000 ) {
        wp_send_json_error( [ 'message' => 'Monto inválido. Mínimo $1.000 CLP.' ] );
    }

    $gateway = sanitize_text_field( $_POST['gateway'] ?? '' );
    if ( ! in_array( $gateway, [ 'paypal', 'mercadopago', 'webpay' ], true ) ) {
        wp_send_json_error( [ 'message' => 'Medio de pago inválido.' ] );
    }

    $data = [
        'cliente'     => sanitize_text_field( $_POST['nombre'] ?? '' ),
        'email'       => sanitize_email( $_POST['email'] ?? '' ),
        'telefono'    => sanitize_text_field( $_POST['telefono'] ?? '' ),
        'reserva'     => sanitize_text_field( $_POST['reserva'] ?? '' ),
        'concepto'    => sanitize_text_field( $_POST['concepto'] ?? '' ),
        'fecha_clase' => sanitize_text_field( $_POST['fecha_clase'] ?? '' ),
        'monto'       => intval( $amount ),
        'gateway'     => $gateway,
        'estado'      => 'pending',
    ];

    $payment_id = CDSKI_DB::insert_payment( $data );
    if ( ! $payment_id ) {
        wp_send_json_error( [ 'message' => 'Error al registrar pago.' ] );
    }

    // PayPal uses JS SDK — just return payment_id, no server-side order creation here
    if ( $gateway === 'paypal' ) {
        wp_send_json_success( [ 'payment_id' => $payment_id ] );
    }

    // Webpay / Mercado Pago — create order and return redirect URL
    $redirect_url = '';
    switch ( $gateway ) {
        case 'mercadopago':
            $redirect_url = CDSKI_MercadoPago::create_preference( $payment_id, intval( $amount ), $data );
            break;
        case 'webpay':
            $redirect_url = CDSKI_Webpay::create_transaction( $payment_id, intval( $amount ), $data );
            break;
    }

    if ( ! $redirect_url ) {
        CDSKI_DB::update_payment( $payment_id, [ 'estado' => 'error' ] );
        wp_send_json_error( [ 'message' => 'Error al conectar con el medio de pago. Intenta con otro medio.' ] );
    }

    wp_send_json_success( [ 'redirect' => $redirect_url ] );
}
add_action( 'wp_ajax_cdski_create_payment', 'cdski_pagos_create_payment' );
add_action( 'wp_ajax_nopriv_cdski_create_payment', 'cdski_pagos_create_payment' );

/**
 * Robust callback handler — intercepts gateway returns early via init.
 * This avoids relying on rewrite rules which may not be flushed properly.
 */
function cdski_pagos_handle_callbacks() {
    $uri = trim( $_SERVER['REQUEST_URI'] ?? '', '/' );

    // Remove query string for matching
    $path = strtok( $uri, '?' );
    $path = trim( $path, '/' );

    // Match: cdski-callback/{gateway}/{payment_id}
    if ( preg_match( '#^cdski-callback/(paypal|mercadopago|webpay)/(\d+)#', $path, $m ) ) {
        // Bootstrap WordPress if needed
        $gateway    = $m[1];
        $payment_id = intval( $m[2] );

        switch ( $gateway ) {
            case 'paypal':
                CDSKI_PayPal::handle_callback( $payment_id );
                break;
            case 'mercadopago':
                CDSKI_MercadoPago::handle_callback( $payment_id );
                break;
            case 'webpay':
                CDSKI_Webpay::handle_callback( $payment_id );
                break;
        }
        exit;
    }

    // Match: cdski-webhook/{gateway}
    if ( preg_match( '#^cdski-webhook/(paypal|mercadopago|webpay)#', $path, $m ) ) {
        $gateway = $m[1];
        $input   = file_get_contents( 'php://input' );

        switch ( $gateway ) {
            case 'mercadopago':
                CDSKI_MercadoPago::handle_webhook_direct( $input );
                break;
        }
        exit;
    }
}
add_action( 'init', 'cdski_pagos_handle_callbacks', 1 );

/**
 * Send notification emails after payment status change.
 */
function cdski_pagos_after_status_change( $payment_id, $status ) {
    $payment = CDSKI_DB::get_payment( $payment_id );
    if ( ! $payment ) {
        return;
    }

    // Send email to client (if email provided)
    if ( ! empty( $payment->email ) ) {
        CDSKI_Emails::send_client_notification( $payment, $status );
    }

    // Always notify admin on approved or error
    if ( in_array( $status, [ 'approved', 'rejected', 'error' ], true ) ) {
        CDSKI_Emails::send_admin_notification( $payment, $status );
    }
}

/**
 * AJAX: Create PayPal order for JS SDK (returns order ID, not redirect).
 */
function cdski_paypal_create_order() {
    check_ajax_referer( 'cdski_pagos_nonce', 'nonce' );

    $payment_id = intval( $_POST['payment_id'] ?? 0 );
    $payment    = CDSKI_DB::get_payment( $payment_id );

    if ( ! $payment ) {
        wp_send_json_error( [ 'message' => 'Pago no encontrado.' ] );
    }

    $order_id = CDSKI_PayPal::create_order_for_js(
        $payment_id,
        intval( $payment->monto ),
        $payment->concepto
    );

    if ( ! $order_id ) {
        wp_send_json_error( [ 'message' => 'Error al crear orden PayPal.' ] );
    }

    wp_send_json_success( [ 'orderID' => $order_id ] );
}
add_action( 'wp_ajax_cdski_paypal_create_order', 'cdski_paypal_create_order' );
add_action( 'wp_ajax_nopriv_cdski_paypal_create_order', 'cdski_paypal_create_order' );

/**
 * AJAX: Capture PayPal order after JS SDK approval.
 */
function cdski_paypal_capture_order() {
    check_ajax_referer( 'cdski_pagos_nonce', 'nonce' );

    $payment_id = intval( $_POST['payment_id'] ?? 0 );
    $order_id   = sanitize_text_field( $_POST['orderID'] ?? '' );
    $payment    = CDSKI_DB::get_payment( $payment_id );

    if ( ! $payment || ! $order_id ) {
        wp_send_json_error( [ 'message' => 'Datos inválidos.' ] );
    }

    $result = CDSKI_PayPal::capture_order( $order_id );

    if ( $result['success'] ) {
        CDSKI_DB::update_payment( $payment_id, [
            'estado'         => 'approved',
            'transaction_id' => $result['capture_id'],
        ] );
        cdski_pagos_after_status_change( $payment_id, 'approved' );
        wp_send_json_success( [
            'status'  => 'approved',
            'redirect' => add_query_arg( [
                'cdski_status' => 'approved',
                'cdski_pid'    => $payment_id,
            ], home_url( '/resultado-pago/' ) ),
        ] );
    } else {
        CDSKI_DB::update_payment( $payment_id, [ 'estado' => 'rejected' ] );
        cdski_pagos_after_status_change( $payment_id, 'rejected' );
        wp_send_json_error( [
            'message'  => 'Pago rechazado.',
            'redirect' => add_query_arg( [
                'cdski_status' => 'rejected',
                'cdski_pid'    => $payment_id,
            ], home_url( '/resultado-pago/' ) ),
        ] );
    }
}
add_action( 'wp_ajax_cdski_paypal_capture_order', 'cdski_paypal_capture_order' );
add_action( 'wp_ajax_nopriv_cdski_paypal_capture_order', 'cdski_paypal_capture_order' );
