
    // 2. WEBSOCKET
    // -------------------------------------------------

    let ws = null;
	let chatWs = null;

    function sendRaw(line) {
        if (!ws || ws.readyState !== WebSocket.OPEN) {
            console.warn("[WS] Impossible d'envoyer, WS fermé :", line);
            return;
        }
        if (!line.endsWith("\n")) line += "\n";
        ws.send(line);
    }

    function connectToServer() {
        const url = `ws://${cfg.host}:${cfg.port}`;
        console.log("[WS] Connexion à :", url);

        ws = new WebSocket(url);

        ws.onopen = () => {
            console.log("[WS] Connecté !");
            const version = "2.5.0";
            const loginCmd = `LOGIN|${cfg.userID}|${cfg.sessionID}|${version}`;
            console.log("[WS] Envoi LOGIN →", loginCmd);
            sendRaw(loginCmd);
        };

        ws.onmessage = (event) => {
            const raw = event.data;
            const lines = raw.split("\n").filter(l => l.trim().length > 0);

            for (const line of lines) {
                const chunks = line.split("\0");
                for (let chunk of chunks) {
                    chunk = chunk.trim();
                    if (!chunk) continue;
                    handleServerLine(chunk);
                }
            }
        };

        ws.onerror = (err) => console.error("[WS] ERREUR :", err);
        ws.onclose = () => console.warn("[WS] Déconnexion.");
    }
    // Cette fonction gère la connexion dédiée au Chat et au Groupe
    function ensureDefaultChatRooms() {
        if (!chatRooms.length) {
            upsertChatRoom(1, "Global", 0);
            const factionRooms = {
                1: "MMO",
                2: "EIC",
                3: "VRU"
            };
            if (heroFactionId && factionRooms[heroFactionId]) {
                upsertChatRoom(heroFactionId + 1, factionRooms[heroFactionId], heroFactionId);
            }
            if (heroClanId) {
                upsertChatRoom(heroClanId + 100, "Clan", heroClanId + 100);
            }
        }
    }

    // Permet d'éviter les erreurs si l'UI du chat n'est pas encore chargée
    function renderChatTabsSafe(attempt = 0) {
        if (typeof renderChatTabs === 'function') {
            renderChatTabs();
            return;
        }

        if (attempt < 10) {
            setTimeout(() => renderChatTabsSafe(attempt + 1), 100);
        }
    }

    function connectToChat() {
        const url = `ws://${cfg.host}:${cfg.port}`;
        console.log("[CHAT-WS] Connexion au canal Chat/Groupe...");

        chatWs = new WebSocket(url);

        chatWs.onopen = () => {
            console.log("[CHAT-WS] Connecté ! Attente avant init...");

            ensureDefaultChatRooms();
            renderChatTabsSafe();
            
            // On attend 500ms avant d'envoyer le paquet d'auth Chat
            // pour être sûr que le serveur a fini le handshake WebSocket
            setTimeout(() => {
                if (chatWs.readyState === WebSocket.OPEN) {
                    const chatInitCmd = `bu|u|0|${heroId}|${cfg.sessionID}`;
                    console.log("[CHAT-WS] Envoi INIT :", chatInitCmd);
                    chatWs.send(chatInitCmd);
                }
            }, 500);
        };

        chatWs.onmessage = (event) => {
            const raw = event.data;
            const lines = raw.split("\n").filter(l => l.trim().length > 0);
            
            for (const line of lines) {
                const chunks = line.split("\0");
                for (let chunk of chunks) {
                    chunk = chunk.trim();
                    if (!chunk) continue;

                    // Si le paquet contient un '%', c'est du protocole Chat (bv, by, dq...)
                    if (chunk.indexOf("%") !== -1) {
                        handleChatPacket(chunk);
                    } 
                    // Sinon, c'est du protocole Jeu (ex: ps|inv|...) qui passe par le chat
                    else {
                        handleServerLine(chunk); 
                    }
                }
            }
        };

        chatWs.onerror = (e) => console.warn("[CHAT-WS] Erreur", e);
        
        chatWs.onclose = (e) => {
            console.warn("[CHAT-WS] Fermé (Code: " + e.code + ").");
            // Optionnel : Reconnexion auto si fermé
            // setTimeout(connectToChat, 3000); 
        };
    }

    connectToServer(); // Lancement du jeu principal

    // Lancement du Chat (avec petite sécurité pour être sûr que l'ID est prêt)
    const chatInitInterval = setInterval(() => {
        if (heroId && heroId > 0) {
            clearInterval(chatInitInterval);
            connectToChat();
        }
    }, 500);

    // =====================================================
    // TABLE GLOBALE DES HANDLERS DE PACKETS
    // =====================================================

    const PACKET_HANDLERS = {
        "m":   handlePacket_m,
		"i":   handlePacket_i,
        "1":   handlePacket_move,
        "A":   handlePacket_A,
        "RDY": handlePacket_RDY,
        "c":   handlePacket_c,
        "f":   handlePacket_f,
        "H":   handlePacket_H,
        "p":   handlePacket_portal,
        "SMP": handlePacket_SMP,
        "P":   handlePacket_noAttack,
        "O":   handlePacket_O,
        "X":   handlePacket_X,
        "a":   handlePacket_laserAttack,
        "SAB_SHOT": handlePacket_sabShot,
        "v":   handlePacket_rocketAttack,
        "Y":   handlePacket_attackInfo,
        "2":   handlePacket_remove,
                "s": handlePacket_s,
        "S":   handlePacket_S,
        "C":   handlePacket_C,
        "R":   handlePacket_R,
        "CSS": handlePacket_CSS,
        "UT":  handlePacket_UT,
        "D":   handlePacket_D,
        "U":   handlePacket_U,
        "UI":  handlePacket_UI,
        "POI": handlePacket_POI,
                "E":   handlePacket_E,     // <--- AJOUT
        "T":   handlePacket_T,     // <--- AJOUT
        "b":   handlePacket_b,     // <--- AJOUT (Prix des minerais)
        "B":   handlePacket_B,
        "3":   handlePacket_3,
        "g":   handlePacket_g,
        "LAB": handlePacket_LAB,
                "ps":  handlePacket_ps,   // (Gestion Groupe)
                "N":  handlePacket_N,  // Infos Cible
                "n":  handlePacket_n,   // Attributs
        "y":   handlePacket_y, // Récompenses
                "7":   handlePacket_7,
		"9":   handlePacket_QuestFM,
        "K":   handlePacket_K  // Explosions
    };

    // Statistiques des packets inconnus (pour debug)
    const unknownPacketStats = {};

    function logUnknownPacket(opcode, parts) {
        if (!unknownPacketStats[opcode]) {
            unknownPacketStats[opcode] = 0;
        }
        unknownPacketStats[opcode]++;

        console.warn(
            "[PACKET INCONNU] opcode =", opcode,
            "| total vus =", unknownPacketStats[opcode],
            "| contenu =", parts.join("|")
        );
    }


    // -------------------------------------------------
    // 3. TRAITEMENT DES PAQUETS
    // -------------------------------------------------

    function handleServerLine(line) {
        const parts = line.split("|");
        if (parts.length === 0) return;

        let opcode;
        let startIndex;

        if (parts[0] === "0") {
            if (parts.length < 2) return;
            opcode = parts[1];
            startIndex = 2;
        } else {
            opcode = parts[0];
            startIndex = 1;
        }

        const handler = PACKET_HANDLERS[opcode];

        if (handler) {
            handler(parts, startIndex);
        } else {
            logUnknownPacket(opcode, parts);
        }
    }
	// ========================================================
    // GESTIONNAIRE PROTOCOLE CHAT (Format: OPCODE%DATA#) - FINAL
    // ========================================================
    const chatRooms = [];
    let chatCurrentRoomId = 1;
    const chatBuffers = {};
	
	    // Fonction utilitaire pour ajouter une ligne dans la fenêtre de chat
    function addChatMessage(name, msg, roomId = chatCurrentRoomId, typeClass = "chatGlobal", clanTag = null) {
        const buffer = (chatBuffers[roomId] = chatBuffers[roomId] || []);

        // Affichage du Tag de Clan s'il existe
        let nameDisplay = name;
        if (clanTag && clanTag.length > 0 && name) {
            nameDisplay = `<span class="chatClanTag">[${clanTag}]</span> ${name}`;
        }

        const html = name
            ? `<span class="chatName">${nameDisplay}</span> : ${msg}`
            : msg;

        buffer.push({ html, typeClass });

        // Si c'est le salon actuellement affiché, on ajoute la ligne dans le DOM
        if (roomId === chatCurrentRoomId) {
            const container = document.getElementById('chatContent');
            if (container) {
                const div = document.createElement('div');
                div.className = "chatLine " + typeClass;
                div.innerHTML = html;
                container.appendChild(div);
                container.scrollTop = container.scrollHeight;
            }
        }
    }


    function upsertChatRoom(id, name, faction) {
        const existing = chatRooms.find(r => r.id === id);
        if (existing) {
            existing.name = name;
            existing.faction = faction;
            return existing;
        }
        const room = { id, name, faction };
        chatRooms.push(room);
        return room;
    }

    function handleChatPacket(raw) {
        const clean = raw.endsWith("#") ? raw.slice(0, -1) : raw;
        const separatorIndex = clean.indexOf("%");
        if (separatorIndex === -1) return;

        const opcode = clean.substring(0, separatorIndex);
        const data = clean.substring(separatorIndex + 1);

        if (opcode === "by") {
            // Room definition: id|name|faction|visibility
            const parts = data.split("|");
            if (parts.length >= 2) {
                const roomId = parseInt(parts[0], 10);
                const roomName = parts[1];
                const faction = parseInt(parts[2], 10) || 0;
                if (!isNaN(roomId)) {
                    upsertChatRoom(roomId, roomName, faction);
                    renderChatTabsSafe();
                }
            }
        }
        // 1. MESSAGE JOUEUR STANDARD (a)
        else if (opcode === "a") {
            // Format: ROOM_ID@NOM@MESSAGE@CLAN_TAG#
            const parts = data.split("@");
            if (parts.length >= 3) {
                const roomId = parseInt(parts[0], 10) || 1;
                const name = parts[1];
                const msg = parts[2];
                const clanTag = parts[3] || null; // Lecture du Tag
                addChatMessage(name, msg, roomId, "chatGlobal", clanTag);
            }
        }
        // 2. MESSAGE ADMIN (j)
        else if (opcode === "j") {
            // Format: ROOM_ID@NOM@MESSAGE@CLAN_TAG#
            const parts = data.split("@");
            if (parts.length >= 3) {
                const roomId = parseInt(parts[0], 10) || 1;
                const name = parts[1];
                const msg = parts[2];
                const clanTag = parts[3] || null; // Lecture du Tag
                addChatMessage(name, msg, roomId, "chatAdmin", clanTag);
            }
        }
        // MESSAGE SYSTÈME (dq)
        else if (opcode === "dq") {
            const textOnly = data.replace(/<[^>]*>?/gm, "");
            addChatMessage(null, textOnly, chatCurrentRoomId, "chatSystem");
        }
        // WHISPER SENT
        else if (opcode === "cw") {
            const parts = data.split("@");
            if (parts.length >= 2) {
                const target = parts[0];
                const msg = parts[1];
                addChatMessage(null, `[À ${target}] ${msg}`, chatCurrentRoomId, "chatWhisper");
            }
        }
        // WHISPER RECEIVED
        else if (opcode === "cv") {
            const parts = data.split("@");
            if (parts.length >= 2) {
                const sender = parts[0];
                const msg = parts[1];
                addChatMessage(null, `[De ${sender}] ${msg}`, chatCurrentRoomId, "chatWhisper");
            }
        }
    }
	
// ========================================================
// GESTION DES PAQUETS DE GROUPE (PS) - FIX FINAL
// ========================================================
function handlePacket_ps(parts) {
    // Structure de base : 0|ps|ACTION|...
    // parts[0]="0", parts[1]="ps", parts[2]=ACTION

    if (parts.length < 3) return;
    const action = parts[2]; 

    // ----------------------------------------------------
    // 1. GESTION DES INVITATIONS (inv)
    // Structure : 0|ps|inv|new|SENDER_ID|SENDER_NAME|...
    // ----------------------------------------------------
    if (action === "inv") {
        const subAction = parts[3];

        if (subAction === "new") {
            // Index 4 = ID de l'expéditeur
            const senderId = parseInt(parts[4], 10);
            const nameInPacket = parts[5];

            // On sécurise les types pour la comparaison
            const myHeroId = parseInt(heroId, 10);

            console.log(`[GROUPE] Inv. Sender: ${senderId}, Moi: ${myHeroId}`);

            if (senderId === myHeroId) {
                // C'est une confirmation d'envoi (Miroir) -> On ignore
                addInfoMessage("Invitation envoyée à " + nameInPacket + ".");
            } else {
                // C'est une vraie invitation reçue
                pendingGroupInvite = { id: senderId, name: nameInPacket };
                addInfoMessage("INVITATION GROUPE REÇUE DE : " + nameInPacket);
                // Tu peux ajouter ici ta logique visuelle (bouton accepter etc.)
            }
        }
        else if (subAction === "del") {
            if (pendingGroupInvite) {
                addInfoMessage("Invitation annulée.");
                pendingGroupInvite = null;
            }
        }
    }

    // ----------------------------------------------------
    // 2. INITIALISATION DU GROUPE (init) -> C'EST ICI QUE ÇA PLANTAIT
    // Structure : 0|ps|init|grp|1|1|1|1|1|NOM|ID|...
    // ----------------------------------------------------
    else if (action === "init") {
        const subAction = parts[3];
        
        if (subAction === "grp") {
            // On vide la liste proprement
            for (const k in groupMembers) delete groupMembers[k];
            groupLeaderId = null;

            // CORRECTION CRITIQUE : L'offset de départ
            // parts[0] à parts[3] = en-têtes
            // parts[4] à parts[8] = les cinq "1" de config
            // parts[9] = Premier NOM de joueur.
            let offset = 9; 
            
            while (offset < parts.length - 1) {
                const gName = parts[offset];
                
                // Sécurité fin de paquet
                if (!gName || gName === "" || offset + 6 >= parts.length) break;
                
                const gId = parseInt(parts[offset + 1], 10);
                
                if (!isNaN(gId) && gId > 0) {
                    groupMembers[gId] = {
                        id: gId,
                        name: gName,
                        hp: parseInt(parts[offset + 2], 10) || 0,
                        maxHp: parseInt(parts[offset + 3], 10) || 100000,
                        shield: parseInt(parts[offset + 4], 10) || 0,
                        maxShield: parseInt(parts[offset + 5], 10) || 10000,
                        mapId: parseInt(parts[offset + 6], 10) || 0
                    };
                    if (groupLeaderId === null) {
                        groupLeaderId = gId;
                    }
                    // En AS3, chaque membre occupe 19 segments dans le tableau
                    offset += 19;
                } else {
                    // Si lecture impossible, on avance de 1 pour essayer de retomber sur nos pattes
                    offset++;
                }
            }
            addInfoMessage("Groupe formé !");
            drawGroupWindow();
        }
    }

    // ----------------------------------------------------
    // 3. MISE À JOUR (upd) -> C'EST ICI AUSSI QUE ÇA PLANTAIT
    // Structure : 0|ps|upd|ID|XML
    // ----------------------------------------------------
    else if (action === "upd") {
        // CORRECTION CRITIQUE : L'ID est à l'index 3, pas 2
        const memId = parseInt(parts[3], 10);
        const xmlData = parts[4]; 

        if (groupMembers[memId] && xmlData) {
            // Extraction simple des valeurs (HP, Shield, Map)
            const extract = (key) => {
                const match = xmlData.match(new RegExp(`${key}="(\\d+)"`));
                return match ? parseInt(match[1], 10) : null;
            };

            const hp = extract("hp");
            const maxHp = extract("hpM");
            const sh = extract("sh");
            const maxSh = extract("shM");
            const map = extract("map");

            if (hp !== null) groupMembers[memId].hp = hp;
            if (maxHp !== null) groupMembers[memId].maxHp = maxHp;
            if (sh !== null) groupMembers[memId].shield = sh;
            if (maxSh !== null) groupMembers[memId].maxShield = maxSh;
            if (map !== null) groupMembers[memId].mapId = map;
            
            drawGroupWindow();
        }
    }

    // ----------------------------------------------------
    // 4. QUITTER / KICK / LEADER
    // ----------------------------------------------------
    else if (action === "lp") { // Leave Party
        // Structure: 0|ps|lp|CODE|ID
        const targetId = parseInt(parts[4], 10); 
        if (groupMembers[targetId]) {
            addInfoMessage(groupMembers[targetId].name + " a quitté le groupe.");
            delete groupMembers[targetId];
            if (groupLeaderId === targetId) {
                groupLeaderId = null;
            }
            drawGroupWindow();
        }
    }
    
	    else if (action === "png") { 
        // Ping de groupe
        // Format prévu : 0|ps|png|pos|X|Y|[FROM_ID]
        const subAction = parts[3];

        if (subAction === "pos") {
            const gx = parseInt(parts[4], 10);
            const gy = parseInt(parts[5], 10);

            if (!isNaN(gx) && !isNaN(gy)) {
                groupPings.push({
                    x: gx,
                    y: gy,
                    createdAt: performance.now()
                });
                addInfoMessage("Ping de groupe reçu.");
            }
        }
    }

	
	else if (action === "end") { // Fin du groupe
        for (const k in groupMembers) delete groupMembers[k];
        groupLeaderId = null;
        addInfoMessage("Groupe dissous.");
        drawGroupWindow();
    }
    else if (action === "nl") { // New Leader
        const leaderId = parseInt(parts[3], 10); // 0|ps|nl|ID
        if (groupMembers[leaderId]) {
            addInfoMessage(groupMembers[leaderId].name + " est le chef.");
            groupLeaderId = leaderId;
        }
    }
}

// Info Cible (HP/Shield précis)
function handlePacket_N(parts, i) {
    // 0|N|id|nom|sh|maxSh|hp|maxHp
    const id = parseInt(parts[i], 10);
    const name = parts[i + 1] || "";
    const shield = parseInt(parts[i + 2], 10);
    const maxShield = parseInt(parts[i + 3], 10);
    const hp = parseInt(parts[i + 4], 10);
    const maxHp = parseInt(parts[i + 5], 10);

    if (isNaN(id)) return;

    if (heroId !== null && id === heroId) {
        if (!isNaN(shield))    heroShield = shield;
        if (!isNaN(maxShield)) heroMaxShield = maxShield;
        if (!isNaN(hp))        heroHp = hp;
        if (!isNaN(maxHp))     heroMaxHp = maxHp;
    } else {
        const ent = ensureEntity(id);
        if (name) ent.name = name;
        if (!isNaN(shield))    ent.shield = shield;
        if (!isNaN(maxShield)) ent.maxShield = maxShield;
        if (!isNaN(hp))        ent.hp = hp;
        if (!isNaN(maxHp))     ent.maxHp = maxHp;
    }
}

// Gestion du changement de carte (Jump)
    function resetMapState(newMapId) {
        if (!isNaN(newMapId)) {
            currentMapId = newMapId;
            cfg.mapID = newMapId;
        }

        applyMapBackground(currentMapId);

        for (const id in entities) delete entities[id];
        for (const id in portals) delete portals[id];

        laserBeams.length = 0;
        rocketAttacks.length = 0;
        damageBubbles.length = 0;
        explosions.length = 0;
        groupPings.length = 0;

        selectedTargetId = null;
        currentLaserTargetId = null;
        attackIntentTargetId = null;
        resetPendingRangeResume();
        clearPendingCollectState();
        if (typeof collectedBoxRequestIds !== "undefined") collectedBoxRequestIds.clear();
        if (typeof stopHeroCollectorBeam === "function") stopHeroCollectorBeam();
        moveTargetX = null;
        moveTargetY = null;
        isChasingTarget = false;

        inDemilitarizedZone = false;
        inTradeZone = false;
        inJumpZone = false;
        radiationServerFlag = false;
        radiationWarningActive = false;
        radiationFade = 0;
        radiationPulseStart = 0;
        radiationFlashAlpha = 0;
        stopRadiationWarningTimer();

        addInfoMessage("Saut vers la carte " + (newMapId || "?") + " effectué.");
    }

    function handlePacket_i(parts, i) {
        const newMapId = parseInt(parts[i], 10);
        if (isNaN(newMapId)) return;

        console.log("[MAP] Changement de carte vers : " + newMapId);

        resetMapState(newMapId);
    }

// GESTION DES ATTRIBUTS & SPACEBALL (n)
    function handlePacket_n(parts, i) {
        if (parts.length < i + 1) return;
        const sub = parts[i]; // ex: ssi, ssc, d, emp ...

        // --- EFFET EMP / IEM ---
        if (sub === "emp") {
            const targetId = parseInt(parts[i + 1], 10);
            
            // Animation (Optionnel : tu pourras ajouter un effet visuel ici plus tard)
            
            // Si ma CIBLE utilise un IEM
            if (selectedTargetId === targetId) {
                addInfoMessage("Cible : IEM activé ! Verrouillage perdu.");

                // 1. On perd la cible
                selectedTargetId = null;
                resetPendingRangeResume();

                // 2. On arrête le laser
                if (currentLaserTargetId === targetId) {
                    currentLaserTargetId = null;
                    // On peut envoyer un stop au serveur pour être sûr
                    sendLaserStop(targetId, true);
                }
                
                // 3. On arrête de courir
                isChasingTarget = false; 
                moveTargetX = null;
                moveTargetY = null;
            }
        }

        // --- INSTA SHIELD BROADCAST ---
        else if (sub === "ISH") {
            const targetId = parseInt(parts[i + 1], 10);
            if (!isNaN(targetId)) {
                if (targetId === heroId) setHeroShieldEffect("ISH", true, ISH_DURATION_MS);
                else setEntityShieldEffect(ensureEntity(targetId), "ISH", true, ISH_DURATION_MS);
            }
        }

        // --- TARGET FADE TO GRAY (LSH / USH) ---
        else if (sub === "LSH") {
            const targetId = parseInt(parts[i + 1], 10);
            if (!isNaN(targetId)) {
                if (targetId === heroId) heroTargetFaded = true;
                else {
                    const ent = ensureEntity(targetId);
                    if (ent) ent.targetFaded = true;
                }
            }
        }
        else if (sub === "USH") {
            const targetId = parseInt(parts[i + 1], 10);
            if (!isNaN(targetId)) {
                if (targetId === heroId) heroTargetFaded = false;
                else if (entities[targetId]) entities[targetId].targetFaded = false;
            }
        }
        
        // --- SPACEBALL INIT (ssi) ---
        else if (sub === "ssi") {
            const mmo = parseInt(parts[i+1], 10);
            const eic = parseInt(parts[i+2], 10);
            const vru = parseInt(parts[i+3], 10);
            const spd = parseInt(parts[i+4], 10);
            const own = parseInt(parts[i+5], 10);
            updateSpaceballHUD(mmo, eic, vru, spd, own);
        }
        
        // --- SPACEBALL SCORE UPDATE (ssc) ---
        else if (sub === "ssc") {
            const faction = parseInt(parts[i+1], 10);
            const score   = parseInt(parts[i+2], 10);
            
            if (faction === 1) updateSpaceballHUD(score, null, null, null, null);
            if (faction === 2) updateSpaceballHUD(null, score, null, null, null);
            if (faction === 3) updateSpaceballHUD(null, null, score, null, null);
        }

        // --- EFFETS VISUELS DÉDIÉS ---
        else if (sub === "fx") {
            const action = (parts[i + 1] || "").toLowerCase();
            const effect = (parts[i + 2] || "").toUpperCase();
            const targetId = parseInt(parts[i + 3], 10);

            if (!isNaN(targetId)) {
                const targetEnt = targetId === heroId ? null : ensureEntity(targetId);

                const activate = (action === "start");
                if (effect === "INVINCIBILITY") {
                    if (targetId === heroId) setHeroShieldEffect("INVINCIBILITY", activate, INVINCIBILITY_DURATION_MS);
                    else setEntityShieldEffect(targetEnt, "INVINCIBILITY", activate, INVINCIBILITY_DURATION_MS);
                } else if (effect === "ISH") {
                    if (targetId === heroId) setHeroShieldEffect("ISH", activate, ISH_DURATION_MS);
                    else setEntityShieldEffect(targetEnt, "ISH", activate, ISH_DURATION_MS);
                }
            }
        }
        
        // --- SPACEBALL STATUS (sss) ---
        else if (sub === "sss") {
            const owner = parseInt(parts[i+1], 10);
            const speed = parseInt(parts[i+2], 10);
            updateSpaceballHUD(null, null, null, speed, owner);
        }
        
        // --- DRONES (d) ---
        else if (sub === "d") {
            const targetId = parseInt(parts[i + 1], 10);
            const droneStr = parts[i + 2] || "";

            const parsedDrones = parseDrones(droneStr);
            
            if (targetId === heroId) {
                window.heroDrones = parsedDrones;
            } else {
                const ent = ensureEntity(targetId);
                if (ent) {
                    ent.drones = parsedDrones;
                }
            }
        }
    }

    var DRONE_GROUP_RADIUS = (typeof DRONE_GROUP_RADIUS !== "undefined") ? DRONE_GROUP_RADIUS : 90; // game.xml patterns.drones.@groupRadius
    var DRONE_RADIUS = (typeof DRONE_RADIUS !== "undefined") ? DRONE_RADIUS : 18;                   // game.xml patterns.drones.drone.@droneRadius
    var DRONE_GROUP_DIMENSION = DRONE_GROUP_RADIUS * 2;

    const DRONE_POSITION_TOP = 0;
    const DRONE_POSITION_RIGHT = 1;
    const DRONE_POSITION_DOWN = 2;
    const DRONE_POSITION_LEFT = 3;
    const DRONE_POSITION_CENTER = 4;

    function resolveDroneKind(typeId) {
        // Dans le client Flash, les types 1 = Flax, 2/3 = Iris (et dérivés). Ici on ne gère
        // que Flax/Iris au niveau max : on mappe 1 -> "flax", tout le reste -> "iris".
        if (typeId === 1) return "flax";
        return "iris";
    }

    function resolveDroneRadius(typeId, level) {
        // Les patterns du client Flash donnent un radius de 15px pour les deux types (levels 0..5).
        // On conserve cette valeur par défaut pour reproduire le placement original.
        if (!Number.isFinite(typeId) || !Number.isFinite(level)) return DRONE_RADIUS;
        return DRONE_RADIUS;
    }

    function mapGroupPosition(groupCount, groupIndex) {
        if (groupCount === 1) return DRONE_POSITION_DOWN;
        if (groupCount === 2) return (groupIndex === 0) ? DRONE_POSITION_LEFT : DRONE_POSITION_RIGHT;
        if (groupCount === 3) {
            if (groupIndex === 0) return DRONE_POSITION_RIGHT;
            if (groupIndex === 1) return DRONE_POSITION_DOWN;
            return DRONE_POSITION_LEFT;
        }
        // groupCount >= 4
        if (groupIndex === 0) return DRONE_POSITION_RIGHT;
        if (groupIndex === 1) return DRONE_POSITION_DOWN;
        if (groupIndex === 2) return DRONE_POSITION_LEFT;
        return DRONE_POSITION_TOP;
    }

    function mapDronePosition(droneCount, droneIndex) {
        if (droneCount === 1) return DRONE_POSITION_CENTER;
        if (droneCount === 2) return droneIndex === 0 ? DRONE_POSITION_LEFT : DRONE_POSITION_RIGHT;
        if (droneCount === 3) {
            if (droneIndex === 0) return DRONE_POSITION_TOP;
            if (droneIndex === 1) return DRONE_POSITION_RIGHT;
            return DRONE_POSITION_LEFT;
        }
        // droneCount >= 4
        if (droneIndex === 0) return DRONE_POSITION_TOP;
        if (droneIndex === 1) return DRONE_POSITION_RIGHT;
        if (droneIndex === 2) return DRONE_POSITION_LEFT;
        return DRONE_POSITION_DOWN;
    }

    function parseDrones(droneStr) {
        const emptyResult = { groupCount: 0, groupDimension: DRONE_GROUP_DIMENSION, groups: [] };
        if (!droneStr || typeof droneStr !== "string") return emptyResult;

        const trimmed = droneStr.trim();
        if (!trimmed) return emptyResult;

        // Format original du client Flash (voir parseDroneString) :
        // "<nbGroupes>/<nbDrones>-<d1>-<d2>.../<nbDrones>-<d1>-..."
        // Exemple pour 8 drones (4 groupes de 2) :
        // "4/2-21-21/2-21-21/2-21-21/2-21-21"
        const segments = trimmed.split('/').filter(s => s !== "");
        if (!segments.length) return emptyResult;

        const groupCount = parseInt(segments.shift(), 10);
        if (!Number.isFinite(groupCount) || groupCount <= 0) return emptyResult;

        const groups = [];
        for (let i = 0; i < segments.length; i++) {
            const rawGroup = segments[i];
            if (!rawGroup) continue;

            const parts = rawGroup.split('-').filter(p => p !== "");
            if (!parts.length) continue;

            const droneCount = parseInt(parts.shift(), 10);
            if (!Number.isFinite(droneCount) || droneCount <= 0) continue;

            const drones = [];
            for (let j = 0; j < parts.length; j++) {
                const token = parts[j];
                const tokenParts = token.split(',');
                const digits = (tokenParts[0] || "").trim();
                if (digits.length < 2) continue;

                const typeId = parseInt(digits.charAt(0), 10);
                const level = parseInt(digits.charAt(1), 10);

                drones.push({
                    type: Number.isNaN(typeId) ? null : typeId,
                    kind: resolveDroneKind(typeId),
                    level: Number.isNaN(level) ? null : level,
                    position: mapDronePosition(droneCount, j),
                    dimension: resolveDroneRadius(typeId, level) * 2
                });
            }

            if (drones.length) {
                groups.push({
                    position: mapGroupPosition(groupCount, i),
                    drones
                });
            }
        }

        return { groupCount, groupDimension: DRONE_GROUP_DIMENSION, groups };
    }


    function handlePacket_S(parts, i) {
        if (parts.length < i + 1) return;
        const subOpcode = parts[i];

        switch (subOpcode) {
            case "CFG": {
                const cfgId = parseInt(parts[i + 1], 10);
                if (!isNaN(cfgId)) {
                    heroConfig = cfgId;
                    addInfoMessage("Configuration active : " + cfgId);
                }
                break;
            }
            case "ROB": {
                addInfoMessage("Réparation en cours / confirmée.");
                break;
            }
            case "ISH": {
                const state = (parts[i + 1] || "1").toString().toUpperCase();
                const active = (state === "1" || state === "ON" || state === "TRUE");
                setHeroShieldEffect("ISH", active, ISH_DURATION_MS);
                addInfoMessage(heroIshActive ? "ISH (Insta-shield) activé." : "ISH terminé.");
                break;
            }
            case "SMB": {
                const state = (parts[i + 1] || "1").toString().toUpperCase();
                heroSmbJustUsed = (state === "1" || state === "ON" || state === "TRUE");
                addInfoMessage("Smartbomb déclenchée.");
                break;
            }
            case "EMP": {
                const state = (parts[i + 1] || "1").toString().toUpperCase();
                heroEmpActive = (state === "1" || state === "ON" || state === "TRUE");
                addInfoMessage(heroEmpActive ? "EMP activé." : "EMP terminé.");
                break;
            }
            case "CLK": {
                const state = (parts[i + 1] || "1").toString().toUpperCase();
                heroCloaked = (state === "1" || state === "ON" || state === "TRUE");
                addInfoMessage(heroCloaked ? "Camouflage activé." : "Camouflage désactivé.");
                break;
            }
            default: break;
        }
    }
    
        function triggerSlot(slot) {
        const item = quickSlots[slot];
        if (!item) return;

        // 1. Récupération du code d'action pour cooldown
        const actionCode = getActionCodeForSlot(slot);

        // 2. Vérification Blacklist (Stock épuisé ou interdiction serveur)
        if (actionCode && isActionBlacklisted(actionCode)) {
            addInfoMessage("Action indisponible (Stock 0 ou Bloqué).");
            return;
        }

        // 3. Vérification Cooldown (Sauf pour les munitions qui n'ont pas de CD global client)
        if (item.type !== "ammo") {
            const cd = actionCode ? getCooldownInfo(actionCode) : null;
            if (cd) {
                addInfoMessage("Cooldown actif : " + Math.ceil(cd.remaining) + "s");
                return;
            }
        }

        // 4. LOGIQUE SPÉCIFIQUE TYPE FLASH
        if (item.type === "ammo") {
            // 1. Sélection de la munition
            if (currentAmmoId !== item.id) {
                sendSelectAmmo(item.id);
                currentAmmoId = item.id;
            }
            
            // 2. NOUVEAU : Si on a une cible, on attaque !
            if (selectedTargetId !== null) {
                // On lance l'attaque
                sendLaserAttack(selectedTargetId);
                
                // On active le mode poursuite
                isChasingTarget = true;
            }
        }
        else if (item.type === "rocket") {
            // Sélection uniquement
            if (currentRocketId !== item.id) {
                sendSelectRocket(item.id);
                currentRocketId = item.id;
            }
        } 
        else if (item.type === "tech") {
            // Activation immédiate
            sendTechActivation(item.id);
        } 
        else if (item.type === "cpu") {
            // Activation immédiate
            sendCpuAction(item.code);
        }
        else if (item.type === "mine") {
            // Pose de mine immédiate (touche M par défaut dans Flash, ou via slot)
            sendRaw("u|m|" + item.id); // Format standard mine
        }
    }


        function handlePacket_m(parts, i) {
        // Paquet "m" : informations de map (centre, debug)
        // Format possible (futur) :
        //  - "m|1|centerX|centerY"
        //  - "0|m|1|centerX|centerY"
        
        let start = i;

        // Cas où la ligne commence par "0|m|..."
        if (parts[0] === "0" && parts[1] === "m") {
            start = 2;
        }

        if (parts.length < start + 3) return;

        const mode = parseInt(parts[start], 10) || 0;
        const cx   = parseInt(parts[start + 1], 10);
        const cy   = parseInt(parts[start + 2], 10);

        if (isNaN(cx) || isNaN(cy)) return;

        mapCenterX = cx;
        mapCenterY = cy;

        console.log(`[MAP] Packet m (mode=${mode}) center=(${cx},${cy})`);
    }


    function handlePacket_H(parts, i) {
        if (parts.length < i + 2) return;
        const x = parseInt(parts[i], 10);
        const y = parseInt(parts[i + 1], 10);
        if (!isNaN(x) && !isNaN(y)) {
            shipX = x;
            shipY = y;
            cameraX = shipX;
            cameraY = shipY;
        }
    }

    function handlePacket_move(parts, i) {
        // Format attendu: 1|ID|X|Y|TIME ou 1|X|Y|TIME
        const remaining = parts.length - i;
        if (remaining < 3) return;

        let id = 0;
        let x = 0;
        let y = 0;
        let time = 0;
        let isHeroCorrection = false;

        // Détermination du format
        if (remaining === 3) {
            // Format court (1|X|Y|TIME) - Ancienne correction lag
            x = parseInt(parts[i], 10);
            y = parseInt(parts[i + 1], 10);
            time = parseFloat(parts[i + 2] || "0");
            id = heroId; // On force l'ID du héros
            isHeroCorrection = true;

        } else if (remaining >= 4) {
            // Format long (1|ID|X|Y|TIME) - Mouvements d'entités et Follow du Héros
            id = parseInt(parts[i], 10);
            x = parseInt(parts[i + 1], 10);
            y = parseInt(parts[i + 2], 10);
            time = parseFloat(parts[i + 3] || "0");
            
            if (id === heroId) {
                isHeroCorrection = true;
            }
        } else {
            return;
        }

        if (isNaN(id) || isNaN(x) || isNaN(y)) return;


        // --- LOGIQUE HÉROS (Valable pour le Follow et les clics sol) ---
        if (isHeroCorrection) {
            // On accepte la destination donnée par le serveur (Follow ou Clic Sol)
            moveTargetX = x;
            moveTargetY = y;
            
            // Si c'est une téléportation (distance énorme > 2000), on se téléporte direct
            const dist = Math.hypot(x - shipX, y - shipY);
            if (dist > 2000) {
                shipX = x;
                shipY = y;
                moveTargetX = null;
                moveTargetY = null;
            }
            return;
        }
        
        // --- LOGIQUE ENTITÉS (NPC / Autres joueurs) ---
        
        const ent = ensureEntity(id);
        
        if (ent.kind === "box") return; // On n'anime pas les boîtes

        if (ent.kind === "unknown") ent.kind = "player";

        // Initialisation ou Mouvement Interpolé
        if (ent.interp.duration === 0 && ent.x === 0 && ent.y === 0) {
            ent.x = x; ent.y = y;
            ent.interp.startX = x; ent.interp.startY = y;
            ent.interp.endX = x; ent.interp.endY = y;
            ent.interp.duration = 0; ent.interp.startTime = performance.now();
        } else {
            ent.interp.startX = ent.x; 
            ent.interp.startY = ent.y;
            ent.interp.endX = x; 
            ent.interp.endY = y;
            ent.interp.duration = (time > 0 ? time : 0);
            ent.interp.startTime = performance.now();
        }
    }

    function handlePacket_A(parts, i) {
    if (parts.length < i + 1) return;
    const subOpcode = parts[i];

    switch (subOpcode) {
        case "STD": {
            const msg = parts[i + 1] || "";
            const clean = msg.replace(/~/g, "").trim();
            addInfoMessage(clean);
            break;
        }
        case "BK": {
            const count = parseInt(parts[i + 1], 10);
            if (!isNaN(count)) {
                heroBootyKeys = count;
            }
            break;
        }
        case "ITM": { // Paquet Inventory (A|ITM) - Chargement des stocks
            // Lasers Spéciaux
            ammoStock[4] = parseInt(parts[i + 4], 10) || 0;  // MCB-50 (x4)
            ammoStock[6] = parseInt(parts[i + 5], 10) || 0;  // RSB-75
            ammoStock[5] = parseInt(parts[i + 6], 10) || 0;  // SAB-50
            
            // Lasers de base
            ammoStock[1] = parseInt(parts[i + 8], 10) || 0;  // x1 Laser
            ammoStock[2] = parseInt(parts[i + 9], 10) || 0;  // x2 Laser
            ammoStock[3] = parseInt(parts[i + 10], 10) || 0; // x3 Laser
            
            // Roquettes
            ammoStock[10] = parseInt(parts[i + 18], 10) || 0; // PLT-2026
            ammoStock[11] = parseInt(parts[i + 19], 10) || 0; // PLT-2021
            ammoStock[12] = parseInt(parts[i + 20], 10) || 0; // PLT-3030
			
			 // --- Mise à jour automatique de la disponibilité des roquettes (blacklist) ---
            const totalRockets =
                (ammoStock[10] || 0) +
                (ammoStock[11] || 0) +
                (ammoStock[12] || 0);

            if (totalRockets <= 0) {
                // Plus de roquettes → on les met en blacklist
                blacklistAction("ROK");
            } else {
                // Il y a au moins une roquette → on enlève la blacklist
                unblacklistAction("ROK");
            }
			renderActionDrawerItems(); // Met à jour le menu visuel immédiatement

            addInfoMessage("Inventaire de munitions et objets chargé.");
            break;
        }
        case "RS": { 
            const mode = parts[i + 1] || "0";
            if (mode === "1") addInfoMessage("Réparation rapide démarrée (30k).");
            else addInfoMessage("Réparation démarrée.");
            break;
        }
                case "CLD": {
            const code    = parts[i + 1] || "";
            const seconds = parseInt(parts[i + 2], 10);

            if (!isNaN(seconds) && seconds > 0) {
                // On lance le cooldown côté client
                setActionCooldown(code, seconds);
            }

            let label = code;
            if (code === "ISH")      label = "Insta-shield";
            else if (code === "SMB") label = "Smartbomb";
            else if (code === "ROK") label = "Rockets";
            else if (code === "EMP") label = "EMP";
            else if (code === "SBU") label = "Shield Backup";
            else if (code === "BRB") label = "Battle Repair Bot";

            if (!isNaN(seconds) && seconds > 0)
                addInfoMessage(`Cooldown ${label} : ${seconds}s`);
            else
                addInfoMessage(`Cooldown ${label}`);
            break;
        }

        case "v": {
            const speedStr = parts[i + 1] || "0";
            const speed = parseInt(speedStr, 10);
            if (!isNaN(speed) && speed > 0) {
                heroSpeed = speed;
                addInfoMessage("Vitesse : " + heroSpeed + " u/s");
            }
            break;
        }
        case "SHD": {
            const shStr     = parts[i + 1] || "0";
            const maxShStr  = parts[i + 2] || "0";
            const newShield = parseInt(shStr, 10);
            const newMaxSh  = parseInt(maxShStr, 10);
            if (!isNaN(newShield)) heroShield = newShield;
            if (!isNaN(newMaxSh) && newMaxSh > 0) heroMaxShield = newMaxSh;
            break;
        }
        case "HL": {
            // Format Serveur C# : 0|A|HL|1|ID|TYPE|VALEUR|DIFF
            if (parts.length < i + 5) break;

            const targetId = parseInt(parts[i + 2], 10);
            const type     = parts[i + 3];
            const value    = parseInt(parts[i + 4], 10);
            const diffRaw  = parseInt(parts[i + 5] || "0", 10);
            const targetEnt = (heroId !== null && targetId === heroId) ? null : entities[targetId];

            const applyDeltaBubble = (prevVal, newVal, entityId, isShield = false) => {
                if (entityId == null || prevVal == null || isNaN(newVal)) return;
                let delta = !isNaN(diffRaw) ? diffRaw : (newVal - prevVal);
                if (delta === 0) delta = newVal - prevVal;
                if (delta === 0) return;
                pushDamageBubble(entityId, delta, delta > 0);
                if (!isShield && delta < 0 && entityId === heroId) {
                    const angle = getRecentBeamAngleForTarget(heroId);
                    const radius = computeShieldImpactRadius(snapshotEntityById(heroId));
                    spawnShieldBurstAt(shipX, shipY, "hit", { angle, radius, targetId: heroId });
                }
            };

            if (heroId !== null && targetId === heroId) {
                if (type === "HPT") {
                    const prev = heroHp;
                    heroHp = value;
                    applyDeltaBubble(prev, value, heroId);
                } else if (type === "SHD") {
                    const prev = heroShield;
                    heroShield = value;
                    if (prev != null && value < prev) {
                        const angle = getRecentBeamAngleForTarget(heroId);
                        const radius = computeShieldImpactRadius(snapshotEntityById(heroId));
                        spawnShieldBurstAt(shipX, shipY, "hit", { angle, radius, targetId: heroId });
                    }
                    applyDeltaBubble(prev, value, heroId, true);
                }
            } else if (targetEnt) {
                if (type === "HPT") {
                    const prev = targetEnt.hp;
                    targetEnt.hp = value;
                    applyDeltaBubble(prev, value, targetId);
                } else if (type === "SHD") {
                    const prev = targetEnt.shield;
                    targetEnt.shield = value;
                    if (prev != null && value < prev && targetEnt.kind === "player") {
                        const angle = getRecentBeamAngleForTarget(targetId);
                        const radius = computeShieldImpactRadius(snapshotEntityById(targetId));
                        spawnShieldBurstAt(targetEnt.x, targetEnt.y, "hit", { angle, radius, targetId });
                    }
                    applyDeltaBubble(prev, value, targetId, true);
                }
            }
            break;
        }
        default: break;
    }
}

    function handlePacket_B(parts, i) {
        const values = [];
        for (let idx = i; idx < parts.length; idx++) {
            const v = parseInt(parts[idx], 10);
            values.push(isNaN(v) ? 0 : v);
        }
        const laserOrder = [1, 2, 3, 4, 5, 6];
        laserOrder.forEach((stockId, idx) => {
            if (values[idx] !== undefined) {
                ammoStock[stockId] = values[idx];
            }
        });
        renderActionDrawerItems();
    }

    function handlePacket_3(parts, i) {
        const rocketOrder = [10, 11, 12, 13, 14, 15, null, 20, 32, 31, 30, 21, 22, 23];
        let cursor = i + 1;
        const firstVal = parseInt(parts[i], 10);
        if (!isNaN(firstVal)) {
            ammoStock[rocketOrder[0]] = firstVal;
        }
        for (let idx = 1; idx < rocketOrder.length; idx++) {
            const raw = parts[cursor++] || "0";
            const val = parseInt(raw, 10);
            const stockKey = rocketOrder[idx];
            if (stockKey && !isNaN(val)) {
                ammoStock[stockKey] = val;
            }
        }
        renderActionDrawerItems();
    }

    // RDY (Infos Héros complètes)
    function handlePacket_RDY(parts, i) {
        // Format (UserDataComposer): RDY|I|id|username|shipId|shipSpeed|shield|maxShield|hp|maxHp|cargo|maxCargo|locX|locY|mapId|factionId|clanId|... (see code_complet.txt)
        const section = parts[i];
        if (section !== "I") {
            console.warn("[PACKET RDY] section inattendue :", section, parts);
            return;
        }

        let idx = i + 1;
        const nextStr = () => (idx < parts.length ? parts[idx++] : null);
        const nextInt = () => {
            const raw = nextStr();
            if (raw === null) return null;
            const val = parseInt(raw, 10);
            return isNaN(val) ? null : val;
        };

        const id   = nextInt();
        const name = nextStr() || "";
        const shipModel = nextInt();
        const shipSpeed = nextInt();
        const shipShield = nextInt();
        const shipMaxShld = nextInt();
        const shipHp = nextInt();
        const shipMaxHp = nextInt();
        const cargo = nextInt();
        const maxCargo = nextInt();
        const locX = nextInt();
        const locY = nextInt();
        const mapId = nextInt();
        const faction = nextInt();
        const clanId = nextInt();

        // champs supplémentaires non utilisés directement mais qui avancent l'index
        nextStr(); // exp
        nextStr(); // level
        nextStr(); // rank?
        nextStr(); // premium?
        nextStr(); // npc kill
        nextStr(); // player kill
        nextStr(); // ???

        const creds = nextInt();
        const uri = nextInt();
        nextStr(); // ???
        const grade = nextStr();
        const clanTag = nextStr();
        nextStr(); // ggrings
        nextStr(); // ???
        const invisible = nextStr();

        if (id !== null) heroId = id;
        heroName = name;
        if (shipModel !== null) heroShipId = shipModel;
        if (shipSpeed !== null) heroSpeed = shipSpeed;
        if (shipShield !== null) heroShield = shipShield;
        if (shipMaxShld !== null) heroMaxShield = shipMaxShld;
        if (shipHp !== null) heroHp = shipHp;
        if (shipMaxHp !== null) heroMaxHp = shipMaxHp;
        if (cargo !== null) heroCargo = cargo;
        if (maxCargo !== null) heroMaxCargo = maxCargo;

        if (locX !== null && locY !== null) {
            shipX = locX;
            shipY = locY;
            cameraX = shipX;
            cameraY = shipY;
            heroLastPosX = shipX;
            heroLastPosY = shipY;
            heroLastMoveMs = performance.now();
        }

        if (mapId !== null && mapId !== currentMapId) {
            resetMapState(mapId);
        }
        window.heroFactionId = (faction === null ? 0 : faction);
        heroClanId = clanId || null;
        heroGrade = grade || heroGrade;
        heroClanTag = clanTag || heroClanTag;
        heroInvisible = invisible === "1" || invisible === 1;

        if (creds !== null) heroCredits = creds;
        if (uri !== null) heroUridium = uri;

        moveTargetX = null;
        moveTargetY = null;
        isChasingTarget = false;
    }

    
    // c (Spawn Box - Avec immunité temporaire)
    function handlePacket_c(parts, i) {
        if (parts.length < i + 4) return;

        const idStr = parseInt(parts[i], 10);            
        const type  = parseInt(parts[i + 1], 10);
        const x     = parseInt(parts[i + 2], 10);
        const y     = parseInt(parts[i + 3], 10);

        if (isNaN(x) || isNaN(y)) return;

        const e = ensureEntity(idStr);

        if (typeof clearBoxAnimationState === "function") {
            clearBoxAnimationState(idStr);
        }

        // On transforme l'entité en BOÎTE
        e.id     = idStr;
        e.type   = type;
        e.shipId = null;  // Plus d'image de vaisseau
        e.x      = x;
        e.y      = y;

        e.kind      = "box";
        e.name      = "";
        e.hp        = null;
        e.shield    = null;
        e.factionId = 0;
        
        // --- AJOUT IMPORTANT : L'heure de naissance ---
        e.boxSpawnTime = Date.now(); 

        categorizeEntityFromType(e);

        // Stop mouvement
        e.interp.startX   = x;
        e.interp.startY   = y;
        e.interp.endX     = x;
        e.interp.endY     = y;
        e.interp.duration = 0;

        console.log(`[BOX] Boîte ${idStr} créée (Immunisée 2s).`);
    }


    // f|C (Spawn Player - Fix Couleur)
    function handlePacket_f(parts, i) {
        const subOpcode = parts[i];
        if (subOpcode !== "C") return;

        const id = parseInt(parts[i + 1], 10);
        const shipId = parseInt(parts[i + 2], 10);
        const clanTag = parts[i + 4] || "";
        const name    = parts[i + 5] || "";
        const x       = parseInt(parts[i + 6], 10);
        const y       = parseInt(parts[i + 7], 10);
        const faction = parseInt(parts[i + 8], 10); 

        if (isNaN(id) || isNaN(x) || isNaN(y)) return;

        if (heroId !== null && id === heroId) {
            shipX = x;
            shipY = y;
            return;
        }

        const e = ensureEntity(id);
        e.kind = "player";
        e.name = name;
        e.clanTag = clanTag;
        e.factionId = faction; 
        e.shipId = shipId;     
        e.x = x;
        e.y = y;
        
        if (e.interp.duration === 0) {
            e.interp.startX = x;
            e.interp.startY = y;
            e.interp.endX = x;
            e.interp.endY = y;
        }
    }

    function handlePacket_portal(parts, i) {
        const len = parts.length;
        if (len < i + 3) return;
        const portalId = parseInt(parts[i], 10);
        if (isNaN(portalId)) return;

        let factionId = 0;
        let typeId = 0;
        let x = 0;
        let y = 0;
        let visibleOnMiniMap = true;
        let targetMaps = [];

        if (len < i + 7) {
            typeId = parseInt(parts[i + 1], 10) || 0;
            x      = parseInt(parts[i + 3], 10);
            y      = parseInt(parts[i + 4], 10);
        } else {
            factionId = parseInt(parts[i + 1], 10) || 0;
            typeId    = parseInt(parts[i + 2], 10) || 0;
            x         = parseInt(parts[i + 3], 10);
            y         = parseInt(parts[i + 4], 10);
            visibleOnMiniMap = (parseInt(parts[i + 5], 10) === 1);

            const mapsStr = parts[i + 6] || "";
            if (mapsStr.length > 0) {
                const tokens = mapsStr.split(",");
                for (const t of tokens) {
                    const m = parseInt(t, 10);
                    if (!isNaN(m) && m > 0) targetMaps.push(m);
                }
            }
        }

        if (isNaN(x) || isNaN(y)) return;

        const p = ensurePortal(portalId);
        p.factionId = factionId;
        p.typeId    = typeId;
        p.x = x;
        p.y = y;
        p.visibleOnMiniMap = visibleOnMiniMap;
        p.targetMaps = targetMaps;
    }

    function handlePacket_SMP(parts, i) {
        if (parts.length < i + 2) return;
        const pvp  = parseInt(parts[i], 10);
        const home = parseInt(parts[i + 1], 10);
        if (!isNaN(pvp))  mapPvpAllowed  = pvp;
        if (!isNaN(home)) mapHomeFaction = home;
    }

    function handlePacket_U(parts, i) {
        if (parts.length < i + 2) return;
        const nextMap = parseInt(parts[i], 10);
        const portalId = parseInt(parts[i + 1], 10);
        if (!isNaN(nextMap)) {
            addInfoMessage("Préparation du saut vers la carte " + nextMap);
        }
        if (!isNaN(portalId) && portals[portalId]) {
            portals[portalId].playJump = true;
            portals[portalId].jumpStart = performance.now();
            const portal = portals[portalId];
            spawnPortalJumpEffect(portal.x, portal.y);
        } else if (!isNaN(portalId)) {
            spawnPortalJumpEffect(shipX, shipY);
        }
    }

    function handlePacket_UI(parts, i) {
        const action = parts[i];
        if (action === "W") {
            const key = parts[i + 1];
            const value = parts[i + 2];
            if (key === "HW" && value) {
                window.hudHeightHint = parseInt(value, 10) || window.hudHeightHint;
            }
        }
    }

    function handlePacket_POI(parts, i) {
        const action = parts[i];
        if (action === "RDY") {
            addInfoMessage("Points d'intérêt chargés");
        }
    }

    function handlePacket_C(parts, i) {
        if (parts.length < i + 8) return;
        const id       = parseInt(parts[i], 10);
        const shipId   = parseInt(parts[i + 1], 10);
        const name     = parts[i + 4] || "";
        const x        = parseInt(parts[i + 5], 10);
        const y        = parseInt(parts[i + 6], 10);
        const factionId = parseInt(parts[i + 7] || "0", 10);

        if (isNaN(id) || isNaN(x) || isNaN(y)) return;

        const e = ensureEntity(id);
        e.kind      = "npc";
        e.type      = shipId;      // type logique du NPC
        e.shipId    = shipId;      // <<< ESSENTIEL pour les sprites
        e.x         = x;
        e.y         = y;
        e.name      = name;
        e.factionId = isNaN(factionId) ? 0 : factionId;
    }




    function handlePacket_CSS(parts, i) {
        const value = parseInt(parts[i] || "0", 10);
        healthStationActive = (value === 1);
    }

    function handlePacket_UT(parts, i) {}

    function handlePacket_D(parts, i) {
        // Format (Flash): locX|locY|demil|repair|trade|radiation|jump|fastRepair
        if (parts.length < i + 8) return;
        const demilitarized = !!parseInt(parts[i + 2] || "0", 10);
        const repairZone = !!parseInt(parts[i + 3] || "0", 10);
        const tradeArea = !!parseInt(parts[i + 4] || "0", 10);
        const radiation = !!parseInt(parts[i + 5] || "0", 10);
        const jumpArea = !!parseInt(parts[i + 6] || "0", 10);
        const fastRepairCount = parseInt(parts[i + 7] || "0", 10);

        if (demilitarized !== lastDemilitarizedState) {
            addInfoMessage(demilitarized ? "Zone de paix" : "Zone de paix quittée");
            lastDemilitarizedState = demilitarized;
        }
        if (tradeArea !== lastTradeZoneState) {
            addInfoMessage(tradeArea ? "Zone commerciale" : "Zone commerciale quittée");
            lastTradeZoneState = tradeArea;
        }

        inDemilitarizedZone = demilitarized;
        inTradeZone = tradeArea;
        inJumpZone = jumpArea;
        healthStationActive = repairZone;
        if (!isNaN(fastRepairCount)) {
            heroFastRepair = fastRepairCount;
        }

        setRadiationWarning(radiation);
    }

    function handlePacket_noAttack(parts, i) {
        lastNoAttackZoneTime = performance.now();
        addInfoMessage("Zone de paix (no-attack)");
    }

    function handlePacket_O(parts, i) {
        addInfoMessage("Out of range");
        
        // On cherche qui était la cible (celle qu'on voulait attaquer ou celle qu'on a lock)
        const lockedId = attackIntentTargetId !== null ? attackIntentTargetId : (selectedTargetId !== null ? selectedTargetId : currentLaserTargetId);

        if (lockedId != null) {
            // 1. On garde le verrouillage (Cercle rouge)
            if (selectedTargetId === null) {
                selectedTargetId = lockedId;
                sendSelectShip(selectedTargetId); // On s'assure que le serveur sait qu'on lock
            }

            // 2. IMPORTANT : On n'est plus en train de tirer activement (le serveur a dit Stop)
            currentLaserTargetId = null;

            // 3. MAIS on garde l'INTENTION d'attaquer
            attackIntentTargetId = lockedId;
            
            // 4. On prépare la reprise automatique
            pendingRangeResumeTargetId = lockedId;
            pendingRangeResumeMessage = true;
            rangeProtectedTargetId = lockedId;
        }
        
        // On coupe les lasers visuels immédiatement
        laserBeams.length = 0;
    }

    function handlePacket_X(parts, i) {
        const resumeId = pendingRangeResumeTargetId ?? attackIntentTargetId ?? selectedTargetId;
        const shouldAnnounce = pendingRangeResumeMessage && resumeId != null;
        if (resumeId != null) {
            if (selectedTargetId === null) selectedTargetId = resumeId;
            currentLaserTargetId = resumeId;
            attackIntentTargetId = resumeId;
            sendLaserAttack(resumeId);
            if (shouldAnnounce) addInfoMessage("The battle continues");
        }
        if (resumeId != null) resetPendingRangeResume(resumeId);
        if (resumeId != null && rangeProtectedTargetId === resumeId) rangeProtectedTargetId = null;
    }

    function handlePacket_sabShot(parts, i) {
        if (parts.length < i + 2) return;

        const attackerId = parseInt(parts[i], 10);
        const targetId = parseInt(parts[i + 1], 10);

        if (isNaN(attackerId) || isNaN(targetId)) return;

        const attackerSnap = snapshotEntityById(attackerId);
        const targetSnap = snapshotEntityById(targetId);
        if (!attackerSnap || !targetSnap) return;

        // FULL_MERGE_AS : laser4 (SAB-50) est un laser absorbeur "playLoop".
        // Le clip part de la cible vers l'attaquant, sans rotation supplémentaire,
        // et se resserre progressivement (scale -> 0.1).
        const startX = targetSnap.id === heroId ? shipX : targetSnap.x;
        const startY = targetSnap.id === heroId ? shipY : targetSnap.y;
        const endX = attackerSnap.id === heroId ? shipX : attackerSnap.x;
        const endY = attackerSnap.id === heroId ? shipY : attackerSnap.y;

        const duration = (typeof SAB_SHOT_DURATION_MS !== "undefined") ? SAB_SHOT_DURATION_MS : 1000;

        const now = performance.now();

        // FULL_MERGE_AS : une seule instance de sab visuel par couple Attaquant/Cible.
        // On remet à jour l'instance existante au lieu d'empiler des copies.
        let updated = false;
        for (let idx = sabShots.length - 1; idx >= 0; idx--) {
            const shot = sabShots[idx];
            if (shot.attackerId === attackerId && shot.targetId === targetId) {
                if (!updated) {
                    shot.startX = startX;
                    shot.startY = startY;
                    shot.endX = endX;
                    shot.endY = endY;
                    shot.duration = duration;
                    shot.createdAt = now;
                    updated = true;
                } else {
                    sabShots.splice(idx, 1);
                }
            }
        }

        if (updated) return;

        sabShots.push({
            attackerId,
            targetId,
            startX,
            startY,
            endX,
            endY,
            startScale: 1,
            endScale: 0.1,
            duration,
            createdAt: now
        });
    }

    function handlePacket_rocketAttack(parts, i) {
        if (parts.length < i + 6) return;
        const attackerId = parseInt(parts[i], 10);
        const targetId = parseInt(parts[i + 1], 10);
        const heavyFlag = parts[i + 2] === "H";
        const rocketId = parseInt(parts[i + 3], 10);
        const patternId = parseInt(parts[i + 4], 10);
        const autoFlag = !!parseInt(parts[i + 5] || "0", 10);

        if (isNaN(attackerId) || isNaN(targetId)) return;

        const beamAngle = computeShieldImpactAngle(attackerId, targetId);
        rocketAttacks.push({
            attackerId,
            targetId,
            rocketId: isNaN(rocketId) ? 0 : rocketId,
            patternId: isNaN(patternId) ? 0 : patternId,
            heavy: heavyFlag,
            auto: autoFlag,
            angle: beamAngle,
            createdAt: performance.now()
        });
    }

    function handlePacket_laserAttack(parts, i) {
        if (parts.length < i + 5) return;
        const attackerId = parseInt(parts[i], 10);
        const targetId   = parseInt(parts[i + 1], 10);
        const patternId  = parseInt(parts[i + 2], 10);
        const showShieldDamage = !!parseInt(parts[i + 3], 10);
        const skilledLaser = !!parseInt(parts[i + 4], 10);

        if (isNaN(attackerId) || isNaN(targetId)) return;

        const attackerSnap = snapshotEntityById(attackerId);
        const targetSnap = snapshotEntityById(targetId);
        if (!attackerSnap || !targetSnap) return;

        const visual = resolveLaserVisual(patternId, skilledLaser);
        const spriteInfo = getLaserSpriteFrame(visual.spriteId, skilledLaser);
        const laserLength = spriteInfo?.width || LASER_SPRITE_INFO[visual.spriteId]?.width || 0;
        const origin = visual.absorber ? targetSnap : attackerSnap;
        const destination = visual.absorber ? attackerSnap : targetSnap;

        const startX = origin.x;
        const startY = origin.y;
        let endX = destination.x;
        let endY = destination.y;

        const dx = startX - endX;
        const dy = startY - endY;
        const distSq = dx * dx + dy * dy;

        if (!visual.absorber && laserLength > 0) {
            if (distSq < laserLength * laserLength) return;
            const dist = Math.sqrt(distSq);
            const nx = dx / dist;
            const ny = dy / dist;
            endX += nx * laserLength;
            endY += ny * laserLength;
        }

        const angle = Math.atan2(endY - startY, endX - startX);
        const baseDuration = visual.speedMs || DEFAULT_LASER_SPEED_MS;
        // FULL_MERGE_AS : les lasers "playLoop" (SAB-50 / laser4.swf) restent actifs pendant attackLength
        // pour éviter toute coupure visuelle entre deux rafraîchissements.
        const duration = visual.playLoop ? (visual.attackLengthMs || LASER_ATTACK_LENGTH_MS) : baseDuration;

        const shouldDouble = shouldDrawDoubleLaser(attackerId, visual, patternId, attackerSnap);
        const now = performance.now();

        if (visual.playLoop) {
            let reused = false;
            for (let idx = laserBeams.length - 1; idx >= 0; idx--) {
                const beam = laserBeams[idx];
                if (beam.playLoop && beam.attackerId === attackerId && beam.targetId === targetId && beam.patternId === patternId && beam.skilledLaser === skilledLaser) {
                    if (!reused) {
                        beam.startX = startX;
                        beam.startY = startY;
                        beam.endX = endX;
                        beam.endY = endY;
                        beam.duration = duration;
                        beam.createdAt = now;
                        beam.absorber = visual.absorber;
                        beam.showShieldDamage = showShieldDamage;
                        beam.angle = angle;
                        beam.rotation = null;
                        beam.endScale = visual.absorber ? 0.1 : 1;
                        beam.hitHandled = false;
                        reused = true;
                    } else {
                        laserBeams.splice(idx, 1);
                    }
                }
            }
            if (reused) return;
        }

        const beamEntries = shouldDouble
            ? buildDoubleLaserEntries({
                attackerId,
                targetId,
                patternId,
                spriteId: visual.spriteId,
                showShieldDamage,
                skilledLaser,
                angle,
                startX,
                startY,
                endX,
                endY,
                duration,
                visual
            })
            : [{
                attackerId,
                targetId,
                patternId,
                spriteId: visual.spriteId,
                showShieldDamage,
                skilledLaser,
                absorber: visual.absorber,
                rotation: visual.playLoop ? null : angle,
                angle,
                startX,
                startY,
                endX,
                endY,
                duration,
                endScale: visual.absorber ? 0.1 : 1,
                createdAt: now,
                playLoop: visual.playLoop,
                hitHandled: false
            }];

        for (const entry of beamEntries) {
            laserBeams.push(entry);
        }
    }

    function shouldDrawDoubleLaser(attackerId, visual, patternId, attackerSnap) {
        const attacker = attackerSnap || snapshotEntityById(attackerId);
        if (!attacker || attacker.kind !== "player") return false;

        // FULL_MERGE_AS : les joueurs tirent en double avec les munitions basées sur
        // laser1, laser2, laser3, laser5 (ou "laser") et laser6. Le SAB-50 (laser4)
        // reste en tir simple et les NPC conservent leur comportement actuel.
        const spriteId = Number.isFinite(visual?.spriteId) ? visual.spriteId : null;
        const playerDoubleSprites = new Set([1, 2, 3, 5, 6]);
        return spriteId !== null && playerDoubleSprites.has(spriteId);
    }

    function buildDoubleLaserEntries(base) {
        const entries = [];
        const offset = 12;
        const { angle, startX, startY, endX, endY, visual } = base;
        const perpendicular = angle + Math.PI / 2;
        const cosP = Math.cos(perpendicular);
        const sinP = Math.sin(perpendicular);

        const offsets = [-offset, offset];
        for (const o of offsets) {
            const sx = startX + cosP * o;
            const sy = startY + sinP * o;
            const ex = endX + cosP * o;
            const ey = endY + sinP * o;
            const offsetX = cosP * o;
            const offsetY = sinP * o;

            entries.push({
                attackerId: base.attackerId,
                targetId: base.targetId,
                patternId: base.patternId,
                spriteId: base.spriteId,
                showShieldDamage: base.showShieldDamage,
                skilledLaser: base.skilledLaser,
                absorber: visual.absorber,
                rotation: visual.playLoop ? null : angle,
                angle,
                startX: sx,
                startY: sy,
                endX: ex,
                endY: ey,
                offsetX,
                offsetY,
                duration: base.duration,
                endScale: visual.absorber ? 0.1 : 1,
                createdAt: performance.now(),
                playLoop: visual.playLoop,
                hitHandled: false
            });
        }
        return entries;
    }

    // Y (Attack Info - Nettoyé)
    function handlePacket_attackInfo(parts, i) {
        const attackerId = parseInt(parts[i] || "", 10);
        const targetId   = parseInt(parts[i + 1] || "", 10);
        const hitType    = parts[i + 2] || "";
        const hpRaw      = parts[i + 3];
        const shRaw      = parts[i + 4];
        const deltaRaw   = parts[i + 5];
        const deltaAltRaw = parts[i + 6];

        if (isNaN(targetId)) return;

        const hp     = hpRaw !== undefined ? parseInt(hpRaw, 10) : NaN;
        const shield = shRaw !== undefined ? parseFloat(shRaw) : NaN;

        const applyShieldHit = (id, prev, next) => {
            if (prev != null && !isNaN(next) && next < prev) {
                const angle = getRecentBeamAngleForTarget(id);
                const radius = computeShieldImpactRadius(snapshotEntityById(id));
                const sx = (heroId !== null && id === heroId) ? shipX : (entities[id]?.x || 0);
                const sy = (heroId !== null && id === heroId) ? shipY : (entities[id]?.y || 0);
                spawnShieldBurstAt(sx, sy, "hit", { angle, radius, targetId: id });
            }
        };

        let prevHp = null;
        if (heroId !== null && targetId === heroId) {
            prevHp = heroHp;
            const prevShield = heroShield;
            if (!isNaN(hp))     heroHp = hp;
            if (!isNaN(shield)) {
                applyShieldHit(heroId, prevShield, shield);
                heroShield = shield;
            }
        } else {
            const ent = ensureEntity(targetId);
            prevHp = ent.hp;
            const prevShield = ent.shield;
            if (!isNaN(hp))     ent.hp = hp;
            if (!isNaN(shield)) {
                applyShieldHit(targetId, prevShield, shield);
                ent.shield = shield;
            }
        }

        let delta = deltaRaw !== undefined ? parseInt(deltaRaw, 10) : NaN;
        if (isNaN(delta) && deltaAltRaw !== undefined) {
            const alt = parseInt(deltaAltRaw, 10);
            if (!isNaN(alt)) delta = (hitType === "H") ? alt : -alt;
        }
        if (isNaN(delta) && !isNaN(hp) && prevHp != null) {
            delta = hp - prevHp;
        }

        if (!isNaN(delta) && delta !== 0) {
            pushDamageBubble(targetId, delta, hitType === "H");
        }
    }

    // 0|2|id (Remove Entity - CORRIGÉ : Suppression immédiate si ramassée)
    function handlePacket_remove(parts, i) {
        if (parts.length < i + 1) return;
        const id = parseInt(parts[i], 10);
        if (isNaN(id)) return;
		
        const e = entities[id];
        if (e) {
             const isMyCollection = (pendingCollectBoxId === id) || (typeof collectedBoxRequestIds !== "undefined" && collectedBoxRequestIds.has(id));

             if (e.kind === "box") {
                 if (!isMyCollection && e.boxSpawnTime && (Date.now() - e.boxSpawnTime < 2000)) {
                     return;
                 }
             } else {
                 // MODIFICATION ICI : On force le unlock SEULEMENT si ce n'est pas notre cible d'attaque en cours de poursuite
                 // Cela permet de garder le lock un peu plus longtemps si le serveur lagge
                 forceUnlock(id); 
             }
             
            if (isMyCollection) {
                clearPendingCollectState();
                moveTargetX = null;
                moveTargetY = null;
                isChasingTarget = false;
                if (typeof collectedBoxRequestIds !== "undefined") collectedBoxRequestIds.delete(id);
                if (typeof stopHeroCollectorBeam === "function") stopHeroCollectorBeam();
            }

            if (e.kind === "box") {
                if (typeof clearBoxAnimationState === "function") clearBoxAnimationState(id);
                if (typeof clearOreAnimationState === "function") clearOreAnimationState(id);
            }

            delete entities[id];
            if (loggedEntities.has(id)) loggedEntities.delete(id);
            if (typeof collectedBoxRequestIds !== "undefined") collectedBoxRequestIds.delete(id);
        }
    }
	
	// Gestion des Stations (Packet s)
function handlePacket_s(parts, i) {
    // Le serveur envoie : 0|s|0|1|redStation|1|0|2000|1200
    // parts[i] = "0" (mode), parts[i+1] = "1" (type?)
    // parts[i+2] = NOM (ex: "redStation")
    // parts[i+5] = X
    // parts[i+6] = Y
    
    // Note : L'index dépend de ton ému. Basé sur le code C# : 
    // Compose("s", "0|1|redStation|1|0|2000|1200")
    
    let typeStation = parts[i+2]; // Index 2 après le 's'
    let stationX = parseInt(parts[i+5]);
    let stationY = parseInt(parts[i+6]);

    if (typeStation && !isNaN(stationX) && !isNaN(stationY)) {
        stations.push({
            type: typeStation,
            x: stationX,
            y: stationY
        });
        console.log("Station ajoutée : " + typeStation + " en " + stationX + "," + stationY);
    }
}

   // 0|R|id (Remove Object - CORRIGÉ : Nettoyage propre et Immunité Box)
    function handlePacket_R(parts, i) {
        if (parts.length < i + 1) return;
        
        const id = parseInt(parts[i], 10); 
        if (isNaN(id)) return;

        const e = entities[id];
        if (e) {
            // Est-ce la boîte que je suis en train de ramasser ?
            const isMyCollection = (pendingCollectBoxId === id) || (typeof collectedBoxRequestIds !== "undefined" && collectedBoxRequestIds.has(id));

            if (e.kind === "box") {
                 // Si ce N'EST PAS ma collecte active, on applique l'immunité de 2s
                 // (Empêche la disparition immédiate en cas de conflit packet c/R)
                 if (!isMyCollection && e.boxSpawnTime && (Date.now() - e.boxSpawnTime < 2000)) {
                     return; 
                 }
                 // Si c'est une boite, pas besoin de forceUnlock complet, 
                 // mais on nettoie si c'était notre cible de collecte
            } else {
                 // Si c'est un vaisseau/NPC, on DOIT déverrouiller
                 // Car l'entité va être supprimée de la mémoire juste après
                 forceUnlock(id);
            }
            
            // Nettoyage des variables de mouvement si c'était ma collecte
            if (isMyCollection) {
                clearPendingCollectState();
                moveTargetX = null;
                moveTargetY = null;
                isChasingTarget = false;
                if (typeof collectedBoxRequestIds !== "undefined") collectedBoxRequestIds.delete(id);
                if (typeof stopHeroCollectorBeam === "function") stopHeroCollectorBeam();
            }

            if (e.kind === "box") {
                if (typeof clearBoxAnimationState === "function") clearBoxAnimationState(id);
                if (typeof clearOreAnimationState === "function") clearOreAnimationState(id);
            }

            // Suppression définitive de l'entité en mémoire
            delete entities[id];

            // Nettoyage des logs de debug (optionnel)
            if (loggedEntities.has(id)) loggedEntities.delete(id);
            if (typeof collectedBoxRequestIds !== "undefined") collectedBoxRequestIds.delete(id);
        }
    }

    // 0|y|TYPE|amount|total (Rewards)
    function handlePacket_y(parts, i) {
        const type = parts[i];
        const amount = parseInt(parts[i+1], 10);
        const total  = parseInt(parts[i+2], 10);

        if (isNaN(amount)) return;

        let label = type;
        if (type === "CRE") {
            label = "Crédits";
            heroCredits = total;
        } 
        else if (type === "URI") {
            label = "Uridium";
            heroUridium = total;
        }
        else if (type === "EP") {
            label = "XP";
            heroXp = total;
        }
        else if (type === "HON") {
            label = "Honneur";
            heroHonor = total;
        }

        const sign = amount >= 0 ? "+" : "";
        
        damageBubbles.push({
            entityId: heroId,
            value: sign + amount + " " + label,
            isHeal: true,
            createdAt: performance.now()
        });

        addInfoMessage("Reçu : " + sign + amount + " " + label);
    }
	// --- FONCTION UTILITAIRE POUR DÉVERROUILLER UNE CIBLE ---
    function forceUnlock(targetId) {
        // 1. Si on ciblait cette entité (Lock visuel / Rond rouge)
        if (selectedTargetId === targetId) {
            selectedTargetId = null;
        }

        // 2. Si on tirait dessus (Laser actif)
        if (currentLaserTargetId === targetId) {
            currentLaserTargetId = null;
            // On envoie le STOP au serveur pour être propre et éviter les lasers fantômes
            sendLaserStop(targetId, true);
        }

        // 3. Si on avait l'intention d'attaquer (Mémoire d'attaque)
        // On supprime cette intention car l'entité n'existe plus (morte ou partie)
        if (attackIntentTargetId === targetId) {
            attackIntentTargetId = null;
        }

        // 4. Nettoyage des flags de reprise de portée (Packet O) [IMPORTANT]
        // Si on ne nettoie pas ça, le client peut bloquer sur une reprise d'attaque impossible
        if (pendingRangeResumeTargetId === targetId) {
             resetPendingRangeResume(targetId);
        }
        if (rangeProtectedTargetId === targetId) {
             rangeProtectedTargetId = null;
        }

        // 5. Arrêt du mouvement de poursuite
        if (isChasingTarget) {
            // On arrête de courir après une cible qui n'existe plus
            isChasingTarget = false;
            moveTargetX = null;
            moveTargetY = null;
        }

        // 6. Nettoyage visuel des lasers (Correction du nom de variable : laserBeams)
        // Cela supprime les traits de lasers visuels immédiatement
        if (typeof laserBeams !== 'undefined') {
             for (let i = laserBeams.length - 1; i >= 0; i--) {
                const b = laserBeams[i];
                // On supprime si le laser vient de cette cible OU va vers cette cible
                if (b.attackerId === targetId || b.targetId === targetId) {
                    laserBeams.splice(i, 1);
                }
            }
        }

        if (typeof sabShots !== 'undefined') {
             for (let i = sabShots.length - 1; i >= 0; i--) {
                const s = sabShots[i];
                if (s.attackerId === targetId || s.targetId === targetId) {
                    sabShots.splice(i, 1);
                }
            }
        }
    }

   // 0|K|id (Explosion + Mort - CORRIGÉ : Protège les boîtes fraîches)
    function handlePacket_K(parts, i) {
        const id = parseInt(parts[i], 10); 
        const e = entities[id];
        
        // 1. Explosion visuelle
        // On affiche l'explosion à la dernière position connue
        if (e || id === heroId) {
            const entityX = (id === heroId) ? shipX : (e ? e.x : 0);
            const entityY = (id === heroId) ? shipY : (e ? e.y : 0);
            const isBig = (id === heroId || (e && e.kind === "player"));
            
            spawnExplosionAt(entityX, entityY, isBig);
        }

        // 2. Si c'est le HÉROS qui meurt
        if (id === heroId) {
            console.log("[MORT] Vaisseau détruit !");
            addInfoMessage("VAISSEAU DÉTRUIT !");
            heroHp = 0;
            heroShield = 0;
            moveTargetX = null;
            moveTargetY = null;
            isChasingTarget = false;
            attackIntentTargetId = null;
            currentLaserTargetId = null;
            if (typeof activeLasers !== 'undefined') activeLasers = [];
            if (typeof updateHtmlWindows === 'function') updateHtmlWindows();
            return;
        }

        // 3. Si c'est une autre entité
        if (e) {
            // Si l'entité est une BOÎTE, on ne la supprime jamais via K (explosion).
            // Le Flash laisse la boîte jusqu'à un packet de suppression dédié (2/R) ou expiration.
            if (e.kind === "box") {
                forceUnlock(id);
                return;
            }

            // Sinon, on nettoie (c'était un vaisseau, il est mort)
            forceUnlock(id);
            delete entities[id];

            if (loggedEntities.has(id)) loggedEntities.delete(id);
        }
    }
	
	
	
	// ========================================================
    // GESTIONNAIRE PAQUETS DE BASE DE DONNÉES
    // ========================================================

    // E : Mise à jour du Cargo (Ore)
    // Format : E|prometium|endurium|terbium|xenomit|prometid|duranium|promerium|sep|palladium
    function handlePacket_E(parts, i) {
        if (parts.length < i + 9) return;
        
        // Stockage dans une structure globale pour être accessible à la fenêtre
        window.oreCargo = {
            prometium: parseInt(parts[i], 10) || 0,
            endurium:  parseInt(parts[i + 1], 10) || 0,
            terbium:   parseInt(parts[i + 2], 10) || 0,
            xenomit:   parseInt(parts[i + 3], 10) || 0,
            prometid:  parseInt(parts[i + 4], 10) || 0,
            duranium:  parseInt(parts[i + 5], 10) || 0,
            promerium: parseInt(parts[i + 6], 10) || 0,
            palladium: parseInt(parts[i + 8], 10) || 0
        };
        console.log("[LABO] Cargo reçu:", window.oreCargo);
        // La fenêtre Labo devra être mise à jour ici
    }

        function handlePacket_T(parts, i) {
        // Paquet info CPU / Trade Drone (inspiré de assembleCPUInfo du client Flash)
        // Format prévu (pour TON futur émulateur) :
        //  - "T|HM7|AMOUNT"
        //  - ou "0|T|HM7|AMOUNT"
        
        let start = i;

        // Cas où la ligne commence par "0|T|..."
        if (parts[0] === "0" && parts[1] === "T") {
            start = 2;
        }

        if (parts.length < start + 2) return;

        const typeRaw = parts[start] || "HM7";
        const amount  = parseInt(parts[start + 1], 10);

        if (isNaN(amount)) return;

        const type = typeRaw.toUpperCase();

        if (type === "HM7") {
            cpuItems.HM7.amount  = amount;
            cpuItems.HM7.hasItem = (amount > 0);

            if (amount <= 0) {
                addInfoMessage("Trade Drone HM7 épuisé.");
            } else {
                addInfoMessage("Trade Drone HM7 : " + amount + " utilisation(s) restante(s).");
            }
        }

        console.log("[CPU] T packet reçu :", type, amount);
    }


    // b : Prix des minerais (Non utilisé directement par le Labo mais nécessaire)
    // Format: b|prometium_price|endurium_price|...
    function handlePacket_b(parts, i) {
        if (parts.length < i + 5) return;
        window.orePrices = {
            prometium: parseInt(parts[i], 10) || 0,
            endurium:  parseInt(parts[i + 1], 10) || 0,
            terbium:   parseInt(parts[i + 2], 10) || 0,
            // ... autres prix ...
        };
    }

    function handlePacket_g(parts, i) {
        const keys = [1, 2, 3, 11, 12, 13];
        for (let idx = 0; idx < keys.length && (i + idx) < parts.length; idx++) {
            const price = parseInt(parts[i + idx], 10);
            if (!isNaN(price)) {
                labPrices[keys[idx]] = price;
            }
        }
    }

    // LAB : Statut du Laboratoire (Durées de buff, niveaux de raffinage)
    // Format : LAB|UPD|INFO|LASER|level|duration|ROCKET|level|duration|...
    function handlePacket_LAB(parts, i) {
        if (parts.length < i + 1) return;
        const subAction = parts[i];

        if (subAction === "UPD" || subAction === "INFO") {
            const data = {};
            // Simplification: le Labo est complexe. On va juste stocker ce qui est nécessaire.
            // On peut s'attendre à recevoir : LAB|UPD|LASER|1|100|ROCKET|1|100|DRIVING|1|10|SHIELD|1|10
            
            // Si l'objectif est d'implémenter l'action PROD, on peut ignorer la lecture complète
            // de l'état des buffs pour l'instant et se concentrer sur l'envoi.
            console.log("[LABO] État Labo reçu. Prêt pour la production.");
        }
    }

    function handlePacket_TX(parts, i) {
        const action = parts[i];
        if (action === "S") {
            for (let idx = i + 1; idx < parts.length; idx++) {
                const val = parseInt(parts[idx], 10);
                if (!isNaN(val)) {
                    techCooldowns[idx - (i + 1)] = val;
                }
            }
            renderActionDrawerItems();
        } else if (action === "A" || action === "D") {
            const code = parts[i + 2];
            if (code) {
                addInfoMessage((action === "A" ? "Tech activée : " : "Tech arrêtée : ") + code);
            }
        }
    }
	
	// ========================================================
// GESTIONNAIRE PAQUET 7 (Initialisation des Settings au Login)
// ========================================================
function handlePacket_7(parts, i) {
    // Le serveur envoie 0|7|CLE|VALEUR (répété plusieurs fois)
    const settingKey = parts[i];
    const settingValue = parts[i + 1];

    if (settingKey && settingValue !== undefined) {
        // Mise à jour de l'état local pour persister le setting
        updateLocalSetting(settingKey, settingValue);
    }
}

    function handlePacket_QuestFM(parts, i) {
        if (parts.length < i + 1) return;

        const sub = parts[i]; // "ini", "upd", "p", "a", "c", "f"

        switch (sub) {
            case "ini": {
                const questData = parts[i + 1];      // XML
                const category  = parts[i + 2] || ""; // optionnel

                if (!questData) {
                    console.warn("[QUEST] Paquet ini sans données.");
                    return;
                }

                initQuestFromServer(questData, category);
                break;
            }

            case "upd": {
                const questId = parseInt(parts[i + 1] || "0", 10);
                const mode    = parts[i + 2]; // "o" ou "i"

                if (mode === "i") {
                    const condId     = parseInt(parts[i + 3] || "0", 10);
                    const current    = parseInt(parts[i + 4] || "0", 10);
                    const visibility = parseInt(parts[i + 5] || "0", 10);
                    const runstate   = !!parseInt(parts[i + 6] || "0", 10);

                    updateQuestCondition(questId, condId, current, visibility, runstate);
                }
                break;
            }

            case "p": {
                const questId = parseInt(parts[i + 1] || "0", 10);
                privilegeQuestById(questId);
                break;
            }

            case "a": {
                const questId = parseInt(parts[i + 1] || "0", 10);
                const param2  = parseInt(parts[i + 2] || "0", 10);
                setQuestAccomplished(questId, param2);
                break;
            }

            case "c": {
                const questId = parseInt(parts[i + 1] || "0", 10);
                setQuestCancelled(questId);
                break;
            }

            case "f": {
                const questId = parseInt(parts[i + 1] || "0", 10);
                setQuestFailed(questId);
                break;
            }

            default: {
                console.warn("[QUEST] Sous-opcode QUESTFM inconnu :", sub, "parts=", parts);
                break;
            }
        }
    }



    // -------------------------------------------------
    // 4. CLASSIFICATION & VISIBILITÉ