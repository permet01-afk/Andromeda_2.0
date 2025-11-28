<?php
// --- Sécurité de base : récupérer le clan du joueur ---
$sth = $db->prepare("SELECT clanid FROM users WHERE id = :id LIMIT 1");
$sth->execute([':id' => $_SESSION['player_id']]);
$me = $sth->fetchAll();
if (!$me || (int)$me[0]['clanid'] === 0) {
    header("Location: view.php?page=clan&tab=joinclan"); exit();
}
$clan_id = (int)$me[0]['clanid'];

// --- Charger les 100 derniers logs du clan ---
$sth = $db->prepare("
    SELECT id, clan_id, actor_user_id, action_type, details, created_at
    FROM clan_log
    WHERE clan_id = :cid
    ORDER BY created_at DESC
    LIMIT 100
");
$sth->execute([':cid' => $clan_id]);
$rows = $sth->fetchAll();

// --- IDs à résoudre ---
$userIds = [];
$clanIds = [];

foreach ($rows as $r) {
    if (!empty($r['actor_user_id'])) $userIds[] = (int)$r['actor_user_id'];
    $det = [];
    if (!empty($r['details'])) {
        $tmp = json_decode($r['details'], true);
        if (is_array($tmp)) $det = $tmp;
    }
    foreach ([
        'to_user_id','from_user_id','target_user_id','kicked_user_id',
        'joined_user_id','left_user_id','new_leader_user_id'
    ] as $k) {
        if (isset($det[$k])) $userIds[] = (int)$det[$k];
    }
    foreach (['other_clan_id','second_clan_id','target_clan_id'] as $k) {
        if (isset($det[$k])) $clanIds[] = (int)$det[$k];
    }
}
$userIds = array_values(array_unique(array_filter($userIds)));
$clanIds = array_values(array_unique(array_filter($clanIds)));

// --- Résoudre pseudos: users.username puis fallback users_info.login ---
$userMap = [];
if ($userIds) {
    $in = implode(',', array_fill(0, count($userIds), '?'));

    // 1) users.username
    $sth = $db->prepare("SELECT id, username FROM users WHERE id IN ($in)");
    $sth->execute($userIds);
    foreach ($sth->fetchAll() as $u) {
        if (!empty($u['username'])) $userMap[(int)$u['id']] = $u['username'];
    }

    // 2) fallback sur users_info.login pour les manquants
    $missing = array_values(array_diff($userIds, array_keys($userMap)));
    if ($missing) {
        $in2 = implode(',', array_fill(0, count($missing), '?'));
        // adapte le nom de table/champ si besoin
        $sth = $db->prepare("SELECT id, login FROM users_info WHERE id IN ($in2)");
        $sth->execute($missing);
        foreach ($sth->fetchAll() as $u) {
            if (!empty($u['login'])) $userMap[(int)$u['id']] = $u['login'];
        }
    }
}

// --- Résoudre noms de clan ---
$clanMap = [];
if ($clanIds) {
    $in = implode(',', array_fill(0, count($clanIds), '?'));
    $sth = $db->prepare("SELECT id, clan_tag, clan_name FROM clan WHERE id IN ($in)");
    $sth->execute($clanIds);
    foreach ($sth->fetchAll() as $c) {
        $clanMap[(int)$c['id']] = '['.$c['clan_tag'].'] '.$c['clan_name'];
    }
}

// --- Helpers rendu ---
function fmt_credits($n){ return number_format((int)$n, 0, '.', ' '); }
function yesno($v){ return ((int)$v===1 ? '✓' : '✗'); }
// Normalise action_type
function norm_action($s){
    $s = strtolower((string)$s);
    $s = str_replace([chr(13),chr(10),chr(9),chr(160)], '', $s); // CR, LF, TAB, NBSP
    $s = trim($s);
    $s = preg_replace('~[^a-z_]+~','', $s);
    return $s;
}

?>
<link rel="stylesheet" type="text/css" href="styles/clan.css?v=2" />

<div class="box">
  <div class="title">Clan Log</div>

  <div id="clan-logs" class="clan-messages-box">
    <?php if (!$rows): ?>
      <div class="clan-message"><em>No activity yet.</em></div>
    <?php else: ?>
      <?php foreach ($rows as $r):
        $actor = $r['actor_user_id'] ? ($userMap[(int)$r['actor_user_id']] ?? ('#'.$r['actor_user_id'])) : 'System';
        $actor = htmlspecialchars($actor, ENT_QUOTES, 'UTF-8');

        $t = norm_action($r['action_type'] ?? '');
        $d = [];
        if (!empty($r['details'])) {
            $tmp = json_decode($r['details'], true);
            if (is_array($tmp)) $d = $tmp;
        }

        $getUser = function($uid) use ($userMap){
            if ($uid===null) return null;
            $name = $userMap[(int)$uid] ?? ('#'.$uid);
            return htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        };

        $toUser   = isset($d['to_user_id']) ? $getUser($d['to_user_id']) : null;
        $target   = isset($d['target_user_id']) ? $getUser($d['target_user_id']) : null;
        $kicked   = isset($d['kicked_user_id']) ? $getUser($d['kicked_user_id']) : null; // <-- ajouté
        $left     = isset($d['left_user_id']) ? $getUser($d['left_user_id']) : null;     // <-- pour plus tard
        $newLead  = isset($d['new_leader_user_id']) ? $getUser($d['new_leader_user_id']) : null;
        $joined   = isset($d['joined_user_id']) ? $getUser($d['joined_user_id']) : null;

        $otherClanId = $d['other_clan_id'] ?? ($d['second_clan_id'] ?? ($d['target_clan_id'] ?? null));
        $otherClan = $otherClanId ? ($clanMap[(int)$otherClanId] ?? ('Clan #'.$otherClanId)) : null;
        if ($otherClan !== null) $otherClan = htmlspecialchars($otherClan, ENT_QUOTES, 'UTF-8');

        $amount = isset($d['amount']) ? (int)$d['amount'] : null;
        $rate   = isset($d['rate_bps']) ? (int)$d['rate_bps'] : null;
        $active = isset($d['active']) ? (int)$d['active'] : null;
        $note   = (!empty($d['note'])) ? ' — “'.htmlspecialchars((string)$d['note'], ENT_QUOTES, 'UTF-8').'”' : '';

        switch ($t) {
          case 'member_join':
            if ($joined !== null) {
              $msg = "{$joined} joined the clan (accepted by {$actor}).";
            } else {
              $msg = "{$actor} joined the clan.";
            }
            break;

          case 'member_leave':
            // si un jour tu logs left_user_id, on l’affiche : sinon acteur
            $who = $left ?? $actor;
            $msg = "{$who} left the clan.";
            break;

          case 'member_kick':
            // priorité au champ normalisé 'kicked_user_id', fallback sur 'target_user_id'
            $who = $kicked ?? $target ?? '(unknown)';
            $msg = "{$actor} kicked {$who}.";
            break;

          case 'invite_sent':
            $msg = "{$actor} sent an invite to {$toUser}.";
            break;
          case 'invite_accepted':
            $msg = "{$actor} accepted the invite.";
            break;
          case 'invite_declined':
            $msg = "{$actor} declined the invite.";
            break;

          case 'alliance_request':
            $msg = "{$actor} requested an alliance with {$otherClan}.";
            break;
          case 'alliance_accept':
            $msg = "{$actor} accepted the alliance with {$otherClan}.";
            break;

          case 'alliance_cancel':
            $phase = $d['phase'] ?? '';
            if ($phase === 'direct_cancel') {
              $msg = "{$actor} cancelled the alliance with {$otherClan}{$note}.";
            } elseif ($phase === 'request_cancelled_by_requester') {
              $msg = "{$actor} cancelled their alliance request to {$otherClan}{$note}.";
            } elseif ($phase === 'request_refused_by_recipient') {
              $msg = "{$actor} refused the alliance request from {$otherClan}{$note}.";
            } else {
              $msg = "{$actor} cancelled the alliance with {$otherClan}{$note}.";
            }
            break;

          case 'war_declare':
            $msg = "{$actor} declared war on {$otherClan}.";
            break;

          case 'war_cancel':
            $phase = $d['phase'] ?? '';
            if ($phase === 'request') {
              $msg = "{$actor} requested to cancel the war with {$otherClan}{$note}.";
            } elseif ($phase === 'accepted') {
              $msg = "{$actor} accepted the war cancellation with {$otherClan}. The war has ended{$note}.";
            } elseif ($phase === 'request_refused') {
              $msg = "{$actor} refused the war cancellation with {$otherClan}{$note}.";
            } else {
              $msg = "{$actor} cancelled the war with {$otherClan}{$note}.";
            }
            break;

          case 'leader_transfer':
            $msg = "{$actor} transferred leadership to {$newLead}.";
            break;

          case 'role_change':
            $role = htmlspecialchars((string)($d['role'] ?? 'member'), ENT_QUOTES, 'UTF-8');
            $f = $d['flags'] ?? [];
            $msg = "{$actor} set {$target} to <b>{$role}</b> "
                 . "(invite ".yesno($f['can_invite'] ?? 0)
                 . ", kick ".yesno($f['can_kick'] ?? 0)
                 . ", edit desc ".yesno($f['can_edit_desc'] ?? 0)
                 . ", set tax ".yesno($f['can_set_tax'] ?? 0)
                 . ", spend ".yesno($f['can_spend'] ?? 0).").";
            break;

          case 'tax_change':
            $rperc = number_format(($rate ?? 0) / 100, 2, '.', '');
            $state = $active ? 'enabled' : 'disabled';
            $msg = "{$actor} {$state} clan tax at {$rperc}%.";
            break;

          case 'tax_run':
            $count = (int)($d['charged_count'] ?? 0);
            $total = fmt_credits($d['total_amount'] ?? 0);
            $msg = "Daily tax run: {$count} member(s) charged, total {$total} credits.";
            break;

          case 'wallet_credit':
            $msg = "{$actor} added ".fmt_credits($amount)." credits to the clan wallet{$note}.";
            break;
          case 'wallet_debit':
            $msg = "{$actor} removed ".fmt_credits($amount)." credits from the clan wallet{$note}.";
            break;
          case 'wallet_transfer':
            $msg = "{$actor} transferred ".fmt_credits($amount)." to {$toUser}{$note}.";
            break;

          default:
            $msg = htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
            if (!empty($d)) { $msg .= ' — '.htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8'); }
        }
      ?>
        <div class="clan-message">
          <span class="msg-time"><?= htmlspecialchars($r['created_at']) ?></span>
          <div class="msg-body"><?= $msg ?></div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
