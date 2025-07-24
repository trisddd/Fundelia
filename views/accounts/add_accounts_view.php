<section>
    <h2>Créer un nouveau compte</h2>
    <form method="POST" action="/add_accounts_and_passbooks" class="add-form">
      <label for="account_type">Type de compte :</label>
      <select name="account_type" id="account_type" required>
        <option value="">-- Sélectionner --</option>
        <?php foreach ($account_types as $account_type): ?>
          <option value="<?= $account_type['id']?>"><?= htmlspecialchars($account_type['name']) ?></option>
        <?php endforeach; ?>
      </select>

      <label for="account_name">Nom du compte :</label>
      <input type="text" name="account_name" id="account_name" required>

      <button type="submit">Créer le compte</button>
    </form>
</section>