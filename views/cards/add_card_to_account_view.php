<div class="form-container">
    <form action="/add_cards_to_account" method="POST" class="card-creation-form">
        <h2>Créer une Nouvelle Carte</h2>

        <div class="form-group">
            <label for="card_name">Nom de la carte :</label>
            <input type="text" id="card_name" name="card_name" required>
        </div>

        <div class="form-group">
            <label for="card_holder">Nom du titulaire :</label>
            <input type="text" id="card_holder" name="card_holder" required>
        </div>

        <div class="form-group">
            <label for="card_type">Type de carte :</label>
            <select id="card_type" name="card_type" required>
                <option value="normal">Carte normale</option>
                <option value="short-lived">Carte éphémère</option>
            </select>
        </div>

        <div class="form-group">
            <label for="creation_reason">Motif de création :</label>
            <input type="text" id="creation_reason" name="creation_reason" required>
        </div>

        <div class="form-group">
            <label for="account_id">Choisir un compte :</label>
            <select id="account_id" name="account_id" required>
                <?php
                foreach ($GLOBALS["accounts"] as $account) { ?>
                    <option value="<?= htmlspecialchars($account['account_id'], ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($account['account_name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <button type="submit" name="submit" class="submit-button">Créer la carte</button>
    </form>
</div>
