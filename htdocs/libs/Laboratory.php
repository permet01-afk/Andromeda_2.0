<?php

class Laboratory 
{
	function __construct($userid, $skilltree, $userlogfiles, $db) 
	{
        $this->skills = $this->load_skills($skilltree);
		$this->userid = $userid;
		$this->userlogfiles = $userlogfiles;
		$this->db = $db;
    }
	
	function buy_skill($skill)
	{
		if(array_key_exists($skill, $this->skills))
		{
			$skills_db_string = "";
			$purchased = false;
			
			$skill_count = 0;
			
			foreach ($this->skills as $key => $value)
			{
				++$skill_count;

				if($key == $skill)  // if the current skill is the bought skill
				{
					if($this->userlogfiles >= $this->get_skill_Prix($skill))
					{
						if($value < $this->get_skill_max_level($skill))
						{
							$value += 1;
							$purchased = true;
						}
					}
				}
				$skills_db_string = $skills_db_string.$key.':'.$value;
				
				if(count($this->skills) != $skill_count)
				{
					$skills_db_string = $skills_db_string.'/';
				}

			}
			
			if($purchased) 
			{
				$req = $this->db->prepare('UPDATE users SET logfiles=logfiles-'.$this->get_skill_Prix($skill).', skilltree="'.$skills_db_string.'" WHERE id='.$this->userid);
				$req->execute();
				return true; 
			}
			else 
			{ 
				return false; 
			}
			
			echo $skills_db_string;
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function load_skills($skilltree)
	{
		$skilltree = explode("/", $skilltree);

		$skills = array();
		
		foreach ($skilltree as $key => $value)
		{
			$skill = explode(":", $value);
			$skills[$skill[0]] = $skill[1];
		}

		// echo $skills["skill_name"] will return the level of the skill;
		
		return $skills;
	}
	
	function get_skill_Prix($skill)
	{
		$skill_level = $this->skills[$skill];

		switch($skill)
		{
			case "dmg":
				switch($skill_level)
				{
					case 0:
						return 60;
						break;
					case 1:
						return 120;
						break;

					case 2:
						return 240;
						break;

					case 3:
						return 360;
						break;

					case 4:
						return 480;
						break;
					default:
						break;
				}
				break;
			case "hp":
				switch($skill_level)
				{
					case 0:
						return 60;
						break;
					case 1:
						return 120;
						break;

					case 2:
						return 240;
						break;
					default:
						break;
				}
				break;
			case "rep":
				switch($skill_level)
				{
					case 0:
						return 60;
						break;
					case 1:
						return 120;
						break;

					case 2:
						return 240;
						break;
					default:
						break;
				}
				break;
			case "shd_abs":
				switch($skill_level)
				{
					case 0:
						return 70;
						break;
					case 1:
						return 140;
						break;

					case 2:
						return 280;
						break;
					default:
						break;
				}
				break;
			case "shreg":
				switch($skill_level)
				{
					case 0:
						return 60;
						break;
					case 1:
						return 120;
						break;

					case 2:
						return 240;
						break;

					case 3:
						return 480;
						break;

					case 4:
						return 960;
						break;
					default:
						break;
				}
				break;
			case "smb":
				switch($skill_level)
				{
					case 0:
						return 110;
						break;
					case 1:
						return 220;
						break;
					default:
						break;
				}
				break;
			case "rck":
				switch($skill_level)
				{
					case 0:
						return 60;
						break;
					case 1:
						return 120;
						break;

					case 2:
						return 240;
						break;

					case 3:
						return 480;
						break;

					case 4:
						return 960;
						break;
					default:
						break;
				}
				break;
			default:
				return 0;
				break;
		}
	}
	
	function get_skill_max_level($skill)
	{
		switch($skill)
		{
			case "dmg":
				return 5;
				break;
			case "hp":
				return 3;
				break;
			case "rep":
				return 3;
				break;
			case "shd_abs":
				return 3;
				break;
			case "shreg":
				return 5;
				break;
			case "smb":
				return 2;
				break;
			case "rck":
				return 5;
				break;
			default:
				return 0;
				break;
		}
	}
	
	
	function get_skill_description($skill)
	{
		$skill_level = $this->skills[$skill];
		switch($skill)
		{
			case "dmg":
				switch($skill_level)
				{
					case 0:
						return "
						Next level : increase damage by <font color='red'>2%</font> versus players and <font color='red'>4%</font> versus npc";
						break;
					case 1:
						return "Current(x) level : increase damage by <font color='red'>2%</font> versus players and <font color='red'>4%</font> versus npc<br/>
						Next level : increase damage by <font color='red'>4%</font> versus players and <font color='red'>6%</font> versus npc";
						break;

					case 2:
						return "Current(x) level : increase damage by <font color='red'>4%</font> versus players and <font color='red'>6%</font> versus npc<br/>
						Next level : increase damage by <font color='red'>6%</font> versus players and <font color='red'>9%</font> versus npc";
						break;

					case 3:
						return "Current(x) level : increase damage by <font color='red'>6%</font> versus players and <font color='red'>9%</font> versus npc<br/>
						Next level : increase damage by <font color='red'>9%</font> versus players and <font color='red'>13%</font> versus npc";
						break;

					case 4:
						return "Current(x) level : increase damage by <font color='red'>9%</font> versus players and <font color='red'>13%</font> versus npc<br/>
						Next level : increase damage by <font color='red'>12%</font> versus players and <font color='red'>18%</font> versus npc";
						break;
						
					case 5:
						return "Current(x) level : increase damage by <font color='red'>12%</font> versus players and <font color='red'>18%</font> versus npc<br/><br/>";
						break;

					default:
						break;
				}
				break;
			case "hp":
				switch($skill_level)
				{
					case 0:
						return "
						Next level : Increased maximum hitpoints by  <font color='green'>10,000</font>";
						break;
					case 1:
						return "Current level : Increased maximum hitpoints by  <font color='green'>10,000</font><br/>
						Next level :  Increased maximum hitpoints by  <font color='green'>25,000</font>";
						break;

					case 2:
						return "Current level : Increased maximum hitpoints by  <font color='green'>25,000</font><br/>
						Next level :  Increased maximum hitpoints by  <font color='green'>50,000</font>";
						break;
					
					case 3:
						return "Current level : Increased maximum hitpoints by  <font color='green'>50,000</font>";
						break;

					default:
						break;
				}
				break;
			case "rep":
				switch($skill_level)
				{
					case 0:
						return "
						Next level : The robot repair <font color='green'>15,000</font> hitpoints per second";
						break;
					case 1:
						return "Current level : The robot repair <font color='green'>15,000</font> hitpoints per second<br/>
						Next level :  The robot repair <font color='green'>20,000</font> hitpoints per second";
						break;

					case 2:
						return "Current level : The robot repair <font color='green'>20,000</font> hitpoints per second<br/>
						Next level :  The robot repair <font color='green'>30,000</font> hitpoints per second";
						break;
					
					case 3:
						return "Current level : The robot repair <font color='green'>30,000</font> hitpoints per second";
						break;

					default:
						break;
				}
				break;
			case "shd_abs":
				switch($skill_level)
				{
					case 0:
						return "
						Next level : Increased shield absorbption by <font color='#00AAFF'>3%</font>";
						break;
					case 1:
						return "Current level : Increased shield absorbption by <font color='#00AAFF'>3%</font><br/>
						Next level :  Increased shield absorbption by <font color='#00AAFF'>6%</font>";
						break;

					case 2:
						return "Current level : Increased shield absorbption by <font color='#00AAFF'>6%</font><br/>
						Next level : Increased shield absorbption by <font color='#00AAFF'>10%</font>";
						break;
					
					case 3:
						return "Current level : Increased shield absorbption by <font color='#00AAFF'>10%</font>";
						break;

					default:
						break;
				}
				break;
			case "shreg":
				switch($skill_level)
				{
					case 0:
						return "Next level : Shield regeneration <font color='#00AAFF'>6500</font>";
						break;
					case 1:
						return "Current(x) level : Shield regeneration <font color='#00AAFF'>6500</font><br/>
						Next level : Shield regeneration <font color='#00AAFF'>7000</font>";
						break;

					case 2:
						return "Current(x) level : Shield regeneration <font color='#00AAFF'>7000</font><br/>
						Next level : Shield regeneration <font color='#00AAFF'>7500</font>";
						break;

					case 3:
						return "Current(x) level : Shield regeneration <font color='#00AAFF'>7500</font><br/>
						Next level : Shield regeneration <font color='#00AAFF'>8000</font> versus players";
						break;

					case 4:
						return "Current(x) level : Shield regeneration <font color='#00AAFF'>8000</font><br/>
						Next level : Shield regeneration <font color='#00AAFF'>8500</font>";
						break;
						
					case 5:
						return "Current(x) level : Shield regeneration <font color='#00AAFF'>8500</font>";
						break;

					default:
						break;
				}
				break;
			case "smb":
				switch($skill_level)
				{
					case 0:
						return "
						Next level : Increases damage of the atom bomb by <font color='red'>5,000</font>";
						break;
					case 1:
						return "Current level : Increases damage of the atom bomb by <font color='red'>5,000</font><br/>
						Next level : Increases damage of the atom bomb by <font color='red'>15,000</font>";
						break;

					case 2:
						return "Current level : Increases damage of the atom bomb by <font color='red'>15,000</font>";
						break;
					default:
						break;
				}
				break;
			case "rck":
				switch($skill_level)
				{
					case 0:
						return "Next level : Rocket damage <font color='red'>2000</font> versus players";
						break;
					case 1:
						return "Current(x) level : Rocket damage <font color='red'>2000</font> versus players<br/>
						Next level : Rocket damage <font color='red'>3000</font> versus players";
						break;

					case 2:
						return "Current(x) level : Rocket damage <font color='red'>3000</font> versus players<br/>
						Next level : Rocket damage <font color='red'>4000</font> versus players";
						break;

					case 3:
						return "Current(x) level : Rocket damage <font color='red'>4000</font> versus players<br/>
						Next level : Rocket damage <font color='red'>5000</font> versus players";
						break;

					case 4:
						return "Current(x) level : Rocket damage <font color='red'>5000</font> versus players<br/>
						Next level : Rocket damage <font color='red'>6000</font> versus players";
						break;
						
					case 5:
						return "Current(x) level : Rocket damage <font color='red'>6000</font> versus players";
						break;

					default:
						break;
				}
				break;
			default:
				return 0;
				break;
		}	
	}
}
?>