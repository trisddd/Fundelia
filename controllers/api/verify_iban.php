<?php
// Récupération sécurisée et nettoyage des entrées
$request_iban = trim($body["request_iban"] ?? "");
$request_first_name = trim($body["request_first_name"] ?? "");
$request_last_name = trim($body["request_last_name"] ?? "");

// Vérification des champs requis
if (empty($request_iban) || empty($request_first_name) || empty($request_last_name)) {
    echo json_encode([
        "status" => "error",
        "message" => "Champs requis manquants"
    ]);
    exit;
}

// Vérification de l'utilisateur
if (verify_user_by_iban($request_iban, $request_first_name, $request_last_name)) {
    echo json_encode([
        "status" => "ok",
        "message" => "Utilisateur vérifié avec succès"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Aucune correspondance trouvée pour cet utilisateur et cet IBAN"
    ]);
}

exit;
