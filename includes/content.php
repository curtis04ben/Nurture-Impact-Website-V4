<?php
/**
 * Nurture Impact — lightweight content store helpers.
 *
 * Content is stored as JSON files in /data. This keeps the public site
 * simple (no database required) while still letting the /admin area
 * add, edit and publish Projects and Articles without touching HTML.
 *
 * If the site later outgrows JSON files (very large volumes of content,
 * multiple simultaneous editors), these functions are the only place
 * that would need to change to swap in a MySQL-backed implementation —
 * everything else (public pages, admin forms) calls through here.
 */

require_once __DIR__ . '/../backend/config.php';
// NI_DATA_DIR, NI_PROJECTS_FILE, NI_ARTICLES_FILE, NI_UPLOADS_DIR and
// NI_CREDENTIALS_FILE are all defined centrally in backend/config.php.

/**
 * Read all records from a JSON content file.
 * Returns an array (empty array if the file is missing or invalid).
 */
function ni_read_json($path) {
    if (!file_exists($path)) {
        return [];
    }
    $raw = file_get_contents($path);
    if ($raw === false || trim($raw) === '') {
        return [];
    }
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        return [];
    }
    return $data;
}

/**
 * Write records back to a JSON content file (pretty-printed).
 * Uses a temp-file-then-rename to avoid corrupting the file if two
 * requests happen to write at the same moment.
 */
function ni_write_json($path, $data) {
    $json = json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $tmp = $path . '.tmp';
    if (file_put_contents($tmp, $json, LOCK_EX) === false) {
        return false;
    }
    return rename($tmp, $path);
}

function ni_get_projects($publishedOnly = true) {
    $items = ni_read_json(NI_PROJECTS_FILE);
    if ($publishedOnly) {
        $items = array_values(array_filter($items, function ($p) {
            return !empty($p['published']);
        }));
    }
    usort($items, function ($a, $b) {
        return strcmp($b['date'] ?? '', $a['date'] ?? '');
    });
    return $items;
}

function ni_get_articles($publishedOnly = true) {
    $items = ni_read_json(NI_ARTICLES_FILE);
    if ($publishedOnly) {
        $items = array_values(array_filter($items, function ($a) {
            return !empty($a['published']);
        }));
    }
    usort($items, function ($a, $b) {
        return strcmp($b['date'] ?? '', $a['date'] ?? '');
    });
    return $items;
}

function ni_find_by_slug($items, $slug) {
    foreach ($items as $item) {
        if (isset($item['slug']) && $item['slug'] === $slug) {
            return $item;
        }
    }
    return null;
}

function ni_get_project_by_slug($slug, $publishedOnly = true) {
    return ni_find_by_slug(ni_get_projects($publishedOnly), $slug);
}

function ni_get_article_by_slug($slug, $publishedOnly = true) {
    return ni_find_by_slug(ni_get_articles($publishedOnly), $slug);
}

/**
 * Turn a title into a URL-safe slug, e.g. "Community Workshop!" -> "community-workshop".
 */
function ni_slugify($text) {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    if ($text === '') {
        $text = 'item-' . time();
    }
    return $text;
}

/** Ensure a slug is unique within a set of items, appending -2, -3, etc. if needed. */
function ni_unique_slug($items, $slug, $ignoreSlug = null) {
    $base = $slug;
    $i = 2;
    while (true) {
        $clash = false;
        foreach ($items as $item) {
            if ($item['slug'] === $slug && $item['slug'] !== $ignoreSlug) {
                $clash = true;
                break;
            }
        }
        if (!$clash) {
            return $slug;
        }
        $slug = $base . '-' . $i;
        $i++;
    }
}

/** Basic HTML-escaping shortcut used throughout the templates. */
function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Render a plain-text body (as stored from a <textarea>) as paragraphs of safe HTML. */
function ni_render_body($text) {
    $paragraphs = preg_split('/\r\n\r\n|\n\n/', trim((string) $text));
    $html = '';
    foreach ($paragraphs as $p) {
        $p = trim($p);
        if ($p === '') {
            continue;
        }
        $html .= '<p>' . nl2br(e($p)) . "</p>\n";
    }
    return $html;
}

/**
 * Handle an optional image upload from an admin form.
 * Returns the relative path (e.g. "assets/uploads/xyz.jpg") to store on
 * the record, or null if no file was uploaded. Returns false on error
 * (caller should check for false vs null explicitly).
 */
function ni_handle_image_upload($fieldName) {
    if (empty($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $file = $_FILES[$fieldName];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    if ($file['size'] > NI_MAX_UPLOAD_BYTES) {
        return false;
    }
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    $mime = mime_content_type($file['tmp_name']);
    if (!isset($allowed[$mime])) {
        return false;
    }
    $ext = $allowed[$mime];
    $filename = 'upload-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
    $destDir = NI_UPLOADS_DIR;
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }
    $dest = $destDir . '/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return false;
    }
    return 'assets/uploads/' . $filename;
}

function ni_format_date($dateString) {
    $ts = strtotime((string) $dateString);
    if (!$ts) {
        return e($dateString);
    }
    return date('j F Y', $ts);
}
