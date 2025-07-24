<div class="payment-box">
  <div class="fundelia-logo">
    <img src="/public/icons/logo.png" alt="Logo FUNDELIA">
  </div>
  <div class="fundelia-logo">FUNDELIA</div>
  <h1>Paiement sécurisé</h1>
  <form action="" method="POST" class="form">
    <label>Titulaire de la carte :
      <input type="text" name="holder_name" required placeholder="Jean">
    </label>

    <label>Numéro de carte :
      <input type="text" name="card_number" required maxlength="16" placeholder="1234 5678 9012 3456">
    </label>

    <label>Date d'expiration :
      <input type="date" name="card_exp" required>
    </label>

    <label>CVV :
      <input type="text" name="card_cvc" required maxlength="3" placeholder="123">
    </label>

    <p>Montant de la commande :
      <?= htmlspecialchars($_SESSION['order_amount'] ?? '', ENT_QUOTES, 'UTF-8') ?>
      <?php include 'views/includes/_money.php'; ?>
    </p>

    <button type="submit">Valider le paiement</button>
  </form>
</div>