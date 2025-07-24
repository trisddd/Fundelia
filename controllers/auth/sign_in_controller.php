<?php

require_once "models/users_sql_requests.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Nettoyage et validation des champs
    $email = filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? "";  // Utilisation de l'opérateur null coalescent pour éviter les erreurs

    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password)) {
        // Préparation de la requête pour récupérer l'utilisateur et certaines infos ?
        // inclurer la fonction qui se trouve dans accounts request sql qui la fonction : read_user_by_email

        $user = read_user_by_email($email);

        // var_dump($user) ;

        if ($user && password_verify($password, $user['password'])) {
            // Création des variables de session
            $_SESSION['user_first_name'] = $user['first_name'];
            $_SESSION['user_last_name'] = $user['last_name'];
            $_SESSION['user_mail'] = $user['email'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['session'] =$user ;
            $_SESSION['registered'] = true;
            // Redirection vers le dashboard
            header("Location: /dashboard");
            exit();  // Toujours appeler exit après header() pour s'assurer que le script s'arrête
        } else {
        $_SESSION['toast'] = [
            'message' => "Mot de passe ou non dutilisateur incorrecte",
            'type' => 'error'
        ];            
        $error_message = "Identifiants incorrects. Veuillez vérifier votre email et mot de passe.";
        }
    } else {

        $error_message = "⚠️ Veuillez remplir tous les champs avec des informations valides.";
    }
}

display("sign_in","Se connecter");