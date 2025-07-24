<?php

set_exception_handler(function ($e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Exception non attrapée : " . $e->getMessage()
    ]);
});

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Erreur système : $errstr dans $errfile à la ligne $errline"
    ]);
    return true; // empêche l'erreur par défaut de s'afficher
});

require_once "models/banks_sql_requests.php";
require_once "models/accounts_sql_requests.php";
require_once "models/transactions_sql_requests.php";
try {
    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
        // Récupération du h1 envoyé par le business
        $h1 = $_GET['h1'] ?? '';

        if (!$h1) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Paramètre h1 manquant"
            ]);
            exit;
        }

        // On cherche un compte dont le champ `h1` (en base) correspond à celui reçu
        $account = bank_h1_check($h1);

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
        $h3 = hash('sha256', $h1 . $k2);

        // Génération du token de session
        $token = bin2hex(random_bytes(16));
        $timestamp = time();

        // Enregistrement du token
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

        // Nettoyage
        foreach ($tokens as $key => $data) {
            if (time() - $data['timestamp'] > 900) {
                unset($tokens[$key]);
            }
        }

        $tokens[$token] = [
            'timestamp' => $timestamp,
        ];

        file_put_contents($tokensFile, json_encode($tokens, JSON_PRETTY_PRINT));

        // Réponse
        echo json_encode([
            "status" => "ok",
            "h3" => $h3,
            "token" => $token,
            "timestamp" => $timestamp
        ]);

    } else {
        // Vérification du token

        $auth_header = $headers['Authorization'];

        if (strpos($auth_header, 'Bearer ') !== 0) {
            http_response_code(403);
            echo json_encode(["status" => "error", "message" => "Authorization manquante"]);
            exit;
        }

        $received_token = substr($auth_header, 7);

        // Nettoyage des tokens expirés
        $tokensFile = 'controllers/api/bank_tokens.json';
        $tokens = file_exists($tokensFile)
            ? json_decode(file_get_contents($tokensFile), true)
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

        $transaction_type = $body["transaction_type"] ?? "";
        $amount = $body["amount"] ?? "";
        $emitter_iban = $body["emitter_iban"] ?? "";
        $wording = $body["wording"] ?? "";

        if ($transaction_type == "transfer") {
            $timestamp = $body["timestamp"] ?? "";
            require_once "controllers/api/confirm_transfer.php";
        } elseif ($transaction_type == "payment") {
            require_once "controllers/api/confirm_payment.php";
        } elseif ($transaction_type == "verify_iban_existance") {
            $request_iban = $body["request_iban"] ?? "";
            $request_first_name = $body["request_first_name"] ?? "";
            $request_last_name = $body["request_last_name"] ?? "";

            require_once "controllers/api/verify_iban.php";
        } else {
            echo json_encode(["status" => "error", "message" => "Type de transaction non défini"]);
            http_response_code(400);
            exit;
        }
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Erreur serveur : " . $e->getMessage()
    ]);
}
