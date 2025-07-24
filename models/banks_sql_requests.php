<?php

require_once "models/db_connect.php";

// CREATE





// READ
/**
 * Get bank informations by its BIN
 * @param String $BIN the BIN (bank cards) with 6 numbers
 * @return Array bank informations
 */
function get_bank_by_BIN($BIN){
    global $db;
    $query="SELECT 
                *
            FROM
                `banks`
            WHERE
                `BIN`=?;";
    $request = $db->prepare($query);
    $request->execute([$BIN]);
    return $request->fetch(PDO::FETCH_ASSOC);
}

/**
 * Get bank informations by its bank code
 * @param String $BIN the bank code (IBAN) à 5 numbers
 * @return Array bank informations
 */
function get_bank_by_bank_code($bank_code){
    global $db;
    $query="SELECT 
                *
            FROM
                `banks`
            WHERE
                `bank_code`=?;";
    $request = $db->prepare($query);
    $request->execute([$bank_code]);
    return $request->fetch(PDO::FETCH_ASSOC);
}

/**
 * Check if h1 is in banks and if it is, returns the bank informations
 * @param String $h1 the API_key of the bank
 * @return Array bank informations
 */
function bank_h1_check($h1){
    global $db;
    $query="SELECT * FROM banks WHERE API_key = :h1";
    $request = $db->prepare($query);
    $request->execute([':h1' => $h1]);
    return  $request->fetch(PDO::FETCH_ASSOC);
}
