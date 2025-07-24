<?php
require_once "models/accounts_sql_requests.php";

if (!isset($_SESSION['user_id'])) {
        $_SESSION['toast'] = [
        'message' => "Veuillez vous connecter pour accéder à cette page.",
        'type' => 'error'
    ];
    header("Location: /sign_in");
    exit;
}

if (empty($_SESSION['emitter_account_iban'])) {
        $_SESSION['toast'] = [
        'message' => "Veuillez choisir un compte de débit.",
        'type' => 'error'
    ];
    header("Location: /show_transfer_page");
    exit;
}

$accounts = get_all_accounts_by_user_id($_SESSION['user_id']);

$account_info = read_account_by_iban($_SESSION['emitter_account_iban']);

// Vérifie que le compte existe bien en base
if (!$account_info) {
        $_SESSION['toast'] = [
        'message' => "Le compte sélectionné n\'existe pas.",
        'type' => 'error'
    ];
    header("Location: /show_transfer_page");
    exit;
}

// Détermine le nom du bénéficiaire
if (!empty($_GET['name'])) {
    $beneficiary_name = htmlspecialchars($_GET['name']);
} elseif (!empty($_GET['first_name']) && !empty($_GET['last_name'])) {
    $beneficiary_name = htmlspecialchars($_GET['first_name']) . ' ' . htmlspecialchars($_GET['last_name']);
} else {
    $beneficiary_name = 'Non spécifié';
}

display("show_transfer_form", "Effectuer un virement");
