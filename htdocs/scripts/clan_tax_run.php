<?php
/**
 * Daily Clan Tax Runner
 * ---------------------
 * À exécuter via le Planificateur de tâches Windows (ou cron) une fois par jour.
 * Exemple Windows (Action):
 *   Program:  C:\xampp\php\php.exe
 *   Args:     "C:\xampp\htdocs\scripts\clan_tax_run.php"
 *
 * Règle de taxe:
 *   - Si clan_tax_settings.active = 1, on applique rate_bps (ex: 300 = 3%)
 *   - On débite chaque membre du clan: tax = floor(credits * rate_bps / 10000)
 *   - On ne prélève que si tax > 0
 *   - On crédite la somme totale dans clan_wallet.balance
 *   - On enregistre un log 'tax_run' dans clan_log
 *   - On marque la date du run dans clan_tax_settings.last_run_date = 'YYYY-MM-DD'
 */

// ---------------------------------------------------------------------
// 1) Bootstrap de l'application : Récupère $db (PDO) et la config
// ---------------------------------------------------------------------
// ⚠️ ADAPTEZ CE CHEMIN selon votre projet !
require_once __DIR__ . '/../includes/config.php'; // doit définir $db (PDO ou wrapper compatible)

// Facultatif : définir le fuseau si besoin (pour le "today")
date_default_timezone_set('Europe/Zurich');

// Petit helper pour sortie CLI lisible
function out($msg) {
    if (php_sapi_name() === 'cli') {
        echo '['.date('Y-m-d H:i:s')."] $msg\n";
    } else {
        echo '<div>'.htmlspecialchars('['.date('Y-m-d H:i:s')."] $msg", ENT_QUOTES, 'UTF-8').'</div>';
    }
}

// ---------------------------------------------------------------------
// 2) Verrou global pour éviter deux exécutions simultanées
// ---------------------------------------------------------------------
try {
    $stmt = $db->prepare("SELECT GET_LOCK('clan_tax_daily_lock', 10)");
    $stmt->execute();
    $gotLock = (int)$stmt->fetchColumn() === 1;
    if (!$gotLock) {
        out("Another tax runner is already executing. Exiting.");
        exit;
    }
} catch (Throwable $e) {
    // Si GET_LOCK indisponible, on continue sans verrou (au pire).
    out("Warning: Could not acquire MySQL lock (continuing anyway). ".$e->getMessage());
}

// ---------------------------------------------------------------------
// 3) Charger les clans ayant une taxe active
//    et n'ayant pas encore été traités aujourd'hui
// ---------------------------------------------------------------------
$today = date('Y-m-d');

$sqlClans = "
    SELECT c.id AS clan_id, c.clan_name, c.clan_tag,
           t.active, t.rate_bps, t.last_run_date
    FROM clan_tax_settings t
    JOIN clan c ON c.id = t.clan_id
    WHERE t.active = 1
";
$clansStmt = $db->prepare($sqlClans);
$clansStmt->execute();
$clans = $clansStmt->fetchAll(PDO::FETCH_ASSOC);

if (!$clans) {
    out("No clan with active tax. Nothing to do.");
    // Libérer le verrou si pris
    try { $db->query("DO RELEASE_LOCK('clan_tax_daily_lock')"); } catch (Throwable $e) {}
    exit;
}

$outcomes = []; // pour un petit résumé final

foreach ($clans as $cl) {
    $cid       = (int)$cl['clan_id'];
    $rate_bps  = (int)$cl['rate_bps'];
    $last_date = $cl['last_run_date'] ? substr((string)$cl['last_run_date'], 0, 10) : null;

    // Déjà traité aujourd'hui ?
    if ($last_date === $today) {
        $outcomes[] = ["clan_id"=>$cid, "charged_count"=>0, "total_amount"=>0, "skipped"=>true];
        out("Clan #$cid already processed today. Skipping.");
        continue;
    }

    if ($rate_bps <= 0) {
        $outcomes[] = ["clan_id"=>$cid, "charged_count"=>0, "total_amount"=>0, "skipped"=>true];
        out("Clan #$cid has 0 bps (inactive or 0%). Skipping.");
        continue;
    }

    // -----------------------------------------------------------------
    // 4) Charger tous les membres du clan avec leur solde actuel
    // -----------------------------------------------------------------
    $memStmt = $db->prepare("SELECT id, credits FROM users WHERE clanid = :cid");
    $memStmt->execute([':cid'=>$cid]);
    $members = $memStmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$members) {
        // Aucun membre : marquer la date quand même pour éviter boucle vide
        $db->prepare("UPDATE clan_tax_settings SET last_run_date = :d WHERE clan_id = :c")
           ->execute([':d'=>$today, ':c'=>$cid]);
        $outcomes[] = ["clan_id"=>$cid, "charged_count"=>0, "total_amount"=>0, "skipped"=>false];
        out("Clan #$cid has no members. Marked last_run_date and continued.");
        continue;
    }

    // Calcul des taxes individuelles
    $taxRows = []; // [ ['user_id'=>int, 'tax'=>int], ... ]
    $total   = 0;
    $count   = 0;

    foreach ($members as $m) {
        $uid = (int)$m['id'];
        $cr  = (int)$m['credits'];
        if ($cr <= 0) continue;

        // tax = floor(credits * rate_bps / 10000)
        // ex: 3% => 300 bps, 100'000 * 300 / 10000 = 3000
        $tax = intdiv($cr * $rate_bps, 10000);

        if ($tax > 0) {
            $taxRows[] = ['user_id'=>$uid, 'tax'=>$tax];
            $total += $tax;
            $count++;
        }
    }

    if ($total <= 0 || $count === 0) {
        // Rien à prélever : on marque quand même la date pour éviter re-run inutile
        $db->prepare("UPDATE clan_tax_settings SET last_run_date = :d WHERE clan_id = :c")
           ->execute([':d'=>$today, ':c'=>$cid]);

        $outcomes[] = ["clan_id"=>$cid, "charged_count"=>0, "total_amount"=>0, "skipped"=>false];
        out("Clan #$cid computed total 0. Marked last_run_date.");
        continue;
    }

    // -----------------------------------------------------------------
    // 5) Transaction : débiter joueurs, créditer wallet, marquer la date, log
    // -----------------------------------------------------------------
    try {
        $db->beginTransaction();

        // Débiter chaque joueur (avec garde-fou credits>=tax)
        $updUser = $db->prepare("
            UPDATE users
            SET credits = credits - :t
            WHERE id = :uid AND credits >= :t
        ");
        $debited_total = 0;
        $debited_count = 0;

        foreach ($taxRows as $row) {
            $ok = $updUser->execute([':t'=>$row['tax'], ':uid'=>$row['user_id']]);
            if ($ok && $updUser->rowCount() === 1) {
                $debited_total += $row['tax'];
                $debited_count++;
            }
            // Si rowCount = 0, le joueur a peut-être eu un mouvement concurrent → ignoré pour ce run
        }

        // Créditer le wallet du clan du total effectivement prélevé
        if ($debited_total > 0) {
            $db->prepare("UPDATE clan_wallet SET balance = balance + :amt WHERE clan_id = :cid")
               ->execute([':amt'=>$debited_total, ':cid'=>$cid]);
        }

        // Marquer la date de run
        $db->prepare("UPDATE clan_tax_settings SET last_run_date = :d WHERE clan_id = :c")
           ->execute([':d'=>$today, ':c'=>$cid]);

        // Log
        // details: charged_count (nb membres réellement débités), total_amount (somme débitée), rate_bps (taux appliqué)
        try {
            $db->insert('clan_log', [
                'clan_id'       => $cid,
                'actor_user_id' => null, // système
                'action_type'   => 'tax_run',
                'details'       => json_encode([
                    'charged_count' => $debited_count,
                    'total_amount'  => $debited_total,
                    'rate_bps'      => $rate_bps
                ], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
            ]);
        } catch (Throwable $e) {
            // Si $db->insert n'existe pas, fallback PDO
            $ins = $db->prepare("
                INSERT INTO clan_log (clan_id, actor_user_id, action_type, details)
                VALUES (:c, NULL, 'tax_run', :d)
            ");
            $ins->execute([
                ':c'=>$cid,
                ':d'=>json_encode([
                    'charged_count' => $debited_count,
                    'total_amount'  => $debited_total,
                    'rate_bps'      => $rate_bps
                ], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
            ]);
        }

        $db->commit();

        $outcomes[] = ["clan_id"=>$cid, "charged_count"=>$debited_count, "total_amount"=>$debited_total, "skipped"=>false];
        out("Clan #$cid taxed: members={$debited_count}, total={$debited_total}, rate_bps={$rate_bps}");

    } catch (Throwable $e) {
        $db->rollBack();
        out("ERROR on clan #$cid: ".$e->getMessage());
        // On ne marque pas last_run_date en cas d'échec pour retenter au prochain run
        continue;
    }
}

// ---------------------------------------------------------------------
// 6) Résumé (facultatif) + libérer le verrou
// ---------------------------------------------------------------------
$totalClans   = count($outcomes);
$totalMembers = array_sum(array_column($outcomes, 'charged_count'));
$totalAmount  = array_sum(array_column($outcomes, 'total_amount'));
out("Done. Clans processed: {$totalClans}, members charged: {$totalMembers}, total taxed: {$totalAmount}");

try { $db->query("DO RELEASE_LOCK('clan_tax_daily_lock')"); } catch (Throwable $e) {}
