  <form class="modern-form" action="/verify_password" method="POST">
    <div class="form-title">
      S'inscrire - Étape 2
    </div>
    <div class="form-subtitle">
      Nous avons envoyé un code à votre adresse e-mail <?= htmlspecialchars($email); ?>
    </div>
    <div class="form-body">
      <div class="input-group">
        <div class="otp-inputs">
          <input class="form-input" id="otp1" name="otp1" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" required oninput="moveFocus(1)" onkeydown="Backspace(event, 1)" onkeypress="validateInput(event)" />
          <input class="form-input" id="otp2" name="otp2" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" required oninput="moveFocus(2)" onkeydown="Backspace(event, 2)" onkeypress="validateInput(event)" />
          <input class="form-input" id="otp3" name="otp3" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" required oninput="moveFocus(3)" onkeydown="Backspace(event, 3)" onkeypress="validateInput(event)" />
          <input class="form-input" id="otp4" name="otp4" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" required oninput="moveFocus(4)" onkeydown="Backspace(event, 4)" onkeypress="validateInput(event)" />
          <input class="form-input" id="otp5" name="otp5" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" required oninput="moveFocus(5)" onkeydown="Backspace(event, 5)" onkeypress="validateInput(event)" />
          <input class="form-input" id="otp6" name="otp6" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" required oninput="moveFocus(6)" onkeydown="Backspace(event, 6)" onkeypress="validateInput(event)" />
        </div>
      </div>
    </div>

    <button class="submit-button" type="submit">
      <span class="button-text">Envoyer</span>
      <div class="button-glow"></div>
    </button>

    <div class="form-footer">
      <!-- TODO:Mettre le lien pour renvoyer le code à la même adresse email -->
      <a class="login-link" href="/verify_password">
        Vous n'avez pas reçu le code ?<span>Renvoyer</span>
      </a>
    </div>
  </form>
