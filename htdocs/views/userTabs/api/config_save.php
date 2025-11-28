<?php
/**
 * config_save.php
 *
 * - Sauvegarde l'équipement (configs A/B + drones) envoyé par l'UI.
 * - Met à jour les slots ship_slot / drone_slot et l'inventaire.
 * - Recalcule les stats par configuration dans ship_config_stats
 *   (ship + drones pour dégâts / boucliers ; vitesse = ship seulement).
 * - Met à jour :
 *      * users.damages / users.max_shield / users.speed pour la config active
 *      * player_config.damage1/shield1/speed1 (config A)
 *        et damage2/shield2/speed2 (config B),
 *    afin que l'émulateur garde son comportement (temporairement).
 */

require_once __DIR__ . '/bootstrap.php';
header('Content-Type: application/json');

$pid = $_SESSION['player_id'] ?? null;
if (!$pid) {
    http_response_code(401);
    echo json_encode(['error' => 'unauthorized']);
    exit;
}

/**
 * Convertit des quantités lasers/shields/speed en points (0–10 chacun, somme ≤ 15)
 * pour remplir player_config (damageX/shieldX/speedX).
 */
function computeConfigPoints(int $lasers, int $shields, int $speeds): array
{
    $wD = max(0, $lasers);
    $wS = max(0, $shields);
    $wV = max(0, $speeds);

    $total = $wD + $wS + $wV;
    if ($total <= 0) {
        return [0, 0, 0];
    }

    // Répartition brute sur 15 points
    $pD = 15.0 * $wD / $total;
    $pS = 15.0 * $wS / $total;
    $pV = 15.0 * $wV / $total;

    // Limite 10 par stat
    $pD = min($pD, 10.0);
    $pS = min($pS, 10.0);
    $pV = min($pV, 10.0);

    $sum = $pD + $pS + $pV;
    if ($sum > 15.0) {
        $k  = 15.0 / $sum;
        $pD *= $k;
        $pS *= $k;
        $pV *= $k;
    }

    // Arrondir proprement
    $dmg    = (int)round($pD);
    $shield = (int)round($pS);
    $speed  = (int)round($pV);

    // Petit correctif si on dépasse 15 après arrondi
    $sum = $dmg + $shield + $speed;
    if ($sum > 15) {
        $delta = $sum - 15;
        while ($delta > 0) {
            if ($dmg >= $shield && $dmg >= $speed && $dmg > 0) {
                $dmg--;
            } elseif ($shield >= $dmg && $shield >= $speed && $shield > 0) {
                $shield--;
            } elseif ($speed > 0) {
                $speed--;
            }
            $delta--;
        }
    }

    return [$dmg, $shield, $speed];
}

/* -----------------------------------------------------------
 * 0) S'assurer que la table de stats existe (en dehors d'une transaction)
 * ----------------------------------------------------------- */
$db->exec("
    CREATE TABLE IF NOT EXISTS ship_config_stats (
        ship_config_id INT PRIMARY KEY,
        config        CHAR(1) NOT NULL,
        lasers_slots  INT NOT NULL DEFAULT 0,
        gen_slots     INT NOT NULL DEFAULT 0,
        extras_slots  INT NOT NULL DEFAULT 0,
        damage_total  INT NOT NULL DEFAULT 0,
        shield_total  INT NOT NULL DEFAULT 0,
        speed_total   INT NOT NULL DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

/* -----------------------------------------------------------
 * 1) Récupération du payload JSON
 * ----------------------------------------------------------- */
$raw = file_get_contents('php://input');
if (!$raw && isset($_POST['payload'])) {
    $raw = $_POST['payload'];
}
$payload = json_decode($raw, true);
if (!is_array($payload)) {
    $payload = [];
}

$hasConfigs = array_key_exists('configs', $payload);
$hasDrones  = array_key_exists('drones',  $payload);

try {
    $db->beginTransaction();

    /* -------------------------------------------------------
     * 2) Map des items : [id] => ['cat' => ..., 'type' => ...]
     * ------------------------------------------------------- */
    $itemsMap = [];
    $itQ = $db->query("SELECT id, category, type FROM items");
    foreach ($itQ as $row) {
        $itemsMap[(int)$row['id']] = [
            'cat'  => $row['category'],
            'type' => (int)$row['type']
        ];
    }

    /* -------------------------------------------------------
     * 3) Sauvegarde des configs A / B (ship_slot + inventaire)
     * ------------------------------------------------------- */
    if ($hasConfigs && is_array($payload['configs'])) {

        // Charger les configs du joueur
        $cfgStmt = $db->prepare("
            SELECT id, name, lasers_slots, gen_slots, extras_slots
            FROM ship_config
            WHERE player_id = :p
        ");
        $cfgStmt->execute([':p' => $pid]);
        $configs   = $cfgStmt->fetchAll(PDO::FETCH_ASSOC);

        $cfgByName = [];
        foreach ($configs as $c) {
            $cfgByName[$c['name']] = $c;
        }

        // On ne traite que A/B réellement envoyées dans le payload
        $namesWanted = [];
        foreach ($payload['configs'] as $conf) {
            if (!is_array($conf)) continue;
            $n = (($conf['name'] ?? 'A') === 'B') ? 'B' : 'A';
            if (isset($cfgByName[$n])) {
                $namesWanted[$n] = true;
            }
        }

        $idsWanted = [];
        foreach ($namesWanted as $n => $_) {
            $idsWanted[] = (int)$cfgByName[$n]['id'];
        }

        if (!empty($idsWanted)) {
            $in = implode(',', array_map('intval', $idsWanted));

            // 3.1 Rendre l'équipement actuel au hangar
            $cur = $db->query("
                SELECT item_id
                FROM ship_slot
                WHERE ship_config_id IN ($in) AND item_id IS NOT NULL
            ")->fetchAll(PDO::FETCH_COLUMN);

            foreach ($cur as $iid) {
                $db->prepare("
                    INSERT INTO player_inventory (player_id, item_id, qty)
                    VALUES (:p, :i, 1)
                    ON DUPLICATE KEY UPDATE qty = qty + 1
                ")->execute([':p' => $pid, ':i' => $iid]);
            }

            // 3.2 Vider les slots ship
            $db->exec("UPDATE ship_slot SET item_id = NULL WHERE ship_config_id IN ($in)");

            // 3.3 Réappliquer l'équipement selon le payload
            foreach ($payload['configs'] as $conf) {
                if (!is_array($conf)) continue;

                $name = ($conf['name'] ?? 'A') === 'B' ? 'B' : 'A';
                if (!isset($cfgByName[$name])) continue;

                $c = $cfgByName[$name];

                $rowsSpec = [
                    'lasers'     => (int)$c['lasers_slots'],
                    'generators' => (int)$c['gen_slots'],
                    'extras'     => (int)$c['extras_slots'],
                ];

                $slots = $conf['slots'] ?? [];
                if (!is_array($slots)) {
                    $slots = [];
                }

                foreach ($rowsSpec as $row => $max) {
                    $list = $slots[$row] ?? [];
                    if (!is_array($list)) {
                        $list = [];
                    }
                    $n = min(count($list), $max);

                    for ($i = 0; $i < $n; $i++) {
                        $iid = (int)($list[$i] ?? 0);
                        if ($iid <= 0) continue;

                        $info = $itemsMap[$iid] ?? null;
                        if (!$info) continue;

                        $ok = false;
                        if ($row === 'lasers'     && $info['cat'] === 'laser')     $ok = true;
                        if ($row === 'generators' && $info['cat'] === 'generator') $ok = true;
                        if ($row === 'extras'     && $info['cat'] === 'extra')     $ok = true;
                        if (!$ok) continue;

                        // Vérifier la dispo en inventaire
                        $st = $db->prepare("
                            SELECT qty FROM player_inventory
                            WHERE player_id = :p AND item_id = :i
                        ");
                        $st->execute([':p' => $pid, ':i' => $iid]);
                        if ((int)$st->fetchColumn() <= 0) continue;

                        // Décrémenter l'inventaire
                        $u = $db->prepare("
                            UPDATE player_inventory
                            SET qty = qty - 1
                            WHERE player_id = :p AND item_id = :i AND qty > 0
                        ");
                        $u->execute([':p' => $pid, ':i' => $iid]);
                        if ($u->rowCount() === 0) {
                            continue;
                        }

                        // Poser l'item dans le slot
                        $db->prepare("
                            UPDATE ship_slot
                            SET item_id = :i
                            WHERE ship_config_id = :c AND row_name = :r AND slot_index = :s
                        ")->execute([
                            ':i' => $iid,
                            ':c' => $c['id'],
                            ':r' => $row,
                            ':s' => $i
                        ]);
                    }
                }
            }
        }
    }

    /* -------------------------------------------------------
     * 4) Sauvegarde des drones (drone_slot + inventaire)
     * ------------------------------------------------------- */
    if ($hasDrones && is_array($payload['drones'])) {

        // Drones du joueur, triés
        $dr = $db->prepare("SELECT id FROM drone WHERE player_id = :p ORDER BY id");
        $dr->execute([':p' => $pid]);
        $drIds = $dr->fetchAll(PDO::FETCH_COLUMN);

        foreach ($payload['drones'] as $idx => $d) {
            if (!is_array($d)) continue;

            $did = $drIds[$idx] ?? null;
            if (!$did) continue;

            // Rendre l'équipement actuel au hangar
            $curD = $db->prepare("
                SELECT item_id
                FROM drone_slot
                WHERE drone_id = :d AND item_id IS NOT NULL
            ");
            $curD->execute([':d' => $did]);

            foreach ($curD->fetchAll(PDO::FETCH_COLUMN) as $iid) {
                $db->prepare("
                    INSERT INTO player_inventory (player_id, item_id, qty)
                    VALUES (:p, :i, 1)
                    ON DUPLICATE KEY UPDATE qty = qty + 1
                ")->execute([':p' => $pid, ':i' => $iid]);
            }

            // Vider les slots du drone
            $db->prepare("
                UPDATE drone_slot SET item_id = NULL WHERE drone_id = :d
            ")->execute([':d' => $did]);

            // Appliquer les nouveaux slots (0 et 1)
            $slots = $d['slots'] ?? [];
            for ($s = 0; $s < 2; $s++) {
                $iid = isset($slots[$s]) ? (int)$slots[$s] : 0;
                if ($iid <= 0) continue;

                $info = $itemsMap[$iid] ?? null;
                if (!$info) continue;

                // Drones acceptent lasers + shields
                $ok = ($info['cat'] === 'laser') ||
                      ($info['cat'] === 'generator' && (int)$info['type'] === 4);
                if (!$ok) continue;

                // Vérifier inventaire
                $st = $db->prepare("
                    SELECT qty FROM player_inventory
                    WHERE player_id = :p AND item_id = :i
                ");
                $st->execute([':p' => $pid, ':i' => $iid]);
                if ((int)$st->fetchColumn() <= 0) continue;

                // Décrémenter inventaire
                $db->prepare("
                    UPDATE player_inventory
                    SET qty = qty - 1
                    WHERE player_id = :p AND item_id = :i AND qty > 0
                ")->execute([':p' => $pid, ':i' => $iid]);

                // Poser dans le slot
                $db->prepare("
                    UPDATE drone_slot
                    SET item_id = :i
                    WHERE drone_id = :d AND slot_index = :s
                ")->execute([
                    ':i' => $iid,
                    ':d' => $did,
                    ':s' => $s
                ]);
            }
        }
    }

    /* -------------------------------------------------------
     * 5) Recalcul des stats par config (ship + drones)
     * ------------------------------------------------------- */

    // Constantes simplifiées pour le calcul d'affichage
    $DMG_PER_LASER = 200;     // dégâts d'un LF3 (exemple)
    $SHD_PER_GEN   = 10000;   // bouclier d'un générateur shield
    $SPD_PER_GEN   = 10;      // +10 de vitesse par gen speed
    $BASE_SPEED    = 380;     // base (sans générateurs)

    // 5.1 Configs du joueur
    $cfg = $db->prepare("
        SELECT id, name, lasers_slots, gen_slots, extras_slots
        FROM ship_config
        WHERE player_id = :p
        ORDER BY name
    ");
    $cfg->execute([':p' => $pid]);
    $configs = $cfg->fetchAll(PDO::FETCH_ASSOC);

    // 5.2 Compteurs d'équipement sur le vaisseau
    $countShip = $db->prepare("
        SELECT i.category, i.type, COUNT(*) AS n
        FROM ship_slot s
        JOIN items i ON i.id = s.item_id
        WHERE s.item_id IS NOT NULL AND s.ship_config_id = :cid
        GROUP BY i.category, i.type
    ");

    // 5.3 Compteurs drones (communs aux deux configs)
    $dr = $db->prepare("SELECT id FROM drone WHERE player_id = :p");
    $dr->execute([':p' => $pid]);
    $drIds = $dr->fetchAll(PDO::FETCH_COLUMN);

    $droneLasers  = 0;
    $droneShields = 0;

    if (!empty($drIds)) {
        $in = implode(',', array_map('intval', $drIds));
        $qd = $db->query("
            SELECT i.category, i.type, COUNT(*) AS n
            FROM drone_slot ds
            JOIN items i ON i.id = ds.item_id
            WHERE ds.item_id IS NOT NULL AND ds.drone_id IN ($in)
            GROUP BY i.category, i.type
        ");
        foreach ($qd as $r) {
            $cat = $r['category'];
            $typ = (int)$r['type'];
            $n   = (int)$r['n'];

            if ($cat === 'laser') {
                $droneLasers += $n;
            } elseif ($cat === 'generator' && $typ === 4) {
                $droneShields += $n;
            }
        }
    }

    // 5.4 Upsert dans ship_config_stats + mémorisation des compteurs pour player_config
    $upStats = $db->prepare("
        INSERT INTO ship_config_stats (
            ship_config_id, config,
            lasers_slots, gen_slots, extras_slots,
            damage_total, shield_total, speed_total
        ) VALUES (
            :cid, :cfg,
            :ls, :gs, :es,
            :dmg, :shd, :spd
        )
        ON DUPLICATE KEY UPDATE
            lasers_slots = VALUES(lasers_slots),
            gen_slots    = VALUES(gen_slots),
            extras_slots = VALUES(extras_slots),
            damage_total = VALUES(damage_total),
            shield_total = VALUES(shield_total),
            speed_total  = VALUES(speed_total)
    ");

    $statsByName       = [];
    $equipCountsByName = []; // pour alimenter player_config

    foreach ($configs as $c) {
        $cid  = (int)$c['id'];
        $name = $c['name']; // 'A' ou 'B'

        $shipLasers  = 0;
        $shipShields = 0;
        $shipSpeeds  = 0;

        // Compter les items du vaisseau pour cette config
        $countShip->execute([':cid' => $cid]);
        foreach ($countShip as $r) {
            $cat = $r['category'];
            $typ = (int)$r['type'];
            $n   = (int)$r['n'];

            if ($cat === 'laser') {
                $shipLasers += $n;
            } elseif ($cat === 'generator' && $typ === 4) {
                $shipShields += $n;
            } elseif ($cat === 'generator' && $typ === 3) {
                $shipSpeeds += $n;
            }
        }

        // Ajouter la contribution des drones
        $totalLasers  = $shipLasers  + $droneLasers;
        $totalShields = $shipShields + $droneShields;

        $damage = $DMG_PER_LASER * $totalLasers;
        $shield = $SHD_PER_GEN   * $totalShields;
        $speed  = $BASE_SPEED    + $SPD_PER_GEN * $shipSpeeds; // vitesse: seulement ship

        $upStats->execute([
            ':cid' => $cid,
            ':cfg' => $name,
            ':ls'  => (int)$c['lasers_slots'],
            ':gs'  => (int)$c['gen_slots'],
            ':es'  => (int)$c['extras_slots'],
            ':dmg' => $damage,
            ':shd' => $shield,
            ':spd' => $speed
        ]);

        $statsByName[$name] = [
            'damage' => $damage,
            'shield' => $shield,
            'speed'  => $speed
        ];

        $equipCountsByName[$name] = [
            'lasers'  => $totalLasers,
            'shields' => $totalShields,
            'speeds'  => $shipSpeeds
        ];
    }

    /* -------------------------------------------------------
     * 6) Mettre à jour player_config (pour l'émulateur)
     * ------------------------------------------------------- */

    // Valeurs par défaut si une des configs n'existe pas
    $A = $equipCountsByName['A'] ?? ['lasers'=>0,'shields'=>0,'speeds'=>0];
    $B = $equipCountsByName['B'] ?? ['lasers'=>0,'shields'=>0,'speeds'=>0];

    [$d1,$s1,$v1] = computeConfigPoints($A['lasers'], $A['shields'], $A['speeds']);
    [$d2,$s2,$v2] = computeConfigPoints($B['lasers'], $B['shields'], $B['speeds']);

    // Vérifier si une ligne existe déjà pour ce joueur
    $pcSel = $db->prepare("SELECT COUNT(*) FROM player_config WHERE player_id = :pid");
    $pcSel->execute([':pid' => $pid]);
    $exists = ((int)$pcSel->fetchColumn() > 0);

    if ($exists) {
        // Mise à jour de la ligne existante
        $pcUpd = $db->prepare("
            UPDATE player_config
            SET damage1 = :d1,
                shield1 = :s1,
                speed1  = :v1,
                damage2 = :d2,
                shield2 = :s2,
                speed2  = :v2
            WHERE player_id = :pid
        ");
        $pcUpd->execute([
            ':pid' => $pid,
            ':d1'  => $d1,
            ':s1'  => $s1,
            ':v1'  => $v1,
            ':d2'  => $d2,
            ':s2'  => $s2,
            ':v2'  => $v2,
        ]);
    } else {
        // Création de la ligne si elle n'existe pas
        $pcIns = $db->prepare("
            INSERT INTO player_config
                (player_id, damage1, shield1, speed1, damage2, shield2, speed2)
            VALUES
                (:pid, :d1, :s1, :v1, :d2, :s2, :v2)
        ");
        $pcIns->execute([
            ':pid' => $pid,
            ':d1'  => $d1,
            ':s1'  => $s1,
            ':v1'  => $v1,
            ':d2'  => $d2,
            ':s2'  => $s2,
            ':v2'  => $v2,
        ]);
    }

    /* -------------------------------------------------------
     * 7) Mettre à jour users.* pour la config active (affichage)
     * ------------------------------------------------------- */
    $u = $db->prepare("SELECT active_config FROM users WHERE id = :u LIMIT 1");
    $u->execute([':u' => $pid]);
    $ac = (int)$u->fetchColumn();
    $activeName = ($ac === 2) ? 'B' : 'A';

    if (isset($statsByName[$activeName])) {
        $s = $statsByName[$activeName];

        $upd = $db->prepare("
            UPDATE users
            SET damages    = :dmg,
                max_shield = :shd,
                speed      = :spd
            WHERE id = :u
        ");
        $upd->execute([
            ':dmg' => $s['damage'],
            ':shd' => $s['shield'],
            ':spd' => $s['speed'],
            ':u'   => $pid
        ]);
    }

    $db->commit();
    echo json_encode(['ok' => true]);

} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    http_response_code(500);
    echo json_encode([
        'error'   => 'save_failed',
        'message' => $e->getMessage()
    ]);
}
