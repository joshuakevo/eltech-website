<?php
/**
 * Admin: review practical tasks, unlock paid courses, see students.
 * Only emails listed in admin_emails (server config) can open this page.
 */
require __DIR__ . '/lib/bootstrap.php';
require __DIR__ . '/lib/layout.php';

$user = require_user();
if (!is_admin($user)) {
    http_response_code(403);
    exit('Not allowed.');
}
$pdo = db();
$catalog = course_catalog();
$liveCourses = array_filter($catalog, fn ($c) => $c['status'] === 'live');

$emailed = false;

/* ---------- Actions ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_post_with_csrf();
    $action = $_POST['action'] ?? '';
    $tab = $_POST['tab'] ?? 'tasks';

    if ($action === 'review') {
        $status = ($_POST['status'] ?? '') === 'approved' ? 'approved' : 'needs_work';
        $feedback = trim(mb_substr((string) ($_POST['feedback'] ?? ''), 0, 4000));
        $pdo->prepare('UPDATE academy_submissions SET status = ?, feedback = ?, reviewed_at = NOW() WHERE id = ?')
            ->execute([$status, $feedback, (int) $_POST['id']]);

        $stmt = $pdo->prepare('SELECT s.course, s.lesson, u.email, u.name FROM academy_submissions s JOIN academy_users u ON u.id = s.user_id WHERE s.id = ?');
        $stmt->execute([(int) $_POST['id']]);
        if ($row = $stmt->fetch()) {
            $link = "https://eltech-systems.com/academy/lesson.php?c={$row['course']}&l={$row['lesson']}#task";
            $emailed = send_mail(
                $row['email'],
                $status === 'approved' ? 'Your task was approved!' : 'Feedback on your task',
                "Hi {$row['name']},\n\n"
                . ($status === 'approved' ? "Great work - your task was approved and you earned " . POINTS_TASK_APPROVED . " points.\n\n" : "We reviewed your task and left you some feedback.\n\n")
                . ($feedback ? "Feedback:\n$feedback\n\n" : '')
                . "See it here: $link\n\nEltech Academy"
            );
        }
        flash('Review saved.' . ($emailed ? ' The student has been emailed.' : ' (The email to the student could not be sent.)'));
    } elseif ($action === 'grant' && isset($liveCourses[$_POST['course'] ?? ''])) {
        $userId = (int) $_POST['user_id'];
        $course = $_POST['course'];
        $pdo->prepare('INSERT IGNORE INTO academy_access (user_id, course, granted_by, granted_at) VALUES (?, ?, ?, NOW())')
            ->execute([$userId, $course, $user['email']]);
        $pdo->prepare('UPDATE academy_access_requests SET handled = 1 WHERE user_id = ? AND course = ?')
            ->execute([$userId, $course]);

        $stmt = $pdo->prepare('SELECT email, name FROM academy_users WHERE id = ?');
        $stmt->execute([$userId]);
        if ($student = $stmt->fetch()) {
            $emailed = send_mail(
                $student['email'],
                'Your course is unlocked: ' . $catalog[$course]['title'],
                "Hi {$student['name']},\n\nThank you for your payment. \"{$catalog[$course]['title']}\" is now fully unlocked.\n\n"
                . "Continue here: https://eltech-systems.com/academy/course.php?c=$course\n\nEltech Academy"
            );
        }
        flash('Course unlocked.' . ($emailed ? ' The student has been emailed.' : ' (The email to the student could not be sent.)'));
    } elseif ($action === 'revoke') {
        $pdo->prepare('DELETE FROM academy_access WHERE user_id = ? AND course = ?')
            ->execute([(int) $_POST['user_id'], (string) $_POST['course']]);
        flash('Access removed.');
    } elseif ($action === 'dismiss') {
        $pdo->prepare('UPDATE academy_access_requests SET handled = 1 WHERE id = ?')->execute([(int) $_POST['id']]);
        flash('Request dismissed.');
    }
    redirect('admin.php?tab=' . rawurlencode($tab));
}

/* ---------- Data ---------- */
$tab = $_GET['tab'] ?? 'tasks';

$pendingTasks = (int) $pdo->query("SELECT COUNT(*) FROM academy_submissions WHERE status = 'submitted'")->fetchColumn();
$pendingRequests = (int) $pdo->query('SELECT COUNT(*) FROM academy_access_requests WHERE handled = 0')->fetchColumn();
$studentCount = (int) $pdo->query('SELECT COUNT(*) FROM academy_users')->fetchColumn();
$paidCount = (int) $pdo->query("SELECT COUNT(*) FROM academy_payments WHERE status = 'completed'")->fetchColumn();

function lesson_title(string $course, string $lesson): string
{
    $c = load_course($course);
    $i = $c ? find_lesson($c, $lesson) : null;
    return $i === null ? $lesson : $c['lessons'][$i]['title'];
}

$csrf = csrf_token();
page_start('Admin', $user);
?>
<section class="section-tight">
  <div class="container">
    <h1>Academy admin</h1>

    <nav class="admin-tabs" aria-label="Admin sections">
      <a href="?tab=tasks" <?= $tab === 'tasks' ? 'aria-current="page"' : '' ?>>Tasks to review <span><?= $pendingTasks ?></span></a>
      <a href="?tab=requests" <?= $tab === 'requests' ? 'aria-current="page"' : '' ?>>Unlock requests <span><?= $pendingRequests ?></span></a>
      <a href="?tab=students" <?= $tab === 'students' ? 'aria-current="page"' : '' ?>>Students <span><?= $studentCount ?></span></a>
      <a href="?tab=payments" <?= $tab === 'payments' ? 'aria-current="page"' : '' ?>>Payments <span><?= $paidCount ?></span></a>
    </nav>

<?php if ($tab === 'tasks'):
    $rows = $pdo->query(
        "SELECT s.*, u.name, u.email FROM academy_submissions s JOIN academy_users u ON u.id = s.user_id
         ORDER BY s.status = 'submitted' DESC, s.created_at DESC LIMIT 100"
    )->fetchAll(); ?>
    <?php if (!$rows): ?><p class="muted">No tasks submitted yet.</p><?php endif; ?>
    <?php foreach ($rows as $row): ?>
      <article class="admin-card<?= $row['status'] === 'submitted' ? ' is-pending' : '' ?>">
        <header>
          <strong><?= h($row['name']) ?></strong> <span class="muted"><?= h($row['email']) ?></span>
          <span class="quiz-badge<?= $row['status'] === 'submitted' ? ' quiz-badge-todo' : '' ?>"><?= h(ucfirst(str_replace('_', ' ', $row['status']))) ?></span>
        </header>
        <p class="muted"><?= h($catalog[$row['course']]['title'] ?? $row['course']) ?> &middot; <?= h(lesson_title($row['course'], $row['lesson'])) ?> &middot; <?= h(date('j M Y, H:i', strtotime($row['created_at']))) ?></p>
        <div class="admin-answer"><?= nl2br(h($row['answer'])) ?></div>
        <?php if ($row['link']): ?><p><a href="<?= h($row['link']) ?>" target="_blank" rel="noopener nofollow"><?= h($row['link']) ?></a></p><?php endif; ?>
        <form method="post" class="admin-review">
          <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
          <input type="hidden" name="action" value="review">
          <input type="hidden" name="tab" value="tasks">
          <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
          <label for="fb-<?= (int) $row['id'] ?>">Feedback for the student</label>
          <textarea id="fb-<?= (int) $row['id'] ?>" name="feedback" rows="3"><?= h($row['feedback'] ?? '') ?></textarea>
          <div class="admin-buttons">
            <button type="submit" name="status" value="approved" class="btn btn-primary btn-sm"><i class="bi bi-check-lg" aria-hidden="true"></i> Approve</button>
            <button type="submit" name="status" value="needs_work" class="btn btn-outline btn-sm">Needs work</button>
          </div>
        </form>
      </article>
    <?php endforeach; ?>

<?php elseif ($tab === 'requests'):
    $rows = $pdo->query(
        'SELECT r.*, u.name, u.email FROM academy_access_requests r JOIN academy_users u ON u.id = r.user_id
         ORDER BY r.handled ASC, r.created_at DESC LIMIT 100'
    )->fetchAll(); ?>
    <?php if (!$rows): ?><p class="muted">No unlock requests yet.</p><?php endif; ?>
    <?php foreach ($rows as $row): ?>
      <article class="admin-card<?= $row['handled'] ? '' : ' is-pending' ?>">
        <header>
          <strong><?= h($row['name']) ?></strong> <span class="muted"><?= h($row['email']) ?></span>
          <span class="quiz-badge<?= $row['handled'] ? '' : ' quiz-badge-todo' ?>"><?= $row['handled'] ? 'Handled' : 'New' ?></span>
        </header>
        <p class="muted"><?= h($catalog[$row['course']]['title'] ?? $row['course']) ?> &middot; <?= h(date('j M Y, H:i', strtotime($row['created_at']))) ?></p>
        <div class="admin-answer"><?= nl2br(h($row['note'])) ?></div>
        <?php if (!$row['handled']): ?>
          <div class="admin-buttons">
            <form method="post">
              <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
              <input type="hidden" name="action" value="grant">
              <input type="hidden" name="tab" value="requests">
              <input type="hidden" name="user_id" value="<?= (int) $row['user_id'] ?>">
              <input type="hidden" name="course" value="<?= h($row['course']) ?>">
              <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-unlock" aria-hidden="true"></i> Payment confirmed, unlock</button>
            </form>
            <form method="post">
              <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
              <input type="hidden" name="action" value="dismiss">
              <input type="hidden" name="tab" value="requests">
              <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
              <button type="submit" class="btn btn-outline btn-sm">Dismiss</button>
            </form>
          </div>
        <?php endif; ?>
      </article>
    <?php endforeach; ?>

<?php elseif ($tab === 'payments'):
    $rows = $pdo->query(
        'SELECT p.*, u.name, u.email FROM academy_payments p JOIN academy_users u ON u.id = p.user_id
         ORDER BY p.created_at DESC LIMIT 200'
    )->fetchAll();
    $received = (float) $pdo->query("SELECT COALESCE(SUM(amount), 0) FROM academy_payments WHERE status = 'completed'")->fetchColumn(); ?>
    <p><strong>Total received:</strong> UGX <?= number_format($received) ?> <span class="muted">(before Marz Pay fees)</span></p>
    <div class="table-wrap">
      <table class="admin-table">
        <thead><tr><th>Date</th><th>Student</th><th>Course</th><th>Amount</th><th>Phone</th><th>Status</th></tr></thead>
        <tbody>
        <?php if (!$rows): ?><tr><td colspan="6" class="muted">No payments yet.</td></tr><?php endif; ?>
        <?php foreach ($rows as $row): ?>
          <tr>
            <td><?= h(date('j M Y, H:i', strtotime($row['created_at']))) ?></td>
            <td><strong><?= h($row['name']) ?></strong><br><span class="muted"><?= h($row['email']) ?></span></td>
            <td><?= h($catalog[$row['course']]['title'] ?? $row['course']) ?></td>
            <td>UGX <?= number_format((float) $row['amount']) ?></td>
            <td><?= h($row['phone']) ?></td>
            <td>
              <span class="quiz-badge<?= $row['status'] === 'completed' ? '' : ' quiz-badge-todo' ?>"><?= h(ucfirst($row['status'])) ?></span>
              <?php if ($row['provider_txn']): ?><br><span class="muted">ID <?= h($row['provider_txn']) ?></span><?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

<?php else:
    $rows = $pdo->query(
        'SELECT u.*, (SELECT COUNT(*) FROM academy_progress p WHERE p.user_id = u.id AND p.completed_at IS NOT NULL) AS lessons,
                (SELECT GROUP_CONCAT(a.course) FROM academy_access a WHERE a.user_id = u.id) AS unlocked
         FROM academy_users u ORDER BY u.last_seen_at DESC LIMIT 500'
    )->fetchAll(); ?>
    <div class="table-wrap">
      <table class="admin-table">
        <thead><tr><th>Student</th><th>Joined</th><th>Last seen</th><th>Lessons</th><th>Full access</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $row):
            $unlocked = $row['unlocked'] ? explode(',', $row['unlocked']) : []; ?>
          <tr>
            <td><strong><?= h($row['name']) ?></strong><br><span class="muted"><?= h($row['email']) ?></span></td>
            <td><?= h(date('j M Y', strtotime($row['created_at']))) ?></td>
            <td><?= h(date('j M Y', strtotime($row['last_seen_at']))) ?></td>
            <td><?= (int) $row['lessons'] ?></td>
            <td>
              <?php foreach ($liveCourses as $slug => $c): ?>
                <form method="post" class="inline-form">
                  <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
                  <input type="hidden" name="tab" value="students">
                  <input type="hidden" name="user_id" value="<?= (int) $row['id'] ?>">
                  <input type="hidden" name="course" value="<?= h($slug) ?>">
                  <?php if (in_array($slug, $unlocked, true)): ?>
                    <span class="tag-free"><?= h($c['title']) ?></span>
                    <button type="submit" name="action" value="revoke" class="link-button">remove</button>
                  <?php else: ?>
                    <button type="submit" name="action" value="grant" class="link-button">Unlock <?= h($c['title']) ?></button>
                  <?php endif; ?>
                </form>
              <?php endforeach; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
<?php endif; ?>
  </div>
</section>
<?php
page_end();
