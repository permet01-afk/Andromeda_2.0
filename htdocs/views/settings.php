<?php 


$sth = $db->prepare("SELECT login, email, registerdate
 FROM users_infos WHERE id = :id LIMIT 1");
$sth->execute(array(
				':id' => $_SESSION['player_id']
			));
$datauser = $sth->fetchAll();



$errors = array();
/*$logerr = handleLoginForm($db);
$errors = array_merge($errors,$logerr);
$regerrs = handleRegisterForm($db);
$errors = array_merge($errors,$regerrs);*/
if (!empty($_POST['newign_submit'])) 
{
	$errors = handleNewIgn($db);
}
else if (!empty($_POST['newgenpw_submit'])) 
{
	$errors = handleGenPw($db);
}
else if (!empty($_POST['newpw_submit'])) 
{
	$errors = handleNewPw($db);
}
else if (!empty($_POST['newemail_submit'])) 
{
	$errors = handleNewEmail($db);
}

?>

<link rel="stylesheet" type="text/css" href="styles/home.css" />
<link rel="stylesheet" type="text/css" href="styles/settings.css" />
<div class="CMSContent">
	<div class="box" style="margin-left:220px;margin-bottom:20px;">
		<div class="title">Settings </div>
		<div id="settings-infos">
			<div class="stat"><div class="stat-left">Login</div><div class="stat-right"><?=($datauser[0]['login'])?></div></div>
			<div class="stat"><div class="stat-left">Email</div><div class="stat-right"><?=($datauser[0]['email'])?></div></div>
			<div class="stat"><div class="stat-left">UserID</div><div class="stat-right"><?=($_SESSION['player_id'] + 100000)?></div></div>
			<div class="stat"><div class="stat-left">User since</div><div class="stat-right"><?=$datauser[0]['registerdate']?></div></div>
			<br>				
		</div>
	</div>	
	
	<?php
		
		if (sizeof($errors) > 0) 
		{
			?>
			<div class="box" style="margin-left:220px;margin-bottom:20px;">
			<div class="title">Settings </div>
			<div id="settings-infos"><?php
			echo '<div class="error">';
			echo '<p class="error">Error(s): <br>';
				foreach ($errors as $err_msg) 
				{
					echo "&nbsp; &nbsp; - {$err_msg} <br>";
				}
				echo '</ul></p><br><br>';
			echo '</div>';
			?>
			</div>
	</div><?php
			
		}
		?>
	
	<div class="box" style="margin-left:60px;margin-bottom:20px;">
		<div class="title">New generated password <br> Cost : FREE</div>
		<div class="settings">
			A new generated password will be sent to your email address.
			<br><br>
			<form id="newgenpw" action="view.php?page=settings" method="post">
				<ul>
					<li><input name="newgenpw_submit" type="submit" value="Process" /></li>
				</ul>
			</form>
			<br><br>				
		</div>
	</div>	
	
	
	<div class="box" style="margin-left:20px;margin-bottom:20px;">
		<div class="title">New In-Game Name <br> Cost : 2 token</div>
		<div class="settings">
			Choose a new In-Game Name.
			<br><br>
			<form id="newign" action="view.php?page=settings" method="post">
				<ul>
					<li><input name="newign_pseudo" type="text" placeholder="New In-Game name" maxlength="32" /></li>
					<li><input name="newign_repeatpseudo" type="text" placeholder="Repeat New In-Game name" maxlength="32" /></li>
					<li><input name="newign_submit" type="submit" value="Process" /></li>
				</ul>
			</form>
			<br><br>				
		</div>
	</div>	
	
	<div class="box" style="margin-left:60px;margin-top:-70px;">
		<div class="title">New password <br> Cost : 1 token</div>
		<div class="settings">
			Choose a new password for your account.
			<br><br>
			<form id="newpw" action="view.php?page=settings" method="post">
				<ul>
					<li><input name="newpw_oldpassword" type="password" placeholder="Old Password" maxlength="32" /></li>						
					<li><input name="newpw_password" type="password" placeholder="Password" maxlength="32" /></li>
					<li><input name="newpw_passwordRepeat" type="password" placeholder="Confirm password" maxlength="32" /></li><br>
					<li><input name="newpw_submit" type="submit" value="Process" /></li>
				</ul>
			</form>
			<br><br>				
		</div>
	</div>	
	
	<div class="box" style="margin-left:20px;margin-bottom:20px;">
		<div class="title">New email address <br> Cost : 3 token</div>
		<div class="settings">
			Choose a new email address.
			<br><br>
			<form id="newemail" action="view.php?page=settings" method="post">
				<ul>
					<li><input name="newemail_email" type="text" placeholder="New email address" maxlength="32" /></li>
					<li><input name="newemail_repeatemail" type="text" placeholder="Repeat New email address" maxlength="32" /></li>
					<li><input name="newemail_submit" type="submit" value="Process" /></li>
				</ul>
			</form>
			<br><br>				
		</div>
	</div>	
	
</div>		
<?php	
function convertToNumericEntities($string) {
	$convmap = array(0x80, 0x10ffff, 0, 0xffffff);
	return mb_encode_numericentity($string, $convmap, "UTF-8");
}

function generateRandomString($length = 16) 
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}
	
function handleNewEmail($db)
{	
	if (empty($_POST['newemail_email']))
	{
		$errors[] = 'Email required.';	
		return $errors;
	}
	else if (!filter_var($_POST['newemail_email'], FILTER_VALIDATE_EMAIL)) 
	{
		$errors[] = 'Invalid email.';
		return $errors;
	}
	if( $_POST['newemail_email'] != $_POST['newemail_repeatemail'])
	{
		$errors[] = 'Invalid Email repeat.';	
		return $errors;
	}
	
	$sth = $db->prepare("SELECT tokens
	 FROM users_infos WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();

	$tokens = $datauser[0]['tokens'];
	
	if($tokens < 3)
	{
		$errors[] = 'Not enough tokens.';	
		return $errors;
	}
	
	$dbEmail = htmlentities($_POST['newemail_email']);		
	$db->update('users_infos', array(
			'email' => $dbEmail
			),
			'id='.$_SESSION['player_id']
			);	
			
	$req = $db->prepare('UPDATE users_infos SET tokens=tokens-3 WHERE id='.$_SESSION['player_id']);
	$req->execute();
			
	header('Location: logout.php');
	exit();
}

function handleNewPw($db)
{
	if (empty($_POST['newpw_oldpassword']))
	{
		$errors[] = 'Old Password required.';	
		return $errors;
	}
	if (empty($_POST['newpw_password']))
	{
		$errors[] = 'Password required.';	
		return $errors;
	}
	else if (strlen($_POST['newpw_password']) < 8) 
	{
		$errors[] = 'Invalid password (8 characters minimum).';	
	}
	if( $_POST['newpw_password'] != $_POST['newpw_passwordRepeat'])
	{
		$errors[] = 'Invalid Password repeat.';	
		return $errors;
	}
	
	$sth = $db->prepare("SELECT password FROM users_infos WHERE id=:id");
	$sth->execute(array(
			':id' => $_SESSION['player_id']			
		));
	
	$user_infos = $sth->fetchAll();	
	
	$md5old_pw = md5($_POST['newpw_oldpassword']);
	if($user_infos[0]['password'] != $md5old_pw)
	{
		$errors[] = 'Invalid Old Password.';	
		return $errors;
	}
	
	$sth = $db->prepare("SELECT tokens
	 FROM users_infos WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();

	$tokens = $datauser[0]['tokens'];
	
	if($tokens < 1)
	{
		$errors[] = 'Not enough tokens.';	
		return $errors;
	}
	
	$req = $db->prepare('UPDATE users_infos SET tokens=tokens-1 WHERE id='.$_SESSION['player_id']);
	$req->execute();
	
	$db_pw = md5($_POST['newpw_password']);
	$db->update('users_infos', array(
			'password' => $db_pw
			),
			'id='.$_SESSION['player_id']
			);	
	
	header('Location: logout.php');
	exit();
}
function handleGenPw($db)
{	
	$new_pw = generateRandomString();
	$db_pw = md5($new_pw);
	
	
	$sth = $db->prepare("SELECT email FROM users_infos WHERE id=:id");
	$sth->execute(array(
			':id' => $_SESSION['player_id']			
		));
	
	$user_infos = $sth->fetchAll();
	
	$to = $user_infos[0]['email'];
	$subject = 'Andromeda new password';
	$body = "Hi,\n\nYour new password is: ".$new_pw."\n\n Have a nice day, \n Andromeda's team, \n www.andromeda-server.com.";
	$headers = "From: andromeda.server00@gmail.com" . "\r\n";
	if (!mail($to, $subject, $body, $headers)) 
	{
		$errors[] = 'Message delivery failed...';
		return $errors;
	}
	else
	{
		$db->update('users_infos', array(
			'password' => $db_pw
			),
			'id='.$_SESSION['player_id']
			);	
			
		header('Location: logout.php');
		exit();
	}				
}
function handleNewIgn($db)
{
	$errors = array();
	if (empty($_POST['newign_pseudo']))
	{
		$errors[] = 'In-Game Name required.';	
		return $errors;
	}
	if( $_POST['newign_pseudo'] != $_POST['newign_repeatpseudo'])
	{
		$errors[] = 'Invalid In-Game Name repeat.';	
		return $errors;
	}
	if (strlen($_POST['newign_pseudo']) > 32 || strlen($_POST['newign_pseudo']) < 3) 
	{
		$errors[] = 'Invalid In-Game Name (3-32 characters).';	
		return $errors;
	}
	
	$username = convertToNumericEntities(htmlentities($_POST['newign_pseudo']));
	$sth = $db->prepare("SELECT id FROM users WHERE username = :username");
		$sth->execute(array(
			':username' => $username			
		));
		$count = $sth->rowCount();

		if($count > 0)
		{
			$errors[] = 'In-Game Name already used';	
			return $errors;
		}
	
	$sth = $db->prepare("SELECT tokens
	 FROM users_infos WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();

	$tokens = $datauser[0]['tokens'];
	
	if($tokens < 2)
	{
		$errors[] = 'Not enough tokens.';	
		return $errors;
	}
	
	$req = $db->prepare('UPDATE users_infos SET tokens=tokens-2 WHERE id='.$_SESSION['player_id']);
	$req->execute();
			
	$db->update('users', array(
			'username' => $username
			),
			'id='.$_SESSION['player_id']
			);	
	
	header('Location: view.php?page=user&tab=infos&'.$_SESSION['player_id']);
	exit();
}

?>