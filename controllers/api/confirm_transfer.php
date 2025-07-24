<?php

require_once "models/accounts_sql_requests.php";
require_once "models/transactions_sql_requests.php";

$beneficiary_iban = $body["beneficiary_iban"];
$status = $body["status"] ?? "";

// TODO : vérifier si emitter_iban + beneficiary_iban sont != ""

if ($status == 'ok') {

    $beneficiary_name = get_user_name_by_iban($beneficiary_iban);
    $beneficiary_name = $beneficiary_name['first_name'] . " " . $beneficiary_name['last_name'];

    $emitter_name = $body["emitter_name"] ?? "";
    //$emitter_name = $emitter_name['first_name'] . " " . $emitter_name['last_name'];

    create_transaction($emitter_iban, $beneficiary_iban, $wording, $amount, '', $emitter_name, $beneficiary_name); // On crée une nouvelle transaction pour montrer que la transaction a bien été faite
    credit_account($beneficiary_iban, $amount); // On crédite le compte bénéficiaire du virement

    if ($amount >= 500) {
        $user_ib = get_user_id_by_iban($beneficiary_iban);
        if ($user_ib !== null) {
            notification_of_big_transfer_receive($user_ib, $amount);
        }
    }

    echo json_encode([
        "status" => "ok",
    ]);
    exit;
}

$beneficiary_account = read_account_by_iban($beneficiary_iban);

if (get_user_name_by_iban($beneficiary_iban) !== null) {
    $beneficiary_name = get_user_name_by_iban($beneficiary_iban);
    $beneficiary_name = $beneficiary_name['first_name'] . " " . $beneficiary_name['last_name'];
}

if ($beneficiary_account == array()) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "L'IBAN bénéficiaire n'est pas enregistré dans la banque"]);
    exit;
} else {
    echo json_encode([
        "status" => "ok",
        "beneficiary_name" => $beneficiary_name,
    ]);
}
