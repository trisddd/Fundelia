<?php
 
require_once 'models/accounts_sql_requests.php';
require_once 'models/users_sql_requests.php';

// Garde : Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    session_destroy();
    header("Location: /");
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupération des comptes et bénéficiaires
$accounts = get_all_accounts_by_user_id($user_id);
$beneficiaries = get_all_beneficiaries_by_user_id($user_id);

// Vérifie que l’IBAN émetteur est fourni et appartient bien à l’utilisateur
$emitter_account_iban = $_GET['emitter_account_iban'] ?? null;

if ($emitter_account_iban !== null) {
    // Vérifie que cet IBAN est bien dans les comptes de l'utilisateur
    $user_ibans = array_column($accounts, 'IBAN');
    if (!in_array($emitter_account_iban, $user_ibans)) {
    $_SESSION['toast'] = [
        'message' => "invalid_iban",
        'type' => 'error'
    ];
        header("Location: /dashboard");
        exit;
    }

    // Stockage sécurisé en session
    $_SESSION['emitter_account_iban'] = $emitter_account_iban;
}

display("show_beneficiaries", "liste_des_benenficiare");
