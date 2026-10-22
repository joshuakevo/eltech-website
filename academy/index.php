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
    <?php
    page_end(['scripts' => $clientId ? ['https://accounts.google.com/gsi/client'] : []]);
    exit;
}

/* ---------- Signed in: dashboard ---------- */
record_activity((int) $user['id']);
$stats = student_stats((int) $user['id']);
$firstName = explode(' ', trim($user['name']))[0];

$stmt = db()->prepare('SELECT course FROM academy_interest WHERE user_id = ?');
$stmt->execute([$user['id']]);
$interested = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Progress per live course, and the next lesson to continue with
$cards = [];
$continue = null;
foreach (course_catalog() as $slug => $info) {
    $card = $info + ['slug' => $slug, 'done' => 0, 'total' => 0, 'next' => null];
    if ($info['status'] === 'live') {
        $course = load_course($slug);
        $progress = course_progress((int) $user['id'], $slug);
        $states = lesson_states($course, $progress, has_access((int) $user['id'], $slug));
        $card['total'] = count($course['lessons']);
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

page_start('My learning', $user);
?>
<section class="acad-welcome">
  <div class="container">
    <h1>Welcome back, <?= h($firstName) ?></h1>
    <p class="lead"><?= $stats['lessons'] ? 'Keep going. Every lesson makes AI more useful for your work.' : 'Your AI journey starts here. Your first lesson takes about 10 minutes.' ?></p>

    <ul class="stats">
      <li><i class="bi bi-star-fill" aria-hidden="true"></i><strong><?= $stats['points'] ?></strong><span>points</span></li>
      <li><i class="bi bi-fire" aria-hidden="true"></i><strong><?= $stats['streak'] ?></strong><span>day streak</span></li>
      <li><i class="bi bi-check2-circle" aria-hidden="true"></i><strong><?= $stats['lessons'] ?></strong><span>lessons done</span></li>
      <li><i class="bi bi-award" aria-hidden="true"></i><strong><?= count(array_filter($stats['badges'], fn ($b) => $b['earned'])) ?></strong><span>badges</span></li>
    </ul>
  </div>
</section>

<?php if ($continue): ?>
<section class="container">
  <a class="continue-card" href="lesson.php?c=<?= h($continue['slug']) ?>&amp;l=<?= h($continue['next']['slug']) ?>">
    <span class="continue-label">Continue where you left off</span>
    <strong><?= h($continue['next']['title']) ?></strong>
    <span><?= h($continue['title']) ?> &middot; <?= (int) $continue['next']['minutes'] ?> min</span>
    <i class="bi bi-play-circle-fill" aria-hidden="true"></i>
  </a>
</section>
<?php endif; ?>

<section class="section-tight">
  <div class="container">
    <h2>Courses</h2>
    <div class="acad-courses">
      <?php foreach ($cards as $card): ?>
        <article class="acad-course<?= $card['status'] === 'live' ? '' : ' is-soon' ?>">
          <div class="course-top">
            <span class="course-icon"><i class="bi <?= h($card['icon']) ?>" aria-hidden="true"></i></span>
            <span class="level"><?= $card['status'] === 'live' ? h($card['level']) : 'Coming soon' ?></span>
          </div>
          <h3><?= h($card['title']) ?></h3>
          <p class="course-lead"><?= h($card['tagline']) ?></p>

          <?php if ($card['status'] === 'live'): ?>
            <?php $pct = $card['total'] ? round($card['done'] / $card['total'] * 100) : 0; ?>
            <div class="progress" role="progressbar" aria-valuenow="<?= $pct ?>" aria-valuemin="0" aria-valuemax="100" aria-label="Course progress">
              <span style="width: <?= $pct ?>%"></span>
            </div>
            <p class="progress-label"><?= $card['done'] ?> of <?= $card['total'] ?> lessons</p>
            <a class="btn btn-primary" href="course.php?c=<?= h($card['slug']) ?>"><?= $card['done'] ? ($card['done'] === $card['total'] ? 'Review course' : 'Continue') : 'Start free' ?></a>
          <?php elseif (in_array($card['slug'], $interested, true)): ?>
            <p class="notify-done"><i class="bi bi-bell-fill" aria-hidden="true"></i> We will tell you when it opens</p>
          <?php else: ?>
            <button type="button" class="btn btn-outline" data-notify="<?= h($card['slug']) ?>">Notify me</button>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <h2>Your badges</h2>
    <ul class="badges">
      <?php foreach ($stats['badges'] as $badge): ?>
        <li class="<?= $badge['earned'] ? 'earned' : '' ?>">
          <i class="bi <?= h($badge['icon']) ?>" aria-hidden="true"></i>
          <strong><?= h($badge['name']) ?></strong>
          <span><?= $badge['earned'] ? 'Earned' : h($badge['hint']) ?></span>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
<?php
page_end();
