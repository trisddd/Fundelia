<div class="form-container">
    <div class="beneficiaire">
        <span>
            bénéficiaire : <?= htmlspecialchars($beneficiary_name, ENT_QUOTES, 'UTF-8') ?> <br>
        </span>
        <span>
            IBAN : <?= htmlspecialchars($_GET['to_iban'] ?? '', ENT_QUOTES, 'UTF-8') ?>
        </span>
    </div>

    <form action="/make_transfer" method="POST">
        <div class="beneficiaire">
            <label for="account">Depuis le compte</label>
            <span><?= htmlspecialchars($account_info['name'], ENT_QUOTES, 'UTF-8') ?></span>
            <input type="hidden" name="emitter_iban"
                value="<?= htmlspecialchars($_SESSION['emitter_account_iban'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                id="emitter_iban">
        </div>

        <div class="field">
            <label for="montant">Montant</label>
            <input type="number" name="amount" id="montant" placeholder="Entrez le montant" min="0.01" step="0.01"
                required>
        </div>

        <input type="hidden" name="beneficiary_iban"
            value="<?= htmlspecialchars($_GET['to_iban'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <div class="field">
            <label for="libelle">Libellé</label>
            <input type="text" name="wording" id="libelle" placeholder="Entrez un libellé" required>
        </div>


        <button type="submit" class="button">Valider</button>
    </form>

    <div id="codeModal" class="modal" style="display:none;">
        <div class="modal-content">
            <h3>Vérification</h3>
            <p>Veuillez entrer votre code secret :</p>
            <input type="password" id="codeInput" placeholder="Code secret">
            <div class="modal-actions">
                <button onclick="verifierCode()">Confirmer</button>
                <button onclick="closeModal()">Annuler</button>
            </div>
            <p id="codeError" style="color: red; display: none;">Code incorrect</p>
        </div>
    </div>

</div>


<script>
    const form = document.querySelector("form");
    const modal = document.getElementById("codeModal");
    const codeInput = document.getElementById("codeInput");
    const codeError = document.getElementById("codeError");

    form.addEventListener("submit", function (e) {
        e.preventDefault(); 
        modal.style.display = "block";
    });

    function verifierCode() {
        const code = codeInput.value;


        fetch('/verify_code', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            credentials: 'same-origin',
            body: 'code=' + encodeURIComponent(code)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modal.style.display = "none";
                    form.submit(); 
                } else {
                    codeError.style.display = "block";
                }
            })
            .catch(err => {
                console.error('Erreur lors de la vérification :', err);
            });
    }

    function closeModal() {
        modal.style.display = "none";
        codeInput.value = "";
        codeError.style.display = "none";
    }
    codeInput.addEventListener("keydown", function (e) {
    if (e.key === "Enter") {
        e.preventDefault(); // Empêche un submit par défaut
        verifierCode();     // Lance la vérification
    }
});

</script>