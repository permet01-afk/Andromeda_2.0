<html>
<head>
	<style type="text/css">
		body
		{
			background-color: black;
			font-family: monospace;
			color: white;
			font-size: 12x;
		}

		#packet
		{
			margin-left: 100px;
		}
	</style>
</head>
<?php

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

function get_data()
{
	$req = Connexion::bdd()->prepare('SELECT * FROM `debug` ORDER BY id DESC');
	$req->execute();
	$data = $req->fetchAll();

	return $data;
}

$data = get_data();

foreach ($data as $key => $value) {
	$json_array = json_decode($data[$key]['show']);
		echo '<br><u><b><<span style="color:#00FF00">'.$data[$key]['timestamp'].'</span>> RECEIVE : </b></u><br>';
		echo 'encoded :';
		echo '<div id="packet">';
		foreach ($json_array as $key => $value) {
			echo '<i><b>'.$key.'</b></i> : <span style="color:#cc0000">\'';
			echo $value;
			echo '\'</span><br>';
		}
		echo '</div>';
		echo 'decoded :<br>';
		echo '<div id="packet">';
		foreach ($json_array as $key => $value) {
			echo '<i><b>'.$key.'</b></i> : <span style="color:#cc0000">\'';
			echo base64_decode($value);
			echo '\'</span><br>';
		}
		echo '</div>';
}

?>