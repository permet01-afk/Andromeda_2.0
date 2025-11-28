<?php
// ===== Flash =====
function set_flash(string $type, string $msg){ $_SESSION['flash']=['type'=>$type,'msg'=>$msg]; }
function get_flash(): ?array { if(!empty($_SESSION['flash'])){ $f=$_SESSION['flash']; unset($_SESSION['flash']); return $f; } return null; }

// ===== Récup clan du joueur =====
$sth = $db->prepare("SELECT clanid FROM users WHERE id = :id LIMIT 1");
$sth->execute([':id'=>$_SESSION['player_id']]);
$row = $sth->fetch();
if(!$row || (int)$row['clanid']===0){ header("Location: view.php?page=clan&tab=joinclan"); exit(); }
$clan_id = (int)$row['clanid'];

// ===== Info clan =====
$sth = $db->prepare("SELECT id, admin_id FROM clan WHERE id = :id LIMIT 1");
$sth->execute([':id'=>$clan_id]);
$clan = $sth->fetch();
if(!$clan){ header("Location: view.php?page=clan&tab=joinclan"); exit(); }
$is_marshal = ((int)$clan['admin_id'] === (int)$_SESSION['player_id']);

// ===== Permission "manage roles" (marshal OU can_manage_roles) =====
function current_can_manage_roles(PDO $db, int $clan_id, int $user_id, bool $is_marshal): bool {
    if($is_marshal) return true;
    $q=$db->prepare("SELECT can_manage_roles FROM clan_roles WHERE clan_id=? AND user_id=? LIMIT 1");
    $q->execute([$clan_id,$user_id]);
    $r=$q->fetch();
    return $r && (int)$r['can_manage_roles']===1;
}
$can_manage = current_can_manage_roles($db,$clan_id,(int)$_SESSION['player_id'],$is_marshal);

// ===== Auto-peupler (au minimum 'member') — SANS can_invite =====
$db->prepare("
  INSERT INTO clan_roles (
    clan_id, user_id, role, role_name,
    can_kick, can_edit_desc,
    can_set_tax_rate, can_toggle_tax_active, can_spend,
    can_review_requests, can_manage_roles,
    can_start_alliance, can_cancel_alliance, can_declare_war, can_request_war_cancel
  )
  SELECT
    u.clanid, u.id, 'member', 'member',
    0,0,
    0,0,0,
    0,0,
    0,0,0,0
  FROM users u
  LEFT JOIN clan_roles r ON r.clan_id=u.clanid AND r.user_id=u.id
  WHERE u.clanid=:cid AND r.user_id IS NULL
")->execute([':cid'=>$clan_id]);

// ===== POST : save =====
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['save_role']) && $can_manage){
    $target_id=(int)($_POST['user_id']??0);

    // role_name (max 9)
    $role_name = trim((string)($_POST['role_name'] ?? 'member'));
    $role_name = preg_replace('~[^a-zA-Z0-9 _-]+~','',$role_name);
    if ($role_name==='') $role_name='member';
    if (function_exists('mb_substr')) $role_name = mb_substr($role_name,0,9,'UTF-8');
    else $role_name = substr($role_name,0,9);

    // flags fins (UI visible réduite, mais on envoie tout)
    $flags = [
        'can_kick'               => !empty($_POST['can_kick'])?1:0,
        'can_edit_desc'          => !empty($_POST['can_edit_desc'])?1:0,

        'can_set_tax_rate'       => !empty($_POST['can_set_tax_rate'])?1:0,
        'can_toggle_tax_active'  => !empty($_POST['can_toggle_tax_active'])?1:0,
        'can_spend'              => !empty($_POST['can_spend'])?1:0,

        'can_review_requests'    => !empty($_POST['can_review_requests'])?1:0,
        'can_manage_roles'       => !empty($_POST['can_manage_roles'])?1:0,

        'can_start_alliance'     => !empty($_POST['can_start_alliance'])?1:0,
        'can_cancel_alliance'    => !empty($_POST['can_cancel_alliance'])?1:0,
        'can_declare_war'        => !empty($_POST['can_declare_war'])?1:0,
        'can_request_war_cancel' => !empty($_POST['can_request_war_cancel'])?1:0,
    ];

    // Vérifier appartenance au clan
    $chk=$db->prepare("SELECT COUNT(*) FROM users WHERE id=? AND clanid=?");
    $chk->execute([$target_id,$clan_id]);
    if((int)$chk->fetchColumn()===0){
        set_flash('error','User not in your clan.');
        header("Location: view.php?page=clan&tab=clanroles"); exit();
    }

    // Marshal : tout est forcé à 1
    if ($target_id === (int)$clan['admin_id']) {
        $flags = [
          'can_kick'=>1,'can_edit_desc'=>1,
          'can_set_tax_rate'=>1,'can_toggle_tax_active'=>1,'can_spend'=>1,
          'can_review_requests'=>1,'can_manage_roles'=>1,
          'can_start_alliance'=>1,'can_cancel_alliance'=>1,'can_declare_war'=>1,'can_request_war_cancel'=>1,
        ];
    }

    // Update
    $upd=$db->prepare("
      UPDATE clan_roles SET
        role_name=:rn,
        can_kick=:ck, can_edit_desc=:ced,
        can_set_tax_rate=:cstr, can_toggle_tax_active=:ctta, can_spend=:cs,
        can_review_requests=:crr, can_manage_roles=:cmr,
        can_start_alliance=:csa, can_cancel_alliance=:cca, can_declare_war=:cdw, can_request_war_cancel=:crwc
      WHERE clan_id=:cid AND user_id=:uid
    ");
    $ok=$upd->execute([
        ':rn'=>$role_name,
        ':ck'=>$flags['can_kick'], ':ced'=>$flags['can_edit_desc'],
        ':cstr'=>$flags['can_set_tax_rate'], ':ctta'=>$flags['can_toggle_tax_active'], ':cs'=>$flags['can_spend'],
        ':crr'=>$flags['can_review_requests'], ':cmr'=>$flags['can_manage_roles'],
        ':csa'=>$flags['can_start_alliance'], ':cca'=>$flags['can_cancel_alliance'], ':cdw'=>$flags['can_declare_war'], ':crwc'=>$flags['can_request_war_cancel'],
        ':cid'=>$clan_id, ':uid'=>$target_id
    ]);

    if($ok){
        try{
            $db->insert('clan_log', [
                'clan_id'=>$clan_id,
                'actor_user_id'=>(int)$_SESSION['player_id'],
                'action_type'=>'role_change',
                'details'=>json_encode(['target_user_id'=>$target_id,'role_name'=>$role_name,'flags'=>$flags], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
            ]);
        }catch(Throwable $e){}
        set_flash('success','Role updated.');
    }else{
        set_flash('error','Update failed.');
    }
    header("Location: view.php?page=clan&tab=clanroles"); exit();
}

// ===== Lire membres + rôles (SANS can_invite) =====
$members=$db->prepare("
    SELECT u.id, u.username,
           r.role_name,
           r.can_kick, r.can_edit_desc,
           r.can_set_tax_rate, r.can_toggle_tax_active, r.can_spend,
           r.can_review_requests, r.can_manage_roles,
           r.can_start_alliance, r.can_cancel_alliance, r.can_declare_war, r.can_request_war_cancel
    FROM users u
    JOIN clan_roles r ON r.clan_id=u.clanid AND r.user_id=u.id
    WHERE u.clanid=:cid
    ORDER BY u.username ASC
");
$members->execute([':cid'=>$clan_id]);
$rows=$members->fetchAll();

$flash=get_flash();
?>
<link rel="stylesheet" type="text/css" href="styles/clan.css?v=2" />
<style>
.clan-card{overflow:visible}

/* Table en auto pour laisser respirer la colonne Permissions */
.roles-table{
  width:100%;
  border-collapse:separate;
  border-spacing:0 10px;
  table-layout:auto;              /* <- auto, pas fixed */
}
.roles-table th,.roles-table td{
  padding:.55rem .6rem;
  vertical-align:middle;
  white-space:nowrap;             /* pas de retour dans les cellules */
}
.roles-table thead th{color:#cbd5f5;font-weight:700;text-align:left}
.roles-row{background:rgba(15,23,42,.82);border:1px solid rgba(148,163,184,.2);border-radius:10px}
.roles-row td:first-child{border-top-left-radius:10px;border-bottom-left-radius:10px}
.roles-row td:last-child{border-top-right-radius:10px;border-bottom-right-radius:10px}

/* Colonnes: on réduit User/Role/Save pour donner la place à Permissions */
.user-cell{width:160px}
.role-name-cell{width:210px}
.permissions-cell{width:auto}     /* prend toute la place */
.save-cell{width:90px;text-align:left}

/* Badge & input */
.badge{display:inline-block;padding:.15rem .5rem;border-radius:999px;font-size:.75rem}
.badge-marshal{background:rgba(34,197,94,.2);color:#86efac}
.role-name-input{width:170px}

/* Permissions sur UNE SEULE LIGNE, sans wrap, sans scroll */
.hidden-perms{display:none;}
.inline-perms{
  display:flex;
  align-items:center;
  gap:.75rem;                     /* plus serré */
  flex-wrap:nowrap;               /* <- pas de retour à la ligne */
  white-space:nowrap;             /* <- tout sur une seule ligne */
  overflow:visible;               /* pas de scrollbar ni masquage */
}
.inline-perms > label{white-space:nowrap}

fieldset.disabled{opacity:.6;pointer-events:none}
</style>

<div class="clan-page">
  <h3 class="clan-section-title">Roles & Permissions</h3>

  <?php if($flash): ?>
    <div class="flash <?= $flash['type']==='success'?'flash--success':'flash--error'?>"><?= htmlspecialchars($flash['msg']) ?></div>
  <?php endif; ?>

  <div class="clan-card">
    <?php if(!$can_manage): ?>
      <p class="user-hub__empty">You don't have permission to manage roles.</p>
    <?php else: ?>
      <table class="roles-table">
        <thead>
          <tr>
            <th class="user-cell">User</th>
            <th class="role-name-cell">Role name</th>
            <th>Permissions</th>
            <th class="save-cell">Save</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($rows as $r):
              $uid = (int)$r['id'];
              $is_target_marshal = ($uid === (int)$clan['admin_id']);
        ?>
          <tr class="roles-row">
            <td class="user-cell">
              <?= htmlspecialchars($r['username']) ?>
              <?php if($is_target_marshal): ?>
                <span class="badge badge-marshal">marshal</span>
              <?php endif; ?>
            </td>

            <td class="role-name-cell">
              <form action="view.php?page=clan&tab=clanroles" method="post" style="display:flex;gap:.5rem;align-items:center">
                <input type="hidden" name="user_id" value="<?= $uid ?>">
                <input class="role-name-input" type="text" name="role_name" maxlength="9" value="<?= htmlspecialchars($r['role_name']??'member',ENT_QUOTES,'UTF-8') ?>">
            </td>

            <td class="permissions-cell">
              <fieldset class="inline-perms <?= $is_target_marshal ? 'disabled' : '' ?>">
                <label><input type="checkbox" name="can_kick"        <?= $r['can_kick']?'checked':'' ?>> Kick</label>
                <label><input type="checkbox" name="can_edit_desc"   <?= $r['can_edit_desc']?'checked':'' ?>> Edit</label>

                <label><input type="checkbox" id="macro-tr-<?= $uid ?>" data-macro="tr-<?= $uid ?>"> Treasurer</label>
                <label><input type="checkbox" id="macro-dp-<?= $uid ?>" data-macro="dp-<?= $uid ?>"> Diplomat</label>

                <label><input type="checkbox" name="can_review_requests" <?= $r['can_review_requests']?'checked':'' ?>> Requests</label>
                <label><input type="checkbox" name="can_manage_roles"   <?= $r['can_manage_roles']?'checked':'' ?>> Roles</label>

                <!-- Fines (cachées) -->
                <span class="hidden-perms">
                  <input type="checkbox" name="can_set_tax_rate"      class="tr-<?= $uid ?>" data-group="tr-<?= $uid ?>" <?= $r['can_set_tax_rate']?'checked':'' ?>>
                  <input type="checkbox" name="can_toggle_tax_active" class="tr-<?= $uid ?>" data-group="tr-<?= $uid ?>" <?= $r['can_toggle_tax_active']?'checked':'' ?>>
                  <input type="checkbox" name="can_spend"             class="tr-<?= $uid ?>" data-group="tr-<?= $uid ?>" <?= $r['can_spend']?'checked':'' ?>>

                  <input type="checkbox" name="can_start_alliance"     class="dp-<?= $uid ?>" data-group="dp-<?= $uid ?>" <?= $r['can_start_alliance']?'checked':'' ?>>
                  <input type="checkbox" name="can_cancel_alliance"    class="dp-<?= $uid ?>" data-group="dp-<?= $uid ?>" <?= $r['can_cancel_alliance']?'checked':'' ?>>
                  <input type="checkbox" name="can_declare_war"        class="dp-<?= $uid ?>" data-group="dp-<?= $uid ?>" <?= $r['can_declare_war']?'checked':'' ?>>
                  <input type="checkbox" name="can_request_war_cancel" class="dp-<?= $uid ?>" data-group="dp-<?= $uid ?>" <?= $r['can_request_war_cancel']?'checked':'' ?>>
                </span>
              </fieldset>
            </td>

            <td class="save-cell">
              <button class="btn-primary" type="submit" name="save_role" value="1">Save</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>

<script>
// Met à jour l’état de la macro selon ses cases fines (true / false / indeterminate)
function syncMacro(macroInput){
  const group = macroInput.dataset.macro;
  const items = document.querySelectorAll('input[data-group="'+group+'"]');
  const total = items.length;
  const checked = Array.from(items).filter(i=>i.checked).length;
  if (checked === total){ macroInput.checked = true; macroInput.indeterminate = false; }
  else if (checked === 0){ macroInput.checked = false; macroInput.indeterminate = false; }
  else { macroInput.checked = false; macroInput.indeterminate = true; }
}

document.addEventListener('DOMContentLoaded', function(){
  document.querySelectorAll('input[data-macro]').forEach(macro=>{
    const grp = macro.dataset.macro;

    // clic macro -> (dé)cocher tout
    macro.addEventListener('change', e=>{
      document.querySelectorAll('input[data-group="'+grp+'"]').forEach(chk=>{ chk.checked = e.target.checked; });
      e.target.indeterminate = false;
    });

    // cases fines -> maj macro
    document.querySelectorAll('input[data-group="'+grp+'"]').forEach(chk=>{
      chk.addEventListener('change', ()=> syncMacro(macro));
    });

    // état initial
    syncMacro(macro);
  });
});
</script>
