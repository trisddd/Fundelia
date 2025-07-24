<div class="header-section">
    <h1>Historique des transactions</h1>
</div>

<div class="main-layout">
    <div class="tab-navigation">
        <button id="expenses-button" class="tab-button">
            <span class="arrow">&lt;</span> Dépenses
        </button>
        <button id="incomes-button" class="tab-button">
            Recettes <span class="arrow">&gt;</span>
        </button>
    </div>
    <div class="transaction-display-area">

        <div id="expenses-tab" class="tab-content">
            <h2>Dépenses</h2>
            <?php if (isset($grouped_tx['expenses']) && is_array($grouped_tx['expenses'])): ?>
                <?php foreach ($grouped_tx['expenses'] as $date => $txs): ?>
                    <div class="date-group">
                        <h3><?= htmlspecialchars($date) ?></h3>
                        <?php foreach ($txs as $tx): ?>
                            <div class="transaction-line transaction-line-depense"
                                data-id="<?= htmlspecialchars($tx['id']) ?>"
                                data-name="<?= htmlspecialchars($tx['name']) ?>"
                                data-time="<?= htmlspecialchars($tx['time']) ?>"
                                data-amount="<?= htmlspecialchars($tx['amount']) ?>"
                                data-wording="<?= htmlspecialchars($tx['wording']) ?>"
                                    <?php $added_labels = $labels_for_tx[$tx['id']] ?? [];?>
                                    <?php $labels_json = json_encode($added_labels);?>
                                data-labels="<?= htmlspecialchars($labels_json, ENT_QUOTES, 'UTF-8') ?>"
                                onclick="showPopup(this)">
                                <span><?= htmlspecialchars($tx['name']) ?></span>
                                <time datetime="<?= htmlspecialchars($tx['time']) ?>"><?= htmlspecialchars($tx['time']) ?></time>
                                <span class="amount negative"><?= htmlspecialchars($tx['amount']) ?>
                                <?php include 'views/includes/_money.php'; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: #555;">Aucune dépense à afficher.</p>
            <?php endif; ?>
        </div>

        <div id="incomes-tab" class="tab-content hidden">
            <h2 class="incomes">Recettes</h2>
            <?php if (isset($grouped_tx['incomes']) && is_array($grouped_tx['incomes'])): ?>
                <?php foreach ($grouped_tx['incomes'] as $date => $txs): ?>
                    <div class="date-group">
                        <h3><?= htmlspecialchars($date) ?></h3>
                        <?php foreach ($txs as $tx): ?>
                            <div class="transaction-line transaction-line-entre"
                                data-id="<?= htmlspecialchars($tx['id']) ?>"
                                data-name="<?= htmlspecialchars($tx['name']) ?>"
                                data-time="<?= htmlspecialchars($tx['time']) ?>"
                                data-amount="<?= htmlspecialchars($tx['amount']) ?>"
                                data-wording="<?= htmlspecialchars($tx['wording']) ?>"
                                    <?php $added_labels = $labels_for_tx[$tx['id']] ?? [];?>
                                    <?php $labels_json = json_encode($added_labels);?>
                                data-labels="<?= htmlspecialchars($labels_json, ENT_QUOTES, 'UTF-8') ?>"
                                onclick="showPopup(this)">
                                <span><?= htmlspecialchars($tx['name']) ?></span>
                                <time datetime="<?= htmlspecialchars($tx['time']) ?>"><?= htmlspecialchars($tx['time']) ?></time>
                                <span class="amount positive"><?= htmlspecialchars($tx['amount']) ?>
                                    <?php include 'views/includes/_money.php'; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: #555;">Aucune recette à afficher.</p>
            <?php endif; ?>
        </div>

        <div class="filter-sidebar">
            <div class="sort-options">
                <h3>Trier par :</h3>
                <a href="/show_transaction_history?sort=amount-desc" class="sort-button" data-sort="amount-desc">Montant
                    (Plus élevé)</a>
                <a href="/show_transaction_history?sort=amount-asc" class="sort-button" data-sort="amount-asc">Montant
                    (Moins élevé)</a>
                <a href="/show_transaction_history?sort=date-desc" class="sort-button" data-sort="date-desc">Date (Plus
                    récent)</a>
                <a href="/show_transaction_history?sort=date-asc" class="sort-button" data-sort="date-asc">Date (Moins
                    récent)</a>
            </div>
        </div>
    </div>
</div>

<div id="transactionPopup" class="popup" onclick="closePopup()">
    <div class="popup-content" onclick="event.stopPropagation()">
        <h3>Détails de la transaction</h3>
        <p><strong>Nom:</strong> <span id="popupName"></span></p>
        <p><strong>Heure:</strong> <span id="popupTime"></span></p>
        <p><strong>Montant:</strong> <span id="popupAmount"></span> <?php include 'views/includes/_money.php'; ?></p>
        <p><strong>Libellé complet:</strong> <span id="popupWording"></span></p>
        <div id="popupLabelsSection">
            <strong>Labels :</strong>
            <div id="popupLabelsLinks" style="margin-top: 5px;"></div>
            <button id="addLabelBtn" onclick="openLabelPopup()" >+</button>
        </div>
        <button onclick="closePopup()">Fermer</button>
    </div>
</div>

<div id="labelSelectorPopup" class="popup" onclick="closeLabelSelectorPopup()">
    <div class="popup-content" onclick="event.stopPropagation()">
        <h3>Choisir un label</h3>
        <div id="availableLabels">
            <?php foreach ($labels as $label) { ?>
                <button class="label-btn" onclick="addLabelToTransaction(<?= $label['id'] ?>)"><?= $label['name'] ?>
                </button>
            <?php }; ?>
        </div>
        <button id="addLabelBtn" onclick="window.location.href='/add_labels'">+</button><br>
        <button onclick="closeLabelSelectorPopup()">Fermer</button>
    </div>
</div>
