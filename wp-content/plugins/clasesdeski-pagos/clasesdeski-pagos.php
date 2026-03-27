<?php
/**
 * Plugin Name: Clasesdeski Pagos
 * Description: Pago de monto libre para reservas de clases de ski/snowboard. Soporta PayPal, Mercado Pago y Webpay.
 * Version: 1.4.0
 * Author: Clasesdeski
 * Text Domain: cdski-pagos
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CDSKI_PAGOS_VERSION', '1.4.0' );
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
 * Enqueue frontend assets.
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
            'siteUrl' => home_url(),
        ] );
    }
}
add_action( 'wp_enqueue_scripts', 'cdski_pagos_enqueue_assets' );

/**
 * Shortcodes
 */
function cdski_pago_shortcode( $atts ) {
    ob_start();
    include CDSKI_PAGOS_PATH . 'templates/payment-form.php';
    return ob_get_clean();
}
add_shortcode( 'cdski_pago', 'cdski_pago_shortcode' );

function cdski_pago_resultado_shortcode( $atts ) {
    ob_start();
    include CDSKI_PAGOS_PATH . 'templates/payment-result.php';
    return ob_get_clean();
}
add_shortcode( 'cdski_pago_resultado', 'cdski_pago_resultado_shortcode' );

/**
 * AJAX: Create payment and redirect to gateway.
 * All 3 gateways use redirect flow.
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

    $redirect_url = '';
    switch ( $gateway ) {
        case 'paypal':
            $redirect_url = CDSKI_PayPal::create_order( $payment_id, intval( $amount ), $data );
            break;
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
 * Callback handler via init (no rewrite rules needed).
 */
function cdski_pagos_handle_callbacks() {
    $path = trim( strtok( $_SERVER['REQUEST_URI'] ?? '', '?' ), '/' );

    if ( preg_match( '#^cdski-callback/(paypal|mercadopago|webpay)/(\d+)#', $path, $m ) ) {
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

    if ( preg_match( '#^cdski-webhook/(mercadopago|paypal)#', $path, $m ) ) {
        $input = file_get_contents( 'php://input' );
        if ( $m[1] === 'mercadopago' ) {
            CDSKI_MercadoPago::handle_webhook_direct( $input );
        } elseif ( $m[1] === 'paypal' ) {
            CDSKI_PayPal::handle_ipn( $input );
        }
        exit;
    }
}
add_action( 'init', 'cdski_pagos_handle_callbacks', 1 );

/**
 * AJAX: PayPal Smart Buttons — get client ID.
 */
function cdski_paypal_get_client_id() {
    $client_id   = defined( 'CDSKI_PAYPAL_CLIENT_ID' ) ? CDSKI_PAYPAL_CLIENT_ID : '';
    $environment = ( defined( 'CDSKI_PAYPAL_SANDBOX' ) && CDSKI_PAYPAL_SANDBOX ) ? 'sandbox' : 'production';
    wp_send_json( [ 'client_id' => $client_id, 'environment' => $environment ] );
}
add_action( 'wp_ajax_cdski_paypal_get_client_id', 'cdski_paypal_get_client_id' );
add_action( 'wp_ajax_nopriv_cdski_paypal_get_client_id', 'cdski_paypal_get_client_id' );

/**
 * AJAX: PayPal Smart Buttons — create order via REST API v2.
 */
function cdski_paypal_create_order() {
    $input = json_decode( file_get_contents( 'php://input' ), true );
    if ( ! $input ) {
        wp_send_json( [ 'success' => false, 'error' => 'JSON inválido.' ], 400 );
    }

    $amount      = floatval( $input['amount'] ?? 0 );
    $currency    = strtoupper( $input['currency'] ?? 'USD' );
    $description = mb_substr( $input['description'] ?? 'Pago Clasesdeski', 0, 127 );

    if ( $amount < 1 ) {
        wp_send_json( [ 'success' => false, 'error' => 'Monto inválido.' ], 400 );
    }

    $token = cdski_paypal_get_access_token();
    if ( ! $token ) {
        wp_send_json( [ 'success' => false, 'error' => 'No se pudo autenticar con PayPal.' ], 500 );
    }

    $api_base = ( defined( 'CDSKI_PAYPAL_SANDBOX' ) && CDSKI_PAYPAL_SANDBOX )
        ? 'https://api-m.sandbox.paypal.com'
        : 'https://api-m.paypal.com';

    $order_payload = [
        'intent'         => 'CAPTURE',
        'purchase_units' => [
            [
                'amount'      => [
                    'currency_code' => $currency,
                    'value'         => number_format( $amount, 2, '.', '' ),
                ],
                'description' => $description,
            ],
        ],
        'application_context' => [
            'brand_name'          => 'Clasesdeski',
            'shipping_preference' => 'NO_SHIPPING',
            'user_action'         => 'PAY_NOW',
        ],
    ];

    $response = wp_remote_post( $api_base . '/v2/checkout/orders', [
        'headers' => [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ],
        'body'    => wp_json_encode( $order_payload ),
        'timeout' => 30,
    ] );

    if ( is_wp_error( $response ) ) {
        wp_send_json( [ 'success' => false, 'error' => 'Error de conexión con PayPal.' ], 500 );
    }

    $body = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( ! empty( $body['id'] ) && in_array( $body['status'] ?? '', [ 'CREATED', 'APPROVED' ], true ) ) {
        wp_send_json( [ 'success' => true, 'order_id' => $body['id'] ] );
    } else {
        $err = $body['message'] ?? 'Error al crear la orden en PayPal.';
        wp_send_json( [ 'success' => false, 'error' => $err ], 500 );
    }
}
add_action( 'wp_ajax_cdski_paypal_create_order', 'cdski_paypal_create_order' );
add_action( 'wp_ajax_nopriv_cdski_paypal_create_order', 'cdski_paypal_create_order' );

/**
 * AJAX: PayPal Smart Buttons — capture order.
 */
function cdski_paypal_capture_order() {
    $input    = json_decode( file_get_contents( 'php://input' ), true );
    $order_id = sanitize_text_field( $input['order_id'] ?? '' );

    if ( ! $order_id ) {
        wp_send_json( [ 'success' => false, 'error' => 'order_id requerido.' ], 400 );
    }

    $token = cdski_paypal_get_access_token();
    if ( ! $token ) {
        wp_send_json( [ 'success' => false, 'error' => 'No se pudo autenticar con PayPal.' ], 500 );
    }

    $api_base = ( defined( 'CDSKI_PAYPAL_SANDBOX' ) && CDSKI_PAYPAL_SANDBOX )
        ? 'https://api-m.sandbox.paypal.com'
        : 'https://api-m.paypal.com';

    $response = wp_remote_post( $api_base . '/v2/checkout/orders/' . $order_id . '/capture', [
        'headers' => [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ],
        'body'    => '{}',
        'timeout' => 30,
    ] );

    if ( is_wp_error( $response ) ) {
        wp_send_json( [ 'success' => false, 'error' => 'Error de conexión con PayPal.' ], 500 );
    }

    $body = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( ( $body['status'] ?? '' ) === 'COMPLETED' ) {
        wp_send_json( [ 'success' => true ] );
    } else {
        $err = $body['message'] ?? 'Error al capturar el pago.';
        wp_send_json( [ 'success' => false, 'error' => $err ], 500 );
    }
}
add_action( 'wp_ajax_cdski_paypal_capture_order', 'cdski_paypal_capture_order' );
add_action( 'wp_ajax_nopriv_cdski_paypal_capture_order', 'cdski_paypal_capture_order' );

/**
 * Helper: get PayPal OAuth 2.0 access token.
 */
function cdski_paypal_get_access_token() {
    $client_id = defined( 'CDSKI_PAYPAL_CLIENT_ID' ) ? CDSKI_PAYPAL_CLIENT_ID : '';
    $secret    = defined( 'CDSKI_PAYPAL_SECRET' ) ? CDSKI_PAYPAL_SECRET : '';
    if ( ! $client_id || ! $secret ) {
        return false;
    }

    $api_base = ( defined( 'CDSKI_PAYPAL_SANDBOX' ) && CDSKI_PAYPAL_SANDBOX )
        ? 'https://api-m.sandbox.paypal.com'
        : 'https://api-m.paypal.com';

    $response = wp_remote_post( $api_base . '/v1/oauth2/token', [
        'headers' => [
            'Authorization' => 'Basic ' . base64_encode( $client_id . ':' . $secret ),
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ],
        'body'    => 'grant_type=client_credentials',
        'timeout' => 15,
    ] );

    if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
        return false;
    }

    $data = json_decode( wp_remote_retrieve_body( $response ), true );
    return $data['access_token'] ?? false;
}

/**
 * Send notification emails after payment status change.
 */
function cdski_pagos_after_status_change( $payment_id, $status ) {
    $payment = CDSKI_DB::get_payment( $payment_id );
    if ( ! $payment ) {
        return;
    }

    if ( ! empty( $payment->email ) ) {
        CDSKI_Emails::send_client_notification( $payment, $status );
    }

    if ( in_array( $status, [ 'approved', 'rejected', 'error' ], true ) ) {
        CDSKI_Emails::send_admin_notification( $payment, $status );
    }
}
