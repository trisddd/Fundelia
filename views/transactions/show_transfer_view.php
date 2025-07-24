<h1 class="transfer-page-title">Virements</h1>
<div class="transfer-page-container">
    <section class="transfer-action">
        <button id="openTransferModal" class="transfer-action-button">
            Effectuer un virement
        </button>
    </section>

    <section>
        <h2 class="transfer-history-header">Historique des virements</h2>
    </section>

    <section class="transfer-history-list">
        <?php if (!empty($transfer_history)): ?>
            <?php foreach ($transfer_history as $transfer): ?>
                <div class="transfer-history-item">
                    <div class="transfer-avatar">
                        <?php
                        $names = explode(' ', $transfer['beneficiary_name']);
                        echo strtoupper(htmlspecialchars(substr($names[0], 0, 1) . substr(end($names), 0, 1), ENT_QUOTES, 'UTF-8'));
                        ?>
                    </div>
                    <div class="transfer-info">
                        <p class="transfer-name"><?= htmlspecialchars($transfer['beneficiary_name'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p class="transfer-iban"><?= htmlspecialchars($transfer['beneficiary_IBAN'], ENT_QUOTES, 'UTF-8') ?></p>
                        <div class="transfer-amount-date">
                            <p class="transfer-amount">
                                Vous avez envoyé <?= htmlspecialchars($transfer['amount'], ENT_QUOTES, 'UTF-8') ?>
                                <?php include 'views/includes/_money.php'; ?>
                            </p>
                            <p class="transfer-date"><?= htmlspecialchars(strftime('%e %b', strtotime($transfer['date'])), ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun virement trouvé.</p>
        <?php endif; ?>
    </section>
</div>

<!-- MODALE -->
<div id="transferModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Depuis quel compte ?</h3>
            <button id="closeTransferModal" class="modal-close-btn">&times;</button>
        </div>
        <div class="modal-body">
            <div class="account-group">
                <h4>Mes comptes</h4>
                <div class="account-list">
                    <?php if (!empty($account)): ?>
                        <?php foreach ($account as $acc): ?>
                            <a href="/show_beneficiaries?emitter_account_iban=<?= urlencode($acc['IBAN']) ?>" class="account-link">
                                <div class="account-item">
                                    <span><?= htmlspecialchars($acc['name'], ENT_QUOTES, 'UTF-8') ?></span>
                                    <span class="balance"><?= number_format($acc['balance'], 2, ',', ' ') ?><?php include 'views/includes/_money.php'; ?></span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Aucun compte disponible</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
