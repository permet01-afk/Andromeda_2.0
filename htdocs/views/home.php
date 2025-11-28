<?php
$sth = $db->prepare('SELECT username, grade, factionid, clanid, credits, uridium, rankpoints FROM users WHERE id = :id LIMIT 1');
$sth->execute([
    ':id' => $_SESSION['player_id'],
]);
$datauser = $sth->fetchAll();
if ($datauser[0]['clanid'] != 0) {
    $sth = $db->prepare('SELECT clan_tag FROM clan WHERE id = :clanid LIMIT 1');
    $sth->execute([
        ':clanid' => $datauser[0]['clanid'],
    ]);
    $dataclan = $sth->fetchAll();
    $userclanag = '[' . $dataclan[0]['clan_tag'] . ']';
} else {
    $userclanag = '';
}
$sth = $db->prepare('SELECT timestamp,message FROM users_log WHERE playerid = :playerid ORDER BY timestamp DESC LIMIT 0, 10');
$sth->execute([
    ':playerid' => $_SESSION['player_id'],
]);
$userlog = $sth->fetchAll();

$last_active_limit = time() - (3600 * 14 * 24);
$sth = $db->prepare("SELECT users.username, users.rankpoints, users.factionid, users.grade FROM users WHERE lastlogin > $last_active_limit AND (SELECT count(id) AS is_ban FROM bans WHERE bans.user_id = users.id AND bans.timestamp_expire > UNIX_TIMESTAMP()) = 0  ORDER BY users.rankpoints DESC LIMIT 0, 10");
$sth->execute();
$top10 = $sth->fetchAll();
$sth = $db->prepare("SELECT users.username, (users.rankpoints + users.legend_rankpoints) as rankpoints, users.factionid, users.grade FROM users WHERE lastlogin > $last_active_limit AND (SELECT count(id) AS is_ban FROM bans WHERE bans.user_id = users.id AND bans.timestamp_expire > UNIX_TIMESTAMP() ) = 0  ORDER BY rankpoints DESC LIMIT 0, 10");
$sth->execute();
$legendtop10 = $sth->fetchAll();

$sth = $db->prepare('SELECT COUNT(*) as regplayer FROM users;');
$sth->execute();
$regplayer = $sth->fetchAll();

$sth = $db->prepare("SELECT sval as onlineplayer FROM server_statistics WHERE skey='active_connections';");
$sth->execute();
$onlineplayer = $sth->fetchAll();

$sth = $db->prepare("SELECT sval as mmoplayer FROM server_statistics WHERE skey='active_MMO';");
$sth->execute();
$mmoplayer = $sth->fetchAll();

$sth = $db->prepare("SELECT sval as eicplayer FROM server_statistics WHERE skey='active_EIC';");
$sth->execute();
$eicplayer = $sth->fetchAll();

$sth = $db->prepare("SELECT sval as vruplayer FROM server_statistics WHERE skey='active_VRU';");
$sth->execute();
$vruplayer = $sth->fetchAll();

$username = htmlspecialchars($datauser[0]['username'], ENT_QUOTES, 'UTF-8');
$clanLabel = $userclanag !== '' ? htmlspecialchars($userclanag, ENT_QUOTES, 'UTF-8') : '';
$credits = number_format($datauser[0]['credits']);
$uridium = number_format($datauser[0]['uridium']);
$rankpoints = number_format($datauser[0]['rankpoints']);
$registeredPlayers = number_format($regplayer[0]['regplayer']);
$onlinePlayers = number_format($onlineplayer[0]['onlineplayer']);
$companyCounts = [
    'mmo' => number_format($mmoplayer[0]['mmoplayer']),
    'eic' => number_format($eicplayer[0]['eicplayer']),
    'vru' => number_format($vruplayer[0]['vruplayer']),
];
?>
<link rel="stylesheet" type="text/css" href="styles/home.css" />
<section class="dashboard">
    <header class="dashboard-header">
        <h1>Command Center Overview</h1>
        <p>Stay up to date with your pilot stats, server activity, and the best pilots in the sector.</p>
    </header>
    <div class="dashboard-grid">
        <section class="dashboard-card user-card">
            <header class="card-header">
                <h2>Your pilot</h2>
            </header>
            <dl class="stat-list">
                <div class="stat-row">
                    <dt>Username</dt>
                    <dd>
                        <?php if ($clanLabel !== '') { ?>
                        <span class="stat-badge"><?php echo $clanLabel; ?></span>
                        <?php } ?>
                        <span><?php echo $username; ?></span>
                    </dd>
                </div>
                <div class="stat-row">
                    <dt>Company</dt>
                    <dd class="stat-media">
                        <img src="img/ranks/company/<?php echo (int) $datauser[0]['factionid']; ?>.png" alt="Faction <?php echo (int) $datauser[0]['factionid']; ?>" />
                        <span><?php echo (int) $datauser[0]['factionid']; ?></span>
                    </dd>
                </div>
                <div class="stat-row">
                    <dt>Grade</dt>
                    <dd class="stat-media">
                        <img src="img/ranks/<?php echo (int) $datauser[0]['grade']; ?>.png" alt="Grade <?php echo (int) $datauser[0]['grade']; ?>" />
                        <span><?php echo (int) $datauser[0]['grade']; ?></span>
                    </dd>
                </div>
                <div class="stat-row">
                    <dt>Rank points</dt>
                    <dd><?php echo $rankpoints; ?></dd>
                </div>
                <div class="stat-row">
                    <dt>Credits</dt>
                    <dd><?php echo $credits; ?></dd>
                </div>
                <div class="stat-row">
                    <dt>Uridium</dt>
                    <dd><?php echo $uridium; ?></dd>
                </div>
            </dl>
        </section>
        <section class="dashboard-card system-card">
            <header class="card-header">
                <h2>Andromeda status</h2>
            </header>
            <ul class="stat-summary">
                <li>
                    <span class="summary-label">Server time</span>
                    <span class="summary-value" data-label="Online">
                        <img src="img/Tick.png" alt="Online" width="20" height="20" />
                        <?php echo date('H:i:s T'); ?>
                    </span>
                </li>
                <li>
                    <span class="summary-label">Active pilots</span>
                    <span class="summary-value company-breakdown">
                        <span><img src="img/ranks/company/1.png" alt="MMO" /> <?php echo $companyCounts['mmo']; ?></span>
                        <span><img src="img/ranks/company/2.png" alt="EIC" /> <?php echo $companyCounts['eic']; ?></span>
                        <span><img src="img/ranks/company/3.png" alt="VRU" /> <?php echo $companyCounts['vru']; ?></span>
                    </span>
                </li>
                <li>
                    <span class="summary-label">Pilots connected</span>
                    <span class="summary-value"><?php echo $onlinePlayers; ?></span>
                </li>
                <li>
                    <span class="summary-label">Registered accounts</span>
                    <span class="summary-value"><?php echo $registeredPlayers; ?></span>
                </li>
            </ul>
            <section class="news-feed">
                <header class="card-subheader">
                    <h3>News</h3>
                </header>
                <div class="news-stream">
                    <article class="news-item">
                        <time datetime="2016-12-16">16/12/2016</time>
                        <p>TS Andromeda (unofficial): eu187.ts3.cloud:24163</p>
                    </article>
                    <article class="news-item">
                        <time datetime="2016-05-10">10/05/2016</time>
                        <p>Andromeda is proud to celebrate its first birthday!</p>
                    </article>
                    <article class="news-item">
                        <time datetime="2016-05-10">S15 Awards</time>
                        <p>Congratulation to blueassasin_Halil_TR (1), *TheDefencer* (2) and &#9733;&fnof;&alpha;i&#8467;&epsilon;d&#9733; (3)!</p>
                    </article>
                </div>
            </section>
        </section>
        <section class="dashboard-card hof-card">
            <header class="card-header">
                <h2>Hall of fame</h2>
                <a class="card-link" href="view.php?page=top100">View top 100</a>
            </header>
            <div class="tab-group" data-tabs>
                <div class="tab-controls" role="tablist" aria-label="Hall of fame leaderboards">
                    <button class="tab-control is-active" type="button" role="tab" id="tab-season" aria-controls="panel-season" aria-selected="true">Season 2</button>
                    <button class="tab-control" type="button" role="tab" id="tab-legends" aria-controls="panel-legends" aria-selected="false">Legends</button>
                </div>
                <div class="tab-panels">
                    <div class="tab-panel is-active" id="panel-season" role="tabpanel" aria-labelledby="tab-season">
                        <ol class="leaderboard">
                            <?php foreach ($top10 as $index => $player) { ?>
                            <li class="leaderboard-row">
                                <span class="leaderboard-rank">#<?php echo $index + 1; ?></span>
                                <span class="leaderboard-company"><img src="img/ranks/company/<?php echo (int) $player['factionid']; ?>.png" alt="Company <?php echo (int) $player['factionid']; ?>" /></span>
                                <span class="leaderboard-grade"><img src="img/ranks/<?php echo (int) $player['grade']; ?>.png" alt="Grade <?php echo (int) $player['grade']; ?>" /></span>
                                <span class="leaderboard-name"><?php echo htmlspecialchars($player['username'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <span class="leaderboard-score"><?php echo number_format($player['rankpoints']); ?></span>
                            </li>
                            <?php } ?>
                        </ol>
                    </div>
                    <div class="tab-panel" id="panel-legends" role="tabpanel" aria-labelledby="tab-legends">
                        <ol class="leaderboard">
                            <?php foreach ($legendtop10 as $index => $player) { ?>
                            <li class="leaderboard-row">
                                <span class="leaderboard-rank">#<?php echo $index + 1; ?></span>
                                <span class="leaderboard-company"><img src="img/ranks/company/<?php echo (int) $player['factionid']; ?>.png" alt="Company <?php echo (int) $player['factionid']; ?>" /></span>
                                <span class="leaderboard-grade"><img src="img/ranks/<?php echo (int) $player['grade']; ?>.png" alt="Grade <?php echo (int) $player['grade']; ?>" /></span>
                                <span class="leaderboard-name"><?php echo htmlspecialchars($player['username'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <span class="leaderboard-score"><?php echo number_format($player['rankpoints']); ?></span>
                            </li>
                            <?php } ?>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="dashboard-card log-card">
            <header class="card-header">
                <h2>Activity log</h2>
            </header>
            <ul class="log-list">
                <?php foreach ($userlog as $log) { ?>
                <li class="log-entry">
                    <time class="log-time" datetime="<?php echo htmlspecialchars($log['timestamp'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($log['timestamp'], ENT_QUOTES, 'UTF-8'); ?></time>
                    <div class="log-message"><?php echo $log['message']; ?></div>
                </li>
                <?php } ?>
            </ul>
        </section>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-tabs]').forEach(function (group) {
            var controls = group.querySelectorAll('[role="tab"]');
            var panels = group.querySelectorAll('[role="tabpanel"]');
            controls.forEach(function (control) {
                control.addEventListener('click', function () {
                    controls.forEach(function (btn) {
                        btn.classList.remove('is-active');
                        btn.setAttribute('aria-selected', 'false');
                    });
                    panels.forEach(function (panel) {
                        panel.classList.remove('is-active');
                    });
                    control.classList.add('is-active');
                    control.setAttribute('aria-selected', 'true');
                    var targetId = control.getAttribute('aria-controls');
                    if (targetId) {
                        var targetPanel = group.querySelector('#' + targetId);
                        if (targetPanel) {
                            targetPanel.classList.add('is-active');
                        }
                    }
                });
            });
        });
    });
</script>

