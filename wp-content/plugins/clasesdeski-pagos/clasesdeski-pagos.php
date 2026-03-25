<?php
/**
 * Plugin Name: Clasesdeski Pagos
 * Description: Pago de monto libre para reservas de clases de ski/snowboard. Soporta PayPal, Mercado Pago y Webpay.
 * Version: 1.0.0
 * Author: Clasesdeski
 * Text Domain: cdski-pagos
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CDSKI_PAGOS_VERSION', '1.0.0' );
define( 'CDSKI_PAGOS_PATH', plugin_dir_path( __FILE__ ) );
define( 'CDSKI_PAGOS_URL', plugin_dir_url( __FILE__ ) );

// Includes
require_once CDSKI_PAGOS_PATH . 'includes/class-cdski-db.php';
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
 * Enqueue frontend assets.
 */
function cdski_pagos_enqueue_assets() {
    if ( ! is_singular() ) {
        return;
    }
    global $post;
    if ( ! $post || ! has_shortcode( $post->post_content, 'cdski_pago' ) ) {
        return;
    }
    wp_enqueue_style(
        'cdski-pagos',
        CDSKI_PAGOS_URL . 'assets/css/cdski-pagos.css',
        [],
        CDSKI_PAGOS_VERSION
    );
    wp_enqueue_script(
        'cdski-pagos',
        CDSKI_PAGOS_URL . 'assets/js/cdski-pagos.js',
        [],
        CDSKI_PAGOS_VERSION,
        true
    );
    wp_localize_script( 'cdski-pagos', 'cdskiPagos', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'cdski_pagos_nonce' ),
    ] );
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
 * AJAX: Create payment and redirect to gateway.
 */
function cdski_pagos_create_payment() {
    check_ajax_referer( 'cdski_pagos_nonce', 'nonce' );

    $amount = floatval( sanitize_text_field( $_POST['amount'] ?? 0 ) );
    if ( $amount <= 0 ) {
        wp_send_json_error( [ 'message' => 'Monto inválido.' ] );
    }

    $gateway = sanitize_text_field( $_POST['gateway'] ?? '' );
    if ( ! in_array( $gateway, [ 'paypal', 'mercadopago', 'webpay' ], true ) ) {
        wp_send_json_error( [ 'message' => 'Medio de pago inválido.' ] );
    }

    $data = [
        'cliente'    => sanitize_text_field( $_POST['nombre'] ?? '' ),
        'email'      => sanitize_email( $_POST['email'] ?? '' ),
        'telefono'   => sanitize_text_field( $_POST['telefono'] ?? '' ),
        'reserva'    => sanitize_text_field( $_POST['reserva'] ?? '' ),
        'concepto'   => sanitize_text_field( $_POST['concepto'] ?? '' ),
        'fecha_clase' => sanitize_text_field( $_POST['fecha_clase'] ?? '' ),
        'monto'      => $amount,
        'gateway'    => $gateway,
        'estado'     => 'pending',
    ];

    $payment_id = CDSKI_DB::insert_payment( $data );
    if ( ! $payment_id ) {
        wp_send_json_error( [ 'message' => 'Error al registrar pago.' ] );
    }

    $redirect_url = '';
    switch ( $gateway ) {
        case 'paypal':
            $redirect_url = CDSKI_PayPal::create_order( $payment_id, $amount, $data );
            break;
        case 'mercadopago':
            $redirect_url = CDSKI_MercadoPago::create_preference( $payment_id, $amount, $data );
            break;
        case 'webpay':
            $redirect_url = CDSKI_Webpay::create_transaction( $payment_id, $amount, $data );
            break;
    }

    if ( ! $redirect_url ) {
        CDSKI_DB::update_payment( $payment_id, [ 'estado' => 'error' ] );
        wp_send_json_error( [ 'message' => 'Error al conectar con el medio de pago.' ] );
    }

    wp_send_json_success( [ 'redirect' => $redirect_url ] );
}
add_action( 'wp_ajax_cdski_create_payment', 'cdski_pagos_create_payment' );
add_action( 'wp_ajax_nopriv_cdski_create_payment', 'cdski_pagos_create_payment' );

/**
 * Handle gateway callbacks via query vars.
 */
function cdski_pagos_query_vars( $vars ) {
    $vars[] = 'cdski_callback';
    $vars[] = 'cdski_gateway';
    $vars[] = 'cdski_payment_id';
    return $vars;
}
add_filter( 'query_vars', 'cdski_pagos_query_vars' );

function cdski_pagos_parse_request( $wp ) {
    if ( ! isset( $wp->query_vars['cdski_callback'] ) ) {
        return;
    }

    $gateway    = sanitize_text_field( $wp->query_vars['cdski_gateway'] ?? '' );
    $payment_id = intval( $wp->query_vars['cdski_payment_id'] ?? 0 );

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
}
add_action( 'parse_request', 'cdski_pagos_parse_request' );

function cdski_pagos_rewrite_rules() {
    add_rewrite_rule(
        '^cdski-callback/([a-z]+)/([0-9]+)/?$',
        'index.php?cdski_callback=1&cdski_gateway=$matches[1]&cdski_payment_id=$matches[2]',
        'top'
    );
    // Webhook endpoint (no payment_id in URL)
    add_rewrite_rule(
        '^cdski-webhook/([a-z]+)/?$',
        'index.php?cdski_callback=webhook&cdski_gateway=$matches[1]',
        'top'
    );
}
add_action( 'init', 'cdski_pagos_rewrite_rules' );

/**
 * Result page shortcode [cdski_pago_resultado]
 */
function cdski_pago_resultado_shortcode( $atts ) {
    ob_start();
    include CDSKI_PAGOS_PATH . 'templates/payment-result.php';
    return ob_get_clean();
}
add_shortcode( 'cdski_pago_resultado', 'cdski_pago_resultado_shortcode' );
