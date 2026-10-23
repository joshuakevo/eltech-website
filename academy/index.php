<?php
require __DIR__ . '/lib/bootstrap.php';
require __DIR__ . '/lib/layout.php';

$user = current_user();

/* ---------- Not signed in: sign-in page ---------- */
if (!$user) {
    $clientId = config()['google_client_id'] ?? '';
    page_start('Start learning AI', null, ['index' => true]);
    ?>
    <section class="acad-hero">
      <div class="container acad-signin">
        <div>
          <p class="eyebrow">Eltech Academy</p>
          <h1>Learn AI step by step. Start free today.</h1>
          <p class="lead">Short lessons, quick quizzes and real tasks for your work. Earn points, badges and a certificate as you go.</p>
          <ul class="hero-ticks">
            <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i> First lessons free</li>
            <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i> Learn on your phone</li>
            <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i> Feedback from our team</li>
          </ul>
        </div>

        <div class="signin-card">
          <h2>Sign in to start</h2>
          <p>Use your Google account. It takes a few seconds, and your progress is saved.</p>

          <?php if ($clientId): ?>
            <div id="g_id_onload" data-client_id="<?= h($clientId) ?>" data-callback="onGoogleSignIn" data-auto_prompt="false"></div>
            <div class="g_id_signin" data-type="standard" data-size="large" data-theme="filled_blue" data-text="continue_with" data-shape="pill" data-logo_alignment="left"></div>
          <?php else: ?>
            <p class="flash">Google sign-in is not set up yet.</p>
          <?php endif; ?>

          <?php if (dev_login_allowed()): ?>
            <form class="dev-login" data-dev-login>
              <p><strong>Local test login</strong> (only works on this computer)</p>
              <input type="text" name="name" placeholder="Name" value="Test Student" required>
              <input type="email" name="email" placeholder="Email" value="student@example.com" required>
              <button type="submit" class="btn btn-outline btn-sm">Sign in for testing</button>
            </form>
          <?php endif; ?>

          <p class="signin-status form-status" role="status" aria-live="polite"></p>
          <p class="signin-note">We only use your name, email and photo to save your progress.</p>
        </div>
      </div>
    </section>

    <section class="section-tight">
      <div class="container">
        <div class="row-head"><h2>6 practical courses</h2><span class="muted">Sign in to start any of them free</span></div>
        <div class="course-strip">
          <?php foreach (course_catalog() as $slug => $c): ?>
            <div class="strip-item">
              <img src="../assets/img/courses/<?= h($slug) ?>.svg" alt="" width="640" height="280" loading="lazy">
              <strong><?= h($c['title']) ?></strong>
              <span><?= h($c['level']) ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php
    page_end(['scripts' => $clientId ? ['https://accounts.google.com/gsi/client'] : []]);
    exit;
}

/* ---------- Signed in: dashboard ---------- */
record_activity((int) $user['id']);
$stats = student_stats((int) $user['id']);
$firstName = explode(' ', trim($user['name']))[0];
$prices = course_prices();

// Progress per course, and the next lesson to continue with
$cards = [];
$continue = null;
foreach (course_catalog() as $slug => $info) {
    $card = $info + ['slug' => $slug, 'done' => 0, 'total' => 0, 'minutes' => 0, 'free' => 0, 'next' => null, 'access' => false];
    if ($info['status'] === 'live') {
        $course = load_course($slug);
        $card['access'] = has_access((int) $user['id'], $slug);
        $states = lesson_states($course, course_progress((int) $user['id'], $slug), $card['access']);
        $card['total'] = count($course['lessons']);
        $card['minutes'] = array_sum(array_column($course['lessons'], 'minutes'));
        $card['free'] = $course['free_lessons'];
        $card['done'] = count(array_filter($states, fn ($s) => $s === 'done'));
        foreach ($course['lessons'] as $lesson) {
            if ($states[$lesson['slug']] === 'open') {
                $card['next'] = $lesson;
                break;
            }
        }
        if (!$continue && $card['next'] && $card['done'] > 0) {
            $continue = $card;
        }
    }
    $cards[] = $card;
}

// Courses in progress first, then the rest in catalogue order
$inProgress = fn ($c) => $c['done'] > 0 && $c['done'] < $c['total'];
usort($cards, fn ($a, $b) => $inProgress($b) <=> $inProgress($a));

page_start('My learning', $user);
?>
<section class="acad-welcome">
  <div class="container welcome-grid">
    <div>
      <h1>Welcome back, <?= h($firstName) ?></h1>
      <p class="lead"><?= $stats['lessons'] ? 'Keep going. Every lesson makes AI more useful for your work.' : 'Pick a course below. Your first lesson takes about 10 minutes.' ?></p>
    </div>
    <ul class="stats">
      <li><i class="bi bi-star-fill" aria-hidden="true"></i><strong><?= $stats['points'] ?></strong><span>points</span></li>
      <li><i class="bi bi-fire" aria-hidden="true"></i><strong><?= $stats['streak'] ?></strong><span>streak</span></li>
      <li><i class="bi bi-check2-circle" aria-hidden="true"></i><strong><?= $stats['lessons'] ?></strong><span>lessons</span></li>
      <li><i class="bi bi-award" aria-hidden="true"></i><strong><?= count(array_filter($stats['badges'], fn ($b) => $b['earned'])) ?></strong><span>badges</span></li>
    </ul>
  </div>
</section>

<?php if ($continue): ?>
<section class="container">
  <a class="continue-card" href="lesson.php?c=<?= h($continue['slug']) ?>&amp;l=<?= h($continue['next']['slug']) ?>">
    <img src="../assets/img/courses/<?= h($continue['slug']) ?>.svg" alt="" width="640" height="280">
    <span class="continue-text">
      <span class="continue-label">Continue where you left off</span>
      <strong><?= h($continue['next']['title']) ?></strong>
      <span><?= h($continue['title']) ?> &middot; <?= (int) $continue['next']['minutes'] ?> min</span>
    </span>
    <i class="bi bi-play-circle-fill" aria-hidden="true"></i>
  </a>
</section>
<?php endif; ?>

<section class="section-tight">
  <div class="container">
    <div class="row-head">
      <h2>Courses</h2>
      <span class="muted">First lessons of every course are free</span>
    </div>
    <div class="acad-courses">
      <?php foreach ($cards as $card):
          $live = $card['status'] === 'live';
          $pct = $card['total'] ? round($card['done'] / $card['total'] * 100) : 0;
          $finished = $card['total'] && $card['done'] === $card['total']; ?>
        <?php if ($live): ?><a class="acad-course" href="course.php?c=<?= h($card['slug']) ?>"><?php else: ?><div class="acad-course is-soon"><?php endif; ?>
          <span class="course-cover">
            <img src="../assets/img/courses/<?= h($card['slug']) ?>.svg" alt="" width="640" height="280" loading="lazy">
            <span class="cover-level"><?= h($card['level']) ?></span>
            <?php if ($finished): ?><span class="cover-done"><i class="bi bi-award-fill" aria-hidden="true"></i> Completed</span><?php endif; ?>
          </span>
          <span class="course-info">
            <strong class="course-title"><?= h($card['title']) ?></strong>
            <span class="course-tagline"><?= h($card['tagline']) ?></span>
            <?php if ($live): ?>
              <span class="course-meta">
                <span><i class="bi bi-collection-play" aria-hidden="true"></i> <?= $card['total'] ?> lessons</span>
                <span><i class="bi bi-clock" aria-hidden="true"></i> <?= round($card['minutes'] / 5) * 5 ?> min</span>
              </span>
              <?php if ($card['done']): ?>
                <span class="progress" role="progressbar" aria-valuenow="<?= $pct ?>" aria-valuemin="0" aria-valuemax="100" aria-label="Course progress"><span style="width: <?= $pct ?>%"></span></span>
              <?php endif; ?>
              <span class="course-foot">
                <span class="course-price-tag">
                  <?php if ($card['access']): ?><i class="bi bi-unlock-fill" aria-hidden="true"></i> Full access
                  <?php elseif ($card['done']): ?><?= $card['done'] ?> of <?= $card['total'] ?> done
                  <?php else: ?><?= (int) $card['free'] ?> lessons free &middot; UGX <?= number_format($prices[$card['slug']]) ?><?php endif; ?>
                </span>
                <span class="course-cta"><?= $card['done'] ? ($finished ? 'Review' : 'Continue') : ($card['access'] ? 'Start' : 'Start free') ?> <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
              </span>
            <?php else: ?>
              <span class="course-foot"><span class="course-price-tag">Coming soon</span></span>
            <?php endif; ?>
          </span>
        <?php if ($live): ?></a><?php else: ?></div><?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <div class="row-head"><h2>Your badges</h2></div>
    <ul class="badges">
      <?php foreach ($stats['badges'] as $badge): ?>
        <li class="<?= $badge['earned'] ? 'earned' : '' ?>">
          <i class="bi <?= h($badge['icon']) ?>" aria-hidden="true"></i>
          <span><strong><?= h($badge['name']) ?></strong><?= $badge['earned'] ? 'Earned' : h($badge['hint']) ?></span>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
<?php
page_end();
