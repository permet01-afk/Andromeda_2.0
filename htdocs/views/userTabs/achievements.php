<?php

$npc_names = ['Streuner'
, 'Lordakia'
, 'Saimon'
, 'Sibelon'
, 'Kristallin'
, 'Kristallon'
, 'Cubikon'
, 'IceMeteroid'
, 'Melter'
, 'Scorcher'
, 'BossCurcubitor'
, 'Hitac'
, 'Devourer'
, 'BossKuKu'
, 'Saboteur'
, 'Annihilator'
, 'Battleray'
];

//k,r, cred, uri, npc_points
$npc_data['Streuner'] = [15, 0.10, 72000, 54, 1];
$npc_data['Lordakia'] = [15, 0.10, 160000, 120, 2];
$npc_data['Saimon'] = [15, 0.10, 240000, 180, 3];
$npc_data['Kristallin'] = [15, 0.10, 480000, 360, 6];

$npc_data['Sibelon'] = [10, 0.10, 1520000, 1040, 19];
$npc_data['Kristallon'] = [10, 0.10, 2240000, 1680, 28];
$npc_data['Melter'] = [10, 0.10, 4800000, 3600, 60];
$npc_data['Scorcher'] = [10, 0.10, 2800000, 2100, 35];

$npc_data['Saboteur'] = [10, 0.15, 7040000, 5280, 88];
$npc_data['Annihilator'] = [10, 0.15, 14080000, 10560, 176];

$npc_data['Cubikon'] = [10, 0.05, 8000000, 6000, 100];
$npc_data['IceMeteroid'] = [10, 0.05, 48000000, 36000, 600];
$npc_data['BossCurcubitor'] = [10, 0.05, 9600000, 7200, 120];
$npc_data['BossKuKu'] = [10, 0.05, 164000000, 75000, 1250];

$npc_data['Hitac'] = [5, 0.10, 100000000, 87000, 1500];
$npc_data['Devourer'] = [5, 0.10, 140000000, 116000, 2100];
$npc_data['Battleray'] = [5, 0.10, 240000000, 180000, 3250];


if(isset($_GET['claim']))
{
	if(in_array($_GET['claim'],$npc_names))
	{
		$buymessage = handleClaim($db, $_GET['claim'], $npc_data ); 
	}
}

$sth = $db->prepare("SELECT * FROM users_npc_counts WHERE id = :id LIMIT 1");
$sth->execute(array(
				':id' => $_SESSION['player_id']
			));
$datauser_count = $sth->fetchAll();
$npc_count = $datauser_count[0];

$sth = $db->prepare("SELECT * FROM users_npc_lvl WHERE id = :id LIMIT 1");
$sth->execute(array(
				':id' => $_SESSION['player_id']
			));
$datauser_lvl = $sth->fetchAll();
$npc_lvl = $datauser_lvl[0];

$achievements_points = 0;
foreach ($npc_names as $npc_name)
{
    $achievements_points += $npc_lvl[$npc_name];
}
$total_achievements_points =  count($npc_names)*10;

?>
	
<link rel="stylesheet" type="text/css" href="styles/achievements.css" />
<div class="box" style="margin-left:-80px;margin-bottom:20px;">
	<div class="title">Achievements</div>
	<div id="achievements">	
			</br>
			<div class="stat" style="width: 500px; margin-left: 50px;"><div class="stat-left" style="width: 200px;">Achievement's points</div><div class="stat-right"><?=$achievements_points?>/<?=$total_achievements_points?></div></div>
            <div<strong><a href="http://www.andromeda-server.com/forum/viewtopic.php?f=6&t=585" target="_blank"><font color='#FFA500'>Click here to get All Rewards Infos</a>  </strong><br/> </div>			</br>
			<?php
			foreach ($npc_names as $npc_name)
			{
				print_npc_achivement($npc_name, $npc_lvl, $npc_count, $npc_data);
			}
			?>
	</div>
</div>		
<?php 
function get_lvl_npc_count($k,$n)
{
	if($n == 0)
	{
		return 0;
	}
	else
	{
		return $k * pow(2,$n);
	}
}

function get_cumulative_lvl_npc_count($k,$n)
{
	if($n == 0)
	{
		return 0;
	}
	else
	{
		return get_lvl_npc_count($k,$n) + get_cumulative_lvl_npc_count($k,$n-1);
	}
}



function handleClaim($db, $npc_name, $npc_data )
{
	$sth = $db->prepare("SELECT $npc_name FROM users_npc_counts WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser_count = $sth->fetchAll();
	$npc_count = $datauser_count[0][$npc_name];

	$sth = $db->prepare("SELECT $npc_name FROM users_npc_lvl WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser_lvl = $sth->fetchAll();
	$npc_lvl = $datauser_lvl[0][$npc_name];
	
	$actual_count = $npc_count - get_cumulative_lvl_npc_count($npc_data[$npc_name][0],$npc_lvl);
	$goal = get_lvl_npc_count($npc_data[$npc_name][0],$npc_lvl+1);
	
	if($actual_count < $goal)
	{
		return "Can not claim prize, not enough kills";
	}	

	
	$cred = ($npc_data[$npc_name][1] * $npc_data[$npc_name][2] * $goal);
	$uri = ($npc_data[$npc_name][1] * $npc_data[$npc_name][3] * $goal);
	$rp = ($npc_data[$npc_name][1] * $npc_data[$npc_name][4] * $goal);
	
	$req = $db->prepare('UPDATE users SET uridium=uridium+'.$uri.',credits=credits+'.$cred.',rankpoints=rankpoints+'.$rp.' WHERE id='.$_SESSION['player_id']);
	$req->execute();
	
	$req = $db->prepare('UPDATE users_npc_lvl SET '.$npc_name.'='.$npc_name.'+1 WHERE id='.$_SESSION['player_id']);
	$req->execute();
	
	$unlocked_lvl = $npc_lvl+1;	
	return "$npc_name level $unlocked_lvl reward unlocked:</br>$cred credits, $uri uridiums </br>and $rp rankpoints";
}

function print_npc_achivement($npc_name,$npc_lvl, $npc_count, $npc_data)
{
	echo '<div class="bar-stat">';
		echo '<div class="bar-stat-title tooltip">';
			echo $npc_name;
		echo '</div>';
		echo '<div class="bar-stat-content" style="width: 560px;">';
			echo '<div class="bar-stat-content-bar" style="width: 340px;">';
				create_bar(10, $npc_lvl[$npc_name], 32, 10);
			echo '</div>';
			echo '<div class="bar-stat-content-number" style="width: 80px;">';
			echo $npc_lvl[$npc_name];
			echo '/10</div>';
			echo '<div class="bar-stat-content-number" style="width: 130px;">';
			
			$actual_count = $npc_count[$npc_name] - get_cumulative_lvl_npc_count($npc_data[$npc_name][0],$npc_lvl[$npc_name]);
			$goal = get_lvl_npc_count($npc_data[$npc_name][0],$npc_lvl[$npc_name]+1);
			if($actual_count < $goal)
			{
				echo 'Progress: ';
				echo $actual_count;
				echo '/';
				echo $goal;
			}
			else
			{
				echo '<a class="claim" href="view.php?page=user&tab=achievements&claim='.$npc_name.'">';
					echo 'Claim Prize';

				echo '</a>';
			}
			echo '</div>';
		echo '</div>';
	echo '</div>';
}

function create_bar($size, $progress, $elementWidth, $elementHeight)
{
	$i=0;
	while($i < $progress)
	{
		echo '<div class="barUp" style="width: '.$elementWidth.'px; height: '.$elementHeight.'px;"></div>';
		$i++;
	}
	while($i < $size)
	{
		echo '<div class="barDown" style="width: '.$elementWidth.'px; height: '.$elementHeight.'px;"></div>';
		$i++;
	}
}
?>