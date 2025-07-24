<article class="wrapper">
  <h2>Compte et livrets</h2>

  <span class="badge">Informations du compte</span>

  <section class="field-group">
    <div class="label">Nom</div>
    <div class="value">
      <?= htmlspecialchars($account['name']) ?>
    </div>
  </section>

  <section class="field-group">
    <div class="label">Type de compte</div>
    <div class="value">
      <?= htmlspecialchars($account['account_type']) ?>
    </div>
  </section>

  <section class="field-group">
    <div class="label">Solde</div>
    <div class="value">
      <?= htmlspecialchars($account['balance']) ?>
      <?php include 'views/includes/_money.php'; ?>
    </div>
  </section>

  <section class="field-group">
    <div class="label">Bénéficiaire(s)</div>
    <div class="value">
      <?= htmlspecialchars($account['first_name'] . " " . $account['last_name']) ?>
    </div>
  </section>

  <section class="field-group">
    <div class="label">IBAN</div>
    <div class="value">
      <?= htmlspecialchars($account['IBAN']) ?>
    </div>
  </section>

  <form method="POST" action="/delete_accounts_and_passbooks">
    <input type="hidden" name="account_id" value="<?= htmlspecialchars($account['id']) ?>">
    <button class="btn btn-delete">Supprimer le compte</button>
  </form>
</article>