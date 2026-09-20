<?php
/**
 * Nurture Impact — backend configuration.
 *
 * This file holds site-wide constants only — no secrets. Values that ARE
 * secrets (SMTP credentials) are read from environment variables (see
 * README -> "Contact form / email configuration"), never hard-coded here.
 *
 * All persistent-data paths are defined in ONE place (this file) so the
 * same folders can be bind-mounted as Docker volumes without any other
 * code needing to know about it:
 *   - NI_DATA_DIR     (data/)           -> projects.json, articles.json, admin-credentials.json
 *   - NI_UPLOADS_DIR  (assets/uploads/) -> images uploaded via /admin
 * Everything else in the project is ordinary application code and can be
 * replaced/rebuilt at any time without losing content.
 */

// Change this if the site is deployed somewhere other than nurtureimpact.ie
define('NI_SITE_URL', 'https://www.nurtureimpact.ie');
define('NI_SITE_NAME', 'Nurture Impact');

// --- Persistent data locations -------------------------------------------
define('NI_DATA_DIR', __DIR__ . '/../data');
define('NI_PROJECTS_FILE', NI_DATA_DIR . '/projects.json');
define('NI_ARTICLES_FILE', NI_DATA_DIR . '/articles.json');
// Admin credentials live alongside the other content data (not under
// /backend) so a single "data" volume/bind-mount persists everything the
// admin area can create or change -- see README -> "Persistent storage".
define('NI_CREDENTIALS_FILE', NI_DATA_DIR . '/admin-credentials.json');
define('NI_UPLOADS_DIR', __DIR__ . '/../assets/uploads');

// Maximum uploaded image size (bytes) accepted by the admin area -- 5MB
define('NI_MAX_UPLOAD_BYTES', 5 * 1024 * 1024);

// --- Contact form / email -------------------------------------------------
// Where contact form submissions are sent.
define('NI_CONTACT_EMAIL', 'info@nurtureimpact.ie');

// Mail transport: 'mail' (PHP's built-in mail(), the default -- works out
// of the box on most Apache/PHP hosting incl. Blacknight) or 'smtp' (for
// more reliable delivery, e.g. via a transactional email provider).
//
// To switch to SMTP, set these as real environment variables on the
// server (or in a local, git-ignored .env file loaded by your hosting
// panel / Docker Compose) -- never edit them into this file:
//   NI_MAIL_TRANSPORT=smtp
//   NI_SMTP_HOST=smtp.example.com
//   NI_SMTP_PORT=587
//   NI_SMTP_ENCRYPTION=tls        (tls, ssl, or none)
//   NI_SMTP_USERNAME=...
//   NI_SMTP_PASSWORD=...
//   NI_MAIL_FROM_EMAIL=no-reply@nurtureimpact.ie
//   NI_MAIL_FROM_NAME=Nurture Impact Website
define('NI_MAIL_TRANSPORT', getenv('NI_MAIL_TRANSPORT') ?: 'mail');
