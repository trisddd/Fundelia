<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/styles/main_style.css">
    <link rel="stylesheet" href="/public/styles/<?= $view_name ?>_style.css">
    <link rel="stylesheet" href="/public/styles/iframe_style.css">
</head>

<body>
    <main><?php require_once VIEWS_PATHS[$view_name]; ?></main>
    <?php include "views/includes/_toast.php" ?>
    <script src="/public/javascript/main.js"></script>
    <script src="/public/javascript/<?= $view_name ?>.js"></script>
</body>

</html>