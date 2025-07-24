<?php

$email = $_SESSION['email'];
$secret_code = $_SESSION['secret_code'];
$information = ''; // Initialiser pour éviter une erreur plus tard

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp1 = isset($_POST['otp1']) ? (int)$_POST['otp1'] : '';
    $otp2 = isset($_POST['otp2']) ? (int)$_POST['otp2'] : '';
    $otp3 = isset($_POST['otp3']) ? (int)$_POST['otp3'] : '';
    $otp4 = isset($_POST['otp4']) ? (int)$_POST['otp4'] : '';
    $otp5 = isset($_POST['otp5']) ? (int)$_POST['otp5'] : '';
    $otp6 = isset($_POST['otp6']) ? (int)$_POST['otp6'] : '';
    $code_entre = $otp1 . $otp2 . $otp3 . $otp4 . $otp5 . $otp6;
    if ($code_entre === '') {
        $information = 'Veuillez entrer le mot de passe que vous avez reçu.';
    } elseif ($code_entre != $secret_code) {
        $information = 'Le mot de passe est incorrect.';
    } else {
        $information = 'Le mot de passe est correct !';
        $_SESSION["registered"] = false; //On passe la session en mode log in
        header("location:/creation_of_customer_file");
    }
}

display("verify_password","Inscription");
