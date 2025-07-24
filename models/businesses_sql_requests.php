<?php 

require_once "db_connect.php";

/**
 * Create a real business with siren
 * @param String $name the name of the business
 * @param String $SIREN the SIREN of the business
 * @param String $email the email of the business
 */
function create_real_business($name, $SIREN, $email){
    global $db;
    $query="INSERT INTO `businesses`(`name`,`SIREN`,`email`) VALUES 
                (:name,:SIREN,:email);";
    $request = $db->prepare($query);
    $request->execute(array(
        ":name"=>$name,
        ":SIREN"=>$SIREN,
        ":email"=>$email,
    ));
    // Return the id of the business which has been added in this sql request
    return $db->lastInsertId();
}

/**
 * Create a new business without siren (automatically verified)
 * @param String $name the name of the business
 * @param String $email the email of the business
 */
function create_new_business($name, $email){
    global $db;
    $query="INSERT INTO `businesses`(`name`,`email`, `is_verified`) VALUES 
                (:name, :email, true);";
    $request = $db->prepare($query);
    $request->execute(array(
        ":name"=>$name,
        ":email"=>$email,
    ));
    // Return the id of the business which has been added in this sql request
    return $db->lastInsertId();
}

/**
 * Read all business informations
 * @param Number $user_id the id of the user
 * @return Array the informations
 */
function read_all_businesses_informations_of_user($user_id){
    global $db;
    $query="SELECT 
                *
            FROM
                `businesses` b
                INNER JOIN `business_user` bu ON b.`id` = bu.`business_id` 
            WHERE
                bu.`user_id` = ?
            ORDER BY
                `is_verified` DESC,
                `name` ASC;";
    $request = $db->prepare($query);
    $request->execute([$user_id]);
    return $request->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Read all business informations
 * @param Number $business_id the business id in the table businesses
 * @return Array the informations
 */
function read_business_informations($business_id){
    global $db;
    $query="SELECT 
                *
            FROM
                `businesses`
            WHERE
                `id` = ?;";
    $request = $db->prepare($query);
    $request->execute([$business_id]);
    return $request->fetch(PDO::FETCH_ASSOC);
}

/**
 * Read all business informations
 * @param $business_id the business id in the table businesses
 * @return Array the informations
 */
function read_business_informations_by_siren($siren){
    global $db;
    $query="SELECT 
                *
            FROM
                `businesses`
            WHERE
                `SIREN` = ?;";
    $request = $db->prepare($query);
    $request->execute([$siren]);
    return $request->fetch(PDO::FETCH_ASSOC);
}

/**
 * Read all business informations
 * @param $business_id the business id in the table businesses
 * @return Array the informations
 */
function read_business_informations_by_name($name){
    global $db;
    $query="SELECT 
                *
            FROM
                `businesses`
            WHERE
                `name` = ?;";
    $request = $db->prepare($query);
    $request->execute([$name]);
    return $request->fetch(PDO::FETCH_ASSOC);
}

function check_is_verified_business($id){
    global $db;
    $query="SELECT 
                *
            FROM
                `businesses`
            WHERE
                `id` = ?;";
    $request = $db->prepare($query);
    $request->execute([$id]);
    return $request->fetch(PDO::FETCH_ASSOC)["is_verified"] == 1;
}
/**
 * Change business to verified status 
 * @param String $id  the id of the business
 */
function verify_business($id){
    global $db;
    $query="UPDATE
                `accounts`
            SET
                `balance`= `balance`+:amount
            WHERE
                `id`= :id;";
    $request = $db->prepare($query);
    $request->execute(array(
        "id"=> $id
    ));
}



#region DELETE

function delete_business($business_id){
    global $db;
    $query="DELETE FROM
                `businesses`
            WHERE
                `id` = ?;";
    $request = $db->prepare($query);
    $request->execute([$business_id]);
}


#endregion