<?php
require_once 'models/transactions_sql_requests.php';
require_once 'models/users_sql_requests.php';
require_once 'models/accounts_sql_requests.php';
require_once 'models/banks_sql_requests.php';

if (!isset($_SESSION['tentatives'])) {
    $_SESSION['tentatives'] = 0;
}

$code_saisi = $_POST['code'] ?? '';

$token = $_SESSION['token'] ?? '';

$bank_tokens_json = file_get_contents('controllers/api/bank_tokens.json');
$bank_tokens = json_decode($bank_tokens_json, true);

if (isset($bank_tokens[$token]['code'])) {
    $Check_code = (string) $bank_tokens[$token]['code'];
} else {
    // Gérer le cas où le code n'existe pas
    $Check_code = null;
}

$card_number = $bank_tokens[$token]['card_number'] ?? '';
$card_exp = $bank_tokens[$token]['card_exp'] ?? '';
$card_cvc = $bank_tokens[$token]['card_cvc'] ?? '';
$holder_name = $bank_tokens[$token]['holder_name'] ?? '';
$business_iban = $bank_tokens[$token]['business_iban'] ?? '';
$business_name = $bank_tokens[$token]['business_name'] ?? '';

$order_amount = $bank_tokens[$token]['order_amount'] ?? '';

$redirect_url_business_confirm = $bank_tokens[$token]['redirect_url_business_confirm'] ?? '';

if (
    empty($token) ||
    empty($card_number) ||
    empty($business_iban) ||
    empty($business_name) ||
    empty($card_exp) ||
    empty($card_cvc) ||
    empty($holder_name) ||
    empty($order_amount) ||
    empty($Check_code) ||
    empty($redirect_url_business_confirm)
) {
    $redirect_url = "/transaction_error?error='Requête invalide : données manquantes'";
    header("Location: $redirect_url", true, 303);
    session_destroy();
    exit;
}

$tokens_path = 'controllers/api/bank_tokens.json';
$tokens = file_exists($tokens_path)
    ? json_decode(file_get_contents($tokens_path), true)
    : [];
if (!isset($tokens[$token])) {
    $redirect_url = "/transaction_error?error='Token invalide.'";
    header("Location: $redirect_url", true, 303);
    session_destroy();
    exit;
}
$transaction = $tokens[$token];

$token_timestamp = $transaction['timestamp'] ?? 0;

// Vérification expiration du token 
if (time() - $token_timestamp > 900) {
    unset($tokens[$token]);
    file_put_contents($tokens_path, json_encode($tokens, JSON_PRETTY_PRINT));
    $redirect_url = "/transaction_error?error='Token invalide.'";
    header("Location: $redirect_url", true, 303);
    session_destroy();
    exit;
}

// Trop de tentatives
if ($_SESSION['tentatives'] >= 3) {
    unset($tokens[$token]);
    file_put_contents($tokens_path, json_encode($tokens, JSON_PRETTY_PRINT));
    $redirect_url = "/transaction_error?error='Trop de tentatives.'";
    header("Location: $redirect_url", true, 303);
    session_destroy();
    exit;
}

// Code incorrect
if ((int) $code_saisi !== (int) $Check_code) {
    $_SESSION['tentatives']++;

    // Rediriger vers la page de saisie avec un message d'erreur
    $redirect_url = "/bank_mail_code_check_form?"
        . http_build_query([
            'error' => 'Code incorrect'
        ]);

    header("Location: $redirect_url", true, 303);
    exit;
}

// Code valide

$controll = verifiy_payment_info($card_number, $card_exp, $card_cvc, $holder_name);

if ($controll == false) {
    $redirect_url = "/transaction_error?error='Informations de transaction incorrectes'";
    header("Location: $redirect_url", true, 303);
    session_destroy();
    exit;
}

// Là on refait un handshake et ensuite avec le token qu’on reçoit on fait un confirm pour créditer le business IBAN

$amount = $order_amount;
$wording = 'Paiement de ' . $amount . ' effectué chez ' . $business_name;

/**
 * Confirms and processes a payment transaction with a beneficiary bank.
 *
 * @param string $card_number The number of the card used for payment.
 * @param string $business_iban The IBAN of the beneficiary business account.
 * @param float|int $order_amount The amount of the order to be paid.
 * @param string $wording The description or reason for the payment.
 * @param string $holder_name The name of the cardholder.
 * @param string $token The authentication token for communicating with the beneficiary bank.
 *
 * @return bool Returns `true` if the payment confirmation with the beneficiary bank and the local recording are successful, `false` otherwise.
 */

function payment($card_number, $business_iban, $order_amount, $wording, $holder_name, $token)
{
    $beneficiary_bank_code = substr($business_iban, 4, 5);
    $bank_informations = get_bank_by_bank_code($beneficiary_bank_code);

    // Clés (à stocker en dehors du code en production)

    $bank_url = $bank_informations["contact_url"];
    // Là tout est ok, du coup on demande à la banque de créditer
    $emitter_iban = get_iban_by_card_number($card_number);

    $transaction_data = [
        'status' => "confirm",
        'transaction_type' => "payment",
        'business_iban' => $business_iban,
        'emitter_iban' => $emitter_iban,
        'order_amount' => $order_amount,
        'holder_name' => $holder_name,
        'wording' => $wording
    ];

    $new_token = $token;
    $options = [
        "ssl" => [
            "verify_peer" => false,
            "verify_peer_name" => false,
            "allow_self_signed" => true,
        ],
        'http' => [
            'header' => "Content-Type: application/json\r\nAuthorization: Bearer $new_token\r\n",
            'method' => 'POST',
            'content' => json_encode($transaction_data),
        ]
    ];

    $context = stream_context_create($options);

    $response = file_get_contents($bank_url, false, $context);

    if ($response === false) {
        return false;
    }

    $data = json_decode($response, true);

    if ($data === null || $data['status'] === 'error') {
        return false;
    }
    $beneficiary_name = $data['beneficiary_name'];
    if (create_payment($card_number, $business_iban, $order_amount, $wording, $holder_name, $beneficiary_name) !== true) {
        return false;
    }

    return true;
}

$transaction = payment($card_number, $business_iban, $order_amount, $wording, $holder_name, $token);

if ($transaction === true) {
    unset($tokens[$token]);

    if (verify_card_status($card_number) == true) {
        $result = get_user_id_and_card_name_by_card_number($card_number);
        notification_of_short_lived_card_deleting($result['user_id'], $result['name']);
        delete_card($card_number);
    }
    $redirect_url = $redirect_url_business_confirm;
    header("Location: $redirect_url", true, 303);
    session_destroy();
    exit;

} elseif ($transaction === false) {
    $redirect_url = '/transaction_error?error="Vérifiez si vous avez les fonds nécessaires pour cet achat"';
    header("Location: $redirect_url", true, 303);
    session_destroy();
    exit;
} else {
    $redirect_url = '/transaction_error?error="Une erreur inattendue s\'est produite, veuillez contacter les développeurs pour une assistance"';
    header("Location: $redirect_url", true, 303);
    session_destroy();
    exit;
}
