<?php
/**
 * ClasesdeSki — Webpay return handler.
 *
 * Transbank redirects back via POST to this URL with token_ws in the body.
 * We forward it to /pago/?status=return&method=webpay&token_ws=...
 * so cdski-pago.js can call commit_transaction and display the result.
 */
$token = $_POST['token_ws'] ?? $_GET['token_ws'] ?? '';

if (!$token) {
    // User aborted or session expired → /pago/ with abort status
    $loc = '/pago/?status=abort&method=webpay';
} else {
    $loc = '/pago/?status=return&method=webpay&token_ws=' . urlencode($token);
}

// HTTP redirect (HTTP 303 = "See Other", proper for POST→GET conversion)
header('Location: ' . $loc, true, 303);
exit;
