<?php


// Définir une variable pour stocker les erreurs
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier et récupérer les mots de passe
    if (
        isset($_POST['new_password'], $_POST['confirm_password'], $_POST['new_code'], $_POST['confirm_code'], $_POST["terms_and_conditions"])
    ) {
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        $new_code = trim($_POST['new_code']);
        $confirm_code = trim($_POST['confirm_code']);

        $errors = [];

        if ($new_password === '' || $confirm_password === '') {
            $errors[] = "Les champs de mot de passe ne doivent pas être vides.";
            $_SESSION['toast'] = [
                'message' => "Les champs de mot de passe ne doivent pas être vides.",
                'type' => 'error'
            ];
        } elseif ($new_password !== $confirm_password) {
            $errors[] = "Les mots de passe ne correspondent pas.";
            $_SESSION['toast'] = [
                'message' => "Les mots de passe ne correspondent pas.",
                'type' => 'error'
            ];
        }

        if ($new_code === '' || $confirm_code === '') {
            $errors[] = "Les champs de code ne doivent pas être vides.";
            $_SESSION['toast'] = [
                'message' => "Les champs de code ne doivent pas être vides.",
                'type' => 'error'
            ];
        } elseif ($new_code !== $confirm_code) {
            $errors[] = "Les code ne correspondent pas.";
            $_SESSION['toast'] = [
                'message' => "Les code ne correspondent pas.",
                'type' => 'error'
            ];
        }

        // Si aucune erreur, on enregistre
        if (empty($errors)) {
            $_SESSION['new_password'] = $new_password;
            $_SESSION['new_code'] = $new_code;
        }
    } else {
        $errors[] = "Veuillez remplir les champs de mot de passe.";
        $_SESSION['toast'] = [
            'message' => "Veuillez remplir les champs de mot de passe.",
            'type' => 'error'
        ];
    }


    // Vérifier et récupérer les informations personnelles
    if (isset($_POST['first_name'], $_POST['last_name'], $_POST['birth_date'], $_POST['gender'])) {
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $birth_date = trim($_POST['birth_date']);
        $gender = trim($_POST['gender']);

        if ($first_name === '' || $last_name === '') {
            $errors[] = "Le prénom et le nom sont obligatoires.";
            $_SESSION['toast'] = [
                'message' => "Le prénom et le nom sont obligatoires.",
                'type' => 'error'
            ];
        }

        if (!empty($birth_date)) {
            $date_parts = explode('-', $birth_date);
            if (count($date_parts) === 3 && checkdate((int) $date_parts[1], (int) $date_parts[2], (int) $date_parts[0])) {
                $today = new DateTime();
                $birth = new DateTime($birth_date);
                $age = $today->diff($birth)->y;

                if ($age < 18) {
                    $errors[] = "Vous devez avoir au moins 18 ans.";
                    $_SESSION['toast'] = [
                        'message' => "Vous devez avoir au moins 18 ans.",
                        'type' => 'error'
                    ];
                }
            } else {
                $errors[] = "La date de naissance n'est pas valide.";
                $_SESSION['toast'] = [
                    'message' => "La date de naissance n'est pas valide.",
                    'type' => 'error'
                ];
            }
        } else {
            $errors[] = "Veuillez renseigner votre date de naissance.";
            $_SESSION['toast'] = [
                'message' => "Veuillez renseigner votre date de naissance.",
                'type' => 'error'
            ];
        }

        if (empty($gender)) {
            $errors[] = "Veuillez sélectionner un genre.";
            $_SESSION['toast'] = [
                'message' => "Veuillez sélectionner un genre.",
                'type' => 'error'
            ];
        }
        if (empty($errors)) {
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['birth_date'] = $birth_date;// format YYYY-MM-DD
            $_SESSION['gender'] = $gender;
        }
    } else {
        $errors[] = "Veuillez remplir tous les champs requis.";
        $_SESSION['toast'] = [
            'message' => "Veuillez remplir tous les champs requis.",
            'type' => 'error'
        ];
    }

    // Si aucune erreur, rediriger vers une autre page
    if (empty($errors)) {
        header('Location:/insertion_of_customer_file');
        exit;
    }
}


display("creation_of_customer_file", "Inscription");
