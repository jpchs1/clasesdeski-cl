<?php
/**
 * ClasesdeSki — PayPal Smart Buttons API endpoint.
 *
 * Endpoints:
 *   GET  ?action=get_client_id   → { client_id, environment }
 *   POST ?action=create_order    → { success, order_id }
 *   POST ?action=capture_order   → { success, status, payer_email, amount }
 *
 * Reads credentials from api/config.php (shared with Tourevo merchant).
 * Compatible with PAYPAL_LIVE_* / PAYPAL_SANDBOX_* constants.
 *
 * Required config.php constants:
 *   PAYMENT_ENVIRONMENT     ('production' | 'sandbox')
 *   PAYPAL_LIVE_CLIENT_ID, PAYPAL_LIVE_SECRET, PAYPAL_LIVE_URL
 *   PAYPAL_SANDBOX_CLIENT_ID, PAYPAL_SANDBOX_SECRET, PAYPAL_SANDBOX_URL
 */

require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');

function pp_is_sandbox() {
    return defined('PAYMENT_ENVIRONMENT') && PAYMENT_ENVIRONMENT === 'sandbox';
}

function pp_creds() {
    if (pp_is_sandbox()) {
        return [
            'client_id' => defined('PAYPAL_SANDBOX_CLIENT_ID') ? PAYPAL_SANDBOX_CLIENT_ID : '',
            'secret'    => defined('PAYPAL_SANDBOX_SECRET')    ? PAYPAL_SANDBOX_SECRET    : '',
            'url'       => defined('PAYPAL_SANDBOX_URL')       ? PAYPAL_SANDBOX_URL       : 'https://api-m.sandbox.paypal.com',
            'env'       => 'sandbox',
        ];
    }
    return [
        'client_id' => defined('PAYPAL_LIVE_CLIENT_ID') ? PAYPAL_LIVE_CLIENT_ID : '',
        'secret'    => defined('PAYPAL_LIVE_SECRET')    ? PAYPAL_LIVE_SECRET    : '',
        'url'       => defined('PAYPAL_LIVE_URL')       ? PAYPAL_LIVE_URL       : 'https://api-m.paypal.com',
        'env'       => 'production',
    ];
}

function pp_get_access_token() {
    $c = pp_creds();
    if (!$c['client_id'] || !$c['secret']) return false;

    $ch = curl_init($c['url'] . '/v1/oauth2/token');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => 'grant_type=client_credentials',
        CURLOPT_USERPWD        => $c['client_id'] . ':' . $c['secret'],
        CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
        CURLOPT_TIMEOUT        => 15,
    ]);
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code !== 200) return false;
    $data = json_decode($body, true);
    return $data['access_token'] ?? false;
}

function pp_request($method, $path, $payload = null) {
    $token = pp_get_access_token();
    if (!$token) {
        return ['status' => 0, 'body' => ['error' => 'No se pudo autenticar con PayPal.']];
    }
    $c = pp_creds();
    $ch = curl_init($c['url'] . $path);
    $opts = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ],
        CURLOPT_TIMEOUT        => 30,
    ];
    if ($method === 'POST') {
        $opts[CURLOPT_POST]       = true;
        $opts[CURLOPT_POSTFIELDS] = json_encode($payload ?: new stdClass());
    }
    curl_setopt_array($ch, $opts);
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['status' => $code, 'body' => json_decode($body, true)];
}

$action = $_GET['action'] ?? '';

switch ($action) {

    case 'get_client_id':
        $c = pp_creds();
        echo json_encode([
            'client_id'   => $c['client_id'],
            'environment' => $c['env'],
        ]);
        break;

    case 'create_order':
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'JSON inválido.']);
            break;
        }

        $amount      = floatval($input['amount'] ?? 0);
        $currency    = strtoupper($input['currency'] ?? 'USD');
        $description = substr($input['description'] ?? 'Reserva CDSKI', 0, 127);
        $booking     = substr($input['booking_code'] ?? '', 0, 80);

        if ($amount < 1) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Monto inválido.']);
            break;
        }

        $allowed_currencies = ['USD', 'EUR', 'BRL', 'CAD', 'GBP', 'MXN'];
        if (!in_array($currency, $allowed_currencies, true)) {
            $currency = 'USD';
        }

        $purchase_unit = [
            'amount' => [
                'currency_code' => $currency,
                'value'         => number_format($amount, 2, '.', ''),
            ],
            'description' => $description,
        ];
        if ($booking) {
            $purchase_unit['custom_id']    = $booking;
            $purchase_unit['invoice_id']   = 'CDSKI-' . preg_replace('/[^A-Za-z0-9-]/', '', $booking);
        }

        $res = pp_request('POST', '/v2/checkout/orders', [
            'intent'         => 'CAPTURE',
            'purchase_units' => [$purchase_unit],
            'application_context' => [
                'brand_name'          => 'CDSKI Chile',
                'shipping_preference' => 'NO_SHIPPING',
                'user_action'         => 'PAY_NOW',
                'locale'              => 'es-CL',
            ],
        ]);

        if (!empty($res['body']['id']) && in_array($res['body']['status'] ?? '', ['CREATED', 'APPROVED'], true)) {
            echo json_encode(['success' => true, 'order_id' => $res['body']['id']]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error'   => $res['body']['message'] ?? 'Error al crear la orden en PayPal.',
                'details' => $res['body']['details'] ?? null,
            ]);
        }
        break;

    case 'capture_order':
        $input    = json_decode(file_get_contents('php://input'), true);
        $order_id = $input['order_id'] ?? '';
        if (!$order_id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'order_id requerido.']);
            break;
        }
        $res = pp_request('POST', '/v2/checkout/orders/' . $order_id . '/capture', new stdClass());

        $status = $res['body']['status'] ?? '';
        if ($status === 'COMPLETED') {
            $payer = $res['body']['payer'] ?? [];
            $caps  = $res['body']['purchase_units'][0]['payments']['captures'][0] ?? [];
            echo json_encode([
                'success'     => true,
                'status'      => $status,
                'order_id'    => $order_id,
                'payer_email' => $payer['email_address'] ?? null,
                'amount'      => $caps['amount']['value'] ?? null,
                'currency'    => $caps['amount']['currency_code'] ?? null,
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'status'  => $status,
                'error'   => $res['body']['message'] ?? 'Error al capturar el pago.',
            ]);
        }
        break;

    default:
        http_response_code(400);
        echo json_encode([
            'error'             => 'Acción no válida.',
            'available_actions' => ['get_client_id', 'create_order', 'capture_order'],
        ]);
}
