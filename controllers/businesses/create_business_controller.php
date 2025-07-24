<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $business_type = $_SESSION["business_type"] ?? throw new Exception("Not all the informations are registered in session1", 2);
        if ($business_type == "real_business") {
            $business_input = $_SESSION["business_input"] ?? throw new Exception("Not all the informations are registered in session2", 2);
        } elseif ($business_type == "new_business") {
            $business_input =  $_SESSION["business_input"] ?? throw new Exception("Not all the informations are registered in session3", 2);
        } else {
            header('Location:/error_404');
            exit;
        }

        !in_array($business_input, $_SESSION["businesses"]) ?: throw new Exception("Not all the informations are registered in session4", 2);
        

        $email = $_SESSION["businesses"][$business_input]["email"] ?? throw new Exception("Not all the informations are registered in session5", 2);

        $otp1 = isset($_POST['otp1']) ? (int)$_POST['otp1'] : '';
        $otp2 = isset($_POST['otp2']) ? (int)$_POST['otp2'] : '';
        $otp3 = isset($_POST['otp3']) ? (int)$_POST['otp3'] : '';
        $otp4 = isset($_POST['otp4']) ? (int)$_POST['otp4'] : '';
        $otp5 = isset($_POST['otp5']) ? (int)$_POST['otp5'] : '';
        $otp6 = isset($_POST['otp6']) ? (int)$_POST['otp6'] : '';
        $code_entre = $otp1 . $otp2 . $otp3 . $otp4 . $otp5 . $otp6;

        if ($code_entre === '') {
            throw new Exception('Veuillez entrer le code que vous avez reçu.', 1);
        } elseif ($code_entre != $_SESSION["secret_code"]) {
            throw new Exception('Le code est incorrect.', 1);
        }
        
        require_once "models/business_user_sql_requests.php";
        require_once "models/businesses_sql_requests.php";
        require_once "models/tickets_sql_requests.php";


        if ($business_input != "" && $email != "") {
            if ($business_type == "real_business") {
                $business_name = $_SESSION["businesses"][$business_input]["periodesUniteLegale"][0]["denominationUniteLegale"] ?? throw new Exception("Not all the informations are registered in session6", 2);
                if ($business_name == "") throw new Exception("Error during business creation", 23000);
                $business_id = create_real_business($business_name, $business_input, $email);
            } elseif ($business_type == "new_business") {
                $business_id = create_new_business($business_input, $email);
            }

            if ($business_id != "") {
                add_user_to_business($_SESSION["user_id"], $business_id, "manager");
                create_business_adding_ticket($_SESSION["user_id"], $business_id);
            } else {
                throw new Exception("Error during business creation", 23000);
            }
        }
        if ($business_type == "real_business") {
            display("create_business", "Création d'entreprise");
            exit;
        } elseif ($business_type == "new_business") {
            header("Location:/business_homepage");
            exit;
        }

    } catch (\Throwable $th) {
        switch ($th->getCode()) {
            case 1:
                $_SESSION['toast'] = [
                    'message' => $th->getMessage(),
                    'type' => 'error'
                ];
                display("check_business_infos", "Vérification de l'email");
                exit;

            case 23000:
                //dd($th);
                $_SESSION['toast'] = [
                    'message' => "Un problème a eu lieu lors de l'enregistrement. \n Merci de contacter notre service client.",
                    'type' => 'error'
                ];
                header("Location:/add_business");
                exit;
            default:
                //dd($th);
                break;
        }
    }
}
header("Location:/error_404");
exit;
