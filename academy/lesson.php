<?php
require __DIR__ . '/lib/bootstrap.php';
require __DIR__ . '/lib/layout.php';

$user = require_user();
$userId = (int) $user['id'];
$course = load_course((string) ($_GET['c'] ?? ''));
$requested = (string) ($_GET['l'] ?? '');
$isIntro = $course && $requested === 'intro' && $course['intro'];
$index = $course && !$isIntro ? find_lesson($course, $requested) : null;
if (!$isIntro && $index === null) {
    flash('That lesson could not be found.');
    redirect($course ? 'course.php?c=' . $course['slug'] : 'index.php');
}

$progress = course_progress($userId, $course['slug']);
$states = lesson_states($course, $progress, has_access($userId, $course['slug']));
$base = 'lesson.php?c=' . rawurlencode($course['slug']) . '&l=';

/** Left-hand lesson list; $current is a lesson slug or 'intro'. */
function side_list(array $course, array $states, string $base, string $current, bool $introDone): void
{
    ?>
    <a class="back-link" href="course.php?c=<?= h($course['slug']) ?>"><i class="bi bi-arrow-left" aria-hidden="true"></i> <?= h($course['title']) ?></a>
    <ol class="side-list">
      <?php if ($course['intro']): ?>
        <li class="is-<?= $introDone ? 'done' : 'open' ?><?= $current === 'intro' ? ' is-current' : '' ?>">
          <span class="side-icon"><i class="bi <?= $introDone ? 'bi-check-circle-fill' : 'bi-play-circle' ?>" aria-hidden="true"></i></span>
          <a href="<?= h($base . 'intro') ?>"<?= $current === 'intro' ? ' aria-current="page"' : '' ?>>Introduction</a>
        </li>
      <?php endif; ?>
      <?php foreach ($course['lessons'] as $l):
          $s = $states[$l['slug']]; ?>
        <li class="is-<?= $s ?><?= $l['slug'] === $current ? ' is-current' : '' ?>">
          <span class="side-icon"><i class="bi <?= $s === 'done' ? 'bi-check-circle-fill' : ($s === 'open' ? 'bi-circle' : ($s === 'paid' ? 'bi-gem' : 'bi-lock')) ?>" aria-hidden="true"></i></span>
          <?php if ($s === 'done' || $s === 'open'): ?>
            <a href="<?= h($base . rawurlencode($l['slug'])) ?>"<?= $l['slug'] === $current ? ' aria-current="page"' : '' ?>><?= h($l['title']) ?></a>
          <?php else: ?>
            <span><?= h($l['title']) ?></span>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ol>
    <?php
}

/* ---------- Introduction: the course video, played inside the course ---------- */
if ($isIntro) {
    record_activity($userId);
    $introDone = intro_watched($progress);
    $first = $base . rawurlencode($course['lessons'][0]['slug']);
    page_start('Introduction: ' . $course['title'], $user);
    ?>
    <div class="container lesson-layout">
      <aside class="lesson-side"><?php side_list($course, $states, $base, 'intro', $introDone); ?></aside>

      <article class="lesson">
        <p class="lesson-kicker">Introduction &middot; 45 seconds</p>
        <h1>Welcome to <?= h($course['title']) ?></h1>
        <p class="lesson-summary">Start here: see what you will achieve in this course. Watch to the end to unlock lesson 1.</p>

        <div class="intro-player<?= $introDone ? ' watched' : '' ?>" data-course="<?= h($course['slug']) ?>">
          <video controls playsinline preload="metadata" poster="../assets/video/<?= h($course['intro']) ?>.jpg">
            <source src="../assets/video/<?= h($course['intro']) ?>.mp4" type="video/mp4">
          </video>
          <?php if ($introDone): ?><span class="intro-watched"><i class="bi bi-check-circle-fill" aria-hidden="true"></i> Watched</span><?php endif; ?>
          <div class="intro-end">
            <p><i class="bi bi-unlock-fill" aria-hidden="true"></i> Lesson 1 is unlocked. It takes about <?= (int) $course['lessons'][0]['minutes'] ?> minutes.</p>
            <a class="btn btn-primary" href="<?= h($first) ?>">Start lesson 1 <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
            <button type="button" class="btn btn-ghost" data-replay><i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> Watch again</button>
          </div>
        </div>

        <div class="takeaways">
          <h2><i class="bi bi-flag" aria-hidden="true"></i> By the end of this course you will be able to</h2>
          <ul class="checks">
            <?php foreach ($course['outcomes'] as $outcome): ?>
              <li><?= h($outcome) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>

        <nav class="lesson-nav" aria-label="Lesson navigation">
          <a class="btn btn-outline" href="course.php?c=<?= h($course['slug']) ?>"><i class="bi bi-arrow-left" aria-hidden="true"></i> Course overview</a>
          <a class="btn btn-primary next-lesson intro-next<?= $introDone ? '' : ' is-disabled' ?>" href="<?= h($first) ?>"<?= $introDone ? '' : ' aria-disabled="true"' ?>>Start lesson 1 <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
        </nav>
      </article>
    </div>
    <?php
    page_end();
    exit;
}

$lesson = $course['lessons'][$index];
$state = $states[$lesson['slug']];

if ($state === 'paid') {
    flash('This lesson is part of the full course. Unlock it to continue.');
    redirect('course.php?c=' . $course['slug'] . '#unlock');
}
if ($state === 'locked' && $index === 0) {
    flash('Watch the introduction first. It unlocks lesson 1.');
    redirect($base . 'intro');
}
if ($state === 'locked') {
    flash('Pass the quiz in the previous lesson to unlock this one.');
    redirect('course.php?c=' . $course['slug']);
}

record_activity($userId);
$row = $progress[$lesson['slug']] ?? null;
$submission = course_submissions($userId, $course['slug'])[$lesson['slug']] ?? null;
$total = count($course['lessons']);
$prev = $course['lessons'][$index - 1] ?? ($course['intro'] ? ['slug' => 'intro', 'title' => 'Introduction'] : null);
$next = $course['lessons'][$index + 1] ?? null;

page_start($lesson['title'], $user);
?>
<div class="lesson-progress" aria-hidden="true"><span style="width: <?= round(($index + ($state === 'done' ? 1 : 0)) / $total * 100) ?>%"></span></div>

<div class="container lesson-layout">
  <aside class="lesson-side"><?php side_list($course, $states, $base, $lesson['slug'], intro_watched($progress)); ?></aside>

  <article class="lesson">
    <p class="lesson-kicker">Lesson <?= $index + 1 ?> of <?= $total ?> &middot; <?= (int) $lesson['minutes'] ?> min</p>
    <h1><?= h($lesson['title']) ?></h1>
    <p class="lesson-summary"><?= h($lesson['summary']) ?></p>

    <div class="lesson-body">
      <?= $lesson['body'] /* trusted course content written by Eltech */ ?>
    </div>

    <div class="takeaways">
      <h2><i class="bi bi-key" aria-hidden="true"></i> Key points</h2>
      <ul class="checks">
        <?php foreach ($lesson['takeaways'] as $point): ?>
          <li><?= h($point) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- Quiz -->
    <section class="quiz" id="quiz">
      <div class="quiz-head">
        <h2><i class="bi bi-patch-question" aria-hidden="true"></i> Quick check</h2>
        <?php if ($row && $row['completed_at']): ?>
          <span class="quiz-badge">Passed &middot; <?= (int) $row['quiz_score'] ?>/<?= (int) $row['quiz_total'] ?></span>
        <?php else: ?>
          <span class="quiz-badge quiz-badge-todo">Pass to unlock the next lesson</span>
        <?php endif; ?>
      </div>

      <form class="quiz-form" data-course="<?= h($course['slug']) ?>" data-lesson="<?= h($lesson['slug']) ?>">
        <?php foreach ($lesson['quiz'] as $qi => $q): ?>
          <fieldset class="question" data-q="<?= $qi ?>">
            <legend><span><?= $qi + 1 ?>.</span> <?= h($q['q']) ?></legend>
            <?php
            // Shuffle options per student (stable on reload); values keep the original index for marking
            $order = array_keys($q['options']);
            mt_srand(crc32($userId . '|' . $lesson['slug'] . '|' . $qi));
            shuffle($order);
            mt_srand();
            foreach ($order as $oi):
                $option = $q['options'][$oi]; ?>
              <label class="option">
                <input type="radio" name="a[<?= $qi ?>]" value="<?= $oi ?>" required>
                <span><?= h($option) ?></span>
              </label>
            <?php endforeach; ?>
            <p class="question-feedback" hidden></p>
          </fieldset>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary">Check my answers</button>
        <div class="quiz-result" role="status" aria-live="polite" hidden></div>
      </form>
    </section>

    <!-- Practical task -->
    <section class="task" id="task">
      <div class="quiz-head">
        <h2><i class="bi bi-tools" aria-hidden="true"></i> Practical task: <?= h($lesson['task']['title']) ?></h2>
        <?php if ($submission): ?>
          <span class="quiz-badge<?= $submission['status'] === 'needs_work' ? ' quiz-badge-todo' : '' ?>">
            <?= $submission['status'] === 'approved' ? 'Approved' : ($submission['status'] === 'needs_work' ? 'Needs work' : 'Submitted, awaiting review') ?>
          </span>
        <?php endif; ?>
      </div>
      <div class="task-brief"><?= $lesson['task']['brief'] /* trusted course content */ ?></div>

      <?php if ($submission && $submission['feedback']): ?>
        <div class="feedback">
          <i class="bi bi-chat-quote" aria-hidden="true"></i>
          <div><strong>Feedback from Eltech</strong><p><?= nl2br(h($submission['feedback'])) ?></p></div>
        </div>
      <?php endif; ?>

      <form class="js-academy-form task-form" data-endpoint="api/submit-task.php">
        <input type="hidden" name="course" value="<?= h($course['slug']) ?>">
        <input type="hidden" name="lesson" value="<?= h($lesson['slug']) ?>">
        <label for="answer"><?= h($lesson['task']['submit_hint']) ?></label>
        <textarea id="answer" name="answer" rows="6" required maxlength="8000"><?= h($submission['answer'] ?? '') ?></textarea>
        <label for="link">Link <span class="optional">(optional, for example Google Drive)</span></label>
        <input type="url" id="link" name="link" maxlength="500" placeholder="https://" value="<?= h($submission['link'] ?? '') ?>">
        <button type="submit" class="btn btn-outline"><?= $submission ? 'Update my task' : 'Submit my task' ?></button>
        <p class="form-status" role="status" aria-live="polite"></p>
      </form>
    </section>

    <nav class="lesson-nav" aria-label="Lesson navigation">
      <?php if ($prev): ?>
        <a class="btn btn-outline" href="<?= h($base . rawurlencode($prev['slug'])) ?>"><i class="bi bi-arrow-left" aria-hidden="true"></i> Previous</a>
      <?php else: ?><span></span><?php endif; ?>
      <?php if ($next): ?>
        <a class="btn btn-primary next-lesson<?= $state === 'done' ? '' : ' is-disabled' ?>" href="<?= h($base . rawurlencode($next['slug'])) ?>"
           <?= $state === 'done' ? '' : 'aria-disabled="true"' ?>>Next lesson <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
      <?php else: ?>
        <a class="btn btn-primary" href="course.php?c=<?= h($course['slug']) ?>">Back to course <i class="bi bi-flag" aria-hidden="true"></i></a>
      <?php endif; ?>
    </nav>
  </article>
</div>
<?php
page_end();
