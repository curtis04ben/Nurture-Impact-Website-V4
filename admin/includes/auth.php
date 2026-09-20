<?php
require_once __DIR__ . '/../../backend/config.php';
require_once __DIR__ . '/../../includes/content.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function ni_admin_configured() {
    return file_exists(NI_CREDENTIALS_FILE);
}

function ni_admin_credentials() {
    if (!ni_admin_configured()) {
        return null;
    }
    $raw = file_get_contents(NI_CREDENTIALS_FILE);
    $data = json_decode($raw, true);
    if (!is_array($data) || empty($data['username']) || empty($data['password_hash'])) {
        return null;
    }
    return $data;
}

function ni_admin_save_credentials($username, $password) {
    $data = [
        'username' => $username,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
    ];
    return file_put_contents(NI_CREDENTIALS_FILE, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX) !== false;
}

function ni_admin_logged_in() {
    return !empty($_SESSION['ni_admin_logged_in']);
}

/**
 * Call at the top of every protected admin page.
 * Sends the visitor to setup (if no admin account exists yet) or to
 * login (if one exists but they haven't signed in), and stops execution.
 */
function ni_require_admin() {
    if (!ni_admin_configured()) {
        header('Location: setup.php');
        exit;
    }
    if (!ni_admin_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

/** Simple CSRF token helper for the admin forms. */
function ni_csrf_token() {
    if (empty($_SESSION['ni_csrf'])) {
        $_SESSION['ni_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['ni_csrf'];
}

function ni_csrf_check() {
    $token = $_POST['csrf_token'] ?? '';
    return !empty($token) && !empty($_SESSION['ni_csrf']) && hash_equals($_SESSION['ni_csrf'], $token);
}
