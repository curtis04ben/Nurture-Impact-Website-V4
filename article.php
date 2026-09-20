<?php
require_once __DIR__ . '/includes/content.php';

$slug = isset($_GET['slug']) ? preg_replace('/[^a-z0-9\-]/', '', strtolower($_GET['slug'])) : '';
$article = $slug ? ni_get_article_by_slug($slug, true) : null;

$pageTitle = $article ? $article['title'] . ' | Nurture Impact' : 'Article Not Found | Nurture Impact';
$pageDescription = $article ? ($article['excerpt'] ?? '') : 'This article could not be found.';
$pageImage = $article['featured_image'] ?? 'assets/main_img.png';
$canonicalUrl = 'https://www.nurtureimpact.ie/insights/' . $slug;
$phpUrl = 'https://www.nurtureimpact.ie/article.php?slug=' . urlencode($slug);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700|Great+Vibes:400,100,300,500,700' rel='stylesheet' type='text/css'>
  <title><?php echo e($pageTitle); ?></title>
  <meta name="description" content="<?php echo e($pageDescription); ?>">
  <!-- Canonical points to the pretty /insights/slug URL (see .htaccess). Falls back to
       article.php?slug=... automatically if the rewrite rule isn't active on this host. -->
  <link rel="canonical" href="<?php echo e($canonicalUrl); ?>">
  <!-- Open Graph metadata: this is what LinkedIn reads to build the share preview -->
  <meta property="og:title" content="<?php echo e($article['title'] ?? 'Article Not Found'); ?>">
  <meta property="og:description" content="<?php echo e($pageDescription); ?>">
  <meta property="og:image" content="https://www.nurtureimpact.ie/<?php echo e($pageImage); ?>">
  <meta property="og:type" content="article">
  <meta property="og:url" content="<?php echo e($canonicalUrl); ?>">
  <meta property="article:published_time" content="<?php echo e($article['date'] ?? ''); ?>">
  <?php if (!empty($article['author'])): ?><meta property="article:author" content="<?php echo e($article['author']); ?>"><?php endif; ?>
  <meta name="twitter:card" content="summary_large_image">
  <link rel="icon" type="image/png" href="assets/favicon-32.png" sizes="32x32">
  <link rel="apple-touch-icon" href="assets/favicon-180.png">
  <link rel="shortcut icon" href="favicon.ico">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php include __DIR__ . '/includes/partials/nav.php'; ?>

  <main id="main-content">
    <?php if (!$article): ?>
      <header class="page-intro">
        <h1>Article Not Found</h1>
        <p>This article may have been unpublished or the link may be incorrect.</p>
      </header>
      <p style="text-align:center;margin:40px 0;"><a href="insights.php">&larr; Back to all insights</a></p>
    <?php else: ?>
      <header class="article-header">
        <div class="article-header__meta">
          <?php echo e($article['category'] ?? 'Insight'); ?> &bull; <?php echo e(ni_format_date($article['date'] ?? '')); ?>
          <?php if (!empty($article['author'])): ?> &bull; By <?php echo e($article['author']); ?><?php endif; ?>
        </div>
        <h1><?php echo e($article['title']); ?></h1>
        <?php if (!empty($article['excerpt'])): ?>
          <p class="article-header__excerpt"><?php echo e($article['excerpt']); ?></p>
        <?php endif; ?>
      </header>

      <?php if (!empty($article['featured_image'])): ?>
        <div class="article-hero-image">
          <img src="<?php echo e($article['featured_image']); ?>" alt="<?php echo e($article['title']); ?>">
        </div>
      <?php endif; ?>

      <div class="article-body">
        <?php echo ni_render_body($article['body'] ?? ''); ?>
      </div>

      <div class="article-share">
        <span>Share this article:</span>
        <a class="article-share__btn article-share__btn--linkedin"
           href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode($canonicalUrl); ?>"
           target="_blank" rel="noopener">Share on LinkedIn</a>
        <button type="button" class="article-share__btn" id="copy-link-btn"
                data-url="<?php echo e($canonicalUrl); ?>">Copy Link</button>
      </div>

      <?php if (!empty($article['related_service'])): ?>
        <div class="article-back-link">
          <a href="<?php echo e($article['related_service']); ?>">Related service &rarr;</a>
        </div>
      <?php endif; ?>

      <div class="article-back-link">
        <a href="insights.php">&larr; Back to all insights</a>
      </div>

      <script>
        (function () {
          var btn = document.getElementById('copy-link-btn');
          if (!btn) return;
          btn.addEventListener('click', function () {
            var url = btn.getAttribute('data-url');
            if (navigator.clipboard) {
              navigator.clipboard.writeText(url).then(function () {
                var original = btn.textContent;
                btn.textContent = 'Link Copied!';
                setTimeout(function () { btn.textContent = original; }, 2000);
              });
            }
          });
        })();
      </script>
    <?php endif; ?>
  </main>

  <?php include __DIR__ . '/includes/partials/footer.php'; ?>
</body>
</html>
