<?php
require_once "models/accounts_sql_requests.php";
require_once "models/users_sql_requests.php";
require_once "models/banks_sql_requests.php";
header('Content-Type: application/json');
$headers = getallheaders();

if (!isset($headers['Authorization'])) {

    if (isset($_GET['h1'])) {
        // Récupération du h1 envoyé par le business
        $received_h1 = $_GET['h1'] ?? '';

        if (!$received_h1) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Paramètre h1 manquant"
            ]);
            exit;
        }

        try {
            // On cherche un compte dont le champ `h1` (en base) correspond à celui reçu
            $account = h1_check($received_h1);

            if (!$account) {
                http_response_code(403);
                echo json_encode([
                    "status" => "error",
                    "message" => "Clé h1 inconnue"
                ]);
                exit;
            }

            // On récupère k2 (clé privée de l'entreprise)
            $k2 = $account['private_key'];
            $h3 = hash('sha256', $received_h1 . $k2);

            $business_iban = $account['IBAN'];
            $business_name = $account['name'];

            // Génération du token de session
            $token = bin2hex(random_bytes(16));
            $timestamp = time();

            // Enregistrement du token
            $tokens_file = 'controllers/api/business_bank_tokens.json';
            $tokens = file_exists($tokens_file) ? json_decode(file_get_contents($tokens_file), true) : [];

            $tokens[$token] = [
                'timestamp' => $timestamp,
            ];

            file_put_contents($tokens_file, json_encode($tokens, JSON_PRETTY_PRINT));

            // Réponse
            echo json_encode([
                "status" => "ok",
                "h3" => $h3,
                "token" => $token,
                "timestamp" => $timestamp,
                'business_iban' => $business_iban,
                'business_name' => $business_name
            ]);
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => "Erreur serveur : " . $e->getMessage()
            ]);
        }

    } else {
        http_response_code(403);
        echo json_encode([
            "status" => "error",
            "message" => "Erreur : informations inconnues"
        ]);
        exit;
    }
} else {

    $auth_header = $headers['Authorization'] ?? '';

    if (strpos($auth_header, 'Bearer ') !== 0) {
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "Authorization manquante"]);
        exit;
    }

    $received_token = substr($auth_header, 7);

    // Nettoyage des tokens expirés
    $tokens_file = 'controllers/api/business_bank_tokens.json';
    $tokens = file_exists($tokens_file)
        ? json_decode(file_get_contents($tokens_file), true)
        : [];

    foreach ($tokens as $key => $data) {
        if (time() - $data['timestamp'] > 900) {
            unset($tokens[$key]);
        }
    }

    if (!isset($tokens[$received_token])) {
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "Token inconnu"]);
        exit;
    }

    // Lecture des données JSON envoyées par le business
    $body = json_decode(file_get_contents('php://input'), true);

    if ($body === null) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Corps de requête JSON invalide"]);
        exit;
    }

    // Pour l'étape 3, on sauvegarde toutes les infos que la banque nous a envoyées si le statut est bien
    // évidemment "save_transaction_info"

    if ($body['status'] == "save_transaction_info") {
        $card_number = $body['card_number'] ?? null;
        $card_exp = $body['card_exp'] ?? null;
        $card_cvc = $body['card_cvc'] ?? null;
        $holder_name = $body['holder_name'] ?? null;
        $order_amount = $body['order_amount'] ?? null;
        $business_iban = $body['business_iban'] ?? null;
        $business_name = $body['business_name'] ?? null;
        $order_id = $body['order_id'] ?? null;
        $redirect_url_business_confirm = $body['redirect_url'] ?? null;

        if (!$card_number || !$redirect_url_business_confirm || !$card_exp || !$card_cvc || !$holder_name || !$order_amount || !$order_id || !$business_iban || !$business_name) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Paramètres de transaction manquants"]);
            exit;
        }

        $tokens[$received_token] = [
            'card_number' => $card_number,
            'card_exp' => $card_exp,
            'card_cvc' => $card_cvc,
            'holder_name' => $holder_name,
            'order_amount' => $order_amount,
            'business_iban' => $business_iban,
            'business_name' => $business_name,
            'redirect_url_business_confirm' => $redirect_url_business_confirm,
            'order_id' => $order_id,
            'timestamp' => time(),
        ];

        file_put_contents($tokens_file, json_encode($tokens, JSON_PRETTY_PRINT));
        echo json_encode(["status" => "ok"]);
        exit;
    }

    ///////////// Initier une transaction 
    if ($body['status'] == "init_transaction") {

        // Trouver le h1 de la banque qui va débiter grâce au numéro de carte
        $emiter_card = $tokens[$received_token]['card_number'];
        $BIN = substr($emiter_card, 0, 6);
        $bank_info = get_bank_by_BIN($BIN);

        if (!$bank_info) {
            http_response_code(403);
            echo json_encode([
                "status" => "error",
                "message" => "Banque inconnue"
            ]);
            exit;
        }

        $h1 = (string) $bank_info['API_key'];
        $k2 = (string) $bank_info['private_key'];
        $bank_url = $bank_info['contact_url'];

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
            echo json_encode(["status" => "error", "message" => "Réponse vide"]);
            exit;
        }

        $data = json_decode($response, true);
        if ($data === null) {
            echo json_encode(["status" => "error", "message" => "Aucune donnée récupérée"]);
            exit;
        }

        $h3 = hash('sha256', $h1 . $k2);

        if (!($data['status'] === 'ok' && $data['h3'] == $h3)) {
            echo json_encode(["status" => "error", "message" => "Erreur de handshake"]);
            exit;
        }

        ////// Ensuite je rappelle pour demander si les infos existent, mais cette fois-ci on entre dans confirm_payment

        $url = $bank_url;
        $transaction_data = [
            'status' => "verify",
            'transaction_type' => "payment",
            'amount' => $tokens[$received_token]['order_amount'],
            'card_number' => $tokens[$received_token]['card_number'],
            'card_exp' => $tokens[$received_token]['card_exp'],
            'card_cvc' => $tokens[$received_token]['card_cvc'],
            'holder_name' => $tokens[$received_token]['holder_name'],
            'business_iban' => $tokens[$received_token]['business_iban'],
            'business_name' => $tokens[$received_token]['business_name'],
            'redirect_url_business_confirm' => $tokens[$received_token]['redirect_url_business_confirm']
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
            echo json_encode(["status" => "error"]);
            exit;
        }

        $data = json_decode($response, true);

        if ($data === null || $data['status'] === 'error') {
            echo json_encode(["status" => "error"]);
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
            echo json_encode(["status" => "redirect", "url" => $data['url'], 'token' => $data['token']]);
            exit;
        }
    }
}
