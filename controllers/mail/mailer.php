<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/vendor/autoload.php";

$mail = new PHPMailer(true);
//$mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output

$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';
$mail->Host = 'smtp.gmail.com';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
$mail->Username = "ndayisabagloire96@gmail.com"; // Your Gmail address
$mail->Password = "rqxz gfwq gkpn fnuy"; // Your Gmail password

$mail->isHtml(true);
// Temporary measure to disable certificate verification
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

return $mail;