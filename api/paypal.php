<?php
/**
 * PayPal Smart Buttons API endpoint.
 *
 * GET  ?action=get_client_id  → { client_id, environment }
 * POST ?action=create_order   → { success, order_id }
 * POST ?action=capture_order  → { success }
 */

// Bootstrap WordPress (provides constants from wp-config.php).
define( 'SHORTINIT', true );
require_once dirname( __DIR__ ) . '/wp-load.php';

header( 'Content-Type: application/json; charset=utf-8' );

// ── Helpers ─────────────────────────────────────────────

function pp_client_id() {
    return defined( 'CDSKI_PAYPAL_CLIENT_ID' ) ? CDSKI_PAYPAL_CLIENT_ID : '';
}

function pp_secret() {
    return defined( 'CDSKI_PAYPAL_SECRET' ) ? CDSKI_PAYPAL_SECRET : '';
}

function pp_is_sandbox() {
    return defined( 'CDSKI_PAYPAL_SANDBOX' ) && CDSKI_PAYPAL_SANDBOX;
}

function pp_api_base() {
    return pp_is_sandbox()
        ? 'https://api-m.sandbox.paypal.com'
        : 'https://api-m.paypal.com';
}

function pp_environment() {
    return pp_is_sandbox() ? 'sandbox' : 'production';
}

/**
 * Obtain an OAuth 2.0 access token from PayPal.
 */
function pp_get_access_token() {
    $ch = curl_init( pp_api_base() . '/v1/oauth2/token' );
    curl_setopt_array( $ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => 'grant_type=client_credentials',
        CURLOPT_USERPWD        => pp_client_id() . ':' . pp_secret(),
        CURLOPT_HTTPHEADER     => [ 'Content-Type: application/x-www-form-urlencoded' ],
        CURLOPT_TIMEOUT        => 15,
    ] );
    $body = curl_exec( $ch );
    $code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
    curl_close( $ch );

    if ( $code !== 200 ) {
        return false;
    }
    $data = json_decode( $body, true );
    return $data['access_token'] ?? false;
}

/**
 * Generic PayPal REST call.
 */
function pp_request( $method, $path, $payload = null ) {
    $token = pp_get_access_token();
    if ( ! $token ) {
        return [ 'error' => 'No se pudo autenticar con PayPal.' ];
    }

    $url = pp_api_base() . $path;
    $ch  = curl_init( $url );

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token,
    ];

    $opts = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_TIMEOUT        => 30,
    ];

    if ( $method === 'POST' ) {
        $opts[ CURLOPT_POST ]       = true;
        $opts[ CURLOPT_POSTFIELDS ] = json_encode( $payload );
    }

    curl_setopt_array( $ch, $opts );
    $body = curl_exec( $ch );
    $code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
    curl_close( $ch );

    return [
        'status' => $code,
        'body'   => json_decode( $body, true ),
    ];
}

// ── Router ──────────────────────────────────────────────

$action = $_GET['action'] ?? '';

switch ( $action ) {

    // ─── get_client_id ──────────────────────────────────
    case 'get_client_id':
        echo json_encode( [
            'client_id'   => pp_client_id(),
            'environment' => pp_environment(),
        ] );
        break;

    // ─── create_order ───────────────────────────────────
    case 'create_order':
        $input = json_decode( file_get_contents( 'php://input' ), true );
        if ( ! $input ) {
            http_response_code( 400 );
            echo json_encode( [ 'success' => false, 'error' => 'JSON inválido.' ] );
            break;
        }

        $amount      = floatval( $input['amount'] ?? 0 );
        $currency    = strtoupper( $input['currency'] ?? 'USD' );
        $description = substr( $input['description'] ?? 'Pago Clasesdeski', 0, 127 );

        if ( $amount < 1 ) {
            http_response_code( 400 );
            echo json_encode( [ 'success' => false, 'error' => 'Monto inválido.' ] );
            break;
        }

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

        $res = pp_request( 'POST', '/v2/checkout/orders', $order_payload );

        if ( isset( $res['body']['id'] ) && in_array( $res['body']['status'], [ 'CREATED', 'APPROVED' ], true ) ) {
            echo json_encode( [ 'success' => true, 'order_id' => $res['body']['id'] ] );
        } else {
            http_response_code( 500 );
            $err = $res['body']['message'] ?? 'Error al crear la orden en PayPal.';
            echo json_encode( [ 'success' => false, 'error' => $err ] );
        }
        break;

    // ─── capture_order ──────────────────────────────────
    case 'capture_order':
        $input = json_decode( file_get_contents( 'php://input' ), true );
        $order_id = $input['order_id'] ?? '';

        if ( ! $order_id ) {
            http_response_code( 400 );
            echo json_encode( [ 'success' => false, 'error' => 'order_id requerido.' ] );
            break;
        }

        $res = pp_request( 'POST', '/v2/checkout/orders/' . $order_id . '/capture', new stdClass() );

        if ( isset( $res['body']['status'] ) && $res['body']['status'] === 'COMPLETED' ) {
            echo json_encode( [ 'success' => true ] );
        } else {
            http_response_code( 500 );
            $err = $res['body']['message'] ?? 'Error al capturar el pago.';
            echo json_encode( [ 'success' => false, 'error' => $err ] );
        }
        break;

    default:
        http_response_code( 400 );
        echo json_encode( [ 'error' => 'Acción no válida.' ] );
        break;
}
