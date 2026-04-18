<div class="panel panel-default">
	<div class="panel-heading mt2cms_main_left_panel_header"><?php print $lang['statistics']; ?></div>
	<div style="padding: 0;" class="panel-body mt2cms_main_left_panel_body">
		<?php if($jsondataFunctions['players-online']) { ?>
			<div class="online-now"><div><?php print number_format(getStatistics('players-online'), 0, '', '.'); ?></div></div>
			<h3 class="center" style="text-transform:uppercase; font-size: 14px;"><?php print $lang['players-online']; ?></h3>
		<?php } ?>
		<div class="stats">
		<?php
		foreach($jsondataFunctions as $key => $status)
			if(!in_array($key, array('active-registrations', 'players-debug', 'players-online', 'active-referrals')) && $status)
			{
		?>
			<div><?php print $lang[$key]; ?>: <div class="stats-value"><?php print number_format(getStatistics($key), 0, '', '.'); ?></div></div>
		<?php } ?>
		</div>
	</div>
</div>