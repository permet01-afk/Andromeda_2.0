<?php 
// ======= TON CODE ORIGINAL : handlers POST (inchangé) =======
if(!empty($_POST['submit-booty']))
{
	$buymessage = handleSubmitBooty($db);
}
else if(!empty($_POST['submit-loguri']))
{
	$buymessage = handleSubmitLog($db);
}
else if(!empty($_POST['submit-logspecial']))
{
	$buymessage = handleSubmitLogSpecial($db);
}
else if(!empty($_POST['submit-ticketpalla']))
{
	$buymessage = handleticketpalla($db);
}
else if(!empty($_POST['submit-tickettoken']))
{
	$buymessage = handletickettoken($db);
}

/* ============================================================
   🆕 AJOUT : achats par GET pour équipements & drones
   - LF-3 (item_id=1) : +200 dégâts
   - SG3N-A01 (item_id=2) : +10'000 shield
   - G3N-7900 (item_id=4) : +10 speed
   - Iris / Apis / Zeus : mêmes règles que upgrades.php
   ------------------------------------------------------------
   Note: on met les messages dans $buymessage pour réutiliser
   le popup de views/shop.php (inchangé).
   ============================================================ */
if (isset($_GET['buy'])) {
    // Charger infos utilisateur
    $sth = $db->prepare("SELECT id, credits, uridium, drones, apis_built, zeus_built, drone_parts FROM users WHERE id = :id LIMIT 1");
    $sth->execute([ ':id' => $_SESSION['player_id'] ]);
    $user = $sth->fetch(PDO::FETCH_ASSOC);

    // petite utilitaire: ajouter au player_inventory
    $addToInventory = function(PDO $db, int $playerId, int $itemId, int $qty = 1) {
        $check = $db->prepare("SELECT qty FROM player_inventory WHERE player_id = :pid AND item_id = :iid");
        $check->execute([':pid' => $playerId, ':iid' => $itemId]);
        if ($check->rowCount() > 0) {
            $db->prepare("UPDATE player_inventory SET qty = qty + :q WHERE player_id = :pid AND item_id = :iid")
               ->execute([':q' => $qty, ':pid' => $playerId, ':iid' => $itemId]);
        } else {
            $db->prepare("INSERT INTO player_inventory (player_id, item_id, qty) VALUES (:pid, :iid, :q)")
               ->execute([':pid' => $playerId, ':iid' => $itemId, ':q' => $qty]);
        }
    };

    $what = $_GET['buy'];
    switch ($what) {
        case 'laser': {
            // même idée de prix que damage_upgrade (détail non bloquant)
            $price = pow(1 * 20, 2) + 100; // = 500
            if ((int)$user['uridium'] >= $price) {
                $db->prepare("UPDATE users SET uridium = uridium - :p WHERE id = :id")
                   ->execute([':p' => $price, ':id' => $user['id']]);
                $addToInventory($db, (int)$user['id'], 1, 1); // item_id 1 = LF-3
                $buymessage = "Purchase success ! (LF-3 Laser +200 dmg)";
            } else {
                $buymessage = "Error : Not enough uridium";
            }
            break;
        }
        case 'shield': {
            // proche de shield_upgrade
            $price = pow(1 * 4, 3) + 100; // = 164
            if ((int)$user['uridium'] >= $price) {
                $db->prepare("UPDATE users SET uridium = uridium - :p WHERE id = :id")
                   ->execute([':p' => $price, ':id' => $user['id']]);
                $addToInventory($db, (int)$user['id'], 2, 1); // item_id 2 = SG3N-A01
                $buymessage = "Purchase success ! (Shield Generator +10'000 shield)";
            } else {
                $buymessage = "Error : Not enough uridium";
            }
            break;
        }
        case 'speed': {
            // proche de speed_upgrade
            $price = pow(1 * 40, 2) + 1000; // = 2600
            if ((int)$user['uridium'] >= $price) {
                $db->prepare("UPDATE users SET uridium = uridium - :p WHERE id = :id")
                   ->execute([':p' => $price, ':id' => $user['id']]);
                $addToInventory($db, (int)$user['id'], 4, 1); // item_id 4 = G3N-7900
                $buymessage = "Purchase success ! (Speed Generator +10 speed)";
            } else {
                $buymessage = "Error : Not enough uridium";
            }
            break;
        }
        case 'iris': {
            // reprise de la logique upgrades.php (prix progressif & string drones)
            $user_drones = substr_count($user['drones'], "-") - 2;
            if ($user['apis_built'] == 1) $user_drones--;
            if ($user['zeus_built'] == 1) $user_drones--;
            $price = (pow($user_drones + 1, 2)) * 1000000;

            if ((int)$user['credits'] >= $price) {
                if ($user_drones < 8) {
                    switch ($user_drones) {
                        case 0:  $drones_str = "3/0-3/1-25-3/0"; break;
                        case 1:  $drones_str = "3/0-3/2-25-25-3/0"; break;
                        case 2:  $drones_str = "3/0-3/3-25-25-25-3/0"; break;
                        case 3:  $drones_str = "3/0-3/4-25-25-25-25-3/0"; break;
                        case 4:  $drones_str = "3/1-25-3/4-25-25-25-25-3/0"; break;
                        case 5:  $drones_str = "3/1-25-3/4-25-25-25-25-3/1-25"; break;
                        case 6:  $drones_str = "3/2-25-25-3/4-25-25-25-25-3/1-25"; break;
                        case 7:  $drones_str = "3/2-25-25-3/4-25-25-25-25-3/2-25-25"; break;
                        default: $drones_str = "3/0-3/1-25-3/0"; break;
                    }
                    $db->prepare('UPDATE users SET drones=:d, credits=credits-:c WHERE id=:id')
                       ->execute([':d'=>$drones_str, ':c'=>$price, ':id'=>$user['id']]);
                    $buymessage = "Purchase success ! (Iris)";
                } else {
                    $buymessage = "Error : You already have 8 Iris !";
                }
            } else {
                $buymessage = "Error : Not enough credits !";
            }
            break;
        }
        case 'apis': {
            if ((int)$user['credits'] >= 100000000 && (int)$user['drone_parts'] >= 30) {
                if ((int)$user['apis_built'] === 1) {
                    $buymessage = "Error : Apis already owned !";
                } else {
                    $user_drones = substr_count($user['drones'], '-') - 2;
                    if ($user_drones == 8) {
                        $sql = 'UPDATE users SET drone_parts=drone_parts-30, credits=credits-100000000, apis_built=1, drones="3/3-25-25-25-3/3-25-25-25-3/3-25-25-25" WHERE id='.$_SESSION['player_id'];
                    } else if ($user_drones == 9) {
                        $sql = 'UPDATE users SET drone_parts=drone_parts-30, credits=credits-100000000, apis_built=1, drones="3/3-25-25-25-3/4-25-25-25-25-3/3-25-25-25" WHERE id='.$_SESSION['player_id'];
                    } else {
                        $sql = '';
                        $buymessage = "Error : You don't have all iris !";
                    }
                    if ($sql !== '') {
                        $req = $db->prepare($sql);
                        $req->execute();
                        $buymessage = "Purchase success ! (Apis)";
                    }
                }
            } else {
                $buymessage = "Error : Not enough credits or drone parts !";
            }
            break;
        }
        case 'zeus': {
            if ((int)$user['credits'] >= 100000000 && (int)$user['drone_parts'] >= 30) {
                if ((int)$user['zeus_built'] === 1) {
                    $buymessage = "Error : Zeus already owned !";
                } else {
                    $user_drones = substr_count($user['drones'], '-') - 2;
                    if ($user_drones == 8) {
                        $sql = 'UPDATE users SET drone_parts=drone_parts-30, credits=credits-100000000, zeus_built=1, drones="3/3-25-25-25-3/3-25-25-25-3/3-25-25-25" WHERE id='.$_SESSION['player_id'];
                    } else if ($user_drones == 9) {
                        $sql = 'UPDATE users SET drone_parts=drone_parts-30, credits=credits-100000000, zeus_built=1, drones="3/3-25-25-25-3/4-25-25-25-25-3/3-25-25-25" WHERE id='.$_SESSION['player_id'];
                    } else {
                        $sql = '';
                        $buymessage = "Error : You don't have all iris !";
                    }
                    if ($sql !== '') {
                        $req = $db->prepare($sql);
                        $req->execute();
                        $buymessage = "Purchase success ! (Zeus)";
                    }
                }
            } else {
                $buymessage = "Error : Not enough credits or drone parts !";
            }
            break;
        }
    }
}
?>

<!-- ======= TON HTML ORIGINAL (inchangé) ======= -->
<div class="box" style="margin-top:50px;margin-left:140px;margin-bottom:20px;">
	<div class="title">Shop</div>
	<div id="shop">
		<div class="stat" style="width:550px;">
			<div class="stat-left">
				Booty keys
			</div>
			<div class="stat-right tooltip" style="width:390px;text-align:left; padding-left: 10px;">
				Booty keys
				<span style="text-align:center;">
					<img class="callout" src="img/callout.gif" />
					<strong>Booty keys</strong><br/>
					Credits: <font color='#00AAFF'>1,000,000</font>
				</span>
				<form action="view.php?page=shop&tab=items" method="post" style="margin-top:-16px;">
					<label for="" style="position:absolute;right:130px; color:#81B7F2;" >Amount :</label><input style="opacity:0.6;width:50px;position:absolute;right:80px;margin-top:-1px;text-align:center;" type="text" name="amount-a" value="1">
					<input name="submit-booty" class="buy-stat"  style="width:50px;height:20px;" type="submit" value="Buy">
				</form>
			</div>
		</div>		
		
		<div class="stat" style="width:550px;">
			<div class="stat-left">
				Logfiles
			</div>
			<div class="stat-right  tooltip" style="width:390px;text-align:left; padding-left: 10px;">
				Uridium
				<span style="text-align:center;">
					<img class="callout" src="img/callout.gif" />
					<strong>Logfiles</strong><br/>
					Uridium: <font color='magenta'>1,000</font>
				</span>
				<form action="view.php?page=shop&tab=items" method="post" style="margin-top:-16px;">
					<label for="" style="position:absolute;right:130px; color:#81B7F2;" >Amount :</label><input style="opacity:0.6;width:50px;position:absolute;right:80px;margin-top:-1px;text-align:center;" type="text" name="amount-b" value="1">
					<input name="submit-loguri" class="buy-stat"  style="width:50px;height:20px;" type="submit" value="Buy">
				</form>
			</div>
		</div>		

		<div class="stat" style="width:550px;">
			<div class="stat-left">
				Logfiles
			</div>
			<div class="stat-right tooltip" style="width:390px;text-align:left; padding-left: 10px;">
				Uridium + Credits
				<span style="text-align:center;">
					<img class="callout" src="img/callout.gif" />
					<strong>Logfiles</strong><br/>
					Credits: <font color='#00AAFF'>800,000</font>
					</br>Uridium: <font color='magenta'>700</font>
				</span>
				<form action="view.php?page=shop&tab=items" method="post" style="margin-top:-16px;">
					<label for="" style="position:absolute;right:130px; color:#81B7F2;" >Amount :</label><input style="opacity:0.6;width:50px;position:absolute;right:80px;margin-top:-1px;text-align:center;" type="text" name="amount-c" value="1">
					<input name="submit-logspecial" class="buy-stat"  style="width:50px;height:20px;" type="submit" value="Buy">
				</form>
			</div>
		</div>
		
		<div class="stat" style="width:550px;">
			<div class="stat-left">
				Lottery's ticket
			</div>
			<div class="stat-right tooltip" style="width:390px;text-align:left; padding-left: 10px;">
				Logfiles + Palladium
				<span style="text-align:center;">
					<img class="callout" src="img/callout.gif" />
					<strong>Lottery's ticket</strong><br/>
					Logfiles: <font color='#00AAFF'>100</font>
					</br>Palladium: <font color='magenta'>100</font>
				</span>
				<form action="view.php?page=shop&tab=items" method="post" style="margin-top:-16px;">
					<label for="" style="position:absolute;right:130px; color:#81B7F2;" >Amount :</label><input style="opacity:0.6;width:50px;position:absolute;right:80px;margin-top:-1px;text-align:center;" type="text" name="amount-d" value="1">
					<input name="submit-ticketpalla" class="buy-stat"  style="width:50px;height:20px;" type="submit" value="Buy">
				</form>
			</div>
		</div>
		
		<div class="stat" style="width:550px;">
			<div class="stat-left">
				Lottery's ticket
			</div>
			<div class="stat-right tooltip" style="width:390px;text-align:left; padding-left: 10px;">
				Uridium
				<span style="text-align:center;">
					<img class="callout" src="img/callout.gif" />
					<strong>Lottery's ticket</strong><br/>
					Uridium: <font color='magenta'>400.000</font>
				</span>
				<form action="view.php?page=shop&tab=items" method="post" style="margin-top:-16px;">
					<label for="" style="position:absolute;right:130px; color:#81B7F2;" >Amount :</label><input style="opacity:0.6;width:50px;position:absolute;right:80px;margin-top:-1px;text-align:center;" type="text" name="amount-e" value="1">
					<input name="submit-tickettoken" class="buy-stat"  style="width:50px;height:20px;" type="submit" value="Buy">
				</form>
			</div>
		</div>
		
		<br>
	</div>	
</div>


<?php
// ======= TES FONCTIONS ORIGINALES (inchangées) =======
function handleticketpalla($db)
{
	if(empty($_POST['amount-d']))
	{
		$amount = 1;
	}
	else
	{
		$amount = (int)$_POST['amount-d'];
	}
	if($amount < 1)
	{
		$amount = 1;
	}
	
	$sth = $db->prepare("SELECT logfiles
	 FROM users WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();

	$logfiles = $datauser[0]['logfiles'];
	
	$sth = $db->prepare("SELECT palladium
	 FROM player_cargo WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();

	$palladium = $datauser[0]['palladium'];
	
	if($logfiles < (100*$amount))
	{
		return 'Not enough Logfiles';
	}
	if($palladium < (100*$amount))
	{
		return 'Not enough Palladium';
	}
	
	$req = $db->prepare('UPDATE users SET logfiles=logfiles-'.(100*$amount).' WHERE id='.$_SESSION['player_id']);
	$req->execute();
	
	$req = $db->prepare('UPDATE player_cargo SET palladium=palladium-'.(100*$amount).' WHERE id='.$_SESSION['player_id']);
	$req->execute();
	
	$req = $db->prepare('UPDATE users_infos SET tickets=tickets+'.$amount.' WHERE id='.$_SESSION['player_id']);
	$req->execute();
	
	return 'Tickets purchased'; 
}


function handletickettoken($db)
{
	if(empty($_POST['amount-e']))
	{
		$amount = 1;
	}
	else
	{
		$amount = (int)$_POST['amount-e'];
	}
	if($amount < 1)
	{
		$amount = 1;
	}
	
	$sth = $db->prepare("SELECT uridium
	 FROM users WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();

	$uridium = $datauser[0]['uridium'];
	
	if($uridium < $amount*400000)
	{
		return 'Not enough Uridium';
	}
	
	$req = $db->prepare('UPDATE users SET uridium=uridium-'.($amount*400000).' WHERE id='.$_SESSION['player_id']);
	$req->execute();
	
	$req = $db->prepare('UPDATE users_infos SET tickets=tickets+'.$amount.' WHERE id='.$_SESSION['player_id']);
	$req->execute();
	
	return 'Tickets purchased'; 
}

function handleSubmitLog($db)
{
	$sth = $db->prepare("SELECT uridium
	FROM users WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();
	
	if(empty($_POST['amount-b']))
	{
		$amount = 1;
	}
	else
	{
		$amount = (int)$_POST['amount-b'];
	}
	if($amount < 1)
	{
		$amount = 1;
	}
	$price = 1000*$amount;
	if($datauser[0]['uridium'] >= $price)
	{
		$req = $db->prepare('UPDATE users SET logfiles=logfiles+'.$amount.', uridium=uridium-'.$price.' WHERE id='.$_SESSION['player_id']);
		$req->execute();
		return 'Logfiles purchased'; 
	}
	else
	{
		return 'Not enough uridium';
	}
}

function handleSubmitLogSpecial($db)
{
	$sth = $db->prepare("SELECT uridium, credits
	FROM users WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();
	
	if(empty($_POST['amount-c']))
	{
		$amount = 1;
	}
	else
	{
		$amount = (int)$_POST['amount-c'];
	}
	if($amount < 1)
	{
		$amount = 1;
	}	
	$priceC = 800000*$amount;
	$priceU = 700*$amount;
	if($datauser[0]['uridium'] >= $priceU && $datauser[0]['credits'] >= $priceC)
	{
		$req = $db->prepare('UPDATE users SET logfiles=logfiles+'.$amount.', uridium=uridium-'.$priceU.', credits=credits-'.$priceC.'  WHERE id='.$_SESSION['player_id']);
		$req->execute();
		return 'Logfiles purchased'; 
	}
	else
	{
		return 'Not enough credits or uridium';
	}
}

function handleSubmitBooty($db)
{
	$sth = $db->prepare("SELECT  credits
	FROM users WHERE id = :id LIMIT 1");
	$sth->execute(array(
					':id' => $_SESSION['player_id']
				));
	$datauser = $sth->fetchAll();
	
	if(empty($_POST['amount-a']))
	{
		$amount = 1;
	}
	else
	{
		$amount = (int)$_POST['amount-a'];
	}
	if($amount < 1)
	{
		$amount = 1;
	}
	$price = 1000000*$amount;
	if($datauser[0]['credits'] >= $price)
	{
		$req = $db->prepare('UPDATE users SET booty_keys=booty_keys+'.$amount.', credits=credits-'.$price.' WHERE id='.$_SESSION['player_id']);
		$req->execute();
		return 'Booty keys purchased'; 
	}
	else
	{
		return 'Not enough credits';
	}
}
?>

<!-- ============================== -->
<!-- 🆕 BLOC VISUEL : Equipment Shop -->
<!-- ============================== -->
<div class="box" style="margin-left:140px;margin-bottom:30px;">
    <div class="title">Equipment Shop</div>
    <div class="shop-grid">
        <div class="shop-item">
            <img src="img/items/lf3.png" alt="LF-3" />
            <h3>LF-3 Laser</h3>
            <p>+200 damage per unit</p>
            <a href="view.php?page=shop&tab=items&buy=laser" class="buy">Buy (Uridium)</a>
        </div>

        <div class="shop-item">
            <img src="img/items/sg3n.png" alt="SG3N-A01" />
            <h3>SG3N-A01 Shield</h3>
            <p>+10’000 shield per unit</p>
            <a href="view.php?page=shop&tab=items&buy=shield" class="buy">Buy (Uridium)</a>
        </div>

        <div class="shop-item">
            <img src="img/items/g3n7900.png" alt="G3N-7900" />
            <h3>G3N-7900 Speed Generator</h3>
            <p>+10 speed per unit</p>
            <a href="view.php?page=shop&tab=items&buy=speed" class="buy">Buy (Uridium)</a>
        </div>

        <div class="shop-item">
            <img src="img/items/iris.png" alt="Iris" />
            <h3>Iris Drone</h3>
            <p>Progressive price (Credits)</p>
            <a href="view.php?page=shop&tab=items&buy=iris" class="buy">Buy (Credits)</a>
        </div>

        <div class="shop-item">
            <img src="img/items/apis.png" alt="Apis" />
            <h3>Apis Drone</h3>
            <p>100,000,000 Credits + 30 parts</p>
            <a href="view.php?page=shop&tab=items&buy=apis" class="buy">Buy (Credits + Parts)</a>
        </div>

        <div class="shop-item">
            <img src="img/items/zeus.png" alt="Zeus" />
            <h3>Zeus Drone</h3>
            <p>100,000,000 Credits + 30 parts</p>
            <a href="view.php?page=shop&tab=items&buy=zeus" class="buy">Buy (Credits + Parts)</a>
        </div>
    </div>
</div>

<style>
.shop-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 15px;
    padding: 10px;
}
.shop-item {
    border: 1px solid #333;
    background: #111;
    color: #ccc;
    padding: 10px;
    text-align: center;
    border-radius: 6px;
}
.shop-item img {
    width: 64px;
    height: 64px;
    margin-bottom: 5px;
}
.shop-item h3 {
    color: #fff;
    margin-bottom: 5px;
}
.shop-item .buy {
    display: inline-block;
    margin-top: 5px;
    padding: 5px 10px;
    background: #7a00ff;
    color: #fff;
    border-radius: 4px;
    text-decoration: none;
}
</style>
