<?php
require_once 'services/cards_management.php';
require_once "models/accounts_sql_requests.php";
require_once "models/users_sql_requests.php";
require_once "models/banks_sql_requests.php";

// $_SESSION['token'] = $token;
// $_SESSION['order_amount'] = $order_amount;
// $_SESSION['order_id'] = $order_id;
// $_SESSION['redirect_url'] = $redirect_url;
// // $_SESSION['h1'] = $h1;
// $_SESSION['business_iban'] = $business_iban;
// $_SESSION['business_name'] = $business_name;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {

        // // Trouver le h1 de la banque qui va débiter grâce au numéro de carte

        // $emiter_card = $tokens[$received_token]['card_number'];
        $card_number = $_POST['card_number'];
        $card_exp = $_POST['card_exp'];
        $card_cvc = $_POST['card_cvc'];
        $holder_name = $_POST['holder_name'];
        // Vérification de la carte
        if (!card_verification($card_number)) {
            header('Location: /transaction_error?error="Le numéro de carte ne respecte pas le bon format"');
            exit;
        }
        $BIN = substr($card_number, 0, 6);
        $bank_info = get_bank_by_BIN($BIN);

        if (!$bank_info) {
            http_response_code(403);
            header('Location: /transaction_error?error="Les informations entrées sont incorrectes"');
            exit;
        }

        $h1 = (string) $bank_info['API_key'];
        $k2 = (string) $bank_info['private_key'];
        $bank_url = $bank_info['contact_url'];

        // // 1. Handshake
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
            header('Location: /transaction_error?error="Une erreur s\'est produite lors de la communication avec la banque"');
            exit;
        }

        $data = json_decode($response, true);
        if ($data === null) {
            header('Location: /transaction_error?error="Une erreur s\'est produite 01"');
            exit;
        }

        $h3 = hash('sha256', $h1 . $k2);

        if (!($data['status'] === 'ok' && $data['h3'] == $h3)) {
            header('Location: /transaction_error?error="Une erreur s\'est produite 02"');
            exit;
        }

        /////// Ensuite je rappelle pour demander si les infos existent, mais cette fois-ci on entre dans confirm_payment

        $url = $bank_url;
        $transaction_data = [
            'status' => "verify",
            'transaction_type' => "payment",
            'amount' => $_SESSION['order_amount'],
            'card_number' => $card_number,
            'card_exp' => $card_exp,
            'card_cvc' => $card_cvc,
            'holder_name' => $holder_name,
            'business_iban' => $_SESSION['business_iban'],
            'business_name' => $_SESSION['business_name'],
            'redirect_url_business_confirm' => $_SESSION['redirect_url']
        ];

        $tok_en = $data['token'];
        $options = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
                "allow_self_signed" => true,
            ],
            'http' => [
                'header' => "Content-Type: application/json\r\nAuthorization: Bearer $tok_en\r\n",
                'method' => 'POST',
                'content' => json_encode($transaction_data),
            ]
        ];

        $context = stream_context_create($options);

        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            header('Location: /transaction_error?error="Une erreur s\'est produite 03"');
            exit;
        }

        $data = json_decode($response, true);

        if ($data === null || $data['status'] === 'error') {
            header('Location: /transaction_error?error="Une erreur s\'est produite 04"');
            exit;
        }

        if ((int) $BIN !== 435912) {
            $tokensFile = 'controllers/api/bank_tokens.json';

            if (file_exists($tokensFile)) {
                $tokens = json_decode(file_get_contents($tokensFile), true);
                if (!is_array($tokens)) {
                    // JSON invalide ou vide → on force un tableau vide
                    $tokens = [];
                }
            } else {
                $tokens = [];
            }

            $tokens[$data['token']] = [
                'timestamp' => $data['timestamp'],
            ];

            file_put_contents($tokensFile, json_encode($tokens, JSON_PRETTY_PRINT));
        }

        // 4. Redirection
        if ($data['status'] === 'redirect') {
            require_once 'controllers/api/redirection.php';
        } else {
            header('Location: /transaction_error?error="Une erreur s\'est produite 05"');
        }

    } catch (Exception $e) {
        // Gestion centralisée des erreurs
        header('Location: /transaction_error?error="Une erreur s\'est produite 06"');
        exit;
    }
}

display("payement_form", "formulaire de paiement");
