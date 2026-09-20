<?php
/**
 * Nurture Impact — contact form handler.
 *
 * Receives POSTs from every contact form on the site (see assets/js/contact-form.js).
 * Responds with JSON {success, message} for the normal (JavaScript) path.
 * If a form is submitted without JavaScript, it falls back to a redirect
 * back to the originating page with ?contact_status=... so the same
 * banner can still be shown once the page (and its JS) has loaded.
 */

require_once __DIR__ . '/includes/mailer.php';

// Never leak PHP errors/warnings to the response — this endpoint always
// returns a controlled JSON or redirect response.
error_reporting(E_ALL);
ini_set('display_errors', '0');

$isAjax = (
    (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
);

function ni_contact_respond($success, $message) {
    global $isAjax;

    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => $success, 'message' => $message]);
        exit;
    }

    // No-JS fallback: redirect back to the page the form was on.
    $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
    $separator = (strpos($referer, '?') === false) ? '?' : '&';
    $redirectUrl = $referer . $separator . 'contact_status=' . ($success ? 'success' : 'error')
        . '&contact_message=' . urlencode($message);
    header('Location: ' . $redirectUrl);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    ni_contact_respond(false, 'Invalid request.');
}

// Honeypot: a genuine visitor never fills this hidden field in.
if (!empty($_POST['contact_number'])) {
    // Respond as if it succeeded so automated spam scripts get no signal
    // that they were filtered, without actually sending anything.
    ni_contact_respond(true, "Thanks — your message has been sent. We'll be in touch soon.");
}

$name = trim((string) ($_POST['from_name'] ?? ''));
$email = trim((string) ($_POST['reply_to'] ?? ''));
$organisation = trim((string) ($_POST['organisation'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));
$sourcePage = trim((string) ($_POST['source_page'] ?? ''));

// Defense against header injection: strip line breaks from any field
// that could end up in an email header (name, email, organisation).
$name = ni_mail_header_safe($name);
$email = ni_mail_header_safe($email);
$organisation = ni_mail_header_safe($organisation);
$sourcePage = ni_mail_header_safe($sourcePage);

if ($name === '' || $email === '' || $message === '') {
    ni_contact_respond(false, 'Please fill in your name, email and message.');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    ni_contact_respond(false, 'Please enter a valid email address.');
}
if (mb_strlen($name) > 200 || mb_strlen($organisation) > 200) {
    ni_contact_respond(false, 'Please shorten your name or organisation name.');
}
if (mb_strlen($message) > 8000) {
    ni_contact_respond(false, 'Your message is a little too long — please shorten it and try again.');
}

$fields = [
    'name' => $name,
    'email' => $email,
    'organisation' => $organisation,
    'message' => $message,
    'sourcePage' => $sourcePage,
];

$sent = false;
try {
    $sent = ni_send_contact_email($fields);
} catch (\Throwable $e) {
    error_log('Nurture Impact contact form error: ' . $e->getMessage());
    $sent = false;
}

if ($sent) {
    ni_contact_respond(true, "Thanks, {$name} — your message has been sent. We'll be in touch soon.");
}

ni_contact_respond(false, 'Sorry, something went wrong sending your message. Please email info@nurtureimpact.ie directly in the meantime.');
