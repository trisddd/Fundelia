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
        $employee_first_name = $_POST["first_name"] ?? throw new Exception("Entrée manquante", 2);
        $employee_last_name = $_POST["last_name"] ?? throw new Exception("Entrée manquante", 2);
        $employee_email = $_POST["email"] ?? throw new Exception("Entrée manquante", 2);
        $role = $_POST["role"] ?? throw new Exception("Entrée manquante", 2);

        $employee_user_account = read_user_by_email_reinforced($employee_email, $employee_first_name, $employee_last_name);
        if (check_business_user($employee_user_account["id"], $business["id"])) throw new Exception("L'utilisateur existe déjà dans l'entreprise", 3);

        add_user_to_business($employee_user_account["id"],$business["id"], $role);

        $_SESSION["current_business"] = $business_name;
        $_SESSION['toast'] = [
            'message' => "Le membre a bien été ajouté.",
            'type' => 'success'
        ];
        header('Location: /employees/'.urlencode($business_name).'?iframe=true');
        exit;
    }

} catch (\Throwable $th) {
    switch ($th->getCode()) {
        case 1:
            //dd($th);
            header("Location:/error_403");
            exit;
        case 3:
            $_SESSION['toast'] = [
                'message' => $th->getMessage(),
                'type' => 'error'
            ];
            if($_GET["iframe"]){
                header('Location: /employees/'.urlencode($business_name).'?iframe="true');
            } else {
                header('Location: /employees/'.urlencode($business_name));
            }
            exit;
        default:
            //dd($th);
            break;
    }
}
header("Location:/error_404");
exit;