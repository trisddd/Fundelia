<?php

/**
 * Initiates a business transaction by communicating with a bank API to process a payment.
 *
 * @param array $request_data An associative array containing the transaction details, including:
 * - 'order_id' (string): The unique identifier of the order.
 * - 'card_number' (string): The customer's credit card number.
 * - 'card_exp' (string): The credit card expiration date (format: MM/YY or MMYY).
 * - 'card_cvc' (string): The credit card CVC/CVV code.
 * - 'holder_name' (string): The name of the cardholder.
 * - 'order_amount' (float|int|string): The total amount of the order.
 *
 * This function performs the following steps:
 * 1. Validates the provided credit card number.
 * 2. Establishes a handshake with the bank API to obtain a temporary token.
 * 3. Saves the obtained token locally, associating it with the order ID and a timestamp, and cleans up expired tokens.
 * 4. Sends the payment information and the token to the bank API to save the transaction details.
 * 5. Initiates the payment transaction with the bank API using the obtained token.
 * 6. Redirects the user to the URL provided by the bank for payment processing or to an error page if any step fails.
 *
 * @throws Exception If any communication with the bank API fails or returns an error.
 * @return void This function does not return a value; it redirects the user.
 */

function launch_business_transaction(array $request_data): void
{
    require_once "./keys.php";

    $order_id = $request_data['order_id'];
    $order_amount = $request_data['order_amount'];
    $bank_url = "http://localhost/fundelia/make_payment";

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

    if ($response === false) {
        header("Location: " . $error_url . "error=Erreur appel banque niveau 1");
        exit;
    }

    $data = json_decode($response, true);
    if ($data === null) {
        header("Location: " . $error_url . "error=Erreur appel banque niveau 2");
        exit;
    }

    $h3 = hash('sha256', $h1 . $k2);
    if (!($data['status'] === 'ok' && $data['h3'] == $h3)) {
        header("Location: " . $error_url . "error=Erreur appel banque niveau 3");
        exit;
    }

    // 2. Sauvegarde du token
    $file_path = './business_token.json';
    $tokens = file_exists($file_path) ? json_decode(file_get_contents($file_path), true) : [];
    // $tokens = is_array($tokens) ? $tokens : [];

    // $tokens[$data['token']] = [
    //     'timestamp' => $data['timestamp'],
    //     'order_id' => $order_id,
    // ];

    // // Nettoyage des anciens tokens
    // $valid_tokens = [];
    // $now = time();
    // foreach ($tokens as $tk => $info) {
    //     if (($now - $info['timestamp']) < 900) {
    //         $valid_tokens[$tk] = $info;
    //     }
    // }

    // file_put_contents($file_path, json_encode($valid_tokens, JSON_PRETTY_PRINT));
    // $token = $data['token']; 

    require_once 'redirectionv2.php';
}
