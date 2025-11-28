
<?php 
$sth = $db->prepare("SELECT clanid
 FROM users WHERE id = :id LIMIT 1");
$sth->execute(array(
				':id' => $_SESSION['player_id']
			));
$datauser = $sth->fetchAll();

if($datauser[0]['clanid'] > 0)
{
	$errors = array();	
	$errors[] = "You cannot create an clan while you are in a clan.";	
}
else
{
	$errors = handleCreateClanForm($db);
}

?>
<div class="box" style="margin-left: 100px;">
	<div class="title">Create a New Clan <br>Cost: <font color='#00AAFF'>1,000,000,000</font> Credits</div>
	<div id="clan-create">	
		<?php		
		if (sizeof($errors) > 0) 
		{
			echo '<div class="error">';
			echo '<p class="error">Error(s): <br>';
				foreach ($errors as $err_msg) {
					echo "&nbsp; &nbsp; - {$err_msg} <br>";
				}
				echo '</ul></p><br><br>';
			echo '</div>';
		}
		?>
		<form class="clan-form" action="view.php?page=clan&tab=createclan" method="post">
				<ul>
					<b>Clan's Tag</b> (2-4 characters): <li><input name="clan-create-form-tag" type="text"  maxlength="4" /></li>
					<b>Clan's Name</b> (5-20 characters): <li><input name="clan-create-form-name" type="text"  maxlength="20" /></li>
					<b>Clan's Description</b> (12-120 characters):<li> <textarea name="clan-create-form-description" rows=3 cols=40></textarea></li>
					<li><input name="clan-create-form-submit" type="submit" value="Create Clan" /></li>
				</ul>
		</form>	
	</div>
</div>		
<?php 
function convertToNumericEntities($string) 
{
	$convmap = array(0x80, 0x10ffff, 0, 0xffffff);
	return mb_encode_numericentity($string, $convmap, "UTF-8");
}
function handleCreateClanForm($db)
{
	$errors = array();	
	if (empty($_POST['clan-create-form-submit']))
	{
		return $errors;
	}
	if (empty($_POST['clan-create-form-tag']))
	{
		$errors[] = "Clan's Tag required.";	
	}
	else if ( !preg_match('/^[A-Za-z0-9]{2,4}$/', $_POST['clan-create-form-tag'])) 
	{
		$errors[] = "Invalid Clan'sTag (2-4 characters, Letters and numbers only).";		
	}
	if (empty($_POST['clan-create-form-name']))
	{
		$errors[] = "Clan's Name required.";	
	}
	else if (strlen($_POST['clan-create-form-name']) > 20 || strlen($_POST['clan-create-form-name']) < 5) 
	{
		$errors[] = "Invalid Clan's Name (5-20 characters).";	
	}
	if (empty($_POST['clan-create-form-description']))
	{
		$errors[] = "Clan's Description required.";	
	}
	else if (strlen($_POST['clan-create-form-description']) > 120 || strlen($_POST['clan-create-form-description']) < 12) 
	{
		$errors[] = "Invalid Clan's Description (12-120 characters).";	
	}
	
	if (sizeof($errors) > 0) 
	{		
		return $errors;
	}
	
	$sth = $db->prepare("SELECT  credits
	FROM users WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();
	
	if($datauser[0]['credits'] < 1000000000)
	{
		$errors[] = "Not enough credits.";	
	}	
	
	$tag = convertToNumericEntities(htmlentities($_POST['clan-create-form-tag']));
	$name = convertToNumericEntities(htmlentities($_POST['clan-create-form-name']));
	$description = convertToNumericEntities(htmlentities($_POST['clan-create-form-description']));
	
	$sth = $db->prepare("SELECT clan_tag FROM clan WHERE clan_tag = :clan_tag");
	$sth->execute(array(
		':clan_tag' => $tag			
	));
	$count = $sth->rowCount();
	if($count > 0)
	{
		$errors[] = "Clan's Tag already in use.";	
	}
	
	$sth = $db->prepare("SELECT clan_name FROM clan WHERE clan_name = :clan_name");
	$sth->execute(array(
		':clan_name' => $name			
	));
	$count = $sth->rowCount();
	if($count > 0)
	{
		$errors[] = "Clan's Name already in use.";	
	}
	
	if (sizeof($errors) > 0) 
	{		
		return $errors;
	}
	else
	{
		$req = $db->prepare('UPDATE users SET credits=credits-1000000000 WHERE id='.$_SESSION['player_id']);
		$req->execute();
		
		$sth = $db->prepare("SELECT factionid
		 FROM users WHERE id = :id LIMIT 1");
		$sth->execute(array(
						':id' => $_SESSION['player_id']
					));
		$datauser = $sth->fetchAll();

		$company = $datauser[0]['factionid'];
					
		$db->insert('clan', array(
			'clan_company' => $company,
			'clan_tag' => $tag,
			'clan_name' => $name,
			'clan_description' => $description,
			'admin_id' => $_SESSION['player_id']
			));
			
		$sth = $db->prepare("DELETE FROM `clan_request` WHERE player_id	=:player_id	");
		$sth->execute(array(':player_id' => $_SESSION['player_id']));
			
		$returnvalue = $db->select('SELECT id FROM clan WHERE admin_id= :id', array('id' => $_SESSION['player_id']));
		$db->update('users', array(
			'clanid' => $returnvalue[0]['id']
			),
			'id='.$_SESSION['player_id']
			);
		
		header("Location: view.php?page=clan&tab=claninfos");
		exit();
	}		
	return $errors;
}
?>