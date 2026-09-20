<?php
require_once __DIR__ . '/includes/content.php';
$projects = ni_get_projects(true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700|Great+Vibes:400,100,300,500,700' rel='stylesheet' type='text/css'>
  <title>Projects | Nurture Impact</title>
  <meta name="description" content="Recent projects and case studies from Nurture Impact's work across the Youth & Community Development sector.">
  <link rel="canonical" href="https://www.nurtureimpact.ie/projects.php">
  <meta property="og:title" content="Projects | Nurture Impact">
  <meta property="og:description" content="Recent projects and case studies from Nurture Impact.">
  <meta property="og:type" content="website">
  <link rel="icon" type="image/png" href="assets/favicon-32.png" sizes="32x32">
  <link rel="apple-touch-icon" href="assets/favicon-180.png">
  <link rel="shortcut icon" href="favicon.ico">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php include __DIR__ . '/includes/partials/nav.php'; ?>

  <header class="page-intro" id="main-content">
    <h1>Projects</h1>
    <p>A selection of the work Nurture Impact has supported across community groups, social enterprises and voluntary boards.</p>
  </header>

  <main>
    <section class="content-section content-section--light">
      <div class="content__wrapper">
        <?php if (empty($projects)): ?>
          <p class="content-placeholder-note" style="margin-top:0;">
            Project write-ups are being prepared for publication. In the meantime, see <a href="services.html">our services</a> or <a href="index.php#contact">get in touch</a> to discuss your organisation's needs.
          </p>
        <?php else: ?>
          <div class="card-grid">
            <?php foreach ($projects as $project): ?>
              <a href="project.php?slug=<?php echo urlencode($project['slug']); ?>" class="content-card">
                <img src="<?php echo e($project['featured_image'] ?? 'assets/main_img.png'); ?>" alt="<?php echo e($project['title']); ?>" class="content-card__image">
                <div class="content-card__body">
                  <div class="content-card__meta">
                    <span><?php echo e($project['project_type'] ?? 'Project'); ?></span>
                    <span><?php echo e(ni_format_date($project['date'] ?? '')); ?></span>
                  </div>
                  <h3><?php echo e($project['title']); ?></h3>
                  <p><?php echo e($project['summary'] ?? ''); ?></p>
                  <span class="content-card__cta">Read the full project &rarr;</span>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/includes/partials/footer.php'; ?>
</body>
</html>
