<?php 
$sth = $db->prepare("SELECT clanid
 FROM users WHERE id = :id LIMIT 1");
$sth->execute(array(
				':id' => $_SESSION['player_id']
			));
$datauser = $sth->fetchAll();

if($datauser[0]['clanid'] == 0)
{
		$sth = $db->prepare("SELECT id, clan_tag, clan_name FROM clan");
		$sth->execute();
		$clansdata = $sth->fetchAll();
		
		if(!empty($_GET['join']))
		{
			$message = handleJoinClan($db);
			$sth = $db->prepare("SELECT clanid
			 FROM users WHERE id = :id LIMIT 1");
			$sth->execute(array(
							':id' => $_SESSION['player_id']
						));
			$datauser = $sth->fetchAll();
		}
		else if (!empty($_POST['submit-create']))
		{
			$message = handleCreateClan($db);
			$sth = $db->prepare("SELECT clanid
			 FROM users WHERE id = :id LIMIT 1");
			$sth->execute(array(
							':id' => $_SESSION['player_id']
						));
			$datauser = $sth->fetchAll();
		}		
		$isAdmin = false;
}
if($datauser[0]['clanid'] > 0)
{
	$sth = $db->prepare("SELECT clan_name, clan_tag, admin_id FROM clan WHERE id = :clanid LIMIT 1");
	$sth->execute(array(
					':clanid' => $datauser[0]['clanid'] 
				));
	$dataclan = $sth->fetchAll();
	
	if($dataclan[0]['admin_id'] == $_SESSION['player_id'])
	{
		$isAdmin = true;
		$sth = $db->prepare("SELECT id, clan_tag, clan_name FROM clan");
		$sth->execute();
		$clansdata = $sth->fetchAll();
	}
	else
	{
		$isAdmin = false;
	}
	
	if (!empty($_POST['submit-leave']))
	{
		$message = handleLeaveClan($db);
		$sth = $db->prepare("SELECT clanid
		 FROM users WHERE id = :id LIMIT 1");
		$sth->execute(array(
						':id' => $_SESSION['player_id']
					));
		$datauser = $sth->fetchAll();
	}
	else if (!empty($_POST['submit-remove']) && $isAdmin == true)
	{
		$message = handleRemoveClan($db, $datauser[0]['clanid']);
		$sth = $db->prepare("SELECT clanid
		 FROM users WHERE id = :id LIMIT 1");
		$sth->execute(array(
						':id' => $_SESSION['player_id']
					));
		$datauser = $sth->fetchAll();
	}
	else if (!empty($_POST['submit-war']) && $isAdmin == true)
	{
		$message = handleDeclareWar($db, $datauser[0]['clanid']);
		$sth = $db->prepare("SELECT clanid
		 FROM users WHERE id = :id LIMIT 1");
		$sth->execute(array(
						':id' => $_SESSION['player_id']
					));
		$datauser = $sth->fetchAll();
	}
	else if (!empty($_GET['kick']) && $isAdmin == true)
	{
		$message = handleKickClan($db);
		$sth = $db->prepare("SELECT clanid
		 FROM users WHERE id = :id LIMIT 1");
		$sth->execute(array(
						':id' => $_SESSION['player_id']
					));
		$datauser = $sth->fetchAll();
	}
	else if (!empty($_GET['accept']) && $isAdmin == true)
	{
		$message = handleAcceptRequest($db,$datauser[0]['clanid']);
		$sth = $db->prepare("SELECT clanid
		 FROM users WHERE id = :id LIMIT 1");
		$sth->execute(array(
						':id' => $_SESSION['player_id']
					));
		$datauser = $sth->fetchAll();
	}
	else if (!empty($_GET['refuse']) && $isAdmin == true)
	{
		$message = handleRefuseRequest($db,$datauser[0]['clanid']);
		$sth = $db->prepare("SELECT clanid
		 FROM users WHERE id = :id LIMIT 1");
		$sth->execute(array(
						':id' => $_SESSION['player_id']
					));
		$datauser = $sth->fetchAll();
	}
	else if (!empty($_GET['cancel']) && $isAdmin == true)
	{
		$message = handleCancelWar($db,$datauser[0]['clanid']);
		$sth = $db->prepare("SELECT clanid
		 FROM users WHERE id = :id LIMIT 1");
		$sth->execute(array(
						':id' => $_SESSION['player_id']
					));
		$datauser = $sth->fetchAll();
	}
	
	$sth = $db->prepare("SELECT username, id, factionid FROM users WHERE clanid = :clanid");
	$sth->execute(array(
					':clanid' => $datauser[0]['clanid'] 
				));
	$datamembers = $sth->fetchAll();
	
	$sth = $db->prepare("SELECT second_clan_id FROM clan_diplomacy WHERE clan_id = :clanid");
	$sth->execute(array(
					':clanid' => $datauser[0]['clanid'] 
				));
	$datawarsFist = $sth->fetchAll();
	
	$sth = $db->prepare("SELECT clan_id FROM clan_diplomacy WHERE second_clan_id = :clanid");
	$sth->execute(array(
					':clanid' => $datauser[0]['clanid'] 
				));
	$datawarsSec = $sth->fetchAll();
	
	if($isAdmin == true)
	{
		$sth = $db->prepare("SELECT player_id FROM clan_request WHERE clan_id=:clanid");
		$sth->execute(array('clanid' => $datauser[0]['clanid']));
		$datarequest = $sth->fetchAll();
	}
}
?>
<link rel="stylesheet" type="text/css" href="styles/home.css" />
<link rel="stylesheet" type="text/css" href="styles/userStyle.css" />
<link rel="stylesheet" type="text/css" href="styles/clan.css" />
<div class="CMSContent">
	<?php if($datauser[0]['clanid'] == 0)
	{ ?>
		<div class="box" style="margin-left:120px;">
			<div class="title">Create clan</div>
			<div id="clan-create">
				<form id="createclan" action="view.php?page=clan" method="post">
						<label for="">Clan's tag :</label><input style="opacity:0.6;" type="text" name="clan_tag" maxlength="4">
						<label for="">Clan's name :</label><input style="opacity:0.6;" type="text" name="clan_name">
						<input name="submit-create" class="buy-stat"  style="width:80px;height:25px;" type="submit" value="Create clan">
				</form>
			</div>
		</div>	
		<div class="box" style="margin-left:120px;">
			<div class="title">Clans</div>
			<div id="clan-join">		
				<div class="clan-list">
					<?php displayClansList($clansdata); ?>
				</div>
			</div>
		</div>	
	<?php }
	else { ?>
		<div class="box" style="margin-left:120px;">
			<div class="title">Clan actions</div>
			<div id="clan-create">
				<?php if($isAdmin == true)
				{ ?>
					<form id="createclan" action="view.php?page=clan" method="post">
							<?=$dataclan[0]['clan_name']?><input name="submit-remove" class="buy-stat"  style="width:80px;height:25px;" type="submit" value="Remove clan">
					</form>
				<?php }
				else { ?>
					<form id="createclan" action="view.php?page=clan" method="post">
							<?=$dataclan[0]['clan_name']?><input name="submit-leave" class="buy-stat"  style="width:80px;height:25px;" type="submit" value="Leave clan">
					</form>
				<?php } ?>	
			</div>
		</div>	
		<div class="box" style="margin-left:120px;">
			<div class="title">Clan's members</div>
			<div id="clan-join">		
				<div class="clan-list">
					<?php displayClanMember($dataclan, $datamembers,$isAdmin); ?>
				</div>
			</div>
		</div>
		<div class="box" style="margin-left:120px;">
			<div class="title">Clan's wars</div>
			<div id="clan-request">		
				<div class="clan-list2">
					<?php displayClanWarsFist($datawarsFist,$db); ?>
					<?php displayClanWarsSec($datawarsSec,$db); ?>
				</div>
			</div>
		</div>
		<?php if($isAdmin == true)
		{ ?>
			<div class="box" style="margin-left:120px;">
				<div class="title">Declare war</div>
				<div id="clan-create">
					<form id="createclan" action="view.php?page=clan" method="post">
							<select style="position:absolute; left: 140px; opacity:0.6; width:250px;" name="clan_id" id="" class="text-search">
									<?php
										foreach ($clansdata as  $clan) 
										{
											if($clan['id'] != $datauser[0]['clanid'])
											{
												echo '<option value="'.$clan['id'].'">['.$clan['clan_tag'].'] '.$clan['clan_name'].'</option>';
											}
										}
									?>
							</select>
							<input name="submit-war" class="buy-stat"  style="width:80px;height:25px;" type="submit" value="Declare war">
					</form>
				</div>
			</div>	
			<div class="box" style="margin-left:120px;">
				<div class="title">Clan's applications</div>
				<div id="clan-request">		
					<div class="clan-list2">
						<?php displayClanRequest($datarequest,$db); ?>
					</div>
				</div>
			</div>
		<?php } ?>
	<?php } ?>
</div>		
<?php
if(isset($message))
{
?>
	<div id="popup_box">    <!-- OUR PopupBox DIV-->
		<div id="popupContent"> 
		<?=$message?>
		</div>
		<a id="popupBoxClose"  >Close</a>    
	</div>

	<script src="http://jqueryjs.googlecode.com/files/jquery-1.2.6.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		
		$(document).ready( function() {
		
			// When site loaded, load the Popupbox First
			loadPopupBox();
		
			$('#popupBoxClose').click( function() {            
				unloadPopupBox();
			});
			
			$('#container').click( function() {
				unloadPopupBox();
			});

			function unloadPopupBox() {    // TO Unload the Popupbox
				$('#popup_box').fadeOut("slow");
				$("#container").css({ // this is just for style        
					"opacity": "1"  
				}); 
			}    
			
			function loadPopupBox() {    // To Load the Popupbox
				$('#popup_box').fadeIn("slow");
				$("#container").css({ // this is just for style
					"opacity": "0.3"  
				});         
			}        
		});
	</script>  
<?php
}
?>
<?php 
function displayClansList($clansdata)
{
	foreach($clansdata as $clan)	
	{
		?>
				<div class="stat" style="width:520px">
					<div class="stat-left">
						<?=$clan['clan_tag']?>
					</div>
					<div class="stat-right" style="width:380px" >
						<?=$clan['clan_name']?>
						<a class="buy-stat" href="view.php?page=clan&join=<?=$clan['id']?>">
							Join
						</a>
					</div>
				</div>
		<?php
	}
}

function displayClanRequest($datarequest, $db)
{
	foreach($datarequest as $request)	
	{
		$sth = $db->prepare("SELECT username, factionid, id FROM users WHERE id = :id LIMIT 1");
		$sth->execute(array(
						':id' => $request['player_id'] 
					));
		$datamember = $sth->fetchAll();
		?>
				<div class="stat" style="width:520px">
					<div class="stat-left">
						<img src="img/ranks/company/<?=$datamember[0]['factionid']?>.png">
					</div>
					<div class="stat-right" style="width:380px" >
						<?=$datamember[0]['username']?>
						<a class="buy-stat" href="view.php?page=clan&refuse=<?=$datamember[0]['id']?>">
							Refuse
						</a>
						<a class="buy-stat" href="view.php?page=clan&accept=<?=$datamember[0]['id']?>">
							Accept
						</a>
					</div>
				</div>
		<?php
	}
}

function displayClanWarsFist($datawars, $db)
{
	foreach($datawars as $war)	
	{
		$sth = $db->prepare("SELECT clan_name, clan_tag, admin_id FROM clan WHERE id = :clanid LIMIT 1");
		$sth->execute(array(
						':clanid' => $war['second_clan_id'] 
					));
		$dataclan = $sth->fetchAll();
		$count = $sth->rowCount();

		if ($count > 0)
		{
		?>
				<div class="stat" style="width:520px">
					<div class="stat-left">
						<?=$dataclan[0]['clan_tag']?>
					</div>
					<div class="stat-right" style="width:380px" >
						<?=$dataclan[0]['clan_name']?>
						<a class="buy-stat" href="view.php?page=clan&cancel=<?=$war['second_clan_id']?>">
							Cancel
						</a>
					</div>
				</div>
		<?php
		}
	}
}

function displayClanWarsSec($datawars, $db)
{
	foreach($datawars as $war)	
	{
		$sth = $db->prepare("SELECT clan_name, clan_tag, admin_id FROM clan WHERE id = :clanid LIMIT 1");
		$sth->execute(array(
						':clanid' => $war['clan_id'] 
					));
		$dataclan = $sth->fetchAll();
		$count = $sth->rowCount();

		if ($count > 0)
		{
		?>
				<div class="stat" style="width:520px">
					<div class="stat-left">
						<?=$dataclan[0]['clan_tag']?>
					</div>
					<div class="stat-right" style="width:380px" >
						<?=$dataclan[0]['clan_name']?>
					</div>
				</div>
		<?php
		}
	}
}

function displayClanMember($dataclan, $clanmembers,$isAdmin)
{
	foreach($clanmembers as $member)	
	{
		?>
				<div class="stat" style="width:520px">
					<div class="stat-left">
						<img src="img/ranks/company/<?=$member['factionid']?>.png">
					</div>
					<div class="stat-right" style="width:380px" >
						<?=$member['username']?>
						<?php if($isAdmin == true)
						{ ?>
							<a class="buy-stat" href="view.php?page=clan&kick=<?=$member['id']?>">
								Kick
							</a>
						<?php } ?>
					</div>
				</div>
		<?php
	}
}
function handleCreateClan($db)
{
	if($_POST['clan_tag']=='' || $_POST['clan_name']=='')
	{
		return "Invalid clan's tag or clan's name";
	}
	else 
	{
		$clantag = htmlentities($_POST['clan_tag']);
		$clanname = htmlentities($_POST['clan_name']);
		
		$sth = $db->prepare("INSERT INTO `clan`(`clan_tag`, `clan_name`, `admin_id`) VALUES (:tag, :name, :admin_id)");
		$sth->execute(array('tag' => $clantag, 'name' => $clanname, 'admin_id' => $_SESSION['player_id']));
		
		 $returnvalue = $db->select('SELECT id FROM clan_request WHERE player_id=:id', array('id' => $_SESSION['player_id']));
		 foreach ($returnvalue as $key => $value) {
			$sth = $db->prepare("DELETE FROM `clan_request` WHERE id=:id");
			$sth->execute(array('id' => $returnvalue[$key]['id']));
		 }

		$returnvalue = $db->select('SELECT id FROM clan WHERE admin_id= :id', array('id' => $_SESSION['player_id']));
		$sth = $db->prepare("UPDATE `users` SET `clanid`=:clanid WHERE id=:id");
		$sth->execute(array('clanid' => $returnvalue[0]['id'], 'id' => $_SESSION['player_id']));
		
		return "Clan created";
	}
}
function handleJoinClan($db)
{
	$clanid = htmlentities($_GET['join']);
	$returnvalue = $db->select('SELECT id FROM clan_request WHERE clan_id=:clan_id AND player_id=:id', array('clan_id' => $clanid, 'id' => $_SESSION['player_id']));
	if (isset($returnvalue[0]) == 0)
	{
		$sth = $db->prepare("INSERT INTO `clan_request`(`clan_id`, `player_id`) VALUES (:clan_id, :id)");
		$sth->execute(array('clan_id' => $clanid, 'id' => $_SESSION['player_id']));
		return 'Request send !';
	}
	else
	{
		return 'You already made a request for that clan !';
	}
}
function handleLeaveClan($db)
{
	$sth = $db->prepare("UPDATE `users` SET `clanid`=0 WHERE id=:id");
	$sth->execute(array('id' => $_SESSION['player_id']));
	
	return 'You left your clan';
}

function handleRemoveClan($db, $clandid)
{
	$sth = $db->prepare("DELETE FROM `clan` WHERE id=:clanid");
	$sth->execute(array('clanid' => $clandid));

	$sth = $db->prepare("DELETE FROM `clan_request` WHERE clan_id=:clanid");
	$sth->execute(array('clanid' => $clandid));

	$sth = $db->prepare("UPDATE `users` SET `clanid`=0 WHERE clanid=:clanid");
	$sth->execute(array('clanid' => $clandid));
	
	return 'You removed your clan';
}

function handleKickClan($db)
{
	$id = htmlentities($_GET['kick']);
	$sth = $db->prepare("UPDATE `users` SET `clanid`=0 WHERE id=:id");
	$sth->execute(array('id' => $id));
	return 'Player kicked';
}

function handleAcceptRequest($db,$clanid)
{
	$id = htmlentities($_GET['accept']);
	$sth = $db->prepare("UPDATE `users` SET `clanid`=:clan_id WHERE id=:id");
	$sth->execute(array('id' => $id,'clan_id' => $clanid));
	
	$sth = $db->prepare("DELETE FROM `clan_request` WHERE player_id=:player_id");
	$sth->execute(array('player_id' => $id));
	
	return 'Player accepted';
}

function handleRefuseRequest($db,$clanid)
{	
	$id = htmlentities($_GET['refuse']);
	$sth = $db->prepare("DELETE FROM `clan_request` WHERE player_id=:player_id AND `clan_id`=:clan_id");
	$sth->execute(array('player_id' => $id,'clan_id' => $clanid));
	
	return 'Player refused';
}

function handleCancelWar($db,$clanid)
{	
	$id = htmlentities($_GET['cancel']);
	$sth = $db->prepare("DELETE FROM `clan_diplomacy` WHERE second_clan_id=:second_clan_id AND `clan_id`=:clan_id");
	$sth->execute(array('second_clan_id' => $id,'clan_id' => $clanid));
	
	return 'War cancelled';
}

function handleDeclareWar($db,$my_clan)
{
	$clan_id = htmlentities($_POST['clan_id']);
	
	$sth = $db->prepare("INSERT INTO `clan_diplomacy`(`clan_id`, `second_clan_id`, `type`) VALUES (:clan_id,:second_clan_id,:type)");
	$sth->execute(array('clan_id' => $my_clan, 'second_clan_id' => $clan_id, 'type' => 1));
	
	return 'You declared the war';
}
?>	
	
	