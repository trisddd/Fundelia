<?php

require_once "models/transactions_sql_requests.php";

if (isset($_GET['tx_id'], $_GET['label_id'])) {
    $tx_id = (int)$_GET['tx_id'];
    $label_id = (int)$_GET['label_id'];
    remove_label_from_transaction($tx_id, $label_id);
    header('Location: /show_transaction_history');
}