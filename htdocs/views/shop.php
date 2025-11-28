<?php 
$displayTab = 'boosters';
if (isset($_GET['tab'])) {
    $displayTab = $_GET['tab'];
}
?>
<link rel="stylesheet" type="text/css" href="styles/home.css" />
<link rel="stylesheet" type="text/css" href="styles/userStyle.css" />
<link rel="stylesheet" type="text/css" href="styles/shop.css" />

<div class="CMSContent">
<?php 
    // ✅ Liste des onglets autorisés dans le shop
    $allowed = array(
        'items',
        'boosters',
        'designs',
        'ship' // 🆕 Nouveau onglet pour l'achat des vaisseaux
    );

    if (in_array($displayTab, $allowed)) {
        // ✅ Inclusion dynamique du contenu de l’onglet
        $filePath = 'views/shopTabs/' . $displayTab . '.php';
        if (file_exists($filePath)) {
            include($filePath);
        } else {
            echo '<center>⚠️ Fichier manquant : ' . htmlspecialchars($displayTab) . '.php</center>';
        }
    } else {
        echo '<center>🚫 Onglet non autorisé !</center>';
    }
?>
</div>

<?php
// ✅ Gestion des messages d’achat avec pop-up
if (isset($buymessage)) {
?>
    <div id="popup_box">
        <div id="popupContent"><?= htmlspecialchars($buymessage) ?></div>
        <a id="popupBoxClose">Close</a>    
    </div>

    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
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
