<?php
// Ce fichier est inclus depuis views/user.php
// On suppose que $db et $_SESSION['player_id'] existent déjà.

$pid = $_SESSION['player_id'] ?? 0;
$configs = [];

if ($pid && isset($db)) {
    $sth = $db->prepare("
        SELECT sc.id,
               sc.name,
               sc.lasers_slots,
               sc.gen_slots,
               sc.extras_slots,
               ss.damage_total,
               ss.shield_total,
               ss.speed_total
        FROM ship_config sc
        LEFT JOIN ship_config_stats ss
          ON ss.ship_config_id = sc.id
        WHERE sc.player_id = :pid
        ORDER BY sc.name
    ");
    $sth->execute([':pid' => $pid]);
    $configs = $sth->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="andromeda-panel">
    <div class="andromeda-panel-header">
        <h2>Pilot command hub</h2>
        <p>Andromeda — Equipment</p>
    </div>

    <div class="andromeda-panel-body">
        <?php if (!$pid): ?>
            <p>Impossible de charger vos configurations (vous n'êtes pas connecté).</p>

        <?php elseif (empty($configs)): ?>
            <p>Aucune configuration de vaisseau trouvée pour ce compte.<br>
               Lancez le jeu et sauvegardez une configuration pour générer les statistiques.</p>

        <?php else: ?>
            <div class="config-grid">
                <?php foreach ($configs as $c): ?>
                    <?php
                        $name   = $c['name']; // 'A' ou 'B'
                        $dmg    = (int)($c['damage_total'] ?? 0);
                        $shield = (int)($c['shield_total'] ?? 0);
                        $speed  = (int)($c['speed_total'] ?? 0);
                    ?>
                    <div class="config-card">
                        <div class="config-card-header">
                            <span class="config-badge">Config <?= htmlspecialchars($name) ?></span>
                        </div>

                        <div class="config-card-body">
                            <ul class="config-stats">
                                <li>
                                    <span class="label">Laser slots</span>
                                    <span class="value"><?= (int)$c['lasers_slots'] ?></span>
                                </li>
                                <li>
                                    <span class="label">Generator slots</span>
                                    <span class="value"><?= (int)$c['gen_slots'] ?></span>
                                </li>
                                <li>
                                    <span class="label">Extras slots</span>
                                    <span class="value"><?= (int)$c['extras_slots'] ?></span>
                                </li>
                            </ul>

                            <hr class="config-separator">

                            <ul class="config-stats">
                                <li>
                                    <span class="label">Total damage</span>
                                    <span class="value"><?= $dmg ?></span>
                                </li>
                                <li>
                                    <span class="label">Total shield</span>
                                    <span class="value"><?= $shield ?></span>
                                </li>
                                <li>
                                    <span class="label">Speed</span>
                                    <span class="value"><?= $speed ?></span>
                                </li>
                            </ul>
                        </div>

                        <div class="config-card-footer">
                            <small>
                                Ces valeurs sont calculées à partir de l’équipement
                                (vaisseau + drones) via <code>config_save.php</code>.
                            </small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .andromeda-panel {
        padding: 20px;
        color: #f5f7ff;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .andromeda-panel-header h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 600;
    }

    .andromeda-panel-header p {
        margin: 4px 0 16px;
        opacity: .7;
    }

    .config-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 16px;
    }

    .config-card {
        background: rgba(9, 15, 30, 0.9);
        border-radius: 12px;
        padding: 14px 16px;
        box-shadow: 0 0 0 1px rgba(255,255,255,0.03);
    }

    .config-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .config-badge {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 999px;
        background: rgba(0, 170, 255, 0.15);
        font-size: 13px;
    }

    .config-stats {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .config-stats li {
        display: flex;
        justify-content: space-between;
        padding: 2px 0;
        font-size: 14px;
    }

    .config-stats .label {
        opacity: .75;
    }

    .config-stats .value {
        font-weight: 600;
    }

    .config-separator {
        border: none;
        border-top: 1px solid rgba(255,255,255,0.05);
        margin: 8px 0 10px;
    }

    .config-card-footer {
        margin-top: 6px;
        opacity: .55;
        font-size: 11px;
    }
</style>
