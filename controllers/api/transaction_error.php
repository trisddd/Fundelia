<?php
$mess_error = $_GET['error'] ?? 'Une erreur inconnue s’est produite.';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Erreur - Fundelia</title>
  <style>
    :root {
      --fundelia-blue: #0d47a1;
      --fundelia-red: #c62828;
      --bg-light: #f4f8fb;
    }

    body {
      margin: 0;
      padding: 2rem;
      font-family: "Segoe UI", sans-serif;
      background-color: var(--bg-light);
      color: #333;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      text-align: center;
    }

    h1 {
      color: var(--fundelia-red);
      font-size: 2.2rem;
      margin-bottom: 0.5rem;
    }

    p {
      font-size: 1.1rem;
      max-width: 600px;
      margin: 1rem 0;
      line-height: 1.6;
    }

    .error-details {
      background: #ffebee;
      color: var(--fundelia-red);
      padding: 0.75rem 1rem;
      border-radius: 8px;
      font-size: 1rem;
      margin: 1.5rem 0;
      word-break: break-word;
    }

    a {
      display: inline-block;
      margin: 0.5rem 1rem;
      padding: 0.7rem 1.5rem;
      background-color: var(--fundelia-blue);
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 500;
      transition: background 0.3s ease;
    }

    a:hover {
      background-color: #1565c0;
    }
  </style>
</head>
<body>

  <h1>Oups, une erreur est survenue !</h1>
  <p>Les informations que vous avez fournies semblent incorrectes.</p>

  <div class="error-details"><?= htmlspecialchars($mess_error) ?></div>

  <p>Vous pouvez :</p>
  <a href="/">Retourner à l'accueil de Fundelia</a>
  <a href="/sign_in">Connectez vous pour demander de l'aide au service client</a>

</body>
</html>
