<?php
require_once 'models/users_sql_requests.php';
require_once 'models/owners_sql_requests.php';
require_once 'services/cards_management.php';

$user_id = $_SESSION['user_id'];
// $card_holder= $_SESSION['user_nom'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['card_name']) && isset($_SESSION['user_id']) && isset($_POST['card_holder']) && isset($_POST['card_type']) && isset($_POST['account_id']) && isset($_POST['creation_reason'])) {
    $card_name = $_POST['card_name'];
    $card_holder = $_POST['card_holder'];
    $card_type = $_POST['card_type'];
    $account_id = $_POST['account_id'];
    $creation_motif = $_POST['creation_reason'];

    $nb_cartes = verify_user_number_of_cards($account_id);

    if ($nb_cartes >= 5) {
        $_SESSION['toast'] = [
            'message' => "Vous avez deja 5 cartes",
            'type' => 'error'
        ];
        header("Location: /show_cards_according_to_account");

        exit;
    }


    create_card_for_account($user_id, $account_id, $card_name, $card_type, $card_holder, $creation_motif);

    $_SESSION['toast'] = [
        'message' => "Carte créée avec succès !",
        'type' => 'success'
    ];
    header("Location: /show_cards_according_to_account");
    exit;
}
$accounts = get_accounts_by_user_id($user_id);

display('add_card_to_account', "Créer une carte");