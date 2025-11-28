<?php
$sth = $db->prepare("SELECT username, grade, factionid, clanid, credits, uridium, rankpoints, user_kill, npc_kill, max_hp, speed, damages, 
max_shield, drones, apis_built, zeus_built, dmg_lvl, hp_lvl, shd_lvl, speed_lvl, logfiles, booty_keys, drone_parts, skilltree, booster_dmg_time,
booster_shd_time, booster_spd_time, booster_npc_time, shipId 
FROM users WHERE id = :id LIMIT 1");
$sth->execute(array(':id' => $_SESSION['player_id']));
$datauser = $sth->fetchAll();

require_once('./libs/Laboratory.php');
$lab = new Laboratory($_SESSION['player_id'], $datauser[0]['skilltree'], $datauser[0]['logfiles'], $db );

if(isset($_GET['buy'])) {
    $buymessage = buy($_GET['buy'],$datauser,$lab,$db);
    // update infos
    $sth = $db->prepare("SELECT username, grade, factionid, clanid, credits, uridium, user_kill, npc_kill, max_hp, speed, damages, 
    max_shield, drones, apis_built, zeus_built, dmg_lvl, hp_lvl, shd_lvl, speed_lvl, logfiles, booty_keys, drone_parts, skilltree, booster_dmg_time,
    booster_shd_time, booster_spd_time, booster_npc_time, shipId
    FROM users WHERE id = :id LIMIT 1");
    $sth->execute(array(':id' => $_SESSION['player_id']));
    $datauser = $sth->fetchAll();
    $lab->userlogfiles = $datauser[0]['logfiles'];
    $lab->skills = $lab->load_skills($datauser[0]['skilltree']);	
}
?>

<div class="box" style="margin-left: -10px;">
    <div class="title">Ship upgrades</div>
    <div id="user-upgrades">

        <!-- 💚 Health Upgrade -->
        <div class="bar-stat">
            <div class="bar-stat-title tooltip">
                Health
                <span>
                    <img class="callout" src="img/callout.gif" />
                    <strong>Health upgrade:</strong><br />
                    Increase health by <font color='green'>5,000</font> per point
                </span>
            </div>
            <div class="bar-stat-content">
                <div class="bar-stat-content-bar"><?php create_bar(20, $datauser[0]['hp_lvl'], 13, 10); ?></div>
                <div class="bar-stat-content-number"><?=number_format($datauser[0]['hp_lvl'])?>/20</div>
                <div class="bar-stat-content-buy">
                    <a class="buy tooltip" href="view.php?page=user&tab=upgrades&buy=healt_upgrade">
                        Buy
                        <span>
                            <img class="callout" src="img/callout.gif" />
                            <strong>Health upgrade(next point):</strong><br />
                            Uridium: <font color='magenta'><?=number_format(pow($datauser[0]['hp_lvl']*20, 2) + 100)?></font>
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 🧬 Skills (Laboratory) -->
        <div class="bar-stat">
            <div class="bar-stat-title tooltip">
                Lazer skill
                <span>
                    <img class="callout" src="img/callout.gif" />
                    <strong>Lazer skill:</strong><br />
                    <?= $lab->get_skill_description("dmg"); ?>
                </span>
            </div>
            <div class="bar-stat-content">
                <div class="bar-stat-content-bar"><?php create_bar(5, $lab->skills["dmg"], 58, 10); ?></div>
                <div class="bar-stat-content-number"><?=$lab->skills["dmg"]?>/5</div>
                <div class="bar-stat-content-buy">
                    <a class="buy tooltip" href="view.php?page=user&tab=upgrades&buy=dmgskill">
                        Buy
                        <span>
                            <img class="callout" src="img/callout.gif" />
                            <strong>Lazer skill:</strong><br />
                            Logfiles: <font color='magenta'><?=number_format($lab->get_skill_Prix('dmg'))?></font>
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <div class="bar-stat">
            <div class="bar-stat-title tooltip">
                Rocket skill
                <span>
                    <img class="callout" src="img/callout.gif" />
                    <strong>Rocket skill:</strong><br />
                    <?= $lab->get_skill_description("rck"); ?>
                </span>
            </div>
            <div class="bar-stat-content">
                <div class="bar-stat-content-bar"><?php create_bar(5, $lab->skills["rck"], 58, 10); ?></div>
                <div class="bar-stat-content-number"><?=$lab->skills["rck"]?>/5</div>
                <div class="bar-stat-content-buy">
                    <a class="buy tooltip" href="view.php?page=user&tab=upgrades&buy=rckskill">
                        Buy
                        <span>
                            <img class="callout" src="img/callout.gif" />
                            <strong>Rocket skill(next level):</strong><br />
                            Logfiles: <font color='magenta'><?=number_format($lab->get_skill_Prix('rck'))?></font>
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <div class="bar-stat">
            <div class="bar-stat-title tooltip">
                Hull skill
                <span>
                    <img class="callout" src="img/callout.gif" />
                    <strong>Hull skill:</strong><br />
                    <?= $lab->get_skill_description("hp"); ?>
                </span>
            </div>
            <div class="bar-stat-content">
                <div class="bar-stat-content-bar"><?php create_bar(3, $lab->skills["hp"], 98, 10); ?></div>
                <div class="bar-stat-content-number"><?=$lab->skills["hp"]?>/3</div>
                <div class="bar-stat-content-buy">
                    <a class="buy tooltip" href="view.php?page=user&tab=upgrades&buy=hpskill">
                        Buy
                        <span>
                            <img class="callout" src="img/callout.gif" />
                            <strong>Hull skill:</strong><br />
                            Logfiles: <font color='magenta'><?=number_format($lab->get_skill_Prix('hp'))?></font>
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <div class="bar-stat">
            <div class="bar-stat-title tooltip">
                Shield skill
                <span>
                    <img class="callout" src="img/callout.gif" />
                    <strong>Shield skill:</strong><br />
                    <?= $lab->get_skill_description("shd_abs"); ?>
                </span>
            </div>
            <div class="bar-stat-content">
                <div class="bar-stat-content-bar"><?php create_bar(3, $lab->skills["shd_abs"], 98, 10); ?></div>
                <div class="bar-stat-content-number"><?=$lab->skills["shd_abs"]?>/3</div>
                <div class="bar-stat-content-buy">
                    <a class="buy tooltip" href="view.php?page=user&tab=upgrades&buy=shd_absskill">
                        Buy
                        <span>
                            <img class="callout" src="img/callout.gif" />
                            <strong>Shield skill:</strong><br />
                            Logfiles: <font color='magenta'><?=number_format($lab->get_skill_Prix('shd_abs'))?></font>
                        </span>
                    </a>
                </div>
            </div>
        </div>	

        <div class="bar-stat">
            <div class="bar-stat-title tooltip">
                Regeneration skill
                <span>
                    <img class="callout" src="img/callout.gif" />
                    <strong>Shield regeneration skill:</strong><br />
                    <?= $lab->get_skill_description("shreg"); ?>
                </span>
            </div>
            <div class="bar-stat-content">
                <div class="bar-stat-content-bar"><?php create_bar(5, $lab->skills["shreg"], 58, 10); ?></div>
                <div class="bar-stat-content-number"><?=$lab->skills["shreg"]?>/5</div>
                <div class="bar-stat-content-buy">
                    <a class="buy tooltip" href="view.php?page=user&tab=upgrades&buy=shregskill">
                        Buy
                        <span>
                            <img class="callout" src="img/callout.gif" />
                            <strong>Shield regeneration skill:</strong><br />
                            Logfiles: <font color='magenta'><?=number_format($lab->get_skill_Prix('shreg'))?></font>
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <div class="bar-stat">
            <div class="bar-stat-title tooltip">
                Repair skill
                <span>
                    <img class="callout" src="img/callout.gif" />
                    <strong>Repair skill:</strong><br />
                    <?= $lab->get_skill_description("rep"); ?>
                </span>
            </div>
            <div class="bar-stat-content">
                <div class="bar-stat-content-bar"><?php create_bar(3, $lab->skills["rep"], 98, 10); ?></div>
                <div class="bar-stat-content-number"><?=$lab->skills["rep"]?>/3</div>
                <div class="bar-stat-content-buy">
                    <a class="buy tooltip" href="view.php?page=user&tab=upgrades&buy=repskill">
                        Buy
                        <span>
                            <img class="callout" src="img/callout.gif" />
                            <strong>Repair skill:</strong><br />
                            Logfiles: <font color='magenta'><?=number_format($lab->get_skill_Prix('rep'))?></font>
                        </span>
                    </a>
                </div>
            </div>
        </div>	

        <div class="bar-stat">
            <div class="bar-stat-title tooltip">
                Smartbomb skill
                <span>
                    <img class="callout" src="img/callout.gif" />
                    <strong>Smartbomb skill:</strong><br />
                    <?= $lab->get_skill_description("smb"); ?>
                </span>
            </div>
            <div class="bar-stat-content">
                <div class="bar-stat-content-bar"><?php create_bar(2, $lab->skills["smb"], 147, 10); ?></div>
                <div class="bar-stat-content-number"><?=$lab->skills["smb"]?>/2</div>
                <div class="bar-stat-content-buy">
                    <a class="buy tooltip" href="view.php?page=user&tab=upgrades&buy=smbskill">
                        Buy
                        <span>
                            <img class="callout" src="img/callout.gif" />
                            <strong>Smartbomb skill:</strong><br />
                            Logfiles: <font color='magenta'><?=number_format($lab->get_skill_Prix('smb'))?></font>
                        </span>
                    </a>
                </div>
            </div>
        </div>			
    </div>
</div>	

<?php 
function create_bar($size, $progress, $elementWidth, $elementHeight)
{
    $i=0;
    while($i < $progress) {
        echo '<div class="barUp" style="width: '.$elementWidth.'px; height: '.$elementHeight.'px;"></div>';
        $i++;
    }
    while($i < $size) {
        echo '<div class="barDown" style="width: '.$elementWidth.'px; height: '.$elementHeight.'px;"></div>';
        $i++;
    }
}

function buy($item,$datauser,$lab,$db)
{
    if($item == 'healt_upgrade')
    {
        $hp_upgrade = array("level" => $datauser[0]['hp_lvl'], "price" => pow($datauser[0]['hp_lvl']*20, 2) + 100);
        if($datauser[0]['uridium'] >= $hp_upgrade["price"] and $hp_upgrade["level"] < 20)
        {
            $req = $db->prepare('UPDATE users SET uridium=uridium-'.$hp_upgrade["price"].' WHERE id='.$_SESSION['player_id']);
            if($req->execute())
            {
                $req = $db->prepare('UPDATE users SET max_hp=max_hp+5000, hp_lvl=hp_lvl+1 WHERE id='.$_SESSION['player_id']);
                if($req->execute())
                {
                    return "Purchase success !";
                }
            }
        }
        else
        {
            return "Error : Not enough uridium or maximum level reached !";
        }  
    }

    else if($item == 'dmgskill' || $item == 'hpskill' || $item == 'shd_absskill' || $item == 'repskill' || $item == 'smbskill' || $item == 'rckskill' || $item == 'shregskill')
    {
        $skill_map = [
            'dmgskill' => 'dmg',
            'hpskill' => 'hp',
            'shd_absskill' => 'shd_abs',
            'repskill' => 'rep',
            'smbskill' => 'smb',
            'rckskill' => 'rck',
            'shregskill' => 'shreg'
        ];
        $skill = $skill_map[$item];
        if($lab->buy_skill($skill)) {
            return "Purchase success !";
        } else {
            return "Error : Not enough logfiles or maximum level reached!";
        }
    }
}
?>
