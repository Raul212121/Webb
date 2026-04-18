<?php
	$helloworld = file_get_contents_curl('http://metin2cms.cf/salut.php?lang='.$language_code, 2, 5);
	if($helloworld)
		print '<div class="alert alert-info alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$helloworld.'</div>';
	else
		print '<div class="alert alert-danger fade in" role="alert">'.$lang['https-get-contents-error'].' <a href="https://piwik.org/faq/troubleshooting/faq_177/" target="_blank">Piwik</a> | <a href="https://stackoverflow.com/search?q=Unable+to+find+the+wrapper+%22https%22" target="_blank">StackOverflow</a></div>';
?>



