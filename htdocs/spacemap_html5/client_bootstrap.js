    function render(now) {
        const dt = (now - lastTime) / 1000;
        lastTime = now;
        shieldAnimTime += dt;

        // --- 1. MISES À JOUR LOGIQUES ---
        reinforceLockState();
        updateChaseMovement();
        updateHeroLocalMovement(dt);
        updateInterpolations();
        updateCombat();
        updateCombatRotations();
        updateActionCooldowns();
        updateShieldEffects(now);

        updatePortalJumpEffects(now);
        
        updateLaserBeams(now);
        updateRocketAttacks(now);
        updateDamageBubbles(now);
        updateShieldBursts(now);
        updateExplosions(now);

        // Centrage caméra
        cameraX = shipX;
        cameraY = shipY;

        // --- 2. DESSIN (L'ordre est important !) ---

        // A. Fond d'écran (image de fond de la carte)
        drawMapBackground();

        // B. Stations (Elles sont au sol, donc on les dessine AVANT le reste)
        if (typeof stations !== 'undefined' && typeof stationImages !== 'undefined') {
            for (let s of stations) {
                let img = stationImages[s.type];
                if (img && img.complete) {
                    let drawX = mapToScreenX(s.x);
                    let drawY = mapToScreenY(s.y);
                    // On dessine l'image centrée
                    ctx.drawImage(img, drawX - (img.width / 2), drawY - (img.height / 2));
                }
            }
        }

        // C. Portails
        drawPortals();

        // D. Entités (NPCs, Ennemis, Boîtes)
        // On les dessine APRES les stations pour qu'ils marchent "dessus"
        drawEntities();

        // E. Votre Vaisseau (Héros)
        // On le dessine EN DERNIER pour qu'il soit toujours visible par-dessus tout le monde
        drawShip();

        // F. Effets Spéciaux (Toujours au-dessus)
        drawPortalJumpEffects();
        drawShieldBursts();
        drawExplosions();
        drawRocketAttacks();
        drawLaserBeams();
        drawDamageBubbles();

        // G. Interfaces (HUD, Minimap, Fenêtres)
        drawRadiationOverlay();
        drawPvpOverlay();

        drawMiniMap();

        updateHtmlWindows();
        drawQuickbar();
        drawDebugInfo();
        drawTooltip();
        
        heroSmbJustUsed = false;
        requestAnimationFrame(render);
    }
	
	// Fonction pour cibler un membre quand on clique sur son nom dans la liste
    window.selectGroupMember = function(id) {
        // Si le joueur existe
        if (id && entities[id]) {
            selectedTargetId = id; // On mémorise son ID (C'est ça qui sert au bouton Suivre)
            sendSelectShip(id);    // On envoie la sélection au serveur
            
            // Bonus : On met son nom dans la case d'invitation
            const groupInput = document.getElementById('groupInputName');
            if (groupInput && entities[id].name) {
                groupInput.value = entities[id].name;
            }
        }
    };
    initGlobalButtonStyles();           // styles des boutons
    initGlobalTextFieldStyles();        // styles TextInput / TextArea
    initGlobalComboBoxStyles();         // styles ComboBox
    initGlobalSliderStyles();           // styles Sliders
    initGlobalListStyles();             // wrappers List / CellRenderer
    initGlobalMiscComponentStyles();    // focusRect, thumbIcon, ComponentShim
	initGlobalSpriteDebugStyles();      // sprites génériques restants
    if (typeof initChatInterface === "function") {
        initChatInterface();
    }
	initLabWindow();
	initQuestWindow();
	initSpaceballHUD();
	initGlobalScrollbarStyles();  
	initActionDrawer();
	initDragAndDrop();
	loadInterfaceLayout();
	initWindowManager();
	createGameWindows();
	setInterval(() => {
        // On ne met à jour que si le menu existe
        if (document.getElementById("actionDrawerContainer")) {
            renderActionDrawerItems();
        }
    }, 100); 
    //
    requestAnimationFrame(render);
	
	function updateHtmlWindows() {
    // --- MISE À JOUR FENÊTRE VAISSEAU (SHIP) ---
    // On vérifie si la fenêtre est censée être ouverte
    if (windowStates && windowStates.ship) {
        const container = document.getElementById('content_ship');
        // Si la fenêtre existe bien dans le HTML
        if (container) {
            // On calcule le pourcentage de vie et bouclier pour la barre colorée
            const hpPct = (heroMaxHp > 0) ? (heroHp / heroMaxHp) * 100 : 0;
            const shPct = (heroMaxShield > 0) ? (heroShield / heroMaxShield) * 100 : 0;

            // On injecte le HTML avec les valeurs à jour
            container.innerHTML = `
                <div style="margin-bottom:6px;">
                    <div style="font-size:10px; color:#0f0;">HP</div>
                    <div style="background:#222; border:1px solid #555; height:12px; position:relative;">
                        <div style="position:absolute; left:0; top:0; bottom:0; width:${hpPct}%; background:linear-gradient(90deg,#4aff4a,#0f0);"></div>
                        <div style="position:absolute; width:100%; text-align:center; font-size:10px; color:#fff;">${heroHp} / ${heroMaxHp}</div>
                    </div>
                </div>
                <div style="margin-bottom:6px;">
                    <div style="font-size:10px; color:#3bc5ff;">SHIELD</div>
                    <div style="background:#222; border:1px solid #555; height:10px; position:relative;">
                        <div style="position:absolute; left:0; top:0; bottom:0; width:${shPct}%; background:linear-gradient(90deg,#3bc5ff,#46e0ff);"></div>
                        <div style="position:absolute; width:100%; text-align:center; font-size:10px; color:#fff;">${heroShield} / ${heroMaxShield}</div>
                    </div>
                </div>
                <div class="uiValueRow"><span class="label">Cargo</span><span>${heroCargo}/${heroMaxCargo}</span></div>
                <div class="uiValueRow"><span class="label">Config</span><span>${heroConfig}</span></div>
            `;
        }
    }

    // --- MISE À JOUR FENÊTRE UTILISATEUR (USER) ---
    if (windowStates && windowStates.user) {
        const container = document.getElementById('content_user');
        if (container) {
            container.innerHTML = `
                <div class="uiValueRow"><span class="label">Niveau</span><span>${heroLevel}</span></div>
                <div class="uiValueRow"><span class="label">XP</span><span>${heroXp.toLocaleString()}</span></div>
                <div class="uiValueRow"><span class="label">Honneur</span><span>${heroHonor.toLocaleString()}</span></div>
                <div class="uiValueRow"><span class="label">Jackpot</span><span>${heroJackpot.toFixed(2)}</span></div>
                <div class="uiValueRow"><span class="label">Crédits</span><span>${heroCredits.toLocaleString()}</span></div>
                <div class="uiValueRow"><span class="label">Uridium</span><span>${heroUridium.toLocaleString()}</span></div>
                <div class="uiValueRow"><span class="label">Keys</span><span>${heroBootyKeys}</span></div>
            `;
        }
    }
    
    // --- MISE À JOUR FENÊTRE GROUPE (Version Finale Flash) ---
    if (windowStates && windowStates.group) {
        const container = document.getElementById('content_group');
        
        if (container) {
            // 1. INITIALISATION (On crée la structure une seule fois)
            if (!document.getElementById('grp_ui_root')) {
                container.innerHTML = `
                    <div id="grp_ui_root" style="display:flex; flex-direction:column; height:100%; font-family:Arial;">
                        
                        <div id="grp_members_list" style="flex:1; overflow-y:auto; padding:2px; min-height:50px;"></div>

                        <div style="height:30px; background:rgba(0,0,0,0.6); border-top:1px solid #444; display:flex; align-items:center; padding:2px; gap:2px;">
                            <input type="text" id="groupInputName" placeholder="Nom..." style="flex:1; background:#111; border:1px solid #555; color:#fff; font-size:10px; padding:2px;">
                            <button id="btnGrpInvite" title="Inviter" style="width:22px; background:#4a6b8c; color:#fff; border:1px solid #aaa; cursor:pointer;">✉</button>
                            <button id="btnGrpBlock" title="Bloquer" style="width:22px; background:#222; color:#fff; border:1px solid #555; cursor:pointer;">Ø</button>
                        </div>

                        <div style="height:30px; background:rgba(0,0,0,0.8); display:flex; justify-content:space-around; align-items:center; padding:2px; border-top:1px solid #444;">
                            <div id="btnGrpLeave" title="Quitter" style="cursor:pointer; width:24px; background:#300; border:1px solid #f00; color:#f00; text-align:center;">[x]</div>
                            <div id="btnGrpPing" title="Ping" style="cursor:pointer; width:24px; background:#333; border:1px solid #aaa; color:#fff; text-align:center;">⌖</div>
                            
                            <div id="btnGrpFollow" title="Suivre (Rejoindre)" style="cursor:pointer; width:24px; background:#333; border:1px solid #aaa; color:#fff; text-align:center;">➔</div>
                        </div>
                    </div>
                `;

                // --- CONFIGURATION DES BOUTONS ---
                
                document.getElementById('btnGrpInvite').onclick = function() {
                    const name = document.getElementById('groupInputName').value;
                    if (name) sendRaw("ps|inv|name|" + name);
                };

                document.getElementById('btnGrpBlock').onclick = function() {
                    sendRaw("ps|blk"); // Commande serveur pour bloquer/débloquer
                };

                document.getElementById('btnGrpLeave').onclick = function() {
                    if(confirm("Quitter ?")) sendRaw("ps|lv");
                };

                document.getElementById('btnGrpPing').onclick = function() {
                    groupPingMode = !groupPingMode;
                    addInfoMessage("Mode Ping : " + (groupPingMode ? "ON" : "OFF"));
                };

                // Follow (Suivre la cible sélectionnée SI elle est dans le groupe)
                document.getElementById('btnGrpFollow').onclick = function() {
                    const target = entities[selectedTargetId];
                    
                    // 1. VÉRIFICATIONS : Si pas de cible ou cible hors groupe
                    if (!selectedTargetId || !target || !groupMembers[selectedTargetId]) {
                        addInfoMessage("Sélectionnez un membre du groupe.");
                        return;
                    }

                    // 2. ENVOI de la commande d'état (ps|flw) au serveur pour le journal/état
                    sendRaw("ps|flw|" + selectedTargetId);
                    
                    // 3. ACTIONS CLIENT (Déplacement forcé vers les coordonnées de l'ami)
                    
                    // a) Définir la destination vers la position actuelle de l'ami
                    moveTargetX = target.x;
                    moveTargetY = target.y;
                    
                    // b) Envoyer un paquet de mouvement pour démarrer le déplacement côté serveur
                    sendMoveToServer(target.x, target.y);
                    
                    addInfoMessage("Déplacement vers " + target.name + " activé.");
                };
            }

            // 2. MISE À JOUR LISTE MEMBRES
            const listContainer = document.getElementById('grp_members_list');
            if (listContainer) {
                const members = Object.values(groupMembers);
                let html = "";
                if (members.length === 0) html = "<div style='text-align:center; color:#555; font-size:10px;'>Seul</div>";
                else {
                    members.forEach(m => {
                        const mHp = (m.maxHp > 0) ? (m.hp / m.maxHp) * 100 : 0;
                        const mSh = (m.maxShield > 0) ? (m.shield / m.maxShield) * 100 : 0;
                        // On ajoute l'événement onclick pour sélectionner le membre
                        html += `
                            <div style="margin-bottom:2px; background:rgba(30,30,30,0.8); border:1px solid #444; padding:2px; cursor:pointer;" 
                                 onclick="window.selectGroupMember(${m.id})">
                                <div style="display:flex; justify-content:space-between; font-size:9px; color:#fff;">
                                    <span>${m.name}</span>
                                    <span style="color:#aaa;">${m.mapId === cfg.mapID ? '' : 'Map '+m.mapId}</span>
                                </div>
                                <div style="width:100%; height:3px; background:#222;"><div style="height:100%; width:${mHp}%; background:#0f0;"></div></div>
                                <div style="width:100%; height:3px; background:#222;"><div style="height:100%; width:${mSh}%; background:#00aaff;"></div></div>
                            </div>`;
                    });
                }
                if (listContainer.innerHTML !== html) listContainer.innerHTML = html;
            }
        }
    }
	
	// --- MISE À JOUR FENÊTRE LOG (JOURNAL) ---
    if (windowStates && windowStates.log) {
        const container = document.getElementById('content_log');
        if (container) {
            // On prend les messages stockés dans infoMessages (ta liste de messages verts)
            // et on les affiche proprement ligne par ligne
            let html = "";
            // infoMessages contient les messages récents. On les affiche.
            infoMessages.forEach(msg => {
                html += `<div style="border-bottom:1px solid #333; padding:2px; font-size:10px; color:#ccc;">${msg}</div>`;
            });
            container.innerHTML = html;
        }
    }
}