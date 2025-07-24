<?php
require_once "models/users_sql_requests.php";


if (isset($_GET['card_number'])) {
    $card_number = filter_input(INPUT_GET, 'card_number', FILTER_SANITIZE_NUMBER_INT);

    if ($card_number !== false && $card_number !== null) {
        $resultat = delete_card($card_number);

        if ($resultat > 0) {
            $_SESSION['toast'] = [
                'message' => "Carte supprimée avec succès !",
                'type' => 'success'
            ];
            header("Location: /show_cards_according_to_account");
            exit;
        }
    }
}
header("Location: /error_404");
exit;
