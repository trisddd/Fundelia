<?php
require_once './businesshadsahke_v2.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {
    launch_business_transaction($_GET); 
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Paiement</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <h1>Resume</h1>
  <form action="" method="get" class="form">

    <p>Montant de la commande : 500t</p>
    <input type="hidden" name="order_amount" value=500>
    <input type="hidden" name="order_id" value="VMC325">

    <button type="submit">Valider le paiement</button>
  </form>
</body>
</html>
