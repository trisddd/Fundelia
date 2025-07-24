<?php

// Récupérer les données depuis la session
$token = $_GET['token'] ?? '';
$card_number = $_GET['card_number'] ?? '';
$business_iban = $_GET['business_iban'] ?? '';
$card_exp = $_GET['card_exp'] ?? '';
$card_cvc = $_GET['card_cvc'] ?? '';
$holder_name = $_GET['holder_name'] ?? '';
$order_amount = $_GET['order_amount'] ?? '';
$order_id = $_GET['order_id'] ?? '';

// Vérifier que toutes les infos sont présentes
if (
  $token &&
  $card_number &&
  $business_iban &&
  $card_exp &&
  $card_cvc &&
  $holder_name &&
  $order_amount &&
  $order_id

) {
  echo " Tout est OK : commande confirmée pour l'article <strong>$order_id</strong> (montant : <strong>$order_amount <?php include 'views/includes/_money.php'; ?></strong>) , Mr/Mdm  <strong>$holder_name <?php include 'views/includes/_money.php'; ?></strong> ";
} else {
  http_response_code(400);
  $error = " Infos manquantes pour valider la commande. ou solde insuffisant sur le compte ";



}
?>

<!DOCTYPE html>
<hp lang="fr">

  <head>
    <meta charset="UTF-8">
    <title>Confirmation</title>
    <link rel="stylesheet" href="style.css">
  </head>

  <body>
    <?php if ($_GET['order_status'] != 0) { ?>
      <h1>Merci pour votre achat !</h1>
      <p>Votre commande a bien été prise en compte.</p>
      <a class="button" href="./index.php">Retour à l'accueil</a>
    <?php } else { ?>

      <h1>Une erreur s'est produite lors de la transction !</h1>
      <p>Votre commande n'a pas été prise en compte , et vous n'avez pas ete debité.</p>
      <p><?php echo $error ?></p>
      <a class="button" href="index.php">Retour à l'accueil</a>
    <?php
    } ?>

  </body>

  </html>