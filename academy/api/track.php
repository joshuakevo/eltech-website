<?php
/**
 * Anonymous visit counter for the admin "Visitors" tab.
 * Each page sends one small request. We store no IP address and set no cookie:
 * a visitor is a daily-changing code made from the IP and browser, which
 * cannot be turned back into either.
 */
const ACADEMY_NO_SESSION = true;
require dirname(__DIR__) . '/lib/bootstrap.php';

http_response_code(204);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

// Skip search engines, link previews and other robots
$ua = (string) ($_SERVER['HTTP_USER_AGENT'] ?? '');
$robots = '/bot|crawl|spider|slurp|preview|lighthouse|facebookexternalhit|whatsapp\/|curl|wget|python|java\//i';
if ($ua === '' || preg_match($robots, $ua) || (stripos($ua, 'Headless') !== false && !dev_login_allowed())) {
    exit;
}

// Signed-in students are shown by name; admins are not counted
$userId = null;
if (isset($_COOKIE['eltech_academy'])) {
    start_session();
    $user = current_user();
    if ($user && is_admin($user)) {
        exit;
    }
    $userId = $user['id'] ?? null;
    session_write_close();
}

$path = (string) ($_POST['p'] ?? '');
if ($path === '' || $path[0] !== '/' || strlen($path) > 200 || preg_match('#/academy/admin\.php#', $path)) {
    exit;
}

// Where the visitor came from
$utm = strtolower(preg_replace('/[^a-z0-9_.-]/i', '', (string) ($_POST['u'] ?? '')));
$host = strtolower((string) parse_url((string) ($_POST['r'] ?? ''), PHP_URL_HOST));
$host = preg_replace('/^www\./', '', $host);
$sources = [
    'google' => 'Google', 'bing' => 'Bing', 'facebook' => 'Facebook', 'fb.' => 'Facebook',
    'whatsapp' => 'WhatsApp', 'wa.me' => 'WhatsApp', 'l.wl.co' => 'WhatsApp', 'instagram' => 'Instagram',
    'tiktok' => 'TikTok', 'linkedin' => 'LinkedIn', 'lnkd.in' => 'LinkedIn', 't.co' => 'X (Twitter)',
    'twitter' => 'X (Twitter)', 'youtube' => 'YouTube', 'eltech-systems.com' => 'Inside the site',
];
$ownHost = preg_replace('/^www\./', '', strtolower(explode(':', $_SERVER['HTTP_HOST'] ?? '')[0]));
$from = $utm !== '' ? $utm : $host;
if ($from === '') {
    $source = 'Direct';
} elseif ($utm === '' && $host === $ownHost) {
    $source = 'Inside the site';
} else {
    $source = $utm !== '' ? ucfirst(mb_substr($utm, 0, 40)) : mb_substr($host, 0, 60);
    foreach ($sources as $needle => $label) {
        if (strpos($from, $needle) !== false) {
            $source = $label;
            break;
        }
    }
}

$device = preg_match('/iPad|Tablet/i', $ua) ? 'Tablet' : (preg_match('/Mobi|Android|iPhone/i', $ua) ? 'Phone' : 'Computer');

$secret = hash('sha256', json_encode(config()['db']));
$visitor = substr(hash_hmac('sha256', date('Y-m-d') . '|' . ($_SERVER['REMOTE_ADDR'] ?? '') . '|' . $ua, $secret), 0, 16);

db()->prepare(
    'INSERT INTO academy_visits (visited_at, day, visitor, path, source, device, user_id) VALUES (NOW(), CURDATE(), ?, ?, ?, ?, ?)'
)->execute([$visitor, $path, $source, $device, $userId]);

// Keep six months of history
if (random_int(1, 300) === 1) {
    db()->exec('DELETE FROM academy_visits WHERE visited_at < NOW() - INTERVAL 180 DAY');
}
