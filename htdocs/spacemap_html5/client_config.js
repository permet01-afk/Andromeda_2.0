// ===================================================================
//  CLIENT HTML5 ANDROMEDA - VERSION FINALE (Fix Cargo/Loot/Explo/Factions)
// ===================================================================

console.log("ANDROMEDA_CONFIG =", window.ANDROMEDA_CONFIG);
	
        // Niveau de zoom du jeu (mis à l'échelle par la résolution logique)
        const BASE_GAME_SCALE = 0.82;

    // -------------------------------------------------
    // 0. CONFIG
    // -------------------------------------------------
    const cfg = window.ANDROMEDA_CONFIG || {};

    // Bornes de la map (comme côté serveur)
    const MAP_MIN_X = 0;
    const MAP_MAX_X = 21000;
    const MAP_MIN_Y = 0;
    const MAP_MAX_Y = 13200;

    const MAP_WIDTH  = MAP_MAX_X - MAP_MIN_X;
    const MAP_HEIGHT = MAP_MAX_Y - MAP_MIN_Y;
	 // Centre logique de la map (utile pour les futurs paquets "m")
    let mapCenterX = (MAP_MIN_X + MAP_MAX_X) / 2;
    let mapCenterY = (MAP_MIN_Y + MAP_MAX_Y) / 2;

    const MINIMAP_MARGIN = 10;
    const MINIMAP_FRAME_PADDING = 8;
    const MINIMAP_HEADER_HEIGHT = 26;
    const MINIMAP_INFO_HEIGHT = 27;
    const MINIMAP_BUTTON_SIZE = 16;
    const MINIMAP_SCALE_MIN = 3;
    const MINIMAP_SCALE_MAX = 11;
    const MINIMAP_SCALE_DEFAULT = 8;

    let MINIMAP_WIDTH  = 0;
    let MINIMAP_HEIGHT = 0;
    let minimapScaleFactor = MINIMAP_SCALE_DEFAULT;
    let minimapPosition = null; // { x, y } = coin supérieur gauche du cadre
    let minimapDragOffset = { x: 0, y: 0 };
    let isDraggingMinimap = false;
    let minimapPositionDirty = false;
    let minimapHitboxes = {
        icon: null,
        zoomIn: null,
        zoomOut: null,
        close: null,
        content: null,
        frame: null
    };
    let minimapHoverState = {
        icon: false,
        header: false,
        zoomIn: false,
        zoomOut: false
    };
    window.showMinimap = true;

    // Échelle de la minimap (équivalent du combinedScaleFactor de l'AS3)
    function getMiniMapScale() {
        // Dans le client Flash, combinedScaleFactor = 1 / (zoomFactor * 10)
        return 1 / (minimapScaleFactor * 10);
    }

    function updateMinimapSize() {
        const scale = getMiniMapScale();
        MINIMAP_WIDTH  = Math.round(MAP_WIDTH  * scale);
        MINIMAP_HEIGHT = Math.round(MAP_HEIGHT * scale);
    }

    function clampMinimapScale(value) {
        return Math.max(MINIMAP_SCALE_MIN, Math.min(MINIMAP_SCALE_MAX, value));
    }

    function setMinimapScale(newScale, options = {}) {
        const previous = minimapScaleFactor;
        const clamped  = clampMinimapScale(Math.round(newScale));
        minimapScaleFactor = clamped;
        updateMinimapSize();

        if ((clamped !== previous || options.forceSend) && typeof sendSetting === "function") {
            sendSetting('MINIMAP_SCALE', minimapScaleFactor);
        }

        if (options.message) {
            const msg = (typeof options.message === "function")
                ? options.message(minimapScaleFactor, previous)
                : options.message;
            addInfoMessage(msg);
        }

        return { previous, current: minimapScaleFactor, changed: clamped !== previous };
    }

    function zoomMinimapIn() {
        setMinimapScale(minimapScaleFactor - 1);
    }

    function zoomMinimapOut() {
        setMinimapScale(minimapScaleFactor + 1);
    }

    function resetMinimapZoom() {
        setMinimapScale(MINIMAP_SCALE_DEFAULT, { forceSend: true });
        addInfoMessage("Taille minimap réinitialisée");
    }

    function getMinimapLayout(isOpenOverride = null) {
        const isOpen = (isOpenOverride !== null) ? isOpenOverride : (window.showMinimap !== false);
        const contentHeight = isOpen ? MINIMAP_HEIGHT + MINIMAP_INFO_HEIGHT : 0;

        const outerWidth  = MINIMAP_WIDTH + MINIMAP_FRAME_PADDING * 2;
        const outerHeight = MINIMAP_HEADER_HEIGHT + MINIMAP_FRAME_PADDING * 2 + contentHeight;

        if (!minimapPosition) {
            minimapPosition = {
                x: canvas.width  - outerWidth  - MINIMAP_MARGIN,
                y: canvas.height - outerHeight - MINIMAP_MARGIN
            };
        }

        const maxX = canvas.width  - outerWidth - MINIMAP_MARGIN;
        const maxY = canvas.height - outerHeight - MINIMAP_MARGIN;
        minimapPosition.x = Math.min(Math.max(MINIMAP_MARGIN, minimapPosition.x), Math.max(MINIMAP_MARGIN, maxX));
        minimapPosition.y = Math.min(Math.max(MINIMAP_MARGIN, minimapPosition.y), Math.max(MINIMAP_MARGIN, maxY));

        const outerX = minimapPosition.x;
        const outerY = minimapPosition.y;

        const contentX = outerX + MINIMAP_FRAME_PADDING;
        const contentY = outerY + MINIMAP_HEADER_HEIGHT + MINIMAP_FRAME_PADDING;
        const mapY      = contentY + MINIMAP_INFO_HEIGHT;

        return {
            outerX, outerY, outerWidth, outerHeight,
            contentX, contentY, mapY,
            infoHeight: MINIMAP_INFO_HEIGHT,
            headerY: outerY,
            headerHeight: MINIMAP_HEADER_HEIGHT
        };
    }
    window.getMinimapLayout = getMinimapLayout;
    window.getMinimapHoverState = () => minimapHoverState;

    updateMinimapSize();

    // Distance max pour considérer qu'on est "dans" un portail
    const PORTAL_JUMP_DISTANCE = 400;

    // Rayon de vision
    const VIEW_RADIUS = 1600;
    const VIEW_RADIUS_SQ = VIEW_RADIUS * VIEW_RADIUS;
	
	// Rayon de vision MINIMAP
	const MINIMAP_VIEW_RADIUS = 3500; 
    const MINIMAP_VIEW_RADIUS_SQ = MINIMAP_VIEW_RADIUS * MINIMAP_VIEW_RADIUS;
    
    // Portée max du laser
    const LASER_MAX_RANGE = 700;
    const LASER_MAX_RANGE_SQ = LASER_MAX_RANGE * LASER_MAX_RANGE;

    // Radiation zone
    const RADIATION_MARGIN = 2000;

    // Durées visuelles (ms)
    const LASER_BEAM_DURATION    = 150;
    const ROCKET_BEAM_DURATION   = 700;
    const DAMAGE_BUBBLE_DURATION = 800;
    const EXPLOSION_DURATION     = 700;
    const ISH_DURATION_MS        = 3000;
    const INVINCIBILITY_DURATION_MS = 3000;
    const TARGET_FADE_OVERLAY_ALPHA = 0.45;
    const TARGET_FADE_OVERLAY_RADIUS = 26;

    // Types d'objets (bonus, cargos, etc.)
    const OBJECT_TYPE_META = {
        1:  { label: "CargoBox / SpaceballBox", category: "cargoFree",  kind: "box"  },
        2:  { label: "BonusBox",                category: "bonusBox",   kind: "box"  },
        10: { label: "ShieldBox",               category: "buffBox",    kind: "box"  },
        19: { label: "LifeBox",                 category: "buffBox",    kind: "box"  },
        21: { label: "BootyBox",                category: "bootyBox",   kind: "box"  },
        23: { label: "RedBootyBox",             category: "bootyBox",   kind: "box"  },
        24: { label: "GoldBootyBox",            category: "bootyBox",   kind: "box"  },
        26: { label: "ApocalypseBox",           category: "bootyBox",   kind: "box"  },
        25: { label: "SilverBootyKey",          category: "bootyKey",   kind: "box"  }
    };

    // Visibilité des objets
    const VISIBILITY_SETTINGS = {
        bonusBoxes:      true,
        freeCargo:       true,
        notFreeCargo:    true,
        ore:             true,
        beacons:         true,
        mines:           true,
        others:          true
    };

    // HUD héros
    const HERO_HUD_X = 10;
    const HERO_HUD_Y = 10;
    const HERO_HUD_WIDTH = 260;
    const HERO_HUD_HEIGHT = 110;
    const HERO_REPAIR_BTN_WIDTH = 80;
    const HERO_REPAIR_BTN_HEIGHT = 22;

    // -------------------------------------------------
    // 1. CANVAS & ÉTAT DU JOUEUR
    // -------------------------------------------------
    // --- GROUPE ---
    const groupMembers = {}; // Stocke les membres (id, name, hp, shield...)
    let groupLeaderId = null; // Id du chef de groupe ("nl" côté serveur)
    let pendingGroupInvite = null; // Stocke une invitation en attente (nom du joueur)

    // Ping de groupe (minimap)
    let groupPingMode = false;       // true = prochain clic minimap envoie un ping
    const groupPings  = [];          // liste des pings visibles sur la minimap

	
    const DEFAULT_LOGICAL_WIDTH = cfg.width || 1024;
    const DEFAULT_LOGICAL_HEIGHT = cfg.height || 768;

    let canvas = document.getElementById("gameCanvas");
    if (!canvas) {
        canvas = document.createElement("canvas");
        canvas.id = "gameCanvas";
        canvas.width = DEFAULT_LOGICAL_WIDTH;
        canvas.height = DEFAULT_LOGICAL_HEIGHT;
        document.body.appendChild(canvas);
    }

    let clientResolution = { id: 1, width: DEFAULT_LOGICAL_WIDTH, height: DEFAULT_LOGICAL_HEIGHT };
    let gameScale = BASE_GAME_SCALE;

    const ctx = canvas.getContext("2d");

    function parseClientResolution(raw) {
        if (!raw) return null;
        const parts = String(raw).split(/[|,xX]/).filter(Boolean);
        if (parts.length < 3) return null;
        const id = parseInt(parts[0], 10);
        const width = parseInt(parts[1], 10);
        const height = parseInt(parts[2], 10);
        if (isNaN(width) || isNaN(height)) return null;
        return {
            id: isNaN(id) ? clientResolution.id : id,
            width: width > 0 ? width : clientResolution.width,
            height: height > 0 ? height : clientResolution.height
        };
    }

    function refreshCanvasScale() {
        const targetW = window.innerWidth || canvas.width;
        const targetH = window.innerHeight || canvas.height;
        if (canvas.width !== targetW || canvas.height !== targetH) {
            canvas.width = targetW;
            canvas.height = targetH;
        }

        const logicalW = clientResolution.width || DEFAULT_LOGICAL_WIDTH;
        const logicalH = clientResolution.height || DEFAULT_LOGICAL_HEIGHT;
        const fitScale = Math.min(canvas.width / logicalW, canvas.height / logicalH);
        gameScale = BASE_GAME_SCALE * fitScale;
    }

    function applyClientResolution(raw) {
        const parsed = parseClientResolution(raw);
        if (!parsed) return;
        clientResolution = parsed;
        setting_client_resolution = `${parsed.id}|${parsed.width}|${parsed.height}`;
        refreshCanvasScale();
    }

    refreshCanvasScale();
    window.addEventListener("resize", refreshCanvasScale);

    // Désactiver le menu contextuel
    canvas.addEventListener("contextmenu", (e) => e.preventDefault());

    // Position du vaisseau
    let shipX = 1000;
    let shipY = 1000;
    let heroLastPosX = shipX;
    let heroLastPosY = shipY;
    let heroLastMoveMs = performance.now();

    // Caméra
    let cameraX = shipX;
    let cameraY = shipY;

    // Héros
    let heroId = 0;
    if (window.ANDROMEDA_CONFIG && window.ANDROMEDA_CONFIG.userID) {
        heroId = parseInt(window.ANDROMEDA_CONFIG.userID, 10);
        console.log("[SYSTEM] HeroID forcé via Config PHP : " + heroId);
    } else {
        console.error("[SYSTEM] ERREUR CRITIQUE : ID introuvable !");
    }
	let heroShipId = 0; // ID du modèle de vaisseau (ex: 10 pour Goliath)
    let heroName = "";
    let heroClanId = null;
    let heroClanTag = "";
    let heroGrade = "";
    let heroInvisible = false;
    let heroConfig = 1;
    let currentAmmoId = null;    
    let currentRocketId = null;  
let setting_show_drones = true;
let setting_play_sfx = true;
let setting_play_music = true;
let setting_client_resolution = "ID,W,H"; 
let isChasingTarget = false; // Est-ce qu'on poursuit une cible ?

// Stock de munitions complet (Basé sur les IDs standards Flash / émulateur)
let ammoStock = {
    // Lasers (catalogue Flash : batteryNames[1..6])
    1: 0,  // LCB-10 (x1)
    2: 0,  // MCB-25 (x2)
    3: 0,  // MCB-50 (x3)
    4: 0,  // UCB-100 (x4)
    5: 0,  // SAB-50
    6: 0,  // RSB-75
    101: 0, // CBO-100 (slot spécial combo)
    102: 0, // JOB-100 (lasers aliens)

    // Roquettes (RocketPattern 1..10)
    9: 0,   // R-310
    10: 0,  // PLT-2026
    11: 0,  // PLT-2021
    12: 0,  // PLT-3030
    13: 0,  // PLD-8
    14: 0,  // WIZ-X
    15: 0,  // HSTRM-01
    16: 0,  // UBR-100
    17: 0,  // ECO-10
    18: 0,  // DCR-250
    19: 0,  // SAR-02 (spéciale absorb bouclier)

    // Mines et spéciaux (explosive_names 1..10)
    20: 0, // ACM-01
    21: 0, // EMPM-01
    22: 0, // SABM-01
    23: 0, // DDM-01
    24: 0, // FWX-S
    25: 0, // FWX-M
    26: 0, // FWX-L

    // Charges spéciales / CPU consommables
    30: 0, // EMP-01
    31: 0, // ISH-01
    32: 0  // SMB-01
};

// --- Fonction de mise à jour de l'état (essentielle pour le paquet 7) ---
function updateLocalSetting(key, value) {
    const val = parseInt(value, 10);

    switch (key) {
        case 'SHOW_DRONES':
            setting_show_drones = (val === 1);
            break;
        case 'PLAY_SFX':
            setting_play_sfx = (val === 1);
            break;
        case 'PLAY_MUSIC':
            setting_play_music = (val === 1);
            break;
        case 'CLIENT_RESOLUTION':
            setting_client_resolution = value;
            break;
        default:
            // Laisse les autres paramètres non gérés
            break;
    }
}
    
    // Variable globale pour stocker la faction du joueur (1=MMO, 2=EIC, 3=VRU)
    window.heroFactionId = 0;

    // États temporaires liés aux CPU / tech
    let heroIshActive   = false;
    let heroIshUntil    = 0;
    let heroIshSince    = 0;
    let heroInvincible  = false;
    let heroInvUntil    = 0;
    let heroInvSince    = 0;
    let heroEmpActive   = false;
    let heroCloaked     = false;
    let heroTargetFaded = false;
    
    // Infos CPU (HM7 / Trade Drone, etc.) côté client
    const cpuItems = {
        HM7: {
            hasItem: false,
            amount: 0
        }
    };
	

    // HP / SHD du héros
    let heroHp = null;
	let groupInvitesBlocked = false; // false = on accepte les invitations (par défaut)
    let heroMaxHp = null;
    let heroShield = null;
    let heroMaxShield = null;
    let healthStationActive = false;
    let heroCargo = null;
    let heroMaxCargo = null;
    let heroShieldDamageCount = 0;
    let heroFastRepair = 0;
    
    // Stats Économie
    let heroLevel   = 1;
    let heroXp      = 0;
    let heroHonor   = 0;
    let heroCredits = 0;
    let heroUridium = 0;
	let heroJackpot   = 0;
    let heroBootyKeys = 0;

    // Estimation vitesse héro
    let heroSpeed = (cfg.heroSpeed !== undefined) ? Number(cfg.heroSpeed) || 3000 : 3000;
	
	// Orientation du vaisseau du héros (en radians, 0 = vers la droite)
	let heroAngle = 0;


    // État map
    let mapPvpAllowed  = 1; // 1 = PVP ON
    let mapHomeFaction = 0; // 0 = neutre
    let currentMapId   = cfg.mapID || 0;
    let lastNoAttackZoneTime = 0;
    let lastDemilitarizedState = false;
    let lastTradeZoneState = false;
    let inDemilitarizedZone = false;
    let inTradeZone = false;
    let inJumpZone = false;
    let radiationServerFlag = false;
    let radiationWarningActive = false;
    let radiationFade = 0;
    let radiationPulseStart = 0;
    let radiationWarningTimer = null;
    let radiationFlashAlpha = 0;
    
    // --- Quickbar (barre 1-0 configurable avec cadenas) ---
	
	// --- ÉTAT VISUEL QUICKBAR ---
let quickbarPosition = { x: 0, y: 0 }; // Sera initialisé au premier rendu
let quickbarLayoutMode = 0;          
let isDraggingQuickbar = false;
let quickbarDragOffset = { x: 0, y: 0 };
let quickbarRotateHitbox = null;       // Zone du bouton rotation
let quickbarInitialized = false;       // Pour centrer au premier lancement
let quickbarMinimized = false;     // État réduit ou non
let quickbarMinHitbox = null;      // Zone du bouton réduire (-)
let activeTooltip = null;          // { text: "x1", x: 100, y: 100 } ou null

// Layout courant des 10 slots (valeurs par défaut)
let quickSlots = {
    1:  { type: "ammo",   id: 1 },   // x1
    2:  { type: "ammo",   id: 2 },   // x2
    3:  { type: "ammo",   id: 3 },   // x3
    4:  { type: "ammo",   id: 4 },   // x4
    5:  { type: "tech",   id: 1 },   // T1
    6:  { type: "tech",   id: 2 },   // T2
    7:  { type: "rocket", id: 1 },   // R1
    8:  { type: "rocket", id: 2 },   // R2
    9:  { type: "cpu",    code: "ISH" },
    10: { type: "cpu",    code: "SMB" }
};

// Variable pour stocker l'item en cours de glissement
    let draggedActionItem = null;

// État du cadenas : true = verrouillé (comme dans ton Flash)
let quickbarLocked = true;

// Hitboxes pour gérer les clics souris sur la barre
let quickbarBounds         = null;  // rectangle global
let quickbarLockHitbox     = null;  // icône cadenas
const quickbarSlotHitboxes = {};    // par numéro de slot

// Raccourcis clavier → numéro de slot
let keyBindings = {
    "Digit1": 1, "Digit2": 2, "Digit3": 3, "Digit4": 4, "Digit5": 5,
    "Digit6": 6, "Digit7": 7, "Digit8": 8, "Digit9": 9, "Digit0": 10,
    "F1": 5, "F2": 6, "F5": 9, "F6": 10,
};

// Cooldowns et blacklist d’actions (client-side)
const actionCooldowns = {};        // code d’action -> { duration, endTime }
const actionBlacklist = new Set(); // codes d’actions temporairement interdites
const techCooldowns = {};          // index -> état brute TX

// Presets autorisés pour la configuration rapide
const QUICKBAR_PRESETS = {
    "X1":  { type: "ammo",   id: 1 },
    "X2":  { type: "ammo",   id: 2 },
    "X3":  { type: "ammo",   id: 3 },
    "X4":  { type: "ammo",   id: 4 },
    "R1":  { type: "rocket", id: 1 },
    "R2":  { type: "rocket", id: 2 },
    "T1":  { type: "tech",   id: 1 },
    "T2":  { type: "tech",   id: 2 },
    "ISH": { type: "cpu",    code: "ISH" },
    "SMB": { type: "cpu",    code: "SMB" },
    "VIDE": null
};

// SPRITES DE VAISSEAUX & NPC (shipid -> frames)
const SHIP_SPRITE_DEFS = {
    // ---------- VAISSEAUX JOUEUR ----------
    1:  { frameCount: 32, basePath: "graphics/ships/1/"  }, // Phoenix
    3:  { frameCount: 32, basePath: "graphics/ships/3/"  }, // Leonovo
    4:  { frameCount: 32, basePath: "graphics/ships/4/"  }, // Defcom
    5:  { frameCount: 32, basePath: "graphics/ships/5/"  }, // Liberator
    6:  { frameCount: 32, basePath: "graphics/ships/6/"  }, // Piranha
    7:  { frameCount: 32, basePath: "graphics/ships/7/"  }, // Nostromo
    8:  { frameCount: 32, basePath: "graphics/ships/8/"  }, // Vengeance
    9:  { frameCount: 32, basePath: "graphics/ships/9/"  }, // Bigboy
    10: { frameCount: 32, basePath: "graphics/ships/10/" }, // Goliath
    20: { frameCount: 16, basePath: "graphics/ships/20/" }, // Ovni (admin / modo)
    56: { frameCount: 32, basePath: "graphics/ships/56/" }, // Goliath Enforcer
    58: { frameCount: 32, basePath: "graphics/ships/58/" }, // Vengeance Enforcer
    59: { frameCount: 32, basePath: "graphics/ships/59/" }, // Goliath Bastion

    // ---------- NPC "NORMAUX" ----------
    2:  { frameCount: 32, basePath: "graphics/ships/2/"  }, // Streuner
    71: { frameCount: 32, basePath: "graphics/ships/71/" }, // Lordakia
    72: { frameCount: 32, basePath: "graphics/ships/72/" }, // Devolarium
    73: { frameCount: 32, basePath: "graphics/ships/73/" }, // Mordon
    74: { frameCount: 32, basePath: "graphics/ships/74/" }, // Sibelon
    75: { frameCount: 32, basePath: "graphics/ships/75/" }, // Saimon
    76: { frameCount: 20, basePath: "graphics/ships/76/" }, // Sibelonit
    77: { frameCount: 64, basePath: "graphics/ships/77/" }, // Lordakium
    78: { frameCount: 1,  basePath: "graphics/ships/78/" }, // Kristallin
    79: { frameCount: 1,  basePath: "graphics/ships/79/" }, // Kristallon
    80: { frameCount: 1,  basePath: "graphics/ships/80/" }, // Cubikon
    81: { frameCount: 32, basePath: "graphics/ships/81/" }, // Protegit

    // ---------- NPC "BOSS" (même sprite que le normal) ----------
    34: { frameCount: 32, basePath: "graphics/ships/2/"  }, // Boss Streuner  -> Streuner
    36: { frameCount: 32, basePath: "graphics/ships/71/" }, // Boss Lordakia  -> Lordakia
    37: { frameCount: 32, basePath: "graphics/ships/75/" }, // Boss Saimon    -> Saimon
    46: { frameCount: 32, basePath: "graphics/ships/74/" }, // Boss Sibelon   -> Sibelon
    38: { frameCount: 1,  basePath: "graphics/ships/78/" }, // Boss Kristallin -> Kristallin
    35: { frameCount: 1,  basePath: "graphics/ships/79/" }, // Boss Kristallon -> Kristallon
    39: { frameCount: 1,  basePath: "graphics/ships/80/" }  // Boss Cubikon    -> Cubikon
};

// --- CONFIGURATION DES STATIONS ---
const STATION_SPRITE_DEFS = {
    "blueStation":  { path: "graphics/stations/blueStation/1.png" },
    "greenStation": { path: "graphics/stations/greenStation/1.png" },
    "redStation":   { path: "graphics/stations/redStation/1.png" }
};

// --- SPRITES DE BOUCLIERS ---
const SHIELD_ANIM_FPS = 30;
const SHIELD_SPRITE_DEFS = {
    standard: {
        frameCount: 51,
        basePath: "graphics/shields/shield1/sprites/DefineSprite_2_mc/",
        fps: SHIELD_ANIM_FPS,
        loop: true
    },
    low: {
        frameCount: 33,
        basePath: "graphics/shields/shield0/sprites/DefineSprite_2_mc/",
        fps: SHIELD_ANIM_FPS,
        loop: true
    },
    insta: {
        frameCount: 134,
        basePath: "graphics/shields/instaShield/sprites/DefineSprite_2_mc/",
        fps: SHIELD_ANIM_FPS,
        loop: false
    },
    invincibility: {
        frameCount: 31,
        basePath: "graphics/shields/invincibilityShield/sprites/DefineSprite_2_mc/",
        fps: SHIELD_ANIM_FPS,
        loop: true
    },
    hit: {
        frameCount: 9,
        basePath: "graphics/shields/shieldDamage/sprites/DefineSprite_19_mc/",
        fps: SHIELD_ANIM_FPS,
        loop: false
    }
};

const UI_SPRITES = {
    heroHudBg: "graphics/ui/ui/images/156_bg_standard.png.png",
    heroHudActiveBg: "graphics/ui/ui/images/157_bg_active.png.png",
    heroHpIcon: "graphics/ui/ui/images/65_hp_small.png.png",
    heroHpBar: "graphics/ui/ui/images/66_hp_bar.png.png",
    heroShieldIcon: "graphics/ui/ui/images/16_shipInfoIcon_shield.png",
    heroShieldBar: "graphics/ui/ui/images/15_shield_bar.png.png",
    heroCargoIcon: "graphics/ui/ui/images/102_shipInfoIcon_cargo.png",
    quickbarSlot: "graphics/ui/actionMenu/images/8_slot.png",
    minimapFrame: "graphics/ui/minimap/frames/1.png",
    minimapBg: "graphics/ui/minimap/images/19.png",
    minimapOverlay: "graphics/ui/minimap/sprites/DefineSprite_27_minimapOverlay/1.png",
    minimapGrid: "graphics/ui/minimap/images/20.png",
    windowBg: "graphics/ui/window/images/226.png",
    windowHeader: "graphics/ui/window/images/217.png",
    windowHeaderAlt: "graphics/ui/window/images/219.png",
    windowFooter: "graphics/ui/window/images/126.png",
    windowTopEdge: "graphics/ui/window/images/219.png",
    windowBottomEdge: "graphics/ui/window/images/223.png",
    windowSide: "graphics/ui/window/images/78.png",
    windowCornerTL: "graphics/ui/window/images/201.png",
    windowCornerTR: "graphics/ui/window/images/205.png",
    windowCornerBL: "graphics/ui/window/images/208.png",
    windowCornerBR: "graphics/ui/window/images/212.png",
    windowCornerAltL: "graphics/ui/window/images/203.png",
    windowCornerAltR: "graphics/ui/window/images/214.png",
    windowDivider: "graphics/ui/window/images/130.png",
    buttonClose: "graphics/ui/window/images/101.png",
    buttonCollapse: "graphics/ui/window/images/104.png",
    chatBg: "graphics/ui/window/images/226.png",
    chatInputBg: "graphics/ui/window/images/126.png",
    chatButton: "graphics/ui/window/images/104.png",
    dockBg: "graphics/ui/window/images/126.png",
    dockIconGroup: "graphics/ui/window1/images/20_groupsystem_icon.png.png",
    dockIconChat: "graphics/ui/window1/images/22_chat_icon.png.png",
    quickbarLockIcon: "graphics/ui/window1/images/18_info_icon.png.png",
    quickbarRotateIcon: "graphics/ui/window1/images/16_log_icon.png.png",
    quickbarMinimizeIcon: "graphics/ui/window1/images/19_help_icon.png.png",
    minimapWindowIcon: "graphics/ui/window1/images/14_map_icon.png.png",
    iconAmmo: "graphics/ui/actionMenu/images/42_laser.png.png",
    iconRocket: "graphics/ui/actionMenu/images/20_rocket.png.png",
    iconTech: "graphics/ui/actionMenu/images/3_tech_icon.png.png",
    iconCpu: "graphics/ui/actionMenu/images/73_cpu.png.png",
    iconMine: "graphics/ui/actionMenu/images/19_rocket_probability_maximizer.png.png",
    iconLevel: "graphics/ui/ui/images/57_shipInfoIcon_level.png",
    iconLaser: "graphics/ui/ui/images/58_shipInfoIcon_laser.png",
    iconRocketInfo: "graphics/ui/ui/images/18_shipInfoIcon_rockets.png",
    iconBootyKey: "graphics/ui/ui/images/59_shipInfoIcon_bootykey.png",
    iconJumpVoucher: "graphics/ui/ui/images/61_shipInfoIcon_jumpvoucher.png",
    mainMenuIconQuest: "graphics/ui/window1/images/10_quest_icon.png.png",
    mainMenuIconShip: "graphics/ui/window1/images/11_player_icon.png.png",
    mainMenuIconMap: "graphics/ui/window1/images/14_map_icon.png.png",
    mainMenuIconLog: "graphics/ui/window1/images/16_log_icon.png.png",
    mainMenuIconInfo: "graphics/ui/window1/images/18_info_icon.png.png",
    mainMenuIconHelp: "graphics/ui/window1/images/19_help_icon.png.png",
    mainMenuIconGroup: "graphics/ui/window1/images/20_groupsystem_icon.png.png",
    mainMenuIconChat: "graphics/ui/window1/images/22_chat_icon.png.png",
    chatCornerTL: "graphics/ui/window/images/192.png",
    chatCornerTR: "graphics/ui/window/images/210.png",
    chatCornerBL: "graphics/ui/window/images/208.png",
    chatCornerBR: "graphics/ui/window/images/212.png",
    chatTopEdge: "graphics/ui/window/images/221.png",
    chatBottomEdge: "graphics/ui/window/images/223.png",
    chatSide: "graphics/ui/window/images/78.png",
    chatBgTile: "graphics/ui/window/images/226.png",
    chatHeader: "graphics/ui/window/images/217.png",
    chatFooter: "graphics/ui/window/images/126.png",
    minimapPingBase: "graphics/ui/minimap/sprites/DefineSprite_29_minimapmarker/1.png",
    minimapPortalIcon: "graphics/ui/minimap/images/2_mapIcon_portal.png",
    minimapStationIcon: "graphics/ui/minimap/images/7_mapIcon_station_0.png",
    minimapSpaceballIcon: "graphics/ui/minimap/images/1_mapIcon_spaceball.png",
    minimapFinishIcon: "graphics/ui/minimap/images/8_mapIcon_finish.png",
    minimapAlertIcon: "graphics/ui/minimap/images/9_mapIcon_alert.png",
    radiationHelp: "graphics/ui/ui/sprites/DefineSprite_323_radiationHelp/1.png"
};

const MINIMAP_SPRITE_DEFS = {
    overlay: {
        basePath: "graphics/ui/minimap/sprites/DefineSprite_27_minimapOverlay/",
        frameCount: 1,
        fps: 1,
        loop: false
    },
    groupPing: {
        basePath: "graphics/ui/minimap/sprites/DefineSprite_29_minimapmarker/",
        frameCount: 25,
        fps: 25,
        loop: true
    }
};
const minimapSpriteCache = {};

// Variables pour stocker les stations
let stations = [];       // Liste des stations sur la carte
let stationImages = {};  // Stockage des images chargées



// Cache des images déjà chargées
const shipSpriteCache = {};
const shieldSpriteCache = {};
const uiImageCache = {};

    const QUICKBAR_ICON_LOOKUP = {
        ammo: {
            1: "graphics/ui/actionMenu/images/41_laserBat1.png.png", // LCB-10
            2: "graphics/ui/actionMenu/images/40_laserBat2.png.png", // MCB-25
            3: "graphics/ui/actionMenu/images/39_laserBat3.png.png", // MCB-50
            4: "graphics/ui/actionMenu/images/38_laserBat4.png.png", // UCB-100
            5: "graphics/ui/actionMenu/images/18_sab_m01.png.png", // SAB-50
            6: "graphics/ui/actionMenu/images/37_laserBat5.png.png", // RSB-75
            12: "graphics/ui/actionMenu/images/36_laserBat6.png.png", // CBO-100
            13: "graphics/ui/actionMenu/images/63_explosive.png.png" // JOB-100
        },
        rocket: {
            1: "graphics/ui/actionMenu/images/28_r310.png.png", // R-310
            2: "graphics/ui/actionMenu/images/30_plt2026.png.png", // PLT-2026
            3: "graphics/ui/actionMenu/images/31_plt2021.png.png", // PLT-2021
            4: "graphics/ui/actionMenu/images/29_plt3030.png.png", // PLT-3030
            5: "graphics/ui/actionMenu/images/32_pld8.png.png", // PLD-8
            6: "graphics/ui/actionMenu/images/1_wiz.png.png", // WIZ-X
            7: "graphics/ui/actionMenu/images/113_hstrm01.png.png", // HSTRM-01
            8: "graphics/ui/actionMenu/images/112_ubr100.png.png", // UBR-100
            9: "graphics/ui/actionMenu/images/111_eco10.png.png", // ECO-10
            10: "graphics/ui/actionMenu/images/72_dcr30.png.png", // DCR-250
            11: "graphics/ui/actionMenu/images/63_explosive.png.png" // SAR-02
        },
        mine: {
            1: "graphics/ui/actionMenu/images/103_acm1.png.png", // ACM-1
            2: "graphics/ui/actionMenu/images/67_emp_m01.png.png", // EMPM-01
            3: "graphics/ui/actionMenu/images/18_sab_m01.png.png", // SABM-01
            4: "graphics/ui/actionMenu/images/71_dd_m01.png.png", // DDM-01
            5: "graphics/ui/actionMenu/images/7_smb01.png.png" // SMB-01 (explosif spécial)
        },
        cpu: {
            EMP: "graphics/ui/actionMenu/images/66_emp01.png.png",
            ISH: "graphics/ui/actionMenu/images/46_ish.png.png",
            SMB: "graphics/ui/actionMenu/images/7_smb01.png.png",
        ROB: "graphics/ui/actionMenu/images/92_battle_repair_bot.png.png",
        CLK: "graphics/ui/actionMenu/images/88_cloak01.png.png",
        DRP: "graphics/ui/actionMenu/images/68_droneRepair02.png.png",
        AIM: "graphics/ui/actionMenu/images/100_aim02.png.png",
        ARL: "graphics/ui/actionMenu/images/93_arol01.png.png",
        RLB: "graphics/ui/actionMenu/images/27_rllb1.png.png",
        AMA: "graphics/ui/actionMenu/images/94_ammobuy06.png.png",
        AJU: "graphics/ui/actionMenu/images/44_jump01.png.png"
    },
    tech: {
        1: "graphics/ui/actionMenu/images/15_shield_backup.png.png", // SBU
        2: "graphics/ui/actionMenu/images/92_battle_repair_bot.png.png", // BRB
        3: "graphics/ui/actionMenu/images/64_energy_leech_array.png.png", // ELA
        4: "graphics/ui/actionMenu/images/65_energy_chain_impulse.png.png", // CIP
        5: "graphics/ui/actionMenu/images/6_speed_leech.png.png" // PTT
    },
    ability: {
        aegis_hp: "graphics/ui/actionMenu/images/11_skill_ship_solace.png.png",
        aegis_shd: "graphics/ui/actionMenu/images/12_skill_ship_sentinel.png.png",
        aegis_pod: "graphics/ui/actionMenu/images/10_skill_ship_spectrum.png.png",
        cita_draw: "graphics/ui/actionMenu/images/14_skill_ship_diminisher.png.png",
        cita_prot: "graphics/ui/actionMenu/images/9_skill_ship_venom.png.png",
        cita_fort: "graphics/ui/actionMenu/images/13_skill_ship_lightning.png.png",
        cita_trav: "graphics/ui/actionMenu/images/45_jump01.png.png"
    }
};

function getUiImage(path) {
    if (!path) return null;
    if (uiImageCache[path]) return uiImageCache[path];
    const img = new Image();
    img.src = path;
    uiImageCache[path] = img;
    return img;
}

function getMinimapSpriteFrame(name, frameIndex) {
    const def = MINIMAP_SPRITE_DEFS[name];
    if (!def) return null;
    const idx = ((frameIndex % def.frameCount) + def.frameCount) % def.frameCount;
    const path = def.basePath + (idx + 1) + ".png";
    if (minimapSpriteCache[path]) return minimapSpriteCache[path];
    const img = new Image();
    img.src = path;
    minimapSpriteCache[path] = img;
    return img;
}

function getQuickbarIconPath(item) {
    if (!item) return null;
    const lookup = QUICKBAR_ICON_LOOKUP[item.type];
    if (lookup) {
        if (item.id && lookup[item.id]) return lookup[item.id];
        if (item.code && lookup[item.code]) return lookup[item.code];
        if (item.type === "ability" && lookup[item.id || item.code]) return lookup[item.id || item.code];
    }

    if (item.type === "ammo") return UI_SPRITES.iconAmmo;
    if (item.type === "rocket") return UI_SPRITES.iconRocket;
    if (item.type === "tech") return UI_SPRITES.iconTech;
    if (item.type === "mine") return UI_SPRITES.iconMine || UI_SPRITES.iconCpu;
    if (item.type === "cpu" || item.type === "ability") return UI_SPRITES.iconCpu;
    return null;
}

// Convertit un angle (radians) en index de frame [0..frameCount-1]
function getDirectionFrameIndex(angle, frameCount) {
    if (!isFinite(angle)) angle = 0;
    // Normaliser dans [0, 2π)
    const twoPi = Math.PI * 2;
    angle = ((angle % twoPi) + twoPi) % twoPi;
    const sector = angle / twoPi * frameCount;
    return Math.round(sector) % frameCount;
}

// Récupère (ou charge) l'image d'un vaisseau pour un shipId + frameIndex
function getShipSpriteFrame(shipId, frameIndex) {
    const def = SHIP_SPRITE_DEFS[shipId];
    if (!def) return null;

    const frameCount = def.frameCount;
    let idx = frameIndex % frameCount;
    if (idx < 0) idx += frameCount;

    const key = shipId + "_" + idx;
    if (shipSpriteCache[key]) return shipSpriteCache[key];

    const img = new Image();
    // nos fichiers sont 1.png..32.png, donc on ajoute +1
    const fileNumber = idx + 1;
    img.src = def.basePath + fileNumber + ".png";
    shipSpriteCache[key] = img;
    return img;
}

function getShieldSpriteFrame(name, frameIndex) {
    const def = SHIELD_SPRITE_DEFS[name];
    if (!def) return null;

    const frameCount = def.frameCount;
    let idx = frameIndex % frameCount;
    if (idx < 0) idx += frameCount;

    const key = name + "_" + idx;
    if (shieldSpriteCache[key]) return shieldSpriteCache[key];

    const img = new Image();
    const fileNumber = idx + 1;
    img.src = def.basePath + fileNumber + ".png";
    shieldSpriteCache[key] = img;
    return img;
}
// ---------- GLOWS DE VAISSEAUX (auras autour des ships) ----------
const SHIP_GLOW_DEFS = {
    // Aura du Leonov (shipId 3)
    3: {
        frameCount: 32,                    
        basePath: "graphics/shipGlows/3/"  
    }
    // plus tard on pourra ajouter d'autres glows (goliath, etc.)
};

// --- FONCTION DE CHARGEMENT DES STATIONS ---
function preloadStationSprites() {
    for (let key in STATION_SPRITE_DEFS) {
        let def = STATION_SPRITE_DEFS[key];
        let img = new Image();
        img.src = def.path;
        stationImages[key] = img;
        console.log("Chargement station : " + key);
    }
}
// On lance le chargement tout de suite
preloadStationSprites();

const shipGlowSpriteCache = {};

function getShipGlowFrame(shipId, frameIndex) {
    const def = SHIP_GLOW_DEFS[shipId];
    if (!def) return null;

    const frameCount = def.frameCount;
    let idx = frameIndex % frameCount;
    if (idx < 0) idx += frameCount;

    const key = shipId + "_" + idx;
    if (shipGlowSpriteCache[key]) return shipGlowSpriteCache[key];

    const img = new Image();
    const fileNumber = idx + 1; // fichiers 1.png..N.png
    img.src = def.basePath + fileNumber + ".png";
    shipGlowSpriteCache[key] = img;
    return img;
}



// Sauvegarde / chargement du layout de barre dans le navigateur
function saveQuickbarLayout() {
    try {
        const data = {};
        for (let i = 1; i <= 10; i++) {
            if (quickSlots[i]) data[i] = quickSlots[i];
        }
        localStorage.setItem("andromeda_quickbar", JSON.stringify(data));
    } catch (e) {
        console.warn("Impossible de sauvegarder la quickbar :", e);
    }
}

function loadQuickbarLayout() {
    try {
        const raw = localStorage.getItem("andromeda_quickbar");
        if (!raw) return;
        const data = JSON.parse(raw);
        for (let i = 1; i <= 10; i++) {
            if (data[i]) {
                const it = data[i];
                if (it.type === "ammo" || it.type === "rocket"
                    || it.type === "tech" || it.type === "cpu") {
                    quickSlots[i] = it;
                }
            }
        }
    } catch (e) {
        console.warn("Impossible de charger la quickbar :", e);
    }
}

loadQuickbarLayout();



    function getKeyLabelForSlot(slot) {
        for (const code in keyBindings) {
            if (keyBindings[code] === slot) {
                if (code.startsWith("Digit")) return code.replace("Digit", ""); 
                if (code.startsWith("Key")) return code.replace("Key", "");   
                return code;
            }
        }
        return "";
    }

    // ========================================================
    // QUICKBAR – LOGIQUE AVANCÉE (COOLDOWN + BLACKLIST)
    // ========================================================

    // Mapping des IDs de techs (1,2,...) vers les codes CLD utilisés par le serveur
    const TECH_ID_TO_CODE = {
        1: "SBU", // Shield Backup
        2: "BRB"  // Battle Repair Bot
        // Tu pourras étendre ici si tu ajoutes d'autres techs
    };

    function getActionCodeForSlot(slot) {
        const item = quickSlots[slot];
        if (!item) return null;

        switch (item.type) {
            case "rocket":
                // Tous les slots roquettes se partagent le cooldown "ROK"
                return "ROK";
            case "tech":
                return TECH_ID_TO_CODE[item.id] || null;
            case "cpu":
                // ex: "ISH", "SMB"
                return item.code || null;
            default:
                return null;
        }
    }

    function setActionCooldown(code, seconds) {
        if (!code || !seconds || seconds <= 0 || isNaN(seconds)) return;
        const nowSeconds = performance.now() / 1000;
        actionCooldowns[code] = {
            endTime: nowSeconds + seconds,
            duration: seconds
        };
    }

    function getCooldownInfo(code) {
        const cd = actionCooldowns[code];
        if (!cd) return null;
        const nowSeconds = performance.now() / 1000;
        const remaining = cd.endTime - nowSeconds;
        if (remaining <= 0) {
            delete actionCooldowns[code];
            return null;
        }
        return { remaining, total: cd.duration };
    }

    function updateActionCooldowns() {
        const nowSeconds = performance.now() / 1000;
        for (const code in actionCooldowns) {
            const cd = actionCooldowns[code];
            if (!cd) continue;
            if (nowSeconds >= cd.endTime) {
                delete actionCooldowns[code];
            }
        }
    }

    function isActionOnCooldown(code) {
        return !!getCooldownInfo(code);
    }

    function blacklistAction(code) {
        if (code) actionBlacklist.add(code);
    }

    function unblacklistAction(code) {
        if (code) actionBlacklist.delete(code);
    }

    function isActionBlacklisted(code) {
        return code ? actionBlacklist.has(code) : false;
    }
	
	    // -------------------------------------------------
    // UI HTML POUR SELECTIONNER LES ITEMS DE LA QUICKBAR
    // -------------------------------------------------

    // Rectangles à l'écran pour chaque slot (pour le clic)
    const quickbarSlotRects = {};

    // Catégories disponibles (onglets de la fenêtre)
    const QUICKBAR_CATEGORIES = [
        { id: "laser",   label: "Lasers" },
        { id: "special", label: "Munitions spéciales" },
        { id: "rocket",  label: "Roquettes" },
        { id: "tech",    label: "Techs" },
        { id: "cpu",     label: "CPUs" }
    ];

    // -------------------------------------------------
    // CONFIGURATION COMPLÈTE ET ORDONNÉE DES ITEMS
    // -------------------------------------------------
    const QUICKBAR_ITEMS_BY_CATEGORY = {
        laser: [
            { type: "ammo", id: 1, stockId: 1, label: "LCB-10" },   // x1
            { type: "ammo", id: 2, stockId: 2, label: "MCB-25" },   // x2
            { type: "ammo", id: 3, stockId: 3, label: "MCB-50" },   // x3
            { type: "ammo", id: 4, stockId: 4, label: "UCB-100" },  // x4
            { type: "ammo", id: 5, stockId: 5, label: "SAB-50" },   // SAB
            { type: "ammo", id: 6, stockId: 6, label: "RSB-75" },   // RSB
            { type: "ammo", id: 12, stockId: 101, label: "CBO-100" }, // Combo
            { type: "ammo", id: 13, stockId: 102, label: "JOB-100" }  // Job
        ],
        rocket: [
            { type: "rocket", id: 1, stockId: 9, label: "R-310" },        // RocketPattern.R310
            { type: "rocket", id: 2, stockId: 10, label: "PLT-2026" },    // RocketPattern.PLT_2026
            { type: "rocket", id: 3, stockId: 11, label: "PLT-2021" },    // RocketPattern.PLT_2021
            { type: "rocket", id: 4, stockId: 12, label: "PLT-3030" },    // RocketPattern.PLT_3030
            { type: "rocket", id: 5, stockId: 13, label: "PLD-8" },       // RocketPattern.PLD_8
            { type: "rocket", id: 6, stockId: 14, label: "WIZ-X" },       // RocketPattern.WIZ
            { type: "rocket", id: 7, stockId: 15, label: "HSTRM-01" },    // RocketPattern.HSTRM01
            { type: "rocket", id: 8, stockId: 16, label: "UBR-100" },     // RocketPattern.UBR100
            { type: "rocket", id: 9, stockId: 17, label: "ECO-10" },      // RocketPattern.ECO10
            { type: "rocket", id: 10, stockId: 18, label: "DCR-250" },    // RocketPattern.DCR_250
            { type: "rocket", id: 11, stockId: 19, label: "SAR-02" }      // Mode absorb bouclier
        ],
        special: [ // Mines & Special Ammo
            { type: "mine", id: 1, stockId: 20, label: "ACM-1" },    // Mine contact
            { type: "mine", id: 2, stockId: 21, label: "EMPM-01" },  // Mine EMP
            { type: "mine", id: 3, stockId: 22, label: "SABM-01" },  // Mine SAB
            { type: "mine", id: 4, stockId: 23, label: "DDM-01" },   // Mine Dégât direct
            { type: "cpu", code: "EMP", stockId: 30, label: "EMP-01" },
            { type: "cpu", code: "ISH", stockId: 31, label: "ISH-01" },
            { type: "cpu", code: "SMB", stockId: 32, label: "SMB-01" }
        ],
        cpu: [
            { type: "cpu", code: "ROB", label: "Rep Bot" }, 
            { type: "cpu", code: "CLK", label: "Cloak" },
            { type: "cpu", code: "DRP", label: "Droid Rep" },
            { type: "cpu", code: "AIM", label: "Aim CPU" },
            { type: "cpu", code: "ARL", label: "Auto Rkt" },
            { type: "cpu", code: "RLB", label: "Launcher" },
            { type: "cpu", code: "AMA", label: "Ammo Buy" },
            { type: "cpu", code: "AJU", label: "Jump CPU" }
        ],
        buy_now: [
            { type: "buy", id: "ammo_x1", label: "Buy x1" },
            { type: "buy", id: "ammo_x2", label: "Buy x2" },
            { type: "buy", id: "ammo_x3", label: "Buy x3" },
            { type: "buy", id: "r_plt2026", label: "Buy R1" },
            { type: "buy", id: "r_plt2021", label: "Buy R2" },
            { type: "buy", id: "m_acm", label: "Buy Mine" }
        ],
        tech: [
            { type: "tech", id: 1, code: "SBU", label: "Shield BU" },
            { type: "tech", id: 2, code: "BRB", label: "Battle Bot" },
            { type: "tech", id: 3, code: "ELA", label: "Energy Leech" },
            { type: "tech", id: 4, code: "CIP", label: "Chain Imp" },
            { type: "tech", id: 5, code: "PTT", label: "Precision" }
        ],
        ability: [
            // --- AEGIS (IDs standards: 49, 63, 64...) ---
            { type: "ability", id: "aegis_hp", label: "HP Repair", reqShips: [49, 63, 64, 109, 110] },
            { type: "ability", id: "aegis_shd", label: "Shield Rep", reqShips: [49, 63, 64, 109, 110] },
            { type: "ability", id: "aegis_pod", label: "Repair Pod", reqShips: [49, 63, 64, 109, 110] },
            
            // --- CITADEL (IDs standards: 69, 80...) ---
            { type: "ability", id: "cita_draw", label: "Draw Fire", reqShips: [69, 80, 119] },
            { type: "ability", id: "cita_prot", label: "Protection", reqShips: [69, 80, 119] },
            { type: "ability", id: "cita_fort", label: "Fortify", reqShips: [69, 80, 119] },
            { type: "ability", id: "cita_trav", label: "Travel", reqShips: [69, 80, 119] },
            
            // --- SPEARHEAD (IDs standards: 70, 81...) ---
            { type: "ability", id: "spear_mine", label: "Spy Mine", reqShips: [70, 81, 118] },
            { type: "ability", id: "spear_camo", label: "Ult. Camo", reqShips: [70, 81, 118] },
            { type: "ability", id: "spear_jam", label: "Jammer", reqShips: [70, 81, 118] },
            { type: "ability", id: "spear_mark", label: "Target", reqShips: [70, 81, 118] },
            
            // --- DESIGNS GOLIATH (Solace, Diminisher...) ---
            // Note: Les IDs dépendent de ton serveur (53=Solace, 56=Spectrum...)
            { type: "ability", id: "solace", label: "Nano Clust", reqShips: [53, 140] },
            { type: "ability", id: "diminisher", label: "Weaken Shd", reqShips: [54, 141] },
            { type: "ability", id: "spectrum", label: "Prismatic", reqShips: [56, 142] },
            { type: "ability", id: "sentinel", label: "Fortress", reqShips: [55, 143] },
            { type: "ability", id: "venom", label: "Singularity", reqShips: [57, 144] },
            { type: "ability", id: "lightning", label: "Afterburn", reqShips: [58] } // Vengeance Lightning
        ]
    };

    let quickbarConfigWindowInitialized = false;
    let quickbarConfigCurrentSlot = null;
    let quickbarConfigCurrentCategory = "laser";

    function setQuickbarSlotFromItem(slot, item) {
        if (!item) return;

        if (item.type === "ammo" || item.type === "rocket" || item.type === "tech") {
            quickSlots[slot] = {
                type: item.type,
                id: item.id
            };
        } else if (item.type === "cpu") {
            quickSlots[slot] = {
                type: "cpu",
                code: item.code
            };
        }
    }

    

    
// ========================================================
    // NOUVEAU SYSTÈME : ACTION DRAWER (Barre tiroir sous les slots)
    // ========================================================

    let actionDrawerCategory = "laser"; // Catégorie active par défaut

    function initActionDrawer() {
        const existing = document.getElementById("actionDrawerContainer");
        if (existing) existing.remove();

        const style = document.createElement("style");
        style.innerHTML = `
            #actionDrawerContainer {
                position: absolute;
                top: 450px; left: 50%;
                width: 620px; 
                background: rgba(0, 15, 30, 0.95);
                border: 1px solid #2a4b6c;
                color: #ccc;
                font-family: Arial, sans-serif;
                font-size: 11px;
                z-index: 800;
                display: flex;
                flex-direction: column;
                user-select: none;
                box-shadow: 0 0 10px rgba(0,0,0,0.5);
            }
            
            #actionDrawerContainer.movable { border-color: #00ff00; cursor: move; }
            
            .adHeader {
                height: 16px; background: #000; border-bottom: 1px solid #333;
                display: flex; align-items: center; justify-content: center;
                cursor: grab;
            }
            .adHeader:active { cursor: grabbing; }
            .adTitle { color: #666; font-size: 10px; text-transform: uppercase; letter-spacing: 1px;}

            #adBody { display: flex; flex-direction: column; width: 100%; }

                        /* Onglets */
            #adTabs { 
                display: flex; 
                background: rgba(0,0,0,0.5); 
                border-bottom: 1px solid #2a4b6c; 
            }

            /* Onglet normal = Button_upSkin */
            .adTab { 
                flex: 1; 
                padding: 6px 0; 
                text-align: center; 
                cursor: pointer; 
                border-right: 1px solid #333; 
                color: #888; 
                font-weight: bold; 
                white-space: nowrap; 
                overflow: hidden;

                background-image: url('assets/spirites/DefineSprite_111_Button_upSkin_Button_upSkin/1.png');
                background-repeat: no-repeat;
                background-size: 100% 100%;
            }

            /* Survol = Button_overSkin */
            .adTab:hover { 
                color: #fff; 
                background-image: url('assets/spirites/DefineSprite_101_Button_overSkin_Button_overSkin/1.png');
            }

            /* Onglet actif = Button_selectedUpSkin */
            .adTab.active { 
                color: #00aaff; 
                background-image: url('assets/spirites/DefineSprite_109_Button_selectedUpSkin_Button_selectedUpSkin/1.png');
                border-bottom: 2px solid #00aaff;
            }


            /* Zone Contenu avec Flèches */
            .adContentWrapper {
                display: flex;
                align-items: center;
                background: rgba(0,0,0,0.3);
                height: 55px;
            }

            /* Boutons Flèches */
            .adArrowBtn {
                width: 20px; height: 100%;
                display: flex; align-items: center; justify-content: center;
                background: #111; color: #00aaff;
                cursor: pointer; font-weight: bold;
                border: 1px solid #333;
            }
            .adArrowBtn:hover { background: #222; color: #fff; }

            /* Liste Items Scrollable (Masquée) */
            #adItemsRow { 
                flex: 1;
                display: flex; padding: 5px; 
                overflow-x: auto; /* Scroll géré par boutons mais possible à la souris */
                overflow-y: hidden;
                align-items: center; 
                height: 100%;
                scrollbar-width: none; /* Cacher scrollbar native Firefox */
            }
            #adItemsRow::-webkit-scrollbar { display: none; } /* Cacher scrollbar native Chrome */
            
            .adItemBox {
                width: 44px; height: 44px; 
                background: #1a1a1a; border: 1px solid #444;
                margin-right: 4px; 
                display: flex; flex-direction: column; justify-content: center; align-items: center; 
                cursor: pointer; position: relative;
                flex-shrink: 0;
            }
            .adItemBox:hover { border-color: #fff; background: #2a2a2a; }
            .adItemLabel { font-weight: bold; color: #ddd; font-size: 9px; text-align:center; overflow:hidden; width:100%; }
            .adItemQty { font-size: 9px; color: #aaa; position: absolute; bottom: 1px; right: 1px; }
            .adItemQty.empty { color: #ff4444; }

            /* --- AJOUTS POUR LA SÉLECTION VISUELLE --- */
            
            /* Laser actif (Bordure Blanche) */
            .adItemBox.active-laser {
                border: 2px solid #ffffff !important;
                box-shadow: 0 0 8px #ffffff;
                z-index: 10;
            }
            
            /* Roquette active (Bordure Dorée) */
            .adItemBox.active-rocket {
                border: 2px solid #ffcc00 !important;
                box-shadow: 0 0 8px #ffcc00;
                z-index: 10;
            }
            
            /* Effet d'enfoncement au clic */
            .adItemBox:active {
                transform: scale(0.95);
            }
			/* --- AJOUT FINAL : COOLDOWN DANS LE MENU --- */
            .adCooldownOverlay {
                position: absolute;
                bottom: 0; left: 0;
                width: 100%;
                background: rgba(0, 0, 0, 0.7); /* Fond noir transparent */
                z-index: 5;
                pointer-events: none; /* Laisse passer les clics */
                transition: height 0.1s linear;
            }
            .adCooldownText {
                position: absolute; top: 50%; left: 50%;
                transform: translate(-50%, -50%);
                color: #fff; font-weight: bold; font-size: 10px;
                z-index: 6;
                text-shadow: 1px 1px 0 #000;
            }
        `;
        document.head.appendChild(style);

        const container = document.createElement("div");
        container.id = "actionDrawerContainer";
        container.style.left = (window.innerWidth / 2 - 310) + "px"; 
        
        // Structure HTML avec Flèches
        container.innerHTML = `
            <div class="adHeader" id="adHeader">
                <span class="adTitle">:: MENU ACTIONS ::</span>
            </div>
            <div id="adBody">
                <div id="adTabs"></div>
                <div class="adContentWrapper">
                    <div class="adArrowBtn" id="adScrollLeft">&lt;</div>
                    <div id="adItemsRow"></div>
                    <div class="adArrowBtn" id="adScrollRight">&gt;</div>
                </div>
            </div>
        `;
        document.body.appendChild(container);

        // --- GESTION SCROLL FLÈCHES ---
        const itemsRow = document.getElementById("adItemsRow");
        document.getElementById("adScrollLeft").addEventListener("mousedown", () => {
            itemsRow.scrollBy({ left: -100, behavior: 'smooth' });
        });
        document.getElementById("adScrollRight").addEventListener("mousedown", () => {
            itemsRow.scrollBy({ left: 100, behavior: 'smooth' });
        });

        // --- LOGIQUE DE DÉPLACEMENT ---
        container.addEventListener("mousedown", (e) => e.stopPropagation());
        let isDraggingDrawer = false;
        let drawerOffset = { x: 0, y: 0 };

        container.addEventListener("mousedown", (e) => {
            if (e.target.closest('.adItemBox') || e.target.closest('.adTab') || e.target.closest('.adArrowBtn')) return;

            if (!quickbarLocked) {
                isDraggingDrawer = true;
                drawerOffset.x = e.clientX - container.offsetLeft;
                drawerOffset.y = e.clientY - container.offsetTop;
                container.classList.add("movable");
            }
        });

        window.addEventListener("mousemove", (e) => {
            if (isDraggingDrawer) {
                container.style.left = (e.clientX - drawerOffset.x) + "px";
                container.style.top  = (e.clientY - drawerOffset.y) + "px";
            }
        });

        window.addEventListener("mouseup", () => {
            if (isDraggingDrawer) {
                isDraggingDrawer = false;
                container.classList.remove("movable");
                saveInterfaceLayout();
            }
        });

        // --- GESTION ONGLETS (ORDRE SPÉCIFIQUE) ---
        const tabsContainer = document.getElementById('adTabs');
        
        const categories = [
            { id: "laser", label: "LASERS" },
            { id: "rocket", label: "ROCKETS" },
            { id: "special", label: "SPECIAL" }, 
            { id: "cpu", label: "CPUs" },
            { id: "buy_now", label: "BUY NOW" },
            { id: "tech", label: "TECH" }, 
            { id: "ability", label: "ABILITY" } 
        ];

        let tabsHtml = "";
        categories.forEach(cat => {
            const active = (cat.id === actionDrawerCategory) ? "active" : "";
            tabsHtml += `<div class="adTab ${active}" data-cat="${cat.id}">${cat.label}</div>`;
        });
        tabsContainer.innerHTML = tabsHtml;

        tabsContainer.addEventListener('click', (e) => {
            const tab = e.target.closest('.adTab');
            if(!tab) return;
            document.querySelectorAll('.adTab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            actionDrawerCategory = tab.dataset.cat;
            renderActionDrawerItems();
        });

        renderActionDrawerItems();
    }
	
	function initDragAndDrop() {
        const cvs = document.getElementById("gameCanvas");
        if(!cvs) return;

        // 1. Autoriser le survol (nécessaire pour que le drop fonctionne)
        cvs.addEventListener("dragover", (e) => {
            e.preventDefault(); // Obligatoire pour autoriser le drop
        });

        // 2. Gérer le "Lâcher" (Drop)
        cvs.addEventListener("drop", (e) => {
            e.preventDefault();

            if (!draggedActionItem) return; // Rien n'était glissé
            if (quickbarLocked) return;     // Sécurité double

            // Calcul des coordonnées souris relatives au Canvas
            const rect = cvs.getBoundingClientRect();
            const scaleX = cvs.width / rect.width;
            const scaleY = cvs.height / rect.height;

            const mouseX = (e.clientX - rect.left) * scaleX;
            const mouseY = (e.clientY - rect.top) * scaleY;

            // On cherche si on est tombé sur un slot
            // quickbarSlotRects est rempli dans drawQuickbar()
            let foundSlot = null;

            for (let slot = 1; slot <= 10; slot++) {
                const r = quickbarSlotRects[slot];
                if (r && mouseX >= r.x && mouseX <= r.x + r.w &&
                         mouseY >= r.y && mouseY <= r.y + r.h) {
                    foundSlot = slot;
                    break;
                }
            }

            // Si on a trouvé un slot, on l'assigne !
            if (foundSlot) {
                const item = draggedActionItem;
                
                // Formatage de l'objet pour quickSlots
                // On ne stocke que le nécessaire (type + id ou code)
                if (item.type === "ammo" || item.type === "rocket" || item.type === "tech" || item.type === "mine") {
                    quickSlots[foundSlot] = { type: item.type, id: item.id, label: item.label };
                } else if (item.type === "cpu") {
                    quickSlots[foundSlot] = { type: "cpu", code: item.code, label: item.label };
                }

                addInfoMessage(`Slot ${foundSlot} configuré : ${item.label || item.code}`);
                
                // Sauvegarde locale pour la prochaine session
                saveQuickbarLayout();
            }

            // Reset
            draggedActionItem = null;
        });
    }

    function renderActionDrawerTabs() {
        const tabsContainer = document.getElementById('adTabs');
        if(!tabsContainer) return;

        // Catégories disponibles (basé sur ton objet QUICKBAR_ITEMS_BY_CATEGORY)
        const categories = [
            { id: "laser", label: "Lasers" },
            { id: "rocket", label: "Roquettes" },
            { id: "special", label: "Special Ammo" },
            { id: "cpu", label: "CPUs" },
            { id: "tech", label: "Tech Items" }
            // Tu pourras ajouter 'buy' ou 'ability' plus tard
        ];

        let html = "";
        categories.forEach(cat => {
            const isActive = (cat.id === actionDrawerCategory) ? "active" : "";
            html += `<div class="adTab ${isActive}" data-cat="${cat.id}">${cat.label}</div>`;
        });
        tabsContainer.innerHTML = html;
    }

    function renderActionDrawerItems() {
        const itemsRow = document.getElementById('adItemsRow');
        if(!itemsRow) return;

        const items = QUICKBAR_ITEMS_BY_CATEGORY[actionDrawerCategory] || [];
        itemsRow.innerHTML = "";

        items.forEach((item, index) => {
            // --- 1. Filtre Vaisseau ---
            if (item.reqShips && Array.isArray(item.reqShips)) {
                if (heroShipId !== 0 && !item.reqShips.includes(heroShipId)) return;
            }

            // --- 2. Calcul Quantités ---
            let qtyText = "";
            let qtyClass = "";
            
            if (item.type !== "buy" && item.type !== "ability") {
                let stockId = item.stockId;
                if (!stockId && QUICKBAR_ITEMS_BY_CATEGORY[item.type]) {
                     const found = QUICKBAR_ITEMS_BY_CATEGORY[item.type].find(i => i.id === item.id || i.code === item.code);
                     if(found) stockId = found.stockId;
                }
                if (stockId && ammoStock && ammoStock[stockId] !== undefined) {
                    let qty = ammoStock[stockId];
                    qtyText = (qty > 9999) ? (qty/1000).toFixed(0)+"k" : qty.toString();
                    if (qty <= 0) qtyClass = "empty";
                } else {
                    if (item.type === 'cpu' && !stockId) { qtyText = ""; } 
                    else { qtyText = "0"; qtyClass = "empty"; }
                }
            }

            const label = item.label ? item.label.substring(0, 6) : (item.code || "X");

            // --- 3. Création DOM ---
            const div = document.createElement("div");
            div.className = "adItemBox";
            div.setAttribute("draggable", "true"); 

            // Sélection visuelle (Cadres blanc/or)
            if (item.type === "ammo" && currentAmmoId === item.id) div.classList.add("active-laser");
            else if (item.type === "rocket" && currentRocketId === item.id) div.classList.add("active-rocket");

            // --- 4. GESTION COOLDOWN ---
            // On cherche si cet item a un cooldown actif
            let cdHtml = "";
            // On détermine le code d'action (ex: ISH, ROK, SBU...)
            let code = item.code; 
            if (!code && item.type === "rocket") code = "ROK"; // Les roquettes partagent le code ROK
            if (!code && item.type === "tech") {
                // Mapping Tech ID -> Code (SBU, BRB...)
                const techMap = { 1:"SBU", 2:"BRB", 3:"ELA", 4:"CIP", 5:"PTT" };
                code = techMap[item.id];
            }

            if (code) {
                const cdInfo = getCooldownInfo(code);
                if (cdInfo) {
                    const pct = (cdInfo.remaining / cdInfo.total) * 100;
                    cdHtml = `<div class="adCooldownOverlay" style="height:${pct}%"></div>
                              <div class="adCooldownText">${Math.ceil(cdInfo.remaining)}</div>`;
                }
            }

            div.innerHTML = `
                ${cdHtml}
                <span class="adItemLabel">${label}</span>
                <span class="adItemQty ${qtyClass}">${qtyText}</span>
            `;

            // --- 5. Événements ---
            div.addEventListener("click", (e) => {
                e.stopPropagation(); 
                executeItemActionDirectly(item);
                // Petit délai pour voir la sélection changer
                setTimeout(renderActionDrawerItems, 50);
            });

            div.addEventListener("dragstart", (e) => {
                if (quickbarLocked) {
                    e.preventDefault();
                    addInfoMessage("Déverrouillez pour déplacer.");
                    return;
                }
                draggedActionItem = item; 
                e.dataTransfer.effectAllowed = "copy";
            });

            div.addEventListener("dragend", () => { draggedActionItem = null; });

            itemsRow.appendChild(div);
        });
    }

    // Nouvelle fonction pour exécuter une action sans passer par un slot
    function executeItemActionDirectly(item) {
        if (!item) return;

        if (item.type === "ammo") {
            if (currentAmmoId !== item.id) {
                sendSelectAmmo(item.id);
                currentAmmoId = item.id;
            }
        } 
        else if (item.type === "rocket") {
             if (currentRocketId !== item.id) {
                sendSelectRocket(item.id);
                currentRocketId = item.id;
            }
        } 
        else if (item.type === "tech") {
            sendTechActivation(item.id);
        } 
        else if (item.type === "cpu") {
            sendCpuAction(item.code);
        } 
        else if (item.type === "mine") {
            sendRaw("u|m|" + item.id);
        }
        // --- NOUVEAUX TYPES ---
        else if (item.type === "buy") {
            // Envoi paquet d'achat (Opcode 5 selon l'émulateur)
            // Format: 5|ITEM_ID (ex: 5|o|1 pour ore ?) 
            // NOTE: Il faudra vérifier l'ID exact attendu par ton serveur C# ("5" handler)
            sendRaw("5|buy|" + item.id); 
            addInfoMessage("Achat demandé : " + item.label);
        }
        else if (item.type === "ability") {
            // Envoi paquet aptitude (Opcode à définir côté serveur, souvent "ab" ou "sel")
            sendRaw("ab|" + item.id);
            addInfoMessage("Aptitude : " + item.label);
        }
    }
    

    



    // Messages texte
    const infoMessages = [];
	
	    // -------------------------------------------------
    // SYSTEME DE QUETES / MISSIONS
    // -------------------------------------------------

    // Stockage local des quêtes
    // quests[questId] = { id, category, title, flatConditions: { condId: {...} } }
    const quests = {};
    let privilegedQuestId = null; // quête "sélectionnée"

    function getQuest(questId) {
        questId = Number(questId);
        return quests[questId] || null;
    }

    function setQuest(quest) {
        if (!quest || quest.id == null) return;
        quests[quest.id] = quest;
        if (privilegedQuestId == null) {
            privilegedQuestId = quest.id;
        }
        // Si la fenêtre est ouverte, on la rafraîchit
        renderQuestWindow();
    }

    function deleteQuest(questId) {
        questId = Number(questId);
        if (quests[questId]) {
            delete quests[questId];
        }
        if (privilegedQuestId === questId) {
            const ids = Object.keys(quests).map(x => parseInt(x, 10)).sort((a, b) => a - b);
            privilegedQuestId = ids.length > 0 ? ids[0] : null;
        }
        renderQuestWindow();
    }

    function getQuestStockCount() {
        return Object.keys(quests).length;
    }

    function getNextQuestId() {
        const ids = Object.keys(quests).map(x => parseInt(x, 10)).sort((a, b) => a - b);
        return ids.length > 0 ? ids[0] : -1;
    }

    function privilegeQuestById(questId) {
        questId = Number(questId);
        if (!quests[questId]) return;
        if (privilegedQuestId !== questId) {
            privilegedQuestId = questId;
            renderQuestWindow();
        }
    }

    function isConditionCompleted(cond) {
        if (!cond) return false;
        if (cond.visibility === 2) return true;
        if (cond.target > 0 && cond.current >= cond.target) return true;
        // Si pas de cible, on considère l'état "on" comme critère d'avancement
        return cond.target === 0 && !!cond.runstate;
    }

    function getQuestState(quest) {
        if (!quest) {
            return {
                hasRunning: false,
                readyToTurnIn: false,
                hasMandatory: false,
                hasVisible: false
            };
        }

        let hasRunning = false;
        let readyToTurnIn = true;
        let hasMandatory = false;
        let hasVisible = false;

        for (const cond of Object.values(quest.flatConditions || {})) {
            if (cond.visibility !== 0) {
                hasVisible = true;
            }

            if (cond.runstate) {
                hasRunning = true;
            }

            if (cond.mandatory) {
                hasMandatory = true;
                if (!isConditionCompleted(cond)) {
                    readyToTurnIn = false;
                }
            }
        }

        // Si aucune condition obligatoire n'est définie, on ne force pas l'achèvement
        if (!hasMandatory) {
            readyToTurnIn = false;
        }

        return { hasRunning, readyToTurnIn, hasMandatory, hasVisible };
    }

    // Parse du XML de quête → objet JS
    function parseQuestXmlToQuest(xmlString, category) {
        const quest = {
            id: null,
            category: category || "std",
            title: "",
            flatConditions: {}
        };

        try {
            const parser = new DOMParser();
            const doc = parser.parseFromString(xmlString, "application/xml");
            const root = doc.documentElement; // <case ...>

            if (!root) {
                console.error("[QUEST] XML invalide (pas de root).", xmlString);
                return null;
            }

            const questIdAttr = root.getAttribute("id");
            if (!questIdAttr) {
                console.error("[QUEST] XML sans attribut id sur le root.", xmlString);
                return null;
            }

            quest.id = parseInt(questIdAttr, 10);

            const titleAttr = root.getAttribute("title") || root.getAttribute("name");
            if (titleAttr) {
                quest.title = titleAttr;
            } else {
                quest.title = "Quête " + quest.id;
            }

            const condMap = {};

            function parseConditionsRec(node, parentCondId) {
                for (const child of Array.from(node.children)) {
                    if (child.nodeName === "cond") {
                        const id = parseInt(child.getAttribute("id") || "0", 10);
                        const typeKey = parseInt(child.getAttribute("k") || "0", 10);
                        const modifier = child.getAttribute("m") || "";
                        const current = parseInt(child.getAttribute("cur") || "0", 10);
                        const target = parseInt(child.getAttribute("t") || "0", 10);
                        const runstate = (child.getAttribute("on") || "0") === "1";
                        const mandatory = (child.getAttribute("do") || child.getAttribute("do_") || "0") === "1";
                        const visibility = parseInt(child.getAttribute("viz") || "0", 10);
                        const description = (child.getAttribute("desc") || child.getAttribute("d") || child.textContent || "").trim();

                        if (!condMap[id]) {
                            condMap[id] = {
                                id,
                                typeKey,
                                modifier,
                                current,
                                target,
                                runstate,
                                mandatory,
                                visibility,
                                description,
                                children: []
                            };
                        } else {
                            const c = condMap[id];
                            c.typeKey = typeKey;
                            c.modifier = modifier;
                            c.current = current;
                            c.target = target;
                            c.runstate = runstate;
                            c.mandatory = mandatory;
                            c.visibility = visibility;
                            c.description = description;
                        }

                        if (parentCondId != null && condMap[parentCondId]) {
                            condMap[parentCondId].children.push(id);
                        }

                        parseConditionsRec(child, id);
                    } else if (child.nodeName === "case") {
                        parseConditionsRec(child, parentCondId);
                    } else {
                        parseConditionsRec(child, parentCondId);
                    }
                }
            }

            parseConditionsRec(root, null);

            quest.flatConditions = condMap;
            return quest;

        } catch (e) {
            console.error("[QUEST] Erreur de parse XML :", e, xmlString);
            return null;
        }
    }

    // API "QuestManager" côté client

    function initQuestFromServer(xmlString, category) {
        const q = parseQuestXmlToQuest(xmlString, category);
        if (!q) return;
        setQuest(q);
        addInfoMessage(`Nouvelle quête disponible (#${q.id})`);
    }

    function setQuestAccomplished(questId, param2) {
        const q = getQuest(questId);
        if (!q) return;
        addInfoMessage(`Quête accomplie : ${q.title}`);
        deleteQuest(questId);
    }

    function setQuestFailed(questId) {
        const q = getQuest(questId);
        if (!q) return;
        addInfoMessage(`Quête échouée : ${q.title}`);
        deleteQuest(questId);
    }

    function setQuestCancelled(questId) {
        const q = getQuest(questId);
        if (!q) return;
        addInfoMessage(`Quête annulée : ${q.title}`);
        deleteQuest(questId);
    }

    function updateQuestCondition(questId, condId, current, visibility, runstate) {
        questId = Number(questId);
        condId  = Number(condId);

        const q = getQuest(questId);
        if (!q) {
            console.warn("[QUEST] updateQuestCondition sur une quête inconnue, id=", questId);
            return;
        }

        const cond = q.flatConditions[condId];
        if (!cond) {
            console.warn("[QUEST] Condition inconnue dans questId=", questId, "condId=", condId);
            return;
        }

        cond.current   = current;
        cond.visibility = visibility;
        cond.runstate   = !!runstate;

        if (cond.children && cond.children.length > 0) {
            for (const childId of cond.children) {
                const child = q.flatConditions[childId];
                if (child) {
                    child.runstate   = cond.runstate;
                    child.visibility = cond.visibility;
                }
            }
        }

        if (privilegedQuestId === questId) {
            renderQuestWindow();
        }
    }

    // API pour actions client → serveur
    function sendQuestAccept(questId) {
        sendRaw(`9|acc|${questId}`);
    }

    function sendQuestCancel(questId) {
        sendRaw(`9|can|${questId}`);
    }

    function sendQuestTurnIn(questId) {
        sendRaw(`9|done|${questId}`);
    }