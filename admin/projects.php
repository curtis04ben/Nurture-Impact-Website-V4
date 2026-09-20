<?php
require_once __DIR__ . '/includes/auth.php';
ni_require_admin();

$flash = '';
if (isset($_GET['saved'])) $flash = 'Project saved.';
if (isset($_GET['deleted'])) $flash = 'Project deleted.';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_slug'])) {
    if (!ni_csrf_check()) {
        die('Invalid request. Please go back and try again.');
    }
    $items = ni_read_json(NI_PROJECTS_FILE);
    $items = array_values(array_filter($items, fn($p) => $p['slug'] !== $_POST['delete_slug']));
    ni_write_json(NI_PROJECTS_FILE, $items);
    header('Location: projects.php?deleted=1');
    exit;
}

$projects = ni_read_json(NI_PROJECTS_FILE);
usort($projects, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
$token = ni_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Projects | Admin | Nurture Impact</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <div class="admin-bar">
    <div>
      <a href="index.php" class="admin-bar__brand">Nurture Impact Admin</a>
      <a href="articles.php">Articles</a>
      <a href="projects.php">Projects</a>
      <a href="../projects.php" target="_blank">View Site</a>
    </div>
    <div><a href="logout.php">Log Out</a></div>
  </div>

  <div class="admin-wrap">
    <h1 class="admin-title">Projects</h1>

    <?php if ($flash): ?><div class="admin-flash admin-flash--success"><?php echo e($flash); ?></div><?php endif; ?>

    <div class="admin-card">
      <div class="admin-actions" style="margin-top:0;margin-bottom:20px;">
        <a href="project-edit.php" class="admin-btn">+ New Project</a>
      </div>

      <?php if (empty($projects)): ?>
        <p>No projects yet.</p>
      <?php else: ?>
        <table class="admin-table">
          <thead>
            <tr><th>Title</th><th>Date</th><th>Type</th><th>Status</th><th></th></tr>
          </thead>
          <tbody>
            <?php foreach ($projects as $p): ?>
              <tr>
                <td><?php echo e($p['title']); ?></td>
                <td><?php echo e($p['date'] ?? ''); ?></td>
                <td><?php echo e($p['project_type'] ?? ''); ?></td>
                <td>
                  <?php if (!empty($p['published'])): ?>
                    <span class="admin-badge admin-badge--published">Published</span>
                  <?php else: ?>
                    <span class="admin-badge admin-badge--draft">Draft</span>
                  <?php endif; ?>
                </td>
                <td>
                  <a href="project-edit.php?slug=<?php echo urlencode($p['slug']); ?>" class="admin-btn admin-btn--secondary">Edit</a>
                  <form method="post" style="display:inline;" onsubmit="return confirm('Delete this project? This cannot be undone.');">
                    <input type="hidden" name="csrf_token" value="<?php echo e($token); ?>">
                    <input type="hidden" name="delete_slug" value="<?php echo e($p['slug']); ?>">
                    <button type="submit" class="admin-btn admin-btn--danger">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
