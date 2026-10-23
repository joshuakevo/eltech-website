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
          <p class="unlock-price">UGX <?= number_format((float) $course['price']) ?> <span>one-time</span></p>

          <?php if (marzpay_configured()): ?>
            <button type="button" class="btn btn-primary btn-pay" data-open-pay>
              <i class="bi bi-phone" aria-hidden="true"></i> Pay with mobile money
            </button>
            <p class="pay-note"><i class="bi bi-lightning-charge-fill" aria-hidden="true"></i> MTN or Airtel. Your course unlocks instantly.</p>
          <?php endif; ?>

          <details class="pay-other"<?= marzpay_configured() ? '' : ' open' ?>>
            <summary>Paid by bank or another way?</summary>
            <ol class="unlock-steps">
              <li>Pay by bank or mobile money. <a href="https://wa.me/256705636699?text=<?= $whatsText ?>" target="_blank" rel="noopener">Ask for payment details on WhatsApp</a>.</li>
              <li>Tell us below once you have paid.</li>
              <li>We unlock your course, usually the same day.</li>
            </ol>
            <?php if ($requestPending): ?>
              <p class="notify-done"><i class="bi bi-hourglass-split" aria-hidden="true"></i> Thanks! We are confirming your payment.</p>
            <?php else: ?>
              <form class="js-academy-form" data-endpoint="api/request-access.php" data-reload>
                <input type="hidden" name="course" value="<?= h($course['slug']) ?>">
                <label for="note">Payment details</label>
                <textarea id="note" name="note" rows="3" required maxlength="1000" placeholder="For example: paid by bank transfer on 3 Nov, reference 12345"></textarea>
                <button type="submit" class="btn btn-outline">I have paid, unlock my course</button>
                <p class="form-status" role="status" aria-live="polite"></p>
              </form>
            <?php endif; ?>
          </details>
        </div>

        <?php if (marzpay_configured()): ?>
        <dialog class="modal pay-modal" id="pay-modal" aria-labelledby="pay-title" data-course="<?= h($course['slug']) ?>">
          <div class="modal-body">
            <button type="button" class="modal-close" aria-label="Close" data-close-pay><i class="bi bi-x-lg" aria-hidden="true"></i></button>

            <div class="pay-step" data-step="form">
              <p class="kicker">Unlock the full course</p>
              <h2 id="pay-title"><?= h($course['title']) ?></h2>
              <p class="pay-amount">UGX <?= number_format((float) $course['price']) ?></p>
              <form class="pay-form" novalidate>
                <label for="pay-phone">Your MTN or Airtel number</label>
                <input type="tel" id="pay-phone" name="phone" inputmode="tel" autocomplete="tel" placeholder="0772 123456" required maxlength="16">
                <button type="submit" class="btn btn-primary">Pay UGX <?= number_format((float) $course['price']) ?></button>
                <p class="form-status" role="status" aria-live="polite"></p>
              </form>
              <p class="pay-secure"><i class="bi bi-shield-lock" aria-hidden="true"></i> Secure payment by Marz Pay. We never see your PIN.</p>
            </div>

            <div class="pay-step" data-step="waiting" hidden>
              <div class="pay-phone-anim" aria-hidden="true"><i class="bi bi-phone-vibrate"></i></div>
              <h2>Check your phone</h2>
              <p class="pay-wait-msg">Enter your mobile money PIN to approve the payment.</p>
              <p class="muted pay-wait-hint">Waiting for confirmation… This usually takes under a minute.</p>
            </div>

            <div class="pay-step" data-step="done" hidden>
              <div class="pay-icon pay-icon-ok" aria-hidden="true"><i class="bi bi-check-lg"></i></div>
              <h2>Course unlocked!</h2>
              <p>Payment received. All lessons, tasks and your certificate are now open.</p>
              <a class="btn btn-primary" href="course.php?c=<?= h($course['slug']) ?>">Continue learning <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
            </div>

            <div class="pay-step" data-step="failed" hidden>
              <div class="pay-icon pay-icon-fail" aria-hidden="true"><i class="bi bi-x-lg"></i></div>
              <h2>Payment not completed</h2>
              <p class="pay-fail-msg">The payment was cancelled or did not go through. No money was taken.</p>
              <button type="button" class="btn btn-primary" data-pay-retry>Try again</button>
            </div>
          </div>
        </dialog>
        <?php endif; ?>
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
