<form action="/show_cards_according_to_account" method="POST">
    <label for="account_ID">Sélectionnez un compte :</label>
    <input type="hidden" name="account" value="1">
    <select name="account_id" id="account_ID">
        <?php
        foreach ($GLOBALS["accounts"] as $key => $account_info) {
            ?>
            <option value="<?= urlencode($account_info['account_id']) ?>"> <!-- sécurisation URL -->
                <?= htmlspecialchars($account_info['account_name']) ?> <!-- sécurisation HTML -->
            </option>
        <?php } ?>
    </select>
    <button type="submit">Afficher les cartes</button>
</form>

<div class="card-display">
    <?php
    if (!empty($GLOBALS["cartes"])) {
        foreach ($GLOBALS["cartes"] as $index => $carte) { ?>
            <div class="card-and-settings">
                <div class="carte-bancaire">
                    <div class="carte-inner">
                        <div class="carte-front">
                            <div class="carte-puce">
                                <img src="public/svg/chip.svg" alt="Card Chip">
                            </div>
                            <div class="carte-logo">
                                <img src="public/img/logo1.png" alt="Card Type Logo">
                            </div>
                            <div class="carte-numero">
                                <span class="card-part" id="card-part-1-<?= $index ?>">****</span>
                                <span class="card-part" id="card-part-2-<?= $index ?>">****</span>
                                <span class="card-part" id="card-part-3-<?= $index ?>">****</span>
                                <span class="card-part" id="card-part-4-<?= $index ?>">
                                    <?= htmlspecialchars(substr($carte['card_numbers'], -4)) ?> <!-- sécurisation HTML -->
                                </span>
                            </div>

                            <div class="carte-informations">
                                <div class="carte-titulaire">
                                    <span class="label">Titulaire</span>
                                    <span class="valeur"><?= htmlspecialchars($carte['holder_name']) ?></span>
                                    <!-- sécurisation -->
                                </div>
                                <div class="carte-expiration">
                                    <span class="label">Expire fin</span>
                                    <span class="valeur"><?= htmlspecialchars($carte['expiration_date']) ?></span>
                                    <!-- sécurisation -->
                                </div>
                            </div>
                        </div>

                        <div class="carte-back">
                            <div class="bande-magnetique"></div>
                            <div class="cvc-section">
                                <span class="label">CVC</span>
                                <div class="cvc-valeur"><?= htmlspecialchars($carte['CSC']) ?></div> <!-- sécurisation -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-settings-1">
                    <a
                        href="/freeze_unfreeze_card?card_number=<?= urlencode($carte['card_numbers']) ?>&freeze=<?= urlencode($carte['freeze']) ?>">
                        <!-- sécurisation URL -->
                        <img class="settings-img"
                            src="public/img/<?= $carte['freeze'] == true ? "freeze.png" : "unfreeze.png" ?>"
                            alt="<?= $carte['freeze'] == true ? "Freeze Card" : "Unfreeze Card" ?>">
                        <!-- ici pas besoin htmlspecialchars car valeurs contrôlées -->
                    </a>

                    <a href="/delete_card?card_number=<?= urlencode($carte['card_numbers']) ?>"> <!-- sécurisation URL -->
                        <img class="settings-img" src="public/img/delete.png" alt="Delete Card">
                    </a>

                    <img class="settings-img hide-unhide-btn" src="public/img/hide.png" alt="Hide Card Details"
                        data-card-index="<?= $index ?>" data-card-number-full="<?= htmlspecialchars($carte['card_numbers']) ?>">
                    <!-- sécurisation HTML -->
                </div>
                <strong>Type : <?= htmlspecialchars($carte['card_type']) ?></strong> <!-- sécurisation -->
                <button class="copy-btn" data-card-index="<?= $index ?>"
                    data-card-number="<?= htmlspecialchars($carte['card_numbers']) ?>"
                    data-holder-name="<?= htmlspecialchars($carte['holder_name']) ?>"
                    data-expiration="<?= htmlspecialchars($carte['expiration_date']) ?>"
                    data-cvc="<?= htmlspecialchars($carte['CSC']) ?>">
                    Copier les infos 📝
                </button>

            </div>
            <?php
        }
    } else {
        echo "<p>Aucune carte trouvée pour ce compte.</p>";
    }
    ?>
    <div class="add-card">
        <a href="/add_cards_to_account">
            <img src="public/img/add.png" alt="Add New Card">
            <span>Ajouter une carte</span>
        </a>
    </div>
</div>

<div class="card-settings-2">
    <div class="card-settings-2_1">
        <p>La fonctionnalité de gestion de budget arrive bientôt 🐦‍⬛</p>
    </div>
    <div class="card-settings-2-history">
        <h3>Historique des Transactions Récentes</h3>
        <?php
        if (!empty($GLOBALS["last_history"])) {
            foreach ($GLOBALS["last_history"] as $history_item) { ?>
                <div class="history-info">
                    <span><?= htmlspecialchars($history_item['beneficiary_name']) ?></span> <!-- sécurisation -->
                    <span><?= htmlspecialchars($history_item['amount']) ?>         <?php include 'views/includes/_money.php'; ?></span>
                    <!-- sécurisation amount -->
                </div>
            <?php }
        } else {
            echo "<p>Aucune transaction récente trouvée.</p>";
        }
        ?>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const hideUnhideButtons = document.querySelectorAll('.hide-unhide-btn');

        hideUnhideButtons.forEach(button => {
            button.addEventListener('click', () => {
                const cardIndex = button.dataset.cardIndex;
                const fullCardNumber = button.dataset.cardNumberFull;
                const cardParts = [
                    document.getElementById(`card-part-1-${cardIndex}`),
                    document.getElementById(`card-part-2-${cardIndex}`),
                    document.getElementById(`card-part-3-${cardIndex}`),
                    document.getElementById(`card-part-4-${cardIndex}`)
                ];

                // Vérifier l'état actuel (masqué ou non)
                const isHidden = cardParts[0].textContent === '****';

                if (isHidden) {
                    // Démasquer le numéro de la carte
                    cardParts[0].textContent = fullCardNumber.substring(0, 4);
                    cardParts[1].textContent = fullCardNumber.substring(4, 8);
                    cardParts[2].textContent = fullCardNumber.substring(8, 12);
                    // Le dernier span est déjà visible

                    // Changer l'image en "unhide"
                    button.src = 'public/img/unhide.png';
                    button.alt = 'Unhide Card Details';
                } else {
                    // Masquer le numéro de la carte
                    cardParts[0].textContent = '****';
                    cardParts[1].textContent = '****';
                    cardParts[2].textContent = '****';
                    // Le dernier span reste visible

                    // Changer l'image en "hide"
                    button.src = 'public/img/hide.png';
                    button.alt = 'Hide Card Details';
                }
            });
        });
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const copyButtons = document.querySelectorAll('.copy-btn');

    copyButtons.forEach(button => {
        button.addEventListener('click', () => {
            const cardNumber = button.dataset.cardNumber;
            const holderName = button.dataset.holderName;
            const expiration = button.dataset.expiration;
            const cvc = button.dataset.cvc;

            const fullText =
                `Numéro : ${cardNumber}\n` +
                `Titulaire : ${holderName}\n` +
                `Expiration : ${expiration}\n` +
                `CVC : ${cvc}`;

            // Création d'un textarea temporaire
            const textarea = document.createElement("textarea");
            textarea.value = fullText;
            document.body.appendChild(textarea);
            textarea.select();
            try {
                document.execCommand("copy");
                alert("Informations de carte copiées dans le presse-papiers !");
            } catch (err) {
                alert("Échec de la copie.");
                console.error("Erreur : ", err);
            }
            document.body.removeChild(textarea);
        });
    });
});

</script>
