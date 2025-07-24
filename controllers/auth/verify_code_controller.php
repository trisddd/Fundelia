<?php

require_once "models/users_sql_requests.php";

header('Content-Type: application/json');

try {

    if (!isset($_SESSION['user_mail'])) {
        throw new Exception("Utilisateur non connecté");
    }

    $user = read_user_by_email($_SESSION['user_mail']);

    if (!$user || !isset($user['code'])) {
        throw new Exception("Utilisateur invalide");
    }

    $code_stocke = $user['code'];
    $code_recu = $_POST['code'] ?? null;
    if ($code_recu && password_verify($code_recu, $code_stocke)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
