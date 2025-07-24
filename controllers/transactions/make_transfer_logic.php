<?php


/**
 * Enable to make a transfer with any bank registered in the table `banks` by the beneficiary iban.
 * @param String $emitter_account The account of the user who wants to make a transfer (have to be in our bank)
 * @param String $beneficiary_iban The iban of the user that will receive the transfer
 * @param String $beneficiary_iban The iban of the user that will receive the transfer
 * @param Number $amount The transfer's amount
 */
function make_transfer($emitter_account, $beneficiary_iban, $wording, $amount): void
{
    $beneficiary_bank_code = substr($beneficiary_iban, 4, 5);
    $bank_informations = get_bank_by_bank_code($beneficiary_bank_code);

    if ($bank_informations === false) {
        throw new Exception("Banque non prise en charge");
    }

    // Clés (à stocker en dehors du code en production)
    $h1 = $bank_informations["API_key"];
    $k2 = $bank_informations["private_key"];
    $bank_url = $bank_informations["contact_url"];

    // 1. Handshake
            $options = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
                "allow_self_signed" => true,
            ],
            "http" => [
                "method" => "GET"
            ]
        ];

        $context = stream_context_create($options);

        $response = file_get_contents($bank_url . "?h1=" . $h1, false, $context);
    //$response = file_get_contents($bank_url . "?h1=" . $h1);
    if ($response === false) {
        throw new Exception("erreur handshake (niveau 1)");
    }

    $data = json_decode($response, true);
    if ($data === null) {
        throw new Exception("erreur handshake (niveau 2)");
    }

    $h3 = hash('sha256', $h1 . $k2);
    if (!($data['status'] === 'ok' && $data['h3'] == $h3)) {
        throw new Exception("erreur handshake (niveau 3)");
    }

    $token = $data['token'];
    $timestamp = $data['timestamp'];

    // 2. Lancement de la transaction pour verifier si le beneficiare existe bel et bien 
    $transaction_data = [
        'transaction_type' => 'transfer',
        'beneficiary_iban' => $beneficiary_iban,
    ];

    $options = [
        "ssl" => [
            "verify_peer" => false,
            "verify_peer_name" => false,
            "allow_self_signed" => true,
        ],
        'http' => [
            'header' => "Content-Type: application/json\r\nAuthorization: Bearer $token\r\n",
            'method' => 'POST',
            'content' => json_encode($transaction_data),
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($bank_url, false, $context);
    if ($response === false) {
        throw new Exception("erreur transaction (niveau 1)");
        exit;
    }

    $data = json_decode($response, true);
    if ($data === null || $data['status'] === 'error') {
        throw new Exception("erreur transaction (niveau 2)");
    }


    // 2.5 Maintenant qu'on sait que le compte existe, on peut initier la transaction

    // Check if emitter has enough money on his account
    if ($emitter_account["balance"] < $amount) {
        throw new Exception("Le compte émetteur n'a pas les fonds suffisants pour assurer ce virement.");
    }
    if (get_user_name_by_iban($emitter_account["IBAN"]) !== null) {
        $emitter_name = get_user_name_by_iban($emitter_account["IBAN"]);
        $emitter_name = $emitter_name['first_name'] . " " . $emitter_name['last_name'];
    }
    // Transaction
    // TODO a partir de la table des BENEFICIAIRE verifie si le beneficiaire existe et recupere son nom ajoute ensuite a create_transaction emitter_name et beneficiary_name
    if ($beneficiary_bank_code != "75076") { // Pour pas créer de doublons
        $emitter_name = $emitter_name['first_name'] . " " . $emitter_name['last_name'];
        create_transaction($emitter_account["IBAN"], $beneficiary_iban, $wording, $amount, '' ,$emitter_name, $data['beneficiary_name']); // On crée une nouvelle donnée transaction pour montrer que la transaction a bien été faite
    }


    debit_account($emitter_account["IBAN"], $amount); // On débite le compte émetteur du montant du virement
    if ($amount >= 500) {
        $user_ib = get_user_id_by_iban($emitter_account["IBAN"]);
        if ($user_ib !== null) {
            notification_of_big_transfer_debit($user_ib, $amount);
        }
    }
    // 3. On part dire que la transaction est passée
    $transaction_data = [
        'transaction_type' => 'transfer',
        'emitter_iban' => $emitter_account["IBAN"],
        'emitter_name' => $emitter_name,
        'beneficiary_iban' => $beneficiary_iban,
        'wording' => $wording,
        'amount' => $amount,
        'status' => 'ok'
    ];

    $options = [
        "ssl" => [
            "verify_peer" => false,
            "verify_peer_name" => false,
            "allow_self_signed" => true,
        ],
        'http' => [
            'header' => "Content-Type: application/json\r\nAuthorization: Bearer $token\r\n",
            'method' => 'POST',
            'content' => json_encode($transaction_data),
        ]
    ];

    $context = stream_context_create($options);

    $response = file_get_contents($bank_url, false, $context);
    $data = json_decode($response, true);

    // 5. Redirection
    if ($data['status'] != 'ok') {
        throw new Exception("erreur transaction (niveau final)");
    }
}
