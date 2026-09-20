<?php
require_once __DIR__ . '/includes/auth.php';
ni_require_admin();

$editingSlug = $_GET['slug'] ?? null;
$projects = ni_read_json(NI_PROJECTS_FILE);
$existing = $editingSlug ? ni_find_by_slug($projects, $editingSlug) : null;

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!ni_csrf_check()) {
        $error = 'Your session expired — please try saving again.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $date = trim($_POST['date'] ?? '');
        $organisation = trim($_POST['organisation'] ?? '');
        $projectType = trim($_POST['project_type'] ?? '');
        $summary = trim($_POST['summary'] ?? '');
        $body = trim($_POST['body'] ?? '');
        $role = trim($_POST['role'] ?? '');
        $outcomes = trim($_POST['outcomes'] ?? '');
        $published = isset($_POST['published']);
        $originalSlug = $_POST['original_slug'] ?? '';

        if ($title === '' || $date === '') {
            $error = 'Title and date are required.';
        } else {
            $featuredUpload = ni_handle_image_upload('featured_image_file');
            if ($featuredUpload === false) {
                $error = 'Featured image upload failed. Please use a JPG, PNG, WEBP or GIF under 5MB.';
            } else {
                $galleryPaths = [];
                if (!empty($_FILES['gallery_files']['name'][0])) {
                    $count = count($_FILES['gallery_files']['name']);
                    for ($i = 0; $i < $count; $i++) {
                        if ($_FILES['gallery_files']['error'][$i] === UPLOAD_ERR_NO_FILE) {
                            continue;
                        }
                        $single = [
                            'name' => $_FILES['gallery_files']['name'][$i],
                            'type' => $_FILES['gallery_files']['type'][$i],
                            'tmp_name' => $_FILES['gallery_files']['tmp_name'][$i],
                            'error' => $_FILES['gallery_files']['error'][$i],
                            'size' => $_FILES['gallery_files']['size'][$i],
                        ];
                        $_FILES['__gallery_single'] = $single;
                        $path = ni_handle_image_upload('__gallery_single');
                        if ($path) {
                            $galleryPaths[] = $path;
                        }
                    }
                }
                // Keep any previously-uploaded gallery images unless explicitly cleared
                $existingGallery = [];
                if (!empty($_POST['existing_gallery'])) {
                    $existingGallery = array_filter(explode(',', $_POST['existing_gallery']));
                }
                $gallery = array_merge($existingGallery, $galleryPaths);

                $slug = $originalSlug ?: ni_unique_slug($projects, ni_slugify($title));
                $featuredImage = $featuredUpload ?: ($_POST['existing_featured_image'] ?? 'assets/main_img.png');

                $record = [
                    'slug' => $slug,
                    'title' => $title,
                    'date' => $date,
                    'organisation' => $organisation,
                    'project_type' => $projectType,
                    'featured_image' => $featuredImage,
                    'gallery' => $gallery,
                    'summary' => $summary,
                    'body' => $body,
                    'role' => $role,
                    'outcomes' => $outcomes,
                    'published' => $published,
                ];

                if ($originalSlug) {
                    foreach ($projects as $i => $p) {
                        if ($p['slug'] === $originalSlug) {
                            $projects[$i] = $record;
                        }
                    }
                } else {
                    $projects[] = $record;
                }

                if (ni_write_json(NI_PROJECTS_FILE, $projects)) {
                    header('Location: projects.php?saved=1');
                    exit;
                }
                $error = 'Could not save — check that /data is writable by the web server.';
            }
        }
    }
}

$title = $existing['title'] ?? ($_POST['title'] ?? '');
$date = $existing['date'] ?? ($_POST['date'] ?? date('Y-m-d'));
$organisation = $existing['organisation'] ?? ($_POST['organisation'] ?? '');
$projectType = $existing['project_type'] ?? ($_POST['project_type'] ?? '');
$summary = $existing['summary'] ?? ($_POST['summary'] ?? '');
$body = $existing['body'] ?? ($_POST['body'] ?? '');
$role = $existing['role'] ?? ($_POST['role'] ?? '');
$outcomes = $existing['outcomes'] ?? ($_POST['outcomes'] ?? '');
$published = $existing['published'] ?? isset($_POST['published']);
$featuredImage = $existing['featured_image'] ?? '';
$gallery = $existing['gallery'] ?? [];
$originalSlug = $existing['slug'] ?? '';
$token = ni_csrf_token();
$pageHeading = $existing ? 'Edit Project' : 'New Project';
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
        <input type="hidden" name="existing_gallery" value="<?php echo e(implode(',', $gallery)); ?>">

        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?php echo e($title); ?>" required>

        <label for="date">Date</label>
        <input type="date" id="date" name="date" value="<?php echo e($date); ?>" required>

        <label for="organisation">Organisation / client (optional)</label>
        <input type="text" id="organisation" name="organisation" value="<?php echo e($organisation); ?>">

        <label for="project_type">Project type / category</label>
        <input type="text" id="project_type" name="project_type" value="<?php echo e($projectType); ?>" placeholder="e.g. Training, Governance, Funding">

        <label for="featured_image_file">Featured image</label>
        <?php if ($featuredImage): ?>
          <img src="../<?php echo e($featuredImage); ?>" class="admin-current-image" alt="Current featured image">
          <p class="admin-hint">Current image shown above. Upload a new file to replace it, or leave blank to keep it.</p>
        <?php endif; ?>
        <input type="file" id="featured_image_file" name="featured_image_file" accept="image/jpeg,image/png,image/webp,image/gif">

        <label for="gallery_files">Add gallery images (optional, can select multiple)</label>
        <?php if (!empty($gallery)): ?>
          <p class="admin-hint">Existing gallery images (<?php echo count($gallery); ?>) will be kept automatically.</p>
        <?php endif; ?>
        <input type="file" id="gallery_files" name="gallery_files[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple>

        <label for="summary">Short summary (shown on the Projects list)</label>
        <textarea id="summary" name="summary" rows="3"><?php echo e($summary); ?></textarea>

        <label for="body">Full project write-up</label>
        <textarea id="body" name="body" rows="12"><?php echo e($body); ?></textarea>
        <p class="admin-hint">Leave a blank line between paragraphs.</p>

        <label for="role">Nurture Impact's role (optional)</label>
        <textarea id="role" name="role" rows="3"><?php echo e($role); ?></textarea>

        <label for="outcomes">Outcomes / results (optional)</label>
        <textarea id="outcomes" name="outcomes" rows="3"><?php echo e($outcomes); ?></textarea>

        <label style="display:flex;align-items:center;gap:10px;margin-top:20px;">
          <input type="checkbox" name="published" style="width:auto;" <?php echo $published ? 'checked' : ''; ?>>
          Published (visible on the live site)
        </label>

        <div class="admin-actions">
          <button type="submit" class="admin-btn">Save Project</button>
          <a href="projects.php" class="admin-btn admin-btn--secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
