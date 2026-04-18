<div class="panel panel-default">
    <div class="panel-heading mt2cms_main_left_panel_header"><?php print $lang['user-panel']; ?></div>
    <div class="panel-body mt2cms_main_left_panel_body">
		<?php if($offline || !$database->is_loggedin()) { ?>
		<form class="form" role="form" method="post" action="<?php print $site_url; ?>users/login" accept-charset="UTF-8" id="login-nav" style="width:200px;margin:0 auto;">
            <label for="mt2login" class="mt2cms_main_box_middle_content_label"><i class="fa fa-user mt2cms_icon_mr mt2cms_color"></i><?php print $lang['user-name-or-email']; ?></label>
            <input id="mt2login" class="mt2cms_main_box_middle_content_input" name="username" type="text" required="" min="5" pattern=".{5,64}" maxlength="64" <?php if($offline) print 'disabled'; else print 'required'; ?>>
            <label for="mt2pass" class="mt2cms_main_box_middle_content_label"><i class="fa fa-key mt2cms_icon_mr mt2cms_color"></i><?php print $lang['password']; ?></label>
            <input id="mt2pass" class="mt2cms_main_box_middle_content_input" name="password" type="password" required="" min="5" pattern=".{5,16}" maxlength="16" <?php if($offline) print 'disabled'; else print 'required'; ?>>
            <button class="mt2cms_main_content_button login-btn" name="submit" type="submit"<?php if($offline) print ' disabled'; ?>><span class="fa fa-sign-in"></span> &nbsp; <?php print $lang['login']; ?></button>
            <?php if(!$offline) { ?>
			<div class="center">
                <a href="<?php print $site_url; ?>users/lost"><?php print $lang['forget-password']; ?></a>
            </div>
			<?php } ?>
        </form>
		<?php } else { ?>
		<div class="panel-body mt2cms_main_left_panel_body">
			<h3 class="center" style="text-transform:uppercase; font-size: 14px;"><?php print $account_name = getAccountName($_SESSION['id']); ?></h3>
			<div class="user_panel_buttons">
				<?php if($web_admin) { ?>
				<a href="<?php print $site_url; ?>admin">
					<button id="upb_accountsettings" class="user_panel_buttons_row">
						<span class="user_panel_buttons_icon fa fa-gear"></span> <?php print $lang['administration']; ?><?php if(checkUpdate(officialVersion())) print ' </br><div class="pull-right heading-secondary-text">'.$lang['update-available'].'</div>'; ?>
					</button>
				</a>
				
				<?php 
						$count_donations = count(get_donations());
						if($count_donations)
						{
				?>	
				<a href="<?php print $site_url; ?>admin/donatelist">
					<button id="upb_accountsettings" class="user_panel_buttons_row">
						<span class="user_panel_buttons_icon fa fa-eur"></span> <?php print $count_donations.' '.$lang['new-donations']; ?>
					</button>
				</a>
				<?php
						}
					} 
				?>
				<a href="<?php print $site_url; ?>user/administration">
					<button id="upb_accountsettings" class="user_panel_buttons_row">
						<span class="user_panel_buttons_icon fa fa-user"></span> <?php print $lang['account-data']; ?>
					</button>
				</a>
				<a href="<?php print $site_url; ?>user/characters">
					<button id="upb_caractere" class="user_panel_buttons_row">
						<span class="user_panel_buttons_icon fa fa-users"></span> <?php print $lang['chars-list']; ?>
					</button>
				</a>
				<a href="<?php print $site_url; ?>user/redeem">
					<button id="upb_caractere" class="user_panel_buttons_row">
						<span class="user_panel_buttons_icon fa fa-barcode"></span> <?php print $lang['redeem-codes']; ?>
					</button>
				</a>
				<?php if($jsondataFunctions['active-referrals']) { ?>
				<a href="<?php print $site_url; ?>user/referrals">
					<button id="upb_caractere" class="user_panel_buttons_row">
						<span class="user_panel_buttons_icon fa fa-users"></span> <?php print $lang['referrals']; ?>
					</button>
				</a>
				<?php } if($item_shop!="" || is_dir('shop')) { ?>
				<a href="<?php if($item_shop!="") print $shop_url = $item_shop; else if(is_dir('shop')) print $shop_url = $site_url.'shop'; else print $shop_url = ''; ?>" target="_blank">
					<button id="upb_caractere" class="user_panel_buttons_row">
						<span class="user_panel_buttons_icon fa fa-shopping-cart"></span> <?php print $lang['item-shop']; ?>
					</button>
				</a>
				<?php }
					$vote4coins = file_get_contents('include/db/vote4coins.json');
					$vote4coins = json_decode($vote4coins, true);
					
					if(count($vote4coins))
					{
				?>
				<a href="<?php print $site_url; ?>user/vote4coins">
					<button id="upb_caractere" class="user_panel_buttons_row">
						<span class="user_panel_buttons_icon fa fa-usd"></span> Vote4Coins
					</button>
				</a>
				<?php 
					}
					$donate = file_get_contents('include/db/donate.json');
					$donate = json_decode($donate, true);
					
					if(count($donate))
					{
				?>
				<a href="<?php print $site_url; ?>user/donate">
					<button id="upb_caractere" class="user_panel_buttons_row">
						<span class="user_panel_buttons_icon fa fa-handshake-o"></span> <?php print $lang['donate']; ?>
					</button>
				</a>
				<?php
					}
				?>
			</div>
			
			<a href="<?php print $site_url; ?>users/logout">
				<button class="mt2cms_main_content_button"><span class="fa fa-sign-out"></span> &nbsp; <?php print $lang['logout']; ?></button>
			</a>
		</div>
		<?php } ?>
    </div>
</div>