<?php
/**
 * ClasesdeSki — MercadoPago Checkout Pro API endpoint.
 *
 * Endpoints:
 *   GET  ?action=get_public_key       → { public_key, environment }
 *   POST ?action=create_preference    → { success, preference_id, init_point }
 *   GET  ?action=get_payment_status   → { status, payment_id, amount }
 *
 * Reads credentials from api/config.php.
 *
 * Required config.php constants (tries multiple common names):
 *   PAYMENT_ENVIRONMENT      ('production' | 'sandbox')
 *   MP_LIVE_PUBLIC_KEY  or  MP_PUBLIC_KEY
 *   MP_LIVE_ACCESS_TOKEN  or  MP_ACCESS_TOKEN
 *   MP_SANDBOX_PUBLIC_KEY, MP_SANDBOX_ACCESS_TOKEN  (for sandbox)
 */

require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');

function mp_is_sandbox() {
    return defined('PAYMENT_ENVIRONMENT') && PAYMENT_ENVIRONMENT === 'sandbox';
}

function mp_const($names) {
    foreach ($names as $n) {
        if (defined($n) && constant($n)) return constant($n);
    }
    return '';
}

function mp_creds() {
    if (mp_is_sandbox()) {
        return [
            'public_key'   => mp_const(['MP_SANDBOX_PUBLIC_KEY', 'MERCADOPAGO_SANDBOX_PUBLIC_KEY']),
            'access_token' => mp_const(['MP_SANDBOX_ACCESS_TOKEN', 'MERCADOPAGO_SANDBOX_ACCESS_TOKEN']),
            'env'          => 'sandbox',
        ];
    }
    return [
        'public_key'   => mp_const(['MP_LIVE_PUBLIC_KEY', 'MP_PUBLIC_KEY', 'MERCADOPAGO_PUBLIC_KEY']),
        'access_token' => mp_const(['MP_LIVE_ACCESS_TOKEN', 'MP_ACCESS_TOKEN', 'MERCADOPAGO_ACCESS_TOKEN']),
        'env'          => 'production',
    ];
}

function mp_request($method, $path, $payload = null) {
    $c = mp_creds();
    if (!$c['access_token']) {
        return ['status' => 0, 'body' => ['error' => 'MP_ACCESS_TOKEN no configurado.']];
    }
    $url = 'https://api.mercadopago.com' . $path;
    $ch  = curl_init($url);
    $opts = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $c['access_token'],
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

    case 'get_public_key':
        $c = mp_creds();
        echo json_encode([
            'public_key'  => $c['public_key'],
            'environment' => $c['env'],
        ]);
        break;

    case 'create_preference':
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'JSON inválido.']);
            break;
        }

        $amount      = floatval($input['amount'] ?? 0);
        $description = substr($input['description'] ?? 'Reserva CDSKI', 0, 256);
        $payer_email = filter_var($input['payer_email'] ?? '', FILTER_VALIDATE_EMAIL);
        $payer_name  = substr($input['payer_name'] ?? '', 0, 80);
        $booking     = substr($input['booking_code'] ?? '', 0, 80);

        if ($amount < 1) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Monto inválido.']);
            break;
        }

        $base_url = 'https://clasesdeski.cl';

        $preference = [
            'items' => [[
                'id'          => 'cdski-reserva',
                'title'       => $description,
                'description' => $description,
                'quantity'    => 1,
                'unit_price'  => round($amount, 2),
                'currency_id' => 'CLP',
            ]],
            'back_urls' => [
                'success' => $base_url . '/pago/?status=success&method=mp',
                'failure' => $base_url . '/pago/?status=failure&method=mp',
                'pending' => $base_url . '/pago/?status=pending&method=mp',
            ],
            'auto_return'    => 'approved',
            'binary_mode'    => false,
            'statement_descriptor' => 'CDSKI Chile',
            'external_reference'   => $booking ?: 'CDSKI-' . time(),
            'metadata' => [
                'source'      => 'clasesdeski.cl',
                'booking'     => $booking,
            ],
        ];

        if ($payer_email) {
            $preference['payer'] = [
                'email' => $payer_email,
                'name'  => $payer_name ?: '',
            ];
        }

        $res = mp_request('POST', '/checkout/preferences', $preference);

        if (!empty($res['body']['id']) && !empty($res['body']['init_point'])) {
            echo json_encode([
                'success'       => true,
                'preference_id' => $res['body']['id'],
                'init_point'    => $res['body']['init_point'],
                'sandbox_init_point' => $res['body']['sandbox_init_point'] ?? null,
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error'   => $res['body']['message'] ?? 'Error al crear preferencia en MercadoPago.',
                'status'  => $res['status'],
                'details' => $res['body'] ?? null,
            ]);
        }
        break;

    case 'get_payment_status':
        $payment_id = $_GET['payment_id'] ?? '';
        if (!$payment_id) {
            http_response_code(400);
            echo json_encode(['error' => 'payment_id requerido.']);
            break;
        }
        $res = mp_request('GET', '/v1/payments/' . urlencode($payment_id));
        if ($res['status'] === 200 && !empty($res['body']['id'])) {
            echo json_encode([
                'success'    => true,
                'status'     => $res['body']['status'] ?? null,
                'status_detail' => $res['body']['status_detail'] ?? null,
                'payment_id' => $res['body']['id'],
                'amount'     => $res['body']['transaction_amount'] ?? null,
                'currency'   => $res['body']['currency_id'] ?? null,
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Pago no encontrado.']);
        }
        break;

    default:
        http_response_code(400);
        echo json_encode([
            'error'             => 'Acción no válida.',
            'available_actions' => ['get_public_key', 'create_preference', 'get_payment_status'],
        ]);
}
