<?php

require_once "db_connect.php";


// CREATE :
function create_business_adding_ticket($user_id, $business_id)
{
    global $db;
    $query = "INSERT INTO `tickets`(`ticket_type`,`ticket_object`,`user_id`,`importance_level`, `ticket_state`) VALUES 
                (:ticket_type,:ticket_object,:user_id,:importance_level,:ticket_state);";
    $request = $db->prepare($query);
    $request->execute(array(
        ":ticket_type" => 'add_business_request',
        ":ticket_object" => "Création de l'entreprise avec l'id " . $business_id,
        ":user_id" => $user_id,
        ":importance_level" => 2,
        ":ticket_state" => 'new'
    ));
}
