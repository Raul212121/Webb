<style>
#download h3 {
    font-size: 16px!important;
}
#download a {
	text-decoration: none;
}
</style>
<div id="download" class="col-2">
    <div class="content content-last" style="background-image: url(<?php print $site_url; ?>images/user.png)">
        <div class="content-bg"><div class="content-bg-bottom">
            <h2 class="pre-social"><?php print $a_title; ?></h2>
	<div class="download-inner-content">
				<?php
					include 'pages/admin/'.$a_page.'.php';
				?>
		</div>
    </div>
        </div>
    </div>
    </div>