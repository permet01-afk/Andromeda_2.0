<?php //if(isset($_GET['mwsp']) and $_GET['mwsp'] = "ra4f51bdkbze") { 

ini_set('max_execution_time', 300000);
$timestart=microtime(true);
$debug = false; // enable / disable debug mode
class Connexion 
{
     
    public static function bdd() {     
        try 
        {
            $bdd = new PDO('mysql:host=127.0.0.1;dbname=andromeda', 'root', '');
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } 
        catch (Exception $e) 
        {
        die('Erreur : '. $e->getMessage());
        }
            return $bdd;
        }
}

$req = Connexion::bdd()->prepare("SELECT count(id) as number,comment,player_id FROM `swf_hacker` WHERE 1 GROUp BY player_id");
$req->execute();

$data = $req->fetchAll();

foreach ($data as $value)
{
	echo $value['number'];
	echo ' ** ';
	echo $value['player_id'];	
	echo ' -> ';
	echo $value['comment'];
	echo '<br>';
}

?>