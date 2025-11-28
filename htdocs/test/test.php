<?php 
	class TreeNode
	{
		public $id;
		public $label;   
		public $imgPath;
		public $description;   
		
		public $parentNode;
		public $childNodes;

		public function __construct($id,$label,$imgPath,$description)
		{
			$this->id = $id;
			$this->label = $label;
			$this->imgPath = $imgPath;
			$this->description = $description;
			$this->parentNode = null;
			$this->childNodes = null;
		}
		
		public function addChild($child)
		{
			$child->parentNode = $this;
			$this->childNodes[] = $child;
		}
	}
	
	function displayTree($root)
	{
		echo '<li>';
		echo '<a href="#" data-toggle="tooltip" data-html="true" data-placement="bottom" title="';
		echo '<h4><strong>';
		echo $root->label;
		echo '</strong></h4>';
		echo $root->description;
		echo '">';
		//
		echo '<img width="45" src="';
		echo $root->imgPath;		
		echo '"/>';
		echo '</a>';
		if($root->childNodes != null)
		{
			echo '<ul>';
			foreach ($root->childNodes as $node)
			{
				displayTree($node);
			}
			echo '</ul>';
		}	
		echo '</li>';
	}
	
	//Combat
	$rocketDMG = new TreeNode("rocketDMG","Rocket Damage","Icons/Attack/rocketDMG.svg","Rocket damage: <font color='red'>+250</font>");

	$bombDMG = new TreeNode("bombDMG","Bomb Damage","Icons/Attack/bombDMG.svg","Smart bomb damage: <font color='red'>+5.000</font>");
	$pplvl1 = new TreeNode("pplvl1","Precision / Shield Penetration","Icons/Attack/precisionpenetration.svg","Precision: <font color='magenta'>+2%</font> / Penetration: <font color='magenta'>+1%</font>");
	$rocketDMG->addChild($bombDMG);
	$rocketDMG->addChild($pplvl1);
	
	$x4lvl1 = new TreeNode("x4lvl1","x4 Damage Level 1","Icons/Attack/x4DMG.svg","x4 damage: <font color='red'>+50</font>");
	$bombDMG->addChild($x4lvl1);
	$x4lvl2 = new TreeNode("x4lvl2","x4 Damage Level 2","Icons/Attack/x4DMG.svg","x4 damage: <font color='red'>+150</font>");
	$x4lvl1->addChild($x4lvl2);
	$x6lvl1 = new TreeNode("x6lvl1","x6 Damage Level 1","Icons/Attack/x6DMG.svg","x6 damage: <font color='red'>+100</font>");
	$bombDMG->addChild($x6lvl1);
	$x6lvl2 = new TreeNode("x6lvl2","x6 Damage Level 2","Icons/Attack/x6DMG.svg","x6 damage: <font color='red'>+300</font>");
	$x6lvl1->addChild($x6lvl2);
	
	$prelvl1 = new TreeNode("prelvl1","Precision Level 1","Icons/Attack/precision.svg","Precision: <font color='magenta'>+5%</font>");
	$pplvl1->addChild($prelvl1);
	$prelvl2 = new TreeNode("prelvl2","Precision Level 2","Icons/Attack/precision.svg","Precision: <font color='magenta'>+8%</font>");
	$prelvl1->addChild($prelvl2);
	$penlvl1 = new TreeNode("penlvl1","Shield Penetration Level 1","Icons/Attack/penetration.svg","Penetration: <font color='magenta'>+2%</font>");
	$pplvl1->addChild($penlvl1);
	$penlvl2 = new TreeNode("penlvl2","Shield Penetration Level 2","Icons/Attack/penetration.svg","Penetration: <font color='magenta'>+5%</font>");
	$penlvl1->addChild($penlvl2);
	
	//Defence
	$x3DMG = new TreeNode("x3DMG","x3 Damage","Icons/Defense/x3DMG.svg","x3 damage: <font color='green'>+300</font>");
	$hpSmallBonus = new TreeNode("hpSmallBonus","Health Bonus","Icons/Defense/hpSmallBonus.svg","Health: <font color='green'>+20.000</font>");
	$ealvl1 = new TreeNode("ealvl1","Evasion / Shield Absorbtion","Icons/Defense/evasionabsorbtion.svg","Evasion: <font color='magenta'>+2%</font> / Absorbtion: <font color='magenta'>+1%</font>");
	$x3DMG->addChild($hpSmallBonus);
	$x3DMG->addChild($ealvl1);
	
	$shlvl1 = new TreeNode("shlvl1","Shield Bonus Level 1","Icons/Defense/shBonus.svg","Shield: <font color='#5882FA'>+15.000</font>");
	$hpSmallBonus->addChild($shlvl1);
	$shlvl2 = new TreeNode("shlvl2","Shield Bonus Level 2","Icons/Defense/shBonus.svg","Shield: <font color='#5882FA'>+35.000</font>");
	$shlvl1->addChild($shlvl2);
	$abslvl1 = new TreeNode("abslvl1","ABS Damage Level 1","Icons/Defense/absDMG.svg","ABS Damage: <font color='#5882FA'>+25</font>");
	$hpSmallBonus->addChild($abslvl1);
	$abslvl2 = new TreeNode("abslvl2","ABS Damage Level 2","Icons/Defense/absDMG.svg","ABS Damage: <font color='#5882FA'>+75</font>");
	$abslvl1->addChild($abslvl2);
	$evalvl1 = new TreeNode("evalvl1","Evasion Level 1","Icons/Defense/evasion.svg","Evasion: <font color='magenta'>+5%</font>");
	$ealvl1->addChild($evalvl1);
	$evalvl2 = new TreeNode("evalvl2","Evasion Level 2","Icons/Defense/evasion.svg","Evasion: <font color='magenta'>+8%</font>");
	$evalvl1->addChild($evalvl2);
	$absorblvl1 = new TreeNode("absorblvl1","Shield Absorbtion Level 1","Icons/Defense/absorbtion.svg","Absorbtion: <font color='magenta'>+2%</font>");
	$ealvl1->addChild($absorblvl1);
	$absorblvl2 = new TreeNode("absorblvl2","Shield Absorbtion Level 2","Icons/Defense/absorbtion.svg","Absorbtion: <font color='magenta'>+5%</font>");
	$absorblvl1->addChild($absorblvl2);

	//Utility
	$shRegen = new TreeNode("shRegen","Shield Regeneration","Icons/Utility/shRegen.svg","Shield regeneration: <font color='#5882FA'>+1.500/s</font>");
	$hpBigBonus = new TreeNode("hpBigBonus","Health Bonus","Icons/Utility/hpBigBonus.svg","Health: <font color='green'>+40.000</font>");
	$shRegen->addChild($hpBigBonus);
	$cooldownlvl1 = new TreeNode("cooldownlvl1","Cooldown Reduction Level 1","Icons/Utility/cooldown.svg","Cooldown Reduction: <font color='#F7FE2E'>-1%</font>");
	$shRegen->addChild($cooldownlvl1);
	$xpBonuslvl1 = new TreeNode("xpBonuslvl1","NPC Points Bonus Level 1","Icons/Utility/xpBonus.svg","NPC Points: <font color='#F7FE2E'>+2%</font>");
	$shRegen->addChild($xpBonuslvl1);
	$hpshtechlvl1 = new TreeNode("hpshtechlvl1","Battle repair bot / Backup shield Level 1","Icons/Utility/hpshtech.svg","Battle repair bot: <font color='green'>+1.000/s</font> ; Backup shield: <font color='#5882FA'>+10.000</font>");
	$shRegen->addChild($hpshtechlvl1);
	$hpRegen = new TreeNode("hpRegen","Health Regeneration","Icons/Utility/hpRegen.svg","Health regeneration: <font color='green'>+10.000/s</font>");
	$hpBigBonus->addChild($hpRegen);
	$hptechlvl2 = new TreeNode("hptechlvl2","Battle Repair bot level 2","Icons/Utility/hptech.svg","Battle repair bot: <font color='green'>+2.000/s</font>");
	$hpshtechlvl1->addChild($hptechlvl2);
	$shtechlvl2 = new TreeNode("shtechlvl2","Backup shield level 2","Icons/Utility/shtech.svg","Backup shield: <font color='#5882FA'>+20.000</font>");
	$hpshtechlvl1->addChild($shtechlvl2);
	$xpBonuslvl2 = new TreeNode("xpBonuslvl2","NPC Points Bonus Level 2","Icons/Utility/xpBonus.svg","NPC Points: <font color='#F7FE2E'>+3%</font>");
	$xpBonuslvl1->addChild($xpBonuslvl2);
	$cooldownlvl2 = new TreeNode("cooldownlvl2","Cooldown Reduction Level 2","Icons/Utility/cooldown.svg","Cooldown Reduction: <font color='#F7FE2E'>-2%</font>");
	$cooldownlvl1->addChild($cooldownlvl2);
	$cooldownlvl3 = new TreeNode("cooldownlvl3","Cooldown Reduction Level 3","Icons/Utility/cooldown.svg","Cooldown Reduction: <font color='#F7FE2E'>-5%</font>");
	$cooldownlvl2->addChild($cooldownlvl3);

?>
<html>
	 <head>
		<title>Test</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="test.css" />
	 </head>
	 <body>
		<div class="tree">
			<ul>
			<?php
				displayTree($rocketDMG);
			?>
			</ul>
		</div>
		<div class="tree">
			<ul>
			<?php
				displayTree($x3DMG);
			?>
			</ul>
		</div>
		<br>
		<div class="tree text-center">
			<ul>
			<?php
				displayTree($shRegen);
			?>
			</ul>
		</div>
	</body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip();
		});
	</script>
</html>

