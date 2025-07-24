<?php

require_once "services/cards_management.php";
require_once "services/ibans_management.php";
require_once "models/users_sql_requests.php";
try {

    // Récupération des variables de session
    $email = $_SESSION['email'];
    $pass= $_SESSION['new_password'];

    $first_name=$_SESSION['first_name'];
    $last_name= $_SESSION['last_name'];
    $birth_date=$_SESSION['birth_date'];
    $gender=$_SESSION['gender'];
    $code = $_SESSION['new_code'] ;
    insert_user_on_first_inscription($pass, $first_name, $last_name, $email, $birth_date, $gender , $code);
    // Si tout est réussi, rediriger vers une page de confirmation
    session_destroy();
    header("Location:/sign_in");

    exit();
} catch (PDOException $e) {
    // Si une erreur se produit, afficher un message d'erreur
    $error_message = "Erreur lors de l'insertion des données : " . $e->getMessage();
}


display("insertion_of_customer_file","Inscription");
