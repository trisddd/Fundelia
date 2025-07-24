<?php
require_once 'models/transactions_sql_requests.php';
require_once 'models/users_sql_requests.php';
require_once 'models/accounts_sql_requests.php';
require_once 'models/banks_sql_requests.php';
require_once "models/users_sql_requests.php";

$user_id = $_SESSION['user_id'];

// Récupération des données envoyées par le formulaire
$beneficiary_iban = trim($_POST["beneficiary_iban"] ?? "");
$beneficiary_first_name = trim($_POST["beneficiary_first_name"] ?? "");
$beneficiary_last_name = trim($_POST["beneficiary_last_name"] ?? "");

// Récupération des infos de la banque à partir du code banque de l'IBAN
$bank_code = substr($beneficiary_iban, 4, 5);
$bank_info = get_bank_by_bank_code($bank_code);

try {
    $verification = IBAN_verification($beneficiary_iban);
} catch (\Exception $th) {
    echo $th->getMessage();
}

if ($verification == !true) {
    $_SESSION['toast'] = [
        'message' => "Banque non prise en charge",
        'type' => 'error'
    ];
    header("Location: /show_beneficiaries");
    exit;
}

// Étape 1 : Handshake
$h1 = $bank_info["API_key"];
$k2 = $bank_info["private_key"];
$bank_url = $bank_info["contact_url"];
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

if ($response === false) {
    $_SESSION['toast'] = [
        'message' => "handshake_1",
        'type' => 'error'
    ];
    header("Location: /add_beneficiary");
    exit;
}

$data = json_decode($response, true);
if ($data === null) {
    $_SESSION['toast'] = [
        'message' => "handshake_2",
        'type' => 'error'
    ];
    header("Location: /add_beneficiary");
    exit;
}

$h3 = hash('sha256', $h1 . $k2);
if (!($data["status"] === "ok" && $data["h3"] === $h3)) {
    $_SESSION['toast'] = [
        'message' => "handshake_3",
        'type' => 'error'
    ];
    header("Location: /add_beneficiary");
    exit;
}

$token = $data["token"];

$transaction_data = [
    "timestamp" => $data["timestamp"],
    "transaction_type" => "verify_iban_existance",
    "request_iban" => $beneficiary_iban,
    "request_first_name" => $beneficiary_first_name,
    "request_last_name" => $beneficiary_last_name
];

$options = [
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
        "allow_self_signed" => true,
    ],
    "http" => [
        "header" => "Content-Type: application/json\r\nAuthorization: Bearer $token\r\n",
        "method" => "POST",
        "content" => json_encode($transaction_data),
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($bank_url, false, $context);

if ($response === false) {
    $_SESSION['toast'] = [
        'message' => "Erreur lors de la vérification",
        'type' => 'error'
    ];
    header("Location: /show_beneficiaries");
    exit;
}

$verify_result = json_decode($response, true);

// Étape 4 : Résultat de la vérification
if ($verify_result["status"] === "ok") {
    $adding_answer = add_beneficiary($user_id, $beneficiary_iban, $beneficiary_first_name, $beneficiary_last_name);

    if ($adding_answer === false) {
        $_SESSION['toast'] = [
            'message' => "Le bénéficiaire existe déjà",
            'type' => 'error'
        ];
        header("Location: /show_beneficiaries");
        exit;
    }
    $_SESSION['toast'] = [
        'message' => "Bénéficiaire ajouté avec succès",
        'type' => 'success'
    ];
    header("Location: /show_beneficiaries");
    exit;

} else {
    $_SESSION['toast'] = [
        'message' => "Erreur lors de l'ajout du bénéficiaire, veuillez contacter le service client",
        'type' => 'error'
    ];
    header("Location: /show_beneficiaries");
    exit;
}
