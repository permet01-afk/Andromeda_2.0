<?php
if(isset($_GET['config']) && isset($_GET['dmg']) && isset($_GET['sh']) && isset($_GET['speed']))
{
	$message = saveConfig((int)htmlentities($_GET['config']), (int)htmlentities($_GET['dmg']), (int)htmlentities($_GET['sh']), (int)htmlentities($_GET['speed']), $db);
}

$sth = $db->prepare("SELECT damage1, shield1, speed1, damage2, shield2, speed2  
 FROM player_config WHERE player_id = :player_id LIMIT 1");
$sth->execute(array(
				':player_id' => $_SESSION['player_id']
			));
$dataconfig = $sth->fetchAll();
?>
<script src="views/userTabs/jquery.min.js"></script>
<script src="views/userTabs/slider/jquery.nouislider.js"></script>
<link rel="stylesheet" type="text/css" href="views/userTabs/slider/jquery.nouislider.css" />
<script>
	
	function setMax1()
	{
		if(isNaN($("#slider1").val()))
		{
			return;
		}
		var aviablePoint = 15 - $("#slider2").val() - $("#slider3").val();
		if(aviablePoint > 10)
		{
			aviablePoint = 10;
		}
		
		if($("#slider1").val() > aviablePoint)
		{
			$("#slider1").val(aviablePoint);
		}	
		var dVal = '+' + Math.floor($("#slider1").val())*2 + '%';
		$('#slider1val').text(dVal);		
	}
	function setMax2()
	{
		if(isNaN($("#slider2").val()))
		{
			return;
		}
		var aviablePoint = 15 - $("#slider1").val() - $("#slider3").val();
		if(aviablePoint > 10)
		{
			aviablePoint = 10;
		}
		
		if($("#slider2").val() > aviablePoint)
		{
			$("#slider2").val(aviablePoint);
		}	
		var dVal = '+' + Math.floor($("#slider2").val())*6 + '%';
		$('#slider2val').text(dVal);				
	}
	function setMax3()
	{
		if(isNaN($("#slider3").val()))
		{
			return;
		}
		var aviablePoint = 15 - $("#slider2").val() - $("#slider1").val();
		if(aviablePoint > 10)
		{
			aviablePoint = 10;
		}
		
		if($("#slider3").val() > aviablePoint)
		{
			$("#slider3").val(aviablePoint);
		}	
		var dVal = '+' +  Math.floor($("#slider3").val())*10;
		$('#slider3val').text(dVal);	
	}
	
	function setMax4()
	{
		if(isNaN($("#slider4").val()))
		{
			return;
		}
		var aviablePoint = 15 - $("#slider5").val() - $("#slider6").val();
		if(aviablePoint > 10)
		{
			aviablePoint = 10;
		}
		
		if($("#slider4").val() > aviablePoint)
		{
			$("#slider4").val(aviablePoint);
		}	
		var dVal = '+' + Math.floor($("#slider4").val())*2 + '%';
		$('#slider4val').text(dVal);		
	}
	function setMax5()
	{
		if(isNaN($("#slider5").val()))
		{
			return;
		}
		var aviablePoint = 15 - $("#slider4").val() - $("#slider6").val();
		if(aviablePoint > 10)
		{
			aviablePoint = 10;
		}
		
		if($("#slider5").val() > aviablePoint)
		{
			$("#slider5").val(aviablePoint);
		}	
		var dVal = '+' + Math.floor($("#slider5").val())*6 + '%';
		$('#slider5val').text(dVal);				
	}
	function setMax6()
	{
		if(isNaN($("#slider6").val()))
		{
			return;
		}
		var aviablePoint = 15 - $("#slider5").val() - $("#slider4").val();
		if(aviablePoint > 10)
		{
			aviablePoint = 10;
		}
		
		if($("#slider6").val() > aviablePoint)
		{
			$("#slider6").val(aviablePoint);
		}	
		var dVal = '+' + Math.floor($("#slider6").val())*10;
		$('#slider6val').text(dVal);	
	}
	 // On document ready, initialize noUiSlider.
	$(function(){

		$('#slider1').noUiSlider({
			start: [ 0 ],
			step: 1,
			limit: 5,
			connect: 'lower',
			range: {
				'min': [  0 ],
				'max': [ 10 ]
			}				
		});
		
		
		$('#slider1').on('slide', setMax1);
		
		$('#slider2').noUiSlider({
			start: [ 0 ],
			step: 1,
			connect: 'lower',
			range: {
				'min': [  0 ],
				'max': [ 10 ]
			}
		});
		
		$('#slider2').on('slide', setMax2);
		
		$('#slider3').noUiSlider({
			start: [ 0 ],
			step: 1,
			connect: 'lower',
			range: {
				'min': [  0 ],
				'max': [ 10 ]
			}
		});
		
		$('#slider3').on('slide', setMax3);
		
		$('#slider4').noUiSlider({
			start: [ 0 ],
			step: 1,
			limit: 5,
			connect: 'lower',
			range: {
				'min': [  0 ],
				'max': [ 10 ]
			}				
		});
		
		
		$('#slider4').on('slide', setMax4);
		
		$('#slider5').noUiSlider({
			start: [ 0 ],
			step: 1,
			connect: 'lower',
			range: {
				'min': [  0 ],
				'max': [ 10 ]
			}
		});
		
		$('#slider5').on('slide', setMax5);
		
		$('#slider6').noUiSlider({
			start: [ 0 ],
			step: 1,
			connect: 'lower',
			range: {
				'min': [  0 ],
				'max': [ 10 ]
			}
		});
		
		$('#slider6').on('slide', setMax6);
		
		$("#reset1").click
		(
			function()
			{
				$("#slider1").val(0);
				$('#slider1val').text('+0%');
				$("#slider2").val(0);
				$('#slider2val').text('+0%');
				$("#slider3").val(0);
				$('#slider3val').text('+0');
			}
		);
		$("#reset2").click
		(
			function()
			{
				$("#slider4").val(0);
				$('#slider4val').text('+0%');
				$("#slider5").val(0);
				$('#slider5val').text('+0%');
				$("#slider6").val(0);
				$('#slider6val').text('+0');
			}
		);
		$("#save1").click
		(
			function()
			{
				 var url = 'view.php?page=user&tab=configurations';
				var query = '&config=1' + '&dmg=' + Math.floor($("#slider1").val()) + '&sh=' + Math.floor($("#slider2").val()) + '&speed=' + Math.floor($("#slider3").val());
				window.location.href = url + query
			}
		);
		$("#save2").click
		(
			function()
			{
				 var url = 'view.php?page=user&tab=configurations';
				var query = '&config=2' + '&dmg=' + Math.floor($("#slider4").val()) + '&sh=' + Math.floor($("#slider5").val()) + '&speed=' + Math.floor($("#slider6").val());
				window.location.href = url + query
			}
		);		
		
		var dVal = '+' + <?=$dataconfig[0]['damage1']*2?> + '%';
		$('#slider1val').text(dVal);		
		$("#slider1").val(<?=$dataconfig[0]['damage1']?>);
		dVal = '+' + <?=$dataconfig[0]['shield1']*6?> + '%';
		$('#slider2val').text(dVal);
		$("#slider2").val(<?=$dataconfig[0]['shield1']?>);
		dVal = '+' + <?=$dataconfig[0]['speed1']*10?>;
		$('#slider3val').text(dVal);
		$("#slider3").val(<?=$dataconfig[0]['speed1']?>);
		
		dVal = '+' + <?=$dataconfig[0]['damage2']*2?> + '%';
		$('#slider4val').text(dVal);
		$("#slider4").val(<?=$dataconfig[0]['damage2']?>);		
		dVal = '+' + <?=$dataconfig[0]['shield2']*6?> + '%';
		$('#slider5val').text(dVal);
		$("#slider5").val(<?=$dataconfig[0]['shield2']?>);
		dVal = '+' + <?=$dataconfig[0]['speed2']*10?>;
		$('#slider6val').text(dVal);
		$("#slider6").val(<?=$dataconfig[0]['speed2']?>);
		
	});
	
	
	

</script>

<div class="box" style="margin-left: -10px;">
	<div class="title">Configuration 1</div>
	<div class="configBox">
		<div class="cfDamage">Damage</div>	<div id="slider1"></div>	<div id="slider1val">null</div>		
		<div class="cfShield">Shield</div>	<div id="slider2"></div>	<div id="slider2val">null</div>	
		<div class="cfSpeed">Speed</div>	<div id="slider3"></div>	<div id="slider3val">null</div>	
		<div id="reset1">Reset</div>	 <div id="save1">Save</div>
	</div>
</div>	
<div class="box" style="margin-left: -10px;">
	<div class="title">Configuration 2</div>
	<div class="configBox">
		<div class="cfDamage">Damage</div>	<div id="slider4"></div>	<div id="slider4val">null</div>		
		<div class="cfShield">Shield</div>   <div id="slider5"></div>	<div id="slider5val">null</div>	
		<div class="cfSpeed">Speed</div>	<div id="slider6"></div>	<div id="slider6val">null</div>
		<div id="reset2">Reset</div>	 <div id="save2">Save</div>
	</div>
</div>	

<?php
if(isset($message))
{
?>
	<div id="popup_box">    <!-- OUR PopupBox DIV-->
		<div id="popupContent"> 
		<?=$message?>
		</div>
		<a id="popupBoxClose"  >Close</a>    
	</div>

	<script type="text/javascript">
		
		$(document).ready( function() {
		
			// When site loaded, load the Popupbox First
			loadPopupBox();
		
			$('#popupBoxClose').click( function() {            
				unloadPopupBox();
			});
			
			$('#container').click( function() {
				unloadPopupBox();
			});

			function unloadPopupBox() {    // TO Unload the Popupbox
				$('#popup_box').fadeOut("slow");
				$("#container").css({ // this is just for style        
					"opacity": "1"  
				}); 
			}    
			
			function loadPopupBox() {    // To Load the Popupbox
				$('#popup_box').fadeIn("slow");
				$("#container").css({ // this is just for style
					"opacity": "0.3"  
				});         
			}        
		});
	</script>  
<?php
}
?>

<?php 
function saveConfig($config, $dmg, $sh, $speed, $db)
{
	if($dmg > 10 || $dmg < 0)
	{
		return 'Error : damage incorrect value.';
	}
	else if($sh > 10 || $sh < 0)
	{
		return 'Error : shield incorrect value.';
	}
	else if($speed > 10 || $speed < 0)
	{
		return 'Error : speed incorrect value.';
	}
	else if(($speed + $sh + $dmg) > 15)
	{
		return 'Error : speed + sh + dmg > 15.';
	}	
	
	if($config == 1)
	{
		$req = $db->prepare('UPDATE player_config SET damage1='.$dmg.', shield1='.$sh.', speed1='.$speed.' WHERE player_id='.$_SESSION['player_id']);
		$req->execute();
		return 'Success : Configuration 1 saved !';
	}
	else if($config == 2)
	{
		$req = $db->prepare('UPDATE player_config SET damage2='.$dmg.', shield2='.$sh.', speed2='.$speed.' WHERE player_id='.$_SESSION['player_id']);
		$req->execute();
		return 'Success : Configuration 2 saved !';
	}
	else 
	{
		return 'Error : config incorrect value.';
	}
}
?>