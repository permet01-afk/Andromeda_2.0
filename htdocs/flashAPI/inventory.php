<?php

require '../libs/Session.php';
Session::init();

$logged = Session::get('loggedIn');
if ($logged == false)
{
	Session::destroy();
	header('location: /index');
	exit;
}
$account_ID = Session::get('account_ID');

class Connexion 
{
     
    public static function bdd() {     
        try 
        {
            $bdd = new PDO('mysql:host=127.0.0.1;dbname=darkorbit', 'root', '');
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } 
        catch (Exception $e) 
        {
        die('Erreur : '. $e->getMessage());
        }
            return $bdd;
        }
}

function add_to_db($arg)
{
	$req = Connexion::bdd()->prepare('INSERT INTO `debug`(`show`) VALUES(:show)');
	$req->execute(array('show' => $arg));
}

function update_config_damage($id, $min_dmg, $max_dmg, $config)
{
	$req = Connexion::bdd()->prepare('UPDATE player_config SET min_damage=:min_dmg, max_damage=:max_dmg WHERE player_id=:id AND config_id=:config_id');
	$req->execute(array('id' => $id, 'min_dmg' => $min_dmg, 'max_dmg' => $max_dmg, 'config_id' => $config));
}

function calcul_config_laser_damage($id, $config)
{

	$req = Connexion::bdd()->prepare('SELECT count(id) FROM `users_items` WHERE player_id=:id and item_id = 1 AND OnConfig_'.$config.' = 1 AND Config'.$config.'_DroneID = 0');
	$req->execute(array('id' => $id));

	$ship_laser_count = $req->fetch();

	$req = Connexion::bdd()->prepare('SELECT count(id) FROM `users_items` WHERE player_id=:id and item_id = 1 AND OnConfig_'.$config.' = 1 AND Config'.$config.'_DroneID != 0');
	$req->execute(array('id' => $id));

	$drone_laser_count = $req->fetch();

	$ship_laser_dmg = $ship_laser_count[0] * 150;
	$drone_laser_dmg = $drone_laser_count[0] * 165; // 165 because 150 / 100 * 110 (more dmg iris lvl 6)

	$full_laser_dmg = $ship_laser_dmg + $drone_laser_dmg;
	$full_laser_dmg = $full_laser_dmg / 100 * 130; // Add 30% dmg from promerium boost
	$full_laser_dmg = $full_laser_dmg / 100 * 120; // Add 20% dmg from damage booster

	$max_damage = ceil($full_laser_dmg);
	$min_damage = ceil($max_damage / 100 * 75.3);

	update_config_damage($id, $min_damage, $max_damage, $config);
}

function update_config_shield($id, $shield, $config)
{
	$req = Connexion::bdd()->prepare('UPDATE player_config SET max_shield=:max_shield, current_shield=:current_shield WHERE player_id=:id AND config_id=:config_id');
	$req->execute(array('id' => $id, 'max_shield' => $shield, 'current_shield' => 0, 'config_id' => $config));
}

function calcul_config_shield($id, $config)
{

	$req = Connexion::bdd()->prepare('SELECT count(id) FROM `users_items` WHERE player_id=:id and item_id = 2 AND OnConfig_'.$config.' = 1 AND Config'.$config.'_DroneID = 0');
	$req->execute(array('id' => $id));

	$ship_shield_count = $req->fetch();

	$req = Connexion::bdd()->prepare('SELECT count(id) FROM `users_items` WHERE player_id=:id and item_id = 2 AND OnConfig_'.$config.' = 1 AND Config'.$config.'_DroneID != 0');
	$req->execute(array('id' => $id));

	$drone_shield_count = $req->fetch();

	$ship_shield = $ship_shield_count[0] * 10000;
	$drone_shield = $drone_shield_count[0] * 12000; // 165 because 150 / 100 * 110 (more dmg iris lvl 6)

	$full_shield = $ship_shield + $drone_shield;

	if (get_ship($id) == 54)
	{
		$full_shield = $full_shield + ($full_shield *0.05);
	}

	update_config_shield($id, $full_shield, $config);
}

function update_config_speed($id, $speed, $config)
{
	$req = Connexion::bdd()->prepare('UPDATE player_config SET ship_speed=:speed WHERE player_id=:id AND config_id=:config_id');
	$req->execute(array('id' => $id, 'speed' => $speed, 'config_id' => $config));
}

function calcul_config_speed($id, $config)
{

	$req = Connexion::bdd()->prepare('SELECT count(id) FROM `users_items` WHERE player_id=:id and item_id = 4 AND OnConfig_'.$config.' = 1 AND Config'.$config.'_DroneID = 0');
	$req->execute(array('id' => $id));

	$ship_speed_count = $req->fetch();

	$ship_infos = ship_infos(Session::get('account_ID'));

	$base_ship_speed = $ship_infos[0]['base_speed'];

	$ship_speed = $ship_speed_count[0] * 10;
	$full_speed = $base_ship_speed + $ship_speed;

	update_config_speed($id, $full_speed, $config);
}

function get_config1_item_lasers($id)
{
	$req = Connexion::bdd()->prepare('SELECT users_items.id, items.category FROM users_items, items WHERE users_items.player_id=:id AND users_items.OnConfig_1=1 AND items.id=users_items.item_id AND items.category="laser" AND users_items.Config1_DroneID=0');
	$req->execute(array('id' => $id));
	
	$data = $req->fetchAll();

	return $data;
}

function get_config2_item_lasers($id)
{
	$req = Connexion::bdd()->prepare('SELECT users_items.id, items.category FROM users_items, items WHERE users_items.player_id=:id AND users_items.OnConfig_2=1 AND items.id=users_items.item_id AND items.category="laser" AND users_items.Config2_DroneID=0');
	$req->execute(array('id' => $id));
	
	$data = $req->fetchAll();

	return $data;
}

function get_config1_item_generators($id)
{
	$req = Connexion::bdd()->prepare('SELECT users_items.id, items.category FROM users_items, items WHERE users_items.player_id=:id AND users_items.OnConfig_1=1 AND items.id=users_items.item_id AND items.category="generator" AND users_items.Config1_DroneID=0');
	$req->execute(array('id' => $id));
	
	$data = $req->fetchAll();

	return $data;
}


function get_config2_item_generators($id)
{
	$req = Connexion::bdd()->prepare('SELECT users_items.id, items.category FROM users_items, items WHERE users_items.player_id=:id AND users_items.OnConfig_2=1 AND items.id=users_items.item_id AND items.category="generator" AND users_items.Config2_DroneID=0');
	$req->execute(array('id' => $id));
	
	$data = $req->fetchAll();

	return $data;
}

function get_config1_item_drones($id, $drone_id)
{
	$req = Connexion::bdd()->prepare('SELECT users_items.id, users_items.Config1_DroneID, items.category FROM users_items, items WHERE users_items.player_id=:id AND users_items.OnConfig_1=1 AND items.id=users_items.item_id AND users_items.Config1_DroneID=:drone_id');
	$req->execute(array('id' => $id, 'drone_id' => $drone_id));
	
	$data = $req->fetchAll();

	return $data;
}

function get_config2_item_drones($id, $drone_id)
{
	$req = Connexion::bdd()->prepare('SELECT users_items.id, users_items.Config2_DroneID, items.category FROM users_items, items WHERE users_items.player_id=:id AND users_items.OnConfig_2=1 AND items.id=users_items.item_id AND users_items.Config2_DroneID=:drone_id');
	$req->execute(array('id' => $id, 'drone_id' => $drone_id));
	
	$data = $req->fetchAll();

	return $data;
}


function get_user_item($id)
{
	$req = Connexion::bdd()->prepare('SELECT users_items.id, items.id AS item_id, items.name FROM users_items, items WHERE items.id = users_items.item_id AND item_id != 3 AND users_items.player_id =:id');
	$req->execute(array('id' => $id));
	
	$data = $req->fetchAll();

	return $data;
}

function get_user_drone($id)
{
	$req = Connexion::bdd()->prepare('SELECT users_items.id, items.id AS item_id, items.name FROM users_items, items WHERE items.id = users_items.item_id AND item_id = 3 AND users_items.player_id =:id');
	$req->execute(array('id' => $id));
	
	$data = $req->fetchAll();

	return $data;
}

function get_ship($id)
{
	$req = Connexion::bdd()->prepare('SELECT shipid FROM `users` WHERE  id=:id');
	$req->execute(array('id' => $id));
	
	$data = $req->fetch();
	return $data[0];
}

function ship_infos($id)
{
	$req = Connexion::bdd()->prepare('SELECT shipid FROM `users` WHERE  id=:id');
	$req->execute(array('id' => $id));
	
	$data = $req->fetch();

	$req = Connexion::bdd()->prepare('SELECT laser, generator, extras, ship_name, base_speed FROM `ships` WHERE  ship_id=:id');
	$req->execute(array('id' => $data[0]));
	
	$data = $req->fetchAll();

	return $data;
}



function get_item_description($id)
{

	$req = Connexion::bdd()->prepare('SELECT DISTINCT items.* FROM users_items, items WHERE items.id = users_items.item_id AND users_items.player_id =:id');
	$req->execute(array('id' => $id));

	$data = $req->fetchAll();

	return $data;
}

function count_item($id, $config, $category, $drone = 0, $drone_id = 0)
{
	if ($config == 1 && $drone == 0)
	{
		$req = Connexion::bdd()->prepare('SELECT count(users_items.id) FROM users_items, items WHERE users_items.player_id=:id AND items.id=users_items.item_id AND items.category=:category AND users_items.OnConfig_1=1 AND users_items.Config1_DroneID=0');
		$req->execute(array('id' => $id, 'category' => substr($category, 0, -1)));
	}

	elseif ($config == 2 && $drone == 0)
	{
		$req = Connexion::bdd()->prepare('SELECT count(users_items.id) FROM users_items, items WHERE users_items.player_id=:id AND items.id=users_items.item_id AND items.category=:category AND users_items.OnConfig_2=1 AND users_items.Config2_DroneID=0');
		$req->execute(array('id' => $id, 'category' => substr($category, 0, -1)));
	}

	elseif ($config == 1 && $drone == 1)
	{
		$req = Connexion::bdd()->prepare('SELECT count(users_items.id) FROM users_items, items WHERE users_items.player_id=:id AND items.id=users_items.item_id AND users_items.OnConfig_1=1 AND users_items.Config1_DroneID =:drone_id');
		$req->execute(array('id' => $id, 'drone_id' => $drone_id));
	}

	elseif ($config == 2 && $drone == 1)
	{
		$req = Connexion::bdd()->prepare('SELECT count(users_items.id) FROM users_items, items WHERE users_items.player_id=:id AND items.id=users_items.item_id AND users_items.OnConfig_2=1 AND users_items.Config2_DroneID =:drone_id');
		$req->execute(array('id' => $id, 'drone_id' => $drone_id));
	}

	$data = $req->fetch();

	return $data[0];
}

function move_item($id, $item_id, $from, $to, $config, $category, $drone_id = 0)
{
	if ($from == 'inventory' && $to == 'ship' && $config == 1)
	{
		$req = Connexion::bdd()->prepare('UPDATE users_items, items SET users_items.OnConfig_1=1 WHERE users_items.player_id=:id AND users_items.id=:item_id AND items.id=users_items.item_id AND items.category=:category');
		$req->execute(array('id' => $id, 'item_id' => $item_id, 'category' => substr($category, 0, -1)));
		return;
	}

	if ($from == 'ship' && $to == 'inventory' && $config == 1)
	{
		$req = Connexion::bdd()->prepare('UPDATE users_items, items SET users_items.OnConfig_1=0 WHERE users_items.player_id=:id AND users_items.id=:item_id AND items.id=users_items.item_id AND items.category=:category');
		$req->execute(array('id' => $id, 'item_id' => $item_id, 'category' => substr($category, 0, -1)));
		return;
	}

	if ($from == 'inventory' && $to == 'ship' && $config == 2)
	{
		$req = Connexion::bdd()->prepare('UPDATE users_items, items SET users_items.OnConfig_2=1 WHERE users_items.player_id=:id AND users_items.id=:item_id AND items.id=users_items.item_id AND items.category=:category');
		$req->execute(array('id' => $id, 'item_id' => $item_id, 'category' => substr($category, 0, -1)));
		return;
	}

	if ($from == 'ship' && $to == 'inventory' && $config == 2)
	{
		$req = Connexion::bdd()->prepare('UPDATE users_items, items SET users_items.OnConfig_2=0 WHERE users_items.player_id=:id AND users_items.id=:item_id AND items.id=users_items.item_id AND items.category=:category');
		$req->execute(array('id' => $id, 'item_id' => $item_id, 'category' => substr($category, 0, -1)));
		return;
	}

	if ($from == 'inventory' && $to == 'drone' && $config == 1)
	{
		$req = Connexion::bdd()->prepare('UPDATE users_items, items SET users_items.OnConfig_1=1, users_items.Config1_DroneID=:drone_id WHERE users_items.player_id=:id AND users_items.id=:item_id AND items.id=users_items.item_id');
		$req->execute(array('drone_id' => $drone_id, 'id' => $id, 'item_id' => $item_id));
		return;
	}

	if ($from == 'drone' && $to == 'inventory' && $config == 1)
	{
		$req = Connexion::bdd()->prepare('UPDATE users_items, items SET users_items.OnConfig_1=0, users_items.Config1_DroneID=:drone_id WHERE users_items.player_id=:id AND users_items.id=:item_id AND items.id=users_items.item_id');
		$req->execute(array('drone_id' => $drone_id, 'id' => $id, 'item_id' => $item_id));
		return;
	}

	if ($from == 'inventory' && $to == 'drone' && $config == 2)
	{
		$req = Connexion::bdd()->prepare('UPDATE users_items, items SET users_items.OnConfig_2=1, users_items.Config2_DroneID=:drone_id WHERE users_items.player_id=:id AND users_items.id=:item_id AND items.id=users_items.item_id');
		$req->execute(array('drone_id' => $drone_id, 'id' => $id, 'item_id' => $item_id));
		return;
	}

	if ($from == 'drone' && $to == 'inventory' && $config == 2)
	{
		$req = Connexion::bdd()->prepare('UPDATE users_items, items SET users_items.OnConfig_2=0, users_items.Config2_DroneID=:drone_id WHERE users_items.player_id=:id AND users_items.id=:item_id AND items.id=users_items.item_id');
		$req->execute(array('drone_id' => $drone_id, 'id' => $id, 'item_id' => $item_id));
		return;
	}
}

if (!empty($_POST))
{
	add_to_db(json_encode($_POST));
	$ship_info = ship_infos($account_ID);

	if($_POST['action'] == 'init')
	{
		if(!empty($_POST['params']))
		{
			$decoded = base64_decode($_POST['params']);
			$json_array = json_decode($decoded, true);

			if($json_array['nr'] == 1)
			{
				$userid = $account_ID;

				$filters = '{"isError":0,"data":{"ret":{"filters":{"weapons":[0,1,2],"generators":[3,4,5],"extras":[6,7,8,9,10,11],"ammunition":[12,13,14],"resources":[15],"drone_related":[16,17],"modules":[18],"pet_related":[19,20]},';


				$user_drone = get_user_drone($account_ID);
				$last_key = end(array_keys($user_drone));

				$drones = '';

				foreach ($user_drone as $key => $value) {
					if ($key == $last_key)
					{
						$drones = $drones.'{"I":"'.$user_drone[$key]['id'].'","L":3,"LV":5,"HP":"0%","EF":"10%/20%","SP":15625,"DE":"","DL":null,"SL":null,"repair":500,"currency":"uridium"}';
					}
					else
					{
						$drones = $drones.'{"I":"'.$user_drone[$key]['id'].'","L":3,"LV":5,"HP":"0%","EF":"10%/20%","SP":15625,"DE":"","DL":null,"SL":null,"repair":500,"currency":"uridium"},';	
					}
				}

				// $drones = '{"I":"59746","L":3,"LV":5,"HP":"0%","EF":"10%/20%","SP":15625,"DE":"","DL":null,"SL":null,"repair":500,"currency":"uridium"}';
              

				$hangars = '"hangars":[{"hangarID":"'.$userid.'","name":"","hangar_is_active":true,"hangar_is_selected":true,"general":{"ship":{"I":10,"HP":"266000","L":0,"SM":"ship_'.$ship_info[0]['ship_name'].'","M":["ship_'.$ship_info[0]['ship_name'].'","ship_goliath_design_enforcer"]},"drones":['.$drones.']},';
				$config1_item = get_config1_item_lasers($account_ID);
				$last_key = end(array_keys($config1_item));
				$config1_lasers = '';


				foreach ($config1_item as $key => $value) {
					if ($key == $last_key)
					{
						$config1_lasers = $config1_lasers.$config1_item[$key]['id'];
					}
					else
					{
						$config1_lasers = $config1_lasers.$config1_item[$key]['id'].',';
					}
				}

				$config1_item = get_config1_item_generators($account_ID);
				$last_key = end(array_keys($config1_item));
				$config1_generators = '';

				foreach ($config1_item as $key => $value) {
					if ($key == $last_key)
					{
						$config1_generators = $config1_generators.$config1_item[$key]['id'];
					}
					else
					{
						$config1_generators = $config1_generators.$config1_item[$key]['id'].',';
					}
				}

				$config2_item = get_config2_item_lasers($account_ID);
				$last_key = end(array_keys($config2_item));
				$config2_lasers = '';

				foreach ($config2_item as $key => $value) {
					if ($key == $last_key)
					{
						$config2_lasers = $config2_lasers.$config2_item[$key]['id'];
					}
					else
					{
						$config2_lasers = $config2_lasers.$config2_item[$key]['id'].',';
					}
				}

				$config2_item = get_config2_item_generators($account_ID);
				$last_key = end(array_keys($config2_item));
				$config2_generators = '';

				foreach ($config2_item as $key => $value) {
					if ($key == $last_key)
					{
						$config2_generators = $config2_generators.$config2_item[$key]['id'];
					}
					else
					{
						$config2_generators = $config2_generators.$config2_item[$key]['id'].',';
					}
				}


				$config1_extras = '';
				$config2_extras = '';

				$user_drone = get_user_drone($account_ID);
				$last_key = end(array_keys($user_drone));

				$config_drones1 = '';
				$config_drones2 = '';

				foreach ($user_drone as $key => $value) {
					$config_1_drones = get_config1_item_drones($account_ID, $user_drone[$key]['id']);
					$config_1_drones_last_key = end(array_keys($config_1_drones));
					$config_2_drones = get_config2_item_drones($account_ID, $user_drone[$key]['id']);
					$config_2_drones_last_key = end(array_keys($config_2_drones));
					$equipment1 = '';
					$equipment2 = '';
					foreach ($config_1_drones as $key1 => $value1) 
					{
						if ($user_drone[$key]['id'] == $config_1_drones[$key1]['Config1_DroneID'] && $key1 == $config_1_drones_last_key)
						{
							$equipment1 = $equipment1.'"'.$config_1_drones[$key1]['id'].'"';
						}
						if ($user_drone[$key]['id'] == $config_1_drones[$key1]['Config1_DroneID'] && $key1 != $config_1_drones_last_key)
						{
							$equipment1 = $equipment1.'"'.$config_1_drones[$key1]['id'].'",';
						}
					}

					foreach ($config_2_drones as $key2 => $value2) 
					{
						if ($user_drone[$key]['id'] == $config_2_drones[$key2]['Config2_DroneID'] && $key2 == $config_2_drones_last_key)
						{
							$equipment2 = $equipment2.'"'.$config_2_drones[$key2]['id'].'"';
						}
						if ($user_drone[$key]['id'] == $config_2_drones[$key2]['Config2_DroneID'] && $key2 != $config_2_drones_last_key)
						{
							$equipment2 = $equipment2.'"'.$config_2_drones[$key2]['id'].'",';
						}
					}

					if ($key == $last_key)
					{
						$config_drones1 = $config_drones1.'"'.$user_drone[$key]['id'].'":{"EQ":{"default":['.$equipment1.']}}';
					}
					else
					{
						$config_drones1 = $config_drones1.'"'.$user_drone[$key]['id'].'":{"EQ":{"default":['.$equipment1.']}},';	
					}

					if ($key == $last_key)
					{
						$config_drones2 = $config_drones2.'"'.$user_drone[$key]['id'].'":{"EQ":{"default":['.$equipment2.']}}';
					}
					else
					{
						$config_drones2 = $config_drones2.'"'.$user_drone[$key]['id'].'":{"EQ":{"default":['.$equipment2.']}},';	
					}
				}

				$config_text = '"config":{"1":{"ship":{"EQ":{"lasers":['.$config1_lasers.'],"generators":['.$config1_generators.'],"extras":['.$config1_extras.']}},"drones":{'.$config_drones1.'}},"2":{"ship":{"EQ":{"lasers":['.$config2_lasers.'],"generators":['.$config2_generators.'],"extras":['.$config2_extras.']}},"drones":{'.$config_drones2.'}}}}],';


				


				$items   = '"items":[';
				$items_end = '],';
				$user_item = get_user_item($account_ID);
				$last_key = end(array_keys($user_item));

				$i = 0;

				foreach ($user_item as $key => $value) {
					if ($key == $last_key)
					{
						$items = $items.'{"I":"'.$user_item[$key]['id'].'","LV":0,"L":'.$user_item[$key]['item_id'].',"S":'.$i.'}';
					}
					else
					{
						$items = $items.'{"I":"'.$user_item[$key]['id'].'","LV":0,"L":'.$user_item[$key]['item_id'].',"S":'.$i.'},';
					}
					$i++;					
				}

				$items = $items.$items_end;


				$itemsinfo = '"itemInfo":[';
				$itemsinfo_end = '],';
				
				$itemsinfo_goliath = '{"L":0,"name":"Goliath","T":22,"C":"ship","levels":[{"slotsets":{"lasers":{"T":[0],"Q":'.$ship_info[0]['laser'].'},"generators":{"T":[3,4],"Q":'.$ship_info[0]['generator'].'},"heavy_guns":{"T":[1],"Q":0},"extras":{"T":[11,9,7,8,10,6],"Q":'.$ship_info[0]['extras'].'}},"selling":{"credits":32000},"cdn":{"63x63":"c6c8a09a4749af691b6a9947cf2c6900","100x100":"5fcdb83e69b401d92cc1ae6abb172300","top":"a604cd4669b80a0ddd89fa54fc946300"}}]},{"L":5,"name":"Enforcer","T":21,"C":"ship","levels":[{"slotsets":{"lasers":{"T":[0],"Q":'.$ship_info[0]['laser'].'},"generators":{"T":[3,4],"Q":'.$ship_info[0]['generator'].'},"heavy_guns":{"T":[1],"Q":0},"extras":{"T":[11,9,7,8,10,6],"Q":'.$ship_info[0]['extras'].'}},"selling":{"credits":32000},"cdn":{"30x30": "910ad3dc8468f831274081d0fd79a700","63x63":"b2a8f157eec6af66d9f4f2fcdfa5dd00","100x100":"8b447eee59e1500e4367eba7578b0300","top":"aa24e60e333abc872ffe39b171b41200"}}]},';

				$itemsinfo = $itemsinfo.$itemsinfo_goliath;
				$loots = '"lootIds":["ship_'.$ship_info[0]['ship_name'].'",';
				$loots_end = ']}}}';

				$items_description = get_item_description($account_ID);
				$last_key = end(array_keys($items_description));

				foreach ($items_description as $key => $value) {
					if ($key == $last_key)
					{
						if ($items_description[$key]['id'] != 3)
						{
							$itemsinfo = $itemsinfo.'{"L":'.$items_description[$key]['id'].',"name":"'.$items_description[$key]['name'].'","T":'.$items_description[$key]['type'].',"C":"'.$items_description[$key]['category'].'","levels":[{"selling":{"credits":'.$items_description[$key]['selling_credits'].'},"cdn":{"30x30":"'.$items_description[$key]['cdn_30x30'].'","63x63":"'.$items_description[$key]['cdn_63x63'].'","100x100":"'.$items_description[$key]['cdn_100x100'].'"}}]}';
							$loots = $loots.'"'.$items_description[$key]['lootIds'].'"';
						}
						
						elseif ($items_description[$key]['id'] == 3)
						{
							$itemsinfo = $itemsinfo.'{"L":3,"name":"IrisDrone","T":23,"C":"drone","repair":500,"currency":"uridium","levels":[{"slotsets":{"default":{"T":[0,4,11,9,7,8,10],"Q":2},"design":{"T":[16],"Q":1}},"selling":{"credits":100000},"cdn":{"30x30":"14ebb8db8014dc4bd757fa2ef74de400","63x63":"9e918d6b19b7d5ead16addad0cc35200","100x100":"afba9239e233f506eff21528ab4e8f00","top":"79b048a4b8e05add7a2245ebe95d5e00"}},{"slotsets":{"default":{"T":[0,4,11,9,7,8,10],"Q":2},"design":{"T":[16],"Q":1}},"selling":{"credits":150000},"cdn":{"30x30":"d951657b84dd80549297702f018b1f00","63x63":"66f6d38952f8fb800abbd5e5d96f7700","100x100":"3b12423b93122055a76f250f307dee00","top":"cdcc2dc2ac60191715615ac48100e300"}},{"slotsets":{"default":{"T":[0,4,11,9,7,8,10],"Q":2},"design":{"T":[16],"Q":1}},"selling":{"credits":210000},"cdn":{"30x30":"b10d193b55f89656936668c1bc559900","63x63":"e3ab25f382927dfd082f290967524500","100x100":"e0dbd77afe0b998abb1be914ff0ca800","top":"9c4888900962a7ac074714cfaa206200"}},{"slotsets":{"default":{"T":[0,4,11,9,7,8,10],"Q":2},"design":{"T":[16],"Q":1}},"selling":{"credits":240000},"cdn":{"30x30":"9c481125952808f464742ffbb0a43500","63x63":"dd3a4359bfadba07d03989ec042b5e00","100x100":"a71d6fe22419ea188a5d2dd78e7b6900","top":"873f7968e96ba2b2aa6a867938171b00"}},{"slotsets":{"default":{"T":[0,4,11,9,7,8,10],"Q":2},"design":{"T":[16],"Q":1}},"selling":{"credits":315000},"cdn":{"30x30":"9cb3929ed0ea6e938eb50599a854d400","63x63":"18d7b8fbe928f1b26ae05e8cf8eab200","100x100":"0227efd2ca7b8749575885abd3d33e00","top":"e65761d42de94f1eeaa0c30e29d88e00"}},{"slotsets":{"default":{"T":[0,4,11,9,7,8,10],"Q":2},"design":{"T":[],"Q":0}},"selling":{"credits":500000},"cdn":{"30x30":"40860d1594e9b6841ccfa87963f8d800","63x63":"0cd363e0f68297796aeb1a1dc8725500","100x100":"915c51fde19ca5d0c4878221ae305f00","top":"b6aae2912b6e0eb1b49d50ab5caef400"}}]}';
							$loots = $loots.'"drone_iris"';
						}
					}
					else
					{
						if ($items_description[$key]['id'] != 3)
						{
							$itemsinfo = $itemsinfo.'{"L":'.$items_description[$key]['id'].',"name":"'.$items_description[$key]['name'].'","T":'.$items_description[$key]['type'].',"C":"'.$items_description[$key]['category'].'","levels":[{"selling":{"credits":'.$items_description[$key]['selling_credits'].'},"cdn":{"30x30":"'.$items_description[$key]['cdn_30x30'].'","63x63":"'.$items_description[$key]['cdn_63x63'].'","100x100":"'.$items_description[$key]['cdn_100x100'].'"}}]},';
							$loots = $loots.'"'.$items_description[$key]['lootIds'].'",';				
						}
						elseif ($items_description[$key]['id'] == 3)
						{
							$itemsinfo = $itemsinfo.'{"L":3,"name":"IrisDrone","T":23,"C":"drone","repair":500,"currency":"uridium","levels":[{"slotsets":{"default":{"T":[0,4,11,9,7,8,10],"Q":2},"design":{"T":[16],"Q":1}},"selling":{"credits":100000},"cdn":{"30x30":"14ebb8db8014dc4bd757fa2ef74de400","63x63":"9e918d6b19b7d5ead16addad0cc35200","100x100":"afba9239e233f506eff21528ab4e8f00","top":"79b048a4b8e05add7a2245ebe95d5e00"}},{"slotsets":{"default":{"T":[0,4,11,9,7,8,10],"Q":2},"design":{"T":[16],"Q":1}},"selling":{"credits":150000},"cdn":{"30x30":"d951657b84dd80549297702f018b1f00","63x63":"66f6d38952f8fb800abbd5e5d96f7700","100x100":"3b12423b93122055a76f250f307dee00","top":"cdcc2dc2ac60191715615ac48100e300"}},{"slotsets":{"default":{"T":[0,4,11,9,7,8,10],"Q":2},"design":{"T":[16],"Q":1}},"selling":{"credits":210000},"cdn":{"30x30":"b10d193b55f89656936668c1bc559900","63x63":"e3ab25f382927dfd082f290967524500","100x100":"e0dbd77afe0b998abb1be914ff0ca800","top":"9c4888900962a7ac074714cfaa206200"}},{"slotsets":{"default":{"T":[0,4,11,9,7,8,10],"Q":2},"design":{"T":[16],"Q":1}},"selling":{"credits":240000},"cdn":{"30x30":"9c481125952808f464742ffbb0a43500","63x63":"dd3a4359bfadba07d03989ec042b5e00","100x100":"a71d6fe22419ea188a5d2dd78e7b6900","top":"873f7968e96ba2b2aa6a867938171b00"}},{"slotsets":{"default":{"T":[0,4,11,9,7,8,10],"Q":2},"design":{"T":[16],"Q":1}},"selling":{"credits":315000},"cdn":{"30x30":"9cb3929ed0ea6e938eb50599a854d400","63x63":"18d7b8fbe928f1b26ae05e8cf8eab200","100x100":"0227efd2ca7b8749575885abd3d33e00","top":"e65761d42de94f1eeaa0c30e29d88e00"}},{"slotsets":{"default":{"T":[0,4,11,9,7,8,10],"Q":2},"design":{"T":[],"Q":0}},"selling":{"credits":500000},"cdn":{"30x30":"40860d1594e9b6841ccfa87963f8d800","63x63":"0cd363e0f68297796aeb1a1dc8725500","100x100":"915c51fde19ca5d0c4878221ae305f00","top":"b6aae2912b6e0eb1b49d50ab5caef400"}}]},';
							$loots = $loots.'"drone_iris",';
						}
					}
				}

				$itemsinfo = $itemsinfo.$itemsinfo_end;

				$loots = $loots.',"ship_goliath_design_enforcer"'.$loots_end;


				$userinfo = '"userInfo":{"factionRelated":"mmo"}},';
				$money = '"money":{"uridium":"10,000","credits":"55,000"},';
				$map = '"map":{"types":["Weapon_LaserType","Weapon_HellstormLauncherType","Weapon_WeaponType","Generator_EngineType","Generator_ShieldType","Generator_GeneratorType","Extra_BoosterType","Extra_Cpu_CPUType","Extra_ModuleType","Extra_RobotType","Extra_UpgradeType","Extra_ExtraType","Weapon_Ammo_LaserType","Weapon_Ammo_RocketType","Weapon_Ammo_AmmunitionType","Resource_OreType","Drone_Design_DroneDesignType","Drone_Formation_DroneFormationType","Module_StationModuleType","Pet_PetGearType","Pet_AIProtocolType","Ship_ShipType","Item_ItemType"],';
				$data = $filters.$hangars.$config_text.$items.$itemsinfo.$userinfo.$money.$map.$loots;

				$data = preg_replace('/(\v|\s)+/', '', $data);
				echo base64_encode($data);
			}
		}
	}

	if($_POST['action'] == 'changeShipModel')
	{
		$decoded = base64_decode($_POST['params']);
		$json_array = json_decode($decoded, true);
		if ($json_array['lootId'][0] == "ship_goliath_design_enforcer")
		{
			$data = '{"isError":0,"data":{"ret":1,"money":{"uridium":"7.245","credits":"37.227.319"}}}';
			echo base64_encode($data);
		}

	}

	if($_POST['action'] == 'move')
	{
		$ret = '';
		$data = '{"isError":0,"data":{"ret":1,"money":{"uridium":"7.246","credits":"37.227.319"}}}';
		$decoded = base64_decode($_POST['params']);
		$json_array = json_decode($decoded, true);

		if ($json_array['from']['target'] == 'ship' && $json_array['to']['target'] == 'inventory')
		{
			if ($json_array['action'] == 'move')
			{
				foreach ($json_array['from']['items'] as $value) 
				{
					$data = '{"isError":0,"data":{"ret":1,"money":{"uridium":"7.246","credits":"37.227.319"}}}';
					move_item($account_ID, $value, $json_array['from']['target'], $json_array['to']['target'], $json_array['to']['configId'], $json_array['from']['slotset']);
				}
			}
		}

		if ($json_array['from']['target'] == 'inventory' && $json_array['to']['target'] == 'ship')
		{
			if ($json_array['action'] == 'move')
			{

				$item_count = count_item($account_ID, $json_array['to']['configId'], $json_array['to']['slotset']);
				

				$ship_infos = ship_infos(Session::get('account_ID'));

				if ($json_array['to']['slotset'] == 'generators')
				{
					$max_item = $ship_infos[0]['generator'];
				}
				elseif ($json_array['to']['slotset'] == 'lasers') 
				{
					$max_item = $ship_infos[0]['laser'];
				}
				else
				{
					$max_item = 0;
				}

				$i = $item_count;
				$last_key = end(array_keys($json_array['from']['items']));
				foreach ($json_array['from']['items'] as $key => $value) 
				{
					if ($i == $max_item)
					{
						if ($key == $last_key)
						{
							$ret = $ret.'"'.$value.'"';
						}
						else
						{
							$ret = $ret.'"'.$value.'",';
						}

						$data = '{"isError":0,"data":{"ret":['.$ret.'],"money":{"uridium":"7.246","credits":"37.227.319"}}}';
					}
					if ($i < $max_item)
					{
						move_item($account_ID, $value, $json_array['from']['target'], $json_array['to']['target'], $json_array['to']['configId'], $json_array['to']['slotset']);
						$data = '{"isError":0,"data":{"ret":1,"money":{"uridium":"7.246","credits":"37.227.319"}}}';
						$i++;
					}
				}
			}
		}

		if ($json_array['from']['target'] == 'drone' && $json_array['to']['target'] == 'inventory')
		{
			if ($json_array['action'] == 'move')
			{
				foreach ($json_array['from']['items'] as $value) 
				{
					$data = '{"isError":0,"data":{"ret":1,"money":{"uridium":"7.246","credits":"37.227.319"}}}';
					move_item($account_ID, $value, $json_array['from']['target'], $json_array['to']['target'], $json_array['to']['configId'], $json_array['from']['slotset'], 0);
				}
			}
		}

		if ($json_array['from']['target'] == 'inventory' && $json_array['to']['target'] == 'drone')
		{
			$item_count = count_item($account_ID, $json_array['to']['configId'], $json_array['to']['slotset'], 1, $json_array['to']['droneId']);
			$max_item = 2;
			$i = $item_count;
			$last_key = end(array_keys($json_array['from']['items']));
			foreach ($json_array['from']['items'] as $key => $value) 
			{
				if ($i == $max_item)
				{
					if ($key == $last_key)
					{
						$ret = $ret.'"'.$value.'"';
					}
					else
					{
						$ret = $ret.'"'.$value.'",';
					}

					$data = '{"isError":0,"data":{"ret":['.$ret.'],"money":{"uridium":"7.245","credits":"37.227.319"}}}';
				}
				if ($i < $max_item)
				{
					move_item($account_ID, $value, $json_array['from']['target'], $json_array['to']['target'], $json_array['to']['configId'], $json_array['to']['slotset'], $json_array['to']['droneId']);
					$data = '{"isError":0,"data":{"ret":1,"money":{"uridium":"7.245","credits":"37.227.319"}}}';
					$i++;
				}
			}
		}

		echo base64_encode($data);
		calcul_config_laser_damage($account_ID, 1);
		calcul_config_laser_damage($account_ID, 2);
		calcul_config_shield($account_ID, 1);
		calcul_config_shield($account_ID, 2);
		calcul_config_speed($account_ID, 1);
		calcul_config_speed($account_ID, 2);
	}
}

?>