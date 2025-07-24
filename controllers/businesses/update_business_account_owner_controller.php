<?php

require_once "models/accounts_sql_requests.php";
require_once "models/owners_sql_requests.php";
require_once "models/businesses_sql_requests.php";
require_once "models/business_user_sql_requests.php";

try {
    $user_id = $_SESSION["user_id"] ?? throw new Exception("Entrée manquante importante", 2);
    $employees = $_SESSION["employees"] ?? throw new Exception("Entrée manquante importante", 2);

    $business_name = $_GET["index"] ?? throw new Exception("Entrée manquante", 2);
    $iban = $_GET["IBAN"] ?? throw new Exception("Entrée manquante", 2);
    $index = $_GET["employee"] ?? throw new Exception("Entrée manquante", 2);

    $business = read_business_informations_by_name($business_name);

    check_business_user($user_id, $business["id"]) ?: throw new Exception("Pas d'accès", 1);

    if ($business["is_verified"] == true) {
        check_business_user($employees[$index]["user_id"], $business["id"]) ?: throw new Exception("Le user n'est pas dans le business", 2);
        update_business_account_owner($business["id"], $iban, $employees[$index]["user_id"]);
        if ($_GET['iframe']) {
            header("Location:/business_accounts/".urlencode($business_name)."?iframe='true'");
        } else {
            header("Location:/business_accounts/".urlencode($business_name));
        }
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