<?php

/**
 * Summary of card_verification
 * @param String $card_number
 * @return bool true to say it is tru and false if it is false 
 */
function card_verification($card_number) {
    // Vérifie que c'est bien une chaîne de 16 chiffres
    if (strlen($card_number) !== 16) {
        throw new Exception('Erreur : Le numéro de carte doit contenir exactement 16 chiffres.');
    }

    if (!ctype_digit($card_number)) {
        throw new Exception('Erreur : Le numéro de carte doit être uniquement composé de chiffres.');
    }

    $somme = 0;
    $longueur = strlen($card_number);

    // Parcours de droite à gauche
    for ($i = 0; $i < $longueur; $i++) {
        $chiffre = intval($card_number[$longueur - 1 - $i]);
        if ($i % 2 == 1) { // Un chiffre sur deux en partant de la droite
            $chiffre *= 2;
            if ($chiffre > 9) {
                $chiffre -= 9;
            }
        }
        $somme += $chiffre;
    }

    // Si la somme n'est pas multiple de 10 => numéro invalide
    if ($somme % 10 !== 0) {
        throw new Exception('Erreur : Numéro de carte invalide selon l\'algorithme de Luhn.');
    }

    // Si tout est OK
    return true;
}


/**
 * Summary of hash9_From_Input
 * @param String||Number $input il sagira par exemple d lid du compte 
 * @return number a 9 chiffres qui sera le numero de carte 
 * 
 * Summary of generate_Card_Number
 *  @param number $account_number il sagira du numero de compte genere par hash9_From_Input
 *  @return number a 9 chiffres qui sera le numero complet de de carte 
 * 
 */

function hash9_From_Input($input) {
    $hash = md5($input); // Utilise md5 pour créer un hash de la chaîne ou du nombre
    $num = hexdec(substr($hash, 0, 6)); // Prend les 6 premiers caractères du hash et les convertit en décimal
    return str_pad($num % 1000000000, 9, '0', STR_PAD_LEFT); // Réduit à 9 chiffres
}


function generate_Card_Number($account_number)
{
    $identifiantBanque=435912;

    // Vérification de l'identifiant banque (6 chiffres)
    if (strlen($identifiantBanque) !== 6 || !is_numeric($identifiantBanque)) {
        return "Erreur : L'identifiant de la banque doit contenir exactement 6 chiffres.";
    }

    // Compléter le numéro de compte avec des zéros à gauche si nécessaire
    $account_number = str_pad($account_number, 9, '0', STR_PAD_LEFT);

    if (strlen($account_number) !== 9 || !is_numeric($account_number)) {
        return "Erreur : Le numéro de compte doit contenir uniquement des chiffres.";
    }


    // Concaténation des 15 premiers chiffres
    $baseCarte = $identifiantBanque . $account_number;


    // Calcul de la clé de contrôle (Luhn)
    $somme = 0;
    $longueur = strlen($baseCarte);
    for ($i = 0; $i < $longueur; $i++) {
        $chiffre = intval($baseCarte[$longueur - 1 - $i]); // On part de la droite
        if ($i % 2 == 0) {
            $chiffre *= 2;
            if ($chiffre > 9) {
                $chiffre -= 9;
            }
        }
        $somme += $chiffre;
    }

    $cle = (10 - ($somme % 10)) % 10;

    // Retour du numéro complet
    return $baseCarte . $cle;
}

// Exemple d'utilisation
?>