<?php
/**
 * config_load.php
 * Charge tout ce qu'il faut pour l'UI d'équipement :
 * - Inventaire joueur (avec icône)
 * - Configurations A et B + leurs slots (lasers / generators / extras) avec item_type + item_icon
 * - Drones + leurs 2 slots chacun (avec item_category + item_type + item_icon)
 */

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/helpers_drones.php'; // fonctions de sync des drones
header('Content-Type: application/json');

$pid = $_SESSION['player_id'] ?? null;
if (!$pid) {
  http_response_code(401);
  echo json_encode(['error' => 'unauthorized']);
  exit;
}

try {
  /* ---------- 1) INVENTAIRE (avec icône locale) ---------- */
  $inv = $db->prepare("
    SELECT
      i.id,
      i.name,
      i.category,
      i.type,
      i.selling_credits,
      /* Icône locale en fonction de la catégorie / type */
      CASE
        WHEN i.category='laser' THEN '/views/userTabs/icons/laser.png'
        WHEN i.category='generator' AND i.type=4 THEN '/views/userTabs/icons/shield.png'
        WHEN i.category='generator' AND i.type=3 THEN '/views/userTabs/icons/speed.png'
        ELSE NULL
      END AS icon,
      pi.qty
    FROM items i
    JOIN player_inventory pi ON pi.item_id = i.id
    WHERE pi.player_id = :pid
    ORDER BY i.category, i.name
  ");
  $inv->execute([':pid' => $pid]);
  $inventory = $inv->fetchAll(PDO::FETCH_ASSOC);

  /* ---------- 2) CONFIGS A/B ---------- */
  // Créer A/B si absentes
  $db->prepare("INSERT IGNORE INTO ship_config (player_id, name) VALUES (:p,'A'),(:p,'B')")
     ->execute([':p' => $pid]);

  // Charger configs
  $cfg = $db->prepare("SELECT * FROM ship_config WHERE player_id = :p ORDER BY name");
  $cfg->execute([':p' => $pid]);
  $configs = $cfg->fetchAll(PDO::FETCH_ASSOC);

  // Préparer statement pour récupérer les slots (avec type + icône locale)
  $slotsStmt = $db->prepare("
    SELECT
      s.id,
      s.row_name,
      s.slot_index,
      s.item_id,
      i.name       AS item_name,
      i.category   AS item_category,
      i.type       AS item_type,
      CASE
        WHEN i.category='laser' THEN '/views/userTabs/icons/laser.png'
        WHEN i.category='generator' AND i.type=4 THEN '/views/userTabs/icons/shield.png'
        WHEN i.category='generator' AND i.type=3 THEN '/views/userTabs/icons/speed.png'
        ELSE NULL
      END AS item_icon
    FROM ship_slot s
    LEFT JOIN items i ON i.id = s.item_id
    WHERE s.ship_config_id = :cid
    ORDER BY s.row_name, s.slot_index
  ");

  // Pour chaque config, s'assurer que les slots existent puis les lire
  foreach ($configs as &$c) {
    $plan = [
      ['lasers',     (int)$c['lasers_slots']],
      ['generators', (int)$c['gen_slots']],
      ['extras',     (int)$c['extras_slots']],
    ];
    foreach ($plan as [$row, $n]) {
      for ($i = 0; $i < $n; $i++) {
        $db->prepare("
          INSERT IGNORE INTO ship_slot (ship_config_id, row_name, slot_index)
          VALUES (:cid, :row, :idx)
        ")->execute([':cid'=>$c['id'], ':row'=>$row, ':idx'=>$i]);
      }
    }

    $slotsStmt->execute([':cid' => $c['id']]);
    $c['slots'] = $slotsStmt->fetchAll(PDO::FETCH_ASSOC);
  }
  unset($c);

  /* ---------- 3) DRONES (sync + lecture) ---------- */
  // Lire la ligne user pour connaître la chaîne users.drones + apis/zeus
  $u = $db->prepare("SELECT drones, apis_built, zeus_built FROM users WHERE id=:p LIMIT 1");
  $u->execute([':p'=>$pid]);
  $userRow = $u->fetch(PDO::FETCH_ASSOC);

  // Synchroniser le nombre de drones en table (drone/drone_slot) avec users.drones
  $desired = count_user_drones_row($userRow);
  sync_drones_tables($db, (int)$pid, $desired);

  // Charger les drones
  $dr = $db->prepare("SELECT id, name FROM drone WHERE player_id=:p ORDER BY id");
  $dr->execute([':p'=>$pid]);
  $drones = $dr->fetchAll(PDO::FETCH_ASSOC);

  // Charger les 2 slots de chaque drone (avec catégorie + type + icône locale)
  $drs = $db->prepare("
    SELECT
      ds.drone_id,
      ds.slot_index,
      ds.item_id,
      i.name      AS item_name,
      i.category  AS item_category,
      i.type      AS item_type,
      CASE
        WHEN i.category='laser' THEN '/views/userTabs/icons/laser.png'
        WHEN i.category='generator' AND i.type=4 THEN '/views/userTabs/icons/shield.png'
        WHEN i.category='generator' AND i.type=3 THEN '/views/userTabs/icons/speed.png'
        ELSE NULL
      END AS item_icon
    FROM drone_slot ds
    LEFT JOIN items i ON i.id = ds.item_id
    WHERE ds.drone_id = :d
    ORDER BY ds.slot_index
  ");
  foreach ($drones as &$d) {
    $drs->execute([':d'=>$d['id']]);
    $d['slots'] = $drs->fetchAll(PDO::FETCH_ASSOC);
  }
  unset($d);

  /* ---------- 4) RÉPONSE ---------- */
  echo json_encode([
    'player_id' => $pid,
    'inventory' => $inventory,
    'configs'   => $configs,
    'drones'    => $drones
  ]);

} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'load_failed', 'message' => $e->getMessage()]);
}
