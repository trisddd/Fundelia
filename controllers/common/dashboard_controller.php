<?php
require_once "models/accounts_sql_requests.php";
require_once "models/users_sql_requests.php";

// affichage du nom et du solde du client 
$user_last_name =  $_SESSION['user_last_name'] ;
$user_first_name =  $_SESSION['user_first_name'] ;
$user_name = $_SESSION['user_first_name'] ." ". $_SESSION['user_last_name'] ;
$user_id = $_SESSION['user_id'];
$unread_notif=count_unread_notifications_by_user($user_id);
$total_amount= read_total_amount($user_id)["total_balance"];
$total_amount_current=read_total_amount_current($user_id)["total_balance"];
$total_amount_saving=read_total_amount_saving($user_id)["total_balance"];
if ($total_amount==NULL) {
    $total_amount="0.00";
}
if ($total_amount_current==NULL) {
    $total_amount_current="0.00";
}
if ($total_amount_saving==NULL) {
    $total_amount_saving="0.00";
}

$txs = get_transaction_history_by_user_id_for_dashboard($user_id, 3);

function get_transactions_details ($txs, $user_name) {
    $transactions = [];
    // Tableau de traduction des mois en français
    $mois_fr = [
        'January' => 'Janvier', 'February' => 'Février', 'March' => 'Mars', 'April' => 'Avril',
        'May' => 'Mai', 'June' => 'Juin', 'July' => 'Juillet', 'August' => 'Août',
        'September' => 'Septembre', 'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Décembre'
    ];
    foreach ($txs as $tx) {
        // Convertit la date de la BDD (ex: 'YYYY-MM-DD HH:MM:SS') en timestamp UNIX
        // C'est le même timestamp qui était généré par new DateTime($tx['date'])->getTimestamp()
        $timestamp = strtotime($tx['date']); 
        
        // Formate la date en anglais avec le nom du mois complet (ex: "21 May 2025")
        $date_en = date('d F Y', $timestamp); 
        
        // Traduit le nom du mois en français en utilisant le tableau de traduction
        $date = strtr($date_en, $mois_fr);

        // Formate l'heure à partir du même timestamp
        $time = date('H:i', $timestamp);

        $name = $tx['wording'];

        if (str_contains($user_name, $tx['emitter_name'])) {
            $amount="-".$tx['amount'];
        }
        else {
            $amount=$tx['amount'];
        }

        $transactions[] = [$date, $time, $name, $amount];

    };
    return $transactions;
}
$transactions = get_transactions_details($txs, $user_name);
$notifications = read_last_3_notifications_by_user($user_id);
$label_stats = get_label_stats_for_user($user_id);
$weekly_comparison_data = get_weekly_expenses_comparison($user_name);
// $notification['message'];

$notifications = read_last_3_notifications_by_user($user_id);
// $notification['message'];



// foreach ($notifications as $notification) {
//     echo "voici une $notification ";
// }
// dd($notifications);
display("dashboard","Dashboard");

