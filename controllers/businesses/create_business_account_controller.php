<?php

require_once "models/accounts_sql_requests.php";
require_once "models/businesses_sql_requests.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" 
    && isset($_POST["account_name"]) 
    && strlen($_POST["account_name"]) <= 255) {

        try {
            $user_id = $_SESSION["user_id"] ?? throw new Exception("Entrée manquante importante", 2);

            $business_name = $_SESSION["current_business"] ?? throw new Exception("Entrée manquante", 2); 
            
            $business = read_business_informations_by_name($business_name);
            $account = create_and_read_enterprise_account($_POST["account_name"],$business["id"], $user_id);
            $iban = $account['IBAN'];
            if ($_GET['iframe']) {
                header("Location:/update_API_key/$iban?iframe='true'");
            } else {
                header("Location:/update_API_key/$iban");
            }
            exit();

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
}


display("create_business_account", "Création d'un compte entreprise");