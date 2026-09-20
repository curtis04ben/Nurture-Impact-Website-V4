<?php
require_once __DIR__ . '/includes/content.php';

$slug = isset($_GET['slug']) ? preg_replace('/[^a-z0-9\-]/', '', strtolower($_GET['slug'])) : '';
$project = $slug ? ni_get_project_by_slug($slug, true) : null;

$pageTitle = $project ? $project['title'] . ' | Nurture Impact' : 'Project Not Found | Nurture Impact';
$pageDescription = $project ? ($project['summary'] ?? '') : 'This project could not be found.';
$pageImage = $project['featured_image'] ?? 'assets/main_img.png';
$canonicalUrl = 'https://www.nurtureimpact.ie/project.php?slug=' . urlencode($slug);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700|Great+Vibes:400,100,300,500,700' rel='stylesheet' type='text/css'>
  <title><?php echo e($pageTitle); ?></title>
  <meta name="description" content="<?php echo e($pageDescription); ?>">
  <link rel="canonical" href="<?php echo e($canonicalUrl); ?>">
  <!-- Open Graph metadata: gives LinkedIn/other platforms a proper preview when this project is shared -->
  <meta property="og:title" content="<?php echo e($project['title'] ?? 'Project Not Found'); ?>">
  <meta property="og:description" content="<?php echo e($pageDescription); ?>">
  <meta property="og:image" content="https://www.nurtureimpact.ie/<?php echo e($pageImage); ?>">
  <meta property="og:type" content="article">
  <meta property="og:url" content="<?php echo e($canonicalUrl); ?>">
  <link rel="icon" type="image/png" href="assets/favicon-32.png" sizes="32x32">
  <link rel="apple-touch-icon" href="assets/favicon-180.png">
  <link rel="shortcut icon" href="favicon.ico">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php include __DIR__ . '/includes/partials/nav.php'; ?>

  <main id="main-content">
    <?php if (!$project): ?>
      <header class="page-intro">
        <h1>Project Not Found</h1>
        <p>This project may have been unpublished or the link may be incorrect.</p>
      </header>
      <p style="text-align:center;margin:40px 0;"><a href="projects.php">&larr; Back to all projects</a></p>
    <?php else: ?>
      <header class="article-header">
        <div class="article-header__meta">
          <?php echo e($project['project_type'] ?? 'Project'); ?> &bull; <?php echo e(ni_format_date($project['date'] ?? '')); ?>
          <?php if (!empty($project['organisation'])): ?> &bull; <?php echo e($project['organisation']); ?><?php endif; ?>
        </div>
        <h1><?php echo e($project['title']); ?></h1>
        <?php if (!empty($project['summary'])): ?>
          <p class="article-header__excerpt"><?php echo e($project['summary']); ?></p>
        <?php endif; ?>
      </header>

      <?php if (!empty($project['featured_image'])): ?>
        <div class="article-hero-image">
          <img src="<?php echo e($project['featured_image']); ?>" alt="<?php echo e($project['title']); ?>">
        </div>
      <?php endif; ?>

      <div class="article-body">
        <?php echo ni_render_body($project['body'] ?? ''); ?>

        <?php if (!empty($project['role'])): ?>
          <h2>Nurture Impact's Role</h2>
          <p><?php echo nl2br(e($project['role'])); ?></p>
        <?php endif; ?>

        <?php if (!empty($project['outcomes'])): ?>
          <h2>Outcomes</h2>
          <p><?php echo nl2br(e($project['outcomes'])); ?></p>
        <?php endif; ?>
      </div>

      <?php if (!empty($project['gallery']) && is_array($project['gallery'])): ?>
        <div class="article-gallery">
          <?php foreach ($project['gallery'] as $img): ?>
            <img src="<?php echo e($img); ?>" alt="<?php echo e($project['title']); ?> — gallery image">
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <div class="article-back-link">
        <a href="projects.php">&larr; Back to all projects</a>
      </div>
    <?php endif; ?>
  </main>

  <?php include __DIR__ . '/includes/partials/footer.php'; ?>
</body>
</html>
