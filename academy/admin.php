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
    } elseif ($action === 'price' && isset($catalog[$_POST['course'] ?? ''])) {
        $price = (int) preg_replace('/\D/', '', (string) ($_POST['price'] ?? ''));
        if ($price < 1000 || $price > 10000000) {
            flash('Please enter a price between UGX 1,000 and UGX 10,000,000.');
        } else {
            $pdo->prepare(
                'INSERT INTO academy_prices (course, price, updated_by, updated_at) VALUES (?, ?, ?, NOW())
                 ON DUPLICATE KEY UPDATE price = VALUES(price), updated_by = VALUES(updated_by), updated_at = NOW()'
            )->execute([$_POST['course'], $price, $user['email']]);
            flash('Price for "' . $catalog[$_POST['course']]['title'] . '" is now UGX ' . number_format($price) . '.');
        }
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
$onlineNow = (int) $pdo->query('SELECT COUNT(DISTINCT visitor) FROM academy_visits WHERE visited_at > NOW() - INTERVAL 5 MINUTE')->fetchColumn();

/** Friendly name for a visited page. */
function page_label(string $path): string
{
    $parts = parse_url($path);
    $file = $parts['path'] ?? '';
    parse_str($parts['query'] ?? '', $q);
    if (in_array($file, ['/', '/index.html'], true)) {
        return 'Home page';
    }
    if ($file === '/learn.html') {
        return 'Learn AI page';
    }
    if (in_array($file, ['/academy/', '/academy/index.php'], true)) {
        return 'Academy: sign in / My learning';
    }
    $course = isset($q['c']) ? load_course((string) $q['c']) : null;
    if ($course && $file === '/academy/course.php') {
        return 'Course: ' . $course['title'];
    }
    if ($course && $file === '/academy/lesson.php') {
        if (($q['l'] ?? '') === 'intro') {
            return 'Intro video: ' . $course['title'];
        }
        $i = find_lesson($course, (string) ($q['l'] ?? ''));
        return $i === null ? 'Lesson: ' . $course['title'] : 'Lesson ' . ($i + 1) . ': ' . $course['lessons'][$i]['title'];
    }
    return $path;
}

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
      <a href="?tab=prices" <?= $tab === 'prices' ? 'aria-current="page"' : '' ?>>Prices</a>
      <a href="?tab=visitors" <?= $tab === 'visitors' ? 'aria-current="page"' : '' ?>>Visitors <span><?= $onlineNow ?> online</span></a>
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

<?php elseif ($tab === 'visitors'):
    $count = fn (string $where) => (int) $pdo->query("SELECT COUNT(DISTINCT day, visitor) FROM academy_visits WHERE $where")->fetchColumn();
    $views = fn (string $where) => (int) $pdo->query("SELECT COUNT(*) FROM academy_visits WHERE $where")->fetchColumn();
    $tiles = [
        ['Online now', $onlineNow, 'last 5 minutes', null],
        ['Today', $count('day = CURDATE()'), 'visitors', $views('day = CURDATE()')],
        ['Last 7 days', $count('day > CURDATE() - INTERVAL 7 DAY'), 'visits', $views('day > CURDATE() - INTERVAL 7 DAY')],
        ['Last 30 days', $count('day > CURDATE() - INTERVAL 30 DAY'), 'visits', $views('day > CURDATE() - INTERVAL 30 DAY')],
    ];

    // Visitors per day for the last 30 days (days without visits show as 0)
    $perDay = [];
    foreach ($pdo->query('SELECT day, COUNT(DISTINCT visitor) AS n FROM academy_visits WHERE day > CURDATE() - INTERVAL 30 DAY GROUP BY day') as $r) {
        $perDay[$r['day']] = (int) $r['n'];
    }
    $days = [];
    for ($d = 29; $d >= 0; $d--) {
        $key = date('Y-m-d', strtotime("-$d day"));
        $days[$key] = $perDay[$key] ?? 0;
    }
    $max = max(1, max($days));
    // Whole-number scale with a whole-number midpoint
    $scaleTop = $max <= 4 ? 4 : ($max <= 10 ? 10 : (int) (ceil($max / 10) * 10));

    // Group by page name, so e.g. "/" and "/index.html" count as one Home page
    $topPages = [];
    $rows = $pdo->query(
        'SELECT path, day, visitor FROM academy_visits WHERE day > CURDATE() - INTERVAL 30 DAY'
    )->fetchAll();
    foreach ($rows as $r) {
        $label = page_label($r['path']);
        $topPages[$label]['views'] = ($topPages[$label]['views'] ?? 0) + 1;
        $topPages[$label]['people'][$r['day'] . $r['visitor']] = true;
    }
    uasort($topPages, fn ($a, $b) => $b['views'] <=> $a['views']);
    $topPages = array_slice($topPages, 0, 12, true);
    $sources = $pdo->query(
        "SELECT source, COUNT(DISTINCT day, visitor) AS people FROM academy_visits
         WHERE day > CURDATE() - INTERVAL 30 DAY AND source <> 'Inside the site' GROUP BY source ORDER BY people DESC LIMIT 10"
    )->fetchAll();
    $devices = $pdo->query(
        'SELECT device, COUNT(DISTINCT day, visitor) AS people FROM academy_visits
         WHERE day > CURDATE() - INTERVAL 30 DAY GROUP BY device ORDER BY people DESC'
    )->fetchAll();
    $recent = $pdo->query(
        'SELECT v.*, u.name FROM academy_visits v LEFT JOIN academy_users u ON u.id = v.user_id ORDER BY v.id DESC LIMIT 20'
    )->fetchAll();
    $sourceTotal = max(1, array_sum(array_column($sources, 'people')));
    $deviceTotal = max(1, array_sum(array_column($devices, 'people'))); ?>
    <p class="muted">Visits to the website and the academy. Anonymous: no IP addresses or cookies are stored, and your own visits as admin are not counted. A "visit" is one person on one day.</p>

    <div class="v-tiles">
      <?php foreach ($tiles as [$label, $value, $unit, $pageViews]): ?>
        <div class="v-tile">
          <span class="v-label"><?= h($label) ?></span>
          <strong><?= number_format($value) ?></strong>
          <span class="muted"><?= h($unit) ?><?= $pageViews !== null ? ' &middot; ' . number_format($pageViews) . ' page views' : '' ?></span>
        </div>
      <?php endforeach; ?>
    </div>

    <section class="admin-card v-chart-card">
      <h2 class="v-title">Visitors per day <span class="muted">last 30 days</span></h2>
      <div class="v-chart" role="img" aria-label="Bar chart of visitors per day for the last 30 days">
        <div class="v-grid" aria-hidden="true">
          <span data-v="<?= $scaleTop ?>"></span><span data-v="<?= $scaleTop / 2 ?>"></span><span data-v="0"></span>
        </div>
        <div class="v-bars">
          <?php $i = 0; foreach ($days as $day => $n): ?>
            <div class="v-col" tabindex="0" data-tip="<?= h(date('D j M', strtotime($day))) ?>: <?= $n ?> visitor<?= $n === 1 ? '' : 's' ?>">
              <span class="v-bar" style="height: <?= $n ? max(2, round($n / $scaleTop * 100, 1)) : 0 ?>%"></span>
              <span class="v-day"><?= ($i % 5 === 4 || $i === 29) ? h(date('j M', strtotime($day))) : '' ?></span>
            </div>
          <?php $i++; endforeach; ?>
        </div>
      </div>
      <details class="v-table">
        <summary>Show as a table</summary>
        <table class="admin-table"><thead><tr><th>Day</th><th>Visitors</th></tr></thead><tbody>
          <?php foreach (array_reverse($days, true) as $day => $n): ?><tr><td><?= h(date('D j M', strtotime($day))) ?></td><td><?= $n ?></td></tr><?php endforeach; ?>
        </tbody></table>
      </details>
    </section>

    <div class="v-grid-2">
      <section class="admin-card">
        <h2 class="v-title">Top pages <span class="muted">last 30 days</span></h2>
        <table class="admin-table v-compact">
          <thead><tr><th>Page</th><th>Views</th><th>Visitors</th></tr></thead>
          <tbody>
          <?php if (!$topPages): ?><tr><td colspan="3" class="muted">No visits yet.</td></tr><?php endif; ?>
          <?php foreach ($topPages as $label => $r): ?>
            <tr><td><?= h($label) ?></td><td><?= (int) $r['views'] ?></td><td><?= count($r['people']) ?></td></tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </section>

      <div>
        <section class="admin-card">
          <h2 class="v-title">Where visitors came from</h2>
          <?php if (!$sources): ?><p class="muted">No visits yet.</p><?php endif; ?>
          <ul class="v-list">
            <?php foreach ($sources as $r): $pct = round($r['people'] / $sourceTotal * 100); ?>
              <li><span><?= h($r['source']) ?></span><span class="v-meter"><span style="width: <?= $pct ?>%"></span></span><strong><?= (int) $r['people'] ?></strong></li>
            <?php endforeach; ?>
          </ul>
        </section>
        <section class="admin-card">
          <h2 class="v-title">Devices</h2>
          <ul class="v-list">
            <?php foreach ($devices as $r): $pct = round($r['people'] / $deviceTotal * 100); ?>
              <li><span><i class="bi <?= $r['device'] === 'Phone' ? 'bi-phone' : ($r['device'] === 'Tablet' ? 'bi-tablet' : 'bi-laptop') ?>" aria-hidden="true"></i> <?= h($r['device']) ?></span><span class="v-meter"><span style="width: <?= $pct ?>%"></span></span><strong><?= $pct ?>%</strong></li>
            <?php endforeach; ?>
          </ul>
        </section>
      </div>
    </div>

    <section class="admin-card">
      <h2 class="v-title">Latest visits</h2>
      <div class="table-wrap">
        <table class="admin-table v-compact">
          <thead><tr><th>When</th><th>Page</th><th>From</th><th>Device</th><th>Who</th></tr></thead>
          <tbody>
          <?php if (!$recent): ?><tr><td colspan="5" class="muted">No visits yet.</td></tr><?php endif; ?>
          <?php foreach ($recent as $r): ?>
            <tr>
              <td><?= h(date('j M, H:i', strtotime($r['visited_at']))) ?></td>
              <td><?= h(page_label($r['path'])) ?></td>
              <td><?= h($r['source']) ?></td>
              <td><?= h($r['device']) ?></td>
              <td><?= $r['name'] ? '<strong>' . h($r['name']) . '</strong>' : '<span class="muted">Visitor ' . h(strtoupper(substr($r['visitor'], 0, 4))) . '</span>' ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
    <p class="muted">Tip: when you share a link on WhatsApp or Facebook, add <code>?utm_source=whatsapp</code> (or facebook) to the end, so those visits show up here by name. Apps like WhatsApp often hide where a visit came from.</p>

<?php elseif ($tab === 'prices'):
    $prices = course_prices(); ?>
    <p class="muted">Full-course prices in UGX. The first lessons stay free. Changes apply straight away to new payments and to the Learn AI page.</p>
    <div class="price-admin">
      <?php foreach ($catalog as $slug => $c): ?>
        <form method="post" class="admin-card price-row">
          <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
          <input type="hidden" name="action" value="price">
          <input type="hidden" name="tab" value="prices">
          <input type="hidden" name="course" value="<?= h($slug) ?>">
          <div>
            <strong><?= h($c['title']) ?></strong>
            <span class="muted"><?= $c['status'] === 'live' ? 'Live' : 'Coming soon' ?></span>
          </div>
          <label for="price-<?= h($slug) ?>" class="visually-hidden">Price for <?= h($c['title']) ?></label>
          <span class="price-input"><span>UGX</span><input type="text" inputmode="numeric" id="price-<?= h($slug) ?>" name="price" value="<?= number_format($prices[$slug]) ?>" required></span>
          <button type="submit" class="btn btn-primary btn-sm">Save</button>
        </form>
      <?php endforeach; ?>
    </div>

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
