<div class="modern-form">
  <div class="form-header">
    <h2 class="form-title">Ajouter bénéficiaire</h2>
  </div>
  <form method="POST" action="/external_iban_verification">
    <div class="input-group">
      <label for="prenom" class="input-label">Prénom</label>
      <input type="text" id="prenom" name="beneficiary_first_name" required pattern="^[A-Za-zÀ-ÿ '-]{2,50}$" maxlength="50" class="form-input" placeholder="Jean">
    </div>
    <div class="input-group">
      <label for="nom" class="input-label">Nom</label>
      <input type="text" id="nom" name="beneficiary_last_name" required pattern="^[A-Za-zÀ-ÿ '-]{2,50}$" maxlength="50" class="form-input" placeholder="Dupont">
    </div>
    <div class="input-group">
      <label for="iban" class="input-label">IBAN</label>
      <input type="text" id="iban" name="beneficiary_iban" required pattern="[A-Z0-9]{15,34}" maxlength="34" class="form-input" placeholder="FR76...">
    </div>
    <button type="submit" class="submit-button">
      <span class="button-text">Ajouter</span>
      <span class="button-glow"></span>
    </button>
  </form>
</div>


