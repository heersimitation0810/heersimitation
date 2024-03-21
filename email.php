<?php
require 'vendor/autoload.php';

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function smtp_mailer($to, $subject, $msg) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  // SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'heersimitation@gmail.com';  // SMTP username
        $mail->Password   = 'tdvbbekkvqebgolf';      // SMTP password
        $mail->SMTPSecure = 'tls';               // Enable TLS encryption
        $mail->Port       = 587;                 // TCP port to connect to

        $mail->SetFrom("heersimitation@gmail.com", "Heers Imitation Jewelery House");
        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body = $msg;
        $mail->AddAddress($to);
        $mail->AddEmbeddedImage('logo.png', 'logo');

        if($mail->send()) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>