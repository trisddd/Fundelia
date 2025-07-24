<?php

try {
    $user_id = $_SESSION["user_id"] ?? throw new Exception("Entrée manquante importante", 2);

    $iban = $_GET["index"] ?? throw new Exception("Entrée manquante", 2); 

    $business_name = $_SESSION["current_business"] ?? throw new Exception("Entrée manquante", 2); 

    require_once "models/accounts_sql_requests.php";
    require_once "services/ibans_management.php";

    if(IBAN_verification($iban)) {
        $account = read_account_by_iban($iban);
        if(empty($iban)) throw new Exception("L'IBAN n'est pas enregistré dans notre banque.", 2); 
        update_account_keys($iban);
    }
    $account = read_account_by_iban($iban);

    display("update_API_key","Informations du compte entreprise");
    exit;

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