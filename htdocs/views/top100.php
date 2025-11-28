<?php 
$last_active_limit = time() - (3600*14*24);
if(empty($_GET['display']))
{
	$sth = $db->prepare("SELECT users.username, users.rankpoints as points, users.factionid, users.grade FROM users WHERE lastlogin > $last_active_limit AND (SELECT count(id) AS is_ban FROM bans WHERE bans.user_id = users.id AND bans.timestamp_expire > UNIX_TIMESTAMP() ) = 0  ORDER BY users.rankpoints DESC LIMIT 0, 100");
	$sth->execute();
	$to_display = $sth->fetchAll();
	
	$sth = $db->prepare("SELECT users.username, (users.rankpoints+users.legend_rankpoints) as points, users.factionid, users.grade FROM users WHERE lastlogin > $last_active_limit AND (SELECT count(id) AS is_ban FROM bans WHERE bans.user_id = users.id AND bans.timestamp_expire > UNIX_TIMESTAMP() ) = 0  ORDER BY points DESC LIMIT 0, 100");
	$sth->execute();
	$legendto_display = $sth->fetchAll();
}
else if($_GET['display'] == 'kills')
{
	$sth = $db->prepare("SELECT users.username, users.user_kill as points, users.factionid, users.grade FROM users WHERE lastlogin > $last_active_limit AND (SELECT count(id) AS is_ban FROM bans WHERE bans.user_id = users.id AND bans.timestamp_expire > UNIX_TIMESTAMP() ) = 0  ORDER BY users.user_kill DESC LIMIT 0, 100");
	$sth->execute();
	$to_display = $sth->fetchAll();
}
else 
{
	$sth = $db->prepare("SELECT users.username, users.npc_kill as points, users.factionid, users.grade FROM users WHERE lastlogin > $last_active_limit AND (SELECT count(id) AS is_ban FROM bans WHERE bans.user_id = users.id AND bans.timestamp_expire > UNIX_TIMESTAMP() ) = 0  ORDER BY users.npc_kill DESC LIMIT 0, 100");
	$sth->execute();
	$to_display = $sth->fetchAll();
}
?>
<script src="views/userTabs/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="styles/home.css?v=1" />
<div class="CMSContent">
	<div class="box" style="margin-left:200px;margin-bottom:20px;">
		<div class="title">Hall of fame </div>
		<div id="hof-large">
		<div id="hof-large-menu">
			<a class="hofLink" href="view.php?page=top100">Rankpoints</a>
			<a class="hofLink" href="view.php?page=top100&display=kills">Kills</a>
			<a class="hofLink" href="view.php?page=top100&display=npcpoints">Npc points</a>
			<a class="hofLink" href="view.php?page=top100&display=clan">Clans</a>
		</div>
		<?php 	
			if(!empty($_GET['display']) && $_GET['display'] == 'clan')
			{
				$sth = $db->prepare("SELECT id, clan_tag, clan_name, kill_count FROM clan ORDER BY kill_count DESC LIMIT 0, 100");
				$sth->execute();
				$data = $sth->fetchAll();
				
				$clan_ranking = array();

				$i = 0;
				foreach ($data as $clan) 
				{
					$i++;
					$j = $i % 2;
					?>	
					<div class="top10item<?=$j?>"><div class="top10index"><?=$i?>.</div><div class="top10company">-</div><div class="top10grade">[<?=$clan['clan_tag']?>]</div><div class="top10username"><?=$clan['clan_name']?></div><div class="top10points"><?=number_format($clan['kill_count'])?></div></div>
					<?php
					if($i >= 100)
					{
						break;
					}
				}
			}
			else
			{
				if(empty($_GET['display']))
				{
					?>	
					<ul class="tabs">
						<li class="tab-link current" data-tab="tab-1">Season 2</li>
						<li class="tab-link" data-tab="tab-2">Legends</li>
					</ul>
					
					<div id="tab-1" class="tab-content current">
					<?php
				}				
				
				$i = 0;
				foreach ($to_display as $player)
				{
					$i++;
					$j = $i % 2;
					?>	
					<div class="top10item<?=$j?>"><div class="top10index"><?=$i?>.</div><div class="top10company"><img src="img/ranks/company/<?=$player['factionid']?>.png"></div><div class="top10grade"><img src="img/ranks/<?=$player['grade']?>.png"></div><div class="top10username"><?=$player['username']?></div><div class="top10points"><?=number_format($player['points'])?></div></div>
					<?php
					if($i == 100)
					{
						break;
					}
				}
				if(empty($_GET['display']))
				{
					?>						
					</div>
					<div id="tab-2" class="tab-content">
					<?php
					
					$i = 0;
					foreach ($legendto_display as $player)
					{
						$i++;
						$j = $i % 2;
						?>	
						<div class="top10item<?=$j?>"><div class="top10index"><?=$i?>.</div><div class="top10company"><img src="img/ranks/company/<?=$player['factionid']?>.png"></div><div class="top10grade"><img src="img/ranks/<?=$player['grade']?>.png"></div><div class="top10username"><?=$player['username']?></div><div class="top10points"><?=number_format($player['points'])?></div></div>
						<?php
						if($i == 100)
						{
							break;
						}
					}
					?>						
					</div>
					<?php
				}	
			}
		?>
		</div>
	</div>	
</div>		
	
<script>
$(document).ready(function(){
	$('ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	})
})
</script>
	