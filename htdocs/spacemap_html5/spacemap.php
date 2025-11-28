<?php

session_start();

// mêmes sécurités que ton ancien fichier
if (!isset($_SESSION['terms_of_use']) || $_SESSION['terms_of_use'] != true) {
    header('Location: ../index.php');
    exit();
}
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) {
    header('Location: ../login.php');
    exit();
}

// chemins modifiés car on est dans spacemap_html5/
include '../libs/database.php';
include '../config/database.php';

$db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);

// récupérer la faction
$sth = $db->prepare("
    SELECT factionid
    FROM users
    WHERE id = :id
    LIMIT 1
");
$sth->execute([':id' => $_SESSION['player_id']]);
$datauser = $sth->fetchAll();

if ($datauser[0]['factionid'] == 0) {
    header('Location: ../company.php');
    exit();
}

// générer un AuthTicket comme avant
$sid = sha1(rand(1000, 9999));
$req = $db->prepare('UPDATE users SET AuthTicket = :sid WHERE id = :id');
$req->execute([
    'sid' => $sid,
    'id'  => $_SESSION['player_id']
]);

// résolution client
$returnvalue = $db->select(
    'SELECT client_resolution FROM users_settings WHERE playerid = :account_id',
    ['account_id' => $_SESSION['player_id']]
);
$string = $returnvalue[0]['client_resolution'];
$str = substr($string, 0, strlen($string) - 2);
$exploded = explode(',', $str);  // [resolutionID, width, height]

// map id
$sth = $db->prepare("
    SELECT mapid
    FROM users
    WHERE id = :id
    LIMIT 1
");
$sth->execute([':id' => $_SESSION['player_id']]);
$datauser = $sth->fetchAll();
$mapId = (int)$datauser[0]['mapid'];

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Andromeda HTML5 - Play with your skills, not your money.</title>

    <style type="text/css" media="screen">
        body {
            margin: 0;
            padding: 0;
            text-align: center;
            background: url("../img/andromeda_spacemap.jpg"); /* chemin remonté d’un niveau */
            width: 100%;
            height: 100%;
        }
        #gameContainer {
            margin: 0 auto;
            display: inline-block;
        }
    </style>

    <script>
        // Équivalent des flashvars, mais en JS
        window.ANDROMEDA_CONFIG = {
            lang: "es",
            userID: <?php echo (int)$_SESSION['player_id']; ?>,
            factionId: "VRU", // tu pourras lier à la faction réelle plus tard
            sessionID: "<?php echo $sid; ?>",
            basePath: "../spacemap/",     // là où sont tes assets SWF exportés etc.
            pid: 563,
            resolutionID: <?php echo (int)$exploded[0]; ?>,
            boardLink: "127.0.0.1",
            helpLink: "127.0.0.1",
            chatHost: "127.0.0.1",
            host: "127.0.0.1",            // IP du serveur de jeu (émulateur)
            port: 8081,                   // port de l’émulateur (à adapter si besoin)
            mapID: <?php echo $mapId; ?>,
            supportedResolutions: "0,1,2,3,4,5",
            width: <?php echo (int)$exploded[1]; ?>,
            height: <?php echo (int)$exploded[2]; ?>
        };
    </script>
</head>

<body>
    <div id="gameContainer">
        <!-- Le client HTML5 va être injecté ici -->
    </div>

    <!-- Notre futur client JS -->
    <script src="client.js"></script>
</body>
</html>
