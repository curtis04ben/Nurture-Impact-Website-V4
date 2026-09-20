<?php
require_once __DIR__ . '/includes/auth.php';
ni_require_admin();

$projects = ni_read_json(NI_PROJECTS_FILE);
$articles = ni_read_json(NI_ARTICLES_FILE);

$publishedProjects = count(array_filter($projects, fn($p) => !empty($p['published'])));
$publishedArticles = count(array_filter($articles, fn($a) => !empty($a['published'])));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard | Nurture Impact</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <div class="admin-bar">
    <div>
      <a href="index.php" class="admin-bar__brand">Nurture Impact Admin</a>
      <a href="articles.php">Articles</a>
      <a href="projects.php">Projects</a>
      <a href="../index.php" target="_blank">View Site</a>
    </div>
    <div>
      <span style="margin-right:16px;opacity:0.8;">Signed in as <?php echo e($_SESSION['ni_admin_username'] ?? ''); ?></span>
      <a href="logout.php">Log Out</a>
    </div>
  </div>

  <div class="admin-wrap">
    <h1 class="admin-title">Dashboard</h1>

    <div class="admin-card">
      <h2 class="admin-subtitle">Content Overview</h2>
      <p>Articles: <strong><?php echo count($articles); ?></strong> total, <strong><?php echo $publishedArticles; ?></strong> published.</p>
      <p>Projects: <strong><?php echo count($projects); ?></strong> total, <strong><?php echo $publishedProjects; ?></strong> published.</p>
      <div class="admin-actions" style="margin-top:20px;">
        <a href="article-edit.php" class="admin-btn">+ New Article</a>
        <a href="project-edit.php" class="admin-btn admin-btn--secondary">+ New Project</a>
      </div>
    </div>

    <div class="admin-card">
      <h2 class="admin-subtitle">How This Works</h2>
      <p style="color:#555;line-height:1.6;">
        Articles and Projects are stored as JSON files in <code>/data</code> — there's no database
        to manage. Anything marked <strong>Published</strong> appears immediately on the live
        Insights and Projects pages; anything left as a <strong>Draft</strong> stays hidden from
        visitors until you're ready.
      </p>
    </div>
  </div>
</body>
</html>
