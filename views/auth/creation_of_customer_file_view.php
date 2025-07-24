<form class="modern-form" action="/creation_of_customer_file" method="POST">
    <div class="form-title">
        S'inscrire - Étape 3
    </div>
    <div class="form-body">
        <div class="form-row">
            <div class="input-group">
                <div class="input-wrapper">
                    <?php include_once 'public/svg/user_icon.php'; ?>
                    <?php user_icon('input-icon'); ?>
                    <input required="" placeholder="First Name" name="first_name" id="first_name" class="form-input"
                        type="text"
                        value="<?= isset($old['first_name']) ? htmlspecialchars($old['first_name']) : '' ?>" />
                </div>
            </div>
            <div class="input-group">
                <div class="input-wrapper">
                    <?php include_once 'public/svg/user_icon.php'; ?>
                    <?php user_icon('input-icon'); ?>
                    <input required="" placeholder="Last Name" name="last_name" id="last_name" class="form-input"
                        type="text" />
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="input-group">
                <div class="input-wrapper">
                    <?php include_once 'public/svg/padlock_icon.php'; ?>
                    <?php padlock_icon('input-icon'); ?>
                    <input required="" placeholder="Password" name="new_password" id="new_password" class="form-input"
                        type="password" />
                    <button class="password-toggle" type="button" onclick="passcodeVisibility(this)">
                        <?php include_once 'public/svg/eye_icon.php'; ?>
                        <?php eye_icon('eye-icon'); ?>
                    </button>
                </div>
            </div>
            <div class="input-group">
                <div class="input-wrapper">
                    <?php include_once 'public/svg/padlock_icon.php'; ?>
                    <?php padlock_icon('input-icon'); ?>
                    <input required="" placeholder="Confirm Password" name="confirm_password" id="confirm_password"
                        class="form-input" type="password" />
                    <button class="password-toggle" type="button" onclick="passcodeVisibility(this)">
                        <?php include_once 'public/svg/eye_icon.php'; ?>
                        <?php eye_icon('eye-icon'); ?>
                    </button>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="input-group">
                <div class="input-wrapper">
                    <?php include_once 'public/svg/key_icon.php'; ?>
                    <?php key_icon('input-icon'); ?>
                    <input required="" placeholder="Code" name="new_code" id="new_code" class="form-input"
                        type="password" maxlength="4" onkeypress="validateInput(event)" />
                    <button class="password-toggle" type="button" onclick="passcodeVisibility(this)">
                        <?php include_once 'public/svg/eye_icon.php'; ?>
                        <?php eye_icon('eye-icon'); ?>
                    </button>
                </div>
            </div>
            <div class="input-group">
                <div class="input-wrapper">
                    <?php include_once 'public/svg/key_icon.php'; ?>
                    <?php key_icon('input-icon'); ?>
                    <input required="" placeholder="Confirm Code" name="confirm_code" id="confirm_code"
                        class="form-input" type="password" maxlength="4" onkeypress="validateInput(event)" />
                    <button class="password-toggle" type="button" onclick="passcodeVisibility(this)">
                        <?php include_once 'public/svg/eye_icon.php'; ?>
                        <?php eye_icon('eye-icon'); ?>
                    </button>
                </div>
            </div>
        </div>
        <span class="placeholder">Date de naissance</span>
        <div class="form-row">
            <div class="input-group">
                <div class="input-wrapper">
                    <?php include_once 'public/svg/calendar_icon.php'; ?>
                    <?php calendar_icon('input-icon'); ?>
                    <input required="" placeholder="BirthDate" name="birth_date" id="birth_date" class="form-input"
                        type="Date" />
                </div>
            </div>
        </div>
        <div class="radio-container">
            <div>
                <label>
                    <input type="radio" name="gender" value="male" checked="">
                    <span>Homme</span>
                </label>
                <label>
                    <input type="radio" name="gender" value="female">
                    <span>Femme</span>
                </label>
                <label>
                    <input type="radio" name="gender" value="non-binary">
                    <span>Non Binaire</span>
                </label>
                <label>
                    <input type="radio" name="gender" value="agender">
                    <span>Agenre</span>
                </label>
            </div>
        </div>
        <div class="checkbox-group">
            <label class="checkbox-container">
                <input type="checkbox" name="terms_and_conditions" required>
                <?php include_once 'public/svg/checkbox.php'; ?>
                <?php checkbox(''); ?>
            </label>
            <div class="checkbox-text">
                J'accepte les <a href="#">Termes et Conditions.</a> <!-- TODO : Faire les Termes et conditions -->
            </div>
        </div>
    </div>

    <button class="submit-button" type="submit">
        <span class="button-text">S'inscrire</span>
        <div class="button-glow"></div>
    </button>
</form>