<?php

require_once "models/owners_sql_requests.php";
require_once "models/accounts_sql_requests.php";

$_SESSION['user_id'] ?? exit(header("Location: /error_403")); 
    
$user_id = $_SESSION['user_id'];
$accounts = get_accounts_by_user_id($user_id);
$total_amount = read_total_amount($user_id)["total_balance"];
$total_amount_pro = read_total_amount_pro($user_id)["total_balance"];

if ($total_amount==NULL) {
    $total_amount="0.00";
}
if ($total_amount_pro==NULL) {
    $total_amount_pro="0.00";
}

display("show_accounts","Comptes et Livrets");