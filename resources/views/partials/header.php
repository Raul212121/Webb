<?php
declare(strict_types=1);

?>
<header class="site-header">
    <div class="site-header-overlay"></div>

    <div class="container">
        <div class="site-header-inner">
            <div class="site-header-left">
                <a href="/" class="site-logo">LuraMT2</a>
            </div>

            <nav class="site-nav">
                <a href="/" class="site-nav-link is-active">Acasa</a>
                <a href="/download" class="site-nav-link">Descarca</a>
                <a href="/ranking" class="site-nav-link">Ranking</a>
                <a href="/discord" class="site-nav-link">Discord</a>
                <a href="/support" class="site-nav-link">Support</a>

                <?php if ($isLoggedIn): ?>
                    <a href="/itemshop" class="site-nav-link site-nav-link-special">Itemshop</a>
                    <button type="button" class="site-nav-link site-nav-link-special site-nav-button" id="open-fleamarket-modal">FleaMarket</button>
                <?php endif; ?>
            </nav>

            <div class="site-header-right">
				<?php if (!empty($isLoggedIn)): ?>
					<div class="account-dropdown">
						<button type="button" class="header-action-button" id="account-dropdown-toggle">
							My Account
						</button>

						<div class="account-dropdown-menu" id="account-dropdown-menu" hidden>
							<a href="/account">Panou cont</a>
							<a href="/itemshop-balance">Monede</a>
							<a href="/?logout=1" class="account-dropdown-logout">Logout</a>
						</div>
					</div>
				<?php else: ?>
					<button type="button" class="header-action-button" id="open-login-modal">Login</button>
					<button type="button" class="header-action-button header-action-button-primary" id="open-register-modal">Inregistrare</button>
				<?php endif; ?>
			</div>
        </div>
    </div>
</header>