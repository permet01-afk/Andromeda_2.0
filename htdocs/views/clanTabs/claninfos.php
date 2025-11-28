<?php
// ---------------------------------------------------------------
// Chargement des données de base
// ---------------------------------------------------------------
$sth = $db->prepare("SELECT clanid FROM users WHERE id = :id LIMIT 1");
$sth->execute([':id' => $_SESSION['player_id']]);
$datauser = $sth->fetchAll();
if (!$datauser || (int)$datauser[0]['clanid'] === 0) {
    header("Location: view.php?page=clan&tab=joinclan");
    exit();
}
$clan_id = (int)$datauser[0]['clanid'];

$sth = $db->prepare("SELECT * FROM clan WHERE id = :id LIMIT 1");
$sth->execute([':id' => $clan_id]);
$clansdata = $sth->fetchAll();
if (!$clansdata) { header("Location: view.php?page=clan&tab=joinclan"); exit(); }
$clan     = $clansdata[0];
$admin_id = (int)$clan['admin_id'];
$is_admin = ($admin_id === (int)$_SESSION['player_id']);

// nombre de membres
$sth = $db->prepare("SELECT COUNT(id) AS nb FROM users WHERE clanid = :cid");
$sth->execute([':cid' => $clan_id]);
$nbmembers = (int)$sth->fetchColumn();

// messages (15 derniers)
$sth = $db->prepare("SELECT * FROM clan_messages WHERE clanid = :clanid ORDER BY timestamp DESC LIMIT 15");
$sth->execute([':clanid' => $clan_id]);
$clan_messages = $sth->fetchAll();

// ---------------------------------------------------------------
// Helpers rôles & permissions
// ---------------------------------------------------------------
function clan_get_role($db, int $clan_id, int $user_id) : array {
    $q = $db->prepare("SELECT role, can_invite, can_kick, can_edit_desc, can_set_tax, can_spend
                       FROM clan_roles WHERE clan_id = ? AND user_id = ? LIMIT 1");
    $q->execute([$clan_id, $user_id]);
    $r = $q->fetchAll();
    return $r ? $r[0] : [];
}
function clan_can_edit_desc($db, int $clan_id, int $user_id, bool $is_admin) : bool {
    if ($is_admin) return true;
    $r = clan_get_role($db, $clan_id, $user_id);
    return !empty($r) && (int)$r['can_edit_desc'] === 1;
}

// Petites variables pratiques pour l'UI
$can_edit_desc = clan_can_edit_desc($db, $clan_id, (int)$_SESSION['player_id'], $is_admin);

// ---------------------------------------------------------------
// Flash
// ---------------------------------------------------------------
function set_flash(string $type, string $msg) {
    $_SESSION['flash'] = ['type'=>$type,'msg'=>$msg];
}
function get_flash(): ?array {
    if (!empty($_SESSION['flash'])) { $f = $_SESSION['flash']; unset($_SESSION['flash']); return $f; }
    return null;
}

// ---------------------------------------------------------------
// Journal local
// ---------------------------------------------------------------
function clan_log_local($db, int $clan_id, ?int $actor_id, string $type, array $details = []) {
    try {
        $db->insert('clan_log', [
            'clan_id'       => $clan_id,
            'actor_user_id' => $actor_id,
            'action_type'   => $type,
            'details'       => json_encode($details, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
        ]);
    } catch (Throwable $e) { /* ignore si table absente */ }
}

// ---------------------------------------------------------------
// Actions (forms)
// ---------------------------------------------------------------
$ui_errors = [];
$ui_notices = [];

/* ========= Description du clan ========= */
if (isset($_POST['clan-edit-form-submit'])) {
    if (!$can_edit_desc) {
        set_flash('error', "You don't have permission to edit the description.");
        header("Location: view.php?page=clan&tab=claninfos"); exit();
    }

    $desc = trim($_POST['clan-edit-form-description'] ?? '');
    $len  = function_exists('mb_strlen') ? mb_strlen($desc, 'UTF-8') : strlen($desc);

    if ($desc === '' || $len < 12 || $len > 120) {
        set_flash('error', "Invalid Clan's Description (12–120 characters).");
        header("Location: view.php?page=clan&tab=claninfos"); exit();
    }

    $upd = $db->prepare("UPDATE clan SET clan_description = :d WHERE id = :id LIMIT 1");
    $ok  = $upd->execute([':d'=>$desc, ':id'=>$clan_id]);

    // Relecture immédiate
    $chk = $db->prepare("SELECT clan_description FROM clan WHERE id = :id LIMIT 1");
    $chk->execute([':id'=>$clan_id]);
    $saved = (string)$chk->fetchColumn();

    if ($ok && $saved === $desc) {
        clan_log_local($db, $clan_id, (int)$_SESSION['player_id'], 'desc_edit', ['field'=>'description']);
        set_flash('success', 'Description updated.');
    } else {
        set_flash('error', "Description could not be saved (DB mismatch).");
    }
    header("Location: view.php?page=clan&tab=claninfos"); exit();
}

// ========= Nouveau message =========
if (isset($_POST['clan-newmessage-submit'])) {
    $msg = trim($_POST['clan-newmessage-message'] ?? '');
    if ($msg === '' || strlen($msg) < 12 || strlen($msg) > 120) {
        set_flash('error', "Invalid Message (12–120 characters).");
        header("Location: view.php?page=clan&tab=claninfos"); exit();
    } else {
        $message = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
        $db->insert('clan_messages', [
            'clanid'    => $clan_id,
            'player_id' => (int)$_SESSION['player_id'],
            'message'   => $message
        ]);
        set_flash('success', 'Message posted.');
        header("Location: view.php?page=clan&tab=claninfos"); exit();
    }
}

// ========= Quitter / supprimer / transfert leadership =========

// Membre non-admin quitte le clan
if (isset($_POST['clan-leave-form-submit'])) {
    if ($is_admin) {
        set_flash('error', "As the clan leader, choose to transfer leadership or disband the clan.");
        header("Location: view.php?page=clan&tab=claninfos"); exit();
    }

    $sth = $db->prepare("UPDATE users SET clanid=0 WHERE id=:id AND clanid=:cid");
    $sth->execute([':id' => $_SESSION['player_id'], ':cid' => $clan_id]);

    try {
        $db->prepare("DELETE FROM clan_roles WHERE clan_id=:cid AND user_id=:uid")
           ->execute([':cid'=>$clan_id, ':uid'=>(int)$_SESSION['player_id']]);
    } catch (Throwable $e) {}

    clan_log_local($db, $clan_id, (int)$_SESSION['player_id'], 'member_leave', []);
    set_flash('success', "You left the clan.");
    header("Location: view.php?page=clan&tab=joinclan"); exit();
}

// Transfert du leadership (admin -> membre choisi) et l’admin quitte
if (isset($_POST['clan-transfer-lead-submit']) && $is_admin) {
    $newLeader = (int)($_POST['new_admin_user_id'] ?? 0);

    $chk = $db->prepare("SELECT COUNT(*) FROM users WHERE id=:uid AND clanid=:cid");
    $chk->execute([':uid'=>$newLeader, ':cid'=>$clan_id]);
    if ($newLeader <= 0 || (int)$chk->fetchColumn() === 0 || $newLeader === (int)$_SESSION['player_id']) {
        set_flash('error', "Invalid leader selection.");
        header("Location: view.php?page=clan&tab=claninfos"); exit();
    }

    $db->prepare("UPDATE clan SET admin_id = :new WHERE id = :cid LIMIT 1")
       ->execute([':new'=>$newLeader, ':cid'=>$clan_id]);

    $db->prepare("UPDATE users SET clanid=0 WHERE id=:id AND clanid=:cid")
       ->execute([':id'=>(int)$_SESSION['player_id'], ':cid'=>$clan_id]);

    try {
        $db->prepare("DELETE FROM clan_roles WHERE clan_id=:cid AND user_id=:uid")
           ->execute([':cid'=>$clan_id, ':uid'=>(int)$_SESSION['player_id']]);
    } catch (Throwable $e) {}

    clan_log_local($db, $clan_id, (int)$_SESSION['player_id'], 'leader_transfer', [
        'new_leader_user_id' => $newLeader
    ]);
    clan_log_local($db, $clan_id, (int)$_SESSION['player_id'], 'member_leave', []);

    set_flash('success', "Leadership transferred. You left the clan.");
    header("Location: view.php?page=clan&tab=joinclan"); exit();
}

// Dissolution complète du clan (admin uniquement)
if (isset($_POST['clan-delete-form-submit']) && $is_admin) {

    $db->update('users', ['clanid' => 0], 'clanid='.$clan_id);

    $db->prepare("DELETE FROM `clan` WHERE id=:clanid")->execute([':clanid'=>$clan_id]);
    $db->prepare("DELETE FROM clan_messages WHERE clanid=:clanid")->execute([':clanid'=>$clan_id]);
    $db->prepare("DELETE FROM `clan_request` WHERE clan_id=:clanid")->execute([':clanid'=>$clan_id]);
    $db->prepare("DELETE FROM `clan_diplomacy` WHERE clan_id=:clanid OR second_clan_id=:clanid")->execute([':clanid'=>$clan_id]);
    $db->prepare("DELETE FROM `clan_diplomacy_request` WHERE clan_id=:clanid OR second_clan_id=:clanid")->execute([':clanid'=>$clan_id]);
    try { $db->prepare("DELETE FROM `clan_roles` WHERE clan_id=:clanid")->execute([':clanid'=>$clan_id]); } catch (Throwable $e) {}
    try { $db->prepare("DELETE FROM `clan_wallet` WHERE clan_id=:clanid")->execute([':clanid'=>$clan_id]); } catch (Throwable $e) {}
    try { $db->prepare("DELETE FROM `clan_tax_settings` WHERE clan_id=:clanid")->execute([':clanid'=>$clan_id]); } catch (Throwable $e) {}
    try { $db->prepare("DELETE FROM `clan_transfers` WHERE clan_id=:clanid")->execute([':clanid'=>$clan_id]); } catch (Throwable $e) {}

    clan_log_local($db, $clan_id, (int)$_SESSION['player_id'], 'clan_disband', []);
    set_flash('success', "Clan disbanded.");
    header("Location: view.php?page=clan&tab=createclan"); exit();
}

// ---------------------------------------------------------------
// Utils
// ---------------------------------------------------------------
function convertToNumericEntities($string) {
    $convmap = [0x80, 0x10ffff, 0, 0xffffff];
    return mb_encode_numericentity($string, $convmap, "UTF-8");
}

// Lire un éventuel flash pour affichage
$flash = get_flash();
?>
<link rel="stylesheet" type="text/css" href="styles/achievements.css" />
<link rel="stylesheet" type="text/css" href="styles/clan.css?v=2" />

<!-- Styles flash -->
<style>
.flash { position: sticky; top: 12px; z-index: 30; margin: 0 auto 12px; width: min(100%, 960px);
  padding: 10px 14px; border-radius: 10px; border: 1px solid rgba(148,163,184,.25);
  box-shadow: var(--shadow-md, 0 12px 30px rgba(2,6,23,.32)); display: flex; gap: .75rem; }
.flash--success { background: rgba(34,197,94,.12); color: #dcfce7; border-color: rgba(34,197,94,.35); }
.flash--error { background: rgba(248,113,113,.12); color: #fecaca; border-color: rgba(248,113,113,.35); }
.flash-close { margin-left: auto; background: transparent; border: 0; cursor: pointer; color: inherit; font-weight: 700; opacity: .8; }
.flash-close:hover { opacity: 1; }
</style>

<div class="clan-page">
  <?php if ($flash): ?>
    <div class="flash <?= $flash['type']==='success' ? 'flash--success' : 'flash--error' ?>" role="status" id="flashBox">
      <div><?= htmlspecialchars($flash['msg']) ?></div>
      <button class="flash-close" type="button" aria-label="Close" onclick="(function(){var n=document.getElementById('flashBox'); if(n) n.remove();})();">×</button>
    </div>
    <script>setTimeout(function(){var n=document.getElementById('flashBox'); if(n){n.remove();}}, 4000);</script>
  <?php endif; ?>

  <h3 class="clan-section-title">Clan informations</h3>
  <div class="clan-card">
    <dl class="clan-grid">
      <div class="clan-row"><dt>Clan's Company</dt>
        <dd><img src="img/ranks/company/<?= (int)$clan['clan_company'] ?>.png" alt=""></dd></div>
      <div class="clan-row"><dt>Clan's Tag</dt><dd><?= htmlspecialchars($clan['clan_tag']) ?></dd></div>
      <div class="clan-row"><dt>Clan's Name</dt><dd><?= htmlspecialchars($clan['clan_name']) ?></dd></div>
      <div class="clan-row"><dt>Marshal</dt><dd>
        <?php $sth = $db->prepare("SELECT username FROM users WHERE id = :id LIMIT 1");
              $sth->execute([':id' => $admin_id]);
              $admin_name = $sth->fetchColumn();
              echo htmlspecialchars($admin_name); ?>
      </dd></div>
      <div class="clan-row"><dt>Number of members</dt><dd><?= (int)$nbmembers ?></dd></div>
      <div class="clan-row"><dt>Number of kills</dt><dd><?= (int)$clan['kill_count'] ?></dd></div>

      <div class="clan-row clan-row--textarea">
        <dt>Clan's Description</dt>
        <dd>
          <form class="clan-form" action="view.php?page=clan&tab=claninfos" method="post">
            <textarea name="clan-edit-form-description" rows="4" cols="40" <?= $can_edit_desc ? '' : 'readonly' ?>><?= htmlspecialchars($clan['clan_description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
            <?php if ($can_edit_desc) { ?>
              <button class="btn-primary" name="clan-edit-form-submit" value="1" type="submit">Save</button>
            <?php } ?>
          </form>
        </dd>
      </div>
    </dl>
  </div>

  <h3 class="clan-section-title">Clan messages</h3>
  <div class="clan-card">
    <div class="clan-messages-box">
      <?php foreach ($clan_messages as $message) {
          $pid = (int)$message['player_id'];
          $sth = $db->prepare("SELECT username FROM users WHERE id = :id");
          $sth->execute([':id' => $pid]);
          $name = $sth->fetchColumn();
      ?>
        <div class="clan-message">
          <span class="msg-user"><?= htmlspecialchars($name ?: 'Unknown') ?></span>
          <span class="msg-time"><?= htmlspecialchars($message['timestamp']) ?></span>
          <div class="msg-body"><?= $message['message'] ?></div>
        </div>
      <?php } ?>
    </div>
    <form class="clan-form" action="view.php?page=clan&tab=claninfos" method="post">
      <label class="clan-label">New Message <small>(12–120 characters)</small></label>
      <textarea name="clan-newmessage-message" rows="4" cols="40"></textarea>
      <button class="btn-primary" name="clan-newmessage-submit" value="1" type="submit">Post New Message</button>
    </form>
  </div>

  <h3 class="clan-section-title">Clan Administration</h3>
  <div class="clan-card">
    <?php if ($is_admin) { ?>
      <!-- Bloc spécial leader : transférer ou dissoudre -->
      <form class="clan-form" action="view.php?page=clan&tab=claninfos" method="post" onsubmit="return confirm('Confirm disband? This cannot be undone.');">
        <button class="btn-danger" name="clan-delete-form-submit" value="1" type="submit">Disband Clan</button>
      </form>
      <hr>
      <form class="clan-form" action="view.php?page=clan&tab=claninfos" method="post">
        <label>Transfer leadership to:
          <select name="new_admin_user_id" required>
            <?php
            $mem = $db->prepare("SELECT id, username FROM users WHERE clanid = :cid AND id <> :admin ORDER BY username ASC");
            $mem->execute([':cid'=>$clan_id, ':admin'=>$admin_id]);
            foreach ($mem->fetchAll() as $m) {
                echo '<option value="'.(int)$m['id'].'">'.htmlspecialchars($m['username']).'</option>';
            }
            ?>
          </select>
        </label>
        <button class="btn-primary" name="clan-transfer-lead-submit" value="1" type="submit" onclick="return confirm('Transfer leadership and leave the clan?');">Transfer & Leave</button>
      </form>
    <?php } else { ?>
      <form class="clan-form" action="view.php?page=clan&tab=claninfos" method="post" onsubmit="return confirm('Leave the clan?');">
        <button class="btn-danger" name="clan-leave-form-submit" value="1" type="submit">Leave Clan</button>
      </form>
    <?php } ?>
  </div>
</div>
