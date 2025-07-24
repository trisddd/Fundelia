<?php

/**
 * verifie si liban est valide dans le sens ou la suite de chiffre est correcte .
 *
 * @param string $iban Liban numérique à traiter.
 * @return bool .
 */
function IBAN_verification($iban) {
    // Enlever les espaces et mettre l'IBAN en majuscule
    $iban = strtoupper(str_replace(' ', '', $iban));

    // Vérification du pays (seulement FR accepté)
    if (substr($iban, 0, 2) !== 'FR') {
        throw new Exception('Erreur : Seuls les IBAN français (commençant par FR) sont acceptés.');
    }

    // Vérification de la longueur de l'IBAN
    if (strlen($iban) < 15 || strlen($iban) > 34) {
        throw new Exception('Erreur : La longueur de l\'IBAN doit être comprise entre 15 et 34 caractères.');
    }

    // Déplacer les 4 premiers caractères à la fin (code pays + clé)
    $iban = substr($iban, 4) . substr($iban, 0, 4);

    // Remplacer les lettres par des chiffres
    $iban_numeric = '';
    foreach (str_split($iban) as $char) {
        if (ctype_alpha($char)) {
            $iban_numeric .= ord($char) - 55;
        } elseif (ctype_digit($char)) {
            $iban_numeric .= $char;
        } else {
            throw new Exception('Erreur : L\'IBAN contient des caractères invalides.');
        }
    }

    // Appliquer le Modulo 97
    $modulo = intval(substr($iban_numeric, 0, 7)) % 97;
    $iban_numeric = substr($iban_numeric, 7);

    // Pour les grandes chaînes, traiter par parties
    while (strlen($iban_numeric) > 0) {
        $modulo = intval($modulo . substr($iban_numeric, 0, 7)) % 97;
        $iban_numeric = substr($iban_numeric, 7);
    }

    // Vérifier que le modulo final soit égal à 1
    if ($modulo != 1) {
        throw new Exception('Erreur : IBAN invalide après vérification du Modulo 97.');
    }

    // Si tout est OK
    return true;
} 


/**
 * Génère un hash unique de 11 chiffres à partir d'une entrée donnée.
 *
 * @param string $input La valeur d'entrée (par exemple, un identifiant de compte).
 * @return string Une chaîne de 11 chiffres représentant le hash unique.
 */

function hash_11_Unique($input) {
    $hash = hash('sha256', $input); // Utilisation de SHA-256 pour générer un hash
    $num = hexdec(substr($hash, 0, 8)); // Prend une partie du hash et le convertit en nombre
    return str_pad($num % 100000000000, 11, '0', STR_PAD_LEFT); // Réduit à 11 chiffres
}

/**
 * Calcule le modulo 97 d'une chaîne numérique.
 *
 * @param string $s La chaîne numérique à traiter.
 * @return int Le reste de la division modulo 97.
 */

// Fonction mod97 définie une seule fois
function mod97($s) {
    $checksum = '';
    foreach (str_split($s, 7) as $chunk) {
        $checksum = (int)($checksum . $chunk) % 97;
    }
    return $checksum;
}


/**
 * Génère un IBAN pour Fundelia à partir d'un numéro de compte genere avec le hash a 11 chiffres.
 *
 * @param int $account_number Le numéro de compte 
 * @return string L'IBAN généré au format FR suivi des informations bancaires.
 */

function generate_IBAN_Fundelia($account_number) {
    $bank_code = "75076";
    $box_code = "00001";
    $account_number = str_pad($account_number, 11, "0", STR_PAD_LEFT);

    // Étape 1 : Calcul clé RIB
    $rib_num = $bank_code . $box_code . $account_number;
    $rib_key = 97 - (intval($rib_num) % 97);
    
    $rib_key = str_pad($rib_key, 2, "0", STR_PAD_LEFT);

    // Étape 2 : Construction BBAN
    $bban = $bank_code . $box_code . $account_number . $rib_key;
    // echo "\n";

    // Étape 3 : Calcul clé IBAN
    $iban_temp = $bban . "FR00";

    $iban_numeric = "";

    foreach (str_split($iban_temp) as $char) {
        if (ctype_alpha($char)) {
            $iban_numeric .= (ord(strtoupper($char)) - 55); // A=10, B=11, ..., Z=35
            // echo "dans la partie if qui transforme les lettres en chiffres ".$iban_numeric."\n";
            // echo "\n";
        } else {
            $iban_numeric .= $char;
            // echo "dans la partie else qui transfomre les lettres en chiffres ".$iban_numeric."\n";
            // echo "\n";
        }
    }

    // Calcul du modulo pour obtenir la clé IBAN
    $modulo = mod97($iban_numeric);
    $iban_key = str_pad((98 - $modulo), 2, "0", STR_PAD_LEFT);

    return "FR".$iban_key.$bban;
}
