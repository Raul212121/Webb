<div id="download" class="col-2">
			<div class="content content-last" style="background-image: url(<?php print $site_url; ?>images/user.png)">
				<div class="content-bg"><div class="content-bg-bottom">
					<h2 class="pre-social"><?php print $lang['register']; ?></h2>
		<div class="download-inner-content">
		<?php if($jsondataFunctions['active-registrations']==1) { ?>
            <form role="form" method="post" action="">
				<?php
					include 'include/functions/register.php';
					
					require 'include/captcha/simple-php-captcha.php';
					$_SESSION['captcha'] = simple_php_captcha();
				?>
				<table class="table table-hover">
					<tbody>
						<tr>
							<td><?php print $lang['user-name']; ?>:</td>
							<td><input class="form-control" name="username" id="username" pattern=".{5,16}" maxlength="16" pattern="[A-Za-z0-9]" placeholder="<?php print $lang['user-name']; ?>..." required="" type="text" autocomplete="off">
							<p class="text-danger" id="checkname"></p>
							</td>
						</tr>
						<tr>
							<td><?php print $lang['password']; ?>:</td>
							<td><input class="form-control" name="password" id="password" pattern=".{5,16}" maxlength="16" placeholder="<?php print $lang['password']; ?>" required="" type="password"></td>
						</tr>
						<tr>
							<td><?php print $lang['rpassword']; ?>:</td>
							<td><input class="form-control" name="rpassword" id="rpassword" pattern=".{5,16}" maxlength="16" placeholder="<?php print $lang['password']; ?>" required="" type="password">
							<p class="text-danger" id="checkpassword"></p>
							</td>
						</tr>
						<tr>
							<td><?php print $lang['email-address']; ?>:</td>
							<td><input class="form-control" name="email" id="email" pattern=".{7,64}" maxlength="64" placeholder="ex@test.com" required="" type="email">
							</td>
						</tr>
						<tr>
							<td><?php print '<img src='.$_SESSION['captcha']['image_src'].'>'; ?></td>
							<td><input class="form-control" name="captcha" pattern=".{4,6}" maxlength="5" placeholder="<?php print $lang['captcha-code']; ?>" required="" type="text"></td>
						</tr>
					</tbody>
				</table>
				<hr>
				<input type="submit" value="<?php print $lang['register']; ?>" class="btn btn-danger btn-lg btn-block" tabindex="7">
            </form>
		<?php } else { ?>
			<div class="alert alert-info" role="alert">
				<strong>Info!</strong> <?php print $lang['disabled-registrations']; ?>
			</div>
		<?php } ?>
        </div>
</div>
				</div>
			</div>
			</div>