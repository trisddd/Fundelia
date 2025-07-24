<div class="card">
    Nom du compte : <?= htmlspecialchars($GLOBALS["account"]["name"]) ?> <br>
    IBAN : <?= htmlspecialchars($GLOBALS["account"]["IBAN"]) ?><br>

    Clé API (publique) : <br>
    <span class="key-preview"><?= htmlspecialchars(substr($GLOBALS["account"]["API_key"], 0, 20)) ?>...</span>
    <button class="copy-btn" data-api-key="<?= htmlspecialchars($GLOBALS["account"]["API_key"]) ?>">Copier</button><br>

    Clé privée : <br>
    <span class="key-preview"><?= htmlspecialchars(substr($GLOBALS["account"]["private_key"], 0, 20)) ?>...</span>
    <button class="copy-btn" data-api-key="<?= htmlspecialchars($GLOBALS["account"]["private_key"]) ?>">Copier</button><br>
    
    Faites bien attention : une fois cachée, la clé privée ne sera plus visible.

    <a href="/business_accounts/<?= urlencode($business_name) ?>" class="getBackButton">
        <button type="button">J'ai bien récupéré mes clés</button>
    </a><br>

</div>

<script src="/public/javascript/copy.js"></script>
