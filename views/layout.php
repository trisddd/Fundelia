<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="manifest" href="/public/json/manifest.json">
    <link rel="icon" type="image/png" sizes="16x16" href="/public/icons/logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/public/icons/logo.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/public/icons/logo.png">
    <meta name="theme-color" content="#186ED1">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php if($view_name == "show_transaction_history"): ?>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="10">
    <?php endif; ?>

    <link rel="stylesheet" href="/public/styles/main_style.css">
    <link rel="stylesheet" href="/public/styles/_nav_style.css">
    <link rel="stylesheet" href="/public/styles/_header_style.css">
    <link rel="stylesheet" href="/public/styles/_footer_style.css">
    <link rel="stylesheet" href="/public/styles/<?= $view_name ?>_style.css">
    <title>Fundelia - <?= $title ?></title>
</head>
<body>
    <?php if($view_name=="homepage"){include "views/includes/_header.php";} ?>
    <?php if (!in_array($view_name, NO_NAV_PAGES)) {include "views/includes/_nav.php";} ?>
    <main><?php require_once VIEWS_PATHS[$view_name]; ?></main>
    <?php if($view_name=="homepage"){include "views/includes/_footer.php";} ?>
    <?php include "views/includes/_toast.php" ?>
    <script src="/public/javascript/main.js"></script>
    <script src="/public/javascript/<?= $view_name ?>.js"></script>
</body>

</html>