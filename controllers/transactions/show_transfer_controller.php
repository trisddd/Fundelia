<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: /error_403");
    exit;
}

require_once "models/users_sql_requests.php";
require_once "models/accounts_sql_requests.php";

$transfer_history = get_transfert_history_by_user_id($_SESSION['user_id'], 5);

if ($transfer_history === false) {
        $_SESSION['toast'] = [
        'message' => "Erreur lors du chargement de l'historique des virements.",
        'type' => 'error'
    ];
    $transfer_history = [];
}

$account = get_all_accounts_by_user_id($_SESSION['user_id']);

if (empty($account)) {
        $_SESSION['toast'] = [
        'message' => "Aucun compte trouvé pour cet utilisateur.",
        'type' => ''
    ];
   $account = [];
}

display("show_transfer_page", "Effectuer un virement");
