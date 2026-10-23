<?php
/**
 * The payment pop-up asks this every few seconds. If Marz Pay's callback has not
 * arrived yet, we ask Marz Pay directly (at most every 8 seconds per payment).
 */
require dirname(__DIR__) . '/lib/bootstrap.php';

$user = api_user();

$stmt = db()->prepare('SELECT * FROM academy_payments WHERE id = ? AND user_id = ?');
$stmt->execute([(int) ($_GET['id'] ?? 0), $user['id']]);
$payment = $stmt->fetch();
if (!$payment) {
    json_out(['ok' => false, 'message' => 'Payment not found.'], 404);
}

$status = $payment['status'];
$recentlyChecked = $payment['checked_at'] && strtotime($payment['checked_at']) > time() - 8;
if ($status === 'pending' && !$recentlyChecked && strtotime($payment['created_at']) < time() - 5) {
    $status = marzpay_verify($payment);
}

json_out([
    'ok' => true,
    'status' => $status,
    'course_url' => 'course.php?c=' . rawurlencode($payment['course']),
]);
