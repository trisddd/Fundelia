<?php
require_once 'models/users_sql_requests.php';
require_once "models/owners_sql_requests.php";


$user_id = $_SESSION['user_id'];
$account_id=get_accounts_by_user_id($user_id);
$account_id=$account_id[0]['account_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $account_id = isset($_POST['account_id']) ? $_POST['account_id'] : "";


    if ($account_id == '') {
        $account_id=get_accounts_by_user_id($user_id);
        $account_id=$account_id[0]['account_id'];
        
    }

    $accounts = get_accounts_by_user_id($user_id);

    $cartes = show_user_card_link_to_account($user_id, $account_id);

}
$accounts = get_accounts_by_user_id($user_id);
$cartes = show_user_card_link_to_account($user_id, $account_id);
$limit = 5;
$last_history = get_transaction_history_by_user_id($user_id, $limit);

display('show_cards','Mes Cartes');