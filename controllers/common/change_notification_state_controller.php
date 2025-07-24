<?php
require_once "models/users_sql_requests.php";
header('Content-Type: application/json');

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception(message: "Utilisateur non connecté");
    }
    $notif_id = $_POST['notif_id'] ?? null;
    if (!$notif_id) {
        throw new Exception("ID de notification manquant");
    }

    $success = mark_notification_as_read($notif_id, $_SESSION['user_id']);
    echo json_encode(['success' => $success]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
