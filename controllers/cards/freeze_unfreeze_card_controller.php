<?php
require_once "models/users_sql_requests.php";

if (isset($_GET['card_number']) && isset($_GET['freeze'])) {
    $card_number = filter_input(INPUT_GET, 'card_number', FILTER_SANITIZE_NUMBER_INT);
    $freeze = filter_input(INPUT_GET, 'freeze', FILTER_VALIDATE_INT);

    if ($card_number !== false && $freeze !== null && ($freeze === 0 || $freeze === 1)) {
        $resultat = freeze_unfreeze_card($card_number, $freeze);
        if ($resultat > 0) {
            $_SESSION['toast'] = [
                'message' => "modification prise en compte",
                'type' => 'success'
            ];
            header("Location: /show_cards_according_to_account");
            exit;
        } else {
            header("Location: /error_404");
            exit;
        }
    } else {
        // Invalid input values
        header("Location: /error_404");
        exit;
    }
} else {
    header("Location: /error_404");
    exit;
}
