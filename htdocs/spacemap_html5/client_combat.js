    // -------------------------------------------------

    function categorizeEntityFromType(e) {
        const meta = OBJECT_TYPE_META[e.type];

        if (!meta) {
            if (!loggedObjectTypes.has(e.type)) {
                loggedObjectTypes.add(e.type);
            }
            if (!e.category || e.category === "unknown") {
                e.category = "other";
            }
            return;
        }

        if (meta.kind) e.kind = meta.kind;
        else if (e.kind === "unknown") e.kind = "box";

        e.category = meta.category || e.category || "other";
    }

    function isEntityVisibleOnMap(e) {
        if (e.kind === "player" || e.kind === "npc") return true;

        if (e.kind === "box") {
            switch (e.category) {
                case "bonusBox":
                case "bootyBox":
                    return VISIBILITY_SETTINGS.bonusBoxes;
                case "cargoFree":
                    return VISIBILITY_SETTINGS.freeCargo;
                case "cargoNotFree":
                    return VISIBILITY_SETTINGS.notFreeCargo;
                case "ore":
                    return VISIBILITY_SETTINGS.ore;
                case "beacon":
                    return VISIBILITY_SETTINGS.beacons;
                case "mine":
                    return VISIBILITY_SETTINGS.mines;
                case "buffBox":
                case "bootyKey":
                default:
                    return VISIBILITY_SETTINGS.others;
            }
        }
        return true;
    }

    function getEntityColor(e) {
        if (e.kind === "player") {
            if (window.heroFactionId && e.factionId) {
                if (e.factionId === window.heroFactionId) {
                    return "#0099ff"; // BLEU
                } else {
                    return "#ff0000"; // ROUGE
                }
            }
            return "orange";
        }
        if (e.kind === "npc")    return "red";

        if (e.kind === "box") {
            switch (e.category) {
                case "bonusBox":     return "yellow";
                case "bootyBox":     return "gold";
                case "cargoFree":    return "lime";
                case "cargoNotFree": return "red";
                case "ore":          return "cyan";
                case "beacon":       return "magenta";
                case "mine":         return "purple";
                case "buffBox":      return "deepskyblue";
                case "bootyKey":     return "white";
                default:             return "yellow";
            }
        }
        return "white";
    }

    function getNameplateColor(e) {
        if (!e) return "#ffffff";

        // Priorité : NPC toujours rouge
        if (e.kind === "npc") return "#ff0000";

        // Le héros reste toujours blanc
        if (e.id === heroId) return "#ffffff";

        // Par défaut pour les joueurs
        if (e.kind === "player") {
            const sameClan = heroClanTag && e.clanTag && heroClanTag === e.clanTag;
            if (sameClan) return "#00ff00";

            if (window.heroFactionId && e.factionId) {
                if (e.factionId === window.heroFactionId) return "#0099ff";
                return "#ff0000";
            }
        }

        return "#ffffff";
    }

    function getHeroIdleOffset() {
        if (moveTargetX !== null || moveTargetY !== null) return 0;
        const now = performance.now();
        const idleDuration = now - heroLastMoveMs;
        if (idleDuration < 150) return 0;
        return Math.sin(now / 600) * 3;
    }

    const NAMEPLATE_OFFSET = 6;

    // Gestion locale des reprises d'attaque (logique Flash)
    const AUTO_RESUME_INTERVAL_MS = 300;
    let lastAutoLaserResumeMs = 0;

    function computeNameplateY(centerY, spriteHeight) {
        const h = Math.max(10, Math.min(spriteHeight || 10, 60));
        return centerY + h * 0.45 + NAMEPLATE_OFFSET;
    }

    // -------------------------------------------------
    // 5. COMMANDES VERS SERVEUR
    // -------------------------------------------------
	
	// Fonction d'envoi des paramètres (Paquet 7)
    function sendSetting(key, value) {
        if (!ws || ws.readyState !== WebSocket.OPEN) return;
        
        const keyUpper = key.toUpperCase();
        const packet = `7|${keyUpper}|${value}`;
        
        console.log("[WS] Envoi SETTING →", packet);
        sendRaw(packet);
        
        // Mettre à jour l'état local immédiatement
        updateLocalSetting(keyUpper, value);
    }
	// Met à jour l'état local du client (simule la réponse serveur)
        // Met à jour l'état local du client (simule la réponse serveur)
    function updateLocalSetting(key, value) {
        const val    = parseInt(value, 10);
        const valStr = String(value).toUpperCase(); // Pour les chaînes

        switch (key) {
            case 'SHOW_DRONES':
                // Le serveur envoie '1' ou '0'. La variable est un booléen.
                setting_show_drones = (val === 1);
                break;

            case 'PLAY_SFX':
                setting_play_sfx = (val === 1);
                break;

            case 'PLAY_MUSIC':
                setting_play_music = (val === 1);
                break;

            case 'MINIMAP_SCALE':
                // FULL_MERGE_AS : MinimapManager.scaleFactor (int), par défaut 8.
                // On l'applique directement comme facteur de zoom interne (bornes 3..11)
                if (!isNaN(val) && val > 0) {
                    setMinimapScale(val, { forceSend: false });
                    console.log("[SETTINGS] MINIMAP_SCALE reçu → scale =", val);
                }
                break;

            case 'CLIENT_RESOLUTION':
                // Le serveur envoie "ID,WIDTH,HEIGHT" ou "ID|WIDTH|HEIGHT".
                console.log("[SETTINGS] CLIENT_RESOLUTION =", value);
                applyClientResolution(value);
                break;

            default:
                // Pour ne pas ignorer les autres paramètres envoyés par le serveur
                console.log(`[SETTINGS] Paramètre stocké: ${key} = ${value}`);
                break;
        }
    }

	function sendSellOre(oreType, amount) {
        if (!chatWs || chatWs.readyState !== WebSocket.OPEN || amount <= 0) return;

        // Le serveur attend l'ID du minerai (1=prometium, 2=endurium, 3=terbium, etc.)
        const oreIdMap = { 'prometium': 1, 'endurium': 2, 'terbium': 3, 'palladium': 5, 'prometid': 11, 'duranium': 12, 'promerium': 13 };
        const oreId = oreIdMap[oreType.toLowerCase()];

        if (oreId) {
            // Le serveur T attend : T|ORE_ID|AMOUNT
            const packet = `T|${oreId}|${amount}`; 
            console.log("[WS] Envoi VENTE →", packet);
            // On envoie sur le Chat Socket car le handler est dans le flux de gestion DB/Chat
            chatWs.send(packet + "\n"); 
            addInfoMessage(`Vente de ${amount} ${oreType} demandée.`);
        }
    }

    function sendProduce(productId, amount) {
        if (!chatWs || chatWs.readyState !== WebSocket.OPEN || amount <= 0) return;

        // Le serveur attend : LAB|REF|PROD|PRODUCT_ID|AMOUNT
        // Note: Selon le code C#, cela va consommer l'Uridium/Crédit et les minerais
        const packet = `LAB|REF|PROD|${productId}|${amount}`; 
        console.log("[WS] Envoi PRODUCTION →", packet);
        chatWs.send(packet + "\n");
        addInfoMessage(`Production de ${amount} unités demandée.`);
    }
	
    function sendMoveToServer(x, y) {
        if (!ws || ws.readyState !== WebSocket.OPEN) {
            console.warn("[WS] Move ignoré, WS non connecté");
            return;
        }
        let ix = Math.round(x);
        let iy = Math.round(y);
        const moveMinX = MAP_MIN_X - RADIATION_MARGIN;
        const moveMaxX = MAP_MAX_X + RADIATION_MARGIN;
        const moveMinY = MAP_MIN_Y - RADIATION_MARGIN;
        const moveMaxY = MAP_MAX_Y + RADIATION_MARGIN;
        ix = Math.max(moveMinX, Math.min(moveMaxX, ix));
        iy = Math.max(moveMinY, Math.min(moveMaxY, iy));
        const packet = `1|${ix}|${iy}|${ix}|${iy}`;
        console.log("[WS] Envoi move →", packet);
        sendRaw(packet);
    }
    
    function sendPortalJump() {
        pendingCollectBoxId = null;
        if (!ws || ws.readyState !== WebSocket.OPEN) return;
        const packet = "j";
        console.log("[WS] Envoi PORTAL_JUMP →", packet);
        sendRaw(packet);
    }

    function sendSelectShip(targetId) {
        if (!ws || ws.readyState !== WebSocket.OPEN) return;
        if (targetId == null) return;
        const packet = `SES|${targetId}`;
        console.log("[WS] Envoi SES →", packet);
        sendRaw(packet);
    }

    function sendLaserAttack(targetId) {
        pendingCollectBoxId = null;
        if (!ws || ws.readyState !== WebSocket.OPEN) return;
        if (targetId == null) return;
        const packet = `a|${targetId}`;
        console.log("[WS] Envoi LASER_ATTACK →", packet);
        currentLaserTargetId = targetId;
        attackIntentTargetId = targetId;
        resetPendingRangeResume();
        sendRaw(packet);
    }

    function sendLaserStop(targetId, force = false, keepIntent = false) {
        if (!ws || ws.readyState !== WebSocket.OPEN) return;
        if (targetId == null) return;
        if (!force && rangeProtectedTargetId === targetId) return;

        const packet = `G|${targetId}`;
        console.log("[WS] Envoi LASER_STOP →", packet);

        if (currentLaserTargetId === targetId) currentLaserTargetId = null;
        if (!keepIntent && attackIntentTargetId === targetId) attackIntentTargetId = null;

        if (!keepIntent) {
            resetPendingRangeResume(targetId);
        }

        sendRaw(packet);
    }

    function sendRocketAttack(targetId) {
        pendingCollectBoxId = null;
        if (!ws || ws.readyState !== WebSocket.OPEN) return;
        if (targetId == null) return;
        const packet = `v|${targetId}`;
        console.log("[WS] Envoi ROCKET_ATTACK →", packet);
        sendRaw(packet);
    }

    function sendRepairCommand() {
        if (!ws || ws.readyState !== WebSocket.OPEN) return;
        const packet = "S|ROB";
        sendRaw(packet);
    }

    function sendCollectBox(id) {
        if (!ws || ws.readyState !== WebSocket.OPEN) return;
        if (id == null) return;
        const packet = `x|${id}`;
        console.log("[WS] Envoi COLLECT →", packet);
        sendRaw(packet);
    }

    function sendSelectAmmo(ammoId) {
        if (!ws || ws.readyState !== WebSocket.OPEN) return;
        if (ammoId == null) return;
        const packet = `u|${ammoId}`;
        sendRaw(packet);
        addInfoMessage("Laser ammo = " + ammoId);
        currentAmmoId = ammoId; 
		if (actionDrawerCategory === "laser") {
            renderActionDrawerItems();
        }
    }

    function sendSelectRocket(rocketId) {
        if (!ws || ws.readyState !== WebSocket.OPEN) return;
        if (rocketId == null) return;
        const packet = `d|${rocketId}`;
        sendRaw(packet);
        addInfoMessage("Rocket = " + rocketId);
        currentRocketId = rocketId;
		if (actionDrawerCategory === "rocket") {
            renderActionDrawerItems();
        }
    }

    function sendTechActivation(techId) {
        if (!ws || ws.readyState !== WebSocket.OPEN) return;
        if (techId == null) return;
        const packet = `TX|${techId}`;
        sendRaw(packet);
        addInfoMessage("Tech activée : " + techId);
    }

    function sendChangeConfig(configId) {
        if (!ws || ws.readyState !== WebSocket.OPEN) return;
        if (configId !== 1 && configId !== 2) return;
        const packet = `S|CFG|${configId}`;
        sendRaw(packet);
        heroConfig = configId;
        addInfoMessage("Configuration " + configId + " demandée");
    }

    function sendCpuAction(code) {
        if (!ws || ws.readyState !== WebSocket.OPEN) return;
        if (!code) return;

        // Le serveur C# attend "S|ROB" pour le robot de réparation
        // Ou S|ISH, S|SMB, etc.
        const packet = `S|${code}`;

        sendRaw(packet);
        addInfoMessage("CPU activé : " + code);

        // Dans le client Flash, l'ISH s'affiche immédiatement lors de l'activation locale
        // (le serveur diffuse ensuite l'état). On réplique ce comportement pour le héros.
        if (code === "ISH") {
            setHeroShieldEffect("ISH", true, ISH_DURATION_MS);
        }
    }
	
	    function sendGroupPing(targetX, targetY) {
        if (!ws || ws.readyState !== WebSocket.OPEN) return;

        const x = Math.round(targetX);
        const y = Math.round(targetY);

        // Format inspiré du Flash : ps|png|pos|x|y
        const packet = `ps|png|pos|${x}|${y}`;
        sendRaw(packet);

        addInfoMessage(`Ping de groupe envoyé : ${x},${y}`);
    }


    // -------------------------------------------------
    // 6. MOUVEMENT LOCAL
    // -------------------------------------------------

    function updateHeroLocalMovement(dt) {
        const prevX = shipX;
        const prevY = shipY;

        if (moveTargetX === null || moveTargetY === null) {
            heroLastPosX = shipX;
            heroLastPosY = shipY;
            return;
        }

        const dx = moveTargetX - shipX;
        const dy = moveTargetY - shipY;
        const dist = Math.hypot(dx, dy);
        let arrivedThisFrame = false;

        // NOUVEAU : mettre à jour l'angle du vaisseau vers la cible
		if (dist > 0.0001) {
			heroAngle = Math.atan2(dy, dx) + Math.PI; // +180°
		}


        if (dist < 1) {

            shipX = moveTargetX;
            shipY = moveTargetY;
            moveTargetX = null;
            moveTargetY = null;
            arrivedThisFrame = true;
        } else {
            const maxStep = heroSpeed * dt;
            if (maxStep >= dist) {
                shipX = moveTargetX;
                shipY = moveTargetY;
                moveTargetX = null;
                moveTargetY = null;
                arrivedThisFrame = true;
            } else {
                const nx = dx / dist;
                const ny = dy / dist;
                shipX += nx * maxStep;
                shipY += ny * maxStep;
            }

            const moveMinX = MAP_MIN_X - RADIATION_MARGIN;
            const moveMaxX = MAP_MAX_X + RADIATION_MARGIN;
            const moveMinY = MAP_MIN_Y - RADIATION_MARGIN;
            const moveMaxY = MAP_MAX_Y + RADIATION_MARGIN;
            shipX = Math.max(moveMinX, Math.min(moveMaxX, shipX));
            shipY = Math.max(moveMinY, Math.min(moveMaxY, shipY));
        }

        if (arrivedThisFrame && pendingCollectBoxId !== null) {
            console.log("[MOVE] Arrivé sur cible, envoi collecte pending id=", pendingCollectBoxId);
            sendCollectBox(pendingCollectBoxId);
            pendingCollectBoxId = null;
        }

        if (Math.abs(shipX - prevX) > 0.01 || Math.abs(shipY - prevY) > 0.01) {
            heroLastMoveMs = performance.now();
            heroLastPosX = shipX;
            heroLastPosY = shipY;
        }
    }
	
    function updateChaseMovement() {
        // On regarde si on a une intention d'attaque (mémorisée lors du packet O ou Ctrl)
        const targetId = attackIntentTargetId;
        if (targetId == null) return;

        // On récupère la cible
        const target = targetId === heroId ? { x: shipX, y: shipY } : entities[targetId];
        
        // Si la cible n'existe plus (déco, morte), on nettoie tout
        if (!target) {
            attackIntentTargetId = null;
            isChasingTarget = false;
            resetPendingRangeResume();
            return;
        }

        const dx = target.x - shipX;
        const dy = target.y - shipY;
        const dist = Math.hypot(dx, dy);
        const attackRange = LASER_MAX_RANGE; // Environ 700-900 selon ta config

        // CAS 1 : On est ENCORE trop loin
        if (dist > attackRange) {
            if (pendingRangeResumeTargetId == null) {
                pendingRangeResumeTargetId = targetId;
                pendingRangeResumeMessage = true;
            }

            rangeProtectedTargetId = targetId;

            if (currentLaserTargetId === targetId) {
                // On arrête de tirer mais on conserve l'intention (comme le client Flash)
                sendLaserStop(targetId, false, true);
                currentLaserTargetId = null;
            }
            return; // On ne fait rien, on attend de se rapprocher
        }

        // CAS 2 : On est revenu à portée !
        // Si on ne tire pas encore (currentLaserTargetId est null à cause du Packet O), on relance !
        if (currentLaserTargetId !== targetId) {
            // On envoie l'attaque
            sendLaserAttack(targetId);
            
            // Petit message visuel (optionnel, comme sur Flash)
            if (pendingRangeResumeTargetId === targetId && pendingRangeResumeMessage) {
                addInfoMessage("Attack running"); // "L'attaque reprend"
                pendingRangeResumeMessage = false;
            }
            
            // On nettoie les flags d'attente
            resetPendingRangeResume(targetId);
            if (rangeProtectedTargetId === targetId) rangeProtectedTargetId = null;
        }
    }

    // -------------------------------------------------
    // 7. INTERPOLATION & COMBAT
    // -------------------------------------------------

    function updateInterpolations() {
        const now = performance.now();
        for (const id in entities) {
            const e = entities[id];
            const p = e.interp;
            if (!p || p.duration <= 0) continue;

            // Position avant la mise à jour (pour calculer la direction)
            const oldX = e.x;
            const oldY = e.y;

            const t = (now - p.startTime) / p.duration;
            if (t >= 1) {
                e.x = p.endX;
                e.y = p.endY;
                p.duration = 0;
            } else if (t >= 0) {
                e.x = p.startX + (p.endX - p.startX) * t;
                e.y = p.startY + (p.endY - p.startY) * t;
            }

            // Nouveau : calcul de l'angle quand l'entité se déplace
            const dx = e.x - oldX;
            const dy = e.y - oldY;
            if (dx * dx + dy * dy > 0.1) {
                // Même logique que pour heroAngle : on ajoute PI pour aligner avec les sprites
                e.angle = Math.atan2(dy, dx) + Math.PI;
            }
        }
    }


    function updateCombat() {
        if (heroHp !== null && heroHp <= 0) {
            if (currentLaserTargetId != null) {
                sendLaserStop(currentLaserTargetId, true);
            }
            return;
        }

        const targetId = attackIntentTargetId ?? currentLaserTargetId;
        if (targetId == null) return;

        const target = targetId === heroId ? { x: shipX, y: shipY } : entities[targetId];
        if (!target) {
            if (currentLaserTargetId === targetId) currentLaserTargetId = null;
            if (attackIntentTargetId === targetId) attackIntentTargetId = null;
            resetPendingRangeResume(targetId);
            return;
        }

        const dx = target.x - shipX;
        const dy = target.y - shipY;
        const dist = Math.hypot(dx, dy);
        const now = performance.now();

        if (dist > LASER_MAX_RANGE) {
            // Prépare la reprise auto comme sur le client Flash
            if (pendingRangeResumeTargetId == null) {
                pendingRangeResumeTargetId = targetId;
                pendingRangeResumeMessage = true;
            }
            rangeProtectedTargetId = targetId;

            if (currentLaserTargetId === targetId) {
                sendLaserStop(targetId, false, true);
            }
            currentLaserTargetId = null;
            return;
        }

        // Si nous avons une intention mais que le tir est coupé (packet O reçu), on reprend
        const shouldResume = attackIntentTargetId === targetId && currentLaserTargetId == null;
        if (shouldResume && now - lastAutoLaserResumeMs >= AUTO_RESUME_INTERVAL_MS) {
            sendLaserAttack(targetId);
            lastAutoLaserResumeMs = now;

            if (pendingRangeResumeTargetId === targetId && pendingRangeResumeMessage) {
                addInfoMessage("The battle continues");
                pendingRangeResumeMessage = false;
            }
            resetPendingRangeResume(targetId);
            if (rangeProtectedTargetId === targetId) rangeProtectedTargetId = null;
        }
    }
	
	// --- GESTION DES ROTATIONS DE COMBAT (AJOUT) ---
    function updateCombatRotations() {
        // 1. HÉROS : Regarde sa cible s'il tire
        if (currentLaserTargetId !== null) {
            const target = entities[currentLaserTargetId];
            if (target) {
                const dx = target.x - shipX;
                const dy = target.y - shipY;
                // On force l'angle vers la cible (+ Math.PI pour corriger l'orientation)
                heroAngle = Math.atan2(dy, dx) + Math.PI;
            }
        }

        // 2. ENNEMIS : Regardent le héros s'ils tirent dessus
        // (activeLasers doit avoir été défini tout en haut du fichier comme demandé avant)
        if (typeof activeLasers !== 'undefined') {
            for (let i = 0; i < activeLasers.length; i++) {
                const laser = activeLasers[i];
                
                // Si la cible du laser est le Héro (Moi)
                if (laser.targetId === heroId) {
                    const attacker = entities[laser.shooterId];
                    // Si l'attaquant existe et est visible
                    if (attacker) {
                        const dx = shipX - attacker.x;
                        const dy = shipY - attacker.y;
                        // L'ennemi pivote pour nous regarder
                        attacker.angle = Math.atan2(dy, dx) + Math.PI;
                    }
                }
            }
        }
    }

    function reinforceLockState() {
        const candidate =
            selectedTargetId !== null ? selectedTargetId :
            (currentLaserTargetId !== null ? currentLaserTargetId :
            (pendingRangeResumeTargetId !== null ? pendingRangeResumeTargetId : attackIntentTargetId));

        if (candidate != null) {
            if (selectedTargetId === null) selectedTargetId = candidate;
            if (attackIntentTargetId === null && currentLaserTargetId !== null) {
                attackIntentTargetId = currentLaserTargetId;
            } else if (attackIntentTargetId !== null && currentLaserTargetId === null) {
                currentLaserTargetId = attackIntentTargetId;
            }
        }
    }


    // -------------------------------------------------
    // 8. EFFETS VISUELS
    // -------------------------------------------------

    function updateLaserBeams(now) {
        for (let i = laserBeams.length - 1; i >= 0; i--) {
            const beam = laserBeams[i];
            if (now - beam.createdAt > LASER_BEAM_DURATION) {
                laserBeams.splice(i, 1);
            }
        }
    }

    function updateRocketAttacks(now) {
        for (let i = rocketAttacks.length - 1; i >= 0; i--) {
            if (now - rocketAttacks[i].createdAt > ROCKET_BEAM_DURATION) {
                rocketAttacks.splice(i, 1);
            }
        }
    }
	
    function getLaserOffsetsForShip(entityId) {
        let size = 10;
        const snap = snapshotEntityById(entityId);
        if (snap && snap.shipId && SHIP_SPRITE_DEFS[snap.shipId]) {
            const img = getShipSpriteFrame(snap.shipId, 0);
            if (img && img.complete && img.width > 0) {
                size = img.width / 4;
            }
        }
        return Math.max(6, Math.min(18, size));
    }

    function drawLaserBeams() {
        const now = performance.now();

        for (const beam of laserBeams) {
            let ax, ay, tx, ty;

            if (heroId !== null && beam.attackerId === heroId) {
                ax = shipX; ay = shipY;
            } else if (entities[beam.attackerId]) {
                ax = entities[beam.attackerId].x;
                ay = entities[beam.attackerId].y;
            } else continue;

            if (heroId !== null && beam.targetId === heroId) {
                tx = shipX; ty = shipY;
            } else if (entities[beam.targetId]) {
                tx = entities[beam.targetId].x;
                ty = entities[beam.targetId].y;
            } else continue;

            const startScreenX = mapToScreenX(ax);
            const startScreenY = mapToScreenY(ay);
            const endScreenX = mapToScreenX(tx);
            const endScreenY = mapToScreenY(ty);

            let color = "#ff0000";
            let width = 2;

            switch(beam.patternId) {
                case 1: color = "#ff0000"; break;
                case 2: color = "#0000ff"; break;
                case 3: color = "#00ff00"; break;
                case 4: color = "#ffffff"; width = 3; break;
                case 5: color = "#aaaaaa"; break;
                case 6: color = "#ffff00"; width = 3; break;
                default: color = "#ff0000"; break;
            }

            const life = (now - beam.createdAt) / LASER_BEAM_DURATION;
            const alpha = Math.max(0, 1 - life);

            const angle = Math.atan2(ty - ay, tx - ax);
            const perpX = Math.cos(angle + Math.PI / 2);
            const perpY = Math.sin(angle + Math.PI / 2);
            const offsetMag = (beam.patternId === 5) ? 0 : getLaserOffsetsForShip(beam.attackerId);

            const drawSegments = offsetMag === 0 ? [{ ox: 0, oy: 0 }] : [
                { ox: perpX * offsetMag, oy: perpY * offsetMag },
                { ox: -perpX * offsetMag, oy: -perpY * offsetMag }
            ];

            ctx.save();
            ctx.globalAlpha = 0.6 * alpha;
            ctx.strokeStyle = color;
            ctx.lineWidth = width;
            ctx.shadowBlur = 10;
            ctx.shadowColor = color;

            for (const seg of drawSegments) {
                ctx.beginPath();
                ctx.moveTo(startScreenX + seg.ox, startScreenY + seg.oy);
                ctx.lineTo(endScreenX + seg.ox, endScreenY + seg.oy);
                ctx.stroke();
            }
            ctx.restore();
        }
    }

    function drawRocketAttacks() {
        const now = performance.now();
        for (const beam of rocketAttacks) {
            let ax, ay, tx, ty;

            if (heroId !== null && beam.attackerId === heroId) {
                ax = shipX; ay = shipY;
            } else if (entities[beam.attackerId]) {
                ax = entities[beam.attackerId].x;
                ay = entities[beam.attackerId].y;
            } else continue;

            if (heroId !== null && beam.targetId === heroId) {
                tx = shipX; ty = shipY;
            } else if (entities[beam.targetId]) {
                tx = entities[beam.targetId].x;
                ty = entities[beam.targetId].y;
            } else continue;

            const progress = Math.min(1, (now - beam.createdAt) / ROCKET_BEAM_DURATION);
            const startScreenX = mapToScreenX(ax);
            const startScreenY = mapToScreenY(ay);
            const endScreenX = mapToScreenX(tx);
            const endScreenY = mapToScreenY(ty);

            const projX = startScreenX + (endScreenX - startScreenX) * progress;
            const projY = startScreenY + (endScreenY - startScreenY) * progress;

            ctx.save();
            ctx.strokeStyle = beam.heavy ? "#ffa500" : "#ffea00";
            ctx.fillStyle = beam.heavy ? "rgba(255,165,0,0.7)" : "rgba(255,234,0,0.7)";
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(startScreenX, startScreenY);
            ctx.lineTo(projX, projY);
            ctx.stroke();
            ctx.beginPath();
            ctx.arc(projX, projY, beam.heavy ? 6 : 4, 0, Math.PI * 2);
            ctx.fill();
            ctx.restore();
        }
    }

    function snapshotEntityById(id) {
        if (heroId !== null && id === heroId) {
            return {
                id: heroId,
                x: shipX,
                y: shipY,
                kind: "player",
                shipId: heroShipId,
                shield: heroShield,
                maxShield: heroMaxShield
            };
        }
        if (entities[id]) {
            const ent = entities[id];
            return {
                id: ent.id,
                x: ent.x,
                y: ent.y,
                kind: ent.kind,
                shipId: ent.shipId,
                shield: ent.shield,
                maxShield: ent.maxShield
            };
        }
        return null;
    }

    function computeShieldImpactRadius(targetSnap) {
        let radius = 40;
        if (targetSnap && targetSnap.shipId && SHIP_SPRITE_DEFS[targetSnap.shipId]) {
            const img = getShipSpriteFrame(targetSnap.shipId, 0);
            if (img && img.complete && img.width > 0 && img.height > 0) {
                radius = Math.max(img.width, img.height) / 2;
            }
        }
        return radius + 10;
    }

    function computeShieldImpactAngle(attackerId, targetId) {
        const attacker = snapshotEntityById(attackerId);
        const target = snapshotEntityById(targetId);
        if (!attacker || !target) return null;
        return Math.atan2(target.y - attacker.y, target.x - attacker.x) + Math.PI;
    }

    function getRecentBeamAngleForTarget(targetId) {
        for (let i = laserBeams.length - 1; i >= 0; i--) {
            const beam = laserBeams[i];
            if (beam.targetId === targetId && beam.angle != null) return beam.angle;
        }
        return null;
    }

    function updateDamageBubbles(now) {
        for (let i = damageBubbles.length - 1; i >= 0; i--) {
            const b = damageBubbles[i];
            if (now - b.createdAt > DAMAGE_BUBBLE_DURATION) {
                damageBubbles.splice(i, 1);
            }
        }
    }

    function resolveDamageBubblePosition(b) {
        if (b.entityId === heroId) {
            return { x: shipX, y: shipY };
        }
        const ent = entities[b.entityId];
        if (ent) {
            return { x: ent.x, y: ent.y };
        }
        return null;
    }

    function drawDamageBubbles() {
        const now = performance.now();
        for (const b of damageBubbles) {
            const pos = resolveDamageBubblePosition(b);
            if (!pos) continue;

            const bubbleScreenX = mapToScreenX(pos.x);
            const bubbleScreenY = mapToScreenY(pos.y);

            const life = (now - b.createdAt) / DAMAGE_BUBBLE_DURATION;
            const alpha = Math.max(0, 1 - life);
            const offsetY = -20 - 30 * life;

            ctx.save();
            ctx.globalAlpha = alpha;
            ctx.font = "14px Consolas, monospace";
            ctx.textAlign = "center";
            ctx.textBaseline = "middle";
            if (b.isHeal) {
                ctx.fillStyle = "#00ff00";
                ctx.fillText(b.value, bubbleScreenX, bubbleScreenY + offsetY);
            } else {
                ctx.fillStyle = "#ff0000";
                ctx.fillText("-" + b.value, bubbleScreenX, bubbleScreenY + offsetY);
            }
            ctx.restore();
        }
    }

    function updateShieldBursts(now) {
        for (let i = shieldBursts.length - 1; i >= 0; i--) {
            const sb = shieldBursts[i];
            const lifeMs = sb.lifeMs || 350;
            if (now - sb.createdAt > lifeMs) {
                shieldBursts.splice(i, 1);
                if (sb.targetId !== undefined && sb.targetId !== null) {
                    if (heroId !== null && sb.targetId === heroId) {
                        heroShieldDamageCount = Math.max(0, heroShieldDamageCount - 1);
                    } else if (entities[sb.targetId]) {
                        const ent = entities[sb.targetId];
                        ent.shieldDamageCount = Math.max(0, ent.shieldDamageCount - 1);
                    }
                }
            }
        }
    }

    function drawShieldBursts() {
        const now = performance.now();
        for (const sb of shieldBursts) {
            const spriteKey = sb.sprite || "hit";
            const def = SHIELD_SPRITE_DEFS[spriteKey];
            if (!def) continue;

            const lifeMs = sb.lifeMs || 350;
            const life = Math.min(1, (now - sb.createdAt) / lifeMs);
            const frame = Math.min(def.frameCount - 1, Math.floor(def.frameCount * life));
            const img = getShieldSpriteFrame(spriteKey, frame);
            if (!img || !img.complete || img.width === 0 || img.height === 0) continue;

            const alpha = 1 - life;
            const scale = 1 + life * 0.2;

            const angle = sb.angle || 0;
            const radius = sb.radius || 0;
            const baseX = sb.x + Math.cos(angle) * radius;
            const baseY = sb.y + Math.sin(angle) * radius;

            const burstScreenX = mapToScreenX(baseX);
            const burstScreenY = mapToScreenY(baseY);
            const w = img.width * scale;
            const h = img.height * scale;

            ctx.save();
            ctx.translate(burstScreenX, burstScreenY);
            if (sb.angle !== undefined && sb.angle !== null) {
                ctx.rotate(angle);
            }
            ctx.globalAlpha = alpha;
            ctx.drawImage(img, -w / 2, -h / 2, w, h);
            ctx.restore();
        }
    }

    function spawnShieldBurstAt(x, y, sprite = "hit", options = {}) {
        if (x == null || y == null) return;
        const def = SHIELD_SPRITE_DEFS[sprite];
        const lifeMs = def ? (def.frameCount / (def.fps || SHIELD_ANIM_FPS)) * 1000 : 350;
        const targetId = options.targetId;
        if (targetId !== undefined && targetId !== null) {
            if (heroId !== null && targetId === heroId) {
                if (heroShieldDamageCount >= 9) return;
                heroShieldDamageCount++;
            } else if (entities[targetId]) {
                const ent = entities[targetId];
                if (ent.kind !== "player") return;
                if (ent.shieldDamageCount >= 9) return;
                ent.shieldDamageCount++;
            } else {
                return;
            }
        }

        shieldBursts.push({
            x,
            y,
            sprite,
            createdAt: performance.now(),
            angle: options.angle,
            radius: options.radius || 0,
            lifeMs,
            targetId
        });
    }

    function updateShieldEffects(now) {
        if (heroIshActive && heroIshUntil && now >= heroIshUntil) {
            setHeroShieldEffect("ISH", false, 0);
        }
        if (heroInvincible && heroInvUntil && now >= heroInvUntil) {
            setHeroShieldEffect("INVINCIBILITY", false, 0);
        }

        for (const id in entities) {
            const e = entities[id];
            if (e.ishActive && e.ishUntil && now >= e.ishUntil) {
                setEntityShieldEffect(e, "ISH", false, 0);
            }
            if (e.invincible && e.invUntil && now >= e.invUntil) {
                setEntityShieldEffect(e, "INVINCIBILITY", false, 0);
            }
        }
    }

    function spawnExplosionAt(x, y, isPlayer) {
        if (x == null || y == null) return;
        const now = performance.now();
        explosions.push({
            x,
            y,
            createdAt: now,
            radiusStart: isPlayer ? 28 : 18,
            radiusEnd:   isPlayer ? 70 : 45,
            isPlayer: !!isPlayer
        });
    }

    function updateExplosions(now) {
        for (let i = explosions.length - 1; i >= 0; i--) {
            const ex = explosions[i];
            if (now - ex.createdAt > EXPLOSION_DURATION) {
                explosions.splice(i, 1);
            }
        }
    }

    function drawExplosions() {
        const now = performance.now();
        for (const ex of explosions) {
            const t = (now - ex.createdAt) / EXPLOSION_DURATION;
            if (t < 0 || t > 1) continue;

            const radius = ex.radiusStart + (ex.radiusEnd - ex.radiusStart) * t;
            const alpha  = 1 - t;
            const explosionScreenX = mapToScreenX(ex.x);
            const explosionScreenY = mapToScreenY(ex.y);

            ctx.save();
            ctx.globalAlpha = 0.3 * alpha;
            ctx.fillStyle = ex.isPlayer ? "#ff9933" : "#ff6600";
            ctx.beginPath();
            ctx.arc(explosionScreenX, explosionScreenY, radius, 0, Math.PI * 2, false);
            ctx.fill();

            ctx.globalAlpha = 0.6 * alpha;
            ctx.strokeStyle = "#ffffaa";
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.arc(explosionScreenX, explosionScreenY, radius * 0.6, 0, Math.PI * 2, false);
            ctx.stroke();

            ctx.globalAlpha = 0.8 * alpha;
            ctx.fillStyle = "#ffffff";
            ctx.beginPath();
            ctx.arc(explosionScreenX, explosionScreenY, radius * 0.3, 0, Math.PI * 2, false);
            ctx.fill();
            ctx.restore();
        }
    }

    // -------------------------------------------------
    // 9. ZONES VISUELLES & HUD
    // -------------------------------------------------

    function triggerRadiationPulse() {
        radiationPulseStart = performance.now();
        radiationFlashAlpha = 0.35;
    }

    function startRadiationWarning() {
        if (radiationWarningTimer === null) {
            radiationWarningTimer = setInterval(triggerRadiationPulse, 2000);
        }
        triggerRadiationPulse();
        radiationWarningActive = true;
    }

    function stopRadiationWarningTimer() {
        if (radiationWarningTimer !== null) {
            clearInterval(radiationWarningTimer);
            radiationWarningTimer = null;
        }
    }

    function stopRadiationWarning() {
        radiationWarningActive = false;
        radiationPulseStart = 0;
        stopRadiationWarningTimer();
    }

    function setRadiationWarning(active) {
        const shouldActivate = !!active;
        radiationServerFlag = shouldActivate;
        if (shouldActivate) {
            startRadiationWarning();
        } else {
            stopRadiationWarning();
        }
    }

    function drawRadiationOverlay() {
        const now = performance.now();
        if (radiationWarningActive) {
            radiationFade = Math.min(1, radiationFade + 0.08);
        } else {
            radiationFade = Math.max(0, radiationFade - 0.08);
            if (radiationFade === 0) {
                radiationFlashAlpha = 0;
            }
        }

        if (radiationFlashAlpha > 0) {
            ctx.save();
            ctx.globalAlpha = radiationFlashAlpha;
            ctx.fillStyle = "rgba(255, 64, 64, 0.8)";
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.restore();
            radiationFlashAlpha = Math.max(0, radiationFlashAlpha - 0.05);
        }

        if (radiationFade <= 0) return;

        const pulseAlpha = radiationPulseStart ? Math.max(0, 1 - (now - radiationPulseStart) / 600) : 0;
        const heroSnap = snapshotEntityById(heroId);

        if (heroSnap) {
            const radiationScreenX = mapToScreenX(heroSnap.x);
            const radiationScreenY = mapToScreenY(heroSnap.y);
            const arrow = getUiImage(UI_SPRITES.radiationHelp);
            const angle = Math.atan2(mapCenterY - heroSnap.y, mapCenterX - heroSnap.x) + Math.PI / 2;
            ctx.save();
            ctx.translate(radiationScreenX, radiationScreenY);
            ctx.rotate(angle);
            ctx.globalAlpha = 0.9 * radiationFade;
            if (arrow && arrow.complete && arrow.width > 0 && arrow.height > 0) {
                const scale = 0.9 + 0.15 * pulseAlpha;
                const w = arrow.width * scale;
                const h = arrow.height * scale;
                ctx.drawImage(arrow, -w / 2, -h / 2, w, h);
            } else {
                ctx.strokeStyle = "#ff5555";
                ctx.lineWidth = 2;
                const radius = 32 + 12 * pulseAlpha;
                ctx.beginPath();
                ctx.arc(0, 0, radius, 0, Math.PI * 2, false);
                ctx.stroke();
            }
            ctx.restore();
        }

        ctx.save();
        ctx.globalAlpha = 0.95 * radiationFade;
        ctx.fillStyle = "#ffffff";
        ctx.font = "bold 18px Arial";
        ctx.textAlign = "center";
        ctx.textBaseline = "top";
        const textY = canvas.height / 2 - 150;
        ctx.fillText("ZONE DE RADIATION", canvas.width / 2, textY);
        ctx.font = "14px Arial";
        ctx.fillText("Retournez vers la zone sécurisée", canvas.width / 2, textY + 22);
        ctx.restore();
    }

    function drawPvpOverlay() {
        const now = performance.now();
        if (mapPvpAllowed === 0) {
            ctx.save();
            ctx.globalAlpha = 0.12;
            ctx.fillStyle = "blue";
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.restore();
        }
        if (inDemilitarizedZone) {
            ctx.save();
            ctx.globalAlpha = 0.9;
            ctx.fillStyle = "#ffffff";
            ctx.font = "bold 16px Arial";
            ctx.textAlign = "center";
            ctx.textBaseline = "top";
            ctx.fillText("ZONE DE PAIX", canvas.width / 2, 14);
            ctx.restore();
        }
        if (inTradeZone) {
            ctx.save();
            ctx.globalAlpha = 0.9;
            ctx.fillStyle = "#ffffff";
            ctx.font = "bold 16px Arial";
            ctx.textAlign = "center";
            ctx.textBaseline = "top";
            ctx.fillText("ZONE COMMERCIALE", canvas.width / 2, 34);
            ctx.restore();
        }
        if (lastNoAttackZoneTime > 0 && (now - lastNoAttackZoneTime) < 5000) {
            ctx.save();
            ctx.globalAlpha = 0.25;
            ctx.fillStyle = "green";
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.restore();
        }
    }

    function drawWindowChrome(x, y, w, h, title, options = {}) {
        const bg = getUiImage(UI_SPRITES.windowBg);
        const headerImg = getUiImage(UI_SPRITES.windowHeader);
        const sideImg = getUiImage(UI_SPRITES.windowSide);
        const btnClose = getUiImage(UI_SPRITES.buttonClose);
        const btnCollapse = getUiImage(UI_SPRITES.buttonCollapse);

        ctx.save();

        if (bg && bg.complete && bg.width > 0 && bg.height > 0) {
            ctx.drawImage(bg, x, y, w, h);
        } else {
            ctx.fillStyle = "rgba(10, 16, 26, 0.9)";
            ctx.fillRect(x, y, w, h);
            ctx.strokeStyle = "#35506d";
            ctx.strokeRect(x + 0.5, y + 0.5, w - 1, h - 1);
        }

        let headerHeight = Math.min(28, h / 3);
        if (headerImg && headerImg.complete && headerImg.width > 0) {
            headerHeight = Math.min(headerImg.height, h);
            ctx.drawImage(headerImg, x, y, w, headerHeight);
        } else {
            ctx.fillStyle = "rgba(25, 45, 70, 0.9)";
            ctx.fillRect(x, y, w, headerHeight);
        }

        if (sideImg && sideImg.complete && sideImg.width > 0) {
            ctx.drawImage(sideImg, x, y, sideImg.width, h);
            ctx.drawImage(sideImg, x + w - sideImg.width, y, sideImg.width, h);
        }

        let closeRect = null;
        let collapseRect = null;
        if (options.showButtons) {
            const btnSize = 16;
            const btnY = y + (headerHeight - btnSize) / 2;
            const closeX = x + w - btnSize - 6;
            closeRect = { x: closeX, y: btnY, w: btnSize, h: btnSize };
            if (btnClose && btnClose.complete && btnClose.width > 0) {
                ctx.drawImage(btnClose, closeX, btnY, btnSize, btnSize);
            } else {
                ctx.fillStyle = "#aa0000";
                ctx.fillRect(closeX, btnY, btnSize, btnSize);
            }

            const collapseX = closeX - btnSize - 4;
            collapseRect = { x: collapseX, y: btnY, w: btnSize, h: btnSize };
            if (btnCollapse && btnCollapse.complete && btnCollapse.width > 0) {
                ctx.drawImage(btnCollapse, collapseX, btnY, btnSize, btnSize);
            } else {
                ctx.fillStyle = "#004477";
                ctx.fillRect(collapseX, btnY, btnSize, btnSize);
            }
        }

        if (title) {
            ctx.font = "13px Consolas, monospace";
            ctx.fillStyle = "#e3f2ff";
            ctx.textBaseline = "middle";
            ctx.textAlign = "left";
            ctx.fillText(title, x + 10, y + headerHeight / 2);
        }

        ctx.restore();
        return { headerHeight, closeRect, collapseRect };
    }