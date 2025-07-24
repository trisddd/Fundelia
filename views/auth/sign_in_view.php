    <form class="modern-form" action="/sign_in" method="POST">

        <div class="form-title">Entrez vos identifiants</div>

        <div class="form-body">

            <div class="input-group">
                <div class="input-wrapper">
                    <?php include_once 'public/svg/email_icon.php'; ?>
                    <?php email_icon('input-icon'); ?>
                    <input required="" placeholder="Email" name="mail" value="<?= htmlspecialchars($email ?? '') ?>" class="form-input" type="email"/>
                </div>
            </div>

            <?php if (isset($error_message)) : ?>
                <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
            <?php endif; ?>

            <div class="input-group">
                <div class="input-wrapper">
                    <?php include_once 'public/svg/padlock_icon.php'; ?>
                    <?php padlock_icon('input-icon'); ?>
                    <input required="" placeholder="Password" id="password" name="password" class="form-input" type="password"/>
                    <button class="password-toggle" type="button" onclick="passcodeVisibility(this)">
                        <?php include_once 'public/svg/eye_icon.php'; ?>
                        <?php eye_icon('eye-icon'); ?>
                    </button>
                </div>
            </div>
        </div>

        <button class="submit-button" type="submit">
            <span class="button-text">Se connecter</span>
            <div class="button-glow"></div>
        </button>

        <div class="form-footer">
            <a class="login-link" href="#">
                <span>Mot de passe oublié?</span>
            </a>
        </div>

    </form>
