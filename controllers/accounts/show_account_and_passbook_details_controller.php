<?php

require_once "models/accounts_sql_requests.php";
require_once "models/users_sql_requests.php";

try {
    if (isset($_SESSION['user_id']) && isset($_GET['id']) ) {
        $user_id = $_SESSION['user_id'];
        $user_accounts = get_all_accounts_by_user_id($user_id) ?: throw new Exception("Don't have access");
        $user_accounts_ids = [];
        foreach ($user_accounts as $user_account) {
            $user_accounts_ids[] = $user_account['id'];
        }
        $account = read_account_by_id($_GET['id'])?: throw new Exception("Don't have access");
        in_array($account["id"],$user_accounts_ids) ?: throw new Exception("Don't have access");
        display("show_account_details","Informations du compte");
    } else {
        header("Location: /error_404");
        exit;
    }
} catch (\Exception $th) {
    header("Location: /error_403");
    exit;
}