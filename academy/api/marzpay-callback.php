<?php
/**
 * Marz Pay calls this when a payment finishes (completed / failed).
 * We never trust the message alone: we look up the payment by its reference and
 * confirm its status with Marz Pay before unlocking anything.
 */
require dirname(__DIR__) . '/lib/bootstrap.php';

header('Content-Type: application/json');
$raw = (string) file_get_contents('php://input');

// Optional signature check, if webhook signing is turned on in Marz Pay
$secret = config()['marzpay']['webhook_secret'] ?? '';
if ($secret !== '') {
    $timestamp = $_SERVER['HTTP_X_MARZPAY_TIMESTAMP'] ?? '';
    preg_match('/v1=([a-f0-9]+)/', $_SERVER['HTTP_X_MARZPAY_SIGNATURE'] ?? '', $m);
    $expected = hash_hmac('sha256', $timestamp . '.' . $raw, $secret);
    if (!$m || !hash_equals($expected, $m[1]) || abs(time() - (int) $timestamp) > 600) {
        http_response_code(401);
        exit('{"ok":false}');
    }
}

$body = json_decode($raw, true);
$payload = isset($body['data']['transaction']) ? $body['data'] : $body; // dashboard webhooks wrap the payload
$reference = (string) ($payload['transaction']['reference'] ?? '');

if ($reference !== '' && marzpay_configured()) {
    $stmt = db()->prepare('SELECT * FROM academy_payments WHERE reference = ?');
    $stmt->execute([$reference]);
    if ($payment = $stmt->fetch()) {
        marzpay_verify($payment);
    }
}

// Always answer 200 so Marz Pay does not keep retrying unknown references
echo '{"ok":true}';
