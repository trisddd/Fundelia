<?php

require_once "models/accounts_sql_requests.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['account_id']) && isset( $_SESSION['user_id'])) {
    check_accounts_is_empty($_POST["account_id"]) ?
        delete_account($_POST['account_id'],$_SESSION['user_id'])
        : $_SESSION["toast"] = [
            "message" => "Votre compte n'est pas vide.",
            "type"=>"error"];
    
    header('Location: /show_accounts_and_passbooks');
    exit();
}