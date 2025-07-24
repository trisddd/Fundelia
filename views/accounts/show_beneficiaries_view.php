<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="container">
    <header>
        <h1>Répertoire des bénéficiaires</h1>
        <div class="icon-circle">
            <a href="/add_beneficiary"><i class="material-icons">person_add</i></a>
        </div>
    </header>

    <div class="section-title">Mes comptes</div>

    <?php foreach ($accounts as $account): ?>
        <div class="list-item">
            <a href="/show_transfer_form?to_iban=<?= urlencode($account['IBAN']) ?>&name=<?= urlencode($account['name']) ?>">
                <span><?= htmlspecialchars($account['name']) ?></span>
                <span class="amount"><?= number_format($account['balance'], 2, ',', ' ') ?><?php include 'views/includes/_money.php'; ?></span>
            </a>
        </div>
    <?php endforeach; ?>

    <div class="section-title">Mes bénéficiaires</div>

    <?php foreach ($beneficiaries as $b): ?>
        <div class="list-item">
            <a href="/show_transfer_form?to_iban=<?= urlencode($b['IBAN']) ?>&first_name=<?= urlencode($b['first_name']) ?>&last_name=<?= urlencode($b['last_name']) ?>">
                <span><?= htmlspecialchars($b['first_name'] . ' ' . $b['last_name']) ?></span>
                <span class="iban"><?= htmlspecialchars($b['IBAN']) ?></span>
            </a>
        </div>
    <?php endforeach; ?>
</div>