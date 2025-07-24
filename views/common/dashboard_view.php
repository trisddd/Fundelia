<!-- Message de bienvenue -->
<div class="welcome">
    Bonjour, <?= htmlspecialchars($user_first_name . ' ' . $user_last_name, ENT_QUOTES, 'UTF-8') ?> 👋
</div>

<!-- Cards -->
<div class="cards">
    <div class="balance-summary">
        <div class="bubble-container">
            <span class="label">Informations comptes perso</span>
            <div class="balance-bubble total">
                <span class="label"><u>Solde total</u></span>
                <span class="amount">
                    <?= htmlspecialchars($total_amount, ENT_QUOTES, 'UTF-8') ?>
                    <span class="currency-icon">
                        <?php include 'views/includes/_money.php'; ?>
                    </span>
                </span>
            </div>

            <div class="balance-bubble">
                <span class="label"><u>Compte(s) courant(s)</u></span>
                <span class="amount">
                    <?= htmlspecialchars($total_amount_current, ENT_QUOTES, 'UTF-8') ?>
                    <span class="currency-icon">
                        <?php include 'views/includes/_money.php'; ?>
                    </span>
                </span>
            </div>

            <div class="balance-bubble">
                <span class="label"><u>Épargne(s)</u></span>
                <span class="amount">
                    <?= htmlspecialchars($total_amount_saving, ENT_QUOTES, 'UTF-8') ?>
                    <span class="currency-icon">
                        <?php include 'views/includes/_money.php'; ?>
                    </span>
                </span>
            </div>
        </div>
    </div>

    <div class="transaction-history">
        <div class="transaction-header">
            <?php include_once 'public/svg/clock_history_icon.php'; ?>
            <?php clock_history_icon('icon-history'); ?>
            <span class="transaction-title">Historique des transactions</span>
        </div>
        <ul class="transaction-list">
            <?php foreach ($transactions as $transaction): ?>
                <li class="transaction-bubble">
                    <span class="transaction-name"><?= htmlspecialchars($transaction[2], ENT_QUOTES, 'UTF-8') ?></span>
                    <span class="transaction-date"><?= htmlspecialchars($transaction[0], ENT_QUOTES, 'UTF-8') ?></span>
                    <span class="transaction-time"><?= htmlspecialchars($transaction[1], ENT_QUOTES, 'UTF-8') ?></span>
                    <span class="<?php
                    echo str_contains($transaction[3], "-") ? 'transaction-amount negative' : 'transaction-amount positive';
                    ?>">
                        <?= htmlspecialchars($transaction[3], ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="notifications-preview">
        <div class="notifications-header">
            <?php include_once 'public/svg/bell_icon.php'; ?>
            <?php bell_icon('bell-icon'); ?>
            <span class="notifications-title">Notifications</span>
            <?php if($unread_notif > 99): ?>
                <span class="notifications-number"><?= "+99" ?></span>
            <?php elseif($unread_notif > 0): ?>
                <span class="notifications-number"><?= htmlspecialchars($unread_notif); ?></span>
            <?php endif; ?>
        </div>
        <ul class="notification-list">
            <?php foreach ($notifications as $key => $value): ?>

                <li class="notification-item">
                    <?php if ($value['status'] == false): ?>
                        <div class="notification-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <circle cx="12" cy="12" r="10" />
                            </svg>
                        </div>
                    <?php endif; ?>
                    <div class="<?= $value['status'] == false ? 'notification-bubble ' : 'notification-read' ?>">
                        <?= htmlspecialchars($value['message']) ?>
                    </div>
                </li>
            <?php endforeach; ?>
            <?php if (empty($notifications)): ?>
    <p class="no-notifications">Aucune notification pour l'instant.</p>
<?php endif; ?>
        </ul>

    </div>
</div>

<!-- Graphs -->
<div class="grid">
    <div class="chart-box">
        <div style="font-weight:600; margin-bottom:0.5rem; color:#186ED1;">Dépenses Comparées</div>
        <canvas id="barChart"></canvas>
    </div>
    <div class="chart-box">
        <div style="font-weight:600; margin-bottom:0.5rem; color:#186ED1;">Vue d'Ensemble</div>
        <canvas id="pieChart"></canvas>
    </div>
</div>
<script>
  const donutData = <?= json_encode($label_stats) ?>;
  const weeklyExpenseData = <?= json_encode($weekly_comparison_data) ?>;
</script>