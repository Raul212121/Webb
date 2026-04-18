<div class="col-2">

    <div class="content banner">
        <div class="content-bg">
            <div class="content-bg-bottom">
                <!--<a id='various5' href="https://metin2.ie" target="_blank"></a>-->
                <div id="slidedom" style="width:480px;height:147px; background-color:transparent">
                    <center><a href="http://casidie.ro"><img src="img/banner/ro/01.jpg" vspace="3" border="0"></a></center>
                </div><br>
				<?php
					if(!$offline && $database->is_loggedin())
						if($web_admin>=$jsondataPrivileges['news'])
							include 'include/functions/add-news.php';
				?>
            </div>
        </div>
    </div>
    <div class="shadow"> </div>
				<?php
				if (version_compare($php_version = phpversion(), '5.6.0', '<')) {
				?>
				<div class="alert alert-danger alert-dismissible fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<span>Metin2CMS works with a PHP version >= 5.6.0. At this time, the server is running version <?php print $php_version; ?>.</span>
				</div>
				<?php
				}
					$query = "SELECT * FROM news ORDER BY id DESC";
					$records_per_page=intval(getJsonSettings("news"));
					$newquery = $paginate->paging($query,$records_per_page);
					$paginate->dataview($newquery, $lang['sure?'], $web_admin, $jsondataPrivileges['news'], $site_url, $lang['read-more']);
					$paginate->paginglink($query,$records_per_page,$lang['first-page'],$lang['last-page'],$site_url);		
				?>

</div>

