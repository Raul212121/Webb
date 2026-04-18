<div id="download" class="col-2">
		<?php	
			if(!$offline && $database->is_loggedin())
				if($web_admin>=$jsondataPrivileges['news'])
					include 'include/functions/edit-news.php';
		?>
    <div class="content content-last">
        <div class="content-bg"><div class="content-bg-bottom">
            <h2 class="pre-social"><?php print $article['title']; ?>
						<?php
							if(!$offline && $database->is_loggedin())
								if($web_admin>=$jsondataPrivileges['news'])
								{
						?>
						<a href="<?php print $site_url; ?>?delete=<?php print $read_id; ?>" onclick="return confirm('<?php print $lang['sure?']; ?>');"><i style="color:red;" class="fa fa-trash-o fa-2" aria-hidden="true"></i></a>
						<?php
								}
						?></h2>
			<?php if($offline) print '</br><h2 class="pre-social"><strong><font color="red">'.$lang['server-offline'].'</font></strong></h2>' ?>
	<div class="download-inner-content">
		<div class="post fade_in" data-page="1">
			<div class="post_content">
				<?php print $article['content']; ?>						
			</div>
			<div class="post_date pull-right"><?php print $article['time']; ?></div>
		</div>
	</div>
</div>
        </div>
    </div>
    </div>