<?php
require dirname(__DIR__) . '/lib/bootstrap.php';

require_post_with_csrf();
$user = api_user();

$course = load_course((string) ($_POST['course'] ?? ''));
$note = trim(mb_substr((string) ($_POST['note'] ?? ''), 0, 1000));
if (!$course) {
    json_out(['ok' => false, 'message' => 'Course not found.'], 404);
}
if ($note === '') {
    json_out(['ok' => false, 'message' => 'Please tell us how you paid, for example the transaction ID.'], 422);
}

db()->prepare('INSERT INTO academy_access_requests (user_id, course, note, created_at) VALUES (?, ?, ?, NOW())')
    ->execute([$user['id'], $course['slug'], $note]);

notify_admin(
    "Unlock request: {$course['title']}",
    "{$user['name']} ({$user['email']}) says they paid for \"{$course['title']}\".\n\nPayment details:\n$note\n\n"
    . "Check the payment, then unlock the course here: https://eltech-systems.com/academy/admin.php?tab=requests\n"
);

json_out(['ok' => true, 'message' => 'Thank you! We will confirm your payment and unlock the course, usually the same day.']);
