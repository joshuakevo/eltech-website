<?php
/**
 * Called when the student finishes the intro video (or it cannot play),
 * which unlocks lesson 1 of that course.
 */
require dirname(__DIR__) . '/lib/bootstrap.php';

require_post_with_csrf();
$user = api_user();

$course = load_course((string) ($_POST['course'] ?? ''));
if (!$course || !$course['intro']) {
    json_out(['ok' => false, 'message' => 'Course not found.'], 404);
}

db()->prepare(
    "INSERT INTO academy_progress (user_id, course, lesson, quiz_score, quiz_total, attempts, completed_at)
     VALUES (?, ?, 'intro', 0, 0, 0, NOW())
     ON DUPLICATE KEY UPDATE completed_at = COALESCE(completed_at, NOW())"
)->execute([$user['id'], $course['slug']]);
record_activity((int) $user['id']);

json_out(['ok' => true]);
