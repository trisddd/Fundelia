<?php

require_once "models/accounts_sql_requests.php";
require_once "models/owners_sql_requests.php";
require_once "models/businesses_sql_requests.php";
require_once "models/business_user_sql_requests.php";

try {
    $user_id = $_SESSION["user_id"] ?? throw new Exception("Entrée manquante importante", 2);

    $business_name = $_GET["index"] ?? throw new Exception("Entrée manquante", 2);
    $iban = $_GET["IBAN"] ?? throw new Exception("Entrée manquante", 2); 

    $business = read_business_informations_by_name($business_name);

    check_business_user($user_id, $business["id"]) ?: throw new Exception("Pas d'accès", 1);

    if ($business["is_verified"] == true) {
        $account = get_account_by_business_id_and_iban($business["id"], $iban);
        $account ?? throw new Exception("Pas de compte à cet iban", 2);
        $owner = read_user_by_id($account["user_id"]);
        $owner ?? throw new Exception("Pas de bénéficiaire valide", 2);
        $employees = read_all_users_of_business($business["id"]);
        $_SESSION["employees"] = [...$employees["manager"],...$employees["employee"]];
        shuffle($_SESSION["employees"]);
        $_SESSION["current_business"] = $business_name;
        display("show_business_account_details",$account["account_name"]);
        exit;
    }
} catch (\Throwable $th) {
    switch ($th->getCode()) {
        case 1:
            //dd($th);
            header("Location:/error_403");
            exit;
        default:
            //dd($th);
            break;
    }
}
header("Location:/error_404");
exit;