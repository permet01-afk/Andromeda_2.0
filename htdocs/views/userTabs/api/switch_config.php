<?php
/**
 * switch_config.php
 * Body JSON: { "config": 1 | 2 }  // ou "A" | "B"
 * - Met à jour users.active_config (1⇔A, 2⇔B)
 * - Charge les stats de la config ciblée depuis ship_config_stats
 *   (ou les recalcule à la volée si manquantes)
 * - Recopie ces valeurs dans users.{damages,max_shield,speed}
 */

require_once __DIR__ . '/bootstrap.php';
header('Content-Type: application/json');

$pid = $_SESSION['player_id'] ?? null;
if (!$pid) { http_response_code(401); echo json_encode(['error'=>'unauthorized']); exit; }

$raw = file_get_contents('php://input');
$payload = json_decode($raw ?: '[]', true);

// Accepte "A"/"B" ou 1/2
$req = $payload['config'] ?? 1;
if (is_string($req)) { $req = strtoupper(trim($req)) === 'B' ? 2 : 1; }
$targetInt  = ((int)$req === 2) ? 2 : 1;       // 1 par défaut
$targetName = ($targetInt === 2) ? 'B' : 'A';  // pour ship_config.name

try {
  $db->beginTransaction();

  // S'assurer que la config existe
  $cfg = $db->prepare("SELECT id FROM ship_config WHERE player_id=:p AND name=:n LIMIT 1");
  $cfg->execute([':p'=>$pid, ':n'=>$targetName]);
  $row = $cfg->fetch(PDO::FETCH_ASSOC);
  if (!$row) {
    $ins = $db->prepare("INSERT INTO ship_config (player_id, name) VALUES (:p,:n)");
    $ins->execute([':p'=>$pid, ':n'=>$targetName]);
    $cid = (int)$db->lastInsertId();
  } else {
    $cid = (int)$row['id'];
  }

  // Essayer de lire les stats pré-calculées
  $st = $db->prepare("SELECT damages, max_shield, speed FROM ship_config_stats WHERE ship_config_id=:cid");
  $st->execute([':cid'=>$cid]);
  $stats = $st->fetch(PDO::FETCH_ASSOC);

  if (!$stats) {
    // Recalcul à la volée (mêmes constantes que le save)
    $DMG_PER_LASER = 200;
    $SHD_PER_GEN   = 10000; // generator type=4
    $SPD_PER_GEN   = 10;    // generator type=3
    $BASE_SPEED    = 380;

    // Équipement posé sur le VAISSEAU pour CETTE config
    $q = $db->prepare("
      SELECT i.category, i.type, COUNT(*) AS n
      FROM ship_slot s
      JOIN items i ON i.id = s.item_id
      WHERE s.item_id IS NOT NULL AND s.ship_config_id = :cid
      GROUP BY i.category, i.type
    ");
    $q->execute([':cid'=>$cid]);

    $shipLasers=0; $shipShields=0; $shipSpeeds=0;
    foreach ($q as $r) {
      if ($r['category'] === 'laser') $shipLasers += (int)$r['n'];
      elseif ($r['category'] === 'generator' && (int)$r['type'] === 4) $shipShields += (int)$r['n'];
      elseif ($r['category'] === 'generator' && (int)$r['type'] === 3) $shipSpeeds  += (int)$r['n'];
    }

    // Drones (communs aux configs)
    $dr = $db->prepare("SELECT id FROM drone WHERE player_id=:p");
    $dr->execute([':p'=>$pid]);
    $drIds = $dr->fetchAll(PDO::FETCH_COLUMN);

    $droneLasers=0; $droneShields=0;
    if (!empty($drIds)) {
      $in = implode(',', array_map('intval', $drIds));
      $dq = $db->query("
        SELECT i.category, i.type, COUNT(*) AS n
        FROM drone_slot ds
        JOIN items i ON i.id = ds.item_id
        WHERE ds.item_id IS NOT NULL AND ds.drone_id IN ($in)
        GROUP BY i.category, i.type
      ");
      foreach ($dq as $r) {
        if ($r['category'] === 'laser') $droneLasers += (int)$r['n'];
        elseif ($r['category'] === 'generator' && (int)$r['type'] === 4) $droneShields += (int)$r['n'];
      }
    }

    $cfgDamages   = $DMG_PER_LASER * ($shipLasers + $droneLasers);
    $cfgMaxShield = $SHD_PER_GEN   * ($shipShields + $droneShields);
    $cfgSpeed     = $BASE_SPEED    + $SPD_PER_GEN * $shipSpeeds;

    // Sauvegarder pour les prochains switch
    $db->prepare("
      INSERT INTO ship_config_stats (ship_config_id, damages, max_shield, speed)
      VALUES (:cid,:dmg,:shd,:spd)
      ON DUPLICATE KEY UPDATE damages=VALUES(damages), max_shield=VALUES(max_shield), speed=VALUES(speed)
    ")->execute([':cid'=>$cid, ':dmg'=>$cfgDamages, ':shd'=>$cfgMaxShield, ':spd'=>$cfgSpeed]);

    $stats = ['damages'=>$cfgDamages, 'max_shield'=>$cfgMaxShield, 'speed'=>$cfgSpeed];
  }

  // Appliquer: active_config + recopie des stats dans users
  $db->prepare("
    UPDATE users
    SET active_config = :ac, damages = :dmg, max_shield = :shd, speed = :spd
    WHERE id = :u
  ")->execute([
    ':ac'=>$targetInt,
    ':dmg'=>$stats['damages'],
    ':shd'=>$stats['max_shield'],
    ':spd'=>$stats['speed'],
    ':u'=>$pid
  ]);

  $db->commit();
  echo json_encode(['ok'=>true, 'active_config'=>$targetInt, 'stats'=>$stats]);

} catch (Exception $e) {
  if ($db->inTransaction()) $db->rollBack();
  http_response_code(500);
  echo json_encode(['error'=>'switch_failed','message'=>$e->getMessage()]);
}
