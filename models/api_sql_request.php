<?php
require_once "./models/db_connect.php";
// On cherche un compte dont le champ `h1` (en base) correspond à celui reçu
function h1_check($h1_recu){
    global $db;
    $stmt = $db->prepare("SELECT * FROM accounts WHERE API_key = :h1");
    $stmt->execute([':h1' => $h1_recu]);
    
    return  $stmt->fetch(PDO::FETCH_ASSOC);

}


function get_mail_by_card_number($card_number) {
    global $db;
    $query = "
        SELECT u.email
        FROM users u
        JOIN cards c ON u.id = c.user_id
        WHERE c.card_numbers = :card_number
        LIMIT 1
    ";

    $stmt = $db->prepare($query);
    $stmt->execute([':card_number' => $card_number]);

    $email = $stmt->fetchColumn();

    return $email !== false ? $email : null;
}


