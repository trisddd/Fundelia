<div class="form-container">
    <div class="form-header">
        <h2>Ajouter un membre</h2>
    </div>
    <form method="POST" action="/create_employee/<?= urlencode($business_name); ?>">
        <div class="input-group">
            <label for="prenom" class="input-label">Prénom</label>
            <input type="text" id="prenom" name="first_name" required pattern="^[A-Za-zÀ-ÿ '-]{2,50}$" maxlength="50">
        </div>
        <div class="input-group">
            <label for="nom" class="input-label">Nom</label>
            <input type="text" id="nom" name="last_name" required pattern="^[A-Za-zÀ-ÿ '-]{2,50}$" maxlength="50">
        </div>
        <div class="input-group">
            <label for="iban" class="input-label">E-mail</label>
            <input type="text" id="email" name="email" required>
        </div>
        <div class="input-group">
            <label for="role" class="input-label">Rôle</label>
            <select id="role" name="role" required>
                <option value="manager">Manager</option>
                <option value="employee">Employé</option>
            </select>
        </div>
        <button type="submit" class="add-button">Ajouter</button>
    </form>
</div>