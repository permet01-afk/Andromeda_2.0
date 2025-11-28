<?php 
	ini_set('max_execution_time', 300);
	
	include 'libs/database.php';
	include 'config/database.php';

	$db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
	
	$sth = $db->prepare("DELETE FROM users_infos WHERE is_verified = 0");
	$sth->execute();
	
	$sth = $db->prepare("DELETE FROM users_infos WHERE (SELECT count(id) FROM users WHERE users_infos.id = users.id) = 0");
	$sth->execute();

	$sth = $db->prepare("DELETE FROM users WHERE (SELECT count(id) FROM users_infos WHERE users_infos.id = users.id) = 0");
	$sth->execute();

	$sth = $db->prepare("DELETE FROM users_npc_lvl WHERE (SELECT count(id) FROM users_infos WHERE users_infos.id = users_npc_lvl.id) = 0");
	$sth->execute();

	$sth = $db->prepare("DELETE FROM users_npc_counts WHERE (SELECT count(id) FROM users_infos WHERE users_infos.id = users_npc_counts.id) = 0");
	$sth->execute();

	$sth = $db->prepare("DELETE FROM users_settings WHERE (SELECT count(id) FROM users_infos WHERE users_infos.id = users_settings.playerid) = 0");
	$sth->execute();

	$sth = $db->prepare("DELETE FROM player_reff WHERE (SELECT count(id) FROM users_infos WHERE users_infos.id = player_reff.id) = 0");
	$sth->execute();

	$sth = $db->prepare("DELETE FROM player_config WHERE (SELECT count(id) FROM users_infos WHERE users_infos.id = player_config.id) = 0");
	$sth->execute();

	$sth = $db->prepare("DELETE FROM player_cargo WHERE (SELECT count(id) FROM users_infos WHERE users_infos.id = player_cargo.id) = 0");
	$sth->execute();

	echo 'Done !';
	
?>