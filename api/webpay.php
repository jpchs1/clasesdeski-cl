<?php
/**
 * ClasesdeSki — Transbank WebPay Plus API endpoint.
 *
 * Endpoints:
 *   POST ?action=create_transaction    → { success, token, url } (redirect user to url with token_ws=token)
 *   POST ?action=commit_transaction    → { success, status, amount, authorization_code, response_code }
 *   GET  ?action=get_status            → status of a transaction
 *
 * Reads credentials from api/config.php OR uses hardcoded fallbacks
 * (compatible with tourevo's setup where WEBPAY_* were defined inside webpay.php).
 *
 * Recognized config.php constants:
 *   PAYMENT_ENVIRONMENT      ('production' | 'sandbox')
 *   WEBPAY_COMMERCE_CODE
 *   WEBPAY_API_KEY_SECRET
 *   WEBPAY_API_URL           (default: production endpoint)
 *
 * Transbank API docs:
 *   https://www.transbankdevelopers.cl/documentacion/webpay-plus
 */

require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');

function wp_is_sandbox() {
    return defined('PAYMENT_ENVIRONMENT') && PAYMENT_ENVIRONMENT === 'sandbox';
}

function wp_creds() {
    $env = wp_is_sandbox() ? 'sandbox' : 'production';

    // PRODUCTION
    $prod_code   = defined('WEBPAY_COMMERCE_CODE') ? WEBPAY_COMMERCE_CODE : '';
    $prod_secret = defined('WEBPAY_API_KEY_SECRET') ? WEBPAY_API_KEY_SECRET : '';
    $prod_url    = defined('WEBPAY_API_URL') ? WEBPAY_API_URL : 'https://webpay3g.transbank.cl';

    // SANDBOX (Transbank's public integration credentials)
    $sandbox_code   = '597055555532';
    $sandbox_secret = '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C';
    $sandbox_url    = 'https://webpay3gint.transbank.cl';

    if ($env === 'sandbox') {
        return [
            'commerce_code' => $sandbox_code,
            'api_secret'    => $sandbox_secret,
            'api_url'       => $sandbox_url,
            'env'           => 'sandbox',
        ];
    }
    return [
        'commerce_code' => $prod_code,
        'api_secret'    => $prod_secret,
        'api_url'       => $prod_url,
        'env'           => 'production',
    ];
}

function wp_request($method, $path, $payload = null) {
    $c = wp_creds();
    if (!$c['commerce_code'] || !$c['api_secret']) {
        return ['status' => 0, 'body' => ['error_message' => 'Credenciales Webpay no configuradas.']];
    }
    $url = $c['api_url'] . $path;
    $ch  = curl_init($url);
    $opts = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Tbk-Api-Key-Id: '     . $c['commerce_code'],
            'Tbk-Api-Key-Secret: ' . $c['api_secret'],
        ],
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_CUSTOMREQUEST  => $method,
    ];
    if ($method !== 'GET' && $payload !== null) {
        $opts[CURLOPT_POSTFIELDS] = json_encode($payload);
    }
    curl_setopt_array($ch, $opts);
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['status' => $code, 'body' => json_decode($body, true)];
}

function wp_gen_buy_order() {
    return 'CDSKI' . substr(str_replace(['.', '-'], '', uniqid('', true)), 0, 21);
}

function wp_gen_session_id() {
    return 'ses' . substr(bin2hex(random_bytes(16)), 0, 28);
}

$action = $_GET['action'] ?? '';

switch ($action) {

    case 'create_transaction':
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'JSON inválido.']);
            break;
        }

        $amount    = intval($input['amount'] ?? 0);
        $base_url  = 'https://clasesdeski.cl';
        $return_url = $base_url . '/pago/webpay-return.php';

        if ($amount < 50) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Monto mínimo $50 CLP.']);
            break;
        }
        if ($amount > 99999999) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Monto excede el máximo permitido.']);
            break;
        }

        $buy_order  = wp_gen_buy_order();
        $session_id = wp_gen_session_id();

        $res = wp_request('POST', '/rswebpaytransaction/api/webpay/v1.2/transactions', [
            'buy_order'  => $buy_order,
            'session_id' => $session_id,
            'amount'     => $amount,
            'return_url' => $return_url,
        ]);

        if (!empty($res['body']['token']) && !empty($res['body']['url'])) {
            echo json_encode([
                'success'    => true,
                'token'      => $res['body']['token'],
                'url'        => $res['body']['url'],
                'buy_order'  => $buy_order,
                'session_id' => $session_id,
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success'      => false,
                'error'        => $res['body']['error_message'] ?? 'Error al crear transacción Webpay.',
                'status'       => $res['status'],
                'response'     => $res['body'] ?? null,
            ]);
        }
        break;

    case 'commit_transaction':
        $input = json_decode(file_get_contents('php://input'), true);
        $token = $input['token'] ?? ($_GET['token_ws'] ?? '');

        if (!$token) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'token_ws requerido.']);
            break;
        }

        $res = wp_request('PUT', '/rswebpaytransaction/api/webpay/v1.2/transactions/' . urlencode($token), null);

        $status = $res['body']['status'] ?? '';
        $is_authorized = $status === 'AUTHORIZED' && ($res['body']['response_code'] ?? 1) === 0;

        if ($is_authorized) {
            echo json_encode([
                'success'             => true,
                'status'              => $status,
                'amount'              => $res['body']['amount'] ?? null,
                'buy_order'           => $res['body']['buy_order'] ?? null,
                'authorization_code'  => $res['body']['authorization_code'] ?? null,
                'payment_type_code'   => $res['body']['payment_type_code'] ?? null,
                'card_number'         => $res['body']['card_detail']['card_number'] ?? null,
                'transaction_date'    => $res['body']['transaction_date'] ?? null,
            ]);
        } else {
            http_response_code(402);
            echo json_encode([
                'success' => false,
                'status'  => $status,
                'response_code' => $res['body']['response_code'] ?? null,
                'error'   => $res['body']['error_message'] ?? 'Pago rechazado o incompleto.',
            ]);
        }
        break;

    case 'get_status':
        $token = $_GET['token'] ?? '';
        if (!$token) {
            http_response_code(400);
            echo json_encode(['error' => 'token requerido.']);
            break;
        }
        $res = wp_request('GET', '/rswebpaytransaction/api/webpay/v1.2/transactions/' . urlencode($token), null);
        echo json_encode($res['body']);
        break;

    default:
        $c = wp_creds();
        http_response_code(400);
        echo json_encode([
            'error'             => 'Invalid action',
            'available_actions' => ['create_transaction', 'commit_transaction', 'get_status'],
            'environment'       => strtoupper($c['env']),
        ]);
}
