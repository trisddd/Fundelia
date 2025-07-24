<?php

require_once "db_connect.php";

// CREATE :
function create_owner($user_id, $account_id){
    global $db;
    $query="INSERT INTO `owners`(`user_id`,`account_id`) VALUES 
                (:user_id,:account_id);";
    $request = $db->prepare($query);
    $request->execute(array(
        ":user_id"=>$user_id,
        ":account_id"=>$account_id
    ));
}


// READ :
// Read all
function read_all_owners(){
    global $db;
    $query="SELECT 
                *
            FROM
                `owners`;";
    $request = $db->prepare($query);
    $request->execute();
    return $request->fetchAll(PDO::FETCH_ASSOC);
}


// Read number of account for one owner
function get_accounts_count_by_owner_id($id){
    global $db;
    $query="SELECT 
                COUNT(*)
            FROM
                `owners`
            GROUP BY 
                `user_id`
            HAVING
                `user_id`=?;";
    $request = $db->prepare($query);
    $request->execute([$id]);
    return $request->fetch(PDO::FETCH_ASSOC);
}

/**
 * Read all accounts of a user
 * @param Number the user_id from whom we want to get the account informations
 * @return Array all user's accounts
 */
function get_accounts_by_user_id($id){
    global $db;
    $query="SELECT 
                own.*, acct.`name` AS account_type, acc.`name` AS account_name, acc.`IBAN`, acc.`balance`, acc.`creation_date`, acc.`API_key`
            FROM
                `owners` AS own
                LEFT JOIN `accounts` as acc ON own.`account_id` = acc.`id`
                LEFT JOIN `account_types` as acct ON acc.`account_type_id` = acct.`id`
            WHERE
                own.`user_id`=?;";
    $request = $db->prepare($query);
    $request->execute([$id]);
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Read all accounts of an owner
 * @param Number the business_id from whom we want to get the account informations
 * @return Array all business's accounts
 */
function get_accounts_by_business_id($id){
    global $db;
    $query="SELECT 
                own.*, acct.`name` AS account_type, acc.`name` AS account_name, acc.`IBAN`, acc.`balance`, acc.`creation_date`, acc.`API_key`
            FROM
                `owners` AS own
                LEFT JOIN `accounts` as acc ON own.`account_id` = acc.`id`
                LEFT JOIN `account_types` as acct ON acc.`account_type_id` = acct.`id`
            WHERE
                own.`business_id`=?;";
    $request = $db->prepare($query);
    $request->execute([$id]);
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Read the account of a business by its iban
 * @param Number the business_id from whom we want to get the account informations
 * @return Array all business's accounts
 */
function get_account_by_business_id_and_iban($id, $iban){
    global $db;
    $query="SELECT 
                own.*, acct.`name` AS account_type, acc.`name` AS account_name, acc.`IBAN`, acc.`balance`, acc.`creation_date`, acc.`API_key`
            FROM
                `owners` AS own
                LEFT JOIN `accounts` as acc ON own.`account_id` = acc.`id`
                LEFT JOIN `account_types` as acct ON acc.`account_type_id` = acct.`id`
            WHERE
                own.`business_id` = ?
                AND acc.`IBAN`= ?;";
    $request = $db->prepare($query);
    $request->execute([$id, $iban]);
    return $request->fetch(PDO::FETCH_ASSOC);
}



#region UPDATE

function update_business_account_owner($business_id, $iban, $employee_id) {
    global $db;
    $query="UPDATE
                `owners` AS own
                LEFT JOIN `accounts` as acc ON own.`account_id` = acc.`id`
            SET
                own.`user_id` = :employee_id
            WHERE
                own.`business_id` = :business_id
                AND acc.`IBAN`= :iban;";
    $request = $db->prepare($query);
    $request->execute([
        ":business_id" => $business_id,
        ":iban" => $iban,
        ":employee_id" => $employee_id
    ]);
}

#endregion
#region DELETE

function delete_owner($user_id, $account_id){
    global $db;
    $query="DELETE FROM
                `owners`
            WHERE
                `user_id` = ?
            AND
                `account_id` = ?;";
    $request = $db->prepare($query);
    $request->execute([$user_id, $account_id]);
}

#endregion