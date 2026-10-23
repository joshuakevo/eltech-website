<?php
/**
 * Eltech Academy - shared setup for every academy page and API.
 *
 * Secrets (database password etc.) are NOT stored in this public repo.
 * They are read from "eltech-academy-config.php" in the folder ABOVE
 * public_html on the server (see academy/lib/SETUP.md).
 */

declare(strict_types=1);

ini_set('display_errors', '0');
date_default_timezone_set('Africa/Kampala');

require __DIR__ . '/courses.php';
require __DIR__ . '/marzpay.php';

/* ---------- Config, database, session ---------- */

function config(): array
{
    static $config = null;
    if ($config === null) {
        $path = getenv('ELTECH_ACADEMY_CONFIG') ?: dirname(__DIR__, 3) . '/eltech-academy-config.php';
        if (!is_file($path)) {
            http_response_code(503);
            exit('The academy is being set up. Please check back soon.');
        }
        $config = require $path;
    }
    return $config;
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $c = config()['db'];
        $pdo = new PDO(
            "mysql:host={$c['host']};dbname={$c['name']};charset=utf8mb4",
            $c['user'],
            $c['pass'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
        ensure_schema($pdo);
    }
    return $pdo;
}

// Creates any missing academy tables (checks for the newest table, so new tables get added on update)
function ensure_schema(PDO $pdo): void
{
    if ($pdo->query("SHOW TABLES LIKE 'academy_visits'")->fetch()) {
        return;
    }
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
        $pdo->exec($statement);
    }
}

function is_https(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
}

function start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $thirtyDays = 60 * 60 * 24 * 30;
    ini_set('session.gc_maxlifetime', (string) $thirtyDays);
    session_name('eltech_academy');
    session_set_cookie_params([
        'lifetime' => $thirtyDays,
        'path' => '/',
        'secure' => is_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// The visit counter defines ACADEMY_NO_SESSION so anonymous visitors never get a session cookie
if (!defined('ACADEMY_NO_SESSION')) {
    start_session();
}

/* ---------- Helpers ---------- */

function h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Changes whenever the file changes, so browsers fetch the new version after an update
function asset_version(string $file): string
{
    return (string) @filemtime(dirname(__DIR__, 2) . '/assets/' . $file);
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function json_out(array $data, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

function flash(?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'] = $message;
        return null;
    }
    $message = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $message;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

// Every POST must carry the token, as a header (fetch) or a form field
function require_post_with_csrf(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_out(['ok' => false, 'message' => 'Method not allowed.'], 405);
    }
    $sent = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($_POST['csrf'] ?? '');
    if (!is_string($sent) || !hash_equals(csrf_token(), $sent)) {
        json_out(['ok' => false, 'message' => 'Your session expired. Please refresh the page.'], 403);
    }
}

function notify_admin(string $subject, string $body): void
{
    $to = config()['notify_email'] ?? '';
    if ($to === '') {
        return;
    }
    $from = 'info@eltech-systems.com';
    $headers = "From: Eltech Academy <$from>\r\nMIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8";
    $subject = preg_replace('/[\r\n]+/', ' ', $subject);
    @mail($to, mb_encode_mimeheader('[Academy] ' . $subject, 'UTF-8'), $body, $headers, '-f' . $from);
}

function send_mail(string $to, string $subject, string $body): bool
{
    $from = 'info@eltech-systems.com';
    $headers = "From: Eltech Academy <$from>\r\nMIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8";
    $subject = preg_replace('/[\r\n]+/', ' ', $subject);
    return @mail($to, mb_encode_mimeheader($subject, 'UTF-8'), $body, $headers, '-f' . $from);
}

/* ---------- Users ---------- */

function current_user(): ?array
{
    static $user = false;
    if ($user === false) {
        $user = null;
        if (!empty($_SESSION['uid'])) {
            $stmt = db()->prepare('SELECT * FROM academy_users WHERE id = ?');
            $stmt->execute([$_SESSION['uid']]);
            $user = $stmt->fetch() ?: null;
        }
    }
    return $user;
}

function require_user(): array
{
    $user = current_user();
    if (!$user) {
        redirect('index.php');
    }
    return $user;
}

function api_user(): array
{
    $user = current_user();
    if (!$user) {
        json_out(['ok' => false, 'message' => 'Please sign in again.'], 401);
    }
    return $user;
}

function is_admin(?array $user): bool
{
    if (!$user) {
        return false;
    }
    $admins = array_map('strtolower', config()['admin_emails'] ?? []);
    return in_array(strtolower($user['email']), $admins, true);
}

function dev_login_allowed(): bool
{
    $host = strtolower(explode(':', $_SERVER['HTTP_HOST'] ?? '')[0]);
    return !empty(config()['dev_login'])
        && in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true)
        && in_array($host, ['localhost', '127.0.0.1'], true);
}

function log_in_user(string $sub, string $email, string $name, string $picture): void
{
    $pdo = db();
    $stmt = $pdo->prepare(
        'INSERT INTO academy_users (google_sub, email, name, picture, created_at, last_seen_at)
         VALUES (?, ?, ?, ?, NOW(), NOW())
         ON DUPLICATE KEY UPDATE email = VALUES(email), name = VALUES(name), picture = VALUES(picture), last_seen_at = NOW()'
    );
    $stmt->execute([$sub, $email, $name, $picture]);

    $stmt = $pdo->prepare('SELECT id, created_at = last_seen_at AS is_new FROM academy_users WHERE google_sub = ?');
    $stmt->execute([$sub]);
    $row = $stmt->fetch();

    session_regenerate_id(true);
    $_SESSION['uid'] = (int) $row['id'];

    if ($row['is_new']) {
        notify_admin("New student: $name", "A new student joined the academy.\n\nName:  $name\nEmail: $email\n");
    }
}

/* ---------- Progress, access, points ---------- */

function record_activity(int $userId): void
{
    db()->prepare('INSERT IGNORE INTO academy_activity (user_id, day) VALUES (?, CURDATE())')->execute([$userId]);
}

function course_progress(int $userId, string $course): array
{
    $stmt = db()->prepare('SELECT * FROM academy_progress WHERE user_id = ? AND course = ?');
    $stmt->execute([$userId, $course]);
    $rows = [];
    foreach ($stmt->fetchAll() as $row) {
        $rows[$row['lesson']] = $row;
    }
    return $rows;
}

function course_submissions(int $userId, string $course): array
{
    $stmt = db()->prepare('SELECT * FROM academy_submissions WHERE user_id = ? AND course = ?');
    $stmt->execute([$userId, $course]);
    $rows = [];
    foreach ($stmt->fetchAll() as $row) {
        $rows[$row['lesson']] = $row;
    }
    return $rows;
}

function has_access(int $userId, string $course): bool
{
    $stmt = db()->prepare('SELECT 1 FROM academy_access WHERE user_id = ? AND course = ?');
    $stmt->execute([$userId, $course]);
    return (bool) $stmt->fetch();
}

/**
 * State of each lesson for a student:
 *  done        - quiz passed
 *  open        - can be studied now
 *  locked      - finish the previous lesson first
 *  paid        - needs the full course unlocked
 */
function lesson_states(array $course, array $progress, bool $access): array
{
    $states = [];
    // The intro video (when a course has one) must be watched before lesson 1
    $previousDone = empty($course['intro']) || intro_watched($progress);
    foreach ($course['lessons'] as $i => $lesson) {
        $done = !empty($progress[$lesson['slug']]['completed_at']);
        if ($done) {
            $states[$lesson['slug']] = 'done';
        } elseif ($i >= $course['free_lessons'] && !$access) {
            $states[$lesson['slug']] = 'paid';
        } elseif (!$previousDone) {
            $states[$lesson['slug']] = 'locked';
        } else {
            $states[$lesson['slug']] = 'open';
        }
        $previousDone = $done;
    }
    return $states;
}

// The intro is stored as a progress row with lesson = 'intro' (no quiz, no points)
function intro_watched(array $progress): bool
{
    return !empty($progress['intro']['completed_at']);
}

function course_is_complete(array $course, array $progress, array $submissions): bool
{
    foreach ($course['lessons'] as $lesson) {
        if (empty($progress[$lesson['slug']]['completed_at']) || empty($submissions[$lesson['slug']])) {
            return false;
        }
    }
    return true;
}

const POINTS_LESSON = 10;
const POINTS_PERFECT_QUIZ = 5;
const POINTS_TASK_SUBMITTED = 5;
const POINTS_TASK_APPROVED = 15;

function student_stats(int $userId): array
{
    $pdo = db();

    $stmt = $pdo->prepare(
        'SELECT COUNT(completed_at) AS lessons, SUM(completed_at IS NOT NULL AND quiz_score = quiz_total) AS perfect
         FROM academy_progress WHERE user_id = ? AND lesson <> \'intro\''
    );
    $stmt->execute([$userId]);
    $p = $stmt->fetch();

    $stmt = $pdo->prepare(
        "SELECT COUNT(*) AS submitted, SUM(status = 'approved') AS approved FROM academy_submissions WHERE user_id = ?"
    );
    $stmt->execute([$userId]);
    $s = $stmt->fetch();

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM academy_certificates WHERE user_id = ?');
    $stmt->execute([$userId]);
    $certificates = (int) $stmt->fetchColumn();

    $lessons = (int) $p['lessons'];
    $perfect = (int) $p['perfect'];
    $submitted = (int) $s['submitted'];
    $approved = (int) $s['approved'];
    $streak = current_streak($userId);

    $badges = [];
    $badges[] = ['icon' => 'bi-flag', 'name' => 'First step', 'hint' => 'Finish your first lesson', 'earned' => $lessons >= 1];
    $badges[] = ['icon' => 'bi-bullseye', 'name' => 'Sharp shooter', 'hint' => 'Score 100% on a quiz', 'earned' => $perfect >= 1];
    $badges[] = ['icon' => 'bi-fire', 'name' => 'On fire', 'hint' => 'Learn 3 days in a row', 'earned' => $streak >= 3];
    $badges[] = ['icon' => 'bi-tools', 'name' => 'Builder', 'hint' => 'Get a task approved', 'earned' => $approved >= 1];
    $badges[] = ['icon' => 'bi-award', 'name' => 'Graduate', 'hint' => 'Complete a full course', 'earned' => $certificates >= 1];

    return [
        'points' => $lessons * POINTS_LESSON + $perfect * POINTS_PERFECT_QUIZ
                  + $submitted * POINTS_TASK_SUBMITTED + $approved * POINTS_TASK_APPROVED,
        'lessons' => $lessons,
        'streak' => $streak,
        'badges' => $badges,
    ];
}

// Days in a row with activity, counting from today (or yesterday, so the streak survives until tonight)
function current_streak(int $userId): int
{
    $stmt = db()->prepare('SELECT day FROM academy_activity WHERE user_id = ? ORDER BY day DESC LIMIT 400');
    $stmt->execute([$userId]);
    $days = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $expected = new DateTime('today');
    if ($days && $days[0] !== $expected->format('Y-m-d')) {
        $expected->modify('-1 day');
    }
    $streak = 0;
    foreach ($days as $day) {
        if ($day !== $expected->format('Y-m-d')) {
            break;
        }
        $streak++;
        $expected->modify('-1 day');
    }
    return $streak;
}

function ensure_certificate(int $userId, string $course): string
{
    $pdo = db();
    $stmt = $pdo->prepare('SELECT code FROM academy_certificates WHERE user_id = ? AND course = ?');
    $stmt->execute([$userId, $course]);
    $code = $stmt->fetchColumn();
    if ($code) {
        return $code;
    }
    $code = strtoupper(bin2hex(random_bytes(5)));
    $pdo->prepare('INSERT INTO academy_certificates (code, user_id, course, issued_at) VALUES (?, ?, ?, NOW())')
        ->execute([$code, $userId, $course]);
    return $code;
}
