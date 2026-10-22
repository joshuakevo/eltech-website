<?php
/**
 * Form handler for eltech-systems.com.
 *
 * Handles three forms:
 *  - "contact": the Book a demo / contact form    -> info@eltech-systems.com
 *  - "project": the Start a project pop-up        -> kavinyia@gmail.com
 *  - "course":  the AI course form on learn.html  -> kavinyia@gmail.com
 *
 * Uses PHP's built-in mail(), so no SMTP password is needed. Do NOT put any
 * credentials in this file: this repository is public.
 */

// Log problems instead of printing them, so replies stay clean JSON
ini_set('display_errors', '0');

$from = 'info@eltech-systems.com'; // must be an address on our own domain

$recipients = [
    'contact' => 'info@eltech-systems.com',
    'project' => 'kavinyia@gmail.com',
    'course'  => 'kavinyia@gmail.com',
];

$isAjax = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'fetch';

function respond($ok, $message, $isAjax)
{
    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => $ok, 'message' => $message]);
    } else {
        // Fallback for browsers without JavaScript
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8">'
           . '<meta name="viewport" content="width=device-width, initial-scale=1">'
           . '<title>Eltech Systems</title></head>'
           . '<body style="font-family:system-ui,sans-serif;max-width:560px;margin:80px auto;padding:0 20px;color:#2b2f36">'
           . '<p>' . htmlspecialchars($message) . '</p>'
           . '<p><a href="../index.html" style="color:#1565c0">Back to Eltech Systems</a></p>'
           . '</body></html>';
    }
    exit;
}

// Removes line breaks so nothing can be injected into mail headers
function clean_line($value, $max = 100)
{
    return mb_substr(trim(preg_replace('/[\r\n\t]+/', ' ', (string) $value)), 0, $max);
}

// Returns the value if it is one of the allowed options, otherwise the fallback
function pick($value, array $allowed, $fallback)
{
    $value = clean_line($value);
    return in_array($value, $allowed, true) ? $value : $fallback;
}

function valid_phone($value)
{
    $digits = preg_replace('/\D/', '', $value);
    return strlen($digits) >= 9 && strlen($digits) <= 15;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    respond(false, 'Please use the form on our website.', $isAjax);
}

// Spam trap: the hidden "website" field is only filled in by bots
if (!empty($_POST['website'])) {
    respond(true, 'Thank you. We will get back to you soon.', $isAjax);
}

$formType = $_POST['form'] ?? '';
if (!isset($recipients[$formType])) {
    $formType = 'contact';
}
$name     = clean_line($_POST['name'] ?? '');
$message  = mb_substr(trim((string) ($_POST['message'] ?? '')), 0, 5000);

// The project and course forms ask for email and/or phone separately
$email = clean_line($_POST['email'] ?? '');
$phone = clean_line($_POST['phone'] ?? '', 20);
if ($formType !== 'contact') {
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(422);
        respond(false, 'Please check your email address.', $isAjax);
    }
    if ($phone !== '' && !valid_phone($phone)) {
        http_response_code(422);
        respond(false, 'Please check your phone number.', $isAjax);
    }
}

if ($formType === 'course') {
    $course = pick($_POST['course'] ?? '', ['AI Beyond ChatGPT', 'Automate Your Business with AI', 'Data Analysis with AI', 'AI for Marketing and Content', 'AI for Managers and Teams', 'Build Apps with AI', 'Not sure, please advise me'], 'Not sure, please advise me');
    $who    = pick($_POST['who'] ?? '', ['Just me', 'My team'], 'Not given');

    if ($name === '' || ($email === '' && $phone === '')) {
        http_response_code(422);
        respond(false, 'Please add your name and an email or phone number.', $isAjax);
    }

    $replyTo = $email;
    $subject = "AI course interest: $course from $name";
    $body = "New AI course registration from the website\n"
          . "-------------------------------------------\n"
          . "Name:    $name\n"
          . "Email:   " . ($email ?: '-') . "\n"
          . "Phone:   " . ($phone ?: '-') . "\n"
          . "Course:  $course\n"
          . "For:     $who\n\n"
          . "Notes:\n" . ($message ?: '-') . "\n";
    $thanks = 'Thank you. We will send you the next dates and fees within one working day.';
} elseif ($formType === 'project') {
    $type     = pick($_POST['type'] ?? '', ['Business system', 'Web app', 'Mobile app', 'Automation', 'Integration', 'Not sure yet'], 'Not sure yet');
    $timeline = pick($_POST['timeline'] ?? '', ['As soon as possible', 'In 1 to 3 months', 'In 3 to 6 months', 'Just exploring'], 'Not given');
    $budget   = pick($_POST['budget'] ?? '', ['Not sure yet', 'Under UGX 5 million', 'UGX 5 to 20 million', 'UGX 20 to 50 million', 'Over UGX 50 million'], 'Not given');

    if ($name === '' || $message === '' || ($email === '' && $phone === '')) {
        http_response_code(422);
        respond(false, 'Please add your name, an email or phone number, and your idea.', $isAjax);
    }

    $replyTo = $email;
    $subject = "New project idea: $type from $name";
    $body = "New project idea from the website\n"
          . "---------------------------------\n"
          . "Name:      $name\n"
          . "Email:     " . ($email ?: '-') . "\n"
          . "Phone:     " . ($phone ?: '-') . "\n"
          . "Needs:     $type\n"
          . "Timeline:  $timeline\n"
          . "Budget:    $budget\n\n"
          . "Idea:\n$message\n";
    $thanks = 'Thank you. We have your idea and will contact you within one working day.';
} else {
    $contact  = clean_line($_POST['contact'] ?? '');
    $interest = pick($_POST['interest'] ?? '', ['Eltech Finance', 'Eltech POS', 'Custom software'], 'Not specified');

    if ($name === '' || $contact === '' || $message === '') {
        http_response_code(422);
        respond(false, 'Please fill in your name, email or phone, and a message.', $isAjax);
    }

    $replyTo = filter_var($contact, FILTER_VALIDATE_EMAIL) ? $contact : '';
    if ($replyTo === '' && !valid_phone($contact)) {
        http_response_code(422);
        respond(false, 'Please enter a valid email address or phone number.', $isAjax);
    }

    $subject = "Website enquiry: $interest from $name";
    $body = "New enquiry from the website\n"
          . "-----------------------------\n"
          . "Name:        $name\n"
          . "Contact:     $contact\n"
          . "Interested:  $interest\n\n"
          . "Message:\n$message\n";
    $thanks = 'Thank you. We will get back to you within one working day.';
}

$headers = [
    'From: Eltech Website <' . $from . '>',
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'Content-Transfer-Encoding: 8bit',
];
if ($replyTo !== '') {
    $headers[] = 'Reply-To: ' . $replyTo;
}

$sent = mail(
    $recipients[$formType],
    mb_encode_mimeheader($subject, 'UTF-8'),
    $body,
    implode("\r\n", $headers),
    '-f' . $from
);

if ($sent) {
    respond(true, $thanks, $isAjax);
}

http_response_code(500);
respond(false, 'Sorry, your message could not be sent. Please call or WhatsApp us on +256 788 176 140.', $isAjax);
