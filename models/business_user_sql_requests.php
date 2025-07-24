<?php

require_once "db_connect.php";

// CREATE :
function add_user_to_business($user_id, $business_id, $role){
    global $db;
    $query="INSERT INTO `business_user`(`user_id`,`business_id`,`role`) VALUES 
                (:user_id,:account_id,:role);";
    $request = $db->prepare($query);
    $request->execute(array(
        ":user_id"=>$user_id,
        ":account_id"=>$business_id,
        ":role"=>$role,
    ));
}


// READ :
// Read all

/**
 * Get all the users of a business
 * @param Number $business_id the id of the business
 * @return Array all the users
 */
function read_all_users_of_business($business_id){
    global $db;
    $query="SELECT 
                bu.user_id, bu.role, u.first_name, u.last_name, u.email, u.birthdate, u.country, u.genre
            FROM
                `business_user` AS bu
                LEFT JOIN `users` AS u ON u.id = bu.user_id
            WHERE
                bu.`business_id` = ?
            ORDER BY
                u.last_name ASC,
                u.first_name ASC;";
    $request = $db->prepare($query);
    $request->execute([$business_id]);
    $all_users = $request->fetchAll(PDO::FETCH_ASSOC);
    $response = [
        "manager" => [],
        "employee" => [],
    ];
    foreach ($all_users as $user) {
        array_push($response[$user["role"]],$user);
    }
    return $response;
}

/**
 * Get all the businesses of a user
 * @param Number $user_id the id of the user
 * @return Array all the businesses
 */
function read_all_businesses_of_user($user_id){
    global $db;
    $query="SELECT 
                *
            FROM
                `business_user`
            WHERE
                `user_id` = ?;";
    $request = $db->prepare($query);
    $request->execute([$user_id]);
    return $request->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * Check if user is in the business
 * @param Number $user_id the id of the user
 * @param Number $business_id the id of the business
 * @return Boolean true if he is in the business, false otherwise
 */
function check_business_user($user_id,$business_id){
    global $db;
    $query="SELECT 
                *
            FROM
                `business_user`
            WHERE
                `user_id` = :user_id
                AND `business_id` = :business_id;";
    $request = $db->prepare($query);
    $request->execute([
        ":user_id"=>$user_id,
        ":business_id"=>$business_id
    ]);
    $user = $request->fetchAll(PDO::FETCH_ASSOC);
    return !empty($user);
}