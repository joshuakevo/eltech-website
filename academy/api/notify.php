<?php
// "Notify me" for courses that are coming soon
require dirname(__DIR__) . '/lib/bootstrap.php';

require_post_with_csrf();
$user = api_user();

$slug = (string) ($_POST['course'] ?? '');
if (!isset(course_catalog()[$slug])) {
    json_out(['ok' => false, 'message' => 'Course not found.'], 404);
}

db()->prepare('INSERT IGNORE INTO academy_interest (user_id, course, created_at) VALUES (?, ?, NOW())')
    ->execute([$user['id'], $slug]);

json_out(['ok' => true, 'message' => 'We will tell you when it opens']);
