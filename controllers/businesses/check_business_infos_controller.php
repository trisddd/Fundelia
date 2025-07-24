<?php

try {
    $business_type = clean_string($_POST["business_type"] ?? throw new Exception("Entrée manquante", 1));
    $email = clean_string($_POST["email"] ?? throw new Exception("Entrée manquante", 1));
    
    if ($business_type == "real_business") {
        $siren = clean_string($_POST["SIREN"] ?? throw new Exception("Entrée manquante", 1));

        $cleaned_siren = str_replace(" ", "", $siren);

        if (!ctype_digit($cleaned_siren) && strlen($cleaned_siren) != 9) throw new Exception("Le SIREN n'est pas correct", 1);
        

        $options = [
            "http" => [
                "header" => "Accept: application/json\r\n" .
                            "X-INSEE-Api-Key-Integration: 6a981e71-9249-41f7-981e-719249b1f763",
                "method" => "GET"
            ]
        ];

        $context = stream_context_create($options);
        $sirene_response_json = @file_get_contents("https://api.insee.fr/api-sirene/3.11/siren/".$cleaned_siren, false, $context);
        if ($sirene_response_json == false) {
            throw new Exception("Le SIREN n'est pas enregistré", 1);
        }
        $siren_infos = json_decode($sirene_response_json, true);

        require_once "models/businesses_sql_requests.php";

        !read_business_informations_by_siren($cleaned_siren) ?: throw new Exception("Ce SIREN est déjà enregistré chez nous", 1);
        
        $_SESSION["business_input"] = $cleaned_siren;
        
        $_SESSION["businesses"][$cleaned_siren] = $siren_infos["uniteLegale"];
        $_SESSION["businesses"][$cleaned_siren]["email"] = $email;
    } elseif ($business_type == "new_business") {
        $name = clean_string($_POST["name"] ?? throw new Exception("Entrée manquante", 1));
        $_SESSION["businesses"][$name]["email"] = $email;
        $_SESSION["business_input"] = $name;
    } else {
        header('Location:/error_404');
        exit;
    }

    $_SESSION["business_type"] = $business_type;

    $secret_code = send_verification_mail($email);
    
    $_SESSION["secret_code"] = $secret_code;
    display("check_business_infos", "Vérification de l'email");

} catch (\Throwable $th) {
    $_SESSION['toast'] = [
        'message' => $th->getMessage(),
        'type' => 'error'
    ];
    header('Location:/add_business'); 
    exit;
}