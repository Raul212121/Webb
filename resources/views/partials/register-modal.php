<?php
declare(strict_types=1);
?>
<div class="modal-backdrop" id="register-modal-backdrop"<?= (!empty($registerError) || !empty($registerSuccess)) ? '' : ' hidden'; ?>>
    <div class="login-modal" role="dialog" aria-modal="true" aria-labelledby="register-modal-title">
        <button type="button" class="login-modal-close" id="close-register-modal" aria-label="Close">
            ×
        </button>

        <div class="login-modal-inner">
            <h2 class="login-modal-title" id="register-modal-title">Inregistrare</h2>
			<?php if (!empty($registerError)): ?>
				<div class="login-form-error"><?= htmlspecialchars($registerError, ENT_QUOTES, 'UTF-8'); ?></div>
			<?php endif; ?>

			<?php if (!empty($registerSuccess)): ?>
				<div class="login-form-success">
					<?= htmlspecialchars($registerSuccess, ENT_QUOTES, 'UTF-8'); ?><br>
				</div>
			<?php endif; ?>

            <form class="login-form" method="post" action="">
				<input type="hidden" name="register_form" value="1">
                <div class="login-form-group">
                    <label for="register-username">Nume cont</label>
                    <input type="text" id="register-username" name="username" autocomplete="username">
                </div>

                <div class="login-form-group">
                    <label for="register-email">Email</label>
                    <input type="email" id="register-email" name="email" autocomplete="email">
                </div>

                <div class="login-form-group">
                    <label for="register-password">Parola</label>
                    <input type="password" id="register-password" name="password" autocomplete="new-password">
                </div>

                <div class="login-form-group">
                    <label for="register-password-confirm">Confirma parola</label>
                    <input type="password" id="register-password-confirm" name="password_confirm" autocomplete="new-password">
                </div>

                <button type="submit" class="login-submit-button">Creeaza cont</button>
            </form>

            <div class="login-modal-footer">
                <span>Ai deja cont?</span>
                <a href="#" id="switch-to-login-modal">Login</a>
            </div>
        </div>
    </div>
</div>