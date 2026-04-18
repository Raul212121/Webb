<?php
	//Json settings
	function getJsonSettings($v2, $v1="general")
	{
		global $jsondata;
		global $jsondataRanking;
		global $jsondataStats;
		
		if($v2)
			if($v1=="top10backup")
				return $jsondataRanking[$v1][$v2];
			else if($v1=="stats5backup")
				return $jsondataStats[$v1][$v2];
			else
				return $jsondata[$v1][$v2];
		else
			return $jsondata[$v1];
	}