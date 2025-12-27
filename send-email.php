<?php

/**
 * Contact Form Handler
 * Selkify Landing Page - PHPMailer Integration
 */

// Start output buffering to prevent any whitespace before JSON response
ob_start();

// Set JSON response headers
header('Content-Type: application/json; charset=utf-8');

// Include configuration
require_once 'config.php';

// Include PHPMailer classes manually
require_once 'phpmailer/src/Exception.php';
require_once 'phpmailer/src/PHPMailer.php';
require_once 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send JSON response
function sendResponse($success, $message, $data = [])
{
    ob_end_clean(); // Clear any output buffer
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Log incoming request
error_log("===== New contact form submission =====");
error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
error_log("POST data: " . print_r($_POST, true));

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Geçersiz istek metodu.');
}

// CSRF and Origin Check
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

// Log origin for debugging
error_log("Origin: " . $origin);
error_log("Referer: " . $referer);

// Validate origin for AJAX requests
if (!empty($origin)) {
    $allowed = false;
    foreach (ALLOWED_ORIGINS as $allowedOrigin) {
        if (strpos($origin, $allowedOrigin) !== false || strpos($origin, 'localhost') !== false || strpos($origin, '127.0.0.1') !== false) {
            $allowed = true;
            header('Access-Control-Allow-Origin: ' . $origin);
            break;
        }
    }
    if (!$allowed) {
        error_log("Origin blocked: " . $origin);
        sendResponse(false, 'Geçersiz kaynak: ' . $origin);
    }
}

// Get and sanitize form data
$name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$phone = isset($_POST['phone']) ? trim(strip_tags($_POST['phone'])) : '';
$email = isset($_POST['email']) ? trim(strip_tags($_POST['email'])) : '';
$subject = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : '';
$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';

// Validation
$errors = [];

if (empty($name) || strlen($name) < 2) {
    $errors[] = 'Lütfen geçerli bir ad giriniz.';
}

if (empty($phone) || strlen($phone) < 10) {
    $errors[] = 'Lütfen geçerli bir telefon numarası giriniz.';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Lütfen geçerli bir e-posta adresi giriniz.';
}

if (empty($subject) || strlen($subject) < 3) {
    $errors[] = 'Lütfen bir konu giriniz.';
}

if (empty($message) || strlen($message) < 5) {
    $errors[] = 'Lütfen en az 10 karakter uzunluğunda bir mesaj giriniz.';
}

// Return errors if validation fails
if (!empty($errors)) {
    sendResponse(false, 'Lütfen formu eksiksiz doldurunuz.', ['errors' => $errors]);
}

// Check for spam (simple honeypot and rate limiting)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$lastSubmit = isset($_SESSION['last_submit']) ? $_SESSION['last_submit'] : 0;
$currentTime = time();

// Rate limiting: 1 submission per 60 seconds
// Skip rate limiting for first submission
if ($lastSubmit > 0 && ($currentTime - $lastSubmit < 60)) {
    sendResponse(false, 'Lütfen bir dakika bekleyip tekrar deneyiniz.');
}

// Create PHPMailer instance
$mail = new PHPMailer(true);

// Capture debug output
if (EMAIL_DEBUG > 0) {
    $mail->SMTPDebug = EMAIL_DEBUG;
    $mail->Debugoutput = function ($str, $level) {
        error_log("PHPMailer Debug [$level]: $str");
    };
}

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = SMTP_AUTH;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = SMTP_SECURE;
    $mail->Port = SMTP_PORT;
    $mail->CharSet = EMAIL_CHARSET;
    $mail->Encoding = 'base64';

    // Additional SMTP options for better compatibility
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    // Recipients
    $mail->setFrom(FROM_EMAIL, FROM_NAME);
    $mail->addAddress(TO_EMAIL);
    $mail->addReplyTo($email, $name);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Yeni İletişim Formu Mesajı: ' . $subject;

    // HTML Email body
    $htmlBody = '
    <!DOCTYPE html>
    <html lang="tr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px 8px 0 0; }
            .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
            .info-row { margin: 15px 0; padding: 10px; background: white; border-radius: 4px; }
            .label { font-weight: bold; color: #667eea; }
            .value { color: #333; margin-top: 5px; }
            .message-box { background: white; padding: 20px; margin-top: 20px; border-left: 4px solid #667eea; border-radius: 4px; }
            .footer { text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h2 style="margin: 0;">🔔 Yeni İletişim Formu Mesajı</h2>
            </div>
            <div class="content">
                <div class="info-row">
                    <div class="label">👤 İsim:</div>
                    <div class="value">' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</div>
                </div>
                <div class="info-row">
                    <div class="label">📧 E-posta:</div>
                    <div class="value"><a href="mailto:' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '</a></div>
                </div>
                <div class="info-row">
                    <div class="label">📱 Telefon:</div>
                    <div class="value"><a href="tel:' . htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') . '</a></div>
                </div>
                <div class="info-row">
                    <div class="label">📋 Konu:</div>
                    <div class="value">' . htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') . '</div>
                </div>
                <div class="message-box">
                    <div class="label">💬 Mesaj:</div>
                    <div class="value">' . nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) . '</div>
                </div>
                <div class="footer">
                    <p>Bu mesaj ' . date('d.m.Y H:i:s') . ' tarihinde selkify.com iletişim formundan gönderilmiştir.</p>
                </div>
            </div>
        </div>
    </body>
    </html>
    ';

    $mail->Body = $htmlBody;

    // Plain text version
    $mail->AltBody = "Yeni İletişim Formu Mesajı\n\n" .
        "İsim: $name\n" .
        "E-posta: $email\n" .
        "Telefon: $phone\n" .
        "Konu: $subject\n\n" .
        "Mesaj:\n$message\n\n" .
        "Gönderilme Tarihi: " . date('d.m.Y H:i:s');

    // Send email
    $mailSent = $mail->send();

    // Log successful send
    error_log("Contact form email sent successfully to: " . TO_EMAIL . " from: " . $email);

    // Update last submit time
    $_SESSION['last_submit'] = $currentTime;

    // Success response
    sendResponse(true, 'Mesajınız başarıyla gönderildi! En kısa sürede size dönüş yapacağız.');
} catch (Exception $e) {
    // Error response
    $errorMsg = 'Mesaj gönderilemedi. Lütfen daha sonra tekrar deneyiniz.';

    // Include detailed error in development mode
    if (EMAIL_DEBUG > 0) {
        $errorMsg .= ' Hata: ' . $mail->ErrorInfo;
    }

    // Log error (you can customize this to write to a file)
    error_log("Contact Form Error: " . $mail->ErrorInfo);

    sendResponse(false, $errorMsg);
}
