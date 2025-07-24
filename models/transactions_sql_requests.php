<?php

require_once "models/db_connect.php";
require_once "models/accounts_sql_requests.php";
require_once "models/users_sql_requests.php";
require_once "models/banks_sql_requests.php";
require_once "services/ibans_management.php";




// CREATE
function create_transaction($emitter_iban, $beneficiary_iban, $wording, $amount, $type, $emitter_name, $beneficiary_name)
{
    global $db;

    if ($wording == "") {
        $wording = "Virement de " . $amount . " effectué par " . $emitter_name . " à " . $beneficiary_name;
    }

    $type = ($type === "payment") ? "payment" : "transfer";

    $query = "INSERT INTO `transactions` (
                `emitter_IBAN`, `beneficiary_IBAN`, `wording`, `amount`,
                `transaction_type`, `emitter_name`, `beneficiary_name`
              ) VALUES (
                :emitter_iban, :beneficiary_iban, :wording, :amount,
                :type, :emitter_name, :beneficiary_name
              )";

    $request = $db->prepare($query);
    $request->execute(array(
        ":emitter_iban" => $emitter_iban,
        ":beneficiary_iban" => $beneficiary_iban,
        ":wording" => $wording,
        ":amount" => floatval($amount),
        ":type" => $type,
        ":emitter_name" => $emitter_name,
        ":beneficiary_name" => $beneficiary_name
    ));

    return true;
}


function create_transfer($emitter_iban, $beneficiary_iban, $wording, $amount)
{
    global $db;
    try {
        $db->beginTransaction();

        IBAN_verification($emitter_iban);
        IBAN_verification($beneficiary_iban);


        // Check if iban are registered in our bank database
        $emitter_account = read_account_by_iban($emitter_iban);
        if ($emitter_account == array()) {
            throw new Exception("L'IBAN émetteur n'est pas enregistré dans la banque");
        }

        make_transfer($emitter_account, $beneficiary_iban, $wording, $amount);


        $db->commit();
        return true ;

    } catch (\Exception $th) {
        $db->rollBack();
        echo $th->getMessage();
        return [
            'success' => false,
            'message' => $th->getMessage()
        ];    }
}


/**
 * Creates a payment transaction by debiting funds from a card's associated account and optionally recording the transaction details.
 *
 * @param string $card_number The number of the card to debit.
 * @param string $to_iban The IBAN (International Bank Account Number) of the beneficiary account.
 * @param float|int $amount The amount to be transferred.
 * @param string $wording The description or reason for the payment.
 *
 * This function performs the following steps within a database transaction:
 * 1. Retrieves the card details, associated account ID, IBAN, and current balance for the given card number, locking the row for update.
 * 2. Checks if the account balance is sufficient to cover the payment amount.
 * 3. Debits the specified amount from the account balance.
 * 4. If the beneficiary bank code (extracted from the `$to_iban`) is not "75076", it calls the `create_transaction` function to record the transaction details .
 * 5. Commits the database transaction if all operations are successful.
 *
 *
 * @return bool Returns `true` if the payment was successful, `false` otherwise.
 */
function create_payment($card_number, $to_iban, $amount, $wording, $holder_name, $beneficiary_name)
{
    global $db;

    try {
        $db->beginTransaction();

        ////// Récupérer la carte et le compte source
        $query = "SELECT c.id AS card_id, c.account_id, a.IBAN, a.balance 
                  FROM cards c
                  JOIN accounts a ON c.account_id = a.id
                  WHERE c.card_numbers = :card_number
                  FOR UPDATE";
        $stmt = $db->prepare($query);
        $stmt->execute([':card_number' => $card_number]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        $from_iban = $result['IBAN'];
        $from_account_id = $result['account_id'];
        $from_balance = $result['balance'];

        // 2. Vérifier le solde
        if ($from_balance <= $amount) {
            return false;
            // throw new Exception("Solde insuffisant.");
        }



        // 4. Effectuer la transaction : débit
        $stmt = $db->prepare("UPDATE accounts SET balance = balance - :amount WHERE id = :id");
        $stmt->execute([':amount' => $amount, ':id' => $from_account_id]);
        
        if ($amount >= 100) {
        $user_ib = get_user_id_by_iban($from_iban);
        if ($user_ib !== null) {
            notification_of_big_transfer_debit($user_ib, $amount);
        }
    }


        // Transaction beneficiary_bank_code avec la variable toiban 
        //create_transaction($emitter_iban, $beneficiary_iban, $wording, $amount, $type, $emitter_name, $beneficiary_name)

        $beneficiary_bank_code = substr($to_iban, 4, 5);
        if ($beneficiary_bank_code != "75076") { // Pour pas créer de doublons
            create_transaction($from_iban, $to_iban, $wording, $amount, 'payment', $holder_name, $beneficiary_name); // On crée une nouvelle donnée transaction pour montrer que la transaction a bien été faite
        }


        $db->commit();
        return true;

    } catch (Exception $e) {
        $db->rollBack();
        return false;
    }
}

/**
 * Summary of getaccount_nameByIban
 * @param string $iban
 * 
 */
function get_account_name_by_iban($iban)
{

    global $db;
    $query = "SELECT name FROM accounts WHERE iban = :iban LIMIT 1";

    $stmt = $db->prepare($query);
    $stmt->execute([':iban' => $iban]);

    $account_name = $stmt->fetchColumn();

    return $account_name !== false ? $account_name : null;
}

function get_user_name_by_iban($iban) {
    global $db;

    $query = "
        SELECT u.first_name, u.last_name
        FROM users u
        JOIN owners o ON u.id = o.user_id
        JOIN accounts a ON o.account_id = a.id
        WHERE a.IBAN = ?
        LIMIT 1
    ";

    $stmt = $db->prepare($query);
    $stmt->execute([$iban]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ?: null; // Retourne null si rien trouvé
}


/**
 * permet de rcuperer liban a partir du numero de compte 
 * @param $card_number 
 * @return $iban
 */

function get_iban_by_card_number($card_number)
{
    global $db;

    $query = "
        SELECT a.IBAN
        FROM cards c
        JOIN accounts a ON c.account_id = a.id
        WHERE c.card_numbers = :card_number
        LIMIT 1
    ";

    $stmt = $db->prepare($query);
    $stmt->execute([':card_number' => $card_number]);

    $iban = $stmt->fetchColumn();

    return $iban !== false ? $iban : null;
}





/**
 * Verifies if the provided card details match the information stored in the database.
 *
 * @param string $card_number The credit card number to verify.
 * @param string $card_exp The credit card expiration date (format: MM/YY or MMYY).
 * @param string $card_cvc The credit card CVC/CVV code.
 * @param string $holder_name The name of the cardholder.
 *
 * @return bool Returns `true` if all provided details match the database record, `false` otherwise (including if the card number is not found).
 */

function verifiy_payment_info($card_number, $card_exp, $card_cvc, $holder_name)
{
    global $db;

    // 1. Récupération de la carte
    $query = "SELECT * FROM cards WHERE card_numbers = :card_number";
    $stmt_card = $db->prepare($query);
    $stmt_card->execute([
        ':card_number' => $card_number
    ]);
    $card = $stmt_card->fetch(PDO::FETCH_ASSOC);

    if (!$card) {
        return false;
    }


    $cvc_match = (string) $card['CSC'] === (string) $card_cvc;
    $exp_match = $card['expiration_date'] === $card_exp;
    $holder_match = strtolower(trim($card['holder_name'])) === strtolower(trim($holder_name));

    if (!($cvc_match && $exp_match && $holder_match)) {
        return false;
    }


    return true;
}
/**
 * Summary of verify_card_status_status this function verify if the card is shorted-lived 
 * @param number $cardnumber
 * @return bool
 */
function verify_card_status($card_number) {
    global $db;
    $query = 'SELECT card_type FROM cards WHERE card_numbers = :card_number';
    $stmt = $db->prepare($query);
    $stmt->execute([':card_number' => $card_number]);
    $card = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($card && $card['card_type'] === 'short-lived') {
        return true;
    }
    return false;
}

function get_user_id_and_card_name_by_card_number($card_number) {
    global $db;
    $query = 'SELECT user_id, name FROM cards WHERE card_numbers = :card_number';
    $stmt = $db->prepare($query);
    $stmt->execute([':card_number' => $card_number]);
    $card = $stmt->fetch(PDO::FETCH_ASSOC);

    return $card ?: null;
}



/**
 * Checks if the balance of the account associated with a given card number is sufficient for a specified amount.
 * it verify also if the card is frozen or not 
 * @param string $card_number The credit card number to check.
 * @param float|int $amount The amount to compare against the account balance.
 *
 * @return bool Returns `true` if the account balance is greater than the provided amount, `false` if the card number is not found or if the balance is insufficient.
 */


function is_card_balance_sufficient($card_number, $amount)
{
    global $db;

    $query = "
        SELECT a.balance, c.freeze
        FROM cards c
        JOIN accounts a ON c.account_id = a.id
        WHERE c.card_numbers = :card_number
        LIMIT 1
        ";
    $stmt = $db->prepare($query);
    $stmt->execute([':card_number' => $card_number]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // La carte n'existe pas
    if ($result === false) {
        return false;
    }

    $balance = $result['balance'];
    $is_frozen = (bool) $result['freeze']; // Convertir la valeur BOOLEAN de la base de données en booléen PHP

    // Vérifier si la carte est gelée
    if ($is_frozen) {
        return false;
    }

    // Vérifier si le solde est insuffisant
    if ($balance <= $amount) {
        return false;
    }

    // Solde suffisant et carte non gelée
    return true;
}

//Fonction pour générer un code hex pour la couleur
function generate_random_hex_color() {
    return sprintf("#%06X", mt_rand(0, 0xFFFFFF));
}


function create_label($label_name, $user_id){
    global $db;

    $color = generate_random_hex_color();

    $query="INSERT INTO `labels`(`name`,`user_id`, `colour`) VALUES 
                (:label_name, :user_id, :colour);";
    $request = $db->prepare($query);
    $request->execute(array(
        'label_name'=>$label_name,
        'user_id'=>$user_id,
        'colour'  => $color
    ));
}



// READ
// Read all
function read_all_labels($user_id)
{
    global $db;
    $query = "SELECT 
                *
            FROM
                `labels`
            WHERE user_id = :user_id;";
    $request = $db->prepare($query);
    $request->execute(array(
        'user_id'=>$user_id
    ));
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

// Read by id

// Read by emitter iban

// Read by beneficiary_iban


// UPDATE

//Associer un label à une transaction
function assign_label_to_transaction ($tx_id, $label_id) {
     global $db;

    $query = "INSERT INTO `transactions_labels` (transaction_id, label_id) VALUES (:tx_id, :label_id)";
    $request = $db->prepare($query);
    $request->execute([
        'tx_id' => $tx_id,
        'label_id' => $label_id
    ]);
}
//Dissocier un label d'une transaction
function remove_label_from_transaction($tx_id, $label_id) {
    global $db;

    $query = "DELETE FROM `transactions_labels` WHERE transaction_id = :tx_id AND label_id = :label_id";
    $request = $db->prepare($query);
    $request->execute([
        'tx_id' => $tx_id,
        'label_id' => $label_id
    ]);
}

// DELETE