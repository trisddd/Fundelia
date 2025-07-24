<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Vérifier que tous les champs attendus sont présents
    if (
        !isset($_POST["emitter_iban"]) ||
        !isset($_POST["beneficiary_iban"]) ||
        !isset($_POST["amount"]) ||
        !isset($_POST["wording"])
    ) {
        // Redirection avec message d'erreur
    $_SESSION['toast'] = [
        'message' => "missing_fields",
        'type' => 'error'
    ];
        header("Location: /show_transfer_page");
        exit;
    }

    // Nettoyage & validation
    $emitter_iban = trim($_POST["emitter_iban"]);
    $beneficiary_iban = trim($_POST["beneficiary_iban"]);
    $amount = floatval($_POST["amount"]);
    $wording = trim($_POST["wording"]);

    if ($emitter_iban === $beneficiary_iban) {

        header("Location: /show_transfer_page");
            $_SESSION['toast'] = [
        'message' => "choisissez 2 comptes differents",
        'type' => 'error'
    ];
        exit;
    }

    // Garde : IBAN non vide
    if (empty($emitter_iban) || empty($beneficiary_iban)) {
            $_SESSION['toast'] = [
        'message' => "error invalid iban",
        'type' => 'error'
    ];
        header("Location: /show_transfer_page");
        exit;
    }

    // Garde : montant > 0
    if ($amount <= 0) {
            $_SESSION['toast'] = [
        'message' => "error invalid_amount",
        'type' => 'error'
    ];
        header("Location: /show_transfer_page");
        exit;
    }


    require_once "models/transactions_sql_requests.php";
    require_once "controllers/transactions/make_transfer_logic.php";
$resultat = create_transfer($emitter_iban, $beneficiary_iban, $wording, $amount) ;

    if (
        //create_transfer($emitter_iban, $beneficiary_iban, $wording, $amount) == false
    $resultat !== true
        ) {
        unset($_SESSION['emitter_account_iban']);
            $_SESSION['toast'] = [
        'message' => $resultat['message'],
        'type' => 'error'
    ];
        // Redirection finale
        header("Location: /show_transfer_page");

        exit;
    }
    // Nettoyage session
    unset($_SESSION['emitter_account_iban']);

    // Redirection finale
        $_SESSION['toast'] = [
        'message' => "success",
        'type' => 'success'
    ];

    header("Location: /show_transfer_page");
    exit;
}
