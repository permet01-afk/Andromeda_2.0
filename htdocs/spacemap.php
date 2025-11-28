<?php

session_start();
if(!isset($_SESSION['terms_of_use']) || $_SESSION['terms_of_use'] != true)
{
	header('Location: index.php');
	exit();
}
if(!isset($_SESSION['loggedIn']) || $_SESSION['terms_of_use'] != true)
{
	header('Location: login.php');
	exit();
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

if($datauser[0]['factionid'] == 0)
{
	header('Location: company.php');
	exit();
}

$sid = sha1(rand(1000, 9999));
$req = $db->prepare('UPDATE users SET AuthTicket=:sid WHERE id=:id');
$req->execute(array('sid' => $sid, 'id' => $_SESSION['player_id']));

$returnvalue = $db->select('SELECT client_resolution FROM users_settings WHERE playerid= :account_id', array('account_id' => $_SESSION['player_id']));
$string = $returnvalue[0]['client_resolution'];

$str = substr($string, 0, strlen($string)-2);
$exploded = explode(',', $str);

$sth = $db->prepare("SELECT  mapid
 FROM users WHERE id = :id LIMIT 1");
$sth->execute(array(
				':id' => $_SESSION['player_id']
			));
$datauser = $sth->fetchAll();

?>

<html>
<head>
    <title>Andromeda - Play with your skills, not your money.</title>
	
    <style type="text/css" media="screen"> 
body {
    margin: 0;
    padding: 0;
    text-align:center;
    background: url("img/andromeda_spacemap.jpg");
    width: 100%;
    height: 100%;
}
    </style>

<meta charset="utf-8" /> 
		<script type="text/javascript" src="spacemap/swfobject.js"></script>
		<script type="text/javascript">
			var flashvars = {};
			var params = {};
			var attributes = {};
					
			flashvars.lang="es";
			flashvars.userID=<?php echo $_SESSION['player_id']; ?>;
			flashvars.factionid="VRU";
			flashvars.sessionID="<?php echo $sid; ?>";
			flashvars.basePath="spacemap/";
			flashvars.pid=563;
			flashvars.resolutionID=<?php echo $exploded[0]; ?>;
			flashvars.boardLink="127.0.0.1";
			flashvars.helplink="127.0.0.1";
			flashvars.chatHost="127.0.0.1";
			flashvars.host="127.0.0.1";
			
			flashvars.useHash=0;
			flashvars.gameXmlHash="060b9c86992a12a6d343395f64852876";
			flashvars.resourcesXmlHash="4f5d6e23ebb06278f110ba358dde28ec";
			flashvars.allowChat=1;
			flashvars.profileXmlHash="18287bc38698431e80f7cca05e6df2ca";
			flashvars.mapsXmlHash="F69E53DB549B9737E69FCE485AE32F15";
			flashvars.autoStartEnabled=0;
			flashvars.mapID=<?php echo $datauser[0]['mapid']; ?>;
			flashvars.supportedResolutions="0,1,2,3,4,5";
			flashvars.logConfig="0,300,4,5";
			flashvars.instantLogEnabled=1;
			flashvars.doubleClickAttackEnabled=1;
			flashvars.loadingClaim="LOADING...WAIT%2E%2E%2E";
			
			
			params.bgcolor="#000000"; 
			params.play="true"; 
			params.loop="true"; 
			params.menu="false"; 
			params.quality="best"; 
			params.wmode="direct"; 
			params.allowscriptaccess="always"; 
			params.allownetworking="true"; 
			params.allowfullscreen="true"; 
			params.swfliveconnect="true"; 
			
			attributes.name = "";
			attributes.styleclass = "";
			attributes.align = "";			
			swfobject.embedSWF("spacemap/main.swf?v=22", "flashContent", "<?php echo $exploded[1]; ?>", "<?php echo $exploded[2];?>", "9.0.0", false, flashvars, params, attributes);
		</script>
</head>

<body>
		<div id="flashContent">
			Get <a href="http://www.adobe.com/go/getflash">Adobe Flash Player</a>.
		</div>
	</body>
</html>
