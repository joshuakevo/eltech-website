<?php
require dirname(__DIR__) . '/lib/bootstrap.php';

require_post_with_csrf();
$user = api_user();
$userId = (int) $user['id'];

$course = load_course((string) ($_POST['course'] ?? ''));
$index = $course ? find_lesson($course, (string) ($_POST['lesson'] ?? '')) : null;
if ($index === null) {
    json_out(['ok' => false, 'message' => 'Lesson not found.'], 404);
}
$lesson = $course['lessons'][$index];

$states = lesson_states($course, course_progress($userId, $course['slug']), has_access($userId, $course['slug']));
if (!in_array($states[$lesson['slug']], ['open', 'done'], true)) {
    json_out(['ok' => false, 'message' => 'This lesson is locked.'], 403);
}

$answer = trim(mb_substr((string) ($_POST['answer'] ?? ''), 0, 8000));
$link = trim(mb_substr((string) ($_POST['link'] ?? ''), 0, 500));
if (mb_strlen($answer) < 20) {
    json_out(['ok' => false, 'message' => 'Please write a little more about what you did (at least a couple of sentences).'], 422);
}
if ($link !== '' && (!filter_var($link, FILTER_VALIDATE_URL) || !preg_match('#^https?://#i', $link))) {
    json_out(['ok' => false, 'message' => 'Please check the link. It should start with https://'], 422);
}

$stmt = db()->prepare(
    "INSERT INTO academy_submissions (user_id, course, lesson, answer, link, status, created_at)
     VALUES (?, ?, ?, ?, ?, 'submitted', NOW())
     ON DUPLICATE KEY UPDATE answer = VALUES(answer), link = VALUES(link), status = 'submitted', created_at = NOW()"
);
$stmt->execute([$userId, $course['slug'], $lesson['slug'], $answer, $link]);
record_activity($userId);

notify_admin(
    "Task submitted: {$lesson['task']['title']}",
    "{$user['name']} ({$user['email']}) submitted a task.\n\nCourse: {$course['title']}\nLesson: {$lesson['title']}\n\n$answer\n\n"
    . ($link ? "Link: $link\n\n" : '')
    . "Review it here: https://eltech-systems.com/academy/admin.php\n"
);

json_out(['ok' => true, 'message' => 'Task submitted. Nice work! We will review it and leave you feedback here.']);
