<?php 
$sth = $db->prepare("SELECT tokens, tickets 
 FROM users_infos WHERE id = :id LIMIT 1");
$sth->execute(array(
				':id' => $_SESSION['player_id']
			));
$datauser = $sth->fetchAll();

$tokens = $datauser[0]['tokens'];
$tickets = $datauser[0]['tickets'];

$sth = $db->prepare("SELECT username, grade, factionid, clanid, credits, uridium, rankpoints, user_kill, npc_kill, max_hp, speed, damages, 
max_shield, drones, apis_built, zeus_built, dmg_lvl, hp_lvl, shd_lvl, speed_lvl, logfiles, booty_keys, drone_parts, skilltree, booster_dmg_time,
booster_shd_time, booster_spd_time, booster_npc_time, shipId 
 FROM users WHERE id = :id LIMIT 1");
$sth->execute(array(
				':id' => $_SESSION['player_id']
			));
$datauser = $sth->fetchAll();

if($datauser[0]['clanid'] != 0)
{
	$sth = $db->prepare("SELECT clan_name, clan_tag FROM clan WHERE id = :clanid LIMIT 1");
	$sth->execute(array(
					':clanid' => $datauser[0]['clanid'] 
				));
	$dataclan = $sth->fetchAll();
	$userclan = '['.$dataclan[0]['clan_tag'].']'.$dataclan[0]['clan_name'];
}
else
{
	$userclan = 'No clan';
}

$rank_name = array(1 => "Basic Space Pilot", 2 => "Space Pilot", 3 => "Chief Space Pilot", 4 => "Basic Sergeant", 5 => "Sergeant", 6 => "Chief Sergeant",
									7 => "Basic Lieutenant", 8 => "Lieutenant", 9 => "Chief Lieutenant", 10 => "Basic Captain", 11 => "Captain", 12 => "Chief Captain",
									13 => "Basic Major", 14 => "Major", 15 => "Chief Major", 16 => "Basic Colonel", 17 => "Colonel", 18 => "Chief Colonel", 
									19 => "Basic General", 20 => "General", 21 => "Game Administrator", 22 => "Outlaw");
	
if($datauser[0]['grade'] < 20)
{	
	$rank_after = $db->prepare("SELECT rankpoints, grade FROM users WHERE grade > ".$datauser[0]['grade']." AND factionid=".$datauser[0]['factionid']." ORDER BY rankpoints ASC LIMIT 1");
	$rank_after->execute();
	$data_rank = $rank_after->fetchAll();

	$nextrankindex = $datauser[0]['grade']+1;
	$nextrankpoints = number_format($data_rank[0]['rankpoints']);
}
else
{
	$nextrankindex = $datauser[0]['grade'];
	$nextrankpoints = 'You are the KING';	
}

?>
<div class="box">
	<div class="title">User informations</div>
	<div id="user-infos">
		<div class="stat"><div class="stat-left">Username</div><div class="stat-right"><?=$datauser[0]['username']?></div></div>
		<div class="stat"><div class="stat-left">Clan</div><div class="stat-right"><?=$userclan?></div></div>
		<div class="stat"><div class="stat-left">Company</div><div class="stat-right"><img src="img/ranks/company/<?=$datauser[0]['factionid']?>.png"></div></div>
		<div class="stat"><div class="stat-left">Grade</div><div class="stat-right"><img src="img/ranks/<?=$datauser[0]['grade']?>.png">-<?=$rank_name[$datauser[0]['grade']]?></div></div>
		<div class="stat"><div class="stat-left">Next Grade</div><div class="stat-right"><img src="img/ranks/<?=$nextrankindex?>.png">-<?=$nextrankpoints?></div></div>
		<div class="stat"><div class="stat-left">Rankpoints</div><div class="stat-right"><?=number_format($datauser[0]['rankpoints'])?></div></div>
		<div class="stat"><div class="stat-left">Players kills</div><div class="stat-right"><?=number_format($datauser[0]['user_kill'])?></div></div>
		<div class="stat"><div class="stat-left">Npc points</div><div class="stat-right"><?=number_format($datauser[0]['npc_kill'])?></div></div>
		<div class="stat"><div class="stat-left">Credits</div><div class="stat-right"><?=number_format($datauser[0]['credits'])?></div></div>
		<div class="stat"><div class="stat-left">Uridium</div><div class="stat-right"><?=number_format($datauser[0]['uridium'])?></div></div>
		<div class="stat"><div class="stat-left">Logfiles</div><div class="stat-right"><?=number_format($datauser[0]['logfiles'])?></div></div>
		<div class="stat"><div class="stat-left">Booty keys</div><div class="stat-right"><?=number_format($datauser[0]['booty_keys'])?></div></div>
		<div class="stat"><div class="stat-left">Drone parts</div><div class="stat-right"><?=number_format($datauser[0]['drone_parts'])?></div></div>
		<div class="stat"><div class="stat-left">Tokens</div><div class="stat-right"><?=$tokens?></div></div>
		<div class="stat"><div class="stat-left">Lottery's tickets</div><div class="stat-right"><?=$tickets?></div></div>
		<br>
	</div>
</div>

<div class="box" style="margin-left: 40px;">
	<div class="title">Ship informations</div>
	<div id="user-ship">
		<center><img src="img/ship/<?=number_format($datauser[0]['shipId'])?>.png" /></center>
		</br>
		<div class="stat"><div class="stat-left">Health points</div><div class="stat-right" style="color: green; font-weight: bold;"><?=number_format($datauser[0]['max_hp'])?></div></div>
		<div class="stat"><div class="stat-left">Shield points</div><div class="stat-right" style="color: #299E9E; font-weight: bold;"><?=number_format($datauser[0]['max_shield'])?></div></div>
		<div class="stat"><div class="stat-left">Damages points</div><div class="stat-right" style="color: red; font-weight: bold;"><?=number_format($datauser[0]['damages'])?></div></div>
		<div class="stat"><div class="stat-left">Speed</div><div class="stat-right" style="color: #B01AB0; font-weight: bold;"><?=number_format($datauser[0]['speed'])?></div></div>
	</div>
</div>	