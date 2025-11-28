<?php session_start(); 
if(!isset($_SESSION['terms_of_use']) || $_SESSION['terms_of_use'] != true)
{
	header('Location: index.php');
}
if(!isset($_SESSION['loggedIn']) || $_SESSION['terms_of_use'] != true)
{
	header('Location: login.php');
}

include 'libs/database.php';
include 'config/database.php';

$db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);

$sth = $db->prepare("SELECT factionid
 FROM users WHERE id = :id LIMIT 1");
$sth->execute(array(
				':id' => $_SESSION['player_id']
			));
$datauser = $sth->fetchAll();

if($datauser[0]['factionid'] > 0)
{
	header('Location: view.php');
}
if(!empty($_GET['factionid']))
{
	switch($_GET['factionid'])
	{
		case 1:
			$req = $db->prepare('UPDATE users SET factionid=1, locx=2000, locy=1100, mapid=1 WHERE id='.$_SESSION['player_id']);
			$req->execute();
			header('Location: view.php');
			break;
		case 2:
			$req = $db->prepare('UPDATE users SET factionid=2, locx=18500, locy=1100, mapid=5 WHERE id='.$_SESSION['player_id']);
			$req->execute();
			header('Location: view.php');
			break;
		case 3:
			$req = $db->prepare('UPDATE users SET factionid=3, locx=19200, locy=11300, mapid=9 WHERE id='.$_SESSION['player_id']);
			$req->execute();
			header('Location: view.php');
			break;
		default:
			echo 'INVALID COMPANY';
			break;
	}
 }
?>
<!DOCTYPE HTML>

<style>
body
{
	background-color:black;
	background:url('img/bg.jpg');
	background-size:cover;
	color:lightgray;
	font-family:Georgia;
}
</style>

<html>
<head>
<title>Andromeda</title>
</head>
<body>
<center>
	<img style="height:165px;" src="img/logo.png">
	<div style="margin-left:auto;opacity:0.90; height:20px; background-color: #223548;border-top-left-radius: 25px;border-top-right-radius: 25px; color:white; margin-right:auto; display:block; width:400px; margin-top:25px; font-size:13px;">
	Choose a company
	</div>
	<div style="margin-left:auto;opacity:0.90; height:120px; overflow:auto;background-color: #466D94; color:black; margin-right:auto; display:block; width:400px; padding-top:5px; font-size:13px;">
			
			<a href="company.php?factionid=1">
				<img class="company_logo" src="img/mmo.jpg">
			</a>
			
			<a href="company.php?factionid=2">
				<img class="company_logo" src="img/eic.jpg">
			</a>
			
			<a href="company.php?factionid=3">
				<img class="company_logo" src="img/vru.jpg">
			</a>
	</div>
	<div style="margin-left:auto;opacity:0.90; height:20px; background-color: #223548;border-bottom-left-radius: 25px;border-bottom-right-radius: 25px; color:white; margin-right:auto; display:block; width:400px; margin-top:0px; font-size:13px;">
			
	</div>
</center>
</body>
</html>