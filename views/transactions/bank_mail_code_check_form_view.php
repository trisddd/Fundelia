<?php
$error = $_GET['error'] ?? '';
?>
<title>Confirmation de paiement - FUNDELIA</title>

<div class="payment-container">
    <div class="fundelia-logo">
        <img src="/public/icons/logo.png" alt="Logo FUNDELIA">
    </div>
    <div class="fundelia-logo">FUNDELIA</div>


    <?php if ($error !== ''): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <hr>
    <?php endif; ?>

    <h2>Confirmation de votre paiement</h2>
    <p>Veuillez entrer le code que vous avez reçu par e-mail pour confirmer la transaction</p>

    <form method="POST" action="/bank_mail_code_checking">
        <label for="code">Code reçu par mail :</label>
        <input type="text" id="code" name="code" required placeholder="123456">
        <button type="submit">Valider le paiement</button>
    </form>
</div>