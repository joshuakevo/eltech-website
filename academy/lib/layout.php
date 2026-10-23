<?php
/**
 * Shared header and footer for academy pages.
 */

function page_start(string $title, ?array $user, array $opts = []): void
{
    $description = $opts['description'] ?? 'Practical AI courses by Eltech Systems. Start free with your Google account.';
    $index = !empty($opts['index']);
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= h($title) ?> | Eltech Academy</title>
  <meta name="description" content="<?= h($description) ?>">
  <?php if (!$index): ?><meta name="robots" content="noindex"><?php endif; ?>
  <meta name="theme-color" content="#0f2444">
  <meta name="csrf-token" content="<?= h(csrf_token()) ?>">
  <meta property="og:title" content="<?= h($title) ?> | Eltech Academy">
  <meta property="og:description" content="<?= h($description) ?>">
  <meta property="og:image" content="https://eltech-systems.com/assets/img/learn-ai.webp">
  <link href="../favicon.ico" rel="icon" sizes="any">
  <link href="../assets/img/favicon.png" rel="icon" type="image/png">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.min.css" rel="stylesheet">
  <link href="../assets/css/main.css?v=<?= asset_version('css/main.css') ?>" rel="stylesheet">
  <link href="../assets/css/academy.css?v=<?= asset_version('css/academy.css') ?>" rel="stylesheet">
</head>
<body class="academy">
  <a class="skip-link" href="#main">Skip to content</a>

  <header class="header" id="header">
    <div class="container header-inner">
      <a href="index.php" class="brand">
        <img src="../assets/img/logo.png" alt="Eltech Systems logo" width="46" height="36">
        <span class="brand-text">
          <span class="brand-name">Eltech Academy</span>
          <span class="brand-tag">Learn AI, step by step</span>
        </span>
      </a>

      <?php if ($user): ?>
        <div class="acad-user">
          <a href="../index.html" class="acad-link" title="Eltech Systems website"><i class="bi bi-house" aria-hidden="true"></i><span>Home</span></a>
          <a href="index.php" class="acad-link acad-home"><i class="bi bi-grid" aria-hidden="true"></i><span>My learning</span></a>
          <?php if (is_admin($user)): ?>
            <a href="admin.php" class="acad-link"><i class="bi bi-speedometer2" aria-hidden="true"></i><span>Admin</span></a>
          <?php endif; ?>
          <?php if ($user['picture']): ?>
            <img class="avatar" src="<?= h($user['picture']) ?>" alt="" width="34" height="34" referrerpolicy="no-referrer">
          <?php else: ?>
            <span class="avatar avatar-letter" aria-hidden="true"><?= h(mb_strtoupper(mb_substr($user['name'], 0, 1))) ?></span>
          <?php endif; ?>
          <form action="api/logout.php" method="post">
            <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">
            <button type="submit" class="acad-link" title="Sign out"><i class="bi bi-box-arrow-right" aria-hidden="true"></i><span>Sign out</span></button>
          </form>
        </div>
      <?php else: ?>
        <div class="acad-user">
          <a href="../index.html" class="acad-link" title="Eltech Systems website"><i class="bi bi-house" aria-hidden="true"></i><span>Home</span></a>
          <a href="../learn.html" class="acad-link"><i class="bi bi-info-circle" aria-hidden="true"></i><span>About the courses</span></a>
        </div>
      <?php endif; ?>
    </div>
  </header>

  <main id="main">
  <?php if ($message = flash()): ?>
    <div class="container"><p class="flash" role="status"><?= h($message) ?></p></div>
  <?php endif;
}

function page_end(array $opts = []): void
{
    ?>
  </main>

  <footer class="footer acad-footer">
    <div class="container footer-bottom">
      <p>&copy; <?= date('Y') ?> Eltech Systems &middot; <a href="../index.html">eltech-systems.com</a> &middot; <a href="https://wa.me/256705636699" target="_blank" rel="noopener">Help on WhatsApp</a></p>
    </div>
  </footer>

  <script src="../assets/js/academy.js?v=<?= asset_version('js/academy.js') ?>"></script>
  <?php foreach ($opts['scripts'] ?? [] as $src): ?>
    <script src="<?= h($src) ?>" async defer></script>
  <?php endforeach; ?>
</body>
</html>
<?php
}
