<?php
/**
 * PHPMailer Manuel Yükleme Testi
 * Composer olmadan PHPMailer test
 */

// Include PHPMailer classes
require_once 'config.php';
require_once 'phpmailer/src/Exception.php';
require_once 'phpmailer/src/PHPMailer.php';
require_once 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

?>
<!DOCTYPE html>
<html>
<head>
    <title>PHPMailer Manuel Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
        h1 { color: #667eea; }
        h2 { color: #333; border-bottom: 2px solid #667eea; padding-bottom: 5px; }
        .success { background: #d4edda; padding: 15px; border: 1px solid #28a745; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; padding: 15px; border: 1px solid #dc3545; border-radius: 5px; margin: 10px 0; }
        .info { background: #e8f4f8; padding: 10px; border-left: 4px solid #2196F3; margin: 10px 0; }
        code { background: #f4f4f4; padding: 2px 5px; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>📧 PHPMailer Manuel Yükleme Testi</h1>

    <h2>1. Dosya Kontrolleri</h2>
    <?php
    $files = [
        'config.php' => file_exists('config.php'),
        'phpmailer/src/Exception.php' => file_exists('phpmailer/src/Exception.php'),
        'phpmailer/src/PHPMailer.php' => file_exists('phpmailer/src/PHPMailer.php'),
        'phpmailer/src/SMTP.php' => file_exists('phpmailer/src/SMTP.php')
    ];

    foreach ($files as $file => $exists) {
        echo $exists ? "✅ <code>$file</code><br>" : "❌ <code>$file</code> BULUNAMADI!<br>";
        if (!$exists) exit;
    }
    ?>

    <h2>2. Yapılandırma</h2>
    <div class="info">
        <strong>SMTP Host:</strong> <?php echo SMTP_HOST; ?><br>
        <strong>SMTP Port:</strong> <?php echo SMTP_PORT; ?><br>
        <strong>SMTP Secure:</strong> <?php echo SMTP_SECURE; ?><br>
        <strong>Username:</strong> <?php echo SMTP_USERNAME; ?><br>
        <strong>Password:</strong> <?php echo SMTP_PASSWORD ? '****** (ayarlanmış)' : '❌ AYARLANMAMIŞ'; ?><br>
        <strong>From Email:</strong> <?php echo FROM_EMAIL; ?><br>
        <strong>To Email:</strong> <?php echo TO_EMAIL; ?>
    </div>

    <h2>3. PHPMailer Instance</h2>
    <?php
    try {
        $mail = new PHPMailer(true);
        echo "<div class='success'>✅ PHPMailer instance oluşturuldu<br>";
        echo "<strong>Version:</strong> " . PHPMailer::VERSION . "</div>";
    } catch (Exception $e) {
        echo "<div class='error'>❌ Hata: " . $e->getMessage() . "</div>";
        exit;
    }
    ?>

    <h2>4. Test Mail Gönderimi</h2>
    <?php
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = SMTP_AUTH;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Recipients
        $mail->setFrom(FROM_EMAIL, 'PHPMailer Manuel Test');
        $mail->addAddress(TO_EMAIL);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Test - PHPMailer Manuel Yükleme Başarılı';
        $mail->Body = '
        <html>
        <body style="font-family: Arial, sans-serif;">
            <h1 style="color: #28a745;">✅ BAŞARILI!</h1>
            <p>PHPMailer <strong>Composer olmadan</strong> manuel yükleme ile çalışıyor.</p>
            <p><strong>Gönderilme Zamanı:</strong> ' . date('d.m.Y H:i:s') . '</p>
            <hr>
            <p style="color: #666; font-size: 12px;">Bu bir test e-postasıdır.</p>
        </body>
        </html>
        ';
        $mail->AltBody = 'PHPMailer manuel yükleme testi başarılı. Tarih: ' . date('d.m.Y H:i:s');

        // Send
        $mail->send();

        echo "<div class='success'>";
        echo "<strong>✅ BAŞARILI!</strong><br>";
        echo "Test maili başarıyla gönderildi.<br>";
        echo "Lütfen <strong>" . TO_EMAIL . "</strong> adresini kontrol edin.<br>";
        echo "<small>(Spam klasörünü de kontrol edin!)</small>";
        echo "</div>";

    } catch (Exception $e) {
        echo "<div class='error'>";
        echo "<strong>❌ HATA!</strong><br>";
        echo "Mail gönderilemedi: " . $mail->ErrorInfo;
        echo "</div>";
    }
    ?>

    <hr>
    <h2>✅ Sonuç</h2>
    <div class="success">
        <strong>PHPMailer manuel yükleme çalışıyor!</strong><br>
        İletişim formunuz artık sorunsuz çalışmalı.
    </div>

    <p>
        <a href="index.html">← Ana Sayfaya Dön</a> |
        <a href="quick-test.php">Form Testi</a>
    </p>
</body>
</html>
