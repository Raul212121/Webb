<div id="download" class="col-2">
	<div class="content content-last" style="background-image: url(<?php print $site_url; ?>images/user.png)">
		<div class="content-bg"><div class="content-bg-bottom">
			<h2 class="pre-social"><?php print $lang['donate']; ?></h2>
	<div class="download-inner-content">
		<?php
		if(isset($_POST['id']) && isset($_POST['type']) && isset($_POST['code']) && strlen($_POST['code']) >= 3 && strlen($_POST['code']) <= 50)
		{
			if(isset($jsondataDonate[$_POST['id']]['list'][$_POST['type']]))
			{
				$price = $jsondataDonate[$_POST['id']]['list'][$_POST['type']];
				$type = $jsondataDonate[$_POST['id']]['name'].' ['.$price['price'].' '.$jsondataCurrency[$price['currency']]['name'].' - '.$price['md'].' MD]';
				
				insert_donate($_SESSION['id'], $_POST['code'], $type);
			
				print '<div class="alert alert-success alert-dismissible fade in" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>';
				print $lang['send-donate'];
				print '</div>';	
			}
		}
		if(count($jsondataDonate)) { ?>
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		<?php $i=-1; foreach($jsondataDonate as $key => $donate) { $i++; ?>
			<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="heading<?php print $i; ?>">
					<h4 class="panel-title">
						<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php print $i; ?>" aria-expanded="false" aria-controls="collapse<?php print $i; ?>">
							<?php print $donate['name']; ?>
						</a>
					</h4>
				</div>
				<div id="collapse<?php print $i; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php print $i; ?>">
					<div class="panel-body">
					<?php 
						if(strtolower($donate['name'])=="paypal")
						{
					?>
						<form action="" method="post">
							<input type="hidden" name="id" value="<?php print $i; ?>">
							<input type="hidden" name="method" value="<?php print $donate['name']; ?>">
							<div class="form-group row">
								<div class="col-sm-6">
									<select class="form-control" name="type">
									<?php $j=-1; foreach($jsondataDonate[$i]['list'] as $key => $price) { $j++; ?>
										<option value="<?php print $j; ?>"><?php print $lang['price'].' '.$price['price'].' '.$jsondataCurrency[$price['currency']]['name'].' - '.$price['md'].' MD'; ?></option>
									<?php } ?>
									</select>
								</div>
								<div class="col-sm-6">
									<button type="submit" name="submit" class="btn btn-primary"><?php print $lang['send']; ?></button>
								</div>
							</div>
						</form>
					<?php } else { ?>
						<form action="" method="post">
							<input type="hidden" name="id" value="<?php print $i; ?>">
							<input type="hidden" name="method" value="<?php print $donate['name']; ?>">
							<div class="form-group row">
								<div class="col-sm-6">
									<select class="form-control" name="type">
									<?php $j=-1; foreach($jsondataDonate[$i]['list'] as $key => $price) { $j++; ?>
										<option value="<?php print $j; ?>"><?php print $lang['price'].' '.$price['price'].' '.$jsondataCurrency[$price['currency']]['name'].' - '.$price['md'].' MD'; ?></option>
									<?php } ?>
									</select>
								</div>
								<div class="col-sm-6">
									<input type="text" class="form-control" max="50" name="code" placeholder="<?php print $lang['code']; ?>">
								</div>
							</div>
							<div class="form-group row">
								<button type="submit" name="submit" class="btn btn-primary"><?php print $lang['send']; ?></button>
							</div>
						</form>
					<?php } ?>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
		<?php } else { ?>
		<div class="alert alert-info" role="alert">
			<strong>Info!</strong> Donate methods not found.
		</div>
		<?php } ?>
	</div>
</div>
		</div>
	</div>
	</div>