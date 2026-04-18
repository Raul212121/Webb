<?php

	@ob_start();

	include 'include/functions/header.php';

?>

    <!DOCTYPE html>

    <html lang="<?php print $language_code; ?>">



    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title>

            Metin2 - Server Privat

        </title>



        <link rel="shortcut icon" href="<?php print $site_url; ?>img/favicon/favicon.ico" type="image/x-icon" />

        <link href="<?php print $site_url; ?>css/reset.css" rel="stylesheet" type="text/css" media="all" />

        <link href="<?php print $site_url; ?>css/all.css" rel="stylesheet" type="text/css" media="all" />

        <!--[if lt IE 7]><link rel="stylesheet" type="text/css" href="<?php print $site_url; ?>css/IE/ie6.css" media="screen"/><![endif]-->

        <!--[if gte IE 6]><link rel="stylesheet" type="text/css" href="<?php print $site_url; ?>css/IE/ie7.css" media="screen"/><![endif]-->

		

		

		<link href="<?php print $site_url; ?>css/font-awesome.min.css" rel="stylesheet">

		<link rel="stylesheet" href="<?php print $site_url; ?>css/bootstrap.css">

		<link rel="stylesheet" href="<?php print $site_url; ?>css/bootstrap-table.css">

		<?php if($page=="admin" && $a_page=="player_edit") { ?>

		<link rel='stylesheet' href='<?php print $site_url; ?>css/bootstrap-select.css'/>

		<?php } ?>

    

		<script src="https://www.google.com/recaptcha/api.js" async defer></script>

	</head>



    <body class="netbar">

		<?php

			if($item_shop!="")

				$shop_url = $item_shop;

			else if(is_dir('shop')) 

				$shop_url = $site_url.'shop'; 

			else $shop_url = ''; 

		?>

        <div id="pagefoldtarget"></div>

        <div id="mmonetbar" class="mmometin2">

            <div id="mmoContent" class="mmosmallbar">

                <div id="mmoGame" class="mmoGame">

                    <div class="mmoBoxLeft"></div>

                    <div class="mmoBoxMiddle">

                        <div id="mmoLangs">

                            <label><?php print $lang['default-language']; ?>:</label>

                            <div id="mmoLangSelect" class="mmoSelectbox">

                                <div id="mmoSarea1" onclick="mmoShowOptions(1)" class="mmoSelectArea">

                                    <div class="mmoSelectText" id="mmoMySelectContent1">

                                        <div id="mmoMySelectText1" class="mmoflag mmo_<?php print strtoupper($language_code); ?>"><?php print $json_languages['languages'][$language_code]; ?></div>

                                    </div>

                                    <div class="mmoSelectButton"></div>

                                </div>

                                <div class="mmoOptionsDivInvisible" id="mmoOptionsDiv1">

                                    <ul class="mmoLangList mmoListHeight" id="mmoList1">

                                        <li class="mmoActive"><a class="mmoflag mmo_<?php print strtoupper($language_code); ?>"><?php print $json_languages['languages'][$language_code]; ?></a></li>

										<?php

											foreach($json_languages['languages'] as $key => $value)

												if($key!=$language_code)

													print '<li><a href="'.$site_url.'?lang='.$key.'" class="mmoflag mmo_'.strtoupper($key).'">'.$value.'</a></li>';

										?>

                                    </ul>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="mmoBoxRight"></div>

                </div>

                <input id="mmoFocus" type="text" size="5" />

            </div>

        </div>

		<div id="page">

            <div class="header-wrapper">

                <div id="header"><a class="logo" style="margin-top:45;" href="<?php print $site_url; ?>"></a>



<?php if(!$database->is_loggedin()) { ?>

	<div class="header-box">

	<div id="regBtn"> 

		<a id="toReg" href="<?php print $site_url; ?>download"><?php print $lang['download'].' '.$site_title; ?>!</a>

		<div id="regSteps"><a href="<?php print $site_url; ?>users/register"><span>1. <?php print $lang['register']; ?> </span> >> <span>2. <?php print $lang['download']; ?> </span> >> <span>3. <?php print $lang['login']; ?></span></a>

</div>

</div>

<?php

		}

		else {

print "<center>";

?>

<div id="userBox">

<br>

<?php

 print "<div style=\"position=:bottom; margin-left:-155px; align=\"left\"><h4>".getAccountName($_SESSION['id'])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$lang['md'].": ".getAccountMD($_SESSION['id'])."</h4></div>";

?>

<ul class="header-box-nav-container" style="position=:left; margin-left:-153px;">

<?php if($web_admin) { ?>

<li class="stepdown"><a id="example4" href="<?php print $site_url; ?>admin" class="nav-box-btn nav-box-btn-1"><?php print $lang['administration']; ?></a></li>

<?php } else { ?>

<li class="stepdown"><a id="example4" href="<?php print $site_url; ?>user/vote4coins" class="nav-box-btn nav-box-btn-1"><?php print $lang['donate']; ?></a></li>

<?php } ?>

<li class="stepdown"><a href="<?php print $site_url; ?>user/administration" class="nav-box-btn nav-box-btn-2"><?php print $lang['account-data']; ?></a></li>

<li class="stepdown"><a href="<?php print $site_url; ?>users/logout" class="nav-box-btn nav-box-btn-4"><?php print $lang['logout']; ?></a></li>

</ul>

<?php

		

}

?>



                </div>

            </div>

        </div>

        <!-- left column - navigation -->

        <div class="container-wrapper">

            <div class="container">

                <div class="col-1">

                    <div class="boxes-top">&nbsp;</div>

                    <div class="modul-box">

                        <div class="modul-box-bg">

                            <div class="modul-box-bg-bottom">

                                <!-- main navigation -->

                                <ul class="main-nav">

									<li><a href="<?php print $site_url; ?>news"><?php print $lang['news']; ?></a></li>

									<?php if(!$database->is_loggedin()) { ?>

									<li><a href="<?php print $site_url; ?>download"><?php print $lang['download']; ?></a></li>

									<li><a href="<?php print $site_url; ?>users/login"><?php print $lang['login']; ?></a></li>

									<?php } else { ?>

									<li><a href="<?php print $site_url; ?>user/administration"><?php print $lang['account-data']; ?></a></li>

											<?php if($web_admin) { ?>

											<li><a href="<?php print $site_url; ?>admin"><?php print $lang['administration']; ?></a></li>

											<?php 

												if($web_admin>=$jsondataPrivileges['donate']) {

													$count_donations = count(get_donations());

													if($count_donations)

													{

											?>	

											<li><a href="<?php print $site_url; ?>admin/donatelist"><?php print $lang['donatelist']; ?> <span class="tag tag-info tag-pill float-xs-right"><?php print $count_donations.' '.$lang['new-donations']; ?> </span></a></li>

											<?php

													}

												}

											}

											?>

											<li><a href="<?php print $site_url; ?>user/characters"><?php print $lang['chars-list']; ?></a></li>

											<li><a href="<?php print $site_url; ?>user/redeem"><?php print $lang['redeem-codes']; ?></a></li>

											<?php if($jsondataFunctions['active-referrals']) { ?>

											<li><a href="<?php print $site_url; ?>user/referrals"><?php print $lang['referrals']; ?></a></li>

											<?php }

												$vote4coins = file_get_contents('include/db/vote4coins.json');

												$vote4coins = json_decode($vote4coins, true);

												

												if(count($vote4coins))

													print '<li><a href="'.$site_url.'user/vote4coins">Vote4Coins</a></li>';

												

												$donate = file_get_contents('include/db/donate.json');

												$donate = json_decode($donate, true);

												

												if(count($donate))

													print '<li><a href="'.$site_url.'user/donate">'.$lang['donate'].'</a></li>';

											?>

									</li>

									<?php } ?>

									<li><a href="<?php print $forum; ?>" target="_blank">Forum</a></li>

									<li class="btn-download">

									<?php if(!$database->is_loggedin()) { ?>

										<a href="<?php print $site_url; ?>users/register"><?php print $lang['register']; ?></a>

									<?php } else { ?>

										<a href="<?php print $site_url; ?>download"><?php print $lang['download']; ?></a>

									<?php } ?>

									</li>

									<li><a role="button" data-toggle="collapse" href="#collapseRanking" aria-expanded="false" aria-controls="collapseRanking"><?php print $lang['ranking']; ?></a>

										<ul class="collapse" id="collapseRanking">

											<li><a href="<?php print $site_url; ?>ranking/players"><?php print $lang['players']; ?></a></li>

											<li><a href="<?php print $site_url; ?>ranking/guilds"><?php print $lang['guilds']; ?></a></li>

										</ul>

									</li>

                                </ul>

                            </div>

                        </div>

                    </div>

					<?php if(!$offline && $statistics) { ?>

                    <div class="boxes-middle">&nbsp;</div>

                    <div class="modul-box modul-box-2">

                        <div class="modul-box-bg">

                            <div class="modul-box-bg-bottom">

                                <h3>Informa&#355;ii</h3>

								<div class="form-score" style="width: 138px;">

									<div id="highscore-player">

									<?php

									$i = 0;

									$stats = getJsonSettings("stats", "stats5backup");

									foreach($jsondataFunctions as $key => $status)

										if(!in_array($key, array('active-registrations', 'players-debug', 'active-referrals')) && $status)

										{

											$i++;

											print "<ul>

											<li><center><a class=\"first\">". $lang[$key] .":</a></center></li>

											<li class ='light'><center><strong>". number_format($stats[$key], 0, '', '.') ."</strong></center></li>

											</ul>";

										}

									?>	

									</div>

								   </div>

							</div>

                        </div>

                    </div>

					<?php } ?>

                    <div class='boxes-bottom'></div>

                </div>

                <!-- center column -->



			<?php

				include 'pages/'.$page.'.php';

			?>

			<div class="col-3">

			<div class="boxes-top">&nbsp;</div>

<?php

	if($offline || !$database->is_loggedin()) {

?>

<div class="modul-box">

<div class="modul-box-bg">

<div class="modul-box-bg-bottom">

		<h3><?php print $lang['user-panel']; ?></h3>

	<form action="<?php print $site_url; ?>users/login" method="post">

<div class="form-login">

		<label><?php print $lang['user-name-or-email']; ?></label>

<div class="input">

		<input type="text" name="username" pattern=".{5,64}" maxlength="64" autocomplete="off" <?php if($offline) print 'disabled'; else print 'required'; ?>>

<br>

</div>

		<label><?php print $lang['password']; ?></label>

<div class="input">

		<input type="password" name="password" pattern=".{5,16}" maxlength="16" <?php if($offline) print 'disabled'; else print 'required'; ?>>

<br>

</div>

<div class="input">

		<div class="g-recaptcha" data-theme="dark" data-sitekey="<?php print $site_key; ?>" style="transform: scale(0.43); -webkit-transform: scale(0.43); transform-origin: 0 0; -webkit-transform-origin: 0 0; height: 80px;"></div>

<br>

</div>

<div><br>

		<input type="submit" class="button btn-login" name="login" value="<?php print $lang['login']; ?>">

		<?php if(!$offline) { ?>

		<p class="agbok"><br>

			<a href="<?php print $site_url; ?>users/lost" class="password"><?php print $lang['forget-password']; ?></a>

		</p>

		<?php } ?>

</div>

</div>

</form>

</div>

</div>

</div>

<?php

	} else { ?>

<center>		

<div class='modul-box modul-box-2'>

<div class='modul-box-bg'>

<div class='modul-box-bg-bottom'>

<h3><?php print $lang['item-shop']; ?></h3><center>

<a id="various3" href="<?php print $shop_url; ?>" class="btn itemshop-btn"></a></div>

</div></div>

</center>		

	<?php } ?>

							<div class="boxes-middle">&nbsp;</div>

											<div class="modul-box modul-box-2">

								<div class="modul-box-bg">

									<div class="modul-box-bg-bottom">

										<h3><?php print $lang['download']; ?></h3>

										<a href="<?php print $site_url; ?>download" class="btn download-btn"></a> </div>

								</div>

							</div>

							<div class="boxes-middle">&nbsp;</div>

							<div class="modul-box modul-box-2">

								<div class="modul-box-bg">

									<div class="modul-box-bg-bottom">

								

								   <h3><?php print $lang['players']; ?></h3>



											<div class="form-score">

											<div id="highscore-player">

			<?php

				if(!$offline) {

					$top = [];

					$top = getJsonSettings("players", "stats5backup");

					

					foreach($top as $player)

						print $player;

				}

			?>

				</div></br>

			   <div align="left"><a style="padding-bottom: 0;" href="<?php print $site_url; ?>ranking/players" class="btn" rel="nofollow">Top 100</a></div>

				   </div>

				<h3 style="margin-top:0"><?php print $lang['guilds']; ?></h3>



											<div class="form-score">

											<div id="highscore-guild">

			<?php

				if(!$offline) {

					$top = [];

					$top = getJsonSettings("guilds", "stats5backup");



					foreach($top as $guild)

						print $guild;

				}

			?>

											</div>

											<br>

											<div align="left"><a style="padding-bottom: 0;" href="<?php print $site_url; ?>ranking/guilds" class="btn" rel="nofollow">Top 100</a></div><br/>

										</div>							

									</div>

								</div>

							</div>

							<div class="boxes-bottom"> </div>

						</div>

					</div>

				</div>

			</div>

			<!-- footer -->
			<div class="footer-wrapper">

				<div id="footer"> <a class="gameforge4d" href="<?php print $site_url; ?>" rel="nofollow"><?php print $site_title; ?></a>

							<ul>

						<li class="first">&copy; Copyright <?php

										$copyright_year = date('Y');

										if($copyright_year > 2018)

											print '2018 - '.$copyright_year;

										else print $copyright_year;

											print ' '.$site_title;

								?></li>

						<li>All Rights Reserved  </li>

						<li></li>

					</ul>

				</div>

			</div>

			<script type="text/javascript" src="<?php print $site_url; ?>js/jquery-2.2.4.min.js"></script>

			<script type="text/javascript" src="<?php print $site_url; ?>js/jquery.migrate.js"></script>

			<script type="text/javascript" src="<?php print $site_url; ?>js/featherlight.js"></script>

			<?php include 'include/functions/footer.php'; ?>

			<script src="<?php print $site_url; ?>js/tether.min.js"></script>

			<script src="<?php print $site_url; ?>js/bootstrap.min.js"></script>

        </body>

	</html>