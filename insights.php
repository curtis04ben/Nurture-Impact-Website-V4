<?php
require_once __DIR__ . '/includes/content.php';
$articles = ni_get_articles(true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700|Great+Vibes:400,100,300,500,700' rel='stylesheet' type='text/css'>
  <title>Insights | Nurture Impact</title>
  <meta name="description" content="Articles and updates from Nurture Impact on funding, governance, training and organisational development across the Youth & Community Development sector.">
  <link rel="canonical" href="https://www.nurtureimpact.ie/insights.php">
  <meta property="og:title" content="Insights | Nurture Impact">
  <meta property="og:description" content="Articles and updates from Nurture Impact across the Youth & Community Development sector.">
  <meta property="og:type" content="website">
  <link rel="icon" type="image/png" href="assets/favicon-32.png" sizes="32x32">
  <link rel="apple-touch-icon" href="assets/favicon-180.png">
  <link rel="shortcut icon" href="favicon.ico">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php include __DIR__ . '/includes/partials/nav.php'; ?>

  <header class="page-intro" id="main-content">
    <h1>Insights</h1>
    <p>Articles and updates from Nurture Impact, covering funding, governance, training and organisational development.</p>
  </header>

  <main>
    <section class="content-section content-section--light">
      <div class="content__wrapper">
        <?php if (empty($articles)): ?>
          <p class="content-placeholder-note" style="margin-top:0;">
            Articles are being prepared for publication. Follow Nurture Impact on <a href="https://www.linkedin.com/in/curtisalan?utm_source=share_via&amp;utm_content=profile&amp;utm_medium=member_android" target="_blank" rel="noopener">LinkedIn</a> for updates, or <a href="index.php#contact">get in touch</a> in the meantime.
          </p>
        <?php else: ?>
          <div class="card-grid">
            <?php foreach ($articles as $article): ?>
              <a href="article.php?slug=<?php echo urlencode($article['slug']); ?>" class="content-card">
                <img src="<?php echo e($article['featured_image'] ?? 'assets/main_img.png'); ?>" alt="<?php echo e($article['title']); ?>" class="content-card__image">
                <div class="content-card__body">
                  <div class="content-card__meta">
                    <span><?php echo e($article['category'] ?? 'Insight'); ?></span>
                    <span><?php echo e(ni_format_date($article['date'] ?? '')); ?></span>
                  </div>
                  <h3><?php echo e($article['title']); ?></h3>
                  <p><?php echo e($article['excerpt'] ?? ''); ?></p>
                  <span class="content-card__cta">Read the full article &rarr;</span>
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
