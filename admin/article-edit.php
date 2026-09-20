<?php
require_once __DIR__ . '/includes/auth.php';
ni_require_admin();

$editingSlug = $_GET['slug'] ?? null;
$articles = ni_read_json(NI_ARTICLES_FILE);
$existing = $editingSlug ? ni_find_by_slug($articles, $editingSlug) : null;

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!ni_csrf_check()) {
        $error = 'Your session expired — please try saving again.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $date = trim($_POST['date'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $excerpt = trim($_POST['excerpt'] ?? '');
        $body = trim($_POST['body'] ?? '');
        $relatedService = trim($_POST['related_service'] ?? '');
        $published = isset($_POST['published']);
        $originalSlug = $_POST['original_slug'] ?? '';

        if ($title === '' || $date === '') {
            $error = 'Title and date are required.';
        } else {
            $upload = ni_handle_image_upload('featured_image_file');
            if ($upload === false) {
                $error = 'Image upload failed. Please use a JPG, PNG, WEBP or GIF under 5MB.';
            } else {
                $slug = $originalSlug ?: ni_unique_slug($articles, ni_slugify($title));

                $featuredImage = $upload ?: ($_POST['existing_featured_image'] ?? 'assets/main_img.png');

                $record = [
                    'slug' => $slug,
                    'title' => $title,
                    'date' => $date,
                    'author' => $author,
                    'category' => $category,
                    'featured_image' => $featuredImage,
                    'excerpt' => $excerpt,
                    'body' => $body,
                    'related_service' => $relatedService,
                    'published' => $published,
                ];

                if ($originalSlug) {
                    foreach ($articles as $i => $a) {
                        if ($a['slug'] === $originalSlug) {
                            $articles[$i] = $record;
                        }
                    }
                } else {
                    $articles[] = $record;
                }

                if (ni_write_json(NI_ARTICLES_FILE, $articles)) {
                    header('Location: articles.php?saved=1');
                    exit;
                }
                $error = 'Could not save — check that /data is writable by the web server.';
            }
        }
    }
}

$title = $existing['title'] ?? ($_POST['title'] ?? '');
$date = $existing['date'] ?? ($_POST['date'] ?? date('Y-m-d'));
$author = $existing['author'] ?? ($_POST['author'] ?? 'Alan Curtis');
$category = $existing['category'] ?? ($_POST['category'] ?? '');
$excerpt = $existing['excerpt'] ?? ($_POST['excerpt'] ?? '');
$body = $existing['body'] ?? ($_POST['body'] ?? '');
$relatedService = $existing['related_service'] ?? ($_POST['related_service'] ?? '');
$published = $existing['published'] ?? isset($_POST['published']);
$featuredImage = $existing['featured_image'] ?? '';
$originalSlug = $existing['slug'] ?? '';
$token = ni_csrf_token();
$pageHeading = $existing ? 'Edit Article' : 'New Article';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo e($pageHeading); ?> | Admin | Nurture Impact</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <div class="admin-bar">
    <div>
      <a href="index.php" class="admin-bar__brand">Nurture Impact Admin</a>
      <a href="articles.php">Articles</a>
      <a href="projects.php">Projects</a>
    </div>
    <div><a href="logout.php">Log Out</a></div>
  </div>

  <div class="admin-wrap">
    <h1 class="admin-title"><?php echo e($pageHeading); ?></h1>

    <?php if ($error): ?><div class="admin-flash admin-flash--error"><?php echo e($error); ?></div><?php endif; ?>

    <div class="admin-card">
      <form method="post" enctype="multipart/form-data" class="admin-form">
        <input type="hidden" name="csrf_token" value="<?php echo e($token); ?>">
        <input type="hidden" name="original_slug" value="<?php echo e($originalSlug); ?>">
        <input type="hidden" name="existing_featured_image" value="<?php echo e($featuredImage); ?>">

        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?php echo e($title); ?>" required>

        <label for="date">Date</label>
        <input type="date" id="date" name="date" value="<?php echo e($date); ?>" required>

        <label for="author">Author</label>
        <input type="text" id="author" name="author" value="<?php echo e($author); ?>">

        <label for="category">Category</label>
        <input type="text" id="category" name="category" value="<?php echo e($category); ?>" placeholder="e.g. Funding, Governance, Strategy">

        <label for="featured_image_file">Featured image</label>
        <?php if ($featuredImage): ?>
          <img src="../<?php echo e($featuredImage); ?>" class="admin-current-image" alt="Current featured image">
          <p class="admin-hint">Current image shown above. Upload a new file to replace it, or leave blank to keep it.</p>
        <?php endif; ?>
        <input type="file" id="featured_image_file" name="featured_image_file" accept="image/jpeg,image/png,image/webp,image/gif">

        <label for="excerpt">Excerpt (short summary shown on the Insights list)</label>
        <textarea id="excerpt" name="excerpt" rows="3"><?php echo e($excerpt); ?></textarea>

        <label for="body">Full article body</label>
        <textarea id="body" name="body" rows="14"><?php echo e($body); ?></textarea>
        <p class="admin-hint">Leave a blank line between paragraphs.</p>

        <label for="related_service">Related service link (optional)</label>
        <input type="text" id="related_service" name="related_service" value="<?php echo e($relatedService); ?>" placeholder="e.g. grant-applications.html">

        <label style="display:flex;align-items:center;gap:10px;margin-top:20px;">
          <input type="checkbox" name="published" style="width:auto;" <?php echo $published ? 'checked' : ''; ?>>
          Published (visible on the live site)
        </label>

        <div class="admin-actions">
          <button type="submit" class="admin-btn">Save Article</button>
          <a href="articles.php" class="admin-btn admin-btn--secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
