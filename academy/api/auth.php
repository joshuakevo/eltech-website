<?php
/**
 * Sign in with Google.
 * The browser sends the ID token from Google's button; we check it with Google
 * (no client secret needed) and start a session.
 */
require dirname(__DIR__) . '/lib/bootstrap.php';

require_post_with_csrf();

// Local testing only: sign in without Google on this computer
if (isset($_POST['dev']) && dev_login_allowed()) {
    $email = filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
    $name = trim((string) ($_POST['name'] ?? ''));
    if (!$email || $name === '') {
        json_out(['ok' => false, 'message' => 'Enter a name and email.'], 422);
    }
    log_in_user('dev:' . strtolower($email), $email, mb_substr($name, 0, 190), '');
    json_out(['ok' => true, 'csrf' => csrf_token()]);
}

$token = (string) ($_POST['credential'] ?? '');
$clientId = config()['google_client_id'] ?? '';
if ($token === '' || $clientId === '') {
    json_out(['ok' => false, 'message' => 'Sign-in is not available right now.'], 400);
}

$ch = curl_init('https://oauth2.googleapis.com/tokeninfo?id_token=' . rawurlencode($token));
curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10]);
$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$info = $status === 200 ? json_decode((string) $response, true) : null;
$valid = is_array($info)
    && ($info['aud'] ?? '') === $clientId
    && in_array($info['iss'] ?? '', ['accounts.google.com', 'https://accounts.google.com'], true)
    && (int) ($info['exp'] ?? 0) > time()
    && in_array($info['email_verified'] ?? '', ['true', true], true)
    && !empty($info['sub']) && !empty($info['email']);

if (!$valid) {
    json_out(['ok' => false, 'message' => 'We could not confirm your Google sign-in. Please try again.'], 401);
}

log_in_user(
    (string) $info['sub'],
    (string) $info['email'],
    mb_substr((string) ($info['name'] ?? $info['email']), 0, 190),
    mb_substr((string) ($info['picture'] ?? ''), 0, 500)
);
json_out(['ok' => true]);
