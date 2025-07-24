<?php
// 
require_once "./models/api_sql_request.php";
header('Content-Type: application/json');


// Récupération du h1 envoyé par le business
$h1_recu = $_GET['h1'] ?? '';

if (!$h1_recu) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Paramètre h1 manquant"
    ]);
    exit;
}

try {
    // On cherche un compte dont le champ `h1` (en base) correspond à celui reçu
    $account = h1_check($h1_recu);

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
    $h3 = hash('sha256', $h1_recu . $k2);
    $business_iban = $account['IBAN'];  
    
    // Génération du token de session
    $token = bin2hex(random_bytes(16));
    $timestamp = time();

    // Enregistrement du token
    $tokensFile = 'controllers/api/bank_token.json';
    $tokens = file_exists($tokensFile) ? json_decode(file_get_contents($tokensFile), true) : [];

    $tokens[$token] = [
        'timestamp' => $timestamp,
    ];

    file_put_contents($tokensFile, json_encode($tokens, JSON_PRETTY_PRINT));

    // Réponse
    echo json_encode([
        "status" => "ok",
        "h3" => $h3,
        "IBAN" => $business_iban,
        "token" => $token,
        "timestamp" => $timestamp
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Erreur serveur : " . $e->getMessage()
    ]);
}
