<?php 
// ====== Shop Designs (version réduite : 1 seul vaisseau) ======
// On garde la logique de ton ancien fichier : pas de lecture ship_design, on force 1 shipId existant.
// J'utilise shipId = 8 (Vengeance), déjà présent dans ton ancien designs.php.
$SHIP_ID = 8;
$SHIP_PRICE_CREDITS = 123000;

$SLOTS_LASERS      = 20;
$SLOTS_GENERATORS  = 16;
$SLOTS_EXTRAS      = 5;
$BASE_HP           = 256000;

if (isset($_GET['buy'])) {
    $buymessage = buy($_GET['buy'], $db, $SHIP_ID, $SHIP_PRICE_CREDITS, $BASE_HP, $SLOTS_LASERS, $SLOTS_GENERATORS, $SLOTS_EXTRAS);
}

// === Affichage simple (1 carte) ===

// Infos joueur
$sth = $db->prepare("SELECT credits, uridium, shipId FROM users WHERE id = :id LIMIT 1");
$sth->execute([':id' => $_SESSION['player_id']]);
$datauser = $sth->fetchAll();
$hasShip = ((int)$datauser[0]['shipId'] === (int)$SHIP_ID);
?>
<br/><br/>

<div class="box" style="margin-left:140px;margin-bottom:20px; max-width:720px;">
    <div class="title">Shop Designs (Single Ship)</div>
    <div id="shop">

        <?php if(isset($buymessage)) { ?>
            <div class="info-message" style="margin:10px 0; background:#222; border:1px solid #555; color:#9fe29f; padding:10px;">
                <?= htmlspecialchars($buymessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php } ?>

        <div class="stat" style="width:680px;">
            <div class="stat-left tooltip" style="min-width:200px;">
                <span>
                    <img class="callout" src="img/callout.gif" />
                    <strong>Vengeance (ShipId 8)</strong><br />
                    HP de base: <b><?= number_format($BASE_HP) ?></b><br/>
                    Slots: Lasers <b><?= $SLOTS_LASERS ?></b> • Générateurs <b><?= $SLOTS_GENERATORS ?></b> • Extras <b><?= $SLOTS_EXTRAS ?></b>
                </span>
                Vengeance
            </div>
            <div class="stat-right" style="width:440px; text-align:left; padding-left:10px; color:#ddd;">
                <div>Prix: <strong style="color:#7cc8ff;"><?= number_format($SHIP_PRICE_CREDITS) ?></strong> crédits</div>
                <?php if ($hasShip) { ?>
                    <div class="buy-stat" style="display:inline-block; background:#1b2b4b; color:#9fe29f; border:1px solid #355; padding:6px 10px; border-radius:6px; cursor:default;">Equipped</div>
                <?php } else { ?>
                    <a class="buy-stat tooltip" href="view.php?page=shop&tab=designs&buy=ship8" style="display:inline-block;">
                        Buy
                        <span>
                            <img class="callout" src="img/callout.gif" />
                            <strong>Vengeance</strong><br />
                            Credits: <font color='#00AAFF'><?= number_format($SHIP_PRICE_CREDITS) ?></font>
                        </span>
                    </a>
                <?php } ?>
            </div>
        </div>

        <div style="margin-top:10px; color:#aaa; font-size:13px;">
            Après l’achat, tes configs A/B sont redimensionnées et les slots créés.<br/>
            Va dans <em>User → Configurations</em> pour équiper lasers, boucliers et générateurs de vitesse.
        </div>
    </div>
</div>

<?php
// ================== LOGIQUE D’ACHAT ==================
function buy($item, $db, $SHIP_ID, $SHIP_PRICE_CREDITS, $BASE_HP, $SLOTS_LASERS, $SLOTS_GENERATORS, $SLOTS_EXTRAS)
{
    // Ne vendre que notre unique vaisseau
    if ($item !== 'ship8') {
        return 'Not allowed.';
    }

    // Charger solde + ship actuel
    $sth = $db->prepare("SELECT credits, shipId FROM users WHERE id = :id LIMIT 1");
    $sth->execute([':id' => $_SESSION['player_id']]);
    $u = $sth->fetch(PDO::FETCH_ASSOC);

    if ((int)$u['shipId'] === (int)$SHIP_ID) {
        return 'You already own this ship (equipped).';
    }
    if ((int)$u['credits'] < (int)$SHIP_PRICE_CREDITS) {
        return 'Not enough credits.';
    }

    try {
        // a) Débiter, équiper et fixer les HP de base
        $upd = $db->prepare("
            UPDATE users
            SET credits = credits - :price,
                shipId  = :sid,
                max_hp  = :hp
            WHERE id = :uid
        ");
        $upd->execute([
            ':price' => $SHIP_PRICE_CREDITS,
            ':sid'   => $SHIP_ID,
            ':hp'    => $BASE_HP,
            ':uid'   => $_SESSION['player_id']
        ]);

        // b) Garantir A/B dans ship_config
        $insCfg = $db->prepare("INSERT IGNORE INTO ship_config (player_id, name) VALUES (:pid,'A'),(:pid,'B')");
        $insCfg->execute([':pid' => $_SESSION['player_id']]);

        // c) Récupérer les configs de ce joueur
        $cfgStmt = $db->prepare("SELECT id, name FROM ship_config WHERE player_id = :pid");
        $cfgStmt->execute([':pid' => $_SESSION['player_id']]);
        $cfgs = $cfgStmt->fetchAll(PDO::FETCH_ASSOC);

        // d) Mettre à jour les compteurs de slots
        $updCounts = $db->prepare("
            UPDATE ship_config
            SET lasers_slots = :ls, gen_slots = :gs, extras_slots = :es
            WHERE id = :cid
        ");

        // e) Créer ship_slot manquants
        $insSlot = $db->prepare("
            INSERT IGNORE INTO ship_slot (ship_config_id, row_name, slot_index)
            VALUES (:cid, :row, :idx)
        ");

        foreach ($cfgs as $c) {
            $updCounts->execute([
                ':ls'  => $SLOTS_LASERS,
                ':gs'  => $SLOTS_GENERATORS,
                ':es'  => $SLOTS_EXTRAS,
                ':cid' => $c['id']
            ]);

            $plan = [
                ['lasers',     $SLOTS_LASERS],
                ['generators', $SLOTS_GENERATORS],
                ['extras',     $SLOTS_EXTRAS],
            ];
            foreach ($plan as [$row, $n]) {
                for ($i = 0; $i < $n; $i++) {
                    $insSlot->execute([':cid' => $c['id'], ':row' => $row, ':idx' => $i]);
                }
            }
        }

        return "Purchase success! Ship equipped and configurations prepared.";
    } catch (Exception $e) {
        return "An error occurred while purchasing the ship.";
    }
}
?>
