<?php

require_once "models/accounts_sql_requests.php";
require_once "services/ibans_management.php";

$account_types = read_all_account_types();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['account_type']) && isset($_POST['account_name']) && isset( $_SESSION['user_id'])) {
    $account_type_id = intval($_POST['account_type']);
    $name = $_POST['account_name'];
    create_account($name,$account_type_id,$_SESSION['user_id']);
    notification_of_account_adding($_SESSION['user_id']);
    header("Location: /show_accounts_and_passbooks");
}

display("add_accounts", "Créer un compte");