<?php

require_once "models/transactions_sql_requests.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['label_name']) && isset( $_SESSION['user_id'])) {
    create_label($_POST['label_name'], $_SESSION['user_id']);
    header("Location: /show_transaction_history");
}

display("add_labels","Ajout Label");