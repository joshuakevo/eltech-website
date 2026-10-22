<?php
/**
 * Public certificate page. Anyone with the link can view it, so students
 * can share it and employers can check it is genuine.
 */
require __DIR__ . '/lib/bootstrap.php';

$code = strtoupper(preg_replace('/[^A-Fa-f0-9]/', '', (string) ($_GET['code'] ?? '')));
$stmt = db()->prepare(
    'SELECT c.code, c.course, c.issued_at, u.name FROM academy_certificates c
     JOIN academy_users u ON u.id = c.user_id WHERE c.code = ?'
);
$stmt->execute([$code]);
$cert = $stmt->fetch();
$catalog = course_catalog();

if (!$cert || !isset($catalog[$cert['course']])) {
    http_response_code(404);
    exit('Certificate not found.');
}
$courseTitle = $catalog[$cert['course']]['title'];
$url = 'https://eltech-systems.com/academy/certificate.php?code=' . $cert['code'];
$issued = date('j F Y', strtotime($cert['issued_at']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Certificate: <?= h($courseTitle) ?> | Eltech Academy</title>
  <meta name="description" content="<?= h($cert['name']) ?> completed <?= h($courseTitle) ?> at Eltech Academy.">
  <meta property="og:title" content="<?= h($cert['name']) ?> completed <?= h($courseTitle) ?>">
  <meta property="og:description" content="Certificate of completion from Eltech Academy, Kampala.">
  <meta property="og:image" content="https://eltech-systems.com/assets/img/learn-ai.webp">
  <link href="../assets/img/favicon.png" rel="icon" type="image/png">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.min.css" rel="stylesheet">
  <link href="../assets/css/academy.css" rel="stylesheet">
</head>
<body class="cert-page">
  <div class="cert-actions">
    <a href="index.php"><i class="bi bi-arrow-left" aria-hidden="true"></i> Eltech Academy</a>
    <button type="button" onclick="window.print()"><i class="bi bi-printer" aria-hidden="true"></i> Print or save as PDF</button>
    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= rawurlencode($url) ?>" target="_blank" rel="noopener"><i class="bi bi-linkedin" aria-hidden="true"></i> Share on LinkedIn</a>
  </div>

  <main class="certificate">
    <div class="cert-inner">
      <img src="../assets/img/logo.png" alt="Eltech Systems logo" width="70" height="55">
      <p class="cert-org">ELTECH ACADEMY</p>
      <p class="cert-label">Certificate of completion</p>
      <p class="cert-small">This certifies that</p>
      <h1><?= h($cert['name']) ?></h1>
      <p class="cert-small">has successfully completed the course</p>
      <h2><?= h($courseTitle) ?></h2>
      <p class="cert-small">including all lessons, quizzes and practical tasks.</p>
      <div class="cert-footer">
        <div><strong><?= h($issued) ?></strong><span>Date</span></div>
        <div><strong>Eltech Systems</strong><span>Kampala, Uganda</span></div>
        <div><strong><?= h($cert['code']) ?></strong><span>Certificate ID</span></div>
      </div>
      <p class="cert-verify">Verify at eltech-systems.com/academy/certificate.php?code=<?= h($cert['code']) ?></p>
    </div>
  </main>
</body>
</html>
