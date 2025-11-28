<?php
session_start();

// mêmes vérifs que spacemap.php
if (!isset($_SESSION['terms_of_use']) || $_SESSION['terms_of_use'] != true) {
    header('Location: index.php');
    exit();
}
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) {
    header('Location: login.php');
    exit();
}

include '../libs/database.php';
include '../config/database.php';

$db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);

// Récup faction + map, comme dans spacemap.php
$sth = $db->prepare("
    SELECT factionid, mapid
    FROM users
    WHERE id = :id
    LIMIT 1
");
$sth->execute([':id' => $_SESSION['player_id']]);
$datauser = $sth->fetchAll();

if ($datauser[0]['factionid'] == 0) {
    header('Location: company.php');
    exit();
}

// Génère un nouveau sessionID (AuthTicket)
$sid = sha1(rand(1000, 9999));
$req = $db->prepare('UPDATE users SET AuthTicket = :sid WHERE id = :id');
$req->execute(['sid' => $sid, 'id' => $_SESSION['player_id']]);

// Récup résolution client (comme avant)
$returnvalue = $db->select(
    'SELECT client_resolution FROM users_settings WHERE playerid = :account_id',
    ['account_id' => $_SESSION['player_id']]
);
$string = $returnvalue[0]['client_resolution'];

$str = substr($string, 0, strlen($string) - 2);
$exploded = explode(',', $str);

// un peu de sécurité
$userId = (int)$_SESSION['player_id'];
$mapId  = (int)$datauser[0]['mapid'];
$resId  = (int)$exploded[0];
$width  = (int)$exploded[1];
$height = (int)$exploded[2];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Andromeda HTML5 – Test WebSocket</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            background: #000;
            color: #0f0;
            font-family: Consolas, monospace;
            height: 100%;
            overflow: hidden;
        }
        #gameCanvas {
            display: block;
            margin: 0 auto;
            background: #000;
        }
    </style>
</head>
<body>

<!-- Canvas utilisé par client.js -->
<canvas id="gameCanvas"
        width="<?php echo $width; ?>"
        height="<?php echo $height; ?>"></canvas>

<script>
    window.ANDROMEDA_CONFIG = {
        lang: "es",
        userID: <?php echo $userId; ?>,
        factionId: "VRU",
        sessionID: "<?php echo $sid; ?>",
        basePath: "../spacemap/",
        mapID: <?php echo $mapId; ?>,

        // Résolution venant de la BDD
        width:  <?php echo $width; ?>,
        height: <?php echo $height; ?>,

        // Infos pour le WebSocket (via proxy Node)
        host: "127.0.0.1",
        port: 8082
    };
</script>

<script src="client_config.js?v=999"></script>
<script src="client_network.js?v=999"></script>
<script src="client_entities.js?v=999"></script>
<script src="client_combat.js?v=999"></script>
<script src="client_graphics.js?v=999"></script>
<script src="client_ui.js?v=999"></script>
<script src="client_bootstrap.js?v=999"></script>
</body>
</html>
