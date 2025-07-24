<h1>Vos comptes entreprise</h1>
<div class="accounts">
    <?php foreach ($accounts as $account): ?>
        <?php if (!empty($account["API_key"])): ?>
            <div class="card">
                Nom du compte : <?= htmlspecialchars($account["account_name"]) ?><br>
                IBAN : <?= htmlspecialchars($account["IBAN"]) ?><br>
                Clé API (publique) :
                <span class="api-key"><?= htmlspecialchars(substr($account["API_key"], 0, 20)) ?>...</span>
                <button type="button" class="copy-btn"
                    data-api-key="<?= htmlspecialchars($account["API_key"]) ?>">Copier</button>
                <a href="/update_API_key/<?= urlencode($account["IBAN"]) ?>">
                    <button type="button">Créer un nouveau lot de clés</button>
                </a>
                <a href="/business_account_details/<?= urlencode($business_name) ?>?IBAN=<?= urlencode($account["IBAN"]) ?>">
                    <button type="button">Accéder aux détails du compte</button>
                </a>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<span class="reminder">
    Pour rappel, pour chacun de vos comptes d'entreprise, vous devez avoir conservé la clé API publique ainsi que - et
    surtout - la clé API privée.
    En cas de problème ou de doute avec l'une de vos clés privées, recréez-la et conservez-la précieusement.
</span>
<a class="create-new-link" href="/create_business_account">
    <button class="create-new-button" type="button">Créer un compte professionnel / entreprise</button>
</a>

<script src="/public/javascript/copy.js"></script>
