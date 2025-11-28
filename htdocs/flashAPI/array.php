<?php
$json = '{"isError":0,"data":{"ret":{"filters":{"weapons":[0,1,2],"generators":[3,4,5],"extras":[6,7,8,9,10,11],"ammunition":[12,13,14],"resources":[15],"drone_related":[16,17],"modules":[18],"pet_related":[19,20]},"hangars":[{"hangarID":"175707","name":"","hangar_is_active":true,"hangar_is_selected":true,"general":{"ship":{"I":5,"HP":"16000","L":0,"SM":"ship_liberator","M":["ship_liberator"]},"drones":[]},"config":{"1":{"ship":{"EQ":{"lasers":["7109076"],"generators":["7109075"],"extras":["7109079"]}}},"2":{"ship":{"EQ":{"lasers":["7109076"],"generators":["7109075"],"extras":["7109079"]}}}}}],"items":[{"I":"7109075","LV":0,"L":1,"S":0},{"I":"7109076","LV":0,"L":2,"S":1},{"I":"7109077","LV":0,"L":3,"S":2,"Q":2000},{"I":"7109078","LV":0,"L":4,"S":3,"Q":100},{"I":"7109079","LV":0,"L":5,"S":4,"properties":{"durability":180,"active":0}},{"I":"7109080","LV":0,"L":6,"S":5,"Q":1},{"I":"7109081","LV":0,"L":7,"S":6,"Q":50},{"I":"7113754","LV":0,"L":8,"S":7,"Q":300}],"itemInfo":[{"L":0,"name":"Liberator","T":21,"C":"ship","levels":[{"slotsets":{"lasers":{"T":[0],"Q":4},"generators":{"T":[3,4],"Q":6},"heavy_guns":{"T":[1],"Q":1},"extras":{"T":[11,9,7,8,10,6],"Q":2}},"selling":{"credits":32000},"cdn":{"63x63":"1fd1699b162b315e7d7097c79019e700","100x100":"12811c57da5f73380b089c37aebde700","top":"4d48488a8ca0d9946c12ab1241d46c00"}}]},{"L":1,"name":"SG3N-A01","T":4,"C":"generator","levels":[{"selling":{"credits":4000},"cdn":{"30x30":"8c0b74bf0cc43c58fb39e6d48c495000","63x63":"d2fc1d2a80f3ef4376c9b63145b10c00","100x100":"19228ba81b387583a63ed8c9a9465400"}}]},{"L":2,"name":"MP-1","T":0,"C":"laser","levels":[{"selling":{"credits":10000},"cdn":{"30x30":"cfde29bcddbbfbcf4abccb06a1bad900","63x63":"52eb84ef703379bc9cc9268f59ff5a00","100x100":"23ad6cc5c949ffce0e6ff2683b16e700"}}]},{"L":3,"name":"LCB-10","T":14,"C":"battery","levels":[{"selling":{"credits":5},"cdn":{"30x30":"dae4f308e45cc4a93ac36451afabc700","63x63":"c02154d2ce135d660f647631dbbfbc00","100x100":"d8db5c48fe907c65a74b77b7206a5e00"}}]},{"L":4,"name":"R-310","T":14,"C":"battery","levels":[{"selling":{"credits":50},"cdn":{"30x30":"68af73bca9114add674a573fee166200","63x63":"bd4b785081ad98c8a38efd38c1443b00","100x100":"9d29b47ca17ee0316f80a4bee39c9600"}}]},{"L":5,"name":"Repair Robot Basic","T":9,"C":"special","levels":[{"selling":{"credits":3750},"cdn":{"30x30":"6512f6cf8e8d40ff25c73b0c225e5500","63x63":"c82f70710b97fc05410bb53b6b8e7400","100x100":"d474477ba5d66d7f3255fb1068eeea00"}}]},{"L":6,"name":"Booty Key","T":22,"C":"special","levels":[{"cdn":{"30x30":"ffbc72514edc29adbcb69b1da2c23a00","63x63":"2c2649217fac98e086c9cd8fcec30300","100x100":"ef618aff20a6e2bcc88d806284cfcb00"}}]},{"L":7,"name":"PLT-2026","T":14,"C":"battery","levels":[{"selling":{"credits":250},"cdn":{"30x30":"0eb7d727d0e8d7c05ad9121573a6b200","63x63":"c6bfdcd09949f7b61b98ead3c6773500","100x100":"58fdf543bd4fbd7bfd7bcb011c9d1100"}}]},{"L":8,"name":"MCB-25","T":14,"C":"battery","levels":[{"selling":{"credits":1},"cdn":{"30x30":"becac3718527c5212ffbaef4de6beb00","63x63":"86c2c2d261ef196310b34fb3f92c6800","100x100":"fa407a1644e0563e5138b0499c682100"}}]}],"userInfo":{"factionRelated":"mmo"}},"money":{"uridium":"10,000","credits":"55,000"},"map":{"types":["Weapon_LaserType","Weapon_HellstormLauncherType","Weapon_WeaponType","Generator_EngineType","Generator_ShieldType","Generator_GeneratorType","Extra_BoosterType","Extra_Cpu_CPUType","Extra_ModuleType","Extra_RobotType","Extra_UpgradeType","Extra_ExtraType","Weapon_Ammo_LaserType","Weapon_Ammo_RocketType","Weapon_Ammo_AmmunitionType","Resource_OreType","Drone_Design_DroneDesignType","Drone_Formation_DroneFormationType","Module_StationModuleType","Pet_PetGearType","Pet_AIProtocolType","Ship_ShipType","Item_ItemType"],"lootIds":["ship_liberator","equipment_generator_shield_sg3n-a01","equipment_weapon_laser_mp-1","ammunition_laser_lcb-10","ammunition_rocket_r-310","equipment_extra_repbot_rep-s","resource_booty-key","ammunition_rocket_plt-2026","ammunition_laser_mcb-25"]}}}';
$json_array = json_decode($json);

var_dump($json_array);

echo 'encoding to json :';

$data = array();

$data['isError'] = 0;
$data['data']['ret']['filters']['weapons'] = array(0,1,2);
$data['data']['ret']['filters']['generators'] = array(3,4,5);
$data['data']['ret']['filters']['extras'] = array(6,7,8,9,10,11);
$data['data']['ret']['filters']['ammunition'] = array(12,13,14);
$data['data']['ret']['filters']['resources'] = array(15);
$data['data']['ret']['filters']['drone_related'] = array(16,17);
$data['data']['ret']['filters']['modules'] = array(18);
$data['data']['ret']['filters']['pet_related'] = array(19,20);

// HANGAR

$data['data']['ret']['hangars'] = 
array(
	array(
		'hangarID' => '175707',
		'name' => '',
		'hangar_is_active' => true,
		'hangar_is_selected' => true,
		'general' => array(
						'Ship' => array(
									'I' => 5,
    								"HP" => "16000",
    								"L" => 0,
    								"SM" => "ship_liberator",
    								"M" => array("ship_liberator")
    								),
						"drones" => array()
						),
		'config' => array(
						'1' => array(
									'Ship' => array(
													'EQ' => array(
																'lasers' => array(
																				'7109076'
																				),
																'generators' => array(
																				'7109075'
																				),
																'extras' => array(
																				'7109079'
																				)
																)
													)
									),
						'2' => array(
									'Ship' => array(
													'EQ' => array(
																'lasers' => array(
																				'7109076'
																				),
																'generators' => array(
																				'7109075'
																				),
																'extras' => array(
																				'7109079'
																				)
																)
													)
									)
						)
    	)
	);

$data['data']['ret']['items'] = 
array(
	array(
		'I' => '7109075',
		'LV' => 0,
		'L' => 1,
		'S' => 0
		),
	array(
		'I' => '7109076',
		'LV' => 0,
		'L' => 2,
		'S' => 0
		),
	array(
		'I' => '7109077',
		'LV' => 0,
		'L' => 3,
		'S' => 2,
		'Q' => 2000
		),
	array(
		'I' => '7109078',
		'LV' => 0,
		'L' => 4,
		'S' => 3,
		'Q' => 100
		),
	array(
		'I' => '7109079',
		'LV' => 0,
		'L' => 5,
		'S' => 4,
		'properties' => array(
							'durability' => 180,
							'active' => 0
							)
		),
	array(
		'I' => '7109080',
		'LV' => 0,
		'L' => 6,
		'S' => 5,
		'Q' => 1
		),
	array(
		'I' => '7109081',
		'LV' => 0,
		'L' => 7,
		'S' => 6,
		'Q' => 50
		),
	array(
		'I' => '7113754',
		'LV' => 0,
		'L' => 8,
		'S' => 7,
		'Q' => 300
		)
	);

$data['data']['ret']['itemInfo'] =
array(
	array(
		'L' => 0,
		'name' => 'Liberator',
		'T' => 21,
		'C' => 'ship',
		'levels' => array(
						array(
							'slotsets' => array(
												'lasers' => array(
																'T' => array(0),
																'Q' => 4
																),
												'generators' => array(
																	'T' => array(3, 4),
																	'Q' => 6
																	),
												'heavy_guns' => array(
																	'T' => array(1),
																	'Q' => 1
																	),
												'extras' => array(
																'T' => array(11, 9, 7, 8, 10, 6),
																'Q' => 2
																)
												),
							'selling' => array(
											'credits' => 32000
											),
							'cdn' => array(
										'63x63' => '1fd1699b162b315e7d7097c79019e700',
										'100x100' => '12811c57da5f73380b089c37aebde700',
										'top' => '4d48488a8ca0d9946c12ab1241d46c00'
										)
							)
						)

		)
	);
?>