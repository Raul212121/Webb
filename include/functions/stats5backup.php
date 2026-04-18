<?php
	$timeFirst  = strtotime($stats5backup_day."-".$stats5backup_month."-".$stats5backup_year." ".$stats5backup_hours.":".$stats5backup_minutes.":".$stats5backup_seconds);
	
	$timeSecond = strtotime(date("Y-n-j H:i:s"));
	$differenceInSeconds = $timeSecond - $timeFirst;
	
	$diff = 5 * 60;
	
	if($differenceInSeconds>0 && $differenceInSeconds>=$diff)
	{
		$json = file_get_contents('include/db/stats.json');
		$date = json_decode($json,true);
		
		$date['stats5backup']['day'] = date("j");
		$date['stats5backup']['month'] = date("n");
		$date['stats5backup']['year'] = date("Y");
		$date['stats5backup']['hours'] = date("H");
		$date['stats5backup']['minutes'] = date("i");
		$date['stats5backup']['seconds'] = date("s");
		
		foreach($jsondataFunctions as $key => $status)
			if(!in_array($key, array('active-registrations', 'players-debug', 'active-referrals')))
			{
				if($status)
					$date['stats5backup']['stats'][$key] = getStatistics($key);
				else $date['stats5backup']['stats'][$key] = 0;
			}

		$top = [];
		$top = top10players();
		$i = 0;
		$x = "";
		$date['stats5backup']['players'] = [];
		foreach($top as $player)
		{
			$empire=get_player_empire($player['account_id']);
			$i++;
			if($i==10)
				$x=" style='margin-left: -6px;'";
			$zF = ($i%2==0) ? "light" : "";
			
			$date['stats5backup']['players'][] =  "<ul>
			<li class =\"$zF\"><strong".$x.">". $i ."</strong> - <a class=\"first\">".$player["name"]."</a> 
			
			<span class='top_emp'><img width='22' src='".$site_url."images/empire/".$empire.".jpg' alt='".emire_name($empire)."' title='".emire_name($empire)."'></span>
			<span class='top_lvl'>".$player['level']."</span></li>
			</ul>";
		}

		$top = array();
		$top = top10guilds();
		$i = 0;
		$x = "";
		$date['stats5backup']['guilds'] = [];
		foreach($top as $guild)
		{
			$empire=get_guild_empire($guild['master']);
			$i++;
			if($i==10)
				$x=" style='margin-left: -6px;'";
			$zF = ($i%2==0) ? "light" : "";
			$date['stats5backup']['guilds'][] =  "<ul>
			<li class =\"$zF\"><strong".$x.">". $i ."</strong> - <a class=\"first\">".$guild["name"]."</a> 
			
			<span class='top_emp_guild'><img width='22' src='".$site_url."images/empire/".$empire.".jpg' alt='".emire_name($empire)."' title='".emire_name($empire)."'></span>
			<span class='top_lvl'>".$guild['level']."</span></li>
			</ul>";
		}
		
		$json_new = json_encode($date);
	
		file_put_contents('include/db/stats.json', $json_new);
	}
?>