<?php
require_once 'models/transactions_sql_requests.php';
require_once 'models/users_sql_requests.php';
require_once 'models/accounts_sql_requests.php';
require_once 'models/owners_sql_requests.php';



if (!isset($_SESSION['user_id'])) {
    require_once 'controllers/errors/error_404_controller.php';
    exit();
}

$user_id = $_SESSION['user_id'];

$sort_by = $_GET['sort'] ?? 'date-desc';
$limit = $_GET['limit'] ?? 100;

$transactions = get_transfert_history_by_user_id($user_id, $limit, $sort_by);

// Récupère ses comptes
$accounts = get_accounts_by_user_id($user_id);
$user_ibans = array_column($accounts, 'IBAN');
$labels = read_all_labels($user_id);

// --- FIN NOUVELLE LOGIQUE DE TRI ---


/**
 * Regroupe les transactions par type (dépense ou recette) et par date.
 *
 * Une transaction est considérée comme une **dépense** si l'IBAN de l'émetteur 
 * figure dans la liste des IBANs de l'utilisateur. Sinon, c'est une **recette**.
 *
 * Le regroupement final est structuré comme suit :
 * [
 * 'expenses' => [ 'date1' => [tx1, tx2], 'date2' => [tx3, ...], ... ],
 * 'incomes'  => [ 'date1' => [tx4, tx5], ... ]
 * ]
 *
 * Chaque transaction retournée contient :
 * - 'name' : nom de l'autre partie (destinataire ou émetteur selon le cas)
 * - 'time' : heure de la transaction (format HH:MM)
 * - 'amount' : montant (positif ou négatif)
 * - 'wording' : libellé complet
 *
 * tx = transaction
 * * * @param array $transactions      Liste des transactions brutes depuis la base (maintenant déjà triées)
 * @param array $user_iban_list    IBANs appartenant à l'utilisateur connecté
 *
 * @return array                   Tableau associatif groupé par type et date
 */

function group_transactions_by_type_and_date($transactions, $user_iban_list)
{
    $grouped = [
        'expenses' => [],
        'incomes' => []
    ];

    // Tableau de traduction des mois en français
    $mois_fr = [
        'January' => 'Janvier',
        'February' => 'Février',
        'March' => 'Mars',
        'April' => 'Avril',
        'May' => 'Mai',
        'June' => 'Juin',
        'July' => 'Juillet',
        'August' => 'Août',
        'September' => 'Septembre',
        'October' => 'Octobre',
        'November' => 'Novembre',
        'December' => 'Décembre'
    ];

    foreach ($transactions as $tx) {
        $id = $tx['id'];
        // Convertit la date de la BDD (ex: 'YYYY-MM-DD HH:MM:SS') en timestamp UNIX
        // C'est le même timestamp qui était généré par new DateTime($tx['date'])->getTimestamp()
        $timestamp = strtotime($tx['date']);

        // Formate la date en anglais avec le nom du mois complet (ex: "21 May 2025")
        $date_en = date('d F Y', $timestamp);

        // Traduit le nom du mois en français en utilisant le tableau de traduction
        $date = strtr($date_en, $mois_fr);

        // Formate l'heure à partir du même timestamp
        $time = date('H:i', $timestamp);

        $emitter_is_user = in_array($tx['emitter_IBAN'], $user_iban_list);
        $beneficiary_is_user = in_array($tx['beneficiary_IBAN'], $user_iban_list);
        $transaction_type = $tx['transaction_type'] == "transfer" ? "Virement" : "Paiement";
        if ($emitter_is_user && $beneficiary_is_user) {
            $entry = [
                'id' => $id,
                'name' => $transaction_type . " interne",
                'time' => $time,
                'amount' => $tx['amount'],
                'wording' => $tx['wording'],
                'original_date' => $tx['date']
            ];
            $grouped['expenses'][$date][] = $entry;
            $grouped['incomes'][$date][] = $entry;
            continue;
        }


        if ($emitter_is_user) {
            $type = 'expenses';
            $name = $tx['beneficiary_name'];
            $amount = "-" . $tx['amount'];
        } else {
            $type = 'incomes';
            $name = $tx['emitter_name'];
            $amount = $tx['amount'];
        }

        $entry = [
            'id' => $id,
            'name' => $name,
            'time' => $time,
            'amount' => $amount,
            'wording' => $tx['wording'],
            'original_date' => $tx['date']
        ];

        if (!isset($grouped[$type][$date])) {
            $grouped[$type][$date] = [];
        }



        $grouped[$type][$date][] = $entry;
    }
    return $grouped;
}

$grouped_tx = group_transactions_by_type_and_date($transactions, $user_ibans);
$labels_for_tx = [];

foreach ($transactions as $tx) {
    $labels_for_tx[$tx['id']] = get_labels_for_transaction($tx['id']);
}
// dd($labels_for_tx);

display('show_transaction_history', 'Historique des Transactions');
