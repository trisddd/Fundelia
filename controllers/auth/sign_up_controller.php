<?php
require_once 'models/users_sql_requests.php';
$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : false;
$is_already_set = email_exists($email);
if ($is_already_set != 0) {
    $_SESSION['toast'] = [
        'message' => "Cette adresse email est déjà utilisé.",
        'type' => 'error'
    ];
}

if ($email && $is_already_set == 0) {
    require "env.php";
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && $email != $bank_email) {
        session_regenerate_id(true);

        $secret_code = send_verification_mail($email);

        $_SESSION['email'] = $email;
        $_SESSION['secret_code'] = $secret_code;

        header("Location: /verify_password");
        exit();
    } else {
        $error_message = "L'adresse email fournie n'est pas valide.";
    }
}

display("sign_up", "Inscription");
