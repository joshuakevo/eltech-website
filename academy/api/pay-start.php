<?php
/**
 * Starts a mobile money payment: Marz Pay sends a PIN prompt to the student's phone.
 */
require dirname(__DIR__) . '/lib/bootstrap.php';

require_post_with_csrf();
$user = api_user();
$userId = (int) $user['id'];

if (!marzpay_configured()) {
    json_out(['ok' => false, 'message' => 'Mobile money payments are not available right now. Please contact us on WhatsApp.'], 503);
}

$course = load_course((string) ($_POST['course'] ?? ''));
if (!$course || empty($course['price'])) {
    json_out(['ok' => false, 'message' => 'Course not found.'], 404);
}
if (has_access($userId, $course['slug'])) {
    json_out(['ok' => false, 'message' => 'You already have the full course.'], 409);
}

$phone = normalize_ug_phone((string) ($_POST['phone'] ?? ''));
if (!$phone) {
    json_out(['ok' => false, 'message' => 'Please enter a valid MTN or Airtel number, for example 0772 123456.'], 422);
}

// Simple guard against repeated taps: at most 5 attempts per 10 minutes
$stmt = db()->prepare('SELECT COUNT(*) FROM academy_payments WHERE user_id = ? AND created_at > NOW() - INTERVAL 10 MINUTE');
$stmt->execute([$userId]);
if ((int) $stmt->fetchColumn() >= 5) {
    json_out(['ok' => false, 'message' => 'Too many attempts. Please wait a few minutes and try again.'], 429);
}

$reference = uuid_v4();
$pdo = db();
$pdo->prepare(
    "INSERT INTO academy_payments (user_id, course, amount, phone, reference, status, created_at, updated_at)
     VALUES (?, ?, ?, ?, ?, 'pending', NOW(), NOW())"
)->execute([$userId, $course['slug'], $course['price'], $phone, $reference]);
$paymentId = (int) $pdo->lastInsertId();

[$code, $data] = marzpay_request('POST', '/collect-money', [
    'amount' => (int) $course['price'],
    'phone_number' => $phone,
    'reference' => $reference,
    'country' => 'UG',
    'description' => mb_substr('Eltech Academy: ' . $course['title'], 0, 255),
    'callback_url' => 'https://eltech-systems.com/academy/api/marzpay-callback.php',
    'metadata' => [['course' => $course['slug']], ['student' => $user['email'], 'isPII' => true]],
]);

$marzUuid = $data['data']['transaction']['uuid'] ?? '';
if (!in_array($code, [200, 201], true) || ($data['status'] ?? '') !== 'success' || $marzUuid === '') {
    $pdo->prepare("UPDATE academy_payments SET status = 'failed', updated_at = NOW() WHERE id = ?")->execute([$paymentId]);
    error_log('Marz Pay collect-money failed: HTTP ' . $code . ' ' . json_encode($data));
    $message = $data['message'] ?? '';
    if (($data['error_code'] ?? '') === 'VALIDATION_ERROR' || ($data['error_code'] ?? '') === 'INVALID_PHONE_NUMBER') {
        $message = 'That number could not be used for mobile money. Please check it and try again.';
    } elseif ($message === '') {
        $message = 'We could not start the payment. Please try again in a moment.';
    }
    json_out(['ok' => false, 'message' => $message], 502);
}

$pdo->prepare('UPDATE academy_payments SET marz_uuid = ?, updated_at = NOW() WHERE id = ?')->execute([$marzUuid, $paymentId]);

json_out([
    'ok' => true,
    'payment' => $paymentId,
    'provider' => $data['data']['collection']['provider'] ?? '',
    'message' => 'A payment prompt for UGX ' . number_format((float) $course['price']) . ' was sent to ' . $phone . '. Enter your mobile money PIN to approve it.',
]);
