<?php
declare(strict_types=1);
?>
<div class="modal-backdrop" id="verify-modal-backdrop"<?= !empty($openVerificationModal) ? '' : ' hidden'; ?>>
    <div class="login-modal" role="dialog" aria-modal="true" aria-labelledby="verify-modal-title">
        <button type="button" class="login-modal-close" id="close-verify-modal" aria-label="Close">
            ×
        </button>

        <div class="login-modal-inner">
            <h2 class="login-modal-title" id="verify-modal-title">Verificare email</h2>
			<?php if (!empty($verifyError)): ?>
				<div class="login-form-error"><?= htmlspecialchars($verifyError, ENT_QUOTES, 'UTF-8'); ?></div>
			<?php endif; ?>

			<?php if (!empty($verifySuccess)): ?>
				<div class="login-form-success"><?= htmlspecialchars($verifySuccess, ENT_QUOTES, 'UTF-8'); ?></div>
			<?php endif; ?>

            <form class="login-form" method="post" action="">
                <input type="hidden" name="verify_form" value="1">
                <input type="hidden" name="verification_token" value="<?= htmlspecialchars($registerToken, ENT_QUOTES, 'UTF-8'); ?>">

                <div class="login-form-group">
                    <label for="verification-code">Cod verificare</label>
                    <input type="text" id="verification-code" name="verification_code" maxlength="12" autocomplete="one-time-code">
                </div>

                <button type="submit" class="login-submit-button">Verifica codul</button>
            </form>
        </div>
    </div>
</div>