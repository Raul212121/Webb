<?php
	function getSocialLinks()
	{
		global $social_links;

		$html = '';
		foreach($social_links as $social => $link)
			if($link)
				$html = $html . '<a href="'. $link .'" target="_blank" title="'. ucfirst($social) .'" class="'. $social .'"></a> ';

		return $html;
	}