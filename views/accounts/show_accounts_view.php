<div class="container">
  <div class="top-bar">
    <h1>Comptes et Livrets</h1>
    <a href="/add_accounts_and_passbooks">
      <div class="top-button" title="Ajouter un compte">
        <span>+</span>
      </div>
    </a>
  </div>
  <article class="total">
    <section class="accounts-container">
      <p>Total de mes comptes personnels</p>
      <div class="amount">
        <?= htmlspecialchars($total_amount) ?>
        <span class="current-icon"><?php include 'views/includes/_money.php'; ?></span>
      </div>
      <section>
        <h2 class="section-title">Mes comptes courants</h2>
        <?php foreach ($accounts as $account) { ?>
          <?php if ($account['account_type'] === "Compte Courant" && $account['business_id'] == null) { ?>
            <a href="/show_account_details?id=<?= urlencode($account['account_id']) ?>">
              <div class="item-card">
                <span class="account-name"><?= htmlspecialchars($account["account_name"]) ?></span>
                <span class="value"><?= htmlspecialchars($account["balance"]) ?>
                  <span class="current-icon"><?php include 'views/includes/_money.php'; ?></span>
                </span>
              </div>
            </a>
          <?php } ?>
        <?php } ?>
      </section>

      <section class="section">
        <h2 class="section-title">Mon épargne</h2>
        <?php foreach ($accounts as $account) { ?>
          <?php if (
            $account['account_type'] === "Livret A" ||
            $account['account_type'] === "LDDS" ||
            $account['account_type'] === "PER" ||
            $account['account_type'] === "Assurance Vie"
          ) { ?>
            <a href="/show_account_details?id=<?= urlencode($account['account_id']) ?>">
              <section class="item-card">
                <span class="account-name"><?= htmlspecialchars($account["account_name"]) ?></span>
                <span class="value"><?= htmlspecialchars($account["balance"]) ?>
                  <span class="current-icon"><?php include 'views/includes/_money.php'; ?></span>
                </span>
              </section>
            </a>
          <?php } ?>
        <?php } ?>
      </section>
    </section>

    <section class="accounts-container">
      <p>Total de mes comptes professionnels</p>
      <div class="amount">
        <?= htmlspecialchars($total_amount_pro) ?>
        <span class="current-icon"><?php include 'views/includes/_money.php'; ?></span>
      </div>
      <section class="section">
        <h2 class="section-title">Mes comptes entreprise</h2>
        <?php foreach ($accounts as $account) { ?>
          <?php if ($account['account_type'] === "Compte Courant" && $account['business_id'] != null) { ?>
            <a href="/show_account_details?id=<?= urlencode($account['account_id']) ?>">
              <section class="item-card">
                <span class="account-name"><?= htmlspecialchars($account["account_name"]) ?></span>
                <span class="value"><?= htmlspecialchars($account["balance"]) ?>
                  <span class="current-icon"><?php include 'views/includes/_money.php'; ?></span>
                </span>
              </section>
            </a>
          <?php } ?>
        <?php } ?>
      </section>
    </section>
  </article>
</div>