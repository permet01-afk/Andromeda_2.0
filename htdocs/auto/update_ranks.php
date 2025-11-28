<?php //if(isset($_GET['mwsp']) and $_GET['mwsp'] = "ra4f51bdkbze") { 

echo 'Updated <b>';

ini_set('max_execution_time', 300000);
$timestart=microtime(true);
$debug = false; // enable / disable debug mode

$last_active_limit = time() - (3600*14*24);

class Connexion 
{
     
    public static function bdd() {     
        try 
        {
            $bdd = new PDO('mysql:host=127.0.0.1;dbname=andromeda', 'root', 'rutifgjd45dsf45');
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } 
        catch (Exception $e) 
        {
        die('Erreur : '. $e->getMessage());
        }
            return $bdd;
        }
}

//clean update
$req = Connexion::bdd()->prepare("UPDATE users SET grade=1 WHERE lastlogin < $last_active_limit OR (SELECT count(id) AS is_ban FROM bans WHERE bans.user_id = users.id AND bans.timestamp_expire > UNIX_TIMESTAMP() ) <> 0");
$req->execute();


// THEN WE UPDATE ARNKS
function count_users_from_faction($faction_id)
{
	$last_active_limit = time() - (3600*14*24);//1209600
	$req = Connexion::bdd()->prepare("SELECT count(id) FROM `users` WHERE lastlogin > $last_active_limit AND (SELECT count(id) AS is_ban FROM bans WHERE bans.user_id = users.id AND bans.timestamp_expire > UNIX_TIMESTAMP() ) = 0 AND rankpoints > 0 AND factionid=$faction_id");
	$req->execute();

	$data = $req->fetch();

	return $data[0];
}


function get_users_from_faction($faction_id)
{
	$last_active_limit = time() - (3600*14*24);
	$req = Connexion::bdd()->prepare("SELECT id, username, rankpoints FROM `users` WHERE lastlogin > $last_active_limit AND (SELECT count(id) AS is_ban FROM bans WHERE bans.user_id = users.id AND bans.timestamp_expire > UNIX_TIMESTAMP() ) = 0 AND factionid=$faction_id AND rankpoints > 0 ORDER BY rankpoints DESC");
	$req->execute();

	$data = $req->fetchAll();

	return $data;
}

function edit_rank($id, $rank)
{
	$req = Connexion::bdd()->prepare('UPDATE `users` SET `grade`=:rank WHERE id=:id');
	$req->execute(array('rank' => $rank, 'id' => $id));
}

$rank = array(1 => 10,
				2 => 10,
				3 => 10,
				4 => 10,
				5 => 10,
				6 => 9,
				7 => 8,//41
				8 => 7,//33
				9 => 5,//26
				10 => 4,
				11 => 3,
				12 => 3,
				13 => 2,
				14 => 2,
				15 => 2,
				16 => 2,
				17 => 1.75,//3
				18 => 1,
				19 => 0.25
			);

			
// We update MMO ranks
$addition = 0;
$userscount = count_users_from_faction(1) - 1;
$predictcount = 0;
$userslist = get_users_from_faction(1);

echo "<br/>MMO player : $userscount";

$req = Connexion::bdd()->prepare("UPDATE server_statistics SET sval=$userscount WHERE skey='active_MMO'");
$req->execute();

$rank = array_reverse($rank, true);

echo '<br/><font color="brown">- '.$userslist[0]['username']. '</font> set rank 20';
edit_rank($userslist[0]['id'], 20);
unset($userslist[0]);

foreach ($rank as $key => $value) {
	$predict = $userscount / 100 * $value;
	if ($predictcount < $userscount)
	{
		$predictcount += ceil($predict);
		// echo 'rank '.$key. ' = '.$value.' should have '.ceil($predict).' users<br>';
		// echo 'Players in it :<br>';
		$tmp = 0;
		foreach ($userslist as $key2 => $value2) {
				if ($tmp < $predict)
				{
					if($debug)
					{
						echo '<br/>- '.$userslist[$key2]['username'].' set rank '.$key;
					}
					edit_rank($userslist[$key2]['id'], $key);
					unset($userslist[$key2]);
					$tmp += 1;
				}
		}
	}
	$addition += $value;
}

$rank = array(1 => 10,
				2 => 10,
				3 => 10,
				4 => 10,
				5 => 10,
				6 => 9,
				7 => 8,//41
				8 => 7,//33
				9 => 5,//26
				10 => 4,
				11 => 3,
				12 => 3,
				13 => 2,
				14 => 2,
				15 => 2,
				16 => 2,
				17 => 1.75,//3
				18 => 1,
				19 => 0.25
			);
			
// We update EIC ranks
$addition = 0;
$userscount = count_users_from_faction(2) - 1;
$predictcount = 0;
$userslist = get_users_from_faction(2);

echo "<br/>EIC player : $userscount";
$req = Connexion::bdd()->prepare("UPDATE server_statistics SET sval=$userscount WHERE skey='active_EIC'");
$req->execute();

$rank = array_reverse($rank, true);

echo '<br/><br/><font color="blue">- '.$userslist[0]['username']. '</font> set rank 20';
edit_rank($userslist[0]['id'], 20);
unset($userslist[0]);

foreach ($rank as $key => $value) {
	$predict = $userscount / 100 * $value;
	if ($predictcount < $userscount)
	{
		$predictcount += ceil($predict);
		// echo 'rank '.$key. ' = '.$value.' should have '.ceil($predict).' users<br>';
		// echo 'Players in it :<br>';
		$tmp = 0;
		foreach ($userslist as $key2 => $value2) {
				if ($tmp < $predict)
				{
					if($debug)
					{
						echo '<br/>- '.$userslist[$key2]['username'].' set rank '.$key.'<br>';
					}
					edit_rank($userslist[$key2]['id'], $key);
					unset($userslist[$key2]);
					$tmp += 1;
				}
		}
	}
	$addition += $value;
}

$rank = array(1 => 10,
				2 => 10,
				3 => 10,
				4 => 10,
				5 => 10,
				6 => 9,
				7 => 8,//41
				8 => 7,//33
				9 => 5,//26
				10 => 4,
				11 => 3,
				12 => 3,
				13 => 2,
				14 => 2,
				15 => 2,
				16 => 2,
				17 => 1.75,//3
				18 => 1,
				19 => 0.25
			);
			
// We update VRU ranks
$addition = 0;
$userscount = count_users_from_faction(3) - 1;
$predictcount = 0;
$userslist = get_users_from_faction(3);

echo "<br/>VRU player : $userscount";
$req = Connexion::bdd()->prepare("UPDATE server_statistics SET sval=$userscount WHERE skey='active_VRU'");
$req->execute();

$rank = array_reverse($rank, true);

echo '<br/><font color="green">- '.$userslist[0]['username']. '</font> set rank 20';
edit_rank($userslist[0]['id'], 20);
unset($userslist[0]);

foreach ($rank as $key => $value) {
	$predict = $userscount / 100 * $value;
	if ($predictcount < $userscount)
	{
		$predictcount += ceil($predict);
		// echo 'rank '.$key. ' = '.$value.' should have '.ceil($predict).' users<br>';
		// echo 'Players in it :<br>';
		$tmp = 0;
		foreach ($userslist as $key2 => $value2) {
				if ($tmp < $predict)
				{
					if($debug)
					{
						echo '<br/>- '.$userslist[$key2]['username'].' set rank '.$key.'<br>';
					}
					edit_rank($userslist[$key2]['id'], $key);
					unset($userslist[$key2]);
					$tmp += 1;
				}
		}
	}
	$addition += $value;
}


echo '<h1 style="color:silver;">Script executed in '.number_format((microtime(true) - $timestart), 3).' seconds</h1>';

?>