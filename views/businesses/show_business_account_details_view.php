<article class="wrapper">
  <h2>Compte</h2>

  <span class="badge">Informations du compte</span>

  <section class="field-group">
    <div class="label">Nom</div>
    <div class="value">
      <?= htmlspecialchars($account['account_name']) ?>
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
    <div class="label">Bénéficiaire</div>
    <div class="value">
      <span><?= htmlspecialchars($owner["first_name"] . " " . $owner["last_name"]) ?></span>
      <span id="link" class="link">
        Modifier
      </span>
    </div>
  </section>

  <section class="field-group">
    <div class="label">IBAN</div>
    <div class="value">
      <?= htmlspecialchars($account['IBAN']) ?>
    </div>
  </section>

  <form method="POST" action="/delete_business_account/<?= urlencode($business_name) ?>">
    <input type="hidden" name="iban" value="<?= htmlspecialchars($account['IBAN']) ?>">
    <button class="btn btn-delete">Supprimer le compte</button>
  </form>
</article>



<div id="transferModal" class="modal-overlay disabled">
  <div class="modal-content">
    <div class="modal-header">
      <h3>Modifier le bénéficiaire</h3>
      <button id="closeTransferModal" class="close-button">
        &times;
      </button>
    </div>
    <div class="modal-body">
      <div class="employee-group">
        <div class="employee-list">
          <?php if (!empty($employees)): ?>
            <h4>Managers</h4>
            <?php foreach ($employees["manager"] as $manager): 
              $index = array_search($manager, $_SESSION["employees"], true);?>
              <a href="/update_business_account_owner/<?= urlencode($business_name) ?>?IBAN=<?= urlencode($account["IBAN"]) ?>&employee=<?= $index ?>" class="block no-underline">
                <div class="employee-item hover:bg-gray-100 transition-colors cursor-pointer">
                  <span><?= htmlspecialchars($manager['first_name']." ".$manager["last_name"], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
              </a>
            <?php endforeach; ?>            
            <h4>Employé(e)s</h4>
            <?php foreach ($employees["employee"] as $employee): 
              $index = array_search($employee, $_SESSION["employees"], true);?>
              <a href="/update_business_account_owner/<?= urlencode($business_name) ?>?IBAN=<?= urlencode($account["IBAN"]) ?>&employee=<?= $index ?>" class="block no-underline">
                <div class="employee-item hover:bg-gray-100 transition-colors cursor-pointer">
                  <span><?= htmlspecialchars($employee['first_name']." ".$employee["last_name"], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
              </a>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>