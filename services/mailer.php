<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'phpmailer/src/PHPMailer.php';
require_once 'phpmailer/src/SMTP.php';
require_once 'phpmailer/src/Exception.php';

/**
 * Send a mail
 * @param String $message the message you want to send
 * @param String $email the recipient's email address
 */
function send_mail($message, $email){
    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    require "env.php";
    $mail->Username = $bank_email;
    $mail->Password = $mail_password;
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
 


    $mail->setFrom($bank_email, 'Fundelia');
    $mail->addAddress($email); 
    $mail->Subject = 'CODE D\'AUTHENTIFICATION';
    $mail->Body = $message ;
    $mail->send();
}

/**
 * Send a mail
 * @param String $email the recipient's email address
 * @return Number the secret_code generated
 */
function send_verification_mail($email){
    $secret_code = rand(100000, 999999); // send email mode
    // $secret_code = 111111; // email desactivated mode

    $message = "Voici votre code secret : " . $secret_code;

    send_mail($message,$email); // Toggle to activate

    return $secret_code;
}
