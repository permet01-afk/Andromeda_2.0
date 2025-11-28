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

function get_ship($id)
{
	$req = Connexion::bdd()->prepare('SELECT * FROM ships WHERE ship_id=:id');
	$req->execute(array('id' => $id));
	
	$data = $req->fetchAll();

	return $data;
}


if(!empty($_GET))
{
	$ship_list = array(3,4,5,7,8,9,10);
	if (in_array($_GET['id'], $ship_list))
	{
		$ship_array = get_ship($_GET['id']);
		if ($ship_array[0]['price_cre'] == 0)
		{
        	echo '<object width="253" height="206" id="shopdetails" name="shopdetails" data="../swf_global/shopdetails.swf" type="application/x-shockwave-flash"><param name="allowfullscreen" value="true"><param name="allowscriptaccess" value="always"><param name="quality" value="high"><param name="wmode" value="transparent"><param name="bgColor" value="schwarz"><param name="allowScriptAccess" value="always"><param name="flashvars" value="cdn=http://do-test.a.bpcdn.net/&amp;elite_icon_cv=4738d9ac735bc66ddb9d288a3d982e00&amp;background_cv=5a3549d51b64b3301a596e7197760500&amp;limited_icon_cv=74e9a59f2ccf5e2a3a69ebead990c200&amp;limited_std_icon_cv=506294ad55b3114581a227681644e200&amp;elite=1&amp;item_name='.strtoupper($ship_array[0]['ship_name']).'&amp;item_caption='.strtoupper($ship_array[0]['caption']).'&amp;item_prefix=ship&amp;item_id='.$ship_array[0]['ship_id'].'&amp;item_cv=b478c5653d98a43a9604243bdf829500&amp;price_plain='.strtoupper($ship_array[0]['price_uri']).' U.&amp;loot_id=ship_'.$ship_array[0]['ship_name'].'"></object>';
		}
		else
		{
			echo '<object width="253" height="206" id="shopdetails" name="shopdetails" data="../swf_global/shopdetails.swf" type="application/x-shockwave-flash"><param name="allowfullscreen" value="true"><param name="allowscriptaccess" value="always"><param name="quality" value="high"><param name="wmode" value="transparent"><param name="bgColor" value="schwarz"><param name="allowScriptAccess" value="always"><param name="flashvars" value="cdn=http://do-test.a.bpcdn.net/&amp;elite_icon_cv=4738d9ac735bc66ddb9d288a3d982e00&amp;background_cv=5a3549d51b64b3301a596e7197760500&amp;limited_icon_cv=74e9a59f2ccf5e2a3a69ebead990c200&amp;limited_std_icon_cv=506294ad55b3114581a227681644e200&amp;elite=0&amp;item_name='.strtoupper($ship_array[0]['ship_name']).'&amp;item_caption='.strtoupper($ship_array[0]['caption']).'&amp;item_prefix=ship&amp;item_id='.$ship_array[0]['ship_id'].'&amp;item_cv=b478c5653d98a43a9604243bdf829500&amp;price_plain='.strtoupper($ship_array[0]['price_cre']).' C.&amp;loot_id=ship_'.$ship_array[0]['ship_name'].'"></object>';
		}

	}
}


?>