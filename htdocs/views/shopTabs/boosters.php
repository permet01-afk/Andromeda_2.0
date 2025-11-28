<?php 
$sth = $db->prepare("SELECT  uridium, credits, booster_dmg_time,
booster_shd_time, booster_hp_time, booster_spd_time, booster_npc_time
 FROM users WHERE id = :id LIMIT 1");
$sth->execute(array(
				':id' => $_SESSION['player_id']
			));
$datauser = $sth->fetchAll();

if(!empty($_POST['submit-dmgbooster']))
{
	$buymessage = handleSubmitDamageBooster($datauser,$db);
}
else if(!empty($_POST['submit-hpbooster']))
{
	$buymessage = handleSubmitHpBooster($datauser,$db);
}
else if(!empty($_POST['submit-shbooster']))
{
	$buymessage = handleSubmitShieldBooster($datauser,$db);
}
else if(!empty($_POST['submit-spdbooster']))
{
	$buymessage = handleSubmitSpeedBooster($datauser,$db);
}

if(isset($buymessage))
{
	//update infos
	$sth = $db->prepare("SELECT uridium, credits, booster_dmg_time,
booster_shd_time, booster_hp_time, booster_spd_time, booster_npc_time
	 FROM users WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();
}


if($datauser[0]['booster_dmg_time'] > time()) # we check if the booster is not expired (booster time > current time)
{						
	$diff = ($datauser[0]['booster_dmg_time'] - time()); 
	$booster_dmg_days_left = intval($diff/24/60/60); 
	$remain=$diff%86400; 
	$booster_dmg_hours_left=intval($remain/3600); 
	$remain=$remain%3600; 
	$booster_dmg_minutes_left=intval($remain/60);
}
else
{
	$booster_dmg_days_left=0;
	$booster_dmg_hours_left=0;
	$booster_dmg_minutes_left=0;
}

if($datauser[0]['booster_hp_time'] > time()) # we check if the booster is not expired (booster time > current time)
{						
	$diff = ($datauser[0]['booster_hp_time'] - time()); 
	$booster_hp_days_left = intval($diff/24/60/60); 
	$remain=$diff%86400; 
	$booster_hp_hours_left=intval($remain/3600); 
	$remain=$remain%3600; 
	$booster_hp_minutes_left=intval($remain/60);
}
else
{
	$booster_hp_days_left=0;
	$booster_hp_hours_left=0;
	$booster_hp_minutes_left=0;
}

if($datauser[0]['booster_shd_time'] > time()) 
{						
	$diff = ($datauser[0]['booster_shd_time'] - time()); 
	$booster_shd_days_left = intval($diff/24/60/60); 
	$remain=$diff%86400; 
	$booster_shd_hours_left=intval($remain/3600); 
	$remain=$remain%3600; 
	$booster_shd_minutes_left=intval($remain/60);
}
else
{
	$booster_shd_days_left=0;
	$booster_shd_hours_left=0;
	$booster_shd_minutes_left=0;
}

if($datauser[0]['booster_spd_time'] > time()) 
{						
	$diff = ($datauser[0]['booster_spd_time'] - time()); 
	$booster_speed_days_left = intval($diff/24/60/60); 
	$remain=$diff%86400; 
	$booster_speed_hours_left=intval($remain/3600); 
	$remain=$remain%3600; 
	$booster_speed_minutes_left=intval($remain/60);
}
else
{
	$booster_speed_days_left=0;
	$booster_speed_hours_left=0;
	$booster_speed_minutes_left=0;
}

if($datauser[0]['booster_npc_time'] > time()) 
{						
	$diff = ($datauser[0]['booster_npc_time'] - time()); 
	$booster_npc_days_left = intval($diff/24/60/60); 
	$remain=$diff%86400; 
	$booster_npc_hours_left=intval($remain/3600); 
	$remain=$remain%3600; 
	$booster_npc_minutes_left=intval($remain/60);
}
else
{
	$booster_npc_days_left=0;
	$booster_npc_hours_left=0;
	$booster_npc_minutes_left=0;
}
?>
	
<div class="box" style="margin-top:50px;margin-left:140px;margin-bottom:20px;">
	<div class="title">Ship boosters</div>
	<div id="user-booster">
		<div class="stat" style="width:550px;">
			<div class="stat-left tooltip">
				<span>
					<img class="callout" src="img/callout.gif" />
						<strong>Damage booster:</strong><br />
							Increase base damages by <font color='red'>300</font>
				</span>
				<img src="img/dmg.png" style="margin-right:10px;">Damage
			</div>
			<div class="stat-right tooltip" style="width:390px;text-align:left; padding-left: 10px;">
				<?php echo $booster_dmg_days_left.' d / '.$booster_dmg_hours_left.' h / '.$booster_dmg_minutes_left.' min'; ?>
				<span style="text-align:center;">
					<img class="callout" src="img/callout.gif" />
					<strong>Damage booster</strong><br/>

					Credits: <font color='#00AAFF'>5,000,000</font> per hour.
				</span>
				<form action="view.php?page=shop&tab=boosters" method="post" style="margin-top:-16px;">
					<label for="" style="position:absolute;right:130px; color:#81B7F2;" >Amount :</label><input style="opacity:0.6;width:50px;position:absolute;right:80px;margin-top:-1px;text-align:center;" type="text" name="amount-d" value="1">
					<input name="submit-dmgbooster" class="buy-stat"  style="width:50px;height:20px;" type="submit" value="Buy">
				</form>
			</div>
		</div>	
		<div class="stat" style="width:550px;">
			<div class="stat-left tooltip">
				<span>
					<img class="callout" src="img/callout.gif" />
						<strong>Health points booster:</strong><br />
							Increase health points by <font color='green'>25,000</font>
				</span>
				<img src="img/hp.png" style="margin-right:10px;">Health points
			</div>
			<div class="stat-right tooltip" style="width:390px;text-align:left; padding-left: 10px;">
				<?php echo $booster_hp_days_left.' d / '.$booster_hp_hours_left.' h / '.$booster_hp_minutes_left.' min'; ?>
				<span style="text-align:center;">
					<img class="callout" src="img/callout.gif" />
					<strong>Health points booster</strong><br/>
					Credits: <font color='#00AAFF'>5,000,000</font> per hour.
				</span>
				<form action="view.php?page=shop&tab=boosters" method="post" style="margin-top:-16px;">
					<label for="" style="position:absolute;right:130px; color:#81B7F2;" >Amount :</label><input style="opacity:0.6;width:50px;position:absolute;right:80px;margin-top:-1px;text-align:center;" type="text" name="amount-g" value="1">
					<input name="submit-hpbooster" class="buy-stat"  style="width:50px;height:20px;" type="submit" value="Buy">
				</form>
			</div>
		</div>	
		<div class="stat" style="width:550px;">
			<div class="stat-left tooltip">
				<span>
					<img class="callout" src="img/callout.gif" />
						<strong>Shield booster:</strong><br />
							Increase Shield by <font color='#00AAFF'>30.000</font>
				</span>
				<img src="img/sh.png" style="margin-right:10px;">Shield
			</div>
			<div class="stat-right tooltip" style="width:390px;text-align:left; padding-left: 10px;">
				<?php echo $booster_shd_days_left.' d / '.$booster_shd_hours_left.' h / '.$booster_shd_minutes_left.' min'; ?>
				<span style="text-align:center;">
					<img class="callout" src="img/callout.gif" />
					<strong>Shield booster</strong><br/>
					Credits: <font color='#00AAFF'>5,000,000</font> per hour.
				</span>
				<form action="view.php?page=shop&tab=boosters" method="post" style="margin-top:-16px;">
					<label for="" style="position:absolute;right:130px; color:#81B7F2;" >Amount :</label><input style="opacity:0.6;width:50px;position:absolute;right:80px;margin-top:-1px;text-align:center;" type="text" name="amount-e" value="1">
					<input name="submit-shbooster" class="buy-stat"  style="width:50px;height:20px;" type="submit" value="Buy">
				</form>
			</div>
		</div>			
		<div class="stat" style="width:550px;">
			<div class="stat-left tooltip">
				<span>
					<img class="callout" src="img/callout.gif" />
						<strong>Speed booster:</strong><br />
							Increase speed by <font color='magenta'>20</font> 
				</span>
				<img src="img/speed.png" style="margin-right:10px;">Speed
			</div>
			<div class="stat-right tooltip" style="width:390px;text-align:left; padding-left: 10px;">
				<?php echo $booster_speed_days_left.' d / '.$booster_speed_hours_left.' h / '.$booster_speed_minutes_left.' min'; ?>
				<span style="text-align:center;">
					<img class="callout" src="img/callout.gif" />
					<strong>Speed booster</strong><br/>
					Uridium: <font color='magenta'>5,000</font> per hour.
				</span>
				<form action="view.php?page=shop&tab=boosters" method="post" style="margin-top:-16px;">
					<label for="" style="position:absolute;right:130px; color:#81B7F2;" >Amount :</label><input style="opacity:0.6;width:50px;position:absolute;right:80px;margin-top:-1px;text-align:center;" type="text" name="amount-f" value="1">
					<input name="submit-spdbooster" class="buy-stat"  style="width:50px;height:20px;" type="submit" value="Buy">
				</form>
			</div>
		</div>	
		
		<div class="stat" style="width:550px;">
			<div class="stat-left tooltip">
				<span>
						<img class="callout" src="img/callout.gif" />
						<strong>NPC points booster:</strong><br />
							Increase NPC points reward by <font color='yellow'>50%</font> 
				</span>
				<img src="img/xp.png" style="margin-right:10px;">NPC points
			</div>
			<div class="stat-right" style="width:400px;">
				<?php echo $booster_npc_days_left.' d / '.$booster_npc_hours_left.' h / '.$booster_npc_minutes_left.' min'; ?>					
			</div>
		</div>
	</div>
</div>
	
<?php
function handleSubmitDamageBooster($datauser,$db)
{
	$amount = 1;
	if(!empty($_POST['amount-d']))
	{		
		$amount = (int)$_POST['amount-d'];
	}
	if($amount < 1)
	{
		$amount = 1;
	}	
	$price = 5000000*$amount;
	
	if($datauser[0]['credits'] >= $price)
	{
		if($datauser[0]['booster_dmg_time'] > time()) # if the user already have a booster, we increase the booster time by 3600 sec (=1hour)
		{
			$booster_dmg_time = $datauser[0]['booster_dmg_time'] + (3600*$amount); # 
		}
		else # else we set it to time() + 3600 (= current time + 1 hour)
		{
			$booster_dmg_time = time() + (3600*$amount); # 
		}
		$req = $db->prepare('UPDATE users SET booster_dmg_time='.$booster_dmg_time.', credits=credits-'.$price.' WHERE id='.$_SESSION['player_id']);
		$req->execute();
		return "Purchase success !";
	}
	else
	{
		return "Error : Not enough credits !";
	}
}

function handleSubmitHpBooster($datauser,$db)
{
	$amount = 1;
	if(!empty($_POST['amount-g']))
	{		
		$amount = (int)$_POST['amount-g'];
	}
	if($amount < 1)
	{
		$amount = 1;
	}	
	$price = 5000000*$amount;
	
	if($datauser[0]['credits'] >= $price)
	{
		if($datauser[0]['booster_hp_time'] > time()) # if the user already have a booster, we increase the booster time by 3600 sec (=1hour)
		{
			$booster_hp_time = $datauser[0]['booster_hp_time'] + (3600*$amount); # 
		}
		else # else we set it to time() + 3600 (= current time + 1 hour)
		{
			$booster_hp_time = time() + (3600*$amount); # 
		}
		$req = $db->prepare('UPDATE users SET booster_hp_time='.$booster_hp_time.', credits=credits-'.$price.' WHERE id='.$_SESSION['player_id']);
		$req->execute();
		return "Purchase success !";
	}
	else
	{
		return "Error : Not enough credits !";
	}
}

function handleSubmitShieldBooster($datauser,$db)
{
	$amount = 1;
	if(!empty($_POST['amount-e']))
	{		
		$amount = (int)$_POST['amount-e'];
	}
	if($amount < 1)
	{
		$amount = 1;
	}	
	$price = 5000000*$amount;
	
	if($datauser[0]['credits'] >= $price)
	{
		if($datauser[0]['booster_shd_time'] > time()) # if the user already have a booster, we increase the booster time by 3600 sec (=1hour)
		{
			$booster_shd_time = $datauser[0]['booster_shd_time'] + (3600*$amount); # 
		}
		else # else we set it to time() + 3600 (= current time + 1 hour)
		{
			$booster_shd_time = time() + (3600*$amount); # 
		}
		$req = $db->prepare('UPDATE users SET booster_shd_time='.$booster_shd_time.', credits=credits-'.$price.' WHERE id='.$_SESSION['player_id']);
		$req->execute();
		return "Purchase success !";
	}
	else
	{
		return "Error : Not enough credits !";
	}
}

function handleSubmitSpeedBooster($datauser,$db)
{
	$amount = 1;
	if(!empty($_POST['amount-f']))
	{		
		$amount = (int)$_POST['amount-f'];
	}
	if($amount < 1)
	{
		$amount = 1;
	}	
	$price = 5000*$amount;
	
	if($datauser[0]['uridium'] >= $price)
	{
		if($datauser[0]['booster_spd_time'] > time()) # if the user already have a booster, we increase the booster time by 3600 sec (=1hour)
		{
			$booster_spd_time = $datauser[0]['booster_spd_time'] + (3600*$amount); # 
		}
		else # else we set it to time() + 3600 (= current time + 1 hour)
		{
			$booster_spd_time = time() + (3600*$amount); # 
		}
		$req = $db->prepare('UPDATE users SET booster_spd_time='.$booster_spd_time.', uridium=uridium-'.$price.' WHERE id='.$_SESSION['player_id']);
		$req->execute();
		return "Purchase success !";
	}
	else
	{
		return "Error : Not enough uridium !";
	}
}
?>