<?php

if ($body["status"] == "verify") {
    require_once "models/accounts_sql_requests.php";
    require_once "models/transactions_sql_requests.php";
    require_once "models/users_sql_requests.php";
    $card_number = $body["card_number"];

    if (is_card_balance_sufficient($card_number, $amount) == true) {
        $email = get_mail_by_card_number($card_number);

        $secret_code = send_verification_mail($email);

        $new_token = bin2hex(random_bytes(16));
        $timestamp = time();

        $tokensFile = 'controllers/api/bank_tokens.json';
        // $tokens = file_exists($tokensFile) ? json_decode(file_get_contents($tokensFile), true) : [];

        $business_iban = $body["business_iban"];
        $business_name = $body["business_name"];
        $redirect_url_business_confirm = $body["redirect_url_business_confirm"];
        $card_exp = $body["card_exp"];
        $card_cvc = $body["card_cvc"];

        $holder_name = $body["holder_name"];
        $tokens[$new_token] = [
            'timestamp' => $timestamp,
            'code' => $secret_code,
            'card_number' => $card_number,
            'card_exp' => $card_exp,
            'card_cvc' => $card_cvc,
            'holder_name' => $holder_name,
            'business_iban' => $business_iban,
            'business_name' => $business_name,
            'order_amount' => $amount,
            'redirect_url_business_confirm' => $redirect_url_business_confirm
        ];

        file_put_contents($tokensFile, json_encode($tokens, JSON_PRETTY_PRINT));
        // Ici on est dans la banque B. Ce qu’on attend d’elle, c’est de nous rediriger vers le formulaire 
        // pour entrer le bon code dans sa banque bien sûr. Donc j’ai mis Fundelia mais chaque banque met son lien.
        $redirect_url = "http://localhost/fundelia/bank_mail_code_check_form";

        echo json_encode(["status" => "redirect", "url" => $redirect_url, 'token' => $new_token, 'timestamp' => $timestamp]);
        exit;
    }

    echo json_encode(["status" => "error", "message" => "Solde du compte insuffisant ou carte inconnue"]);
    exit;
}

if ($body["status"] == "confirm") {

    if ($body === null) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Corps de requête JSON invalide"]);
        exit;
    }

    $holder_name = $body["holder_name"];
    $beneficiary_iban = $body["business_iban"];
    $amount = $body["order_amount"] ?? "";
    // Récupérer le nom du bénéficiaire
    $beneficiary_name = get_account_name_by_iban($beneficiary_iban);

    create_transaction($emitter_iban, $beneficiary_iban, $wording, $amount, 'payment', $holder_name, $beneficiary_name) == true; // On crée une nouvelle transaction pour montrer que la transaction a bien été faite

    credit_account($beneficiary_iban, $amount);

    if ($amount >= 100) {
        $user_ib = get_user_id_by_iban($beneficiary_iban);
        if ($user_ib !== null) {
            notification_of_big_transfer_receive($user_ib, $amount);
        }
    }

    echo json_encode(["status" => "ok", "message" => "Transaction OK", "beneficiary_name" => $beneficiary_name]);
    exit;
}

echo json_encode(["status" => "error", "message" => "Statut inconnu"]);
exit;
