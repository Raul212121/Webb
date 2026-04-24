<?php
declare(strict_types=1);
?>
<div class="modal-backdrop" id="fleamarket-modal-backdrop"<?= !empty($openFleamarketModal) ? '' : ' hidden'; ?>>

	<?php if (!empty($openFleamarketNotice) && !empty($fleamarketNoticeMessage)): ?>
		<div class="fleamarket-notice-overlay" id="fleamarket-notice-overlay">
			<div class="fleamarket-notice-box">
				<div class="fleamarket-notice-text">
					<?= htmlspecialchars($fleamarketNoticeMessage, ENT_QUOTES, 'UTF-8'); ?>
				</div>
				<button type="button" class="fleamarket-notice-button" id="close-fleamarket-notice">
					OK
				</button>
			</div>
		</div>
	<?php endif; ?>

    <div class="fleamarket-window" role="dialog" aria-modal="true" aria-labelledby="fleamarket-modal-title">

        <div id="fleamarket-content-container">
			<?php require BASE_PATH . '/resources/views/partials/fleamarket-content.php'; ?>
		</div>
    </div>
</div>