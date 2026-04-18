<div class="panel panel-default">
	<div class="panel-heading mt2cms_main_left_panel_header"><?php print $lang['players']; ?></div>
	<div style="padding: 0; padding-bottom: 15px;" class="panel-body mt2cms_main_left_panel_body">
		<div class="jugadores">
			<div class="">
				<?php
					if(!$offline) {
					$top = array();
					$top = top10players();
					
					foreach($top as $player)
					{
				?>
				<div style="margin-right: 0px; margin-left: 0px;" class="row mt2cms_rank_content">
					<div class="col-md-2 top-inline ranking-icon"></div>
					<div class="col-md-6 top-inline"><?php print $player['name']; ?></div>
					<div class="col-md-4 top-inline top-inline-empire"><img src="<?php print $site_url; ?>images/empire/<?php print $empire=get_player_empire($player['account_id']); ?>.jpg" alt="<?php print emire_name($empire); ?>" title="<?php print emire_name($empire); ?>"></div>
				</div>
				<?php }
					} else print $offline_players;
				?>
			</div>
		</div>
		<div class="center">
			<?php if(!$offline) { ?>
				<a href="<?php print $site_url; ?>ranking/players">Top 100 &raquo;</a>
			<?php } else print '<span class="mt2cms_main_box_middle_content_create_error">'.$lang['server-offline'].'</span></br><span class="mt2cms_main_box_middle_content_create_error">'.$lang['last-update'].': '.$offline_date.'</span>'; ?>
		</div>
	</div>
	<div class="panel-heading mt2cms_main_left_panel_header"><?php print $lang['guilds']; ?></div>
	<div style="padding: 0; padding-bottom: 15px;" class="panel-body mt2cms_main_left_panel_body">
		<div class="jugadores">
			<div class="">
				<?php
					if(!$offline) {
					$top = array();
					$top = top10guilds();
					
					foreach($top as $guild)
					{
				?>
				<div style="margin-right: 0px; margin-left: 0px;" class="row mt2cms_rank_content">
					<div class="col-md-2 top-inline ranking-icon"></div>
					<div class="col-md-6 top-inline"><?php print $guild['name']; ?></div>
					<div class="col-md-4 top-inline top-inline-empire"><img src="<?php print $site_url; ?>images/empire/<?php print $empire=get_guild_empire($guild['master']); ?>.jpg" alt="<?php print emire_name($empire); ?>" title="<?php print emire_name($empire); ?>"/></div>
				</div>
				<?php }
					} else print $offline_guilds;
				?>
			</div>
		</div>
		<div class="center">
			<?php if(!$offline) { ?>
				<a href="<?php print $site_url; ?>ranking/guilds">Top 100 &raquo;</a>
			<?php } else print '<span class="mt2cms_main_box_middle_content_create_error">'.$lang['server-offline'].'</span></br><span class="mt2cms_main_box_middle_content_create_error">'.$lang['last-update'].': '.$offline_date.'</span>'; ?>
		</div>
	</div>
</div>