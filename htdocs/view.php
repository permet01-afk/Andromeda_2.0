<?php
session_start();
if (!isset($_SESSION['terms_of_use']) || $_SESSION['terms_of_use'] !== true) {
    header('Location: index.php');
    exit();
}
if (!isset($_SESSION['loggedIn']) || $_SESSION['terms_of_use'] !== true) {
    header('Location: login.php');
    exit();
}

ob_start();

include 'libs/database.php';
include 'config/database.php';

$db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);

$sth = $db->prepare('SELECT factionid, clanid FROM users WHERE id = :id LIMIT 1');
$sth->execute([
    ':id' => $_SESSION['player_id'],
]);
$datauser = $sth->fetchAll();

if ($datauser[0]['factionid'] == 0) {
    header('Location: company.php');
    exit();
}

$bHasClan = (int)($datauser[0]['clanid'] ?? 0) > 0;

local_entete();

$displayPage = 'home';
if (isset($_GET['page'])) {
    $displayPage = $_GET['page'];
}

$allowed = [
    'clan',
    'company',
    'contact',
    'home',
    'rules',
    'settings',
    'shop',
    'store',
    'user',
    'top100',
    'lottery',
];

$navActiveClass = function (array $pages) use ($displayPage): string {
    return in_array($displayPage, $pages, true) ? ' is-active' : '';
};

$userNavPages = ['user', 'company', 'settings'];
$clanNavPages = ['clan'];
$shopNavPages = ['shop'];

$userExpanded = in_array($displayPage, $userNavPages, true);
$clanExpanded = in_array($displayPage, $clanNavPages, true);
$shopExpanded = in_array($displayPage, $shopNavPages, true);
?>
<div class="app-shell">
    <header class="app-header">
        <div class="header-primary">
            <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="app-navigation">
                <span class="sr-only">Toggle navigation</span>
                <span class="nav-toggle-bar"></span>
                <span class="nav-toggle-bar"></span>
                <span class="nav-toggle-bar"></span>
            </button>
            <a class="brand" href="view.php?page=home">
                <img src="img/logo.png" alt="Andromeda" width="160" height="56" />
                <span class="brand-text">Command Center</span>
            </a>
        </div>
        <div class="header-actions">
            <a class="header-link" href="view.php?page=home">Dashboard</a>
            <a class="header-link" href="logout.php">Logout</a>
        </div>
    </header>

    <nav class="app-nav" id="app-navigation">
        <ul class="nav-list">
            <li class="nav-item<?php echo $navActiveClass(['home']); ?>">
                <a class="nav-link" href="view.php?page=home">Home</a>
            </li>

            <li class="nav-item has-submenu<?php echo $navActiveClass($userNavPages); ?><?php echo $userExpanded ? ' is-expanded' : ''; ?>">
                <a class="nav-link" href="view.php?page=user" aria-haspopup="true" aria-expanded="<?php echo $userExpanded ? 'true' : 'false'; ?>">User</a>
                <button class="submenu-toggle" type="button" aria-expanded="<?php echo $userExpanded ? 'true' : 'false'; ?>" aria-label="Toggle user menu"><span class="submenu-toggle-icon" aria-hidden="true"></span></button>
                <ul class="submenu" aria-label="User menu">
                    <li><a class="submenu-link" href="view.php?page=user&amp;tab=infos">Informations</a></li>
                    <li><a class="submenu-link" href="view.php?page=company">Company Change</a></li>
                    <li><a class="submenu-link" href="view.php?page=settings">Settings</a></li>
                    <li><a class="submenu-link" href="view.php?page=user&amp;tab=upgrades">Upgrades</a></li>
                    <li><a class="submenu-link" href="view.php?page=user&amp;tab=configurations">Configurations</a></li>
                    <li><a class="submenu-link" href="view.php?page=user&amp;tab=achievements">Achievements</a></li>
                </ul>
            </li>

            <li class="nav-item has-submenu<?php echo $navActiveClass($clanNavPages); ?><?php echo $clanExpanded ? ' is-expanded' : ''; ?>">
                <a class="nav-link" href="view.php?page=clan" aria-haspopup="true" aria-expanded="<?php echo $clanExpanded ? 'true' : 'false'; ?>">Clan</a>
                <button class="submenu-toggle" type="button" aria-expanded="<?php echo $clanExpanded ? 'true' : 'false'; ?>" aria-label="Toggle clan menu"><span class="submenu-toggle-icon" aria-hidden="true"></span></button>
                <ul class="submenu" aria-label="Clan menu">
                    <?php if ($bHasClan) { ?>
                        <li><a class="submenu-link" href="view.php?page=clan&amp;tab=claninfos">Informations</a></li>
                        <li><a class="submenu-link" href="view.php?page=clan&amp;tab=clanmembers">Members</a></li>
                        <li><a class="submenu-link" href="view.php?page=clan&amp;tab=diplomacy_alliance">Clan Alliances</a></li>
                        <li><a class="submenu-link" href="view.php?page=clan&amp;tab=diplomacy_war">Clan Wars</a></li>
                    <?php } else { ?>
                        <li><a class="submenu-link" href="view.php?page=clan&amp;tab=joinclan">Join Clan</a></li>
                        <li><a class="submenu-link" href="view.php?page=clan&amp;tab=createclan">Create Clan</a></li>
                    <?php } ?>
                </ul>
            </li>

            <!-- ✅ SHOP MENU with new Ship tab -->
            <li class="nav-item has-submenu<?php echo $navActiveClass($shopNavPages); ?><?php echo $shopExpanded ? ' is-expanded' : ''; ?>">
                <a class="nav-link" href="view.php?page=shop" aria-haspopup="true" aria-expanded="<?php echo $shopExpanded ? 'true' : 'false'; ?>">Shop</a>
                <button class="submenu-toggle" type="button" aria-expanded="<?php echo $shopExpanded ? 'true' : 'false'; ?>" aria-label="Toggle shop menu"><span class="submenu-toggle-icon" aria-hidden="true"></span></button>
                <ul class="submenu" aria-label="Shop menu">
                    <li><a class="submenu-link" href="view.php?page=shop&amp;tab=ship">Ships</a></li> <!-- 🆕 NEW SHIP TAB -->
                    <li><a class="submenu-link" href="view.php?page=shop&amp;tab=boosters">Boosters</a></li>
                    <li><a class="submenu-link" href="view.php?page=shop&amp;tab=designs">Ship Designs</a></li>
                    <li><a class="submenu-link" href="view.php?page=shop&amp;tab=items">Items</a></li>
                </ul>
            </li>
            <!-- ✅ END SHOP -->

            <li class="nav-item<?php echo $navActiveClass(['lottery']); ?>">
                <a class="nav-link" href="view.php?page=lottery">Lottery</a>
            </li>

            <li class="nav-item cta">
                <a class="nav-link" href="spacemap.php" target="_blank" rel="noopener">Play</a>
            </li>

            <li class="nav-item<?php echo $navActiveClass(['store']); ?>">
                <a class="nav-link" href="view.php?page=store">Store</a>
            </li>

            <li class="nav-item<?php echo $navActiveClass(['rules']); ?>">
                <a class="nav-link" href="view.php?page=rules">Rules</a>
            </li>

            <li class="nav-item<?php echo $navActiveClass(['contact']); ?>">
                <a class="nav-link" href="view.php?page=contact">Contacts</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="forum">Forum</a>
            </li>
        </ul>
    </nav>

    <main class="app-main" id="main-content">
        <div class="app-content">
            <?php
            if (in_array($displayPage, $allowed, true)) {
                include 'views/' . $displayPage . '.php';
            } else {
                echo '<div class="app-feedback">Not allowed!</div>';
            }
            ?>
        </div>
    </main>

    <footer class="app-footer">
        <p>&copy; <?php echo date('Y'); ?> Andromeda. All rights reserved.</p>
    </footer>
</div>

<?php
local_pied();
ob_end_flush();

function local_entete()
{
    echo '<!DOCTYPE html>',
        '<html lang="en">',
        '<head>',
        '<meta charset="utf-8" />',
        '<meta name="viewport" content="width=device-width, initial-scale=1" />',
        '<title>Andromeda</title>',
        '<link rel="stylesheet" type="text/css" href="styles/default.css" />',
        '<link rel="stylesheet" type="text/css" href="styles/mainStyles.css" />',
        '<style>',
        '@media (hover: hover) and (pointer: fine){',
        '  .nav-item.has-submenu{position:relative;}',
        '  .nav-item.has-submenu .submenu{display:none; position:absolute; top:100%; left:0; z-index:50;}',
        '  .nav-item.has-submenu:hover .submenu{display:block;}',
        '  .nav-item.has-submenu.is-expanded .submenu{display:none;}',
        '}',
        '</style>',
        '</head>',
        '<body>';
}

function local_pied()
{
    echo '<script>',
        'document.addEventListener("DOMContentLoaded",function(){',
        'const toggle=document.querySelector(".nav-toggle");',
        'const nav=document.getElementById("app-navigation");',
        'if(toggle&&nav){',
        'toggle.addEventListener("click",function(){',
        'const expanded=toggle.getAttribute("aria-expanded")==="true";',
        'toggle.setAttribute("aria-expanded",(!expanded).toString());',
        'nav.classList.toggle("is-open",!expanded);',
        '});',
        '}',

        'const items=[...document.querySelectorAll(".nav-item.has-submenu")];',
        'const isTouch=window.matchMedia("(hover: none), (pointer: coarse)").matches;',

        'if(isTouch){',
        'items.forEach(item=>{',
        'const btn=item.querySelector(".submenu-toggle");',
        'const link=item.querySelector(".nav-link");',

        'const doToggle=(e)=>{',
        'e.preventDefault();',
        'items.forEach(i=>{if(i!==item){i.classList.remove("is-expanded");}});',
        'item.classList.toggle("is-expanded");',
        'const expanded=item.classList.contains("is-expanded");',
        'if(btn) btn.setAttribute("aria-expanded",expanded.toString());',
        'if(link) link.setAttribute("aria-expanded",expanded.toString());',
        '};',

        'if(btn) btn.addEventListener("click",doToggle);',
        'if(link) link.addEventListener("click",function(e){',
        'if(!item.classList.contains("is-expanded")) doToggle(e);',
        '});',
        '});',

        'document.addEventListener("click",function(e){',
        'if(!e.target.closest(".nav-item.has-submenu")){',
        'items.forEach(i=>i.classList.remove("is-expanded"));',
        '}',
        '});',
        '}',
        '});',
        '</script>',
        '</body>',
        '</html>';
}
?>
