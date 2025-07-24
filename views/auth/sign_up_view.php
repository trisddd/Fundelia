<form class="modern-form" action="/sign_up" method="POST">
  <div class="form-title">
    S'inscrire - Étape 1
  </div>
  <div class="form-body">
    <div class="input-group">
      <div class="input-wrapper">
        <?php include_once 'public/svg/email_icon.php'; ?>
        <?php email_icon('input-icon'); ?>
          <input required="" placeholder="Email" class="form-input" type="email" name="email" />
      </div>
    </div>
  </div>

  <?php if (isset($error_message)) : ?>
    <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
  <?php endif; ?>

  <button class="submit-button" type="submit">
    <span class="button-text">Envoyer</span>
    <div class="button-glow"></div>
  </button>

  <div class="form-footer">
    <a class="login-link" href="/sign_in">
      Avez-vous déjà un compte? <span>Connectez-vous</span>
    </a>
  </div>
</form>
