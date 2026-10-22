<?php
require __DIR__ . '/lib/bootstrap.php';
require __DIR__ . '/lib/layout.php';

$user = require_user();
$userId = (int) $user['id'];
$course = load_course((string) ($_GET['c'] ?? ''));
if (!$course) {
    flash('That course is not available yet.');
    redirect('index.php');
}

record_activity($userId);
$progress = course_progress($userId, $course['slug']);
$submissions = course_submissions($userId, $course['slug']);
$access = has_access($userId, $course['slug']);
$states = lesson_states($course, $progress, $access);
$done = count(array_filter($states, fn ($s) => $s === 'done'));
$total = count($course['lessons']);
$pct = round($done / $total * 100);
$complete = course_is_complete($course, $progress, $submissions);
$certificate = $complete ? ensure_certificate($userId, $course['slug']) : null;

$stmt = db()->prepare('SELECT 1 FROM academy_access_requests WHERE user_id = ? AND course = ? AND handled = 0');
$stmt->execute([$userId, $course['slug']]);
$requestPending = (bool) $stmt->fetch();

$next = null;
foreach ($course['lessons'] as $lesson) {
    if ($states[$lesson['slug']] === 'open') {
        $next = $lesson;
        break;
    }
}

$whatsText = rawurlencode("Hello Eltech, I'd like to unlock the full course \"{$course['title']}\". My academy email is {$user['email']}.");

page_start($course['title'], $user);
?>
<section class="acad-course-head">
  <div class="container">
    <a class="back-link" href="index.php"><i class="bi bi-arrow-left" aria-hidden="true"></i> My learning</a>
    <div class="course-head-grid">
      <div>
        <span class="level level-dark"><?= h($course['level']) ?></span>
        <h1><?= h($course['title']) ?></h1>
        <p class="lead"><?= h($course['description']) ?></p>
        <?php if ($next): ?>
          <a class="btn btn-primary" href="lesson.php?c=<?= h($course['slug']) ?>&amp;l=<?= h($next['slug']) ?>">
            <?= $done ? 'Continue: ' : 'Start: ' ?><?= h($next['title']) ?> <i class="bi bi-arrow-right" aria-hidden="true"></i>
          </a>
        <?php endif; ?>
      </div>
      <div class="course-progress-card">
        <div class="ring" style="--pct: <?= $pct ?>"><span><?= $pct ?>%</span></div>
        <p><strong><?= $done ?> of <?= $total ?></strong> lessons complete</p>
        <p class="muted"><?= $complete ? 'Course complete. Well done!' : 'Pass each quiz to unlock the next lesson.' ?></p>
      </div>
    </div>
  </div>
</section>

<section class="section-tight">
  <div class="container course-body">
    <div>
      <?php if ($certificate): ?>
        <div class="cert-banner">
          <i class="bi bi-award-fill" aria-hidden="true"></i>
          <div><strong>You earned your certificate!</strong><span>Share it with your network.</span></div>
          <a class="btn btn-primary" href="certificate.php?code=<?= h($certificate) ?>">View certificate</a>
        </div>
      <?php endif; ?>

      <h2>Lessons</h2>
      <ol class="lesson-list">
        <?php foreach ($course['lessons'] as $i => $lesson):
            $state = $states[$lesson['slug']];
            $sub = $submissions[$lesson['slug']] ?? null; ?>
          <li class="lesson-item is-<?= $state ?>">
            <span class="lesson-num">
              <?php if ($state === 'done'): ?><i class="bi bi-check-lg" aria-hidden="true"></i>
              <?php elseif ($state === 'paid'): ?><i class="bi bi-gem" aria-hidden="true"></i>
              <?php elseif ($state === 'locked'): ?><i class="bi bi-lock" aria-hidden="true"></i>
              <?php else: ?><?= $i + 1 ?><?php endif; ?>
            </span>
            <div class="lesson-info">
              <?php if ($state === 'done' || $state === 'open'): ?>
                <a href="lesson.php?c=<?= h($course['slug']) ?>&amp;l=<?= h($lesson['slug']) ?>"><?= h($lesson['title']) ?></a>
              <?php else: ?>
                <span class="lesson-title"><?= h($lesson['title']) ?></span>
              <?php endif; ?>
              <span class="lesson-meta">
                <?= (int) $lesson['minutes'] ?> min
                <?php if ($i < $course['free_lessons']): ?> &middot; <span class="tag-free">Free</span><?php endif; ?>
                <?php if ($state === 'locked'): ?> &middot; Finish the lesson before<?php endif; ?>
                <?php if ($state === 'paid'): ?> &middot; Full course<?php endif; ?>
                <?php if ($sub): ?> &middot; Task: <?= $sub['status'] === 'approved' ? 'approved' : ($sub['status'] === 'needs_work' ? 'needs work' : 'submitted') ?><?php endif; ?>
              </span>
            </div>
          </li>
        <?php endforeach; ?>
      </ol>
    </div>

    <aside>
      <?php if (!$access): ?>
        <div class="unlock-card" id="unlock">
          <i class="bi bi-gem" aria-hidden="true"></i>
          <h2>Unlock the full course</h2>
          <p>The first <?= (int) $course['free_lessons'] ?> lessons are free. Unlock all <?= $total ?> lessons, the practical tasks and your certificate.</p>
          <?php if (!empty($course['price'])): ?>
            <p class="unlock-price"><?= h($course['price']) ?></p>
          <?php endif; ?>
          <ol class="unlock-steps">
            <li>Pay by mobile money or bank. <a href="https://wa.me/256705636699?text=<?= $whatsText ?>" target="_blank" rel="noopener">Ask for payment details on WhatsApp</a>.</li>
            <li>Tell us below once you have paid.</li>
            <li>We unlock your course, usually the same day.</li>
          </ol>
          <?php if ($requestPending): ?>
            <p class="notify-done"><i class="bi bi-hourglass-split" aria-hidden="true"></i> Thanks! We are confirming your payment.</p>
          <?php else: ?>
            <form class="js-academy-form" data-endpoint="api/request-access.php" data-reload>
              <input type="hidden" name="course" value="<?= h($course['slug']) ?>">
              <label for="note">Payment details</label>
              <textarea id="note" name="note" rows="3" required maxlength="1000" placeholder="For example: paid by MTN MoMo, transaction ID 123456789"></textarea>
              <button type="submit" class="btn btn-primary">I have paid, unlock my course</button>
              <p class="form-status" role="status" aria-live="polite"></p>
            </form>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <div class="outcomes-card">
        <h2>You will be able to</h2>
        <ul class="checks">
          <?php foreach ($course['outcomes'] as $outcome): ?>
            <li><?= h($outcome) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </aside>
  </div>
</section>
<?php
page_end();
