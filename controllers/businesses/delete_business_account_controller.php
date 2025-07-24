<?php

require_once "models/accounts_sql_requests.php";
require_once "models/owners_sql_requests.php";
require_once "models/businesses_sql_requests.php";
require_once "models/business_user_sql_requests.php";

try {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') throw new Exception("Pas en POST", 2);
    $user_id = $_SESSION["user_id"] ?? throw new Exception("Entrée manquante importante", 2);

    $business_name = $_GET["index"] ?? throw new Exception("Entrée manquante", 2);
    $iban = $_POST["iban"] ?? throw new Exception("Entrée manquante", 2); 

    $business = read_business_informations_by_name($business_name);

    check_business_user($user_id, $business["id"]) ?: throw new Exception("Pas d'accès", 1);

    if ($business["is_verified"] == true) {
        $account = get_account_by_business_id_and_iban($business["id"], $iban);
        $account ?? throw new Exception("Pas de compte à cet iban", 2);
        if ($account["balance"] != "0.00") throw new Exception("Ce compte n'est pas vide !", 3);
        delete_account($account["account_id"],$_SESSION['user_id']);
        $_SESSION["current_business"] = $business_name;
        header('Location: /business_accounts/'.urlencode($business_name)."?iframe=true");
        exit();
    }
} catch (\Throwable $th) {
    switch ($th->getCode()) {
        case 1:
            //dd($th);
            header("Location:/error_403");
            exit;
        case 3:
            // dd($th);
            $_SESSION['toast'] = [
                'message' => $th->getMessage(),
                'type' => 'error'
            ];
            header("Location:/business_accounts/".urlencode($business_name)."?iframe=true");
            exit;
        case 23000:
            //dd($th);
            exit;
        default:
            //dd($th);
            break;
    }
}
//dd("euh bah dakor");
header("Location:/error_404");
exit;