    function getStarfieldAnchor(cameraXValue, cameraYValue) {
        const camX = typeof cameraXValue === "number" ? cameraXValue : 0;
        const camY = typeof cameraYValue === "number" ? cameraYValue : 0;
        const halfW = canvas ? canvas.width / 2 : 0;
        const halfH = canvas ? canvas.height / 2 : 0;

        return {
            x: halfW - camX,
            y: halfH - camY
        };
    }

    function ensureStarfieldInitialized() {
        if (!starfieldEnabled) return;
        const width = canvas ? canvas.width : 0;
        const height = canvas ? canvas.height : 0;
        if (!width || !height) return;

        const needsReinit =
            !starfieldState ||
            starfieldState.width !== width ||
            starfieldState.height !== height;

        if (!needsReinit) return;

        const starCount = STARFIELD_DEFAULT_COUNT;
        const stars = [];
        for (let i = 0; i < starCount; i++) {
            stars.push({
                x: Math.random() * width,
                y: Math.random() * height,
                speed: Math.random() * (STARFIELD_SPEED_MAX - STARFIELD_SPEED_MIN) + STARFIELD_SPEED_MIN
            });
        }

        starfieldState = {
            width,
            height,
            stars,
            velocityX: 0,
            velocityY: 0,
            lastTick: performance.now(),
            timeAccumulator: 0
        };

        lastStarfieldAnchor = getStarfieldAnchor(cameraX, cameraY);
    }

    function resetStarfieldState() {
        starfieldState = null;
        lastStarfieldAnchor = getStarfieldAnchor(cameraX, cameraY);

        ensureStarfieldInitialized();
    }

    function setStarfieldEnabled(enabled, color = STARFIELD_DEFAULT_COLOR) {
        starfieldEnabled = !!enabled;
        starfieldColor = Number.isFinite(color) ? color : STARFIELD_DEFAULT_COLOR;
        resetStarfieldState();
    }

    function setStarfieldStateFromMap(mapId, settings = null) {
        const cfg = settings || (mapStarfieldSettingsById ? mapStarfieldSettingsById[mapId] : null) || {
            enabled: DEFAULT_STARFIELD_ENABLED,
            color: STARFIELD_DEFAULT_COLOR
        };

        setStarfieldEnabled(cfg.enabled, cfg.color);
    }

    function updateStarfield(cameraXValue, cameraYValue) {
        if (!starfieldEnabled) return;
        ensureStarfieldInitialized();
        if (!starfieldState || !starfieldState.stars.length) return;

        const targetAnchor = getStarfieldAnchor(cameraXValue, cameraYValue);
        const deltaX = targetAnchor.x - lastStarfieldAnchor.x;
        const deltaY = targetAnchor.y - lastStarfieldAnchor.y;

        let moveX = deltaX || 0;
        let moveY = deltaY || 0;

        if (moveX === 0 && moveY === 0) {
            moveX = STARFIELD_IDLE_SPEED;
            moveY = 0;
        }

        starfieldState.velocityX = moveX;
        starfieldState.velocityY = moveY;

        const now = performance.now();
        const tickDuration = 1000 / STARFIELD_FPS;
        starfieldState.timeAccumulator += Math.max(0, now - (starfieldState.lastTick || now));

        while (starfieldState.timeAccumulator >= tickDuration) {
            starfieldState.stars.forEach((star) => {
                const nextX = star.x + starfieldState.velocityX * star.speed;
                const nextY = star.y + starfieldState.velocityY * star.speed;

                star.x = nextX < 0 ? nextX + starfieldState.width : nextX > starfieldState.width ? nextX - starfieldState.width : nextX;
                star.y = nextY < 0 ? nextY + starfieldState.height : nextY > starfieldState.height ? nextY - starfieldState.height : nextY;
            });

            starfieldState.timeAccumulator -= tickDuration;
        }

        starfieldState.lastTick = now;
        lastStarfieldAnchor = targetAnchor;
    }

    function drawStarfield() {
        if (!starfieldEnabled || !starfieldState || !starfieldState.stars.length) return;

        ctx.save();
        ctx.setTransform(1, 0, 0, 1, 0, 0);
        ctx.globalCompositeOperation = "lighter";
        ctx.fillStyle = `#${(starfieldColor >>> 0).toString(16).padStart(6, "0")}`;

        starfieldState.stars.forEach((star) => {
            const x = Math.round(star.x);
            const y = Math.round(star.y);
            ctx.fillRect(x, y, 1, 1);
        });

        ctx.restore();
    }

    function drawMapBackground() {
        // Clear the full viewport every frame to avoid ghosting / repetition when the
        // background image is smaller than the canvas.
        ctx.fillStyle = "black";
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        updateStarfield(cameraX, cameraY);

        if (currentBackgroundLayers && currentBackgroundLayers.length) {
            const scale = gameScale || 1;
            const orderedLayers = [...currentBackgroundLayers].sort((a, b) => (a.layer || 0) - (b.layer || 0));

            orderedLayers.forEach((layer) => {
                const bg = layer.image;
                if (!bg || !bg.complete || bg.width === 0 || bg.height === 0) return;

                const parallax = layer.parallax || DEFAULT_BACKGROUND_PARALLAX;
                const drawWidth = bg.width * scale;
                const drawHeight = bg.height * scale;

                if (drawWidth < 1 || drawHeight < 1) return;

                const offsets = layer.offsets || { x: layer.shiftX || 0, y: layer.shiftY || 0 };
                const screenX = canvas.width / 2 - (cameraX / parallax) * scale + offsets.x * scale;
                const screenY = canvas.height / 2 - (cameraY / parallax) * scale + offsets.y * scale;

                const previousSmoothing = ctx.imageSmoothingEnabled;
                ctx.imageSmoothingEnabled = false;
                ctx.drawImage(bg, screenX, screenY, drawWidth, drawHeight);
                ctx.imageSmoothingEnabled = previousSmoothing;
            });
        }

        drawStarfield();
    }

    const ENGINE_FRAME_DURATION = 1000 / ((ENGINE_SPRITE_DEFS[DEFAULT_ENGINE_KEY]?.fps) || ENGINE_ANIM_FPS || 20);
    const ENGINE_MOVING_MAX_TICKS = 3;
    const engineAnimationState = {};
    const engineSmokeState = {};

    // --- BOX (CARGO) ANIMATION SPRITES ---
    const BOX_ANIMATION_FRAME_DURATION = 25; // ms (Flash client timer cadence)
    const BONUS_BOX_ANIMATION_FRAME_DURATION = 25; // ms (Flash timer cadence matches Flash box loop)
    const BOX_SPRITE_CONFIG = {
        cargo: { basePath: "graphics/collectables/box1/", frameCount: 25 },
        bonus: { basePath: "graphics/collectables/box2/", frameCount: 24 },
        booty: { basePath: "graphics/collectables/pirateBootyBox/", frameCount: 25 }
    };
    const boxSpriteCache = {};
    const boxAnimationStates = {};
    let bonusBoxFrameIndex = 0;
    let bonusBoxAnimationTimer = null;
    const BOOTY_KEY_SPRITE_PATH = UI_SPRITES.iconBootyKey || "graphics/ui/ui/images/59_shipInfoIcon_bootykey.png";
    const bootyKeySprite = getUiImage(BOOTY_KEY_SPRITE_PATH);

    // --- ORE ANIMATION SPRITES ---
    const ORE_ANIMATION_FRAME_DURATION = 25; // ms, aligné sur le timer Flash
    const ORE_SPRITE_CONFIG = {
        oreBlue:   { basePath: "graphics/collectables/oreBlue/",   frameCount: 26 },
        oreRed:    { basePath: "graphics/collectables/oreRed/",    frameCount: 26 },
        oreYellow: { basePath: "graphics/collectables/oreYellow/", frameCount: 26 }
    };
    const oreSpriteCache = {};
    const oreAnimationStates = {};

    // --- COLLECTOR BEAM (effet local au joueur) ---
    const COLLECTOR_BEAM_FRAME_COUNT = 15;
    const COLLECTOR_BEAM_FPS = 30;
    const COLLECTOR_BEAM_FRAME_DURATION = 1000 / COLLECTOR_BEAM_FPS;
    const COLLECTOR_BEAM_DEFAULT_DURATION_MS = 1500;
    const COLLECTOR_BEAM_BASE_PATH = "graphics/effects/loopingCollectorBeam/";

    const collectorBeamCache = [];
    let heroCollectorBeamState = null;

    function getCollectorBeamFrame(frameIndex) {
        const idx = ((frameIndex % COLLECTOR_BEAM_FRAME_COUNT) + COLLECTOR_BEAM_FRAME_COUNT) % COLLECTOR_BEAM_FRAME_COUNT;
        const path = `${COLLECTOR_BEAM_BASE_PATH}${idx + 1}.png`;
        if (collectorBeamCache[path]) return collectorBeamCache[path];
        const img = new Image();
        img.src = path;
        collectorBeamCache[path] = img;
        return img;
    }

    function startHeroCollectorBeam(durationMs = COLLECTOR_BEAM_DEFAULT_DURATION_MS) {
        const now = performance.now();
        heroCollectorBeamState = {
            frameIndex: 0,
            lastUpdate: now,
            startedAt: now,
            durationMs: durationMs || COLLECTOR_BEAM_DEFAULT_DURATION_MS
        };
    }

    function stopHeroCollectorBeam() {
        heroCollectorBeamState = null;
    }

    function getBoxSpriteConfig(category) {
        if (category === "bonusBox") return BOX_SPRITE_CONFIG.bonus;
        if (category === "bootyBox") return BOX_SPRITE_CONFIG.booty;
        return BOX_SPRITE_CONFIG.cargo;
    }

    function getBoxSpriteFrame(category, frameIndex) {
        const cfg = getBoxSpriteConfig(category);
        const frameCount = cfg.frameCount;
        const idx = ((frameIndex % frameCount) + frameCount) % frameCount;
        const path = `${cfg.basePath}${idx + 1}.png`;
        if (boxSpriteCache[path]) return boxSpriteCache[path];
        const img = new Image();
        img.src = path;
        boxSpriteCache[path] = img;
        return img;
    }

    function clearBoxAnimationState(id) {
        if (id == null) return;
        delete boxAnimationStates[id];
    }

    function getOreSpriteKeyFromType(type, oreSpriteOverride = null) {
        if (oreSpriteOverride) return oreSpriteOverride;
        return ORE_TYPE_SPRITES?.[type] || null;
    }

    function getOreSpriteConfig(spriteKey) {
        if (!spriteKey) return null;
        return ORE_SPRITE_CONFIG[spriteKey] || null;
    }

    function getOreSpriteFrame(spriteKey, frameIndex) {
        const cfg = getOreSpriteConfig(spriteKey);
        if (!cfg) return null;
        const frameCount = cfg.frameCount;
        const idx = ((frameIndex % frameCount) + frameCount) % frameCount;
        const path = `${cfg.basePath}${idx + 1}.png`;
        if (oreSpriteCache[path]) return oreSpriteCache[path];
        const img = new Image();
        img.src = path;
        oreSpriteCache[path] = img;
        return img;
    }

    function clearOreAnimationState(id) {
        if (id == null) return;
        delete oreAnimationStates[id];
    }

    function ensureBonusBoxAnimationTimer() {
        if (bonusBoxAnimationTimer !== null) return;
        bonusBoxAnimationTimer = setInterval(() => {
            bonusBoxFrameIndex = (bonusBoxFrameIndex + 1) % BOX_SPRITE_CONFIG.bonus.frameCount;
        }, BONUS_BOX_ANIMATION_FRAME_DURATION);
    }

    function drawBootyKey(boxScreenX, boxScreenY, now) {
        const img = bootyKeySprite;
        if (img && img.complete && img.width > 0 && img.height > 0) {
            const pulse = 1 + 0.15 * Math.sin((now % 900) / 900 * Math.PI * 2);
            const alpha = 0.75 + 0.25 * Math.sin((now % 650) / 650 * Math.PI * 2);
            const w = img.width * pulse;
            const h = img.height * pulse;
            ctx.save();
            ctx.globalAlpha = alpha;
            ctx.drawImage(img, boxScreenX - w / 2, boxScreenY - h / 2, w, h);
            ctx.restore();
        } else {
            ctx.fillStyle = "#ffffff";
            ctx.beginPath();
            ctx.arc(boxScreenX, boxScreenY, 8, 0, Math.PI * 2, false);
            ctx.fill();
        }
    }

    function updateEngineAnimationState(key, worldX, worldY, forceMoving = false) {
        const now = performance.now();
        const engineFrames = ENGINE_SPRITE_DEFS[DEFAULT_ENGINE_KEY]?.frames?.length
            || ENGINE_SPRITE_DEFS[DEFAULT_ENGINE_KEY]?.frameCount
            || 1;

        const state = engineAnimationState[key] || {
            frameIndex: Math.max(0, engineFrames - 1),
            lastUpdate: now,
            lastFrameChange: now,
            movingTicks: 0,
            lastX: worldX,
            lastY: worldY,
            isMoving: false
        };

        const moved = forceMoving || (worldX !== state.lastX || worldY !== state.lastY);
        if (moved) {
            if (state.movingTicks === 0) {
                state.isMoving = true;
            }
            state.movingTicks = Math.min(ENGINE_MOVING_MAX_TICKS, state.movingTicks + 1);
        }

        const shouldAdvance = now - state.lastFrameChange >= ENGINE_FRAME_DURATION;
        const movingNow = state.isMoving || state.movingTicks > 0;

        if (shouldAdvance) {
            if (movingNow && state.frameIndex > 0) {
                state.frameIndex -= 1;
                state.lastFrameChange = now;
            } else if (!movingNow && state.frameIndex < engineFrames - 1) {
                state.frameIndex += 1;
                state.lastFrameChange = now;
            }
        }

        state.lastX = worldX;
        state.lastY = worldY;
        state.lastUpdate = now;

        if (state.movingTicks > 0) {
            state.movingTicks -= 1;
            if (state.movingTicks === 0) {
                state.isMoving = false;
            }
        }

        engineAnimationState[key] = state;
        return { frameIndex: state.frameIndex, isMoving: movingNow };
    }

    function drawEngineSmokeTrail(key, thrusterX, thrusterY, angleRad, isMoving, screenOffsetY = 0) {
        const def = ENGINE_SMOKE_DEFS[DEFAULT_ENGINE_SMOKE_KEY];
        if (!def) return;

        const now = performance.now();
        const state = engineSmokeState[key] || { particles: [], lastSpawn: 0 };

        if (isMoving && now - state.lastSpawn >= (def.spawnInterval || 50)) {
            state.lastSpawn = now;
            state.particles.push({
                x: thrusterX,
                y: thrusterY,
                angle: (angleRad || 0) + Math.PI,
                createdAt: now
            });
        }

        const particles = state.particles;
        const frames = def.frames && def.frames.length > 0
            ? def.frames
            : Array.from({ length: def.frameCount || 1 }, (_, idx) => idx + 1);
        const frameCount = frames.length;
        const duration = def.duration || 750;
        const drift = def.drift || 0;

        const remainingParticles = [];
        for (const p of particles) {
            const age = now - p.createdAt;
            if (age > duration) {
                continue;
            }

            const lifeRatio = age / duration;
            const frameIdx = Math.min(frameCount - 1, Math.floor(lifeRatio * frameCount));
            const img = getEngineSmokeSpriteFrame(DEFAULT_ENGINE_SMOKE_KEY, frameIdx);
            if (img && img.complete && img.width > 0 && img.height > 0) {
                const travel = drift * lifeRatio;
                const drawX = mapToScreenX(p.x + Math.cos(p.angle) * travel);
                const drawY = mapToScreenY(p.y + Math.sin(p.angle) * travel) + screenOffsetY;
                const alpha = Math.max(0, 1 - lifeRatio);

                ctx.save();
                ctx.translate(drawX, drawY);
                if (def.rotate !== false) {
                    ctx.rotate(p.angle);
                }
                ctx.globalAlpha *= alpha;
                const scale = def.scale || 1;
                const drawW = img.width * scale;
                const drawH = img.height * scale;
                ctx.drawImage(img, -drawW / 2, -drawH / 2, drawW, drawH);
                ctx.restore();
            }

            remainingParticles.push(p);
        }

        if (remainingParticles.length === 0 && !isMoving) {
            delete engineSmokeState[key];
            return;
        }

        state.particles = remainingParticles;
        engineSmokeState[key] = state;
    }

    function drawEngineTrail(key, shipId, worldX, worldY, frameIndex, angleRad, offsetY = 0, forceMoving = false) {
        const engineDef = ENGINE_SPRITE_DEFS[DEFAULT_ENGINE_KEY];
        if (!engineDef) return;

        const engineOffset = getEngineOffsetForFrame(shipId, frameIndex || 0);
        if (!engineOffset) return;

        const { frameIndex: animFrameIndex, isMoving } = updateEngineAnimationState(key, worldX, worldY, forceMoving);

        const img = getEngineSpriteFrame(DEFAULT_ENGINE_KEY, animFrameIndex);
        if (!img || !img.complete || img.width === 0 || img.height === 0) return;

        const thrusterX = worldX + engineOffset.x;
        const thrusterY = worldY + engineOffset.y;
        drawEngineSmokeTrail(key, thrusterX, thrusterY, angleRad || 0, isMoving, offsetY);
        const screenX = mapToScreenX(thrusterX);
        const screenY = mapToScreenY(thrusterY) + offsetY;

        const scale = Math.max(0.55, Math.min(1.0, ((img && img.height) || engineDef.height || 80) / 80));
        const drawW = img.width * scale;
        const drawH = img.height * scale;

        ctx.save();
        ctx.translate(screenX, screenY);
        ctx.rotate((angleRad || 0) + Math.PI);
        ctx.drawImage(img, -drawW / 2, -drawH / 2, drawW, drawH);
        ctx.restore();
    }

    // =====================================================================
function drawMiniMap() {
    const layout = (typeof getMinimapLayout === "function") ? getMinimapLayout() : {
        outerX: canvas.width - MINIMAP_WIDTH - 10,
        outerY: canvas.height - MINIMAP_HEIGHT - 10 - 26 - 16,
        outerWidth: MINIMAP_WIDTH + 16,
        outerHeight: MINIMAP_HEIGHT + 26 + 16,
        contentX: canvas.width - MINIMAP_WIDTH - 10 + 8,
        contentY: canvas.height - MINIMAP_HEIGHT - 10 + 26,
        headerY: canvas.height - MINIMAP_HEIGHT - 10 - 26 - 16,
        headerHeight: 26
    };

    const hoverState = (typeof getMinimapHoverState === "function")
        ? getMinimapHoverState()
        : { icon: false, header: false };

    const x = layout.contentX;
    const infoHeight = layout.infoHeight || 0;
    const y = layout.contentY;
    const mapY = layout.mapY || (layout.contentY + infoHeight);
    const headerY = layout.headerY;
    const isMinimapOpen = window.showMinimap !== false;

    minimapHitboxes.icon = null;
    minimapHitboxes.zoomIn = null;
    minimapHitboxes.zoomOut = null;
    minimapHitboxes.close = null;
    minimapHitboxes.frame = isMinimapOpen ? { x: layout.outerX, y: layout.outerY, w: layout.outerWidth, h: layout.outerHeight } : null;
    minimapHitboxes.content = isMinimapOpen ? { x, y: mapY, w: MINIMAP_WIDTH, h: MINIMAP_HEIGHT } : null;

    if (!isMinimapOpen) {
        return;
    }

    // 1. CADRE ET EN-TÊTE
    ctx.save();
    ctx.fillStyle = "#0b0909";
    ctx.fillRect(layout.outerX, layout.outerY, layout.outerWidth, layout.outerHeight);

    const headerGrad = ctx.createLinearGradient(0, headerY, 0, headerY + layout.headerHeight);
    headerGrad.addColorStop(0, "#4d2b1d");
    headerGrad.addColorStop(1, "#2d130d");
    ctx.fillStyle = headerGrad;
    ctx.fillRect(layout.outerX, headerY, layout.outerWidth, layout.headerHeight);

    ctx.strokeStyle = "#8a5a3a";
    ctx.lineWidth = 2;
    ctx.strokeRect(
        layout.outerX + 0.5,
        layout.outerY + 0.5,
        layout.outerWidth - 1,
        layout.outerHeight - 1
    );
    ctx.restore();

    // Icône de la minimap (à la place de la croix)
    const iconSize = MINIMAP_BUTTON_SIZE;
    const iconX = layout.outerX + MINIMAP_FRAME_PADDING;
    const iconY = headerY + (layout.headerHeight - iconSize) / 2;
    const minimapIconPath = UI_SPRITES.mainMenuIconMap || UI_SPRITES.minimapWindowIcon;
    const minimapIcon = getUiImage(minimapIconPath);
    const iconHovered = hoverState.icon === true;

    ctx.save();
    ctx.beginPath();
    ctx.arc(iconX + iconSize / 2, iconY + iconSize / 2, iconSize / 2, 0, Math.PI * 2);
    ctx.fillStyle = iconHovered ? "#0d3927" : "#5d3a28";
    ctx.strokeStyle = iconHovered ? "#00ff7f" : "#d6b48d";
    ctx.lineWidth = 1.5;
    ctx.shadowColor = iconHovered ? "rgba(0, 255, 127, 0.7)" : "transparent";
    ctx.shadowBlur = iconHovered ? 8 : 0;
    ctx.fill();
    ctx.stroke();

    if (minimapIcon && minimapIcon.complete && minimapIcon.width > 0) {
        const scale = (iconSize - 4) / Math.max(minimapIcon.width, minimapIcon.height);
        const drawW = minimapIcon.width * scale;
        const drawH = minimapIcon.height * scale;
        ctx.globalAlpha = iconHovered ? 1 : 0.85;
        ctx.drawImage(
            minimapIcon,
            iconX + (iconSize - drawW) / 2,
            iconY + (iconSize - drawH) / 2,
            drawW,
            drawH
        );
    }
    ctx.restore();
    minimapHitboxes.icon = { x: iconX, y: iconY, w: iconSize, h: iconSize };
    minimapHitboxes.close = minimapHitboxes.icon;

    // Titre
    ctx.fillStyle = "#f5d1a4";
    ctx.font = "bold 12px Arial";
    ctx.textAlign = "left";
    ctx.fillText("Mini map", layout.outerX + MINIMAP_FRAME_PADDING + 26, headerY + layout.headerHeight - 8);

    // Boutons + / -
    const buttonY = headerY + (layout.headerHeight - MINIMAP_BUTTON_SIZE) / 2;
    const zoomOutX = layout.outerX + layout.outerWidth - MINIMAP_FRAME_PADDING - MINIMAP_BUTTON_SIZE;
    const zoomInX  = zoomOutX - MINIMAP_BUTTON_SIZE - 4;

    function drawHeaderButton(xBtn, label, hovered) {
        const grad = ctx.createLinearGradient(xBtn, buttonY, xBtn, buttonY + MINIMAP_BUTTON_SIZE);
        grad.addColorStop(0, hovered ? "#2e8b57" : "#5d3a28");
        grad.addColorStop(1, hovered ? "#1f5f3c" : "#3b2318");
        ctx.fillStyle = grad;
        ctx.fillRect(xBtn, buttonY, MINIMAP_BUTTON_SIZE, MINIMAP_BUTTON_SIZE);
        ctx.fillStyle = "#f8e6c8";
        ctx.font = "bold 12px Arial";
        ctx.textAlign = "center";
        ctx.textBaseline = "middle";
        ctx.fillText(label, xBtn + MINIMAP_BUTTON_SIZE / 2, buttonY + MINIMAP_BUTTON_SIZE / 2 + 0.5);
    }

    drawHeaderButton(zoomInX, "+", hoverState.zoomIn === true);
    drawHeaderButton(zoomOutX, "-", hoverState.zoomOut === true);
    minimapHitboxes.zoomIn = { x: zoomInX, y: buttonY, w: MINIMAP_BUTTON_SIZE, h: MINIMAP_BUTTON_SIZE };
    minimapHitboxes.zoomOut = { x: zoomOutX, y: buttonY, w: MINIMAP_BUTTON_SIZE, h: MINIMAP_BUTTON_SIZE };
    // Fond noir simple (pas d'image grise)
    ctx.fillStyle = "black";
    ctx.fillRect(x, mapY, MINIMAP_WIDTH, MINIMAP_HEIGHT);

    // 2. CALCULS D'ÉCHELLE
    const scale   = (typeof getMiniMapScale === "function")
        ? getMiniMapScale()
        : (MINIMAP_WIDTH / MAP_WIDTH);

    const realW   = MAP_WIDTH  * scale;
    const realH   = MAP_HEIGHT * scale;
    const offsetX = (MINIMAP_WIDTH  - realW)  / 2;
    const offsetY = (MINIMAP_HEIGHT - realH) / 2;

    const toMiniX = (wx) => x + offsetX + (wx * scale);
    const toMiniY = (wy) => mapY + offsetY + (wy * scale);

    // 3. VISEUR (croix sur la position du joueur)
    const px = toMiniX(shipX);
    const py = toMiniY(shipY);

    if (px >= x && px <= x + MINIMAP_WIDTH && py >= mapY && py <= mapY + MINIMAP_HEIGHT) {
        ctx.save();
        ctx.strokeStyle = "rgba(255, 255, 255, 0.2)";
        ctx.lineWidth = 1;
        ctx.beginPath(); ctx.moveTo(x, py); ctx.lineTo(x + MINIMAP_WIDTH, py); ctx.stroke();
        ctx.beginPath(); ctx.moveTo(px, mapY); ctx.lineTo(px, mapY + MINIMAP_HEIGHT); ctx.stroke();
        ctx.restore();
    }

    // 4. PORTAILS
    const portalIcon = getUiImage(UI_SPRITES.minimapPortalIcon);
    for (const pid in portals) {
        const p = portals[pid];
        if (p.visibleOnMiniMap === false) continue;

        const mx = toMiniX(p.x);
        const my = toMiniY(p.y);

        if (mx >= x && mx <= x + MINIMAP_WIDTH && my >= mapY && my <= mapY + MINIMAP_HEIGHT) {
            if (portalIcon && portalIcon.complete && portalIcon.width > 0) {
                const pw = portalIcon.width;
                const ph = portalIcon.height;
                ctx.drawImage(portalIcon, mx - pw / 2, my - ph / 2, pw, ph);
            } else {
                ctx.strokeStyle = "#00ffff";
                ctx.lineWidth = 1.5;
                ctx.beginPath();
                ctx.arc(mx, my, 3, 0, Math.PI * 2);
                ctx.stroke();
            }
        }
    }

    // 5. STATIONS
    for (const s of stations) {
        const mx = toMiniX(s.x);
        const my = toMiniY(s.y);
        if (mx >= x && mx <= x + MINIMAP_WIDTH && my >= mapY && my <= mapY + MINIMAP_HEIGHT) {
            const stationImg = stationImages[s.type];
            if (stationImg && stationImg.complete && stationImg.width > 0) {
                const targetHeight = 26;
                const scale = targetHeight / stationImg.height;
                const drawW = stationImg.width * scale;
                const drawH = stationImg.height * scale;
                ctx.drawImage(
                    stationImg,
                    mx - drawW / 2,
                    my - drawH / 2,
                    drawW,
                    drawH
                );
            } else {
                ctx.strokeStyle = "#00aaff";
                ctx.lineWidth = 1.5;
                ctx.beginPath();
                ctx.arc(mx, my, 6, 0, Math.PI * 2);
                ctx.stroke();
            }
        }
    }

    // 6. ENTITÉS (avec correction pour la cible lockée)
    for (const id in entities) {
        const e = entities[id];
        if (!isEntityVisibleOnMap(e)) continue;

        const isGroupMember  = (groupMembers[e.id] !== undefined);
        const isLockedTarget = (selectedTargetId !== null && e.id == selectedTargetId);

        // Filtre radar : on cache si loin, SAUF si groupe ou cible
        if (!isGroupMember && !isLockedTarget) {
            const dx = e.x - shipX;
            const dy = e.y - shipY;
            if (dx * dx + dy * dy > MINIMAP_VIEW_RADIUS_SQ) continue;
        }

        const mx = toMiniX(e.x);
        const my = toMiniY(e.y);

        if (mx >= x && mx <= x + MINIMAP_WIDTH && my >= mapY && my <= mapY + MINIMAP_HEIGHT) {
            const nameLower = (e.name || "").toLowerCase();
            const isSpaceball = nameLower.includes("spaceball");

            if (isSpaceball) {
                const sbIcon = getUiImage(UI_SPRITES.minimapSpaceballIcon);
                if (sbIcon && sbIcon.complete && sbIcon.width > 0) {
                    ctx.drawImage(
                        sbIcon,
                        mx - sbIcon.width / 2,
                        my - sbIcon.height / 2,
                        sbIcon.width,
                        sbIcon.height
                    );
                    continue;
                }
            }

            ctx.fillStyle = getEntityColor(e);
            let size = isGroupMember ? 4 : 2;

            if (isLockedTarget) {
                size = 4;
                ctx.fillStyle = "#ff0000";
                ctx.save();
                ctx.strokeStyle = "#ff0000";
                ctx.lineWidth = 1;
                ctx.strokeRect(mx - 4, my - 4, 8, 8);
                ctx.restore();
            }

            ctx.fillRect(mx - size / 2, my - size / 2, size, size);
        }
    }

    // 7. Point de destination (clic minimap / mouvement manuel)
    if (moveTargetX !== null && moveTargetY !== null) {
        const tx = toMiniX(moveTargetX);
        const ty = toMiniY(moveTargetY);
        if (tx >= x && tx <= x + MINIMAP_WIDTH && ty >= mapY && ty <= mapY + MINIMAP_HEIGHT) {
            const finishIcon = getUiImage(UI_SPRITES.minimapFinishIcon);
            if (finishIcon && finishIcon.complete && finishIcon.width > 0) {
                ctx.drawImage(
                    finishIcon,
                    tx - finishIcon.width / 2,
                    ty - finishIcon.height / 2,
                    finishIcon.width,
                    finishIcon.height
                );
            } else {
                ctx.save();
                ctx.strokeStyle = "#00ff00";
                ctx.beginPath();
                ctx.arc(tx, ty, 4, 0, Math.PI * 2);
                ctx.stroke();
                ctx.restore();
            }
        }
    }

    // 8. Cadre de vue
    const viewW     = canvas.width  * scale;
    const viewH     = canvas.height * scale;
    const miniViewX = px - viewW / 2;
    const miniViewY = py - viewH / 2;

    ctx.strokeStyle = "rgba(255, 255, 255, 0.4)";
    ctx.lineWidth   = 1;
    ctx.strokeRect(miniViewX, miniViewY, viewW, viewH);

    // 9. Pings de groupe
    const nowMs = performance.now();
    for (let idx = groupPings.length - 1; idx >= 0; idx--) {
        const ping = groupPings[idx];
        if (nowMs - ping.createdAt > 5000) {
            groupPings.splice(idx, 1);
            continue;
        }
        const mx = toMiniX(ping.x);
        const my = toMiniY(ping.y);
        if (mx >= x && mx <= x + MINIMAP_WIDTH && my >= mapY && my <= mapY + MINIMAP_HEIGHT) {
            const def   = MINIMAP_SPRITE_DEFS.groupPing;
            const frame = Math.floor(((nowMs - ping.createdAt) / 1000) * def.fps);
            const pingImg = getMinimapSpriteFrame(
                "groupPing",
                def.loop ? frame % def.frameCount : Math.min(frame, def.frameCount - 1)
            );
            if (pingImg && pingImg.complete && pingImg.width > 0) {
                ctx.save();
                const size = Math.max(pingImg.width, pingImg.height);
                ctx.globalAlpha = 1 - Math.min((nowMs - ping.createdAt) / 5000, 1);
                ctx.drawImage(pingImg, mx - size / 2, my - size / 2, size, size);
                ctx.restore();
            } else {
                ctx.save();
                ctx.strokeStyle = "#ffff00";
                ctx.lineWidth   = 2;
                const anim = (nowMs - ping.createdAt) / 1000;
                const r    = 2 + (anim % 1) * 10;
                ctx.globalAlpha = 1 - (anim % 1);
                ctx.beginPath(); ctx.arc(mx, my, r, 0, Math.PI * 2); ctx.stroke();
                ctx.restore();
            }
        }
    }

    // 10. Joueur (point central + alerte ennemis proches)
    if (px >= x && px <= x + MINIMAP_WIDTH && py >= mapY && py <= mapY + MINIMAP_HEIGHT) {
        ctx.fillStyle = "white";
        ctx.fillRect(px - 2, py - 2, 4, 4);

        // Alerte ennemi proche (icône)
        const alertImg = getUiImage(UI_SPRITES.minimapAlertIcon);
        if (alertImg && alertImg.complete && alertImg.width > 0) {
            const threat = Object.values(entities).some(ent =>
                ent &&
                ent.kind === "player" &&
                ent.factionId &&
                heroFactionId &&
                ent.factionId !== heroFactionId &&
                Math.hypot(ent.x - shipX, ent.y - shipY) < 2000
            );
            if (threat) {
                ctx.drawImage(
                    alertImg,
                    px - alertImg.width / 2,
                    py - alertImg.height / 2,
                    alertImg.width,
                    alertImg.height
                );
            }
        }
    }

    // 11. Cadre et textes map + coordonnées (bande séparée au-dessus de la carte)
    if (infoHeight > 0) {
        const infoY = y;
        const displayX  = Math.round(shipX / 100);
        const displayY  = Math.round(shipY / 100);
        const coordText = `${displayX}/${displayY}`;
        const formatMapId = (mapId) => {
		switch (mapId) {
        case 1:  return "1-1";
        case 2:  return "1-2";
        case 3:  return "1-3";
        case 4:  return "1-4";
        case 5:  return "2-1";
        case 6:  return "2-2";
        case 7:  return "2-3";
        case 8:  return "2-4";
        case 9:  return "3-1";
        case 10: return "3-2";
        case 11: return "3-3";
        case 12: return "3-4";
        case 13: return "4-1";
        case 14: return "4-2";
        case 15: return "4-3";
        case 16: return "4-4";
        case 17: return "1-5";
        case 18: return "1-6";
        case 19: return "1-7";
        case 20: return "1-8";
        case 21: return "2-5";
        case 22: return "2-6";
        case 23: return "2-7";
        case 24: return "2-8";
        case 25: return "3-5";
        case 26: return "3-6";
        case 27: return "3-7";
        case 28: return "3-8";
        case 51: return "GGA";
        case 52: return "GGB";
        case 53: return "GGG";
        case 55: return "GGD";
        case 80: return "Surv";
        case 81: return "Inva";
        default:
            return "1-1"; // fallback
    }
};

        const mapText   = formatMapId(currentMapId);

        ctx.save();
        ctx.fillStyle = "rgba(0, 0, 0, 0.45)";
        ctx.fillRect(x, infoY, MINIMAP_WIDTH, infoHeight - 4);

        ctx.strokeStyle = "rgba(255, 255, 255, 0.15)";
        ctx.lineWidth = 1;
        ctx.beginPath();
        ctx.moveTo(x, mapY - 1);
        ctx.lineTo(x + MINIMAP_WIDTH, mapY - 1);
        ctx.stroke();

        ctx.font = "bold 12px Arial";
        ctx.textAlign = "left";
        const labelY = infoY + infoHeight - 10;

        ctx.fillStyle = "#f5d1a4";
        ctx.fillText(mapText, x + 2, labelY);

        const mapLabelWidth = ctx.measureText(mapText).width;
        ctx.fillStyle = "#ffffff";
        ctx.fillText(coordText, x + 2 + mapLabelWidth + 8, labelY);
        ctx.restore();
    }
}


    

    function drawShieldAura(sx, sy, currentShield, maxShield, ish, invincible, ishSince, ishUntil, invSince, invUntil) {
        if (!invincible && !ish && (!currentShield || currentShield <= 0)) return;

        const effectiveMax = maxShield || currentShield || 1;
        const fullShield = maxShield ? (currentShield >= maxShield || currentShield / effectiveMax >= 0.999) : false;

        let spriteKey = "standard";
        if (ish) spriteKey = "insta";
        else if (invincible) spriteKey = "invincibility";
        else if (fullShield) return;
        else if (currentShield / effectiveMax < 0.25) spriteKey = "low";

        const def = SHIELD_SPRITE_DEFS[spriteKey];
        if (!def) return;

        const now = performance.now();
        let frame;

        if (def.loop) {
            frame = Math.floor(shieldAnimTime * (def.fps || SHIELD_ANIM_FPS)) % def.frameCount;
        } else {
            const start = ish ? ishSince : invSince;
            const end = ish ? ishUntil : invUntil;
            if (!start || !end) return;
            const duration = Math.max(1, end - start);
            const progress = Math.min(1, Math.max(0, (now - start) / duration));
            frame = Math.min(def.frameCount - 1, Math.floor(progress * def.frameCount));
        }

        const img = getShieldSpriteFrame(spriteKey, frame);
        if (!img || !img.complete || img.width === 0 || img.height === 0) return;

        const pulse = 1 + Math.sin(shieldAnimTime * 4) * 0.05;
        const alpha = 0.35 + 0.25 * Math.min(1, currentShield / effectiveMax);

        ctx.save();
        ctx.globalAlpha = alpha;
        const w = img.width * pulse;
        const h = img.height * pulse;
        ctx.drawImage(img, sx - w / 2, sy - h / 2, w, h);
        ctx.restore();
    }

    function drawHpShieldBars(screenX, screenY, spriteHeight, hp, maxHp, shield, maxShield) {
        const barWidth = 50;
        const barHeight = 4;
        const gap = 2;
        const visualHeight = Math.max(20, spriteHeight || 0);
        const topY = screenY - visualHeight / 2 - (barHeight * 2 + gap + 2);

        const safeHp = typeof hp === "number" ? hp : 0;
        const safeMaxHp = (typeof maxHp === "number" && maxHp > 0) ? maxHp : (safeHp > 0 ? safeHp : 1);
        const safeShield = typeof shield === "number" ? shield : 0;
        const safeMaxShield = (typeof maxShield === "number" && maxShield > 0) ? maxShield : (safeShield > 0 ? safeShield : 1);

        const hpRatio = Math.max(0, Math.min(1, safeHp / safeMaxHp));
        const shieldRatio = Math.max(0, Math.min(1, safeShield / safeMaxShield));

        ctx.save();
        ctx.lineWidth = 1;

        ctx.fillStyle = "rgba(40,40,40,0.8)";
        ctx.fillRect(screenX - barWidth / 2, topY, barWidth, barHeight);
        ctx.fillStyle = "#4cb648";
        ctx.fillRect(screenX - barWidth / 2, topY, barWidth * hpRatio, barHeight);
        ctx.strokeStyle = "#000000";
        ctx.strokeRect(screenX - barWidth / 2 + 0.5, topY + 0.5, barWidth - 1, barHeight - 1);

        const shieldY = topY + barHeight + gap;
        ctx.fillStyle = "rgba(40,40,40,0.8)";
        ctx.fillRect(screenX - barWidth / 2, shieldY, barWidth, barHeight);
        ctx.fillStyle = "#007adf";
        ctx.fillRect(screenX - barWidth / 2, shieldY, barWidth * shieldRatio, barHeight);
        ctx.strokeStyle = "#000000";
        ctx.strokeRect(screenX - barWidth / 2 + 0.5, shieldY + 0.5, barWidth - 1, barHeight - 1);

        ctx.restore();
    }

    // Utilise toutes les frames des sprites de dégâts bouclier
    function drawShieldBursts() {
        const now = performance.now();
        for (const sb of shieldBursts) {
            const spriteKey = sb.sprite || "hit";
            const def = SHIELD_SPRITE_DEFS[spriteKey];
            if (!def) continue;

            const elapsed = now - sb.createdAt;
            const frameDuration = 1000 / (def.fps || SHIELD_ANIM_FPS);
            const frame = Math.min(def.frameCount - 1, Math.floor(elapsed / frameDuration));
            const img = getShieldSpriteFrame(spriteKey, frame);
            if (!img || !img.complete || img.width === 0 || img.height === 0) continue;

            const lifeRatio = Math.min(1, elapsed / Math.max(1, frameDuration * def.frameCount));
            const angle = sb.angle || 0;
            const radius = sb.radius || 0;
            const baseX = sb.x + Math.cos(angle) * radius;
            const baseY = sb.y + Math.sin(angle) * radius;

            const burstScreenX = mapToScreenX(baseX);
            const burstScreenY = mapToScreenY(baseY);
            const scale = 1 + lifeRatio * 0.2;
            const w = img.width * scale;
            const h = img.height * scale;

            ctx.save();
            ctx.translate(burstScreenX, burstScreenY);
            if (sb.angle !== undefined && sb.angle !== null) ctx.rotate(angle);
            ctx.globalAlpha = 1 - lifeRatio;
            ctx.drawImage(img, -w / 2, -h / 2, w, h);
            ctx.restore();
        }
    }

    const COLLECTOR_BEAM_SCREEN_OFFSET_Y = 65;

    function drawHeroCollectorBeamAt(shipScreenX, shipScreenY) {
        if (!heroCollectorBeamState) return;

        const now = performance.now();
        const state = heroCollectorBeamState;
        const lifespan = state.durationMs || COLLECTOR_BEAM_DEFAULT_DURATION_MS;

        if (now - state.startedAt >= lifespan) {
            stopHeroCollectorBeam();
            return;
        }

        if (now - state.lastUpdate >= COLLECTOR_BEAM_FRAME_DURATION) {
            const steps = Math.floor((now - state.lastUpdate) / COLLECTOR_BEAM_FRAME_DURATION);
            state.frameIndex = (state.frameIndex + steps) % COLLECTOR_BEAM_FRAME_COUNT;
            state.lastUpdate = state.lastUpdate + steps * COLLECTOR_BEAM_FRAME_DURATION;
        }

        const frameImg = getCollectorBeamFrame(state.frameIndex);
        if (frameImg && frameImg.complete && frameImg.width > 0 && frameImg.height > 0) {
            const drawX = shipScreenX - frameImg.width / 2;
            const drawY = shipScreenY + COLLECTOR_BEAM_SCREEN_OFFSET_Y - frameImg.height / 2;
            ctx.drawImage(frameImg, drawX, drawY);
        }
    }

    function drawShipExpansionOverlay(shipId, frameIndex, screenX, screenY) {
        const expansionDef = SHIP_EXPANSION_DEFS && SHIP_EXPANSION_DEFS[shipId];
        if (!expansionDef) return;

        const img = getShipExpansionFrame(shipId, frameIndex);
        if (!img || !img.complete || img.width === 0 || img.height === 0) return;

        const offset = expansionDef.offset || { x: 0, y: 0 };
        const drawX = screenX - img.width / 2 + (offset.x || 0);
        const drawY = screenY - img.height / 2 + (offset.y || 0);
        ctx.drawImage(img, drawX, drawY);
    }

    function drawShip() {
        const shipScreenX = mapToScreenX(shipX);
        const syBase = mapToScreenY(shipY);
        const bobOffset = getHeroIdleOffset();
        const sy = syBase + bobOffset;

        ctx.save();

        // Transparence si camouflage
        ctx.globalAlpha = heroCloaked ? 0.3 : 1.0;

        drawHeroCollectorBeamAt(shipScreenX, sy);

        const shipId = heroShipId;
        const def = SHIP_SPRITE_DEFS[shipId];
        let shipDrawnHeight = 20;

        if (def) {
            const frameIndex = getDirectionFrameIndex(heroAngle, def.frameCount);

            let glowImg = null;
            let img = null;

            if (typeof getShipGlowFrame === "function") {
                glowImg = getShipGlowFrame(shipId, frameIndex);
                if (glowImg && glowImg.complete && glowImg.width > 0 && glowImg.height > 0) {
                    const gw = glowImg.width;
                    const gh = glowImg.height;
                    ctx.drawImage(glowImg, shipScreenX - gw / 2, sy - gh / 2);
                }
            }

            img = getShipSpriteFrame(shipId, frameIndex);

            drawEngineTrail("hero", shipId, shipX, shipY, frameIndex, heroAngle || 0, bobOffset);

            if (img && img.complete && img.width > 0 && img.height > 0) {
                const w = img.width;
                const h = img.height;
                shipDrawnHeight = h;
                ctx.drawImage(img, shipScreenX - w / 2, sy - h / 2);
            }

            drawShipExpansionOverlay(shipId, frameIndex, shipScreenX, sy);


        } else {
            shipDrawnHeight = 0;
        }

        if (!shipDrawnHeight) {
            const size = 20;
            shipDrawnHeight = size;
            ctx.fillStyle = "#cccccc";
            ctx.beginPath();
            ctx.arc(shipScreenX, sy, size / 2, 0, Math.PI * 2, false);
            ctx.fill();
        }

        if (heroTargetFaded) {
            ctx.save();
            ctx.fillStyle = `rgba(80,80,80,${TARGET_FADE_OVERLAY_ALPHA})`;
            ctx.beginPath();
            ctx.arc(shipScreenX, sy, TARGET_FADE_OVERLAY_RADIUS, 0, Math.PI * 2, false);
            ctx.fill();
            ctx.restore();
        }

        drawShieldAura(shipScreenX, sy, heroShield, heroMaxShield, heroIshActive, heroInvincible, heroIshSince, heroIshUntil, heroInvSince, heroInvUntil);

        if (setting_show_drones && window.heroDrones && window.heroDrones.groups && window.heroDrones.groups.length > 0) {
            drawDrones(shipX, shipY, window.heroDrones, heroAngle);
        }

        if (heroName) {
            const label = heroClanTag ? `[${heroClanTag}] ${heroName}` : heroName;
            const baseY = computeNameplateY(syBase, shipDrawnHeight);

            ctx.save();
            ctx.font = "12px Consolas, monospace";
            ctx.textAlign = "center";
            ctx.textBaseline = "top";
            ctx.lineJoin = "round";
            ctx.lineWidth = 3;
            ctx.strokeStyle = "rgba(0,0,0,0.7)";
            ctx.fillStyle = "#ffffff";
            ctx.strokeText(label, shipScreenX, baseY);
            ctx.fillText(label, shipScreenX, baseY);
            ctx.restore();
        }

        drawHpShieldBars(shipScreenX, sy, shipDrawnHeight, heroHp, heroMaxHp, heroShield, heroMaxShield);

        ctx.restore();
    }
    const DRONE_DIRECTION_FRAME_COUNT = 32;
    var DRONE_GROUP_RADIUS = (typeof DRONE_GROUP_RADIUS !== "undefined") ? DRONE_GROUP_RADIUS : 90; // patterns.drones.@groupRadius dans game.xml
    var DRONE_GROUP_DIMENSION = DRONE_GROUP_RADIUS * 2;
    const DRONE_DEFAULT_DIMENSION = 15 * 2;    // patterns.drones.drone.@droneRadius * 2

    const IRIS_DRONE_FRAMES = [
        131, 133, 135, 137, 139, 141, 143, 145,
        147, 149, 151, 153, 155, 157, 159, 161,
        163, 165, 167, 169, 171, 173, 175, 177,
        179, 181, 183, 185, 187, 189, 191, 193
    ];

    const FLAX_DRONE_FRAMES = [
        196, 198, 200, 202, 204, 206, 208, 210,
        212, 214, 216, 218, 220, 222, 224, 226,
        228, 230, 232, 234, 236, 238, 240, 242,
        244, 246, 248, 250, 252, 254, 256, 258
    ];

    function getDroneSpriteFrame(kind, directionIndex) {
        const frames = (kind === "flax") ? FLAX_DRONE_FRAMES : IRIS_DRONE_FRAMES;
        const idx = ((directionIndex % DRONE_DIRECTION_FRAME_COUNT) + DRONE_DIRECTION_FRAME_COUNT) % DRONE_DIRECTION_FRAME_COUNT;
        const fileNumber = frames[idx];
        return getUiImage(`graphics/assets/drones/images/${fileNumber}.png`);
    }

    function pickDroneKind(drone) {
        if (drone && drone.kind) return drone.kind;
        if (typeof resolveDroneKind === "function" && drone) {
            return resolveDroneKind(drone.type);
        }
        return "iris";
    }

    const RAD_TO_DEG = 180 / Math.PI;
    const DEG_TO_RAD = Math.PI / 180;

    function positionOffsetDegrees(pos) {
        if (pos === DRONE_POSITION_TOP) return 0;
        if (pos === DRONE_POSITION_RIGHT) return 90;
        if (pos === DRONE_POSITION_DOWN) return 180;
        if (pos === DRONE_POSITION_LEFT) return 270;
        return 0;
    }

    // Dessin générique des drones autour d'un vaisseau (structure inspirée du client Flash)
    function drawDrones(worldX, worldY, droneConnector, shipAngle = 0) {
        if (!droneConnector || !droneConnector.groups || !droneConnector.groups.length) return;

        const normalizedShipAngle = isFinite(shipAngle) ? shipAngle : 0;
        const baseRotationDeg = normalizedShipAngle * RAD_TO_DEG - 180;
        const directionIndex = getDirectionFrameIndex(normalizedShipAngle, DRONE_DIRECTION_FRAME_COUNT);
        const groupDimension = droneConnector.groupDimension || DRONE_GROUP_DIMENSION;

        ctx.save();
        ctx.globalCompositeOperation = "source-over";

        for (const group of droneConnector.groups) {
            const groupAngleDeg = baseRotationDeg + positionOffsetDegrees(group.position);
            const groupAngleRad = groupAngleDeg * DEG_TO_RAD;
            const groupWorldX = worldX + Math.cos(groupAngleRad) * groupDimension;
            const groupWorldY = worldY + Math.sin(groupAngleRad) * groupDimension;
            const groupScreenX = mapToScreenX(groupWorldX);
            const groupScreenY = mapToScreenY(groupWorldY);

            for (const drone of group.drones || []) {
                const kind = pickDroneKind(drone);
                const img = getDroneSpriteFrame(kind, directionIndex);
                if (!img || !img.complete || img.width === 0 || img.height === 0) continue;

                const droneAngleDeg = baseRotationDeg + positionOffsetDegrees(drone.position);
                const droneAngleRad = droneAngleDeg * DEG_TO_RAD;

                const droneRadius = (drone.position === DRONE_POSITION_CENTER ? 1 : (drone.dimension || DRONE_DEFAULT_DIMENSION));
                const droneWorldX = groupWorldX + Math.cos(droneAngleRad) * droneRadius;
                const droneWorldY = groupWorldY + Math.sin(droneAngleRad) * droneRadius;
                const droneScreenX = mapToScreenX(droneWorldX);
                const droneScreenY = mapToScreenY(droneWorldY);

                ctx.drawImage(img, droneScreenX - img.width / 2, droneScreenY - img.height / 2);
            }
        }

        ctx.restore();
    }


    function drawEntities() {
    const size = 14;

    // 1) Vaisseaux / NPC (hors boxes)
    for (const id in entities) {
        const e = entities[id];
        if (e.kind === "box") continue;

        const dx = e.x - shipX;
        const dy = e.y - shipY;
        const distSq = dx * dx + dy * dy;
        if (distSq > VIEW_RADIUS_SQ) continue;
        if (!isEntityVisibleOnMap(e)) continue;

        const entityScreenX = mapToScreenX(e.x);
        const entityScreenY = mapToScreenY(e.y);

        const def = SHIP_SPRITE_DEFS[e.shipId];
        let drewSprite = false;
        let spriteHeight = size;
        let img = null;

        // Si on a des sprites pour ce shipId, on les utilise
        if (def) {
            // Frame choisie en fonction de l'angle (si connu)
            let frameIndex = 0;
            if (typeof e.angle === "number" && def.frameCount > 1) {
                frameIndex = getDirectionFrameIndex(e.angle, def.frameCount);
            }

            // --- AURA (GLOW) SI DISPONIBLE POUR CE SHIPID ---
            // (Par exemple Leonovo = shipId 3, défini dans SHIP_GLOW_DEFS)
            if (typeof getShipGlowFrame === "function") {
                const glowImg = getShipGlowFrame(e.shipId, frameIndex);
                if (glowImg && glowImg.complete && glowImg.width > 0 && glowImg.height > 0) {
                    const gw = glowImg.width;
                    const gh = glowImg.height;
                      ctx.drawImage(glowImg, entityScreenX - gw / 2, entityScreenY - gh / 2);
                }
            }

            // --- SPRITE DU VAISSEAU ---
            img = getShipSpriteFrame(e.shipId, frameIndex);
            const forceEngineMoving = typeof e.speed === "number" && e.speed > 0;
            drawEngineTrail(`entity_${e.id}`, e.shipId, e.x, e.y, frameIndex, e.angle || 0, 0, forceEngineMoving);
            if (img && img.complete && img.width > 0 && img.height > 0) {
                const w = img.width;
                const h = img.height;
                spriteHeight = h;
                  ctx.drawImage(img, entityScreenX - w / 2, entityScreenY - h / 2);
                drewSprite = true;
            }

            drawShipExpansionOverlay(e.shipId, frameIndex, entityScreenX, entityScreenY);

        }

        // Fallback : si pas de sprite (ou pas encore chargé), on garde le carré
        if (!drewSprite) {
            spriteHeight = size;
            ctx.fillStyle = getEntityColor(e);
            ctx.beginPath();
        ctx.arc(entityScreenX, entityScreenY, size / 2, 0, Math.PI * 2, false);
            ctx.fill();
        }

        if (e.targetFaded) {
            ctx.save();
            ctx.fillStyle = `rgba(80,80,80,${TARGET_FADE_OVERLAY_ALPHA})`;
            ctx.beginPath();
            ctx.arc(entityScreenX, entityScreenY, TARGET_FADE_OVERLAY_RADIUS, 0, Math.PI * 2, false);
            ctx.fill();
            ctx.restore();
        }

          if (e.kind === "player") {
              drawShieldAura(entityScreenX, entityScreenY, e.shield, e.maxShield, e.ishActive, e.invincible, e.ishSince, e.ishUntil, e.invSince, e.invUntil);
        }

        // Drones de l'entité
        if (setting_show_drones && e.drones && e.drones.groups && e.drones.groups.length > 0) {
            drawDrones(e.x, e.y, e.drones, e.angle || 0);
        }

        if (selectedTargetId !== null && e.id === selectedTargetId) {
            drawHpShieldBars(entityScreenX, entityScreenY, spriteHeight, e.hp, e.maxHp, e.shield, e.maxShield);
        }

        // Anneau de sélection / cible laser
        if (e.id === selectedTargetId || e.id === currentLaserTargetId) {
            ctx.save();
            ctx.strokeStyle = (e.id === currentLaserTargetId ? "#ff0000" : "#00ffff");
            ctx.lineWidth = 2;
            ctx.beginPath();
              ctx.arc(entityScreenX, entityScreenY, size, 0, Math.PI * 2, false);
            ctx.stroke();
            ctx.restore();
        }

        // Nom + clan sous le vaisseau (joueurs) ou nom seul pour les NPCs
        if (e.name && e.kind !== "box") {
            const label = (e.kind === "player" && e.clanTag) ? `[${e.clanTag}] ${e.name}` : e.name;
            const baseY = computeNameplateY(entityScreenY, spriteHeight);

            ctx.save();
            ctx.font = "12px Consolas, monospace";
            ctx.textAlign = "center";
            ctx.textBaseline = "top";
            ctx.lineJoin = "round";
            ctx.lineWidth = 3;
            ctx.strokeStyle = "rgba(0,0,0,0.7)";
            ctx.fillStyle = getNameplateColor(e);

              ctx.strokeText(label, entityScreenX, baseY);
              ctx.fillText(label, entityScreenX, baseY);

            ctx.restore();
        }
    }

    // 2) Boxes (par dessus, inchangé)
    for (const id in entities) {
        const e = entities[id];
        if (e.kind !== "box") continue;

        const dx = e.x - shipX;
        const dy = e.y - shipY;
        const distSq = dx * dx + dy * dy;
        if (distSq > VIEW_RADIUS_SQ) continue;
        if (!isEntityVisibleOnMap(e)) continue;

        const boxScreenX = mapToScreenX(e.x);
        const boxScreenY = mapToScreenY(e.y);

        const now = performance.now();
        const category = e.category || "other";
        const isCargo = category === "cargoFree" || category === "cargoNotFree";
        const isBonus = category === "bonusBox";
        const isBootyBox = category === "bootyBox";
        const isOre = category === "ore";
        const shouldAnimate = isCargo || isBonus || isBootyBox;
        const isBootyKey = category === "bootyKey";

        if (isOre) {
            const spriteKey = getOreSpriteKeyFromType(e.type, e.oreSprite);
            const cfg = getOreSpriteConfig(spriteKey);
            if (cfg) {
                const frameCount = cfg.frameCount;
                const animState = oreAnimationStates[e.id] || { frameIndex: Math.floor(Math.random() * frameCount), lastUpdate: now };

                if (now - animState.lastUpdate >= ORE_ANIMATION_FRAME_DURATION) {
                    const steps = Math.floor((now - animState.lastUpdate) / ORE_ANIMATION_FRAME_DURATION);
                    animState.frameIndex = (animState.frameIndex + steps) % frameCount;
                    animState.lastUpdate = animState.lastUpdate + steps * ORE_ANIMATION_FRAME_DURATION;
                }

                const frameImg = getOreSpriteFrame(spriteKey, animState.frameIndex);
                oreAnimationStates[e.id] = animState;

                if (frameImg && frameImg.complete && frameImg.width > 0 && frameImg.height > 0) {
                    ctx.drawImage(frameImg, boxScreenX - frameImg.width / 2, boxScreenY - frameImg.height / 2);
                } else {
                    ctx.fillStyle = getEntityColor(e);
                    ctx.fillRect(boxScreenX - size / 2, boxScreenY - size / 2, size, size);
                }
            } else {
                ctx.fillStyle = getEntityColor(e);
                ctx.beginPath();
                ctx.arc(boxScreenX, boxScreenY, size / 2, 0, Math.PI * 2, false);
                ctx.fill();
            }
        } else if (shouldAnimate) {
            const spriteCategory = isBonus ? "bonusBox" : isBootyBox ? "bootyBox" : category;
            const cfg = getBoxSpriteConfig(spriteCategory);
            let frameIndex;

            if (isBonus) {
                ensureBonusBoxAnimationTimer();
                frameIndex = bonusBoxFrameIndex;
            } else {
                const animState = boxAnimationStates[e.id] || { frameIndex: 0, lastUpdate: now };

                if (now - animState.lastUpdate >= BOX_ANIMATION_FRAME_DURATION) {
                    const steps = Math.floor((now - animState.lastUpdate) / BOX_ANIMATION_FRAME_DURATION);
                    animState.frameIndex = (animState.frameIndex + steps) % cfg.frameCount;
                    animState.lastUpdate = animState.lastUpdate + steps * BOX_ANIMATION_FRAME_DURATION;
                }

                frameIndex = animState.frameIndex;
                boxAnimationStates[e.id] = animState;
            }

            const frameImg = getBoxSpriteFrame(spriteCategory, frameIndex);

            if (frameImg && frameImg.complete && frameImg.width > 0 && frameImg.height > 0) {
                ctx.drawImage(frameImg, boxScreenX - frameImg.width / 2, boxScreenY - frameImg.height / 2);
            } else {
                ctx.fillStyle = getEntityColor(e);
                ctx.fillRect(boxScreenX - size / 2, boxScreenY - size / 2, size, size);
            }
        } else {
            if (boxAnimationStates[e.id]) clearBoxAnimationState(e.id);

            if (isBootyKey) {
                drawBootyKey(boxScreenX, boxScreenY, now);
            } else {
                ctx.fillStyle = getEntityColor(e);
                ctx.beginPath();
                ctx.arc(boxScreenX, boxScreenY, size / 2, 0, Math.PI * 2, false);
                ctx.fill();
            }
        }
    }
}



    function drawPortals() {
        const now = performance.now();

        for (const pid in portals) {
            const p = portals[pid];

            // 1. Optimisation : Si trop loin, on ne dessine pas
            const dx = p.x - shipX;
            const dy = p.y - shipY;
            const distSq = dx * dx + dy * dy;

            // Si hors de vue (plus loin que la vision), on passe
            if (distSq > VIEW_RADIUS_SQ) continue;

            // 2. Conversion coordonnées Map -> Écran
            const portalScreenX = mapToScreenX(p.x);
            const portalScreenY = mapToScreenY(p.y);

            ctx.save();
            ctx.lineWidth = 2;

            // 3. LOGIQUE VISUELLE BASÉE SUR LE TYPE FLASH
            // Dans le protocole, typeId 1 = Saut (Jumpgate).
            // Les autres types (0, ou spécifiques comme 80, etc.) sont souvent des bases ou des éléments de décor.

            if (p.typeId === 1) {
                let drawn = false;
                const portalDef = PORTAL_SPRITE_DEFS.standard;

                if (portalDef) {
                    if (!p.idleStart) p.idleStart = now;

                    if (p.playJump && p.jumpStart) {
                        const jumpElapsed = now - p.jumpStart;
                        if (jumpElapsed <= PORTAL_ACTIVE_DURATION) {
                            const activeImg = getPortalSpriteFrame("standard", "active", 0);
                            if (activeImg && activeImg.complete && activeImg.width > 0 && activeImg.height > 0) {
                                const w = activeImg.width;
                                const h = activeImg.height;
                                ctx.drawImage(activeImg, portalScreenX - w / 2, portalScreenY - h / 2, w, h);
                                drawn = true;
                            }
                        } else {
                            p.playJump = false;
                            p.jumpStart = 0;
                        }
                    }

                    if (!drawn) {
                        const idleDef = portalDef.idle;
                        const frameDuration = 1000 / (idleDef.fps || PORTAL_ANIM_FPS);
                        const elapsed = now - (p.idleStart || now);
                        const frame = Math.floor(elapsed / frameDuration) % idleDef.frameCount;
                        const idleImg = getPortalSpriteFrame("standard", "idle", frame);
                        if (idleImg && idleImg.complete && idleImg.width > 0 && idleImg.height > 0) {
                            const w = idleImg.width;
                            const h = idleImg.height;
                            ctx.drawImage(idleImg, portalScreenX - w / 2, portalScreenY - h / 2, w, h);
                            drawn = true;
                        }
                    }
                }

                // Fallback vectoriel si les sprites ne sont pas disponibles
                if (!drawn) {
                    ctx.strokeStyle = "#00ffff"; // Cyan brillant
                    ctx.shadowBlur = 15;
                    ctx.shadowColor = "#00ffff";

                    const radius = 24;

                    ctx.beginPath();
                    ctx.arc(portalScreenX, portalScreenY, radius, 0, Math.PI * 2, false);
                    ctx.stroke();

                    ctx.beginPath();
                    ctx.arc(portalScreenX, portalScreenY, radius - 8, 0, Math.PI * 2, false);
                    ctx.globalAlpha = 0.6;
                    ctx.stroke();

                    ctx.beginPath();
                    ctx.arc(portalScreenX, portalScreenY, 4, 0, Math.PI * 2, false);
                    ctx.fillStyle = "#ffffff";
                    ctx.fill();
                }

            } else {
                // --- BASE / STATION DE RÉPARATION (Carré/Structure) ---
                // Ce n'est pas un portail de saut, c'est la base (X-1 ou X-8)
                // Dans le Flash, c'est souvent une image de station, ici on fait un carré symbolique

                ctx.strokeStyle = "#0055ff"; // Bleu foncé (Couleur Firme)
                ctx.shadowBlur = 0; // Pas de lueur magique

                // Dessin d'une "Base" (Carré avec une croix au milieu pour atterrissage)
                const baseSize = 80;
                ctx.strokeRect(portalScreenX - baseSize/2, portalScreenY - baseSize/2, baseSize, baseSize);

                ctx.beginPath();
                ctx.moveTo(portalScreenX - baseSize/2, portalScreenY - baseSize/2);
                ctx.lineTo(portalScreenX + baseSize/2, portalScreenY + baseSize/2);
                ctx.moveTo(portalScreenX + baseSize/2, portalScreenY - baseSize/2);
                ctx.lineTo(portalScreenX - baseSize/2, portalScreenY + baseSize/2);
                ctx.globalAlpha = 0.3;
                ctx.stroke();

                // Label
                ctx.globalAlpha = 1;
                ctx.fillStyle = "#0055ff";
                ctx.font = "bold 10px Arial";
                ctx.textAlign = "center";
                ctx.fillText("BASE STATION", portalScreenX, portalScreenY + baseSize/2 + 12);
            }

            ctx.restore();
        }
    }

        function drawHeroHud() {
        const width  = HERO_HUD_WIDTH;
        const height = HERO_HUD_HEIGHT;
        const x = HERO_HUD_X;
        const y = HERO_HUD_Y;

        ctx.save();
        const hudBg = getUiImage(heroShield && heroMaxShield && heroShield < heroMaxShield ? UI_SPRITES.heroHudActiveBg : UI_SPRITES.heroHudBg);
        if (hudBg && hudBg.complete && hudBg.width > 0 && hudBg.height > 0) {
            ctx.drawImage(hudBg, x, y, width, height);
        } else {
            ctx.fillStyle = "rgba(0, 0, 0, 0.75)";
            ctx.fillRect(x, y, width, height);
            ctx.strokeStyle = "#ffffff";
            ctx.lineWidth = 1;
            ctx.strokeRect(x + 0.5, y + 0.5, width - 1, height - 1);
        }

        // ----- HEADER : SHIP + NOM DU VAISSEAU -----
        ctx.font = "13px Consolas, monospace";
        ctx.fillStyle = "#ffffff";
        ctx.textBaseline = "top";

        // Titre "Ship"
        ctx.textAlign = "left";
        ctx.fillText("Ship", x + 10, y + 6);

        // Nom du héros + flags à droite
        let name = heroName || "Vous";
        const statusFlags = [];
        if (heroCloaked)   statusFlags.push("CLK");
        if (heroIshActive) statusFlags.push("ISH");
        if (heroEmpActive) statusFlags.push("EMP");
        if (heroInvincible) statusFlags.push("INV");
        if (statusFlags.length > 0) name += " [" + statusFlags.join(" ") + "]";

        ctx.textAlign = "right";
        ctx.fillText(name, x + width - 10, y + 6);

        // ----- BARRE HP -----
        const hpBarX = x + 10;
        const hpBarY = y + 26;
        const hpBarW = width - 20;
        const hpBarH = 12;

        ctx.fillStyle = "#333333";
        ctx.fillRect(hpBarX, hpBarY, hpBarW, hpBarH);

        if (heroHp !== null && heroHp >= 0) {
            const denomHp = (heroMaxHp && heroMaxHp > 0) ? heroMaxHp : (heroHp || 1);
            const ratio   = Math.max(0, Math.min(1, heroHp / denomHp));
            const color   = ratio > 0.5 ? "#00ff00" : (ratio > 0.2 ? "#ffff00" : "#ff0000");
            ctx.fillStyle = color;
            ctx.fillRect(hpBarX, hpBarY, hpBarW * ratio, hpBarH);
        }

        ctx.strokeStyle = "#000000";
        ctx.strokeRect(hpBarX + 0.5, hpBarY + 0.5, hpBarW - 1, hpBarH - 1);
        ctx.fillStyle = "#ffffff";
        ctx.font = "11px Consolas, monospace";
        ctx.textAlign = "center";

        let hpText = "HP: ?";
        if (heroHp !== null) {
            if (heroMaxHp && heroMaxHp > 0) {
                hpText = `HP: ${heroHp} / ${heroMaxHp}`;
            } else {
                hpText = `HP: ${heroHp}`;
            }
        }
        ctx.fillText(hpText, hpBarX + hpBarW / 2, hpBarY + 1);

        // ----- BARRE SHIELD -----
        const shBarX = x + 10;
        const shBarY = y + 44;
        const shBarW = width - 20;
        const shBarH = 10;

        ctx.fillStyle = "#333333";
        ctx.fillRect(shBarX, shBarY, shBarW, shBarH);

        if (heroShield !== null && heroShield >= 0) {
            const denomSh = (heroMaxShield && heroMaxShield > 0) ? heroMaxShield : (heroShield || 1);
            const ratio   = Math.max(0, Math.min(1, heroShield / denomSh));
            ctx.fillStyle = "#00bfff";
            ctx.fillRect(shBarX, shBarY, shBarW * ratio, shBarH);
        }

        ctx.strokeStyle = "#000000";
        ctx.strokeRect(shBarX + 0.5, shBarY + 0.5, shBarW - 1, shBarH - 1);
        ctx.fillStyle = "#ffffff";

        let shdText = "SHD: ?";
        if (heroShield !== null) {
            if (heroMaxShield && heroMaxShield > 0) {
                shdText = `SHD: ${heroShield} / ${heroMaxShield}`;
            } else {
                shdText = `SHD: ${heroShield}`;
            }
        }
        ctx.fillText(shdText, shBarX + shBarW / 2, shBarY);

        // ----- LIGNES INFORMATIONS SHIP (Cargo / Lasers / Rockets / CFG) -----
        ctx.textAlign = "left";
        ctx.font = "11px Consolas, monospace";
        let infoY = y + 60;

        const cargoText = (heroCargo != null && heroMaxCargo != null)
            ? `Cargo : ${heroCargo} / ${heroMaxCargo}`
            : "Cargo : ?";

        ctx.fillText(cargoText, x + 10, infoY);
        infoY += 12;

        const totalLaserAmmo =
            (ammoStock[1] || 0) +
            (ammoStock[2] || 0) +
            (ammoStock[3] || 0) +
            (ammoStock[4] || 0) +
            (ammoStock[5] || 0) +
            (ammoStock[6] || 0);

        const totalRocketAmmo =
            (ammoStock[10] || 0) +
            (ammoStock[11] || 0) +
            (ammoStock[12] || 0);

        ctx.fillText(`Lasers : ${totalLaserAmmo}`,   x + 10, infoY);
        infoY += 12;
        ctx.fillText(`Rockets: ${totalRocketAmmo}`,  x + 10, infoY);

        // ----- BOUTON REPAIR -----
        const btnX = HERO_HUD_X + HERO_HUD_WIDTH - HERO_REPAIR_BTN_WIDTH - 10;
        const btnY = HERO_HUD_Y + HERO_HUD_HEIGHT - HERO_REPAIR_BTN_HEIGHT - 8;
        ctx.fillStyle = "#222222";
        ctx.fillRect(btnX, btnY, HERO_REPAIR_BTN_WIDTH, HERO_REPAIR_BTN_HEIGHT);
        ctx.strokeStyle = "#ffffff";
        ctx.strokeRect(btnX + 0.5, btnY + 0.5, HERO_REPAIR_BTN_WIDTH - 1, HERO_REPAIR_BTN_HEIGHT - 1);
        ctx.font = "12px Consolas, monospace";
        ctx.fillStyle = "#00ff00";
        ctx.textAlign = "center";
        ctx.fillText("REPAIR", btnX + HERO_REPAIR_BTN_WIDTH / 2, btnY + 4);

        // ----- LIGNE DU BAS : CONFIG + TOTALS -----
        const statusY = y + HERO_HUD_HEIGHT - 16;
        ctx.font = "10px Consolas, monospace";
        ctx.textAlign = "left";
        ctx.fillStyle = "#00ff00";
        ctx.fillText(`CFG:${heroConfig}  L:${totalLaserAmmo}  R:${totalRocketAmmo}`, x + 10, statusY);

        ctx.restore();
    }

	

function drawGroupWindow() {
    // 1. On vérifie s'il y a des membres dans le groupe
    const memberIds = Object.keys(groupMembers);
    if (memberIds.length === 0) return; // Si personne, on ne dessine rien

    // 2. Dimensions de la fenêtre
    const w = 180;
    const h = memberIds.length * 50 + 32; // La hauteur s'adapte au nombre de joueurs
    const x = 10;  // Position X (gauche)
    const y = 150; // Position Y (en dessous de tes infos perso)

    const chrome = drawWindowChrome(x, y, w, h, `GROUPE (${memberIds.length})`);

    ctx.save();

    // 5. Dessin de chaque membre
    let cy = y + chrome.headerHeight + 6;
    for (const id of memberIds) {
        const m = groupMembers[id];
        
        // Nom du joueur
        ctx.fillStyle = "#fff"; 
        ctx.font = "11px monospace";
        // On coupe le nom s'il est trop long
        ctx.fillText(m.name.substring(0,12), x + 5, cy + 10);

        // Barre de Vie (HP)
        const hpR = (m.maxHp > 0) ? Math.max(0, Math.min(1, m.hp / m.maxHp)) : 0;
        ctx.fillStyle = "#333"; ctx.fillRect(x+5, cy+16, 150, 6); // Fond gris
        ctx.fillStyle = "#0f0"; ctx.fillRect(x+5, cy+16, 150*hpR, 6); // Barre verte

        // Barre de Bouclier (SHD)
        const shR = (m.maxShield > 0) ? Math.max(0, Math.min(1, m.shield / m.maxShield)) : 0;
        ctx.fillStyle = "#333"; ctx.fillRect(x+5, cy+24, 150, 4); // Fond gris
        ctx.fillStyle = "#00bfff"; ctx.fillRect(x+5, cy+24, 150*shR, 4); // Barre bleue

        // On descend pour le prochain joueur
        cy += 50;
    }
    ctx.restore();
}

function drawPlayerStatsHUD() {
        const x = HERO_HUD_X;
        const y = HERO_HUD_Y + HERO_HUD_HEIGHT + 10;

        const width  = 220;
        const height = 130; // un peu plus haut pour 7 lignes

        const chrome = drawWindowChrome(x, y, width, height, "User");

        ctx.save();
        ctx.fillStyle = "#ffffff";
        ctx.textAlign = "left";
        ctx.textBaseline = "top";

        let textX = x + 10;
        let textY = y + chrome.headerHeight + 2;

        ctx.font = "12px Consolas, monospace";

        ctx.fillText(`LVL : ${heroLevel}`,      textX, textY); textY += 16;
        ctx.fillText(`XP  : ${heroXp}`,         textX, textY); textY += 16;
        ctx.fillText(`HON : ${heroHonor}`,      textX, textY); textY += 16;
        ctx.fillText(`CRE : ${heroCredits}`,    textX, textY); textY += 16;
        ctx.fillText(`URI : ${heroUridium}`,    textX, textY); textY += 16;
        ctx.fillText(`JP  : ${heroJackpot}`,    textX, textY); textY += 16;
        ctx.fillText(`Keys: ${heroBootyKeys}`,  textX, textY);

        ctx.restore();
    }


    function drawQuickbar() {
        const slotCount = 10;
        const slotSize  = 40;
        const padding   = 6;
        const headerHeight = 20;

        // --- DÉFINITION DES MODES ---
        // cols = nombre de slots par ligne
        let cols = 10;
        let rows = 1;

        switch (quickbarLayoutMode) {
            case 0: cols = 10; rows = 1; break; // Horizontal
            case 1: cols = 1;  rows = 10; break; // Vertical
            case 2: cols = 5;  rows = 2; break; // Pavé 5x2
            case 3: cols = 2;  rows = 5; break; // Pavé 2x5
        }

        // Calcul des dimensions totales
        const totalWidth  = cols * (slotSize + padding) + padding;
        const totalHeight = rows * (slotSize + padding) + headerHeight + padding;

        // Centrage au premier lancement
        if (!quickbarInitialized) {
            quickbarPosition.x = (canvas.width - totalWidth) / 2;
            quickbarPosition.y = canvas.height - totalHeight - 10;
            quickbarInitialized = true;
        }

        const x = quickbarPosition.x;
        const y = quickbarPosition.y;

        // Mise à jour hitbox globale
        quickbarBounds = { x: x, y: y, w: totalWidth, h: totalHeight };
        
        ctx.save();

        // --- FOND ---
        const grad = ctx.createLinearGradient(x, y, x, y + totalHeight);
        grad.addColorStop(0, "rgba(0, 0, 0, 0.9)");
        grad.addColorStop(1, "rgba(20, 30, 50, 0.9)");
        ctx.fillStyle = grad;
        ctx.fillRect(x, y, totalWidth, totalHeight);
        
        ctx.strokeStyle = isDraggingQuickbar ? "#00ff00" : "#555";
        ctx.lineWidth = 1;
        ctx.strokeRect(x + 0.5, y + 0.5, totalWidth - 1, totalHeight - 1);

        // --- HEADER (BOUTONS) ---
        const iconSize = 14;
        const btnY = y + 3;
        
        // Positionnement : Toujours en haut à droite du bloc, peu importe la forme
        let bx = x + totalWidth - iconSize - 4;

        // 1. Cadenas (Lock)
        let lockX = bx;
        quickbarLockHitbox = { x: lockX, y: btnY, w: iconSize, h: iconSize };

        const lockImg = getUiImage(UI_SPRITES.quickbarLockIcon);
        if (lockImg && lockImg.complete && lockImg.width > 0) {
            ctx.globalAlpha = quickbarLocked ? 1 : 0.75;
            ctx.drawImage(lockImg, lockX, btnY, iconSize, iconSize);
            ctx.globalAlpha = 1;
        } else {
            ctx.fillStyle = quickbarLocked ? "#ffcc00" : "#00ff00";
            ctx.font = "10px Arial";
            ctx.textAlign = "center"; ctx.textBaseline = "middle";
            ctx.fillText(quickbarLocked ? "L" : "U", lockX + iconSize/2, btnY + iconSize/2);
        }
        bx -= (iconSize + 4);

        // 2. Rotation (Rotate)
        // En mode vertical strict (cols=1), la barre est fine, on s'assure que ça rentre
        if (cols === 1) { 
            bx = x + 4; // On le met à gauche si très étroit
        }

        if (!quickbarMinimized) {
            let rotX = bx;
            quickbarRotateHitbox = { x: rotX, y: btnY, w: iconSize, h: iconSize };
            const rotImg = getUiImage(UI_SPRITES.quickbarRotateIcon);
            if (rotImg && rotImg.complete && rotImg.width > 0) {
                ctx.globalAlpha = quickbarLocked ? 0.6 : 1;
                ctx.drawImage(rotImg, rotX, btnY, iconSize, iconSize);
                ctx.globalAlpha = 1;
            } else {
                ctx.fillStyle = quickbarLocked ? "#444" : "#00ccff";
                ctx.fillText("⟳", rotX + iconSize/2, btnY + iconSize/2);
            }

            if (cols > 1) bx -= (iconSize + 4); // Décalage si on a de la place
        }

        // 3. Minimiser
        let minX = (cols === 1) ? (x + totalWidth/2 - iconSize/2) : bx; // Centré si vertical
        quickbarMinHitbox = { x: minX, y: btnY, w: iconSize, h: iconSize };
        const minImg = getUiImage(UI_SPRITES.quickbarMinimizeIcon);
        if (minImg && minImg.complete && minImg.width > 0) {
            ctx.drawImage(minImg, minX, btnY, iconSize, iconSize);
            if (quickbarMinimized) {
                ctx.save();
                ctx.globalAlpha = 0.6;
                ctx.fillStyle = "#000";
                ctx.fillRect(minX, btnY, iconSize, iconSize);
                ctx.restore();
            }
        } else {
            ctx.fillStyle = "#ccc";
            ctx.fillText(quickbarMinimized ? "+" : "-", minX + iconSize/2, btnY + iconSize/2);
        }

        if (quickbarMinimized) {
            ctx.restore();
            return;
        }

        // --- SLOTS ---
        // Point de départ (sous le header)
        const startX = x + padding;
        const startY = y + headerHeight;

        for (let slot = 1; slot <= slotCount; slot++) {
            // Maths de grille :
            // Index base 0 pour le calcul : slot - 1
            // Colonne actuelle = index % cols
            // Ligne actuelle = Math.floor(index / cols)
            
            const idx = slot - 1;
            const col = idx % cols;
            const row = Math.floor(idx / cols);

            const slotScreenX = startX + col * (slotSize + padding);
            const slotScreenY = startY + row * (slotSize + padding);

            quickbarSlotRects[slot] = { x: slotScreenX, y: slotScreenY, w: slotSize, h: slotSize };
            quickbarSlotHitboxes[slot] = quickbarSlotRects[slot];

            const item = quickSlots[slot];

            // Fond
            const slotImg = getUiImage(UI_SPRITES.quickbarSlot);
            if (slotImg && slotImg.complete && slotImg.width > 0 && slotImg.height > 0) {
                ctx.drawImage(slotImg, slotScreenX, slotScreenY, slotSize, slotSize);
            } else {
                ctx.fillStyle = "rgba(30, 30, 30, 1)";
                ctx.fillRect(slotScreenX, slotScreenY, slotSize, slotSize);
            }

            // Bordure
            let borderColor = "#666";
            let isSelected = false;
            if (item) {
                if (item.type === "ammo" && currentAmmoId === item.id) {
                    borderColor = "#ffffff"; isSelected = true;
                } else if (item.type === "rocket" && currentRocketId === item.id) {
                    borderColor = "#ffcc00"; isSelected = true;
                }
            }
            ctx.strokeStyle = borderColor;
            ctx.lineWidth = isSelected ? 2 : 1;
            ctx.strokeRect(slotScreenX + 0.5, slotScreenY + 0.5, slotSize - 1, slotSize - 1);

            // Contenu
            if (item) {
                let label = item.label || (item.code || "x" + item.id);
                label = label.substring(0, 4);

                const iconPath = getQuickbarIconPath(item);
                const iconImg = getUiImage(iconPath);
                if (iconImg && iconImg.complete && iconImg.width > 0) {
                    const iconH = slotSize - 12;
                    const iconW = iconImg.width * (iconH / iconImg.height);
                    const ix = slotScreenX + (slotSize - iconW) / 2;
                    const iy = slotScreenY + 4;
                    ctx.drawImage(iconImg, ix, iy, iconW, iconH);
                }

                ctx.fillStyle = "#eee";
                ctx.font = "bold 11px Arial";
                ctx.textAlign = "center"; ctx.textBaseline = "middle";
                ctx.fillText(label, slotScreenX + slotSize/2, slotScreenY + slotSize - 10);

                let stockId = item.stockId;
                if (!stockId && QUICKBAR_ITEMS_BY_CATEGORY[item.type]) {
                     const found = QUICKBAR_ITEMS_BY_CATEGORY[item.type].find(i => i.id === item.id || i.code === item.code);
                     if(found) stockId = found.stockId;
                }
                
                if (stockId && ammoStock[stockId] !== undefined) {
                    let qty = ammoStock[stockId];
                    ctx.fillStyle = (qty > 0) ? "#aaffaa" : "#ff4444";
                    ctx.font = "9px Arial";
                    ctx.textAlign = "right";
                    let qtyStr = qty > 9999 ? (qty/1000).toFixed(0)+"k" : qty.toString();
                      ctx.fillText(qtyStr, slotScreenX + slotSize - 2, slotScreenY + slotSize - 2);
                    
                    if (qty <= 0) {
                        ctx.fillStyle = "rgba(255, 0, 0, 0.3)";
                          ctx.fillRect(slotScreenX, slotScreenY, slotSize, slotSize);
                    }
                }
                
                const code = getActionCodeForSlot(slot);
                if (code) {
                    const cd = getCooldownInfo(code);
                    if (cd) {
                        const ratio = cd.remaining / cd.total;
                        ctx.fillStyle = "rgba(0, 0, 0, 0.6)";
                        const h = slotSize * ratio;
                          ctx.fillRect(slotScreenX, slotScreenY + slotSize - h, slotSize, h);
                    }
                }
            }

            // Label Touche (discret)
            const keyLabel = getKeyLabelForSlot(slot);
            ctx.fillStyle = "#999";
            ctx.font = "9px Arial";
            ctx.textAlign = "left"; ctx.textBaseline = "top";
            ctx.fillText(keyLabel, slotScreenX + 2, slotScreenY + 2);
        }
        ctx.restore();
    }
	
	function drawTooltip() {
        if (!activeTooltip) return;
        const x = activeTooltip.x + 10; // Décalage souris
        const y = activeTooltip.y + 10;
        const text = activeTooltip.text;

        ctx.save();
        ctx.font = "12px Arial";
        const w = ctx.measureText(text).width + 8;
        const h = 20;

        // Fond noir
        ctx.fillStyle = "rgba(0, 0, 0, 0.8)";
        ctx.fillRect(x, y, w, h);
        ctx.strokeStyle = "#aaa";
        ctx.lineWidth = 1;
        ctx.strokeRect(x, y, w, h);

        // Texte blanc
        ctx.fillStyle = "#fff";
        ctx.textAlign = "left";
        ctx.textBaseline = "middle";
        ctx.fillText(text, x + 4, y + h/2);
        ctx.restore();
    }



    function drawDebugInfo() {
        const baseY = canvas.height - 10;
        ctx.fillStyle = "#0f0";
        ctx.font = "14px Consolas, monospace";
        ctx.textAlign = "left";
        for (let i = 0; i < infoMessages.length; i++) {
            const text = infoMessages[i];
            const yy = baseY - i * 18;
            ctx.fillText(text, 10, yy);
        }
    }
	// ========================================================
// GESTIONNAIRE DE FENÊTRES & MENU PRINCIPAL (Style Flash)
// ========================================================
function initWindowManager() {
    const style = document.createElement('style');
    style.innerHTML = `
        /* --- BARRE D'ICÔNES (GAUCHE) --- */
        #mainMenuContainer {
            position: absolute;
            top: 12px; left: 12px;
            width: 50px;
            padding: 4px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            z-index: 2000;
            background: ${UI_SPRITES.dockBg ? `url('${UI_SPRITES.dockBg}')` : "rgba(0, 10, 20, 0.85)"};
            background-size: 100% 100%;
            border: 1px solid #4a6b8c;
            border-radius: 6px;
            box-shadow: 0 0 8px #000;
        }
        .mainMenuIcon {
            width: 40px; height: 40px;
            background-color: rgba(0, 20, 40, 0.6);
            background-size: 80% 80%;
            background-position: center;
            background-repeat: no-repeat;
            border: 1px solid #4a6b8c;
            border-radius: 6px;
            cursor: pointer;
            display: flex; justify-content: center; align-items: center;
            color: #00aaff; font-weight: bold; font-size: 10px; font-family: Arial;
            transition: all 0.2s;
            box-shadow: 0 0 5px rgba(0,0,0,0.5);
        }
        .mainMenuIcon:hover { border-color: #fff; background-color: rgba(0, 40, 80, 0.9); }
        .mainMenuIcon.active { border-color: #00ff00; box-shadow: 0 0 8px #00ff00; }

        /* --- FENÊTRES GÉNÉRIQUES --- */
        .gameWindow {
            position: absolute;
            color: #ccc; font-family: Consolas, monospace; font-size: 11px;
            display: none; /* Caché par défaut */
            flex-direction: column;
            z-index: 1000;
            box-shadow: 0 0 10px #000;
        }
        .gameWindow.basicWindow {
            background: rgba(0, 10, 20, 0.85);
            border: 1px solid #4a6b8c;
        }
        .basicWindow .basicHeader {
            height: 24px;
            background: rgba(0,0,0,0.65);
            border-bottom: 1px solid #4a6b8c;
            display:flex;
            align-items:center;
            justify-content: space-between;
            padding: 0 6px;
            cursor: move;
            color: #00aaff;
            font-weight: bold;
        }
        .basicWindow .basicContent {
            padding: 6px;
            background: rgba(0,0,0,0.55);
            color: #ccc;
            flex:1;
        }
        .basicWindow .basicBtn { cursor:pointer; color:#fff; background:#222; border:1px solid #555; padding:1px 4px; font-size:10px; }
        .windowChrome { position:absolute; inset:0; pointer-events:none; }
        .windowChrome .winCorner { position:absolute; width:23px; height:21px; background-size:100% 100%; }
        .windowChrome .winCorner.tl { top:0; left:0; background-image:url('${UI_SPRITES.windowCornerTL}'); }
        .windowChrome .winCorner.tr { top:0; right:0; background-image:url('${UI_SPRITES.windowCornerTR}'); }
        .windowChrome .winCorner.bl { bottom:0; left:0; background-image:url('${UI_SPRITES.windowCornerBL}'); }
        .windowChrome .winCorner.br { bottom:0; right:0; background-image:url('${UI_SPRITES.windowCornerBR}'); }
        .windowChrome .winEdge.top { position:absolute; left:23px; right:23px; top:0; height:28px; background:${UI_SPRITES.windowTopEdge ? `url('${UI_SPRITES.windowTopEdge}')` : "rgba(0,0,0,0.5)"}; background-repeat: repeat-x; background-size:auto 100%; }
        .windowChrome .winEdge.bottom { position:absolute; left:23px; right:23px; bottom:0; height:28px; background:${UI_SPRITES.windowBottomEdge ? `url('${UI_SPRITES.windowBottomEdge}')` : "rgba(0,0,0,0.5)"}; background-repeat: repeat-x; background-size:auto 100%; }
        .windowChrome .winEdge.left { position:absolute; left:0; top:21px; bottom:21px; width:16px; background:${UI_SPRITES.windowSide ? `url('${UI_SPRITES.windowSide}')` : "rgba(0,0,0,0.6)"}; background-repeat: repeat-y; background-size:100% auto; }
        .windowChrome .winEdge.right { position:absolute; right:0; top:21px; bottom:21px; width:16px; background:${UI_SPRITES.windowSide ? `url('${UI_SPRITES.windowSide}')` : "rgba(0,0,0,0.6)"}; background-repeat: repeat-y; background-size:100% auto; }
        .windowInterior { position:absolute; left:16px; right:16px; top:28px; bottom:28px; background:${UI_SPRITES.windowBg ? `url('${UI_SPRITES.windowBg}')` : "rgba(0, 10, 20, 0.85)"}; background-size:100% 100%; display:flex; flex-direction:column; }
        .gameWindow.chatTheme .winCorner.tl { background-image:url('${UI_SPRITES.chatCornerTL || UI_SPRITES.windowCornerTL || ''}'); }
        .gameWindow.chatTheme .winCorner.tr { background-image:url('${UI_SPRITES.chatCornerTR || UI_SPRITES.windowCornerTR || ''}'); }
        .gameWindow.chatTheme .winCorner.bl { background-image:url('${UI_SPRITES.chatCornerBL || UI_SPRITES.windowCornerBL || ''}'); }
        .gameWindow.chatTheme .winCorner.br { background-image:url('${UI_SPRITES.chatCornerBR || UI_SPRITES.windowCornerBR || ''}'); }
        .gameWindow.chatTheme .winEdge.top { background:${UI_SPRITES.chatTopEdge ? `url('${UI_SPRITES.chatTopEdge}')` : (UI_SPRITES.windowTopEdge ? `url('${UI_SPRITES.windowTopEdge}')` : "rgba(0,0,0,0.5)")}; background-repeat: repeat-x; background-size:auto 100%; }
        .gameWindow.chatTheme .winEdge.bottom { background:${UI_SPRITES.chatBottomEdge ? `url('${UI_SPRITES.chatBottomEdge}')` : (UI_SPRITES.windowBottomEdge ? `url('${UI_SPRITES.windowBottomEdge}')` : "rgba(0,0,0,0.5)")}; background-repeat: repeat-x; background-size:auto 100%; }
        .gameWindow.chatTheme .winEdge.left,
        .gameWindow.chatTheme .winEdge.right { background:${UI_SPRITES.chatSide ? `url('${UI_SPRITES.chatSide}')` : (UI_SPRITES.windowSide ? `url('${UI_SPRITES.windowSide}')` : "rgba(0,0,0,0.6)")}; background-repeat: repeat-y; background-size:100% auto; }
        .gameWindow.chatTheme .windowInterior { background:${UI_SPRITES.chatBgTile ? `url('${UI_SPRITES.chatBgTile}')` : (UI_SPRITES.windowBg ? `url('${UI_SPRITES.windowBg}')` : "rgba(0, 10, 20, 0.85)")}; }
        .gwHeader {
            height: 28px; background: ${UI_SPRITES.windowHeader ? `url('${UI_SPRITES.windowHeader}')` : "rgba(0, 0, 0, 0.8)"};
            background-repeat: repeat-x; background-size:auto 100%;
            border-bottom: 1px solid #4a6b8c;
            display: flex; justify-content: space-between; align-items: center;
            padding: 0 5px; cursor: move;
        }
		        .windowHeaderIcon {
            width: 22px;
            height: 22px;
            margin-right: 6px;
            background-size: 90% 90%;
            background-position: center;
            background-repeat: no-repeat;
            border-radius: 4px;
            box-shadow: 0 0 4px #000;
            cursor: pointer;
            flex-shrink: 0;
        }
        .windowHeaderIcon:hover {
            box-shadow: 0 0 6px #0ff;
        }

        .gameWindow.chatTheme .gwHeader { background:${UI_SPRITES.chatHeader ? `url('${UI_SPRITES.chatHeader}')` : (UI_SPRITES.windowHeader ? `url('${UI_SPRITES.windowHeader}')` : "rgba(0,0,0,0.8)")}; }
        .gameWindow.chatTheme .gwContent { background:${UI_SPRITES.chatFooter ? `url('${UI_SPRITES.chatFooter}')` : (UI_SPRITES.windowFooter ? `url('${UI_SPRITES.windowFooter}')` : "rgba(0,0,0,0.25)")}; background-size:100% 100%; }
        .gwTitle { color: #00aaff; font-weight: bold; font-size: 11px; text-shadow: 1px 1px 0 #000; }
        .gwButtons { display: flex; gap: 4px; }
        .gwBtn { cursor: pointer; width: 16px; height: 16px; background-size: 100% 100%; background-repeat: no-repeat; filter: drop-shadow(0 0 2px #000); }
        .gwBtn.closeBtn { background-image: url('${UI_SPRITES.buttonClose}'); }
        .gwBtn.collapseBtn { background-image: url('${UI_SPRITES.buttonCollapse}'); }
        .gwBtn:hover { filter: drop-shadow(0 0 4px #0ff); }
        .gwContent { padding: 6px; overflow: hidden; flex: 1; position: relative; background: ${UI_SPRITES.windowFooter ? `url('${UI_SPRITES.windowFooter}')` : "rgba(0,0,0,0.25)"}; background-size: 100% 100%; }
        
        /* Barres de progression (HP/SHD) */
        .statBarBox { width: 100%; height: 10px; background: #222; border: 1px solid #555; margin-bottom: 2px; position:relative; }
        .statBarFill { height: 100%; width: 50%; transition: width 0.2s; }
        .statBarText { position: absolute; top:-1px; left:0; width:100%; text-align:center; font-size:9px; color:#fff; text-shadow:1px 1px 0 #000; }

        .uiStatRow { display:flex; align-items:center; gap:6px; margin-bottom:4px; }
        .uiStatIcon { width:16px; height:16px; background-size: contain; background-repeat:no-repeat; }
        .uiStatBar { position:relative; flex:1; height:16px; border:1px solid #152536; background:${UI_SPRITES.windowFooter ? `url('${UI_SPRITES.windowFooter}')` : "rgba(0,0,0,0.4)"}; background-size:100% 100%; }
        .uiStatFill { position:absolute; left:0; top:0; bottom:0; background:rgba(0,255,0,0.55); }
        .uiStatText { position:absolute; left:0; top:0; width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:10px; color:#fff; text-shadow:1px 1px 0 #000; }
        .uiValueRow { display:flex; align-items:center; justify-content:space-between; margin-bottom:2px; color:#e0efff; font-size:11px; }
        .uiValueRow .label { display:flex; align-items:center; gap:6px; }
    `;
    document.head.appendChild(style);

    // Création de la barre HTML
    const dock = document.createElement('div');
    dock.id = 'mainMenuContainer';
    document.body.appendChild(dock);
}

// Liste des fenêtres gérées
const WINDOWS_CONFIG = {
    'user':  { title: 'PILOT SHEET', w: 220, h: 130, icon: 'U' }, // U = User
    'ship':  { title: 'SHIP',        w: 220, h: 100, icon: 'S' }, // S = Ship
    'chat':  { title: 'CHAT',        w: 320, h: 220, icon: '@' }, // @ = Chat
    'group': { title: 'GROUP',       w: 160, h: 200, icon: 'G' }, // G = Group
    'log':   { title: 'LOG',         w: 280, h: 160, icon: 'L' }, // L = Log
    'quest': { title: 'MISSIONS',    w: 250, h: 300, icon: '!' }, // ! = Quests
    'map':   { title: 'MINIMAP',     icon: 'M' }  // M = Minimap (cas spécial)
};

const WINDOW_ICON_PATHS = {
    user: UI_SPRITES.mainMenuIconInfo,
    ship: UI_SPRITES.mainMenuIconShip,
    chat: UI_SPRITES.dockIconChat || UI_SPRITES.mainMenuIconChat,
    group: UI_SPRITES.dockIconGroup || UI_SPRITES.mainMenuIconGroup,
    log: UI_SPRITES.mainMenuIconLog,
    quest: UI_SPRITES.mainMenuIconQuest,
    map: UI_SPRITES.mainMenuIconMap
};

const BASIC_WINDOW_KEYS = new Set(["chat", "log", "ship", "user", "group"]);

const WINDOW_DEFAULT_POS = {
    ship:  { top: 80,  left: 70 },
    user:  { top: 200, left: 70 },
    group: { top: 80,  left: 280 },
    log:   { top: 360, left: 70 },
    chat:  { top: 540, left: 70 },
    quest: { top: 140, left: 520 }
};

// État d'ouverture des fenêtres (pour sauvegarde)
let windowStates = {
    user: true, ship: true, chat: true, group: false, log: true, quest: false, map: true
};

function createGameWindows() {
    const dock = document.getElementById('mainMenuContainer');
    if (!dock) return;

    for (const [key, cfg] of Object.entries(WINDOWS_CONFIG)) {
        // 1. Créer l'icône
        const icon = document.createElement('div');
        icon.className = 'mainMenuIcon';
        const iconPath = WINDOW_ICON_PATHS[key];
        if (iconPath) {
            icon.style.backgroundImage = `url('${iconPath}')`;
            icon.textContent = '';
        } else {
            icon.textContent = cfg.icon;
        }
        icon.id = 'icon_' + key;
        icon.title = cfg.title;
        icon.addEventListener('click', () => toggleWindow(key));
        dock.appendChild(icon);

        // 2. Créer la fenêtre (SAUF pour la Minimap qui est un dessin Canvas)
        if (key === 'log') {
            initGameLogWindow();
        } else if (key !== 'map' && key !== 'quest') {
            createGenericWindow(key, cfg);
        }
    }
    refreshWindowsVisibility();
}

function createGenericWindow(key, cfg) {
    const div = document.createElement('div');
    div.id = 'win_' + key;

    const isBasic = BASIC_WINDOW_KEYS.has(key); // user / ship / chat / group
    div.className = isBasic ? 'gameWindow basicWindow' : 'gameWindow';

    // Thème spécial pour le chat si tu veux garder ça
    if (!isBasic && key === 'chat') {
        div.classList.add('chatTheme');
    }

    // Taille de la fenêtre
    if (cfg.w) div.style.width  = cfg.w + 'px';
    if (cfg.h) div.style.height = cfg.h + 'px';

    // Position par défaut (équivalent des valeurs dans FULL_MERGE_AS)
    const pos = WINDOW_DEFAULT_POS[key] || {};
    div.style.top  = (pos.top  != null ? pos.top  : 100) + 'px';
    div.style.left = (pos.left != null ? pos.left : 100) + 'px';

    // ---------- Contenu HTML ----------
    if (isBasic) {
        // Fenêtres "simples" style user / ship / group
        div.innerHTML = `
            <div class="basicHeader" id="head_${key}">
                <span class="gwTitle">${cfg.title}</span>
                <div class="gwButtons">
                    <span class="basicBtn collapseBtn">-</span>
                    <span class="basicBtn closeBtn">x</span>
                </div>
            </div>
            <div class="basicContent" id="content_${key}"></div>
        `;
    } else {
        // Fenêtre avec chrome complet (utilisé pour chat si tu le souhaites)
        div.innerHTML = `
            <div class="windowChrome">
                <div class="winCorner tl"></div>
                <div class="winCorner tr"></div>
                <div class="winCorner bl"></div>
                <div class="winCorner br"></div>
                <div class="winEdge top"></div>
                <div class="winEdge bottom"></div>
                <div class="winEdge left"></div>
                <div class="winEdge right"></div>
            </div>
            <div class="windowInterior">
                <div class="gwHeader" id="head_${key}">
                    <span class="gwTitle">${cfg.title}</span>
                    <div class="gwButtons">
                        <span class="gwBtn collapseBtn"></span>
                        <span class="gwBtn closeBtn"></span>
                    </div>
                </div>
                <div class="gwContent" id="content_${key}"></div>
            </div>
        `;
    }

    document.body.appendChild(div);

    const content = div.querySelector('.gwContent') || div.querySelector('.basicContent');

    // ---------- Icône dans le header (comme dans le main.swf) ----------
    const header = div.querySelector('.gwHeader') || div.querySelector('.basicHeader');
    if (header && BASIC_WINDOW_KEYS.has(key)) {
        const headerIcon = document.createElement('div');
        headerIcon.className = 'windowHeaderIcon';
        headerIcon.id = 'header_icon_' + key;

        const iconPath = WINDOW_ICON_PATHS[key];
        if (iconPath) {
            headerIcon.style.backgroundImage = `url('${iconPath}')`;
        } else if (cfg.icon) {
            // Fallback : caractère si jamais aucune image n’est définie
            headerIcon.textContent = cfg.icon;
        }

        // Clic sur l’icône = replie la fenêtre dans la colonne de gauche
        headerIcon.addEventListener('click', () => {
            toggleWindow(key, false);
        });

        // On insère l’icône tout à gauche, avant le titre
        header.insertBefore(headerIcon, header.firstChild);
    }

    // ---------- Bouton fermeture (X) ----------
    const closeBtn = div.querySelector('.closeBtn');
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            toggleWindow(key, false);
        });
    }

    // ---------- Bouton réduire / étendre (-) ----------
    const collapseBtn = div.querySelector('.collapseBtn');
    if (collapseBtn && content) {
        collapseBtn.addEventListener('click', () => {
            const collapsed = content.dataset.collapsed === '1';
            content.dataset.collapsed = collapsed ? '0' : '1';
            content.style.display = collapsed ? 'block' : 'none';
        });
    }

    // ---------- Drag & drop ----------
    const dragHandle = div.querySelector('.gwHeader') || div.querySelector('.basicHeader');
    if (dragHandle) {
        makeElementDraggable(div, dragHandle);
    }
}


function toggleWindow(key, forceState) {
    // Si forceState est défini, on l'utilise, sinon on inverse l'état actuel
    const newState = (forceState !== undefined) ? forceState : !windowStates[key];
    windowStates[key] = newState;
    refreshWindowsVisibility();
    saveInterfaceLayout();
}

function refreshWindowsVisibility() {
    for (const key in windowStates) {
        const isOpen = !!windowStates[key];

        // Icône de la colonne de gauche
        const iconEl = document.getElementById('icon_' + key);

        // Id HTML de la fenêtre correspondante
        let winId = 'win_' + key;
        if (key === 'quest') winId = 'questWindow';
        if (key === 'log')   winId = 'gameLogWindow';

        const winEl = document.getElementById(winId);
        const headerIcon = winEl ? winEl.querySelector('.windowHeaderIcon') : null;

        // --- Colonne de gauche (équivalent leftDynamicSlot du main.swf) ---
        if (iconEl) {
            // Fenêtre ouverte -> icône masquée, fenêtre fermée -> icône visible (minimap comprise)
            iconEl.style.display = isOpen ? 'none' : 'flex';
            iconEl.classList.toggle('active', !isOpen);
        }

        // --- Fenêtre elle-même ---
        if (winEl) {
            winEl.style.display = isOpen ? 'flex' : 'none';
        }

        // --- Icône dans le header : visible seulement quand la fenêtre est ouverte ---
        if (headerIcon) {
            headerIcon.style.display = isOpen ? 'inline-flex' : 'none';
        }

        // Cas spécial minimap (pour le dessin dans le canvas)
        if (key === 'map') {
            window.showMinimap = isOpen;
            minimapPositionDirty = true;
        }
    }
}


// Utilitaire pour rendre n'importe quelle div déplaçable
function makeElementDraggable(elmnt, handle) {
    let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    handle.onmousedown = dragMouseDown;

    function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        document.onmousemove = elementDrag;
        // Met la fenêtre au premier plan
        document.querySelectorAll('.gameWindow').forEach(w => w.style.zIndex = 1000);
        elmnt.style.zIndex = 1001;
    }

    function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
    }

    function closeDragElement() {
        document.onmouseup = null;
        document.onmousemove = null;
    }
}

	
	// ========================================================
    // INTERFACE DU CHAT (HTML/CSS)
    // ========================================================
function renderChatTabs() {
    const tabs = document.getElementById('chatTabs');
    if (!tabs) return;
    ensureDefaultChatRooms();
    tabs.innerHTML = '';
    chatRooms.sort((a, b) => a.id - b.id);
    for (const room of chatRooms) {
        const tab = document.createElement('div');
        tab.className = 'chatTab' + (room.id === chatCurrentRoomId ? ' active' : '');
        tab.style.flex = '1';
        tab.style.padding = '4px';
        tab.style.textAlign = 'center';
        tab.style.color = room.id === chatCurrentRoomId ? '#00aaff' : '#666';
        tab.style.background = room.id === chatCurrentRoomId ? '#051525' : '#030a12';
        tab.style.fontSize = '10px';
        tab.textContent = room.name;
        tab.addEventListener('click', () => {
            chatCurrentRoomId = room.id;
            renderChatTabs();
            renderChatContent();

            if (!(chatBuffers[chatCurrentRoomId] && chatBuffers[chatCurrentRoomId].length)) {
                addChatMessage(null, "Système: Chat connecté.", chatCurrentRoomId, "chatSystem");
            }
        });
        tabs.appendChild(tab);
    }
}

function renderChatContent() {
    const content = document.getElementById('chatContent');
    if (!content) return;
    const buf = chatBuffers[chatCurrentRoomId] || [];
    content.innerHTML = '';
    for (const entry of buf) {
        const div = document.createElement('div');
        div.className = 'chatLine ' + (entry.typeClass || 'chatGlobal');
        div.innerHTML = entry.html;
        content.appendChild(div);
    }
    content.scrollTop = content.scrollHeight;
}

function initChatInterface() {
    // On vérifie régulièrement si la fenêtre du chat a été créée
    const checkExist = setInterval(() => {
        // On cherche l'intérieur de la nouvelle fenêtre mobile
        const container = document.getElementById('content_chat');

        if (container) {
            clearInterval(checkExist); // On arrête de chercher, on a trouvé !

            container.innerHTML = `
                <div id="chatTabs" style="display:flex; background:rgba(0,0,0,0.5); border-bottom:1px solid #4a6b8c; cursor:pointer; height:25px;"></div>

                <div id="chatContent" style="
                    flex:1;
                    overflow-y:auto;
                    padding:5px;
                    font-size:11px;
                    color:#ddd;
                    background:rgba(0,0,0,0.6);
                "></div>

                <div id="chatInputContainer" style="display:flex; border-top:1px solid #4a6b8c; padding:2px; height:25px;">
                    <input id="chatInput" type="text" style="
                        flex:1;
                        border:none;
                        color:white;
                        padding:2px 5px;
                        font-size:11px;
                        background:rgba(0,0,0,0.5);
                        outline:none;
                    " placeholder="Message...">

                    <button id="chatSendBtn" style="
                        background:rgba(0,0,0,0.7);
                        color:white;
                        border:none;
                        width:25px;
                        cursor:pointer;
                        padding:0;
                    ">&gt;</button>
                </div>
            `;

            renderChatTabs();
            renderChatContent();

            // --- RÉ-ACTIVATION DE LA LOGIQUE (Rien n'est supprimé) ---
            const input  = document.getElementById('chatInput');
            const sendBtn = document.getElementById('chatSendBtn');

            function sendMessage() {
                const msg = input.value.trim();
                // On vérifie que le WebSocket (chatWs) est bien ouvert
                if (msg.length > 0 && chatWs && chatWs.readyState === WebSocket.OPEN) {
                    chatWs.send(`a|${chatCurrentRoomId}|${msg}`);
                    input.value = "";
                } else if (!chatWs || chatWs.readyState !== WebSocket.OPEN) {
                    const log = document.getElementById('chatContent');
                    if (log) log.innerHTML += `<div style="color:red">Erreur: Chat déconnecté.</div>`;
                }
            }

            if (input && sendBtn) {
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') sendMessage();
                    e.stopPropagation(); // Empêche le vaisseau de bouger quand on écrit
                });
                sendBtn.addEventListener('click', sendMessage);

                // Effets visuels du bouton : over / down
                sendBtn.addEventListener('mouseenter', () => {
                    sendBtn.style.filter = "brightness(1.1)";
                });
                sendBtn.addEventListener('mouseleave', () => {
                    sendBtn.style.filter = "";
                });
                sendBtn.addEventListener('mousedown', () => {
                    sendBtn.style.filter = "brightness(0.8)";
                });
                sendBtn.addEventListener('mouseup', () => {
                    sendBtn.style.filter = "brightness(1.1)";
                });

                // Empêche de cliquer "au travers" de la fenêtre pour déplacer le vaisseau
                container.addEventListener('mousedown', (e) => e.stopPropagation());
            }
        }
    }, 500);
}