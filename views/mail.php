<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true); // Enable exception handling

try {
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = $config['SMTP_HOST'];
    $mail->Port = $config['SMTP_PORT'];
    $mail->SMTPSecure = $config['SMTP_ENCRYPTION'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['SMTP_USERNAME'];
    $mail->Password = $config['SMTP_PASSWORD'];

    // Sender and recipient
    $mail->setFrom('info@kautilyaeducation.com', 'Kautilya Education');
    $mail->addAddress('contact@imradhe.com', 'Radhe Shyam Salopanthula');

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body = 'This is a test email sent via SendinBlue SMTP with PHPMailer';

    // Send the email
    $mail->send();
    echo 'Email sent successfully.';
} catch (Exception $e) {
    echo 'Email could not be sent. Error: ', $mail->ErrorInfo;
}
?>