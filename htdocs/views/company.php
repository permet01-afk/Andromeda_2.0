<?php 
if(!empty($_GET['factionid']) && !empty($_GET['type']))
{
    $buymessage = handleCompanyChange($db,(int)$_GET['factionid'],(int)$_GET['type']);
}
?>

<link rel="stylesheet" type="text/css" href="styles/home.css" />
<link rel="stylesheet" type="text/css" href="styles/company.css" />
<link rel="stylesheet" type="text/css" href="styles/userStyle.css" />
<div class="CMSContent">
    <div class="box" style="margin-left:220px;margin-bottom:20px;">
        <div class="title">
            Company Change
            </br>
            Cost: 250,000 uridium, 30% rankpoints
        </div>
        <div id="company">
            <center>
            <a href="view.php?page=company&factionid=1&type=1">
                <img class="company_logo" src="img/mmo.jpg">
            </a>
            
            <a href="view.php?page=company&factionid=2&type=1">
                <img class="company_logo" src="img/eic.jpg">
            </a>
            
            <a href="view.php?page=company&factionid=3&type=1">
                <img class="company_logo" src="img/vru.jpg">
            </a>
            </center>
        </div>
    </div>  
    <div class="box" style="margin-left:220px;margin-bottom:20px;">
        <div class="title">
            Company Change
            </br>
            Cost: 5 tokens, 250,000 uridium, 15% rankpoints
        </div>
        <div id="company" style="padding-left:1px;">
            <center>
            <a href="view.php?page=company&factionid=1&type=2">
                <img class="company_logo" src="img/mmo.jpg">
            </a>
            
            <a href="view.php?page=company&factionid=2&type=2">
                <img class="company_logo" src="img/eic.jpg">
            </a>
            
            <a href="view.php?page=company&factionid=3&type=2">
                <img class="company_logo" src="img/vru.jpg">
            </a>
            </center>
        </div>
    </div>  
    <div class="box" style="margin-left:220px;margin-bottom:20px;">
        <div class="title">
            Company Change
            </br>
            Cost: 10 tokens, 250,000 uridium, 5% rankpoints
        </div>
        <div id="company" style="padding-left:1px;">
            <center>
                <a href="view.php?page=company&factionid=1&type=3">
                    <img class="company_logo" src="img/mmo.jpg">
                </a>
                
                <a href="view.php?page=company&factionid=2&type=3">
                    <img class="company_logo" src="img/eic.jpg">
                </a>
                
                <a href="view.php?page=company&factionid=3&type=3">
                    <img class="company_logo" src="img/vru.jpg">
                </a>
            </center>
        </div>
    </div>  
</div>  

<?php
if(isset($buymessage))
{
?>
    <div id="popup_box">    <!-- OUR PopupBox DIV-->
        <div id="popupContent"> 
        <?=$buymessage?>
        </div>
        <a id="popupBoxClose">Close</a>    
    </div>

    <script src="http://jqueryjs.googlecode.com/files/jquery-1.2.6.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            loadPopupBox();
        
            $('#popupBoxClose').click(function() {            
                unloadPopupBox();
            });
            
            $('#container').click(function() {
                unloadPopupBox();
            });

            function unloadPopupBox() {
                $('#popup_box').fadeOut("slow");
                $("#container").css({
                    "opacity": "1"  
                }); 
            }    
            
            function loadPopupBox() {
                $('#popup_box').fadeIn("slow");
                $("#container").css({
                    "opacity": "0.3"  
                });         
            }        
        });
    </script>  
<?php
}
?>
    
<?php
function handleCompanyChange($db, $factionid, $type)
{
    // Récupération des tokens
    $sth = $db->prepare("SELECT tokens FROM users_infos WHERE id = :id LIMIT 1");
    $sth->execute([':id' => $_SESSION['player_id']]);
    $datauser = $sth->fetchAll();

    $tokens = $datauser[0]['tokens'] ?? 0;
    
    // Récupération des données utilisateur
    $sth = $db->prepare("SELECT uridium, rankpoints, clanid FROM users WHERE id = :id LIMIT 1");
    $sth->execute([':id' => $_SESSION['player_id']]);
    $datauser = $sth->fetchAll();

    if (empty($datauser)) {
        return "User not found.";
    }

    if ($datauser[0]['uridium'] <= 250000) {
        return "Not enough uridium";
    }
    
    // Calcul du nouveau rankpoints
    if ($type == 1) {
        $newRankpoints = floor($datauser[0]['rankpoints'] * 0.70);
    } elseif ($type == 2) {
        if ($tokens >= 5) {
            $newRankpoints = floor($datauser[0]['rankpoints'] * 0.85);
            $req = $db->prepare('UPDATE users_infos SET tokens = tokens - 5 WHERE id = :id');
            $req->execute([':id' => $_SESSION['player_id']]);
        } else {
            return "Not enough tokens";
        }
    } elseif ($type == 3) {
        if ($tokens >= 10) {
            $newRankpoints = floor($datauser[0]['rankpoints'] * 0.95);
            $req = $db->prepare('UPDATE users_infos SET tokens = tokens - 10 WHERE id = :id');
            $req->execute([':id' => $_SESSION['player_id']]);
        } else {
            return "Not enough tokens";
        }
    } else {
        return "Type is not valid";
    }

    // Mise à jour selon la faction
    switch ($factionid) {
        case 1:
            $req = $db->prepare(
                'UPDATE users 
                 SET factionid=1, uridium=uridium-250000, rankpoints=:rp, locx=2000, locy=1100, mapid=1 
                 WHERE id=:id'
            );
            break;
        case 2:
            $req = $db->prepare(
                'UPDATE users 
                 SET factionid=2, uridium=uridium-250000, rankpoints=:rp, locx=18500, locy=1100, mapid=5 
                 WHERE id=:id'
            );
            break;
        case 3:
            $req = $db->prepare(
                'UPDATE users 
                 SET factionid=3, uridium=uridium-250000, rankpoints=:rp, locx=19200, locy=11300, mapid=9 
                 WHERE id=:id'
            );
            break;
        default:
            return "Company is not valid";
    }
    $req->execute([':rp' => $newRankpoints, ':id' => $_SESSION['player_id']]);
    
    // Sortir du clan
    $req = $db->prepare('UPDATE users SET clanid=0 WHERE id=:id');
    $req->execute([':id' => $_SESSION['player_id']]);
    
    $clan_id = $datauser[0]['clanid'];
    
    $sth = $db->prepare("SELECT * FROM clan WHERE id = :cid");
    $sth->execute([':cid' => $clan_id]);
    $clansdata = $sth->fetchAll();

    if (!empty($clansdata)) {
        $admin_id = $clansdata[0]['admin_id'];
        $is_admin = ($admin_id == $_SESSION['player_id']);
        
        if ($is_admin) {
            // Retirer tous les membres du clan
            $db->update('users', ['clanid' => 0], 'clanid='.$clan_id);
            
            // Supprimer le clan et ses relations
            $queries = [
                "DELETE FROM clan WHERE id=:clanid",
                "DELETE FROM clan_messages WHERE clanid=:clanid",
                "DELETE FROM clan_request WHERE clan_id=:clanid",
                "DELETE FROM clan_diplomacy WHERE clan_id=:clanid OR second_clan_id=:clanid",
                "DELETE FROM clan_diplomacy_request WHERE clan_id=:clanid OR second_clan_id=:clanid",
                "UPDATE users SET clanid=0 WHERE clanid=:clanid"
            ];
            foreach ($queries as $sql) {
                $sth = $db->prepare($sql);
                $sth->execute([':clanid' => $clan_id]);
            }
        }
    }

    header('Location: view.php');
    exit; // corrige l'erreur "break not in loop/switch"
}
?>
