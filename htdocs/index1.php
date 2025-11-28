<?php session_start(); 
if(isset($_SESSION['terms_of_use']) and $_SESSION['terms_of_use'] == "true") 
{
	header('Location: login.php');
	exit();
}
if(isset($_REQUEST['agree']) and $_REQUEST['agree'] == "true") 
{ 
	$_SESSION['terms_of_use'] = true; 
	header('Location: login.php');
	exit();
}
?>
<!DOCTYPE HTML>
<html>
<head>
<style>
body
{
	background-color:black;
	background:url('img/bg.jpg');
	background-size:cover;
	color:lightgray;
	font-family:Georgia;
}



.continueButton{
  width:140px;
  	margin-left: auto;
    margin-right: auto;
  padding:15px;
  border-radius:5px;
  background-image: linear-gradient(#223548 0%, #97abbf 100%);
  font:14px Oswald;
  color:#FFF;
  text-transform:uppercase;
  text-shadow:#000 0px 1px 5px;
  border:1px solid #000;
  opacity:0.7;
	-webkit-box-shadow: 0 8px 6px -6px rgba(0,0,0,0.7);
  -moz-box-shadow: 0 8px 6px -6px rgba(0,0,0,0.7);
	box-shadow: 0 8px 6px -6px rgba(0,0,0,0.7);
  border-top:1px solid rgba(255,255,255,0.8)!important;
  -webkit-box-reflect: below 0px -webkit-gradient(linear, left top, left bottom, from(transparent), color-stop(50%, transparent), to(rgba(255,255,255,0.2)));
}
.continueButton:hover{
  opacity:1;
  cursor:pointer;
}

</style>
<title>Andromeda</title>
</head>
<body>
<center>
	<img style="height:165px;" src="img/logo.png">
	<div style="margin-left:auto;opacity:0.90; height:20px; background-color: #223548;border-top-left-radius: 25px;border-top-right-radius: 25px; color:white; margin-right:auto; display:block; width:600px; margin-top:25px; font-size:13px;">
	Server under maintenance:
	</div>
	<div style="margin-left:auto;opacity:0.90; height:250px; overflow:auto;background-color: white; color:black; margin-right:auto; display:block; width:600px; margin-top:0px; font-size:13px;">
		<h3>
		Server under maintenance, we will be back soon, very soon.
		</h3>
		</div>
		<div style="margin-left:auto;opacity:0.90; height:20px; background-color: #223548;border-bottom-left-radius: 25px;border-bottom-right-radius: 25px; color:white; margin-right:auto; display:block; width:600px; margin-top:0px; font-size:13px;">
			Sorry for the inconvenience. 
		</div>
		
		</div>

	
	<div style="margin-left:auto; height:70px; background-color: #223548;opacity:0.90;border-radius: 25px;border-bottom-right-radius: 25px; color:black; margin-right:auto; display:block; width:600px; margin-top:25px; font-size:13px;">
	Andromeda is an independent project (nonprofit goal) © 2015.
	<br/>
	<a target="_blank" href="http://darkorbit.com/">Dark Orbit</a> is a registered trademark of <a target="_blank" href="http://bigpoint.com/">BigPoint GmbH</a>. 
	 <br/>
	All rights reserved to their respective owner(s).
	<br/>
	We are not endorsed, affiliated or offered by <a target="_blank" href="http://bigpoint.com/">BigPoint GmbH</a>.
	</div>
</center>
</body>
</html>