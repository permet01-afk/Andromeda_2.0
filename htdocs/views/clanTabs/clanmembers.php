<?php
// ================== Chargement clan du joueur ==================
$sth = $db->prepare("SELECT clanid FROM users WHERE id = :id LIMIT 1");
$sth->execute([':id' => $_SESSION['player_id']]);
$me = $sth->fetch();
if (!$me || (int)$me['clanid'] === 0) {
    header("Location: view.php?page=clan&tab=joinclan"); exit();
}
$clan_id = (int)$me['clanid'];

// ================== Infos clan / marshal ==================
$sth = $db->prepare("SELECT id, admin_id FROM clan WHERE id = :id LIMIT 1");
$sth->execute([':id'=>$clan_id]);
$clanRow = $sth->fetch();
if (!$clanRow) { header("Location: view.php?page=clan&tab=joinclan"); exit(); }
$marshal_id = (int)$clanRow['admin_id'];
$is_marshal = ($marshal_id === (int)$_SESSION['player_id']);

// ================== Helpers log & rôles ==================
function clan_log_local($db, int $clan_id, ?int $actor_id, string $type, array $details = []) {
    try {
        $db->insert('clan_log', [
            'clan_id'       => $clan_id,
            'actor_user_id' => $actor_id,
            'action_type'   => $type,
            'details'       => json_encode($details, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
        ]);
    } catch (Throwable $e) {}
}

function clan_get_role($db, int $clan_id, int $user_id): array {
    $q = $db->prepare("
        SELECT
            can_kick, can_edit_desc,
            can_review_requests, can_manage_roles,
            can_set_tax_rate, can_toggle_tax_active, can_spend,
            can_start_alliance, can_cancel_alliance, can_declare_war, can_request_war_cancel
        FROM clan_roles
        WHERE clan_id = ? AND user_id = ?
        LIMIT 1
    ");
    $q->execute([$clan_id, $user_id]);
    $r = $q->fetch();
    return $r ?: [];
}
function has_perm(array $role, string $key, bool $is_marshal): bool {
    if ($is_marshal) return true;
    return !empty($role[$key]) && (int)$role[$key] === 1;
}

// Rôle de l'utilisateur courant
$my_role = clan_get_role($db, $clan_id, (int)$_SESSION['player_id']);
$can_kick   = has_perm($my_role, 'can_kick', $is_marshal);
$can_review = has_perm($my_role, 'can_review_requests', $is_marshal);

// ================== Actions protégées ==================
if (!empty($_GET['kick']))   handleKickClan($db, $clan_id, $marshal_id, $can_kick);
if (!empty($_GET['accept'])) handleAcceptRequest($db, $clan_id, $can_review);
if (!empty($_GET['refuse'])) handleRefuseRequest($db, $clan_id, $can_review);

// ================== Membres & demandes ==================
$sth = $db->prepare("SELECT username, id, grade FROM users WHERE clanid = :clanid ORDER BY username ASC");
$sth->execute([':clanid' => $clan_id]);
$datamembers = $sth->fetchAll();

$datarequest = [];
if ($can_review) {
    $sth = $db->prepare("SELECT id, player_id, clan_id, message FROM clan_request WHERE clan_id=:clanid ORDER BY id DESC");
    $sth->execute([':clanid' => $clan_id]);
    $datarequest = $sth->fetchAll();
}
?>

<div class="box">
    <div class="title">Clan members</div>
    <div id="clan-members">
        <?php displayClanMember($datamembers, $can_kick, $marshal_id); ?>
    </div>
</div>

<?php if ($can_review) { ?>
    <div class="box">
        <div class="title">Membership Requests</div>
        <div id="clan-members-requests">
            <?php displayClanRequest($datarequest, $db, $clan_id); ?>
        </div>
    </div>
<?php } ?>

<?php
// ================== UI: liste membres ==================
function displayClanMember($clanmembers, bool $can_kick, int $marshal_id)
{
    foreach ($clanmembers as $member) {
        $uid = (int)$member['id']; ?>
        <div class="stat">
            <div class="stat-left">
                <img src="img/ranks/<?= (int)$member['grade'] ?>.png" alt="">
            </div>
            <div class="stat-right">
                <?= htmlspecialchars($member['username'], ENT_QUOTES, 'UTF-8') ?>
                <?php
                // Bouton Kick seulement si on a le droit, pas soi-même, pas le marshal
                if ($can_kick && $uid !== (int)$_SESSION['player_id'] && $uid !== $marshal_id) { ?>
                    <a class="leftbutton" href="view.php?page=clan&tab=clanmembers&kick=<?= $uid ?>">
                        Kick
                    </a>
                <?php } ?>
            </div>
        </div>
    <?php }
}

// ================== Action: Kick ==================
function handleKickClan($db, int $clan_id, int $marshal_id, bool $can_kick): void
{
    if (!$can_kick) { header("Location: view.php?page=clan&tab=clanmembers"); exit(); }

    $id = (int)($_GET['kick'] ?? 0);
    if ($id <= 0) { header("Location: view.php?page=clan&tab=clanmembers"); exit(); }

    // Interdictions: soi-même, marshal
    if ($id === (int)$_SESSION['player_id'] || $id === $marshal_id) {
        header("Location: view.php?page=clan&tab=clanmembers"); exit();
    }

    // Vérifier qu'il est bien dans CE clan
    $chk = $db->prepare("SELECT COUNT(*) FROM users WHERE id=:id AND clanid=:cid");
    $chk->execute([':id'=>$id, ':cid'=>$clan_id]);
    if ((int)$chk->fetchColumn() === 0) {
        header("Location: view.php?page=clan&tab=clanmembers"); exit();
    }

    // Kick
    $sth = $db->prepare("UPDATE users SET clanid=0 WHERE id=:id AND clanid=:cid");
    $sth->execute([':id' => $id, ':cid'=>$clan_id]);

    // Log
    clan_log_local($db, $clan_id, (int)$_SESSION['player_id'], 'member_kick', [
        'kicked_user_id' => $id
    ]);

    header("Location: view.php?page=clan&tab=clanmembers"); exit();
}

// ================== UI: demandes d'adhésion ==================
function displayClanRequest($datarequest, $db, int $clan_id)
{
    foreach ($datarequest as $request) {
        $sth = $db->prepare("SELECT username, grade, id FROM users WHERE id = :id LIMIT 1");
        $sth->execute([':id' => (int)$request['player_id']]);
        $datamember = $sth->fetch();
        if (!$datamember) continue; ?>
        <div class="stat">
            <div class="stat-left">
                <img src="img/ranks/<?= (int)$datamember['grade'] ?>.png" alt="">
            </div>
            <div class="stat-right">
                <?= htmlspecialchars($datamember['username'], ENT_QUOTES, 'UTF-8') ?>
                <a class="leftbutton" href="view.php?page=clan&tab=clanmembers&refuse=<?= (int)$request['id'] ?>">
                    Refuse
                </a>
                <a class="leftbutton" href="view.php?page=clan&tab=clanmembers&accept=<?= (int)$request['id'] ?>">
                    Accept
                </a>
            </div>
        </div>
        <div class="stat" style="margin-top:-4px;">
            <div class="stat-left">Message</div>
            <div class="stat-rightbis" style="padding-bottom:5px;">
                <?= htmlspecialchars($request['message'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        </div>
    <?php }
}

// ================== Action: Accept ==================
function handleAcceptRequest($db, int $clanid, bool $can_review): void
{
    if (!$can_review) { header("Location: view.php?page=clan&tab=clanmembers"); exit(); }

    $id = (int)($_GET['accept'] ?? 0);
    if ($id <= 0) { header("Location: view.php?page=clan&tab=clanmembers"); exit(); }

    $sth = $db->prepare("SELECT id, player_id, clan_id FROM clan_request WHERE id = :id LIMIT 1");
    $sth->execute([':id' => $id]);
    $request = $sth->fetch();
    if (!$request || (int)$request['clan_id'] !== (int)$clanid) {
        header("Location: view.php?page=clan&tab=clanmembers"); exit();
    }

    $playerId = (int)$request['player_id'];

    // Assigner le clan
    $db->prepare("UPDATE users SET clanid=:clan_id WHERE id=:id")
       ->execute([':id' => $playerId, ':clan_id' => $clanid]);

    // Supprimer toutes ses demandes
    $db->prepare("DELETE FROM clan_request WHERE player_id=:player_id")
       ->execute([':player_id' => $playerId]);

    clan_log_local($db, $clanid, (int)$_SESSION['player_id'], 'member_join', [
        'joined_user_id' => $playerId
    ]);

    header("Location: view.php?page=clan&tab=clanmembers"); exit();
}

// ================== Action: Refuse ==================
function handleRefuseRequest($db, int $clanid, bool $can_review): void
{
    if (!$can_review) { header("Location: view.php?page=clan&tab=clanmembers"); exit(); }

    $id = (int)($_GET['refuse'] ?? 0);
    if ($id <= 0) { header("Location: view.php?page=clan&tab=clanmembers"); exit(); }

    $db->prepare("DELETE FROM clan_request WHERE id=:id AND clan_id=:clan_id")
       ->execute([':id' => $id, ':clan_id' => $clanid]);

    clan_log_local($db, $clanid, (int)$_SESSION['player_id'], 'invite_declined', [
        'request_id' => $id
    ]);

    header("Location: view.php?page=clan&tab=clanmembers"); exit();
}
?>

