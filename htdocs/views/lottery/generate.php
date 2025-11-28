<?php
// views/lottery/generate.php

// 1) Session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// 2) Access control
if (empty($_SESSION['player_id'])) {
    header('Content-Type: text/plain; charset=utf-8');
    http_response_code(401);
    echo "You must be logged in to play the lottery.";
    exit();
}

try {
    // 3) DB includes (adaptation aux chemins actuels)
    require_once __DIR__ . '/../../libs/database.php';
    require_once __DIR__ . '/../../config/database.php';

    $db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
    $playerId = (int)$_SESSION['player_id'];

    header('Content-Type: text/plain; charset=utf-8');

    // 4) Read tickets
    $sth = $db->prepare("SELECT tickets FROM users_infos WHERE id = :id LIMIT 1");
    $sth->execute([':id' => $playerId]);
    $row = $sth->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        http_response_code(404);
        echo "User not found.";
        exit();
    }

    $tickets = (int)$row['tickets'];
    if ($tickets < 1) {
        echo "You need a lottery ticket to play, you can buy them in the shop";
        exit();
    }

    // 5) Decrement 1 ticket atomically to avoid negative values if multiple clicks
    $dec = $db->prepare("UPDATE users_infos SET tickets = tickets - 1 WHERE id = :id AND tickets > 0");
    $dec->execute([':id' => $playerId]);

    if ($dec->rowCount() === 0) {
        echo "You need a lottery ticket to play, you can buy them in the shop";
        exit();
    }

    // 6) Draw
    $rand = mt_rand(0, 100);
    $message = '';

    if ($rand < 5) {
        // 5%: +1 token
        $req = $db->prepare("UPDATE users_infos SET tokens = tokens + 1 WHERE id = :id");
        $req->execute([':id' => $playerId]);
        $message = 'You won a Token';
    } elseif ($rand < 25) {
        // 20%: +2h NPC booster
        $sth = $db->prepare("SELECT booster_npc_time FROM users WHERE id = :id LIMIT 1");
        $sth->execute([':id' => $playerId]);
        $row = $sth->fetch(PDO::FETCH_ASSOC);

        $now = time();
        $cur  = isset($row['booster_npc_time']) ? (int)$row['booster_npc_time'] : 0;
        $newT = ($cur > $now) ? $cur + 2 * 3600 : $now + 2 * 3600;

        $req = $db->prepare("UPDATE users SET booster_npc_time = :t WHERE id = :id");
        $req->execute([':t' => $newT, ':id' => $playerId]);
        $message = 'You won 2h of NPC booster';
    } elseif ($rand < 45) {
        // 20%: 80-120 Xenomit
        $amount = mt_rand(80, 120);
        $req = $db->prepare("UPDATE player_cargo SET xenomit = xenomit + :amt WHERE id = :id");
        $req->execute([':amt' => $amount, ':id' => $playerId]);
        $message = 'You won ' . $amount . ' Xenomits';
    } elseif ($rand < 70) {
        // 25%: +4h dmg/shd/hp boosters
        $sth = $db->prepare("SELECT booster_dmg_time, booster_shd_time, booster_hp_time FROM users WHERE id = :id LIMIT 1");
        $sth->execute([':id' => $playerId]);
        $row = $sth->fetch(PDO::FETCH_ASSOC);

        $now = time();
        $add4h = 4 * 3600;

        $curD = isset($row['booster_dmg_time']) ? (int)$row['booster_dmg_time'] : 0;
        $curS = isset($row['booster_shd_time']) ? (int)$row['booster_shd_time'] : 0;
        $curH = isset($row['booster_hp_time'])  ? (int)$row['booster_hp_time']  : 0;

        $tD = ($curD > $now) ? $curD + $add4h : $now + $add4h;
        $tS = ($curS > $now) ? $curS + $add4h : $now + $add4h;
        $tH = ($curH > $now) ? $curH + $add4h : $now + $add4h;

        $req = $db->prepare("
            UPDATE users
               SET booster_dmg_time = :dmg,
                   booster_shd_time = :shd,
                   booster_hp_time  = :hp
             WHERE id = :id
        ");
        $req->execute([':dmg' => $tD, ':shd' => $tS, ':hp' => $tH, ':id' => $playerId]);

        $message = 'You won 4h of health/damage and shield booster';
    } elseif ($rand < 80) {
        // 10%: +500 Promerium
        $req = $db->prepare("UPDATE player_cargo SET promerium = promerium + 500 WHERE id = :id");
        $req->execute([':id' => $playerId]);
        $message = 'You won 500 Promeriums';
    } else {
        // 20%: +2h speed booster
        $sth = $db->prepare("SELECT booster_spd_time FROM users WHERE id = :id LIMIT 1");
        $sth->execute([':id' => $playerId]);
        $row = $sth->fetch(PDO::FETCH_ASSOC);

        $now = time();
        $cur  = isset($row['booster_spd_time']) ? (int)$row['booster_spd_time'] : 0;
        $newT = ($cur > $now) ? $cur + 2 * 3600 : $now + 2 * 3600;

        $req = $db->prepare("UPDATE users SET booster_spd_time = :t WHERE id = :id");
        $req->execute([':t' => $newT, ':id' => $playerId]);
        $message = 'You won 2h of speed booster';
    }

    // 7) Get fresh tickets left
    $sth = $db->prepare("SELECT tickets FROM users_infos WHERE id = :id LIMIT 1");
    $sth->execute([':id' => $playerId]);
    $row = $sth->fetch(PDO::FETCH_ASSOC);
    $left = isset($row['tickets']) ? (int)$row['tickets'] : max(0, $tickets - 1);

    echo $message . ' (' . $left . ' tickets left)';

} catch (Throwable $e) {
    header('Content-Type: text/plain; charset=utf-8');
    http_response_code(500);
    echo "Lottery error: " . $e->getMessage();
    exit();
}
