<?php

require_once 'models/users_sql_requests.php';

// Vous devez toujours utiliser ça dans vos fonctions pour pousser une nouvelle notif : create_notification($user_id, $title, $message);

function notification_on_first_inscription($user_id)
{
    $title = "Bienvenue chez Fundelia !";
    $message = "Merci de nous rejoindre, nous sommes ravis de vous compter parmi nos utilisateurs.";
    create_notification($user_id, $title, $message);
}

function notification_of_big_transfer_receive($user_id, $amount)
{
    $title = "INFORMATION SUITE À UNE TRANSACTION IMPORTANTE";
    $message = "Vous avez récemment reçu une transaction importante de $amount";
    create_notification($user_id, $title, $message);
}

function notification_of_big_transfer_debit($user_id, $amount)
{
    $title = "INFORMATION SUITE À UNE TRANSACTION IMPORTANTE";
    $message = "Vous avez récemment été débité pour une transaction importante de $amount";
    create_notification($user_id, $title, $message);
}

function notification_of_beneficiary_adding($user_id, $first_name, $last_name)
{
    $title = "INFORMATION SUITE À L'AJOUT D'UN BÉNÉFICIAIRE";
    $message = "Vous avez récemment ajouté $first_name, $last_name comme bénéficiaire";
    create_notification($user_id, $title, $message);
}

function notification_of_account_adding($user_id)
{
    $title = "INFORMATION SUITE À L'AJOUT D'UN COMPTE";
    $message = "Vous avez récemment ajouté un nouveau compte";
    create_notification($user_id, $title, $message);
}

function notification_of_card_adding($user_id)
{
    $title = "INFORMATION SUITE À L'AJOUT D'UNE CARTE";
    $message = "Vous avez récemment ajouté une nouvelle carte";
    create_notification($user_id, $title, $message);
}

function notification_of_card_deleting($user_id)
{
    $title = "INFORMATION SUITE À LA SUPPRESSION D'UNE CARTE";
    $message = "Vous avez récemment supprimé une carte";
    create_notification($user_id, $title, $message);
}

function notification_of_short_lived_card_deleting($user_id, $card_name)
{
    $title = "INFORMATION SUITE À LA SUPPRESSION D'UNE CARTE ÉPHÉMÈRE";
    $message = "Votre carte éphémère nommée $card_name a été supprimée suite à un achat avec cette dernière";
    create_notification($user_id, $title, $message);
}
