<?php
require_once __DIR__ . '/includes/auth.php';
ni_require_admin();

$flash = '';
if (isset($_GET['saved'])) $flash = 'Article saved.';
if (isset($_GET['deleted'])) $flash = 'Article deleted.';

// Handle delete (POST, CSRF-protected)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_slug'])) {
    if (!ni_csrf_check()) {
        die('Invalid request. Please go back and try again.');
    }
    $items = ni_read_json(NI_ARTICLES_FILE);
    $items = array_values(array_filter($items, fn($a) => $a['slug'] !== $_POST['delete_slug']));
    ni_write_json(NI_ARTICLES_FILE, $items);
    header('Location: articles.php?deleted=1');
    exit;
}

$articles = ni_read_json(NI_ARTICLES_FILE);
usort($articles, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
$token = ni_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Articles | Admin | Nurture Impact</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <div class="admin-bar">
    <div>
      <a href="index.php" class="admin-bar__brand">Nurture Impact Admin</a>
      <a href="articles.php">Articles</a>
      <a href="projects.php">Projects</a>
      <a href="../insights.php" target="_blank">View Site</a>
    </div>
    <div><a href="logout.php">Log Out</a></div>
  </div>

  <div class="admin-wrap">
    <h1 class="admin-title">Articles</h1>

    <?php if ($flash): ?><div class="admin-flash admin-flash--success"><?php echo e($flash); ?></div><?php endif; ?>

    <div class="admin-card">
      <div class="admin-actions" style="margin-top:0;margin-bottom:20px;">
        <a href="article-edit.php" class="admin-btn">+ New Article</a>
      </div>

      <?php if (empty($articles)): ?>
        <p>No articles yet.</p>
      <?php else: ?>
        <table class="admin-table">
          <thead>
            <tr><th>Title</th><th>Date</th><th>Category</th><th>Status</th><th></th></tr>
          </thead>
          <tbody>
            <?php foreach ($articles as $a): ?>
              <tr>
                <td><?php echo e($a['title']); ?></td>
                <td><?php echo e($a['date'] ?? ''); ?></td>
                <td><?php echo e($a['category'] ?? ''); ?></td>
                <td>
                  <?php if (!empty($a['published'])): ?>
                    <span class="admin-badge admin-badge--published">Published</span>
                  <?php else: ?>
                    <span class="admin-badge admin-badge--draft">Draft</span>
                  <?php endif; ?>
                </td>
                <td>
                  <a href="article-edit.php?slug=<?php echo urlencode($a['slug']); ?>" class="admin-btn admin-btn--secondary">Edit</a>
                  <form method="post" style="display:inline;" onsubmit="return confirm('Delete this article? This cannot be undone.');">
                    <input type="hidden" name="csrf_token" value="<?php echo e($token); ?>">
                    <input type="hidden" name="delete_slug" value="<?php echo e($a['slug']); ?>">
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
