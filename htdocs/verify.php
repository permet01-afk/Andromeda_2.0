<?php 
	session_start();
	if(isset($_SESSION['loggedIn']) and $_SESSION['loggedIn'] == "true") 
	{
		header('Location: view.php?page=home');
		exit();
	}
	if(!isset($_SESSION['terms_of_use']) || $_SESSION['terms_of_use'] != true)
	{
		header('Location: index.php');
		exit();
	}

	ob_start();

	include 'libs/database.php';
	include 'config/database.php';

	$db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
	
	$sth = $db->prepare("SELECT * FROM users_infos WHERE id=:id");
	$sth->execute(array(
			':id' => $_SESSION['player_id']			
		));
	
	$user_infos = $sth->fetchAll();
	
	if($user_infos[0]['is_verified'] > 1)
	{
		$_SESSION['loggedIn'] = true; 
		header('Location: view.php?page=home');
		exit();
	}
	
	$errors = array();
		
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
	
	if((isset($_GET['action']) && $_GET['action'] == 'send') || !empty($_POST['resend_submit']))
	{
		$key = generateRandomString();			
		
		$db->update('users_infos', array(
			'key' => $key
			),
			'id='.$_SESSION['player_id']
			);	
			
		$to = $user_infos[0]['email'];
		$subject = 'Andromeda account activation';
		$body = "Hi,\n\nYour activation key is: ".$key."\n\n Have a nice day, \n Andromeda's team, \n www.andromeda-server.com.";
		$headers = "From: andromeda.server01@gmail.com" . "\r\n";
	if(!mail($to, $subject, $body, $headers))
		{
			$errors[] = 'An admin will activate your account. Come back in few hours';
		}
	}
	
	if(!empty($_POST['verify_submit']) && !empty($_POST['verify_key']))
	{
		if($_POST['verify_key'] == $user_infos[0]['key'])	
		{
			$db->update('users_infos', array(
			'is_verified' => 1
			),
			'id='.$_SESSION['player_id']
			);
			$_SESSION['loggedIn'] = true; 
			header('Location: view.php?page=home');
			exit();
		}
		else
		{
			$errors[] = 'Invalid key.';	
		}		
	}	
 ?>	
 <html>
	 <head>
		 <title>Email verification</title>
		 <link rel="stylesheet" type="text/css" href="styles/default.css" />
		 <link rel="stylesheet" type="text/css" href="styles/login.css" />
	 </head>
	 <body>
		
		<div class="logo"></div>
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
		<div id=register>
			</br>
			<center>Please verify your account by entering the key sent to </br>you by email.</center>
			</br>
			<form id="verification" action="verify.php" method="post">
				<ul>
					<li><input name="verify_key" type="text" placeholder="Key" maxlength="32" /></li>		
					<li><input name="verify_submit" type="submit" value="Verify" /></li>
				</ul>
			</form>
			</br>
			</br>
			<center>Your email: <?= $user_infos[0]['email'] ?>.</center>
			</br>
			</br>
			<form id="resend" action="verify.php" method="post">
				<ul>					
					<li><input name="resend_submit" type="submit" value="Resend" /></li>
				</ul>
			</form>
		</div>
		<center>
		
		</br>
		</br>
		<!-- andromeda -->
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<ins class="adsbygoogle"
		style="display:inline-block;width:728px;height:90px"
		data-ad-client="ca-pub-5037168492183176"
		data-ad-slot="4503188675"></ins>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
		</script></center>
		</body>
		</html>
		<?php 
	ob_end_flush();
	
	function convertToNumericEntities($string) {
	    $convmap = array(0x80, 0x10ffff, 0, 0xffffff);
	    return mb_encode_numericentity($string, $convmap, "UTF-8");
	}
	
 ?>	
	