<?php
require_once "models/businesses_sql_requests.php";
require_once "models/business_user_sql_requests.php";

try {
    $user_id = $_SESSION["user_id"] ?? throw new Exception("Entrée manquante importante", 2);

    $business_name = $_GET["index"] ?? throw new Exception("Entrée manquante", 1);

    $business = read_business_informations_by_name($business_name);

    if (check_business_user($user_id, $business["id"])) {
        if ($business["is_verified"] == true) {
            $_SESSION["current_business"] = $business_name;
            display("show_business",$business["name"]);
            exit;
        }
    }

} catch (\Throwable $th) {
    switch ($th->getCode()) {
        case 1:
            header("Location:/error_403");
            exit;
        default:
            //dd($th);
            break;
    }
}
header("Location:/error_404");
exit;