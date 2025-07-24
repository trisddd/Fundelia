<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>ERROR</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php if(empty($_GET)){ ?>
  <h1>Oups !</h1>
  <p>Une erreur s'est produite. Si l'erreur persiste, contactez le service client ou réessayez. Merci.</p>
  <a class="button" href="./index.php">Retour à l'accueil</a>
  <?php } 
  
  elseif(!empty($_GET)) {?>

<h1><?php echo $_GET['error'] ?></h1>
<p>Votre commande n'a pas été prise en compte, et vous n'avez pas été débité.</p>
<a class="button" href="index.php">Retour à l'accueil</a>
  <?php 
  }?>

</body>
</html>
