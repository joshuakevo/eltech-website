<?php
/**
 * Marks a lesson quiz. Passing (2 out of 3, or 3 out of 4) completes the
 * lesson and unlocks the next one.
 */
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

$progress = course_progress($userId, $course['slug']);
$states = lesson_states($course, $progress, has_access($userId, $course['slug']));
if (!in_array($states[$lesson['slug']], ['open', 'done'], true)) {
    json_out(['ok' => false, 'message' => 'This lesson is locked.'], 403);
}

$answers = $_POST['a'] ?? [];
$results = [];
$score = 0;
foreach ($lesson['quiz'] as $qi => $q) {
    $given = isset($answers[$qi]) ? (int) $answers[$qi] : -1;
    $correct = $given === $q['answer'];
    $score += $correct ? 1 : 0;
    $results[] = ['correct' => $correct, 'why' => $q['why']];
}
$totalQuestions = count($lesson['quiz']);
$passed = $score >= (int) ceil($totalQuestions * 2 / 3);
$wasDone = $states[$lesson['slug']] === 'done';

// Keep the best score; mark the lesson complete the first time it is passed
$stmt = db()->prepare(
    'INSERT INTO academy_progress (user_id, course, lesson, quiz_score, quiz_total, attempts, completed_at)
     VALUES (?, ?, ?, ?, ?, 1, ?)
     ON DUPLICATE KEY UPDATE
       quiz_score = GREATEST(quiz_score, VALUES(quiz_score)),
       quiz_total = VALUES(quiz_total),
       attempts = attempts + 1,
       completed_at = COALESCE(completed_at, VALUES(completed_at))'
);
$stmt->execute([$userId, $course['slug'], $lesson['slug'], $score, $totalQuestions, $passed ? date('Y-m-d H:i:s') : null]);
record_activity($userId);

$points = 0;
if ($passed && !$wasDone) {
    $points = POINTS_LESSON + ($score === $totalQuestions ? POINTS_PERFECT_QUIZ : 0);
}

$next = $course['lessons'][$index + 1] ?? null;
$nextUrl = null;
if ($passed && $next) {
    $nextState = lesson_states($course, course_progress($userId, $course['slug']), has_access($userId, $course['slug']))[$next['slug']];
    $nextUrl = $nextState === 'paid'
        ? 'course.php?c=' . rawurlencode($course['slug']) . '#unlock'
        : 'lesson.php?c=' . rawurlencode($course['slug']) . '&l=' . rawurlencode($next['slug']);
}

json_out([
    'ok' => true,
    'score' => $score,
    'total' => $totalQuestions,
    'passed' => $passed,
    'points' => $points,
    'results' => $results,
    'next_url' => $nextUrl,
    'next_locked' => $nextUrl !== null && strpos($nextUrl, 'course.php') === 0,
    'is_last' => $next === null,
]);
