<?php
/**
 * Nurture Impact — contact form email delivery.
 *
 * Two transports are supported, chosen by the NI_MAIL_TRANSPORT constant
 * (see backend/config.php, which reads it from an environment variable):
 *
 *   - 'mail' (default): PHP's built-in mail(), which hands off to the
 *     server's local MTA. This works out of the box on most conventional
 *     Apache/PHP hosting, including Blacknight shared hosting.
 *
 *   - 'smtp': a small built-in SMTP client (no external library) for
 *     sending via a real mail provider when local mail() delivery isn't
 *     reliable enough (common in some hosting/Docker setups). Configured
 *     entirely through environment variables — see backend/config.php
 *     for the full list. No credentials are ever stored in this file or
 *     committed to the project.
 *
 * Both transports send the same multipart/alternative message (a plain
 * text version plus an HTML table version) built by ni_build_contact_email().
 */

require_once __DIR__ . '/content.php';

/**
 * Build the email subject, plain-text body and HTML body for a contact
 * form submission. $fields is an associative array with keys: name,
 * email, organisation, message, sourcePage (all already trimmed strings;
 * HTML-escaping happens in here, not by the caller).
 */
function ni_build_contact_email($fields) {
    $name = $fields['name'] ?? '';
    $email = $fields['email'] ?? '';
    $organisation = $fields['organisation'] ?? '';
    $message = $fields['message'] ?? '';
    $sourcePage = $fields['sourcePage'] ?? '';
    $submittedAt = date('j F Y, H:i');

    $subject = 'Nurture Impact website enquiry from ' . $name;

    // Plain-text fallback
    $text = "New contact form submission from the Nurture Impact website\n\n";
    $text .= "Name:         {$name}\n";
    $text .= "Email:        {$email}\n";
    if ($organisation !== '') {
        $text .= "Organisation: {$organisation}\n";
    }
    if ($sourcePage !== '') {
        $text .= "Page:         {$sourcePage}\n";
    }
    $text .= "Submitted:    {$submittedAt}\n\n";
    $text .= "Message:\n{$message}\n";

    // HTML table version
    $rows = '';
    $rows .= '<tr><td style="padding:8px 12px;font-weight:bold;border:1px solid #e0e0e0;background:#f8faf9;">Name</td><td style="padding:8px 12px;border:1px solid #e0e0e0;">' . e($name) . '</td></tr>';
    $rows .= '<tr><td style="padding:8px 12px;font-weight:bold;border:1px solid #e0e0e0;background:#f8faf9;">Email</td><td style="padding:8px 12px;border:1px solid #e0e0e0;">' . e($email) . '</td></tr>';
    if ($organisation !== '') {
        $rows .= '<tr><td style="padding:8px 12px;font-weight:bold;border:1px solid #e0e0e0;background:#f8faf9;">Organisation</td><td style="padding:8px 12px;border:1px solid #e0e0e0;">' . e($organisation) . '</td></tr>';
    }
    if ($sourcePage !== '') {
        $rows .= '<tr><td style="padding:8px 12px;font-weight:bold;border:1px solid #e0e0e0;background:#f8faf9;">Page</td><td style="padding:8px 12px;border:1px solid #e0e0e0;">' . e($sourcePage) . '</td></tr>';
    }
    $rows .= '<tr><td style="padding:8px 12px;font-weight:bold;border:1px solid #e0e0e0;background:#f8faf9;">Submitted</td><td style="padding:8px 12px;border:1px solid #e0e0e0;">' . e($submittedAt) . '</td></tr>';
    $rows .= '<tr><td style="padding:8px 12px;font-weight:bold;border:1px solid #e0e0e0;background:#f8faf9;vertical-align:top;">Message</td><td style="padding:8px 12px;border:1px solid #e0e0e0;white-space:pre-wrap;">' . nl2br(e($message)) . '</td></tr>';

    $html = '<!DOCTYPE html><html><body style="font-family:Arial,Helvetica,sans-serif;color:#222;">';
    $html .= '<h2 style="color:#0f3325;">New Website Enquiry</h2>';
    $html .= '<table style="border-collapse:collapse;width:100%;max-width:600px;">' . $rows . '</table>';
    $html .= '</body></html>';

    return [$subject, $text, $html];
}

/** Strip characters that could be used for email header injection. */
function ni_mail_header_safe($value) {
    return trim(preg_replace('/[\r\n]+/', ' ', (string) $value));
}

/**
 * Send the contact form email using whichever transport is configured.
 * Returns true on (apparent) success, false on failure. Never throws —
 * callers should treat a false return as "could not send" and show the
 * visitor a generic message plus the direct contact email as a fallback.
 */
function ni_send_contact_email($fields) {
    [$subject, $text, $html] = ni_build_contact_email($fields);

    $toEmail = NI_CONTACT_EMAIL;
    $fromEmail = getenv('NI_MAIL_FROM_EMAIL') ?: ('no-reply@' . parse_url(NI_SITE_URL, PHP_URL_HOST));
    $fromName = getenv('NI_MAIL_FROM_NAME') ?: (NI_SITE_NAME . ' Website');
    $replyToEmail = $fields['email'] ?? '';
    $replyToName = $fields['name'] ?? '';

    if (NI_MAIL_TRANSPORT === 'smtp') {
        return ni_send_via_smtp($toEmail, $subject, $text, $html, $fromEmail, $fromName, $replyToEmail, $replyToName);
    }
    return ni_send_via_php_mail($toEmail, $subject, $text, $html, $fromEmail, $fromName, $replyToEmail, $replyToName);
}

/** Build the raw MIME multipart/alternative body + headers shared by both transports. */
function ni_build_mime_message($subject, $text, $html, $fromEmail, $fromName, $replyToEmail, $replyToName, $toEmail) {
    $boundary = 'ni-' . bin2hex(random_bytes(12));
    $fromName = ni_mail_header_safe($fromName);
    $replyToName = ni_mail_header_safe($replyToName);
    $subject = ni_mail_header_safe($subject);

    $headers = [];
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'From: ' . ($fromName !== '' ? "\"{$fromName}\" <{$fromEmail}>" : $fromEmail);
    if ($replyToEmail) {
        $headers[] = 'Reply-To: ' . ($replyToName !== '' ? "\"{$replyToName}\" <{$replyToEmail}>" : $replyToEmail);
    }
    $headers[] = 'Content-Type: multipart/alternative; boundary="' . $boundary . '"';

    $body = "--{$boundary}\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $body .= $text . "\r\n\r\n";
    $body .= "--{$boundary}\r\n";
    $body .= "Content-Type: text/html; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $body .= $html . "\r\n\r\n";
    $body .= "--{$boundary}--";

    return [$subject, $body, $headers];
}

/** Transport 1: PHP's built-in mail(). */
function ni_send_via_php_mail($toEmail, $subject, $text, $html, $fromEmail, $fromName, $replyToEmail, $replyToName) {
    [$subject, $body, $headers] = ni_build_mime_message($subject, $text, $html, $fromEmail, $fromName, $replyToEmail, $replyToName, $toEmail);
    $additionalParams = null;
    // Some hosts require a matching envelope sender for deliverability.
    if ($fromEmail) {
        $additionalParams = '-f' . $fromEmail;
    }
    return @mail($toEmail, $subject, $body, implode("\r\n", $headers), $additionalParams);
}

/**
 * Transport 2: minimal SMTP client (no external library).
 * Configured via environment variables only — see backend/config.php.
 * This has not been exercised against a live mail server in this
 * project's development environment; test it against your real SMTP
 * provider before relying on it in production (see README).
 */
function ni_send_via_smtp($toEmail, $subject, $text, $html, $fromEmail, $fromName, $replyToEmail, $replyToName) {
    $host = getenv('NI_SMTP_HOST');
    $port = (int) (getenv('NI_SMTP_PORT') ?: 587);
    $encryption = getenv('NI_SMTP_ENCRYPTION') ?: 'tls'; // tls, ssl, or none
    $username = getenv('NI_SMTP_USERNAME');
    $password = getenv('NI_SMTP_PASSWORD');

    if (!$host || !$username || !$password) {
        error_log('Nurture Impact: NI_MAIL_TRANSPORT=smtp but SMTP environment variables are not fully set.');
        return false;
    }

    [$subject, $body, $headers] = ni_build_mime_message($subject, $text, $html, $fromEmail, $fromName, $replyToEmail, $replyToName, $toEmail);
    $headers[] = 'To: ' . $toEmail;
    $headers[] = 'Subject: ' . $subject;
    $rawMessage = implode("\r\n", $headers) . "\r\n\r\n" . $body;

    $transportPrefix = ($encryption === 'ssl') ? 'ssl://' : '';
    $errno = 0; $errstr = '';
    $socket = @fsockopen($transportPrefix . $host, $port, $errno, $errstr, 15);
    if (!$socket) {
        error_log("Nurture Impact: SMTP connection failed — {$errstr} ({$errno})");
        return false;
    }
    stream_set_timeout($socket, 15);

    $expect = function ($socket, $codes) {
        $response = '';
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (isset($line[3]) && $line[3] === ' ') {
                break;
            }
        }
        $code = (int) substr($response, 0, 3);
        return in_array($code, (array) $codes, true) ? $response : false;
    };

    if (!$expect($socket, 220)) { fclose($socket); return false; }

    $localHost = parse_url(NI_SITE_URL, PHP_URL_HOST) ?: 'localhost';
    fwrite($socket, "EHLO {$localHost}\r\n");
    if (!$expect($socket, 250)) { fclose($socket); return false; }

    if ($encryption === 'tls') {
        fwrite($socket, "STARTTLS\r\n");
        if (!$expect($socket, 220)) { fclose($socket); return false; }
        if (!@stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
            fclose($socket);
            return false;
        }
        fwrite($socket, "EHLO {$localHost}\r\n");
        if (!$expect($socket, 250)) { fclose($socket); return false; }
    }

    fwrite($socket, "AUTH LOGIN\r\n");
    if (!$expect($socket, 334)) { fclose($socket); return false; }
    fwrite($socket, base64_encode($username) . "\r\n");
    if (!$expect($socket, 334)) { fclose($socket); return false; }
    fwrite($socket, base64_encode($password) . "\r\n");
    if (!$expect($socket, 235)) { fclose($socket); return false; }

    fwrite($socket, "MAIL FROM:<{$fromEmail}>\r\n");
    if (!$expect($socket, 250)) { fclose($socket); return false; }
    fwrite($socket, "RCPT TO:<{$toEmail}>\r\n");
    if (!$expect($socket, [250, 251])) { fclose($socket); return false; }

    fwrite($socket, "DATA\r\n");
    if (!$expect($socket, 354)) { fclose($socket); return false; }

    // Dot-stuff any line that starts with a lone "." per RFC 5321
    $escapedMessage = preg_replace('/^\./m', '..', $rawMessage);
    fwrite($socket, $escapedMessage . "\r\n.\r\n");
    if (!$expect($socket, 250)) { fclose($socket); return false; }

    fwrite($socket, "QUIT\r\n");
    fclose($socket);
    return true;
}
