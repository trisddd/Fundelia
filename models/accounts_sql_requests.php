<?php
require_once "db_connect.php";

require_once "services/ibans_management.php";

#region CREATE
// create account

/**
 * Create an account in the bank.
 * @param String $name the name of the account
 * @param Number $account_type_id the account type id (reflects to account_types table)
 * @param Number $user_id the account owner's user id (reflects to users table)
 * @return Number Returns new registered account id
 * @param Number $is_business to create an account for a business
 */
function create_account($name,$account_type_id, $user_id, $business_id = 0){
    global $db;

    $query = "SELECT id FROM `accounts` ORDER BY id DESC LIMIT 1";
    $request = $db->query($query);
    $result = $request->fetch();
    $last_account_id = $result ? $result['id'] : 1;

    $account_number = hash_11_Unique($last_account_id + 1);
    $iban = generate_IBAN_Fundelia($account_number);

    // Insertion dans la table accounts
    $query="INSERT INTO `accounts`(`name`, `account_type_id`, `IBAN`) VALUES 
                (:name, :account_type_id, :iban);";
    $request = $db->prepare($query);
    $request->execute(array(
        ":name" => $name, 
        ":account_type_id" => $account_type_id,
        ":iban" => $iban
    ));

    
    // Relation entre accounts et users dans owners
    $account_id = $db->lastInsertId();
    if ($business_id != 0) {
        $query="INSERT INTO `owners`(`user_id`, `business_id`, `account_id`) VALUES 
                    (:user_id, :business_id, :account_id);";
        $request = $db->prepare($query);
        $request->execute(array(
            ":user_id" => $user_id, 
            ":business_id" => $business_id, 
            ":account_id" => $account_id,
        ));
    } else {
        $query="INSERT INTO `owners`(`user_id`, `account_id`) VALUES 
                    (:user_id, :account_id);";
        $request = $db->prepare($query);
        $request->execute(array(
            ":user_id" => $user_id, 
            ":account_id" => $account_id,
        ));
    }
    return $last_account_id + 1;
}

/**
 * Create an "enterprise-ready" account in the bank and return the account created to pursue the creation.
 * @param String $name the name of the account
 * @param Number $business_id the account owner's business id
 * @return Array the account created
 */
function create_and_read_enterprise_account($account_name,$business_id, $user_id) {
    global $db;
    $account_id = create_account($account_name, 1, $user_id, $business_id, 1);
    $query="SELECT 
                acc.`IBAN`
            FROM
                `owners` AS own
                LEFT JOIN `accounts` as acc ON own.`account_id` = acc.`id`
                LEFT JOIN `account_types` as acct ON acc.`account_type_id` = acct.`id`
            WHERE
                own.`account_id`=?;";
    $request = $db->prepare($query);
    $request->execute([$account_id]);
    return $request->fetch(PDO::FETCH_ASSOC);
}
#endregion
#region READ
// Read all
/**
 * read all accounts
 */
function read_all_accounts(){
    global $db;
    $query="SELECT 
                *
            FROM
                `accounts`;";
    $request = $db->prepare($query);
    $request->execute();
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

// Read all account_types
function read_all_account_types(){
    global $db;
    $query="SELECT 
                *
            FROM
                `account_types`;";
    $request = $db->prepare($query);
    $request->execute();
    return $request->fetchAll();
}

// Read by id
/**
 * Read an account by its id
 * @param Number $id  the id of the account
 * @return Array all the informations of the account
 */
function read_account_by_id($id){
    global $db;
    $query="SELECT 
                accounts.*,
                users.id AS user_id,
                users.first_name,
                users.last_name,
                acct.name as account_type, 
                acct.ceiling, 
                acct.remuneration_rate
            FROM 
                accounts
                INNER JOIN owners ON accounts.id = owners.account_id
                INNER JOIN users ON owners.user_id = users.id
                LEFT JOIN `account_types` AS acct ON acct.id = accounts.account_type_id
            WHERE 
                accounts.id = :id;";
    $request = $db->prepare($query);
    $request->execute([":id"=>$id]);
    return $request->fetch(PDO::FETCH_ASSOC);
}

function get_all_accounts_by_user_id($user_id) {
    global $db;

    $query = "
        SELECT a.*
        FROM accounts a
        JOIN owners o ON a.id = o.account_id
        WHERE o.user_id = ?
    ";

    $stmt = $db->prepare($query);
    $stmt->execute([$user_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Read by iban
/**
 * Read an account by its iban
 * @param str $iban  the iban of the account (len = 27)
 * @return Array all the informations of the account
 */
function read_account_by_iban($iban){
    global $db;
    $query="SELECT 
                *
            FROM
                `accounts`
            WHERE
                `IBAN`=:iban;";
    $request = $db->prepare($query);
    $request->execute(array(
        ":iban"=>$iban
    ));
    return $request->fetch(PDO::FETCH_ASSOC);
}

/**
 * Get the informations of an account by the IBAN with the good owner_id
 * @param Number the owner_id (user_id) from whom we want to get the account informations
 * @param Str $iban  the iban of the account (len = 27)
 * @return Array all the informations of the account if its registered as user's one
 */
function read_account_by_owner_id($owner_id,$iban){
    global $db;
    $query="SELECT 
                acc.*, own.user_id, acct.name AS type, acct.ceiling, acct.remuneration_rate
            FROM
                `accounts` AS acc
                INNER JOIN `owners` AS own ON own.`account_id` = acc.`id`
                INNER JOIN `account_types` AS acct ON acct.`id` = acc.`account_type_id`
            WHERE
                acc.`iban` = :iban
                AND own.`user_id` = :owner_id;";
    $request = $db->prepare($query);
    $request->execute(array(
        ":iban"=>$iban,
        ":owner_id" => $owner_id
    ));
    return $request->fetch(PDO::FETCH_ASSOC);
}

// read the last account in the table
function read_last_account(){
    global $db;
    $query="SELECT * 
            FROM `accounts`
            ORDER BY `id` DESC
            LIMIT 1;";
    $request = $db->prepare($query);
    $request->execute();
    return $request->fetchAll();
}

// On cherche un compte dont le champ `h1` (en base) correspond à celui reçu

function h1_check($h1_receive){
    global $db;
    $stmt = $db->prepare("SELECT * FROM accounts WHERE API_key = :h1");
    $stmt->execute([':h1' => $h1_receive]);
    
    return  $stmt->fetch(PDO::FETCH_ASSOC);

}

// Read amount total of user
function read_total_amount($user_id){
    global $db;
    $query="SELECT 
                SUM(a.balance) AS total_balance
            FROM 
                owners o
            JOIN 
                accounts a ON o.account_id = a.id
            WHERE 
                o.user_id = ?
                AND o.business_id IS NULL;";
    $request = $db->prepare($query);
    $request->execute([$user_id]);
    return $request->fetch(PDO::FETCH_ASSOC);
}

// Read amount total current of user 
function read_total_amount_current($user_id){
    global $db;
    $query="SELECT 
                SUM(a.balance) AS total_balance
            FROM 
                owners o
            JOIN 
                accounts a ON o.account_id = a.id
            WHERE 
                o.user_id = ?
                AND o.business_id IS NULL
                AND a.account_type_id = 1";
    $request = $db->prepare($query);
    $request->execute([$user_id]);
    return $request->fetch(PDO::FETCH_ASSOC);
}

// Read amount total saving of user 
function read_total_amount_saving($user_id){
    global $db;
    $query="SELECT 
                SUM(a.balance) AS total_balance
            FROM 
                owners o
            JOIN 
                accounts a ON o.account_id = a.id
            WHERE 
                o.user_id = ?
                AND o.business_id IS NULL
                AND a.account_type_id != 1";
    $request = $db->prepare($query);
    $request->execute([$user_id]);
    return $request->fetch(PDO::FETCH_ASSOC);
}
/**
 * just to get the user id  by his iban faor pushing notif 
 * @param mixed $iban
 */
function get_user_id_by_iban($iban) {
    global $db;

    $query = "
        SELECT o.user_id
        FROM owners o
        JOIN accounts a ON o.account_id = a.id
        WHERE a.IBAN = ?
    ";

    $stmt = $db->prepare($query);
    $stmt->execute([$iban]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? $result['user_id'] : null;
}


// Read amount total of user
function read_total_amount_pro($user_id){
    global $db;
    $query="SELECT 
                SUM(acc.balance) AS total_balance
            FROM 
                owners AS o
            JOIN 
                accounts AS acc ON o.account_id = acc.id
            WHERE 
                o.user_id = ?
                AND o.business_id IS NOT NULL;";
    $request = $db->prepare($query);
    $request->execute([$user_id]);
    return $request->fetch(PDO::FETCH_ASSOC);
}


function get_label_stats_for_user($user_id) {
    global $db;

    $query = "
        SELECT 
            l.name, 
            l.colour, 
            ROUND(
                COUNT(tl.transaction_id) * 100.0 / NULLIF((
                    SELECT COUNT(*) 
                    FROM transactions_labels tl2
                    JOIN labels l2 ON tl2.label_id = l2.id
                    WHERE l2.user_id = :user_id
                ), 0), 
            2) AS percentage
        FROM labels l
        LEFT JOIN transactions_labels tl ON l.id = tl.label_id
        WHERE l.user_id = :user_id
        GROUP BY l.id, l.name, l.colour
        HAVING percentage > 0
        ORDER BY percentage DESC
    ";

    $request = $db->prepare($query);
    $request->execute(['user_id' => $user_id]);

    return $request->fetchAll(PDO::FETCH_ASSOC);
}

function get_weekly_expenses_comparison($user_name) {
    global $db;

    $query = "
        SELECT 
            DATE_FORMAT(`date`, '%w') AS day_of_week,
            WEEK(`date`, 1) AS week_number,
            YEAR(`date`) AS year,
            SUM(amount) AS total
        FROM transactions
        WHERE (emitter_name = :user)
          AND (
              (WEEK(`date`, 1) = WEEK(CURDATE(), 1) AND YEAR(`date`) = YEAR(CURDATE())) OR
              (WEEK(`date`, 1) = WEEK(CURDATE(), 1) - 1 AND YEAR(`date`) = YEAR(CURDATE()))
          )
        GROUP BY week_number, day_of_week
    ";

    $request = $db->prepare($query);
    $request->execute(['user' => $user_name]);
    $results = $request->fetchAll(PDO::FETCH_ASSOC);

    $current_week = array_fill(0, 7, 0);
    $last_week = array_fill(0, 7, 0);

    $current_week_number = (int)date('W');
    $current_year = (int)date('Y');

    foreach ($results as $row) {
        $day = ((int)$row['day_of_week'] + 6) % 7;
        $amount = abs((float)$row['total']);
        $week = (int)$row['week_number'];
        $year = (int)$row['year'];

        if ($week === $current_week_number && $year === $current_year) {
            $current_week[$day] += $amount;
        } elseif ($week === $current_week_number - 1 && $year === $current_year) {
            $last_week[$day] += $amount;
        }
    }

    return [
        'current_week' => $current_week,
        'last_week' => $last_week
    ];
}

function check_accounts_is_empty($id) {
    global $db;

    $query = "SELECT *
              FROM accounts
              WHERE id = ?
              AND balance = 0;";

    $request = $db->prepare($query);
    $request->execute([$id]);
    return $request->fetchAll(PDO::FETCH_ASSOC) != array();
}

function get_labels_for_transaction($transaction_id) {
    global $db;

    $query = "SELECT l.id, l.name
              FROM transactions_labels tl
              JOIN labels l ON tl.label_id = l.id
              WHERE tl.transaction_id = :transaction_id";

    $request = $db->prepare($query);
    $request->execute(['transaction_id' => $transaction_id]);
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

#endregion
#region UPDATE

/**
 * Debit an account
 * @param str $iban  the iban of the account we want to debit (len = 27)
 * @param Number $amount  the amount of the debit
 */
function debit_account($iban, $amount){
    global $db;
    $query="UPDATE
                `accounts`
            SET
                `balance`= `balance`-:amount
            WHERE
                `IBAN`= :iban;";
    $request = $db->prepare($query);
    $request->execute(array(
        ":amount"=>$amount,
        ":iban"=>$iban
    ));
}

/**
 * Credit an account 
 * @param String $iban  the iban of the account we want to debit (len = 27)
 * @param Number $amount  the amount of the debit
 */
function credit_account($iban, $amount){
    global $db;
    $query="UPDATE
                `accounts`
            SET
                `balance`= `balance`+:amount
            WHERE
                `IBAN`= :iban;";
    $request = $db->prepare($query);
    $request->execute(array(
        ":amount"=>$amount,
        ":iban"=>$iban
    ));
}

/**
 * Re-create API private key (K2)
 * @param String $iban  the iban of the account we want to re-create the API private key (len = 27)
 */
function update_account_keys($iban) {
    global $db, $K1;
    $private_key = password_hash(password_hash($iban, PASSWORD_BCRYPT), PASSWORD_BCRYPT); // on crée une clé api à partir du hash d'un hash
    $h1 = password_hash($K1.$private_key, PASSWORD_BCRYPT);
    $query="UPDATE
                `accounts`
            SET
                `private_key`= :private_key,
                `API_key`= :h1
            WHERE
                `IBAN`= :iban;";
    $request = $db->prepare($query);
    $request->execute(array(
        ":private_key"=>$private_key,
        ":h1"=>$h1,
        ":iban"=>$iban
    ));
}

#endregion
#region DELETE
/**
 * delete an account
 */
function delete_account($id ,$user_id) {
    global $db;
    $query1="DELETE FROM
                `owners`
            WHERE
                `user_id` = ?
                AND `account_id` =?;";
    $request1 = $db->prepare($query1);
    $request1->execute([$user_id,$id]);
    $query2="DELETE FROM
                `accounts`
            WHERE
                `id` = ?;";
    $request2 = $db->prepare($query2);
    $request2->execute([$id]);
}