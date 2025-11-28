<?php
// Compte le nombre de drones à partir de users.drones (ton encodage existant)
function count_user_drones_row(array $user): int {
    $count = substr_count($user['drones'] ?? '', '-') - 2;
    if (!empty($user['apis_built'])) $count--;
    if (!empty($user['zeus_built'])) $count--;
    return max(0, (int)$count);
}

// S'assure que la table drone/drone_slot a bien "desired" drones (2 slots chacun)
function sync_drones_tables(PDO $db, int $playerId, int $desired): void {
    $cur = $db->prepare("SELECT id FROM drone WHERE player_id=:p ORDER BY id");
    $cur->execute([':p'=>$playerId]);
    $ids = $cur->fetchAll(PDO::FETCH_COLUMN);
    $have = count($ids);

    if ($have < $desired) {
        for ($i = $have + 1; $i <= $desired; $i++) {
            $name = "Arès $i";
            $db->prepare("INSERT INTO drone (player_id, name) VALUES (:p, :n)")
               ->execute([':p'=>$playerId, ':n'=>$name]);
            $did = (int)$db->lastInsertId();
            $db->prepare("INSERT INTO drone_slot (drone_id, slot_index) VALUES (:d,0),(:d,1)")
               ->execute([':d'=>$did]);
        }
    }
    // On ne supprime pas si have > desired (évite de perdre de l'équipement).
}
