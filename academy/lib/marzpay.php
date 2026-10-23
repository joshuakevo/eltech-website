<?php
/**
 * Marz Pay mobile money collections (MTN / Airtel Uganda).
 * Docs: https://wallet.wearemarz.com/documentation/collections
 *
 * The API key and secret come from the server config file, never from this repo.
 * A payment is only marked paid after we confirm it with Marz Pay ourselves,
 * so a forged callback cannot unlock a course.
 */

function marzpay_configured(): bool
{
    $m = config()['marzpay'] ?? [];
    return !empty($m['api_key']) && !empty($m['api_secret']);
}

/** Calls the Marz Pay API. Returns [http status, decoded JSON or null]. */
function marzpay_request(string $method, string $path, ?array $body = null): array
{
    $m = config()['marzpay'];
    $base = rtrim($m['base_url'] ?? 'https://wallet.wearemarz.com/api/v1', '/');

    $ch = curl_init($base . $path);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => [
            'Authorization: Basic ' . base64_encode($m['api_key'] . ':' . $m['api_secret']),
            'Accept: application/json',
            'Content-Type: application/json',
        ],
    ]);
    if ($body !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    }
    $response = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($response === false) {
        error_log('Marz Pay request failed: ' . curl_error($ch));
    }
    curl_close($ch);

    return [$status, $response === false ? null : json_decode($response, true)];
}

function uuid_v4(): string
{
    $b = random_bytes(16);
    $b[6] = chr((ord($b[6]) & 0x0f) | 0x40);
    $b[8] = chr((ord($b[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($b), 4));
}

/** Turns 0772 123456, 772123456, 256772123456 or +256 772 123 456 into +256772123456. */
function normalize_ug_phone(string $phone): ?string
{
    $digits = preg_replace('/\D/', '', $phone);
    if (strlen($digits) === 10 && $digits[0] === '0') {
        $digits = '256' . substr($digits, 1);
    } elseif (strlen($digits) === 9) {
        $digits = '256' . $digits;
    }
    return preg_match('/^2567\d{8}$/', $digits) ? '+' . $digits : null;
}

/** Maps Marz Pay statuses to ours: pending, completed or failed. */
function marzpay_status(?string $status): string
{
    $status = strtolower((string) $status);
    if (in_array($status, ['completed', 'successful', 'success'], true)) {
        return 'completed';
    }
    if (in_array($status, ['failed', 'cancelled', 'canceled', 'rejected', 'expired'], true)) {
        return 'failed';
    }
    return 'pending';
}

/**
 * Asks Marz Pay for the real state of a payment and applies it.
 * Returns our payment status after the check.
 */
function marzpay_verify(array $payment): string
{
    if ($payment['status'] !== 'pending' || empty($payment['marz_uuid'])) {
        return $payment['status'];
    }
    db()->prepare('UPDATE academy_payments SET checked_at = NOW() WHERE id = ?')->execute([$payment['id']]);

    [$code, $data] = marzpay_request('GET', '/transactions/' . rawurlencode($payment['marz_uuid']));
    if ($code !== 200 || !is_array($data)) {
        return 'pending';
    }
    $txn = $data['transaction'] ?? ($data['data']['transaction'] ?? null);
    if (!is_array($txn) || ($txn['reference'] ?? '') !== $payment['reference']) {
        return 'pending';
    }

    $status = marzpay_status($txn['status'] ?? null);
    $paid = (float) ($txn['amount']['raw'] ?? 0);
    $currency = $txn['amount']['currency'] ?? 'UGX';
    if ($status === 'completed' && ($paid < (float) $payment['amount'] || $currency !== 'UGX')) {
        error_log("Marz Pay amount mismatch for payment {$payment['id']}: $paid $currency");
        return 'pending';
    }

    $collection = $data['collection'] ?? ($data['data']['collection'] ?? []);
    if ($status !== 'pending') {
        finalize_payment($payment, $status, (string) ($collection['provider_transaction_id'] ?? ''));
    }
    return $status;
}

/** Marks a payment completed/failed once, and unlocks the course when completed. */
function finalize_payment(array $payment, string $status, string $providerTxn = ''): void
{
    $pdo = db();
    $stmt = $pdo->prepare(
        "UPDATE academy_payments SET status = ?, provider_txn = ?, updated_at = NOW() WHERE id = ? AND status = 'pending'"
    );
    $stmt->execute([$status, $providerTxn, $payment['id']]);
    if ($stmt->rowCount() === 0 || $status !== 'completed') {
        return; // already handled, or failed
    }

    $pdo->prepare('INSERT IGNORE INTO academy_access (user_id, course, granted_by, granted_at) VALUES (?, ?, ?, NOW())')
        ->execute([$payment['user_id'], $payment['course'], 'Marz Pay ' . $payment['reference']]);
    $pdo->prepare('UPDATE academy_access_requests SET handled = 1 WHERE user_id = ? AND course = ?')
        ->execute([$payment['user_id'], $payment['course']]);

    $stmt = $pdo->prepare('SELECT name, email FROM academy_users WHERE id = ?');
    $stmt->execute([$payment['user_id']]);
    $student = $stmt->fetch();
    $title = course_catalog()[$payment['course']]['title'] ?? $payment['course'];
    $amount = 'UGX ' . number_format((float) $payment['amount']);

    notify_admin(
        "Payment received: $amount for $title",
        "{$student['name']} ({$student['email']}) paid $amount by mobile money ({$payment['phone']}).\n"
        . "The course \"$title\" was unlocked automatically.\n\nMarz Pay reference: {$payment['reference']}\n"
        . ($providerTxn ? "Provider transaction ID: $providerTxn\n" : '')
    );
    send_mail(
        $student['email'],
        "Your course is unlocked: $title",
        "Hi {$student['name']},\n\nThank you! We received your payment of $amount and \"$title\" is now fully unlocked.\n\n"
        . 'Continue here: https://eltech-systems.com/academy/course.php?c=' . $payment['course'] . "\n\nEltech Academy"
    );
}
