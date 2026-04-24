<?php
declare(strict_types=1);
?>
<div class="modal-backdrop" id="login-modal-backdrop"<?= (!empty($loginError) || !empty($openLoginModal)) ? '' : ' hidden'; ?>>
    <div class="login-modal" role="dialog" aria-modal="true" aria-labelledby="login-modal-title">
        <button type="button" class="login-modal-close" id="close-login-modal" aria-label="Close">
            ×
        </button>

        <div class="login-modal-inner">
            <h2 class="login-modal-title" id="login-modal-title">Login</h2>
			<?php if (!empty($loginError)): ?>
				<div class="login-form-error"><?= htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8'); ?></div>
			<?php endif; ?>

			<?php if (!empty($loginSuccess)): ?>
				<div class="login-form-success"><?= htmlspecialchars($loginSuccess, ENT_QUOTES, 'UTF-8'); ?></div>
			<?php endif; ?>

            <form class="login-form" id="login-form" method="post" action="">
				<input type="hidden" name="login_form" value="1">
                <div class="login-form-group">
                    <label for="login-username">Nume cont</label>
                    <input type="text" id="login-username" name="username" autocomplete="username">
                </div>

                <div class="login-form-group">
                    <label for="login-password">Parola</label>
                    <input type="password" id="login-password" name="password" autocomplete="current-password">
                </div>

                <button type="submit" class="login-submit-button">Login</button>
            </form>

            <div class="login-modal-footer">
                <span>Nu ai cont?</span>
                <a href="#" id="switch-to-register-modal">Inregistrare</a>
            </div>
        </div>
    </div>
</div>