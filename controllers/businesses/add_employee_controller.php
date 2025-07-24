<?php

require_once "models/accounts_sql_requests.php";
require_once "models/owners_sql_requests.php";
require_once "models/businesses_sql_requests.php";
require_once "models/business_user_sql_requests.php";

try {
    $user_id = $_SESSION["user_id"] ?? throw new Exception("Entrée manquante importante", 2);

    $business_name = $_GET["index"] ?? throw new Exception("Entrée manquante", 2);

    $business = read_business_informations_by_name($business_name);

    check_business_user($user_id, $business["id"]) ?: throw new Exception("Pas d'accès", 1);

    if ($business["is_verified"] == true) {
        display("add_employee", "Ajout d'un employé");
        exit;
    }

} catch (\Throwable $th) {
    switch ($th->getCode()) {
        case 1:
           // dd($th);
            header("Location:/error_403");
            exit;
        default:
            //dd($th);
            break;
    }
}
header("Location:/error_404");
exit;