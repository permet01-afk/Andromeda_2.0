// ===================================================================
//  CLIENT HTML5 ANDROMEDA - VERSION FINALE (Fix Cargo/Loot/Explo/Factions)
// ===================================================================

console.log("ANDROMEDA_CONFIG =", window.ANDROMEDA_CONFIG);

(function () {
	
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

    const MINIMAP_WIDTH  = 200;
    const MINIMAP_HEIGHT = 150;
    let minimapZoom = 1;

    // Échelle de la minimap (équivalent du combinedScaleFactor de l'AS3)
    function getMiniMapScale() {
        // Échelle de base : toute la map dans la minimap
        const baseScaleX = MINIMAP_WIDTH  / MAP_WIDTH;
        const baseScaleY = MINIMAP_HEIGHT / MAP_HEIGHT;
        const baseScale  = Math.min(baseScaleX, baseScaleY);

        // Dans le client Flash, combinedScaleFactor = 1 / (zoomFactor * 10)
        // => plus le zoomFactor est grand, plus on "voit large" (zoom OUT).
        // Ici on reproduit la même idée : scale ∝ 1 / minimapZoom.
            return baseScale * minimapZoom;
    }

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

// Stock de munitions complet (Basé sur les IDs serveurs standards)
let ammoStock = {
    // Lasers
    1: 0, // LC-01 / Standard (x1)
    2: 0, // LC-02 (x2)
    3: 0, // LC-03 (x3)
    4: 0, // MCB-50 (x4)
    5: 0, // SAB-50 (Shield Killer)
    6: 0, // RSB-75 (Special)
    // Roquettes Plasma / Hellstorm
    10: 0, // R-310
    11: 0, // PLT-2026
    12: 0, // PLT-2021
    13: 0, // PLT-3030
    14: 0, // PLD-8
    15: 0, // DCR-250
    16: 0, // HSTRM-01
    17: 0, // UBR-100
    18: 0, // ECO-10
    19: 0, // SAR-02

    // Mines et spéciaux
    20: 0, // ACM-1
    21: 0, // EMPM-01
    22: 0, // SABM-01
    23: 0, // DDM-01

    // Charges spéciales / CPU consommables
    30: 0, // EMP
    31: 0, // ISH
    32: 0  // SMB
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
        6: "graphics/ui/actionMenu/images/72_dcr30.png.png", // DCR-250
        10: "graphics/ui/actionMenu/images/113_hstrm01.png.png", // HSTRM-01
        11: "graphics/ui/actionMenu/images/112_ubr100.png.png", // UBR-100
        12: "graphics/ui/actionMenu/images/111_eco10.png.png", // ECO-10
        13: "graphics/ui/actionMenu/images/63_explosive.png.png" // SAR-02
    },
    mine: {
        1: "graphics/ui/actionMenu/images/103_acm1.png.png", // ACM-1
        2: "graphics/ui/actionMenu/images/67_emp_m01.png.png", // EMPM-01
        3: "graphics/ui/actionMenu/images/18_sab_m01.png.png", // SABM-01
        4: "graphics/ui/actionMenu/images/71_dd_m01.png.png" // DDM-01
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
            { type: "ammo", id: 12, stockId: 12, label: "CBO-100" }, // Combo
            { type: "ammo", id: 13, stockId: 13, label: "JOB-100" }  // Job
        ],
        rocket: [
            { type: "rocket", id: 1, stockId: 10, label: "R-310" },      // R1
            { type: "rocket", id: 2, stockId: 11, label: "PLT-2026" },   // R2
            { type: "rocket", id: 3, stockId: 12, label: "PLT-2021" },   // R3
            { type: "rocket", id: 4, stockId: 13, label: "PLT-3030" },   // R4
            { type: "rocket", id: 5, stockId: 14, label: "PLD-8" },      // Plasma
            { type: "rocket", id: 6, stockId: 15, label: "DCR-250" },    // Decelerator
            { type: "rocket", id: 10, stockId: 16, label: "HSTRM-01" },  // Hellstorm
            { type: "rocket", id: 11, stockId: 17, label: "UBR-100" },   // Uber
            { type: "rocket", id: 12, stockId: 18, label: "ECO-10" },    // Eco
            { type: "rocket", id: 13, stockId: 19, label: "SAR-02" }     // Shield Absorb
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


    // Entités
    const entities = {};
    const loggedEntities = new Set();
    const loggedObjectTypes = new Set();

    // Portails
    const portals = {};
    const loggedPortals = new Set();

    // Lasers / dégâts / explosions
    const laserBeams = [];
    const rocketAttacks = [];
    const labPrices = {};
    const damageBubbles = [];
    function pushDamageBubble(entityId, delta, isHealHint = false) {
        if (entityId == null) return;
        const signed = parseInt(delta, 10);
        if (isNaN(signed) || signed === 0) return;
        damageBubbles.push({
            entityId,
            value: Math.abs(signed),
            isHeal: isHealHint || signed > 0,
            createdAt: performance.now()
        });
    }
    const explosions = [];
    const shieldBursts = [];

    // Cible & tir
    let selectedTargetId = null;
    let currentLaserTargetId = null;
    let attackIntentTargetId = null;
    let pendingRangeResumeTargetId = null;
    let pendingRangeResumeMessage = false;
    let rangeProtectedTargetId = null;

    // Gestion CTRL
    let isCtrlDown = false;
    let ctrlHandledThisPress = false;

    // Cible de déplacement
    let moveTargetX = null;
    let moveTargetY = null;
	
	// Suivi souris façon Timer Flash
    let isMouseDownOnMap = false;
    let heroMoveTimerId = null;
    let lastMouseScreenX = 0;
    let lastMouseScreenY = 0;


    // Cible de collecte en attente
    let pendingCollectBoxId = null;

    // Helpers entités / portails
    function ensureEntity(id) {
        if (!entities[id]) {
            entities[id] = {
                id,
                kind: "unknown",
                type: 0,
                category: "unknown",
                x: 0,
                y: 0,
                name: "",
                clanTag: "",
                factionId: 0, // Ajouté pour la couleur
                hp: null,
                shield: null,
                shieldDamageCount: 0,
                ishActive: false,
                ishUntil: 0,
                ishSince: 0,
                invincible: false,
                invUntil: 0,
                invSince: 0,
                drones: [],
                angle: 0,
                interp: {
                    startX: 0,
                    startY: 0,
                    endX: 0,
                    endY: 0,
                    startTime: 0,
                    duration: 0
                }
            };
        }
        return entities[id];
    }

    function ensurePortal(id) {
        if (!portals[id]) {
            portals[id] = {
                id,
                factionId: 0,
                typeId: 0,
                x: 0,
                y: 0,
                visibleOnMiniMap: true,
                targetMaps: []
            };
        }
        return portals[id];
    }

    function resetPendingRangeResume(targetId = null) {
        if (targetId === null || pendingRangeResumeTargetId === targetId) {
            pendingRangeResumeTargetId = null;
            pendingRangeResumeMessage = false;
        }
        if (targetId === null || rangeProtectedTargetId === targetId) {
            rangeProtectedTargetId = null;
        }
    }

    function resolveShieldEffectDuration(effect, baseMs) {
        const spriteKey = (effect === "ISH") ? "insta" : (effect === "INVINCIBILITY" ? "invincibility" : null);
        if (!spriteKey) return baseMs;
        const def = SHIELD_SPRITE_DEFS[spriteKey];
        if (!def) return baseMs;
        const animMs = (def.frameCount / (def.fps || SHIELD_ANIM_FPS)) * 1000;
        return Math.max(baseMs, animMs);
    }

    function setHeroShieldEffect(effect, active, durationMs) {
        const now = performance.now();
        const duration = resolveShieldEffectDuration(effect, durationMs);
        if (effect === "ISH") {
            heroIshActive = !!active;
            heroIshUntil = active ? now + duration : 0;
            heroIshSince = active ? now : 0;
        } else if (effect === "INVINCIBILITY") {
            heroInvincible = !!active;
            heroInvUntil = active ? now + duration : 0;
            heroInvSince = active ? now : 0;
        }
    }

    function setEntityShieldEffect(ent, effect, active, durationMs) {
        if (!ent || ent.kind !== "player") return;
        const now = performance.now();
        const duration = resolveShieldEffectDuration(effect, durationMs);
        if (effect === "ISH") {
            ent.ishActive = !!active;
            ent.ishUntil = active ? now + duration : 0;
            ent.ishSince = active ? now : 0;
        } else if (effect === "INVINCIBILITY") {
            ent.invincible = !!active;
            ent.invUntil = active ? now + duration : 0;
            ent.invSince = active ? now : 0;
        }
    }
	
	function isPointInRect(px, py, rect) {
    return rect &&
           px >= rect.x && px <= rect.x + rect.w &&
           py >= rect.y && py <= rect.y + rect.h;
}

function configureQuickbarSlot(slot) {
    const current = quickSlots[slot];
    let defaultCode = "";

    if (current) {
        if (current.type === "ammo")        defaultCode = "X" + current.id;
        else if (current.type === "rocket") defaultCode = "R" + current.id;
        else if (current.type === "tech")   defaultCode = "T" + current.id;
        else if (current.type === "cpu")    defaultCode = current.code || "";
    }

    const input = window.prompt(
        "Slot " + slot + " – entre un code (X1,X2,X3,X4,R1,R2,T1,T2,ISH,SMB ou VIDE) :",
        defaultCode
    );
    if (!input) return;

    const key = input.trim().toUpperCase();
    const preset = QUICKBAR_PRESETS[key];
    if (typeof preset === "undefined") {
        addInfoMessage("Code invalide : " + key);
        return;
    }

    if (preset === null) {
        quickSlots[slot] = null;
    } else {
        quickSlots[slot] = {
            type: preset.type,
            id:   preset.id,
            code: preset.code
        };
    }

    saveQuickbarLayout();
    addInfoMessage("Slot " + slot + " configuré sur " + key + ".");
}

function handleQuickbarClick(screenX, screenY, e) {
    // Si la quickbar n'est pas définie ou clic en dehors → on laisse le reste gérer
    if (!quickbarBounds || !isPointInRect(screenX, screenY, quickbarBounds)) {
        return false;
    }

    // Cadenas (lock)
    if (quickbarLockHitbox && isPointInRect(screenX, screenY, quickbarLockHitbox)) {
        if (e.button === 0) {
            quickbarLocked = !quickbarLocked;
        }
        return true; // on consomme le clic
    }

    // Clic sur un slot
    for (let slot = 1; slot <= 10; slot++) {
        const rect = quickbarSlotHitboxes[slot];
        if (!rect) continue;
        if (!isPointInRect(screenX, screenY, rect)) continue;

        // On ne gère que le clic gauche pour l'instant
        if (e.button !== 0) return true;

        if (quickbarLocked) {
            // Barre verrouillée : déclenche l'action du slot
            triggerSlot(slot);
        } else {
            // Barre déverrouillée : ouvre la config (popup prompt)
            configureQuickbarSlot(slot);
        }
        return true;
    }

    // Clic ailleurs dans la barre → on consomme quand même
    return true;
}



    // -------------------------------------------------
    // 1.b INPUT SOURIS
    // -------------------------------------------------
    
    function findPortalAtScreenPos(screenX, screenY, radius) {
        let best = null;
        const r = radius || 26;
        let bestDistSq = r * r;

        for (const id in portals) {
            const p = portals[id];
            const screenXPos = mapToScreenX(p.x);
            const screenYPos = mapToScreenY(p.y);
            const dx = screenXPos - screenX;
            const dy = screenYPos - screenY;
            const d2 = dx * dx + dy * dy;
            if (d2 < bestDistSq) {
                bestDistSq = d2;
                best = p;
            }
        }
        return best;
    }

    // --- CORRECTION HITBOX (ZONE DE CLIC) ---
    function findEntityAtScreenPos(screenX, screenY, predicate, radius) {
        let best = null;
        // Dans Flash, la zone de clic est généreuse.
        // On augmente le rayon par défaut (ex: 60px au lieu de 24px) pour faciliter le lock.
        // Si un rayon spécifique est fourni, on l'utilise, sinon on prend 60.
        let maxR = radius || 60; 
        let bestDistSq = maxR * maxR;

        // On parcourt toutes les entités pour trouver la plus proche du clic
        for (const id in entities) {
            const e = entities[id];
            // On vérifie si c'est le bon type (NPC/Joueur/Box) via le prédicat
            if (predicate && !predicate(e)) continue;

            // Conversion des coordonnées Map -> Écran
            const entityScreenX = mapToScreenX(e.x);
            const entityScreenY = mapToScreenY(e.y);

            const dx = entityScreenX - screenX;
            const dy = entityScreenY - screenY;
            const d2 = dx * dx + dy * dy;

            // Si on clique dans la zone, on garde le plus proche
            if (d2 < bestDistSq) {
                bestDistSq = d2;
                best = e;
            }
        }
        return best;
    }

	// =========================================================
    // GESTION CLICS (VERSION FINALE AVEC SAUVEGARDE)
    // =========================================================
  
      // Timer "suivi souris" toutes les 370ms (comme le Timer du SWF)
    function heroFollowMouseTick() {
        if (!isMouseDownOnMap) return;

        const screenX = lastMouseScreenX;
        const screenY = lastMouseScreenY;

        if (!canvas) return;

        // Ne pas déplacer si la souris est sur la minimap
        const margin = 10;
        const miniMapX = canvas.width  - MINIMAP_WIDTH  - margin;
        const miniMapY = canvas.height - MINIMAP_HEIGHT - margin;
        const overMiniMap =
            screenX >= miniMapX && screenX <= miniMapX + MINIMAP_WIDTH &&
            screenY >= miniMapY && screenY <= miniMapY + MINIMAP_HEIGHT;

        if (overMiniMap) return;

        // Conversion écran -> coordonnées carte
        const worldPos = screenToMap(screenX, screenY);
        moveTargetX = worldPos.x;
        moveTargetY = worldPos.y;

        // On arrête la poursuite auto, on garde la cible sélectionnée
        isChasingTarget = false;

        if (typeof sendMoveToServer === "function") {
            sendMoveToServer(moveTargetX, moveTargetY);
        }
    }

  
  
  
  canvas.addEventListener("mousedown", (e) => {
        const rect = canvas.getBoundingClientRect();
        const scaleMouseX = canvas.width  / rect.width;
        const scaleMouseY = canvas.height / rect.height;
        const screenX = (e.clientX - rect.left) * scaleMouseX;
        const screenY = (e.clientY - rect.top) * scaleMouseY;

        // --- 1. INTERCEPTION QUICKBAR ---
        if (quickbarBounds && isPointInRect(screenX, screenY, quickbarBounds)) {
            if (quickbarMinHitbox && isPointInRect(screenX, screenY, quickbarMinHitbox)) {
                quickbarMinimized = !quickbarMinimized; saveInterfaceLayout(); return; 
            }
            if (quickbarLockHitbox && isPointInRect(screenX, screenY, quickbarLockHitbox)) {
                quickbarLocked = !quickbarLocked; addInfoMessage(quickbarLocked ? "Barre verrouillée." : "Barre déverrouillée."); saveInterfaceLayout(); return; 
            }
            if (!quickbarMinimized && !quickbarLocked && quickbarRotateHitbox && isPointInRect(screenX, screenY, quickbarRotateHitbox)) {
                quickbarLayoutMode = (quickbarLayoutMode + 1) % 4;
                quickbarVertical = (quickbarLayoutMode === 1);
                if (quickbarPosition.y + 400 > canvas.height) quickbarPosition.y = Math.max(0, canvas.height - 450);
                if (quickbarPosition.x + 400 > canvas.width) quickbarPosition.x = Math.max(0, canvas.width - 450);
                saveInterfaceLayout(); return; 
            }
            if (!quickbarMinimized) {
                for (let slot = 1; slot <= 10; slot++) {
                    if (quickbarSlotHitboxes[slot] && isPointInRect(screenX, screenY, quickbarSlotHitboxes[slot])) {
                        if (e.button === 2) {
                            if (!quickbarLocked) { quickSlots[slot] = null; saveQuickbarLayout(); addInfoMessage(`Slot ${slot} vidé.`); }
                            return; 
                        }
                        if (e.button === 0) { if (quickbarLocked) triggerSlot(slot); return; }
                    }
                }
            }
            if (e.button === 0 && !quickbarLocked) {
                isDraggingQuickbar = true;
                quickbarDragOffset.x = screenX - quickbarPosition.x;
                quickbarDragOffset.y = screenY - quickbarPosition.y;
                return; 
            }
            return; 
        }

        // --- 2. MINIMAP ---
        const margin = 10;
        const miniMapX = canvas.width  - MINIMAP_WIDTH  - margin;
        const miniMapY = canvas.height - MINIMAP_HEIGHT - margin;

        if (screenX >= miniMapX && screenX <= miniMapX + MINIMAP_WIDTH &&
            screenY >= miniMapY && screenY <= miniMapY + MINIMAP_HEIGHT) {
            const scale = getMiniMapScale ? getMiniMapScale() : (MINIMAP_WIDTH / MAP_WIDTH);
            const realW = MAP_WIDTH * scale;
            const realH = MAP_HEIGHT * scale;
            const offsetX = (MINIMAP_WIDTH  - realW) / 2;
            const offsetY = (MINIMAP_HEIGHT - realH) / 2;
            const clickLocalX = screenX - miniMapX - offsetX;
            const clickLocalY = screenY - miniMapY - offsetY;
            let targetX = clickLocalX / scale;
            let targetY = clickLocalY / scale;
            targetX = Math.max(0, Math.min(MAP_WIDTH,  targetX));
            targetY = Math.max(0, Math.min(MAP_HEIGHT, targetY));

            if (groupPingMode && Object.keys(groupMembers).length > 0) {
                sendGroupPing(targetX, targetY);
                groupPingMode = false;
            } else {
                moveTargetX = targetX;
                moveTargetY = targetY;
                isChasingTarget = false; // Stop poursuite mais GARDE la cible
                sendMoveToServer(targetX, targetY);
            }
            return;
        }

        // --- 3. JEU ---
        
        // Clic Droit : Arrête le laser MAIS garde la cible (selon Flash)
        // Pour désélectionner, il faudrait une autre action, mais le clic droit standard arrête juste le tir.
        if (e.button === 2) {
            if (currentLaserTargetId !== null) sendLaserStop(currentLaserTargetId, true);
            isChasingTarget = false; 
            return;
        }

        if (e.button !== 0) return;
        pendingCollectBoxId = null;
        
        // HUD Repair
        const repairBtnX = HERO_HUD_X + HERO_HUD_WIDTH - HERO_REPAIR_BTN_WIDTH - 10;
        const repairBtnY = HERO_HUD_Y + HERO_HUD_HEIGHT - HERO_REPAIR_BTN_HEIGHT - 8;
        if (screenX >= repairBtnX && screenX <= repairBtnX + HERO_REPAIR_BTN_WIDTH &&
            screenY >= repairBtnY && screenY <= repairBtnY + HERO_REPAIR_BTN_HEIGHT) {
            sendRepairCommand();
            return;
        }

        // A) Clic Entité (Vaisseau/NPC)
        const clickedShip = findEntityAtScreenPos(screenX, screenY, (ent) => ent.kind === "player" || ent.kind === "npc", 60);
        if (clickedShip) {
            // 1. Sélection de la cible
            selectedTargetId = clickedShip.id;
            resetPendingRangeResume();
            sendSelectShip(selectedTargetId);
            
            // --- AJOUT : AUTO-REMPLISSAGE GROUPE ---
            // Si c'est un joueur, on met son nom dans la fenêtre groupe si elle est ouverte
            if (clickedShip.kind === "player" && clickedShip.name) {
                const groupInput = document.getElementById('groupInputName');
                if (groupInput) {
                    groupInput.value = clickedShip.name;
                }
            }
            // ---------------------------------------
            
            if ((e.detail && e.detail >= 2)) { // Double clic = Attaque
                if (currentLaserTargetId !== clickedShip.id) {
                    sendLaserAttack(clickedShip.id);
                    attackIntentTargetId = clickedShip.id;
                    isChasingTarget = true;
                }
            }
            return;
        }

        // B) Clic Portail
        const clickedPortal = findPortalAtScreenPos(screenX, screenY, 30);
        if (clickedPortal) {
            if (Math.hypot(clickedPortal.x - shipX, clickedPortal.y - shipY) <= PORTAL_JUMP_DISTANCE) {
                sendPortalJump();
                // Le saut va déclencher handlePacket_i qui videra la sélection
            } else { 
                moveTargetX = clickedPortal.x; 
                moveTargetY = clickedPortal.y; 
                isChasingTarget = false; 
                sendMoveToServer(moveTargetX, moveTargetY); 
            }
            return;
        }

        // C) Clic Box
        const clickedBox = findEntityAtScreenPos(screenX, screenY, (ent) => ent.kind === "box", 50); // Augmenté à 50 pour faciliter le ramassage
        if (clickedBox) {
            moveTargetX = clickedBox.x; moveTargetY = clickedBox.y; pendingCollectBoxId = clickedBox.id;
            isChasingTarget = false; 
            sendMoveToServer(moveTargetX, moveTargetY);
            return;
        }

               // 	 (Mouvement)
        const worldPos = screenToMap(screenX, screenY);
        moveTargetX = worldPos.x; 
        moveTargetY = worldPos.y;

        // On met à jour l'état "clic enfoncé sur la carte" + position souris
        if (e.button === 0) { // bouton gauche
            isMouseDownOnMap = true;
            lastMouseScreenX = screenX;
            lastMouseScreenY = screenY;

            // Démarrage du timer façon Flash (370 ms)
            if (!heroMoveTimerId) {
                heroMoveTimerId = setInterval(heroFollowMouseTick, 370);
            }
        }
        
        // --- CORRECTION ---
        // On arrête de courir après la cible (isChasingTarget = false)
        // MAIS on NE touche PAS à selectedTargetId. La cible reste verrouillée.
        // On peut donc cliquer pour fuir tout en continuant de tirer si on a la portée.
        isChasingTarget = false; 
        
        sendMoveToServer(moveTargetX, moveTargetY);
    });

	
	

canvas.addEventListener("mousemove", (e) => {
    // Recalcul nécessaire des coordonnées
    const rect = canvas.getBoundingClientRect();
    const scaleMouseX = canvas.width  / rect.width;
    const scaleMouseY = canvas.height / rect.height;
    const screenX = (e.clientX - rect.left) * scaleMouseX;
    const screenY = (e.clientY - rect.top) * scaleMouseY;

    // 1) Déplacement de la Quickbar si on est en drag
    if (isDraggingQuickbar) {
        quickbarPosition.x = screenX - quickbarDragOffset.x;
        quickbarPosition.y = screenY - quickbarDragOffset.y;
    }

    // 2) Tooltips de la Quickbar
    activeTooltip = null; // Reset par défaut
    if (!quickbarMinimized) {
        for (let slot = 1; slot <= 10; slot++) {
            if (quickbarSlotHitboxes[slot] && isPointInRect(screenX, screenY, quickbarSlotHitboxes[slot])) {
                const item = quickSlots[slot];
                if (item) {
                    let label = item.label || (item.code || "Item");
                    activeTooltip = { text: label, x: screenX, y: screenY };
                }
                break;
            }
        }
    }

        // 3) Mise à jour de la dernière position souris + cible locale
    if (isMouseDownOnMap && (e.buttons & 1) === 1) {
        lastMouseScreenX = screenX;
        lastMouseScreenY = screenY;

        // Mise à jour immédiate de la destination côté client (fluide)
        const worldPos = screenToMap(screenX, screenY);
        moveTargetX = worldPos.x;
        moveTargetY = worldPos.y;
        isChasingTarget = false;
        // IMPORTANT : on NE fait PAS sendMoveToServer ici
    }
	
	// --- GESTION DU CURSEUR (STYLE FLASH) ---
        // On vérifie si on survole quelque chose d'interactif
        
        // 1. On cherche une entité (Vaisseau, NPC, Box) avec le même rayon large (60) que pour le clic
        const hoverEntity = findEntityAtScreenPos(screenX, screenY, 
            (ent) => ent.kind === "player" || ent.kind === "npc" || ent.kind === "box", 
            60
        );

        // 2. On cherche un portail
        const hoverPortal = findPortalAtScreenPos(screenX, screenY, 40);

        // 3. Si on survole l'un des deux, on change le curseur
        if (hoverEntity || hoverPortal) {
            canvas.style.cursor = "pointer"; // La petite main (comme dans Flash)
        } else {
            canvas.style.cursor = "default"; // La flèche normale
        }

});


	    // GESTION RELACHEMENT SOURIS (GLOBAL)
    window.addEventListener("mouseup", () => {
        // Si on était en train de bouger la Quickbar, on sauvegarde sa nouvelle place
        if (isDraggingQuickbar) {
            saveInterfaceLayout(); 
        }
        isDraggingQuickbar = false;

        // On stoppe aussi le suivi souris façon Flash
        if (isMouseDownOnMap) {
            isMouseDownOnMap = false;
        }
        if (heroMoveTimerId) {
            clearInterval(heroMoveTimerId);
            heroMoveTimerId = null;
        }
    });


    // -------------------------------------------------
    // 1.c INPUT CLAVIER (CTRL / SPACE)
    // -------------------------------------------------

    function toggleLaserOnSelectedTarget() {
        if (selectedTargetId == null) return;

            if (currentLaserTargetId === selectedTargetId) {
                // Si on tire déjà dessus, on arrête
                sendLaserStop(selectedTargetId, true);
                attackIntentTargetId = null;
                isChasingTarget = false; // On arrête aussi de le suivre
            } else {
                // Si on change de cible ou qu'on commence le tir
                if (currentLaserTargetId !== null && currentLaserTargetId !== selectedTargetId) {
                    sendLaserStop(currentLaserTargetId, true);
                }
            sendLaserAttack(selectedTargetId);
            attackIntentTargetId = selectedTargetId;
            isChasingTarget = true;
        }
    }

// -------------------------------------------------
    // 1.c INPUT CLAVIER (CTRL / SPACE / GROUPE / UI)
    // -------------------------------------------------

    window.addEventListener("keydown", (e) => {
        // --- COMBAT & MOUVEMENT ---

        // CTRL : toggle laser sur la cible
        if (e.key === "Control") {
            if (!isCtrlDown) {
                isCtrlDown = true;
                if (!ctrlHandledThisPress) {
                    ctrlHandledThisPress = true;
                    toggleLaserOnSelectedTarget();
                }
            }
            return;
        }

        // ESPACE : roquette sur cible sélectionnée
        if (e.code === "Space" || e.key === " ") {
            if (selectedTargetId != null) {
                e.preventDefault();
                sendRocketAttack(selectedTargetId);
            }
            return;
        }
        
        // Touche J : Saut de portail
        if (e.key === "j" || e.key === "J") {
            let nearest = null;
            let bestDistSq = PORTAL_JUMP_DISTANCE * PORTAL_JUMP_DISTANCE;

            for (const id in portals) {
                const p = portals[id];
                const dx = p.x - shipX;
                const dy = p.y - shipY;
                const d2 = dx * dx + dy * dy;
                if (d2 < bestDistSq) {
                    bestDistSq = d2;
                    nearest = p;
                }
            }

            if (nearest) {
                console.log("[INPUT] Touche J → JUMP (portal id=" + nearest.id + ")");
                sendPortalJump();
            } else {
                addInfoMessage("Aucun portail à proximité.");
            }
            return;
        }

        // Touche C : Changer de configuration
        if (e.key === "c" || e.key === "C") {
            const nextCfg = (heroConfig === 1 ? 2 : 1);
            sendChangeConfig(nextCfg);
            return;
        }

        // --- INTERFACE (Zoom / Filtres) ---

                // Zoom minimap : + / - / 0
        if (e.key === "+" || e.key === "=") {
            if (minimapZoom < 4) {
                minimapZoom *= 2;
                // On convertit en "scaleFactor" façon DO : 8 = x1
                const serverScale = Math.round(minimapZoom * 8);
                sendSetting('MINIMAP_SCALE', serverScale);
            }
            addInfoMessage("Zoom minimap x" + minimapZoom.toFixed(2));
            return;
        }

        if (e.key === "-" || e.key === "_") {
            if (minimapZoom > 0.5) {
                minimapZoom /= 2;
                const serverScale = Math.round(minimapZoom * 8);
                sendSetting('MINIMAP_SCALE', serverScale);
            }
            addInfoMessage("Zoom minimap x" + minimapZoom.toFixed(2));
            return;
        }

        if (e.key === "0") {
            minimapZoom = 1;
            const serverScale = 8; // valeur "normale" côté serveur / FULL_MERGE_AS
            sendSetting('MINIMAP_SCALE', serverScale);
            addInfoMessage("Zoom minimap réinitialisé");
            return;
        }

        
        // Filtres d'affichage des box : B / F / N
        if (e.key === "b" || e.key === "B") {
            VISIBILITY_SETTINGS.bonusBoxes = !VISIBILITY_SETTINGS.bonusBoxes;
            addInfoMessage("Bonus box : " + (VISIBILITY_SETTINGS.bonusBoxes ? "ON" : "OFF"));
            return;
        }

        if (e.key === "f" || e.key === "F") {
            VISIBILITY_SETTINGS.freeCargo = !VISIBILITY_SETTINGS.freeCargo;
            addInfoMessage("Cargo gratuit : " + (VISIBILITY_SETTINGS.freeCargo ? "ON" : "OFF"));
            return;
        }

        if (e.key === "n" || e.key === "N") {
            VISIBILITY_SETTINGS.notFreeCargo = !VISIBILITY_SETTINGS.notFreeCargo;
            addInfoMessage("Cargo payant : " + (VISIBILITY_SETTINGS.notFreeCargo ? "ON" : "OFF"));
            return;
        }
		
		// --- INTERFACE QUETES ---
        // Touche Q : ouvrir/fermer la fenêtre de quêtes
        if (e.key === "q" || e.key === "Q") {
            toggleQuestWindow();
            return;
        }
		
		        // --- INTERFACE PARAMETRES ---
        // Touche O : ouvrir/fermer la fenêtre de paramètres
        if (e.key === "o" || e.key === "O") {
            toggleSettingsWindow();
            return;
        }



        // --- COMMANDES DE GROUPE (NOUVEAU) ---

// Touche 'I' : Inviter au groupe
        if (e.key === "i" || e.key === "I") {
            // 1. Vérifier qu'on a une cible
            if (!selectedTargetId) {
                addInfoMessage("Aucune cible sélectionnée.");
                return;
            }

            // 2. Récupérer l'entité
            const target = entities[selectedTargetId];

            // 3. Vérifications de sécurité (Debug)
            if (!target) {
                console.error("[ERREUR GROUPE] L'entité cible n'existe pas en mémoire.");
                return;
            }
            
            if (target.kind !== "player") {
                addInfoMessage("Vous ne pouvez inviter que des joueurs.");
                return;
            }

            // 4. Récupération du NOM (Le cœur du problème)
            // On s'assure que le nom existe, sinon on met un message d'erreur
            const targetName = target.name;

            if (!targetName || targetName === "" || targetName === "undefined") {
                console.error("[ERREUR GROUPE] Nom de la cible invalide :", target);
                addInfoMessage("Erreur : Impossible de lire le nom du joueur.");
                return;
            }

            // 5. Envoi propre
            console.log(`[GROUPE] Envoi invitation vers : ${targetName} (ID: ${selectedTargetId})`);
            sendRaw(`ps|inv|name|${targetName}`); 
            addInfoMessage(`Invitation envoyée à ${targetName}`);
            return;
        }

        // Touche 'Entrée' : Accepter
        if (e.key === "Enter") {
            if (pendingGroupInvite) {
                // CORRECTION : On ajoute "ps|" au début
                sendRaw(`ps|inv|ack|${pendingGroupInvite.id}`);
                addInfoMessage(`Vous avez rejoint le groupe de ${pendingGroupInvite.name}`);
                pendingGroupInvite = null;
            }
            return;
        }

        // Touche 'L' : Quitter
        if (e.key === "l" || e.key === "L") {
             // CORRECTION : On ajoute "ps|" au début
             sendRaw("ps|lv");
             addInfoMessage("Demande de départ du groupe...");
             return;
        }
		
		        // Touche P : Mode ping de groupe (clic sur la minimap)
        if (e.key === "p" || e.key === "P") {
            groupPingMode = !groupPingMode;
            if (groupPingMode) {
                addInfoMessage("Mode ping de groupe ACTIVÉ : cliquez sur la minimap.");
            } else {
                addInfoMessage("Mode ping de groupe désactivé.");
            }
            return;
        }


        // --- BARRE DE RACCOURCIS (1-0, F1-F6) ---
        if (keyBindings[e.code]) {
            triggerSlot(keyBindings[e.code]);
            return;
        }
    });

    window.addEventListener("keyup", (e) => {
        if (e.key === "Control") {
            isCtrlDown = false;
            ctrlHandledThisPress = false;
        }
    });

    // -------------------------------------------------
    // UTILITAIRES D'AFFICHAGE
    // -------------------------------------------------

    function addInfoMessage(text) {
        if (!text) return;
        
        // 1. Affichage "Flottant" sur le Canvas (Texte vert actuel)
        infoMessages.unshift(text);
        if (infoMessages.length > 5) infoMessages.pop();

        // 2. Ajout dans la fenêtre JOURNAL (Nouvelle fonctionnalité)
        // On vérifie si la fonction existe avant de l'appeler
        if (typeof addLogEntry === "function") {
            addLogEntry(text);
        }
    }

    // --- CORRECTION DE L'AFFICHAGE (SCALE FIXE COMME FLASH) ---

    function mapToScreenX(x) {
        // On n'utilise plus (canvas.width / MAP_WIDTH) car cela écrase la map.
        // On utilise un zoom logique basé sur la résolution et le scale Flash.
        const dx = x - cameraX;
        return canvas.width / 2 + dx * gameScale;
    }

    function mapToScreenY(y) {
        const dy = y - cameraY;
        return canvas.height / 2 + dy * gameScale;
    }

    function screenToMap(screenX, screenY) {
        const dx = screenX - canvas.width / 2;
        const dy = screenY - canvas.height / 2;

        // On divise par gameScale pour retrouver la vraie distance
        const worldX = cameraX + dx / gameScale;
        const worldY = cameraY + dy / gameScale;

        return { x: worldX, y: worldY };
    }

    // -------------------------------------------------
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

    function connectToChat() {
        const url = `ws://${cfg.host}:${cfg.port}`;
        console.log("[CHAT-WS] Connexion au canal Chat/Groupe...");

        chatWs = new WebSocket(url);

        chatWs.onopen = () => {
            console.log("[CHAT-WS] Connecté ! Attente avant init...");

            ensureDefaultChatRooms();
            renderChatTabs();
            
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
                    renderChatTabs();
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
        addInfoMessage("Groupe dissous.");
        drawGroupWindow();
    }
    else if (action === "nl") { // New Leader
        const leaderId = parseInt(parts[3], 10); // 0|ps|nl|ID
        if (groupMembers[leaderId]) {
            addInfoMessage(groupMembers[leaderId].name + " est le chef.");
        }
    }
}

// Info Cible (HP/Shield précis)
function handlePacket_N(parts, i) {
    // 0|N|id|nom|sh|maxSh|hp|maxHp
    const id = parseInt(parts[i], 10);
    if (id === selectedTargetId) {
        // On peut stocker les infos précises ici si on veut les afficher
        // Pour l'instant, on fait juste en sorte que ça ne plante pas
    }
}

// Gestion du changement de carte (Jump)
    function resetMapState(newMapId) {
        if (!isNaN(newMapId)) {
            currentMapId = newMapId;
            cfg.mapID = newMapId;
        }

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
	    function parseDrones(droneStr) {
        const result = [];
        if (!droneStr || typeof droneStr !== "string") return result;

        const trimmed = droneStr.trim();
        if (!trimmed) return result;

        // Format typique envoyé par l'émulateur :
        // "3/2-15-15,3/4-15-15-15-15,3/2-15-15"
        // => plusieurs drones séparés par des virgules
        const entries = trimmed.split(',');
        for (let rawEntry of entries) {
            rawEntry = rawEntry.trim();
            if (!rawEntry) continue;

            const slashParts = rawEntry.split('/');
            const typeId = parseInt(slashParts[0], 10);
            let level = null;
            let upgrades = [];

            if (slashParts.length >= 2) {
                // Exemple : "2-15-15-15" => niveau 2 + upgrades [15,15,15]
                const lvlAndUpgrades = slashParts[1]
                    .split('-')
                    .map(x => x.trim())
                    .filter(x => x !== "");

                if (lvlAndUpgrades.length > 0) {
                    const lvl = parseInt(lvlAndUpgrades[0], 10);
                    if (!Number.isNaN(lvl)) level = lvl;
                    if (lvlAndUpgrades.length > 1) {
                        upgrades = lvlAndUpgrades.slice(1);
                    }
                }
            }

            result.push({
                type: Number.isNaN(typeId) ? null : typeId,
                level: level,
                upgrades: upgrades,
                raw: rawEntry
            });
        }

        return result;
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
            const shStr     = parts[i + 1] || "0";
            const maxShStr  = parts[i + 2] || "0";
            const newShield = parseInt(shStr, 10);
            const newMaxSh  = parseInt(maxShStr, 10);
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

        const beamAngle = computeShieldImpactAngle(attackerId, targetId);
        const targetSnap = snapshotEntityById(targetId);

        laserBeams.push({
            attackerId,
            targetId,
            patternId,
            showShieldDamage,
            skilledLaser,
            angle: beamAngle,
            createdAt: performance.now()
        });

        if (showShieldDamage && targetSnap && targetSnap.kind === "player" && beamAngle != null) {
            const radius = computeShieldImpactRadius(targetSnap);
            setTimeout(() => {
                const current = snapshotEntityById(targetId);
                if (!current || current.kind !== "player") return;
                const hasShield = (current.maxShield && current.maxShield > 0) || (current.shield && current.shield > 0);
                if (!hasShield) return;
                spawnShieldBurstAt(current.x, current.y, "hit", { angle: beamAngle, radius, targetId });
            }, LASER_BEAM_DURATION);
        }
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
             const isMyCollection = (pendingCollectBoxId === id);

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
                pendingCollectBoxId = null;
                moveTargetX = null; 
                moveTargetY = null;
                isChasingTarget = false;
            }

            delete entities[id];
            if (loggedEntities.has(id)) loggedEntities.delete(id);
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
            const isMyCollection = (pendingCollectBoxId === id);

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
                pendingCollectBoxId = null;
                moveTargetX = null; 
                moveTargetY = null;
                isChasingTarget = false;
            }

            // Suppression définitive de l'entité en mémoire
            delete entities[id];
            
            // Nettoyage des logs de debug (optionnel)
            if (loggedEntities.has(id)) loggedEntities.delete(id);
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
            // --- CORRECTION CRITIQUE ICI ---
            // Si l'entité est devenue une BOÎTE (packet 'c' arrivé avant 'K')
            // et qu'elle est "fraîche" (moins de 2s), ON NE LA SUPPRIME PAS !
            if (e.kind === "box") {
                 if (e.boxSpawnTime && (Date.now() - e.boxSpawnTime < 2000)) {
                     // On arrête juste le tir, mais on laisse l'objet
                     forceUnlock(id);
                     return; 
                 }
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
                // On le convertit en facteur de zoom local.
                if (!isNaN(val) && val > 0) {
                    // 8 = zoom normal → minimapZoom = 1
                    let normalized = val / 8;
                    // On clamp pour éviter les valeurs absurdes en BDD
                    normalized = Math.max(0.25, Math.min(4, normalized));
                    minimapZoom = normalized;
                    console.log("[SETTINGS] MINIMAP_SCALE reçu → minimapZoom =", minimapZoom);
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

    function sendLaserStop(targetId, force = false) {
        if (!ws || ws.readyState !== WebSocket.OPEN) return;
        if (targetId == null) return;
        if (!force && rangeProtectedTargetId === targetId) return;
        const packet = `G|${targetId}`;
        console.log("[WS] Envoi LASER_STOP →", packet);
        if (currentLaserTargetId === targetId) currentLaserTargetId = null;
        if (attackIntentTargetId === targetId) attackIntentTargetId = null;
        resetPendingRangeResume(targetId);
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

        if (currentLaserTargetId == null) return;

        let tx, ty;
        if (heroId !== null && currentLaserTargetId === heroId) {
            tx = shipX;
            ty = shipY;
        } else if (entities[currentLaserTargetId]) {
            tx = entities[currentLaserTargetId].x;
            ty = entities[currentLaserTargetId].y;
        } else {
            currentLaserTargetId = null;
            return;
        }

        // La portée est validée côté serveur ; on laisse l'attaque active
        // tant que la cible existe et que le serveur ne la stoppe pas.
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
	
	function drawGroupWindow() {
    // On récupère la liste des ID
    const memberIds = Object.keys(groupMembers);
    if (memberIds.length === 0) return; // Pas de groupe, on n'affiche rien

    const boxWidth = 160;
    const memberHeight = 50; // Hauteur par membre
    const totalHeight = memberIds.length * memberHeight + 25; // +25 pour le titre
    
    const x = 10; // À gauche
    const y = 150; // En dessous du HUD Héros

    ctx.save();

    // Fond
    ctx.fillStyle = "rgba(0, 0, 0, 0.6)";
    ctx.fillRect(x, y, boxWidth, totalHeight);
    ctx.strokeStyle = "#00ff00"; // Bordure verte pour le groupe
    ctx.lineWidth = 1;
    ctx.strokeRect(x + 0.5, y + 0.5, boxWidth - 1, totalHeight - 1);

    // Titre
    ctx.fillStyle = "#ffffff";
    ctx.font = "12px Consolas, monospace";
    ctx.textAlign = "center";
    ctx.fillText(`GROUPE (${memberIds.length})`, x + boxWidth / 2, y + 14);

    // Membres
    let currentY = y + 25;
    
    for (const id of memberIds) {
        const m = groupMembers[id];
        
        // Nom + Map
        ctx.textAlign = "left";
        ctx.fillStyle = "#ffffff";
        ctx.font = "11px Consolas, monospace";
        
        let mapTxt = (m.mapId === cfg.mapID) ? "(Ici)" : `(Map ${m.mapId})`;
        if (m.mapId === 0) mapTxt = "(??)";
        
        // Raccourcir le nom si trop long
        let nameDisplay = m.name.length > 10 ? m.name.substring(0, 10) + ".." : m.name;
        ctx.fillText(`${nameDisplay} ${mapTxt}`, x + 5, currentY + 10);

        // Barre HP
        const barW = boxWidth - 10;
        const hpRatio = (m.maxHp > 0) ? Math.max(0, Math.min(1, m.hp / m.maxHp)) : 0;
        
        ctx.fillStyle = "#333";
        ctx.fillRect(x + 5, currentY + 16, barW, 6);
        ctx.fillStyle = "#00ff00";
        ctx.fillRect(x + 5, currentY + 16, barW * hpRatio, 6);

        // Barre SHD
        const shRatio = (m.maxShield > 0) ? Math.max(0, Math.min(1, m.shield / m.maxShield)) : 0;
        ctx.fillStyle = "#333";
        ctx.fillRect(x + 5, currentY + 24, barW, 4);
        ctx.fillStyle = "#00bfff";
        ctx.fillRect(x + 5, currentY + 24, barW * shRatio, 4);
        
        currentY += memberHeight;
    }

    ctx.restore();
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

    function drawMiniMap() {
        const margin = 10;
        const x = canvas.width  - MINIMAP_WIDTH  - margin;
        const y = canvas.height - MINIMAP_HEIGHT - margin;

        // 1. FOND ET CADRE
        const frameImg = getUiImage(UI_SPRITES.minimapFrame);
        if (frameImg && frameImg.complete && frameImg.width > 0 && frameImg.height > 0) {
            const pad = 6;
            ctx.drawImage(frameImg, x - pad, y - pad, MINIMAP_WIDTH + pad * 2, MINIMAP_HEIGHT + pad * 2);
        }

        const mmBg = getUiImage(UI_SPRITES.minimapBg);
        if (mmBg && mmBg.complete && mmBg.width > 0 && mmBg.height > 0) {
            ctx.drawImage(mmBg, x, y, MINIMAP_WIDTH, MINIMAP_HEIGHT);
        } else {
            ctx.save();
            ctx.globalAlpha = 0.85;
            ctx.fillStyle = "rgba(0, 0, 0, 0.9)";
            ctx.fillRect(x, y, MINIMAP_WIDTH, MINIMAP_HEIGHT);
            ctx.restore();
        }
        const mmGrid = getUiImage(UI_SPRITES.minimapGrid);
        if (mmGrid && mmGrid.complete && mmGrid.width > 0 && mmGrid.height > 0) {
            ctx.drawImage(mmGrid, x, y, MINIMAP_WIDTH, MINIMAP_HEIGHT);
        }
        const mmOverlay = getMinimapSpriteFrame("overlay", 0);
        if (mmOverlay && mmOverlay.complete && mmOverlay.width > 0 && mmOverlay.height > 0) {
            ctx.drawImage(mmOverlay, x, y, MINIMAP_WIDTH, MINIMAP_HEIGHT);
        }
        ctx.strokeStyle = "#4a6b8c";
        ctx.lineWidth = 1;
        ctx.strokeRect(x + 0.5, y + 0.5, MINIMAP_WIDTH - 1, MINIMAP_HEIGHT - 1);

        // Calculs d'échelle
        const scale = getMiniMapScale ? getMiniMapScale() : (MINIMAP_WIDTH / MAP_WIDTH);
        const realW = MAP_WIDTH * scale;
        const realH = MAP_HEIGHT * scale;
        const offsetX = (MINIMAP_WIDTH  - realW) / 2;
        const offsetY = (MINIMAP_HEIGHT - realH) / 2;

        const toMiniX = (wx) => x + offsetX + (wx * scale);
        const toMiniY = (wy) => y + offsetY + (wy * scale);

        // 2. VISEUR (Croix sur la position joueur)
        const px = toMiniX(shipX);
        const py = toMiniY(shipY);
        
        if (px >= x && px <= x + MINIMAP_WIDTH && py >= y && py <= y + MINIMAP_HEIGHT) {
            ctx.save();
            ctx.strokeStyle = "rgba(255, 255, 255, 0.2)";
            ctx.lineWidth = 1;
            ctx.beginPath(); ctx.moveTo(x, py); ctx.lineTo(x + MINIMAP_WIDTH, py); ctx.stroke();
            ctx.beginPath(); ctx.moveTo(px, y); ctx.lineTo(px, y + MINIMAP_HEIGHT); ctx.stroke();
            ctx.restore();
        }

        // --- 3. PORTAILS & BASES (TOUJOURS VISIBLES) ---
        const portalIcon = getUiImage(UI_SPRITES.minimapPortalIcon);
        for (const pid in portals) {
            const p = portals[pid];
            if (p.visibleOnMiniMap === false) continue;

            const mx = toMiniX(p.x);
            const my = toMiniY(p.y);

            if (mx >= x && mx <= x + MINIMAP_WIDTH && my >= y && my <= y + MINIMAP_HEIGHT) {
                if (portalIcon && portalIcon.complete && portalIcon.width > 0) {
                    const pw = portalIcon.width;
                    const ph = portalIcon.height;
                    ctx.drawImage(portalIcon, mx - pw / 2, my - ph / 2, pw, ph);
                } else {
                    ctx.strokeStyle = "#00ffff"; // Saut
                    ctx.lineWidth = 1.5;
                    ctx.beginPath();
                    ctx.arc(mx, my, 3, 0, Math.PI * 2);
                    ctx.stroke();
                }
            }
        }

        const stationIcon = getUiImage(UI_SPRITES.minimapStationIcon);
        if (stationIcon && stationIcon.complete && stationIcon.width > 0) {
            for (const s of stations) {
                const mx = toMiniX(s.x);
                const my = toMiniY(s.y);
                if (mx >= x && mx <= x + MINIMAP_WIDTH && my >= y && my <= y + MINIMAP_HEIGHT) {
                    ctx.drawImage(stationIcon, mx - stationIcon.width / 2, my - stationIcon.height / 2, stationIcon.width, stationIcon.height);
                }
            }
        }

        // --- 4. ENTITÉS (AVEC CORRECTION LOCK) ---
        for (const id in entities) {
            const e = entities[id];
            if (!isEntityVisibleOnMap(e)) continue;

            const isGroupMember = (groupMembers[e.id] !== undefined);
            // CORRECTION : La cible verrouillée est TOUJOURS visible
            const isLockedTarget = (selectedTargetId !== null && e.id == selectedTargetId);

            // Filtre Radar : On cache si loin, SAUF si Groupe OU Cible
            if (!isGroupMember && !isLockedTarget) {
                const dx = e.x - shipX;
                const dy = e.y - shipY;
                if (dx*dx + dy*dy > MINIMAP_VIEW_RADIUS_SQ) continue;
            }

            const mx = toMiniX(e.x);
            const my = toMiniY(e.y);
            
            if (mx >= x && mx <= x + MINIMAP_WIDTH && my >= y && my <= y + MINIMAP_HEIGHT) {
                const nameLower = (e.name || "").toLowerCase();
                const isSpaceball = nameLower.includes("spaceball");

                if (isSpaceball) {
                    const sbIcon = getUiImage(UI_SPRITES.minimapSpaceballIcon);
                    if (sbIcon && sbIcon.complete && sbIcon.width > 0) {
                        ctx.drawImage(sbIcon, mx - sbIcon.width / 2, my - sbIcon.height / 2, sbIcon.width, sbIcon.height);
                        continue;
                    }
                }

                ctx.fillStyle = getEntityColor(e);
                let size = isGroupMember ? 4 : 2;

                if (isLockedTarget) {
                    size = 4;
                    ctx.fillStyle = "#ff0000"; // Force rouge
                    ctx.save();
                    ctx.strokeStyle = "#ff0000";
                    ctx.lineWidth = 1;
                    ctx.strokeRect(mx - 4, my - 4, 8, 8);
                    ctx.restore();
                }

                ctx.fillRect(mx - size/2, my - size/2, size, size);
            }
        }

        // 4b. Point de destination (clic minimap / mouvement manuel)
        if (moveTargetX !== null && moveTargetY !== null) {
            const tx = toMiniX(moveTargetX);
            const ty = toMiniY(moveTargetY);
            if (tx >= x && tx <= x + MINIMAP_WIDTH && ty >= y && ty <= y + MINIMAP_HEIGHT) {
                const finishIcon = getUiImage(UI_SPRITES.minimapFinishIcon);
                if (finishIcon && finishIcon.complete && finishIcon.width > 0) {
                    ctx.drawImage(finishIcon, tx - finishIcon.width / 2, ty - finishIcon.height / 2, finishIcon.width, finishIcon.height);
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

        // 5. Cadre de vue
        const viewW = canvas.width * scale;
        const viewH = canvas.height * scale;
        const miniViewX = px - viewW/2;
        const miniViewY = py - viewH/2;
        ctx.strokeStyle = "rgba(255, 255, 255, 0.4)";
        ctx.lineWidth = 1;
        ctx.strokeRect(miniViewX, miniViewY, viewW, viewH);

        // 6. Pings Groupe
        const nowMs = performance.now();
        for (let idx = groupPings.length - 1; idx >= 0; idx--) {
            const ping = groupPings[idx];
            if (nowMs - ping.createdAt > 5000) { groupPings.splice(idx, 1); continue; }
            const mx = toMiniX(ping.x);
            const my = toMiniY(ping.y);
            if (mx >= x && mx <= x + MINIMAP_WIDTH && my >= y && my <= y + MINIMAP_HEIGHT) {
                const def = MINIMAP_SPRITE_DEFS.groupPing;
                const frame = Math.floor(((nowMs - ping.createdAt) / 1000) * def.fps);
                const pingImg = getMinimapSpriteFrame("groupPing", def.loop ? frame % def.frameCount : Math.min(frame, def.frameCount - 1));
                if (pingImg && pingImg.complete && pingImg.width > 0) {
                    ctx.save();
                    const size = Math.max(pingImg.width, pingImg.height);
                    ctx.globalAlpha = 1 - Math.min((nowMs - ping.createdAt) / 5000, 1);
                    ctx.drawImage(pingImg, mx - size / 2, my - size / 2, size, size);
                    ctx.restore();
                } else {
                    ctx.save();
                    ctx.strokeStyle = "#ffff00"; ctx.lineWidth = 2;
                    const anim = (nowMs - ping.createdAt) / 1000;
                    const r = 2 + (anim % 1) * 10;
                    ctx.globalAlpha = 1 - (anim % 1);
                    ctx.beginPath(); ctx.arc(mx, my, r, 0, Math.PI * 2); ctx.stroke();
                    ctx.restore();
                }
            }
        }

        // 7. Joueur
        if (px >= x && px <= x + MINIMAP_WIDTH && py >= y && py <= y + MINIMAP_HEIGHT) {
            ctx.fillStyle = "white";
            ctx.fillRect(px - 2, py - 2, 4, 4);

            // Alerte ennemi proche (icône)
            const alertImg = getUiImage(UI_SPRITES.minimapAlertIcon);
            if (alertImg && alertImg.complete && alertImg.width > 0) {
                const threat = Object.values(entities).some(ent => ent && ent.kind === "player" && ent.factionId && heroFactionId && ent.factionId !== heroFactionId && Math.hypot(ent.x - shipX, ent.y - shipY) < 2000);
                if (threat) {
                    ctx.drawImage(alertImg, px - alertImg.width / 2, py - alertImg.height / 2, alertImg.width, alertImg.height);
                }
            }
        }

        // 8. Textes
        const displayX = Math.round(shipX / 100);
        const displayY = Math.round(shipY / 100);
        const coordText = `${displayX} / ${displayY}`;
        const mapText = `${cfg.mapID || 1}-1`;

        ctx.fillStyle = "#ccc";
        ctx.font = "10px Arial";
        ctx.textAlign = "left";
        ctx.fillText(mapText, x + 4, y + 12);
        ctx.fillStyle = "#fff";
        ctx.textAlign = "center";
        ctx.fillText(coordText, x + MINIMAP_WIDTH / 2, y + MINIMAP_HEIGHT - 4);
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

    function drawShip() {
        const shipScreenX = mapToScreenX(shipX);
        const syBase = mapToScreenY(shipY);
        const bobOffset = getHeroIdleOffset();
        const sy = syBase + bobOffset;

        ctx.save();

        // Transparence si camouflage
        ctx.globalAlpha = heroCloaked ? 0.3 : 1.0;

        const shipId = heroShipId;
        const def = SHIP_SPRITE_DEFS[shipId];
        let shipDrawnHeight = 20;

        if (def) {
            const frameIndex = getDirectionFrameIndex(heroAngle, def.frameCount);

            if (typeof getShipGlowFrame === "function") {
                const glowImg = getShipGlowFrame(shipId, frameIndex);
                if (glowImg && glowImg.complete && glowImg.width > 0 && glowImg.height > 0) {
                    const gw = glowImg.width;
                    const gh = glowImg.height;
                    ctx.drawImage(glowImg, shipScreenX - gw / 2, sy - gh / 2);
                }
            }

            const img = getShipSpriteFrame(shipId, frameIndex);

            if (img && img.complete && img.width > 0 && img.height > 0) {
                const w = img.width;
                const h = img.height;
                shipDrawnHeight = h;
                ctx.drawImage(img, shipScreenX - w / 2, sy - h / 2);
            }
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

        if (setting_show_drones && window.heroDrones && window.heroDrones.length > 0) {
            drawDrones(shipX, shipY, window.heroDrones);
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

        ctx.restore();
    }



    // Dessin générique des drones autour d'un vaisseau
    function drawDrones(worldX, worldY, drones) {
        if (!drones || !drones.length) return;

        const count = drones.length;
        const centerX = mapToScreenX(worldX);
        const centerY = mapToScreenY(worldY);
        const radius = 26;   // distance en pixels autour du vaisseau
        const size = 8;      // taille du drone (pour l'instant un petit rond)

        for (let i = 0; i < count; i++) {
            const angle = (Math.PI * 2 * i) / count;
            const droneScreenX = centerX + Math.cos(angle) * radius;
            const droneScreenY = centerY + Math.sin(angle) * radius;

            ctx.save();
            ctx.fillStyle = "#ffaa00"; // couleur temporaire, plus tard on mettra un sprite
            ctx.beginPath();
            ctx.arc(droneScreenX, droneScreenY, size / 2, 0, Math.PI * 2, false);
            ctx.fill();
            ctx.restore();
        }
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
            const img = getShipSpriteFrame(e.shipId, frameIndex);
            if (img && img.complete && img.width > 0 && img.height > 0) {
                const w = img.width;
                const h = img.height;
                spriteHeight = h;
                  ctx.drawImage(img, entityScreenX - w / 2, entityScreenY - h / 2);
                drewSprite = true;
            }
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
        if (setting_show_drones && e.drones && e.drones.length > 0) {
            drawDrones(e.x, e.y, e.drones);
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

        ctx.fillStyle = getEntityColor(e);
        ctx.fillRect(boxScreenX - size / 2, boxScreenY - size / 2, size, size);
    }
}



    function drawPortals() {
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
                // --- VRAI PORTAIL DE SAUT (Cercle Cyan/Bleu) ---
                // C'est le "GalaxyGate" graphique
                ctx.strokeStyle = "#00ffff"; // Cyan brillant
                ctx.shadowBlur = 15;
                ctx.shadowColor = "#00ffff";

                const radius = 24; // Taille standard Flash ~
                
                // Cercle extérieur
                ctx.beginPath();
                ctx.arc(portalScreenX, portalScreenY, radius, 0, Math.PI * 2, false);
                ctx.stroke();

                // Cercle intérieur (animation visuelle simple)
                ctx.beginPath();
                ctx.arc(portalScreenX, portalScreenY, radius - 8, 0, Math.PI * 2, false);
                ctx.globalAlpha = 0.6;
                ctx.stroke();
                
                // Centre
                ctx.beginPath();
                ctx.arc(portalScreenX, portalScreenY, 4, 0, Math.PI * 2, false);
                ctx.fillStyle = "#ffffff";
                ctx.fill();

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


    function drawTargetWindow() {
        if (selectedTargetId == null) return;
        let target = null;
        let isHero = false;
        if (heroId !== null && selectedTargetId === heroId) isHero = true;
        else if (entities[selectedTargetId]) target = entities[selectedTargetId];
        if (!isHero && !target) return;

        const name = isHero ? (heroName || "Vous") : (target.name || `ID ${selectedTargetId}`);
        const hp   = isHero ? heroHp : target.hp;
        const shd  = isHero ? heroShield : target.shield;
        const tx   = isHero ? shipX : target.x;
        const ty   = isHero ? shipY : target.y;
        const dist = Math.round(Math.hypot(tx - shipX, ty - shipY));

        const width  = 260;
        const height = 80;
        const x = (canvas.width  - width) / 2;
        const y = canvas.height - height - 20;

        const chrome = drawWindowChrome(x, y, width, height, name, { showButtons: true });

        ctx.save();
        ctx.font = "12px Consolas, monospace";
        ctx.fillStyle = "#dfefff";
        ctx.textAlign = "right";
        ctx.textBaseline = "middle";
        ctx.fillText(`${dist} u`, x + width - 10, y + chrome.headerHeight / 2);

        const barX = x + 10;
        let barY = y + chrome.headerHeight + 6;
        const barW = width - 20;

        ctx.fillStyle = "#222";
        ctx.fillRect(barX, barY, barW, 12);
        if (hp != null) {
            const denomHp = target && target.maxHp ? target.maxHp : (isHero ? heroMaxHp : hp || 1);
            const ratio = denomHp ? Math.max(0, Math.min(1, hp / denomHp)) : 0;
            ctx.fillStyle = "#0f0";
            ctx.fillRect(barX, barY, barW * ratio, 12);
        }
        ctx.strokeStyle = "#000";
        ctx.strokeRect(barX + 0.5, barY + 0.5, barW - 1, 12 - 1);
        ctx.fillStyle = "#fff";
        ctx.textAlign = "center";
        ctx.font = "11px Consolas, monospace";
        const hpText = hp != null ? `${hp}${target && target.maxHp ? " / " + target.maxHp : (isHero && heroMaxHp ? " / " + heroMaxHp : "")}` : "HP:?";
        ctx.fillText(hpText, barX + barW / 2, barY + 1);

        barY += 18;
        ctx.fillStyle = "#222";
        ctx.fillRect(barX, barY, barW, 10);
        if (shd != null) {
            const denomSh = target && target.maxShield ? target.maxShield : (isHero ? heroMaxShield : shd || 1);
            const ratio = denomSh ? Math.max(0, Math.min(1, shd / denomSh)) : 0;
            ctx.fillStyle = "#00bfff";
            ctx.fillRect(barX, barY, barW * ratio, 10);
        }
        ctx.strokeStyle = "#000";
        ctx.strokeRect(barX + 0.5, barY + 0.5, barW - 1, 10 - 1);
        ctx.fillStyle = "#fff";
        const shText = shd != null ? `${shd}${target && target.maxShield ? " / " + target.maxShield : (isHero && heroMaxShield ? " / " + heroMaxShield : "")}` : "SHD:?";
        ctx.fillText(shText, barX + barW / 2, barY - 1);
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
    const isBasic = BASIC_WINDOW_KEYS.has(key);
    div.className = isBasic ? 'gameWindow basicWindow' : 'gameWindow';
    if (!isBasic && key === 'chat') div.classList.add('chatTheme');
    div.style.width = cfg.w + 'px';
    div.style.height = cfg.h + 'px';
    const pos = WINDOW_DEFAULT_POS[key] || {};
    div.style.top = (pos.top != null ? pos.top : 100) + 'px';
    div.style.left = (pos.left != null ? pos.left : 100) + 'px';

    if (isBasic) {
        div.innerHTML = `
            <div class="basicHeader" id="head_${key}">
                <span>${cfg.title}</span>
                <div class="gwButtons">
                    <span class="basicBtn collapseBtn">-</span>
                    <span class="basicBtn closeBtn">x</span>
                </div>
            </div>
            <div class="basicContent" id="content_${key}"></div>
        `;
    } else {
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
                <div class="gwContent" id="content_${key}">
                    </div>
            </div>
        `;
    }
    document.body.appendChild(div);

    const content = div.querySelector('.gwContent') || div.querySelector('.basicContent');
    // Gestion Fermeture (X)
    div.querySelector('.closeBtn').addEventListener('click', () => {
        toggleWindow(key, false); // Force fermeture
    });

    const collapseBtn = div.querySelector('.collapseBtn');
    if (collapseBtn) {
        collapseBtn.addEventListener('click', () => {
            const collapsed = content.dataset.collapsed === '1';
            content.dataset.collapsed = collapsed ? '0' : '1';
            content.style.display = collapsed ? 'block' : 'none';
        });
    }

    // Gestion Déplacement (Drag & Drop HTML)
    makeElementDraggable(div, div.querySelector('.gwHeader') || div.querySelector('.basicHeader'));
}

function toggleWindow(key, forceState) {
    // Si forceState est défini, on l'utilise, sinon on inverse l'état actuel
    const newState = (forceState !== undefined) ? forceState : !windowStates[key];
    windowStates[key] = newState;
    refreshWindowsVisibility();
}

function refreshWindowsVisibility() {
    for (const key in windowStates) {
        const isVisible = windowStates[key];
        const icon = document.getElementById('icon_' + key);
        
        // C'est ici la correction : on cherche 'win_chat' comme les autres fenêtres
        // On garde une exception uniquement pour 'quest' (quêtes) qui est encore à l'ancienne
        let winId = 'win_' + key;
        if (key === 'quest') winId = 'questWindow';
        if (key === 'log') winId = 'gameLogWindow';
        
        const win = document.getElementById(winId);

        // 1. Allumer/Eteindre l'icône
        if (icon) {
            if (isVisible) icon.classList.add('active');
            else icon.classList.remove('active');
        }

        // 2. Afficher/Cacher la fenêtre HTML
        if (win) {
            win.style.display = isVisible ? 'flex' : 'none';
        }
        
        // 3. Cas spécial Minimap (dessinée sur le Canvas)
        if (key === 'map') {
            window.showMinimap = isVisible; 
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

function initGlobalButtonStyles() {
    const btnUp = UI_SPRITES.chatButton || UI_SPRITES.buttonCollapse || UI_SPRITES.windowHeader;
    const btnOver = UI_SPRITES.buttonClose || btnUp;
    const btnDown = UI_SPRITES.windowHeader || btnOver;
    const btnDisabled = UI_SPRITES.windowSide || btnUp;
    const btnClassic = UI_SPRITES.buttonCollapse || btnUp;
    const btnClassicHover = UI_SPRITES.buttonClose || btnClassic;
    const btnClassicDisabled = UI_SPRITES.windowSide || btnClassic;
    const btnClassicEmph = UI_SPRITES.windowHeader || btnClassicHover;

    const style = document.createElement('style');
    style.innerHTML = `
        /* Boutons "sélectionnés" (style déjà utilisé pour quêtes, labo, etc.) */
        .doButton {
            background-image: url('${btnUp}');
            background-repeat: no-repeat;
            background-size: 100% 100%;
            border: none;
            color: #ffffff;
            cursor: pointer;
            padding: 3px 8px;
            min-width: 90px;
            height: 22px;
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        .doButton:hover {
            background-image: url('${btnOver}');
        }

        .doButton:active {
            background-image: url('${btnDown}');
        }

        .doButton:disabled,
        .doButton.disabled {
            background-image: url('${btnDisabled}');
            cursor: default;
            opacity: 0.7;
        }

        /* Boutons "classiques" basés sur fl.controls.Button */
        .doClassicButton {
            background-image: url('${btnClassic}');
            background-repeat: no-repeat;
            background-size: 100% 100%;
            border: none;
            color: #ffffff;
            cursor: pointer;
            padding: 3px 8px;
            min-width: 90px;
            height: 22px;
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        .doClassicButton:hover {
            background-image: url('${btnClassicHover}');
        }

        .doClassicButton:active {
            /* On réutilise le même visuel que hover pour l'instant */
            background-image: url('${btnClassicHover}');
        }

        .doClassicButton:disabled,
        .doClassicButton.disabled {
            /* Skin Button_disabledSkin (non sélectionné) */
            background-image: url('${btnClassicDisabled}');
            cursor: default;
            opacity: 0.7;
        }

        /* Variante "emphasized" (bouton important) comme dans fl.controls.Button */
        .doClassicButton.emphasized {
            background-image: url('${btnClassicEmph}');
            color: #ffffff;
            font-weight: bold;
        }
    `;
    document.head.appendChild(style);
}

function initGlobalTextFieldStyles() {
    const textBg = UI_SPRITES.chatBg || UI_SPRITES.windowBg;
    const textDisabled = UI_SPRITES.windowSide || textBg;
    const inputBg = UI_SPRITES.chatInputBg || textBg;
    const inputDisabled = UI_SPRITES.windowSide || inputBg;
    const style = document.createElement('style');
    style.innerHTML = `
        /* ================================
           TextArea générique (fl.controls.TextArea)
           ================================ */

        /* Fond "normal" basé sur le wrapper TextArea de Flash */
        .flTextAreaSkin {
            background-image: url('${textBg}');
            background-repeat: repeat;
            background-size: 100% 100%;
        }

        /* Etat désactivé = TextArea_disabledSkin */
        .flTextAreaSkin.disabled,
        .flTextAreaSkin:disabled,
        textarea.flTextAreaSkin:disabled {
            background-image: url('${textDisabled}');
        }

        /* Variante plus simple pour nos futures fenêtres : .doTextArea */
        textarea.doTextArea,
        .doTextArea {
            background-image: url('${textBg}');
            background-repeat: repeat;
            background-size: auto;
            color: #dddddd;
            border: none;
            padding: 4px;
            font-size: 11px;
            font-family: Arial, sans-serif;
        }

        textarea.doTextArea:disabled,
        .doTextArea.disabled {
            background-image: url('${textDisabled}');
            color: #777777;
        }

        /* ================================
           TextInput générique (fl.controls.TextInput)
           ================================ */

        /* Fond "normal" basé sur le wrapper TextInput de Flash */
        .flTextInputSkin {
            background-image: url('${inputBg}');
            background-repeat: repeat;
            background-size: 100% 100%;
        }

        /* Etat désactivé = TextInput_disabledSkin */
        .flTextInputSkin.disabled,
        .flTextInputSkin:disabled,
        input.flTextInputSkin:disabled {
            background-image: url('${inputDisabled}');
        }

        /* Variante simple pour nos inputs : .doTextInput */
        input.doTextInput,
        .doTextInput {
            background-image: url('${inputBg}');
            background-repeat: repeat;
            background-size: 100% 100%;
            border: none;
            padding: 2px 5px;
            font-size: 11px;
            font-family: Arial, sans-serif;
            color: #ffffff;
        }

        input.doTextInput:disabled,
        .doTextInput.disabled {
            background-image: url('${inputDisabled}');
            color: #888888;
        }
    `;
    document.head.appendChild(style);
}

function initGlobalComboBoxStyles() {
    const style = document.createElement('style');
    style.innerHTML = `
        /* ================================
           ComboBox style DarkOrbit
           ================================ */

        /* Wrapper générique basé sur fl.controls.ComboBox */
        .flComboBoxSkin {
            background-image: url('assets/spirites/DefineSprite_142_fl.controls.ComboBox_fl.controls.ComboBox/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        /* Conteneur principal */
        .doComboBox {
            position: relative;
            display: inline-block;
            width: 180px;
            height: 22px;
            font-size: 11px;
            font-family: Arial, sans-serif;
            color: #dddddd;
            cursor: pointer;
            user-select: none;
        }

        /* Zone affichant la valeur sélectionnée */
        .doComboBoxSelected {
            height: 22px;
            line-height: 22px;
            padding: 0 24px 0 6px;
            background-image: url('assets/spirites/DefineSprite_114_ComboBox_upSkin_ComboBox_upSkin/1.png');
            background-repeat: repeat-x;
            background-size: 100% 100%;
            box-sizing: border-box;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        /* Flèche à droite */
        .doComboBoxArrow {
            position: absolute;
            top: 0;
            right: 0;
            width: 22px;
            height: 22px;
            background-image: url('assets/spirites/DefineSprite_114_ComboBox_upSkin_ComboBox_upSkin/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        /* Etat "ouvert" : on utilise le downSkin */
        .doComboBox.open .doComboBoxSelected {
            background-image: url('assets/spirites/DefineSprite_120_ComboBox_downSkin_ComboBox_downSkin/1.png');
        }

        .doComboBox.open .doComboBoxArrow {
            background-image: url('assets/spirites/DefineSprite_120_ComboBox_downSkin_ComboBox_downSkin/1.png');
        }

        /* Etat survolé : overSkin */
        .doComboBox:hover .doComboBoxSelected:not(.disabled) {
            background-image: url('assets/spirites/DefineSprite_118_ComboBox_overSkin_ComboBox_overSkin/1.png');
        }

        .doComboBox:hover .doComboBoxArrow:not(.disabled) {
            background-image: url('assets/spirites/DefineSprite_118_ComboBox_overSkin_ComboBox_overSkin/1.png');
        }

        /* Etat désactivé : disabledSkin */
        .doComboBox.disabled .doComboBoxSelected,
        .doComboBox.disabled .doComboBoxArrow {
            background-image: url('assets/spirites/DefineSprite_116_ComboBox_disabledSkin_ComboBox_disabledSkin/1.png');
            color: #777777;
            cursor: default;
        }

        /* Liste déroulante */
        .doComboBoxList {
            position: absolute;
            left: 0;
            top: 22px;
            width: 100%;
            max-height: 160px;
            overflow-y: auto;
            background: #000910;
            border: 1px solid #3a5b7c;
            z-index: 1500;
            display: none;
            box-sizing: border-box;
        }

        .doComboBox.open .doComboBoxList {
            display: block;
        }

        .doComboBoxList ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .doComboBoxList li {
            padding: 3px 6px;
            cursor: pointer;
            color: #bbbbbb;
            background-image: url('assets/spirites/DefineSprite_124_CellRenderer_upSkin_CellRenderer_upSkin/1.png');
            background-repeat: repeat-x;
            background-size: 100% 100%;
            white-space: nowrap;
        }

        .doComboBoxList li:hover {
            color: #00aaff;
            background-image: url('assets/spirites/DefineSprite_130_CellRenderer_overSkin_CellRenderer_overSkin/1.png');
        }

        .doComboBoxList li.disabled {
            color: #666666;
            cursor: default;
            background-image: url('assets/spirites/DefineSprite_126_CellRenderer_disabledSkin_CellRenderer_disabledSkin/1.png');
        }
    `;
    document.head.appendChild(style);
}
function initGlobalSliderStyles() {
    const style = document.createElement('style');
    style.innerHTML = `
        /* ================================
           Slider style DarkOrbit
           ================================ */

        /* Wrapper générique basé sur fl.controls.Slider */
        .flSliderSkin {
            background-image: url('assets/spirites/DefineSprite_93_fl.controls.Slider_fl.controls.Slider/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        /* Conteneur principal du slider DO */
        .doSlider {
            position: relative;
            height: 22px;
            padding: 3px 4px;
            box-sizing: border-box;
        }

        /* Track normal */
        .doSliderTrack {
            position: relative;
            height: 16px;
            background-image: url('assets/spirites/DefineSprite_88_SliderTrack_skin_SliderTrack_skin/1.png');
            background-repeat: repeat-x;
            background-size: auto 16px;
            border-radius: 3px;
        }

        /* Track désactivé */
        .doSliderTrack.disabled,
        .doSlider.disabled .doSliderTrack {
            background-image: url('assets/spirites/DefineSprite_92_SliderTrack_disabledSkin_SliderTrack_disabledSkin/1.png');
        }

        /* Thumb normal */
        .doSliderThumb {
            position: absolute;
            top: -4px;
            width: 24px;
            height: 24px;
            background-image: url('assets/spirites/DefineSprite_80_SliderThumb_upSkin_SliderThumb_upSkin/1.png');
            background-repeat: no-repeat;
            background-size: contain;
            cursor: pointer;
        }

        /* Thumb survolé */
        .doSliderThumb.over {
            background-image: url('assets/spirites/DefineSprite_82_SliderThumb_overSkin_SliderThumb_overSkin/1.png');
        }

        /* Thumb cliqué (down) */
        .doSliderThumb.down {
            background-image: url('assets/spirites/DefineSprite_84_SliderThumb_downSkin_SliderThumb_downSkin/1.png');
        }

        /* Thumb désactivé */
        .doSliderThumb.disabled,
        .doSlider.disabled .doSliderThumb {
            background-image: url('assets/spirites/DefineSprite_86_SliderThumb_disabledSkin_SliderThumb_disabledSkin/1.png');
            cursor: default;
        }

        /* Ticks (graduations) optionnels */
        .doSliderTicks {
            position: absolute;
            left: 0;
            right: 0;
            top: 50%;
            height: 4px;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .doSliderTick {
            position: absolute;
            width: 2px;
            height: 4px;
            background-image: url('assets/spirites/DefineSprite_90_SliderTick_skin_SliderTick_skin/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }
    `;
    document.head.appendChild(style);
}

function initGlobalListStyles() {
    const style = document.createElement('style');
    style.innerHTML = `
        /* ================================
           List / CellRenderer wrappers
           ================================ */

        /* Wrapper basé sur fl.controls.listClasses.CellRenderer */
        .flCellRendererWrapper {
            background-image: url('assets/spirites/DefineSprite_139_fl.controls.listClasses.CellRenderer_fl.controls.listClasses.CellRenderer/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        /* Wrapper basé sur fl.controls.List */
        .flListWrapper {
            background-image: url('assets/spirites/DefineSprite_141_fl.controls.List_fl.controls.List/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }
    `;
    document.head.appendChild(style);
}

function initGlobalMiscComponentStyles() {
    const style = document.createElement('style');
    style.innerHTML = `
        /* =====================================
           focusRectSkin (focus clavier à la Flash)
           ===================================== */

        /* 
           Classe à poser sur des éléments focusables
           (boutons, items de liste...) pour avoir
           un focus visuel comme dans Flash.
           Exemple d'usage plus tard :
           <button class="doButton doFocusRectTarget">...</button>
        */
        .doFocusRectTarget {
            position: relative;
            outline: none;
        }

        .doFocusRectTarget:focus::after {
            content: "";
            position: absolute;
            left: -2px;
            top: -2px;
            right: -2px;
            bottom: -2px;
            pointer-events: none;
            background-image: url('assets/spirites/DefineSprite_78_focusRectSkin_focusRectSkin/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        /* =====================================
           ScrollBar_thumbIcon (icône sur le thumb)
           ===================================== */

        /*
           Classe utilitaire si on veut ajouter l'icône
           de thumb par-dessus une scrollbar custom.
           Pour l'instant on ne l'applique pas encore,
           mais le sprite est relié.
        */
        .doScrollThumbIcon {
            background-image: url('assets/spirites/DefineSprite_37_ScrollBar_thumbIcon_ScrollBar_thumbIcon/1.png');
            background-repeat: no-repeat;
            background-position: center center;
            background-size: 100% 100%;
        }

        /* =====================================
           ComponentShim (wrapper interne Flash)
           ===================================== */

        /*
           Wrapper générique pour simuler le shim
           fl.core.ComponentShim si besoin.
        */
        .flComponentShim {
            background-image: url('assets/spirites/DefineSprite_5_fl.core.ComponentShim_fl.core.ComponentShim/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }
    `;
    document.head.appendChild(style);
}
function initGlobalSpriteDebugStyles() {
    const style = document.createElement('style');
    style.innerHTML = `
        /* =====================================
           Sprites génériques DO (debug / réserve)
           ===================================== */

        .doSprite4 {
            background-image: url('assets/spirites/DefineSprite_4/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .doSprite7 {
            background-image: url('assets/spirites/DefineSprite_7/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .doSprite12 {
            background-image: url('assets/spirites/DefineSprite_12/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .doSprite15 {
            background-image: url('assets/spirites/DefineSprite_15/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .doSprite44 {
            background-image: url('assets/spirites/DefineSprite_44/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .doSprite46 {
            background-image: url('assets/spirites/DefineSprite_46/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .doSprite49 {
            background-image: url('assets/spirites/DefineSprite_49/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .doSprite50 {
            background-image: url('assets/spirites/DefineSprite_50/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .doSprite52 {
            background-image: url('assets/spirites/DefineSprite_52/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .doSprite54 {
            background-image: url('assets/spirites/DefineSprite_54/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .doSprite56 {
            background-image: url('assets/spirites/DefineSprite_56/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .doSprite58 {
            background-image: url('assets/spirites/DefineSprite_58/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .doSprite60 {
            background-image: url('assets/spirites/DefineSprite_60/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .doSprite62 {
            background-image: url('assets/spirites/DefineSprite_62/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .doSprite64 {
            background-image: url('assets/spirites/DefineSprite_64/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .doSprite66 {
            background-image: url('assets/spirites/DefineSprite_66/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .doSprite68 {
            background-image: url('assets/spirites/DefineSprite_68/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .doSprite70 {
            background-image: url('assets/spirites/DefineSprite_70/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .doSprite140 {
            background-image: url('assets/spirites/DefineSprite_140/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }
    `;
    document.head.appendChild(style);
}






function initGlobalScrollbarStyles() {
    const style = document.createElement('style');
    style.innerHTML = `
        /* ==========================================
           SCROLLBARS STYLE DARKORBIT
           Appliqué au chat, au log et à toute zone
           ayant la classe .doScrollArea ou .doScrollPane
           ========================================== */

        /* Cible de base : chat, log, et zones marquées .doScrollArea */
        #chatContent,
        #logContent,
        .doScrollArea {
            scrollbar-width: thin;                /* Firefox */
            scrollbar-color: #4a6b8c transparent; /* Firefox : thumb + track */
        }

        /* Largeur + fond (track) - WebKit (Chrome, Edge, etc.) */
        #chatContent::-webkit-scrollbar,
        #logContent::-webkit-scrollbar,
        .doScrollArea::-webkit-scrollbar {
            width: 14px;
            background-image: url('assets/spirites/DefineSprite_10_ScrollTrack_skin_ScrollTrack_skin/1.png');
            background-repeat: repeat-y;
            background-size: 100% 16px;
        }

        /* Curseur (thumb) normal */
        #chatContent::-webkit-scrollbar-thumb,
        #logContent::-webkit-scrollbar-thumb,
        .doScrollArea::-webkit-scrollbar-thumb {
            background-image: url('assets/spirites/DefineSprite_30_ScrollThumb_upSkin_ScrollThumb_upSkin/1.png');
            background-repeat: no-repeat;
            background-size: 100% 16px;
            border-radius: 4px;
        }

        /* Thumb survolé (over) */
        #chatContent::-webkit-scrollbar-thumb:hover,
        #logContent::-webkit-scrollbar-thumb:hover,
        .doScrollArea::-webkit-scrollbar-thumb:hover {
            background-image: url('assets/spirites/DefineSprite_24_ScrollThumb_overSkin_ScrollThumb_overSkin/1.png');
        }

        /* Thumb cliqué (down) */
        #chatContent::-webkit-scrollbar-thumb:active,
        #logContent::-webkit-scrollbar-thumb:active,
        .doScrollArea::-webkit-scrollbar-thumb:active {
            background-image: url('assets/spirites/DefineSprite_20_ScrollThumb_downSkin_ScrollThumb_downSkin/1.png');
        }

        /* ==============================
           Flèches haut / bas (ScrollArrow*)
           ============================== */

        /* Boutons de scroll verticaux : hauteur et base */
        #chatContent::-webkit-scrollbar-button:vertical,
        #logContent::-webkit-scrollbar-button:vertical,
        .doScrollArea::-webkit-scrollbar-button:vertical {
            height: 14px;
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        /* Flèche HAUT : état normal (upSkin) */
        #chatContent::-webkit-scrollbar-button:vertical:decrement,
        #logContent::-webkit-scrollbar-button:vertical:decrement,
        .doScrollArea::-webkit-scrollbar-button:vertical:decrement {
            background-image: url('assets/spirites/DefineSprite_28_ScrollArrowUp_upSkin_ScrollArrowUp_upSkin/1.png');
        }

        /* Flèche HAUT survolée (overSkin) */
        #chatContent::-webkit-scrollbar-button:vertical:decrement:hover,
        #logContent::-webkit-scrollbar-button:vertical:decrement:hover,
        .doScrollArea::-webkit-scrollbar-button:vertical:decrement:hover {
            background-image: url('assets/spirites/DefineSprite_26_ScrollArrowUp_overSkin_ScrollArrowUp_overSkin/1.png');
        }

        /* Flèche HAUT cliquée (downSkin) */
        #chatContent::-webkit-scrollbar-button:vertical:decrement:active,
        #logContent::-webkit-scrollbar-button:vertical:decrement:active,
        .doScrollArea::-webkit-scrollbar-button:vertical:decrement:active {
            background-image: url('assets/spirites/DefineSprite_16_ScrollArrowUp_downSkin_ScrollArrowUp_downSkin/1.png');
        }

        /* Flèche BAS : état normal (upSkin) */
        #chatContent::-webkit-scrollbar-button:vertical:increment,
        #logContent::-webkit-scrollbar-button:vertical:increment,
        .doScrollArea::-webkit-scrollbar-button:vertical:increment {
            background-image: url('assets/spirites/DefineSprite_31_ScrollArrowDown_upSkin_ScrollArrowDown_upSkin/1.png');
        }

        /* Flèche BAS survolée (overSkin) */
        #chatContent::-webkit-scrollbar-button:vertical:increment:hover,
        #logContent::-webkit-scrollbar-button:vertical:increment:hover,
        .doScrollArea::-webkit-scrollbar-button:vertical:increment:hover {
            background-image: url('assets/spirites/DefineSprite_22_ScrollArrowDown_overSkin_ScrollArrowDown_overSkin/1.png');
        }

        /* Flèche BAS cliquée (downSkin) */
        #chatContent::-webkit-scrollbar-button:vertical:increment:active,
        #logContent::-webkit-scrollbar-button:vertical:increment:active,
        .doScrollArea::-webkit-scrollbar-button:vertical:increment:active {
            background-image: url('assets/spirites/DefineSprite_18_ScrollArrowDown_downSkin_ScrollArrowDown_downSkin/1.png');
        }

        /* Etats "désactivés" des flèches (quand la zone a la classe .scrollDisabled) */
        .scrollDisabled::-webkit-scrollbar-button:vertical:decrement,
        .scrollDisabled::-webkit-scrollbar-button:vertical:decrement:hover,
        .scrollDisabled::-webkit-scrollbar-button:vertical:decrement:active {
            background-image: url('assets/spirites/DefineSprite_35_ScrollArrowUp_disabledSkin_ScrollArrowUp_disabledSkin/1.png');
        }

        .scrollDisabled::-webkit-scrollbar-button:vertical:increment,
        .scrollDisabled::-webkit-scrollbar-button:vertical:increment:hover,
        .scrollDisabled::-webkit-scrollbar-button:vertical:increment:active {
            background-image: url('assets/spirites/DefineSprite_33_ScrollArrowDown_disabledSkin_ScrollArrowDown_disabledSkin/1.png');
        }

        /* ==============================
           Wrappers ScrollPane / ScrollBar
           ============================== */

        /* Style générique d'une "ScrollPane" à la Flash */
        .doScrollPane {
            background-image: url('assets/spirites/DefineSprite_39_fl.containers.ScrollPane_fl.containers.ScrollPane/1.png');
            background-repeat: repeat;
            background-size: 100% 100%;
        }

        .doScrollPane.disabled {
            background-image: url('assets/spirites/DefineSprite_6_ScrollPane_disabledSkin_ScrollPane_disabledSkin/1.png');
        }

        /* Style générique pour une barre de scroll indépendante (si besoin plus tard) */
        .doScrollBar {
            background-image: url('assets/spirites/DefineSprite_38_fl.controls.ScrollBar_fl.controls.ScrollBar/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .doUIScrollBar {
            background-image: url('assets/spirites/DefineSprite_71_fl.controls.UIScrollBar_fl.controls.UIScrollBar/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }
    `;
    document.head.appendChild(style);
}





	// ========================================================
    // INTERFACE LABO / VENTE MINERAIS
    // ========================================================
    let labWindowVisible = false; // État de la fenêtre
    let labMode = 'cargo';      // 'cargo' ou 'refine'

    function initLabWindow() {
    const style = document.createElement('style');
    style.innerHTML = `
        #labWindow {
            position: absolute; top: 150px; left: 50%; transform: translateX(-50%);
            width: 450px; height: 350px;

            /* ancien fond + skin ScrollPane du main.swf */
            background: rgba(0, 10, 20, 0.95);
            background-image: url('assets/spirites/log/ScrollPane_upSkin.png');
            background-repeat: repeat;
            background-size: auto;

            border: 2px solid #4a6b8c;
            color: #ccc; font-family: Consolas, monospace; font-size: 12px;
            padding: 10px; z-index: 1000; display: none;
        }

        .labHeader {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #4a6b8c;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .labTitle { color: #00aaff; font-weight: bold; font-size: 14px; }
        .labClose { color: #ff4444; cursor: pointer; font-weight: bold; }

        .labContent {
            display: flex;
            height: 80%;
        }

        /* Colonne menu à gauche */
                .labMenu {
            width: 120px;
            border-right: 1px solid #2a4b6c;
            padding-right: 10px;
        }

        .labMenu div {
            padding: 5px;
            cursor: pointer;
            color: #888;
            background-image: url('assets/spirites/DefineSprite_124_CellRenderer_upSkin_CellRenderer_upSkin/1.png');
            background-repeat: repeat-x;
            background-size: 100% 100%;
            margin-bottom: 4px;
        }

        .labMenu div:hover {
            background-image: url('assets/spirites/DefineSprite_130_CellRenderer_overSkin_CellRenderer_overSkin/1.png');
        }

        .labMenu div:active {
            background-image: url('assets/spirites/DefineSprite_128_CellRenderer_downSkin_CellRenderer_downSkin/1.png');
        }

        .labMenu div.active {
            color: #00aaff;
            font-weight: bold;
            background-image: url('assets/spirites/DefineSprite_138_CellRenderer_selectedUpSkin_CellRenderer_selectedUpSkin/1.png');
        }

        .labMenu div.active:hover {
            background-image: url('assets/spirites/DefineSprite_136_CellRenderer_selectedOverSkin_CellRenderer_selectedOverSkin/1.png');
        }
		
		        .labMenu div.active:active {
            background-image: url('assets/spirites/DefineSprite_134_CellRenderer_selectedDownSkin_CellRenderer_selectedDownSkin/1.png');
        }


        .labMenu div.disabled {
            cursor: default;
            color: #666;
            background-image: url('assets/spirites/DefineSprite_126_CellRenderer_disabledSkin_CellRenderer_disabledSkin/1.png');
        }

        .labMenu div.disabled.active {
            background-image: url('assets/spirites/DefineSprite_132_CellRenderer_selectedDisabledSkin_CellRenderer_selectedDisabledSkin/1.png');
        }


        /* Vue principale à droite : fond List_skin comme le journal */
        .labView {
            flex: 1;
            padding-left: 10px;
            background-image: url('assets/spirites/log/List_skin.png');
            background-repeat: repeat;
            background-size: auto;
            overflow-y: auto;
        }

        .oreItem {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
        }

        .oreCount { color: #fff; }
        .oreName  { color: #00ff00; }

        /* Boutons d'action du labo : skin de bouton DO */
        .btnAction {
            margin-top: 10px;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            color: white;

            background-image: url('assets/spirites/DefineSprite_109_Button_selectedUpSkin_Button_selectedUpSkin/1.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .btnAction:hover {
            background-image: url('assets/spirites/DefineSprite_107_Button_selectedOverSkin_Button_selectedOverSkin/1.png');
        }

        .btnAction:active {
            background-image: url('assets/spirites/DefineSprite_105_Button_selectedDownSkin_Button_selectedDownSkin/1.png');
        }

        .btnAction:disabled {
            background-image: url('assets/spirites/DefineSprite_103_Button_selectedDisabledSkin_Button_selectedDisabledSkin/1.png');
            cursor: default;
            opacity: 0.7;
        }
    `;
    document.head.appendChild(style);

    const labDiv = document.createElement('div');
    labDiv.id = 'labWindow';
    labDiv.innerHTML = `
        <div class="labHeader">
            <span class="labTitle">Laboratoire & Stock de Minerais</span>
            <span class="labClose" id="labCloseBtn">X</span>
        </div>
        <div class="labContent">
            <div class="labMenu">
                <div id="menuCargo" class="active" data-mode="cargo">Stock & Vente</div>
                <div id="menuRefine" data-mode="refine">Raffinage (Production)</div>
            </div>
            <div class="labView" id="labViewContent">
            </div>
        </div>
    `;
    document.body.appendChild(labDiv);

    // --- Logique d'affichage ---
    const closeBtn = document.getElementById('labCloseBtn');
    closeBtn.addEventListener('click', toggleLabWindow);
    document.getElementById('menuCargo').addEventListener('click', () => setLabMode('cargo'));
    document.getElementById('menuRefine').addEventListener('click', () => setLabMode('refine'));

    // Empêcher les clics de jeu derrière la fenêtre
    labDiv.addEventListener('mousedown', (e) => e.stopPropagation());
}


    // Fonction pour afficher/masquer la fenêtre
    function toggleLabWindow() {
        const win = document.getElementById('labWindow');
        labWindowVisible = !labWindowVisible;
        win.style.display = labWindowVisible ? 'flex' : 'none';
        if (labWindowVisible) {
            setLabMode(labMode); // Mettre à jour le contenu au moment de l'ouverture
        }
    }

    // Fonction pour changer l'onglet et le contenu
    function setLabMode(mode) {
        labMode = mode;
        const view = document.getElementById('labViewContent');
        const menuItems = document.querySelectorAll('.labMenu div');
        menuItems.forEach(item => {
            if (item.getAttribute('data-mode') === mode) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
        
        view.innerHTML = ''; // Nettoyer
        if (mode === 'cargo') {
            displayCargoView(view);
        } else if (mode === 'refine') {
            displayRefineView(view);
        }
    }
	// Affiche le stock actuel et l'option de vente
    function displayCargoView(container) {
        const cargo = window.oreCargo || {};
        const prices = window.orePrices || {};
        
        let html = `<h3>Stock actuel :</h3>`;
        
        for (const key in cargo) {
            const count = cargo[key];
            const price = prices[key] || 0;
            const value = count * price;
            
            html += `
                <div class="oreItem">
                    <span class="oreName">${key.toUpperCase()} :</span>
                    <span class="oreCount">${count.toLocaleString()}</span>
                    <button class="btnAction btnSellOre" data-ore="${key}" data-amount="all">Vendre (Valeur: ${value.toLocaleString()} Cr.)</button>
                </div>
            `;
        }

        container.innerHTML = html;

        // --- Logique d'envoi VENDRE ---
        document.querySelectorAll('.btnSellOre').forEach(button => {
            button.addEventListener('click', (e) => {
                const oreType = e.target.getAttribute('data-ore');
                const amount = cargo[oreType]; // Vendre tout le stock pour cet ore
                sendSellOre(oreType, amount);
            });
        });
    }

    // Affiche l'interface de production (raffinage)
    function displayRefineView(container) {
        // NOTE: On simplifie en affichant les produits finis principaux
        container.innerHTML = `
            <h3>Production (Raffinage) :</h3>
            <p>Prometid (P) : 20 Prometium + 10 Endurium</p>
            <p>Duranium (D) : 20 Terbium + 10 Endurium</p>
            <p>Promerium (M) : 10 P + 10 D + 1 Xenomit</p>
            
            <input type="number" id="refineAmount" value="100" min="1" style="width: 80px; margin-right: 10px;">
            <button class="btnAction doButton" id="btnProduce">Produire 100 Promerium</button>
            <p style="color:red; margin-top: 10px;">(Production non codée côté serveur dans l'émulateur fourni. Envoi de paquet simulé)</p>
        `;
        
        // --- Logique d'envoi PROD ---
        document.getElementById('btnProduce').addEventListener('click', () => {
            const amount = parseInt(document.getElementById('refineAmount').value, 10);
            if (amount > 0) {
                // Envoyer la commande de production de Promerium (ID 13)
                sendProduce(13, amount); 
            }
        });
    }
	
// -------------------------------------------------
// FENETRE HTML "PARAMETRES / OPTIONS" (version onglets)
// -------------------------------------------------
let settingsWindowInitialized = false;
let settingsWindowVisible = false;

function initSettingsWindow() {
    if (settingsWindowInitialized) return;
    settingsWindowInitialized = true;

    // --- CSS ---
    const style = document.createElement('style');
    style.innerHTML = `
        #settingsWindow {
            position: absolute;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            width: 520px;
            height: 360px;

            background: rgba(0, 10, 20, 0.96);
            background-image: url('assets/spirites/log/ScrollPane_upSkin.png');
            background-repeat: repeat;
            background-size: auto;

            border: 2px solid #4a6b8c;
            color: #ccc;
            font-family: Arial, sans-serif;
            font-size: 11px;
            padding: 6px;
            z-index: 1500;
            display: none;
            box-sizing: border-box;
        }

        #settingsWindow .settingsHeader {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }

        #settingsWindow .settingsTitle {
            font-weight: bold;
            color: #00ffcc;
            font-size: 13px;
        }

        #settingsWindow .settingsClose {
            cursor: pointer;
            color: #ff5555;
            font-weight: bold;
            padding: 0 4px;
        }

        #settingsWindow .settingsTabs {
            display: flex;
            gap: 4px;
            margin-bottom: 4px;
        }

        #settingsWindow .settingsTabBtn {
            flex: 1;
            height: 22px;
            padding: 0;
            font-size: 11px;
        }

        #settingsWindow .settingsTabBtn.active {
            outline: 1px solid #00ffcc;
        }

        #settingsWindow .settingsBody {
            position: absolute;
            left: 6px;
            right: 6px;
            top: 62px;
            bottom: 36px;
        }

        #settingsWindow .settingsTabPage {
            width: 100%;
            height: 100%;
            display: none;
        }

        #settingsWindow .settingsTabPage.active {
            display: block;
        }

        #settingsWindow .settingsScroll {
            width: 100%;
            height: 100%;
            overflow-y: auto;
        }

        #settingsWindow .settingsScroll.doScrollArea {
            /* profite de nos scrollbars DO */
        }

        #settingsWindow .settingsSectionTitle {
            margin-top: 4px;
            margin-bottom: 2px;
            font-weight: bold;
            color: #00aaff;
        }

        #settingsWindow .settingsRow {
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 2px 0;
        }

        #settingsWindow .settingsRow span {
            flex: 1;
        }

        #settingsWindow .settingsFooter {
            position: absolute;
            left: 6px;
            right: 6px;
            bottom: 6px;
            display: flex;
            justify-content: flex-end;
            gap: 6px;
        }
    `;
    document.head.appendChild(style);

    // --- HTML ---
    const win = document.createElement('div');
    win.id = 'settingsWindow';
    win.innerHTML = `
        <div class="settingsHeader">
            <span class="settingsTitle">PARAMÈTRES</span>
            <span class="settingsClose" id="settingsCloseBtn">X</span>
        </div>

        <div class="settingsTabs">
            <button class="doButton settingsTabBtn active" data-tab="display">Affichage</button>
            <button class="doButton settingsTabBtn" data-tab="gameplay">Gameplay</button>
            <button class="doButton settingsTabBtn" data-tab="interface">Interface</button>
            <button class="doButton settingsTabBtn" data-tab="sound">Son</button>
        </div>

        <div class="settingsBody">
            <!-- Onglet AFFICHAGE -->
            <div class="settingsTabPage active" data-tab="display">
                <div class="settingsScroll doScrollArea">
                    <div class="settingsSectionTitle">Affichage HUD / carte</div>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="DISPLAY_NOTIFICATIONS">
                        <span>Afficher les notifications système</span>
                    </label>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="DISPLAY_HITPOINT_BUBBLES">
                        <span>Afficher les bulles de dégâts (HP)</span>
                    </label>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="DISPLAY_PLAYER_NAMES">
                        <span>Afficher les pseudos des joueurs</span>
                    </label>

                    <div class="settingsSectionTitle">Qualité graphique (préréglage)</div>
                    <div class="settingsRow">
                        <div class="doComboBox" id="gfxQualityCombo">
                            <div class="doComboBoxSelected">Élevée</div>
                            <div class="doComboBoxArrow"></div>
                            <div class="doComboBoxList">
                                <ul>
                                    <li data-value="0">Basse</li>
                                    <li data-value="1">Moyenne</li>
                                    <li data-value="2">Élevée</li>
                                    <li data-value="3">Meilleure</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="settingsSectionTitle">Objets sur la carte</div>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="DISPLAY_ORE">
                        <span>Afficher les ressources (minerais)</span>
                    </label>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="DISPLAY_BONUS_BOXES">
                        <span>Afficher les bonus boxes</span>
                    </label>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="DISPLAY_FREE_CARGO_BOXES">
                        <span>Afficher les cargos gratuits</span>
                    </label>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="DISPLAY_NOT_FREE_CARGO_BOXES">
                        <span>Afficher les cargos non gratuits</span>
                    </label>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="SHOW_DRONES">
                        <span>Afficher les drones</span>
                    </label>
                </div>
            </div>

            <!-- Onglet GAMEPLAY -->
            <div class="settingsTabPage" data-tab="gameplay">
                <div class="settingsScroll doScrollArea">
                    <div class="settingsSectionTitle">Aide & automatisation</div>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="AUTO_BOOST">
                        <span>Activer l’auto-boost</span>
                    </label>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="AUTO_AMMO_CHANGE">
                        <span>Changer automatiquement de munition</span>
                    </label>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="AUTO_REFINEMENT">
                        <span>Activer le raffinage automatique</span>
                    </label>

                    <div class="settingsSectionTitle">Combat</div>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="QUICKSLOT_STOP_ATTACK">
                        <span>La barre rapide arrête l’attaque</span>
                    </label>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="DOUBLECLICK_ATTACK">
                        <span>Double-clic pour attaquer</span>
                    </label>

                    <div class="settingsSectionTitle">Divers</div>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="AUTO_START">
                        <span>Auto-start du jeu</span>
                    </label>
                </div>
            </div>

            <!-- Onglet INTERFACE -->
            <div class="settingsTabPage" data-tab="interface">
                <div class="settingsScroll doScrollArea">
                    <div class="settingsSectionTitle">Chat & notifications</div>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="DISPLAY_CHAT">
                        <span>Afficher le chat</span>
                    </label>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="DISPLAY_NOTIFICATIONS">
                        <span>Afficher les notifications système</span>
                    </label>

                    <div class="settingsSectionTitle">Fenêtres</div>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="DISPLAY_WINDOW_BACKGROUND">
                        <span>Afficher le fond des fenêtres</span>
                    </label>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="ALWAYS_DRAGGABLE_WINDOWS">
                        <span>Toujours pouvoir déplacer les fenêtres</span>
                    </label>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="PRELOAD_USER_SHIPS">
                        <span>Pré-charger les vaisseaux des autres joueurs</span>
                    </label>
                </div>
            </div>

            <!-- Onglet SON -->
            <div class="settingsTabPage" data-tab="sound">
                <div class="settingsScroll doScrollArea">
                    <div class="settingsSectionTitle">Audio</div>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="PLAY_MUSIC">
                        <span>Musique de fond</span>
                    </label>

                    <label class="settingsRow">
                        <input type="checkbox" data-setting-key="PLAY_SFX">
                        <span>Effets sonores</span>
                    </label>

                    <div class="settingsSectionTitle">Volume</div>
                    <div class="settingsRow">
                        <span>Volume effets sonores</span>
                        <div class="doSlider" id="sfxVolumeSlider">
                            <div class="doSliderTrack">
                                <div class="doSliderThumb"></div>
                            </div>
                        </div>
                        <span id="sfxVolumeLabel">100%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="settingsFooter">
            <button class="doButton" id="settingsBtnApply">Appliquer</button>
            <button class="doButton" id="settingsBtnClose">Fermer</button>
        </div>
    `;
    document.body.appendChild(win);

    // --- RÉFÉRENCES ---
    const closeX   = document.getElementById('settingsCloseBtn');
    const btnApply = document.getElementById('settingsBtnApply');
    const btnClose = document.getElementById('settingsBtnClose');

    const tabButtons = Array.from(win.querySelectorAll('.settingsTabBtn'));
    const tabPages   = Array.from(win.querySelectorAll('.settingsTabPage'));

    // --- GESTION DES ONGLETS ---
    function setActiveTab(tabId) {
        tabButtons.forEach(btn => {
            const isActive = (btn.dataset.tab === tabId);
            btn.classList.toggle('active', isActive);
        });
        tabPages.forEach(page => {
            const isActive = (page.dataset.tab === tabId);
            page.classList.toggle('active', isActive);
        });
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const tab = btn.dataset.tab;
            setActiveTab(tab);
        });
    });

        // --- CASES A COCHER → sendSetting + effets locaux ---
    const allCheckboxes = win.querySelectorAll('input[type="checkbox"][data-setting-key]');
    allCheckboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            const key   = cb.getAttribute('data-setting-key');
            const value = cb.checked ? 1 : 0;

            // Envoi au serveur
            if (typeof sendSetting === 'function') {
                sendSetting(key, value);
            }

            // Effets immédiats côté client pour certains réglages
            switch (key) {
                case 'DISPLAY_CHAT':
                    applyDisplayChatSetting(!!value);
                    break;

                case 'DISPLAY_NOTIFICATIONS':
                    applyDisplayNotificationsSetting(!!value);
                    break;

                case 'DISPLAY_WINDOW_BACKGROUND':
                    applyDisplayWindowBackgroundSetting(!!value);
                    break;

                default:
                    break;
            }

        });
    });


    // --- INITIALISATION DE QUELQUES CASES (celles qu'on connaît côté client) ---
    function initCheckboxState(key, checked) {
        const el = win.querySelector('input[type="checkbox"][data-setting-key="' + key + '"]');
        if (el) el.checked = !!checked;
    }

    try {
        initCheckboxState('PLAY_MUSIC',  typeof setting_play_music  !== 'undefined' ? setting_play_music  : false);
        initCheckboxState('PLAY_SFX',    typeof setting_play_sfx    !== 'undefined' ? setting_play_sfx    : false);
        initCheckboxState('SHOW_DRONES', typeof setting_show_drones !== 'undefined' ? setting_show_drones : false);
    } catch (e) {
        console.warn('Init settings checkboxes:', e);
    }

    // Appliquer l'état initial pour le chat
    const chatCheckbox = win.querySelector('input[type="checkbox"][data-setting-key="DISPLAY_CHAT"]');
    if (chatCheckbox) {
        applyDisplayChatSetting(chatCheckbox.checked);
    }

    // Appliquer l'état initial pour les notifications (log)
    const notifCheckboxes = win.querySelectorAll('input[type="checkbox"][data-setting-key="DISPLAY_NOTIFICATIONS"]');
    notifCheckboxes.forEach(cb => {
        applyDisplayNotificationsSetting(cb.checked);
    });

    // Appliquer l'état initial pour le fond des fenêtres
    const winBgCheckbox = win.querySelector('input[type="checkbox"][data-setting-key="DISPLAY_WINDOW_BACKGROUND"]');
    if (winBgCheckbox) {
        applyDisplayWindowBackgroundSetting(winBgCheckbox.checked);
    }

    // --- COMBOBOX "QUALITÉ GRAPHIQUE" ---
    const gfxQualityCombo = win.querySelector('#gfxQualityCombo');


    if (gfxQualityCombo) {
        const selectedEl = gfxQualityCombo.querySelector('.doComboBoxSelected');
        const arrowEl    = gfxQualityCombo.querySelector('.doComboBoxArrow');
        const listEl     = gfxQualityCombo.querySelector('.doComboBoxList');
        const items      = Array.from(listEl.querySelectorAll('li'));

        function setGfxQualityFromValue(value) {
            let found = false;
            items.forEach(li => {
                const v = parseInt(li.getAttribute('data-value'), 10);
                if (v === value) {
                    selectedEl.textContent = li.textContent;
                    gfxQualityCombo.setAttribute('data-current-value', String(v));
                    found = true;
                }
            });

            if (!found && items.length > 0) {
                const first = items[0];
                const v0 = parseInt(first.getAttribute('data-value'), 10);
                selectedEl.textContent = first.textContent;
                gfxQualityCombo.setAttribute('data-current-value', String(v0));
            }
        }

        // Valeur initiale : si un setting global existe, on l'utilise, sinon 2 (= Élevée)
        let initialQuality = 2;
        try {
            if (typeof setting_quality_presetting !== 'undefined') {
                initialQuality = parseInt(setting_quality_presetting, 10);
                if (isNaN(initialQuality)) initialQuality = 2;
            }
        } catch (e) {
            initialQuality = 2;
        }
        setGfxQualityFromValue(initialQuality);

        function toggleGfxCombo(open) {
            if (typeof open === 'boolean') {
                gfxQualityCombo.classList.toggle('open', open);
            } else {
                gfxQualityCombo.classList.toggle('open');
            }
        }

        selectedEl.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleGfxCombo();
        });

        arrowEl.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleGfxCombo();
        });

        items.forEach(li => {
            li.addEventListener('click', (e) => {
                e.stopPropagation();
                if (li.classList.contains('disabled')) return;

                const v = parseInt(li.getAttribute('data-value'), 10);
                setGfxQualityFromValue(v);

                // Envoi au serveur : QUALITY_PRESETTING, comme dans le SWF
                if (typeof sendSetting === 'function') {
                    sendSetting('QUALITY_PRESETTING', v);
                }

                toggleGfxCombo(false);
            });
        });

        // Fermer la liste si on clique en dehors
        document.addEventListener('click', (e) => {
            if (!gfxQualityCombo.contains(e.target)) {
                toggleGfxCombo(false);
            }
        });
    }

    // --- SLIDER "VOLUME EFFETS SONORES" ---
    const sfxSlider      = win.querySelector('#sfxVolumeSlider');
    const sfxSliderTrack = sfxSlider ? sfxSlider.querySelector('.doSliderTrack') : null;
    const sfxSliderThumb = sfxSlider ? sfxSlider.querySelector('.doSliderThumb') : null;
    const sfxLabel       = win.querySelector('#sfxVolumeLabel');

    if (sfxSlider && sfxSliderTrack && sfxSliderThumb && sfxLabel) {
        function clamp(val, min, max) {
            return val < min ? min : (val > max ? max : val);
        }

        let sfxVolume = 100;
        try {
            if (typeof setting_sfx_volume !== 'undefined') {
                const tmp = parseInt(setting_sfx_volume, 10);
                if (!isNaN(tmp)) {
                    sfxVolume = clamp(tmp, 0, 100);
                }
            }
        } catch (e) {
            sfxVolume = 100;
        }

        function updateSfxLabel() {
            sfxLabel.textContent = sfxVolume + '%';
        }

        function updateSfxThumbFromVolume() {
            const rect = sfxSliderTrack.getBoundingClientRect();
            const trackWidth = rect.width || 1;
            const ratio = sfxVolume / 100;
            const thumbWidth = 24;
            const x = ratio * (trackWidth - thumbWidth);
            sfxSliderThumb.style.left = x + 'px';
        }

        updateSfxLabel();
        setTimeout(updateSfxThumbFromVolume, 0);

        let sfxDragging = false;

        function applySfxFromClientX(clientX) {
            const rect = sfxSliderTrack.getBoundingClientRect();
            const thumbWidth = 24;
            let localX = clientX - rect.left;
            localX = clamp(localX, 0, rect.width - thumbWidth);
            const ratio = rect.width <= 0 ? 0 : (localX / (rect.width - thumbWidth));
            sfxVolume = Math.round(clamp(ratio * 100, 0, 100));

            if (typeof sendSetting === 'function') {
                sendSetting('VOLUME_SFX', sfxVolume);
            }

            updateSfxLabel();
            updateSfxThumbFromVolume();
        }

        sfxSliderThumb.addEventListener('mousedown', (e) => {
            sfxDragging = true;
            sfxSliderThumb.classList.add('down');
            e.preventDefault();
            e.stopPropagation();
        });

        sfxSliderThumb.addEventListener('mouseenter', () => {
            if (!sfxDragging) {
                sfxSliderThumb.classList.add('over');
            }
        });

        sfxSliderThumb.addEventListener('mouseleave', () => {
            if (!sfxDragging) {
                sfxSliderThumb.classList.remove('over');
            }
        });

        sfxSliderTrack.addEventListener('mousedown', (e) => {
            sfxDragging = true;
            sfxSliderThumb.classList.add('down');
            applySfxFromClientX(e.clientX);
            e.preventDefault();
            e.stopPropagation();
        });

        document.addEventListener('mousemove', (e) => {
            if (!sfxDragging) return;
            applySfxFromClientX(e.clientX);
        });

        document.addEventListener('mouseup', () => {
            if (!sfxDragging) return;
            sfxDragging = false;
            sfxSliderThumb.classList.remove('down');
            sfxSliderThumb.classList.remove('over');
        });
    }

    // --- FERMETURE FENETRE ---
    function closeSettingsWindow() {
        settingsWindowVisible = false;
        document.getElementById('settingsWindow').style.display = 'none';
    }

    btnApply.addEventListener('click', () => {
        closeSettingsWindow();
    });

    btnClose.addEventListener('click', () => {
        closeSettingsWindow();
    });

    closeX.addEventListener('click', () => {
        closeSettingsWindow();
    });
}

function applyDisplayChatSetting(enabled) {
    const chat = document.getElementById('content_chat');
    if (!chat) return;

    chat.style.display = enabled ? '' : 'none';
}

function applyDisplayNotificationsSetting(enabled) {
    const logWin = document.getElementById('gameLogWindow');
    if (!logWin) return;

    logWin.style.display = enabled ? '' : 'none';
}
function applyDisplayWindowBackgroundSetting(enabled) {
    // Liste des fenêtres “classiques” avec fond ScrollPane_upSkin
    const ids = ['gameLogWindow', 'questWindow', 'labWindow', 'settingsWindow'];
    ids.forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;

        // On mémorise le background d’origine une fois
        if (!el.getAttribute('data-bg-image')) {
            const cs = getComputedStyle(el);
            el.setAttribute('data-bg-image', cs.backgroundImage);
            el.setAttribute('data-bg-color', cs.backgroundColor);
        }

        if (enabled) {
            // On remet le background d’origine
            const img = el.getAttribute('data-bg-image');
            const col = el.getAttribute('data-bg-color');
            if (img) el.style.backgroundImage = img;
            if (col) el.style.backgroundColor = col;
        } else {
            // On enlève le fond
            el.style.backgroundImage = 'none';
            el.style.backgroundColor = 'transparent';
        }
    });
}




function toggleSettingsWindow() {
    if (!settingsWindowInitialized) {
        initSettingsWindow();
    }
    const w = document.getElementById('settingsWindow');
    if (!w) return;

    settingsWindowVisible = !settingsWindowVisible;
    w.style.display = settingsWindowVisible ? 'block' : 'none';
}

// accessible depuis la console si besoin
window.toggleSettingsWindow = toggleSettingsWindow;


	// -------------------------------------------------
    // FENETRE HTML "MISSIONS / QUETES"
    // -------------------------------------------------
 let questWindowInitialized = false;
 
    function initQuestWindow() {
    if (questWindowInitialized) return;
    questWindowInitialized = true;

    const style = document.createElement('style');
    style.innerHTML = `
        #questWindow {
            position: absolute;
            top: 120px;
            left: 50%;
            transform: translateX(-50%);
            width: 500px;
            height: 380px;

            /* ancien fond + nouveau skin ScrollPane du main.swf */
            background: rgba(0, 10, 20, 0.95);
            background-image: url('assets/spirites/log/ScrollPane_upSkin.png');
            background-repeat: repeat;
            background-size: auto;

            border: 2px solid #4a6b8c;
            color: #ccc;
            font-family: Consolas, monospace;
            font-size: 12px;
            padding: 8px;
            z-index: 1000;
            display: none;
        }

        .questHeader {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #4a6b8c;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }

        .questTitleBar {
            color: #00aaff;
            font-weight: bold;
            font-size: 14px;
        }

        .questClose {
            color: #ff4444;
            cursor: pointer;
            font-weight: bold;
            padding: 2px 6px;
        }

        .questBody {
            display: flex;
            height: calc(100% - 26px);
        }

        .questListPane {
            width: 170px;
            border-right: 1px solid #2a4b6c;
            padding-right: 6px;
            overflow-y: auto;
        }

        .questDetailPane {
            flex: 1;
            padding-left: 8px;
            overflow-y: auto;

            /* fond type List_skin (comme le texte du journal) */
            background-image: url('assets/spirites/log/List_skin.png');
            background-repeat: repeat;
            background-size: auto;
        }

        #questList {
            list-style: none;
            margin: 0;
            padding: 0;
        }

                /* Ligne de quête = CellRenderer_upSkin par défaut */
        #questList li {
            padding: 3px 4px;
            cursor: pointer;
            color: #aaa;
            border-bottom: 1px solid #1a2b3c;
            background-image: url('assets/spirites/DefineSprite_124_CellRenderer_upSkin_CellRenderer_upSkin/1.png');
            background-repeat: repeat-x;
            background-size: 100% 100%;
        }

        /* Survol = CellRenderer_overSkin */
        #questList li:hover {
            background-image: url('assets/spirites/DefineSprite_130_CellRenderer_overSkin_CellRenderer_overSkin/1.png');
        }

        /* Clic maintenu = CellRenderer_downSkin */
        #questList li:active {
            background-image: url('assets/spirites/DefineSprite_128_CellRenderer_downSkin_CellRenderer_downSkin/1.png');
        }

        /* Quête active = CellRenderer_selectedUpSkin */
        #questList li.activeQuest {
            color: #00aaff;
            font-weight: bold;
            background-image: url('assets/spirites/DefineSprite_138_CellRenderer_selectedUpSkin_CellRenderer_selectedUpSkin/1.png');
        }

        /* Quête active + survol = CellRenderer_selectedOverSkin */
        #questList li.activeQuest:hover {
            background-image: url('assets/spirites/DefineSprite_136_CellRenderer_selectedOverSkin_CellRenderer_selectedOverSkin/1.png');
        }
		
		        /* Quête active + clic (down) = CellRenderer_selectedDownSkin */
        #questList li.activeQuest:active {
            background-image: url('assets/spirites/DefineSprite_134_CellRenderer_selectedDownSkin_CellRenderer_selectedDownSkin/1.png');
        }


        /* Quête désactivée = CellRenderer_disabledSkin */
        #questList li.disabledQuest {
            cursor: default;
            color: #666;
            background-image: url('assets/spirites/DefineSprite_126_CellRenderer_disabledSkin_CellRenderer_disabledSkin/1.png');
        }

        /* Quête active mais désactivée = CellRenderer_selectedDisabledSkin */
        #questList li.disabledQuest.activeQuest {
            background-image: url('assets/spirites/DefineSprite_132_CellRenderer_selectedDisabledSkin_CellRenderer_selectedDisabledSkin/1.png');
        }


        #questDetailTitle {
            color: #00ffcc;
            font-weight: bold;
            margin-bottom: 4px;
        }

        #questDetailCategory {
            color: #999;
            margin-bottom: 8px;
        }

        #questObjectives {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        #questObjectives li {
            margin-bottom: 3px;
        }

        .questObjectiveDone {
            color: #55ff55;
        }

        .questObjectiveRunning {
            color: #ffff55;
        }

        .questObjectiveHidden {
            color: #555;
        }

        .questButtons {
            margin-top: 10px;
        }

        .questButtons button {
            margin-right: 6px;
            padding: 4px 8px;
            background: #4a6b8c;
            color: white;
            border: none;
            cursor: pointer;
        }

        .questButtons button:hover {
            background: #5a7b9c;
        }
    `;
    document.head.appendChild(style);

    const div = document.createElement('div');
    div.id = 'questWindow';
    div.innerHTML = `
        <div class="questHeader">
            <span class="questTitleBar">Missions / Quêtes</span>
            <span class="questClose" id="questCloseBtn">X</span>
        </div>
        <div class="questBody">
            <div class="questListPane">
                <ul id="questList"></ul>
            </div>
            <div class="questDetailPane">
                <div id="questDetailTitle">Aucune quête sélectionnée</div>
                <div id="questDetailCategory"></div>
                <ul id="questObjectives"></ul>
                <div class="questButtons">
                    <button id="questBtnAccept" class="doButton">Accepter / Continuer</button>
                    <button id="questBtnCancel"  class="doButton">Annuler</button>
                    <button id="questBtnTurnIn"  class="doButton">Valider (si terminée)</button>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(div);

    document.getElementById('questCloseBtn').addEventListener('click', () => {
        div.style.display = 'none';
    });

    const btnAccept = document.getElementById('questBtnAccept');
    const btnCancel  = document.getElementById('questBtnCancel');
    const btnTurnIn  = document.getElementById('questBtnTurnIn');

    btnAccept.addEventListener('click', () => {
        if (privilegedQuestId != null) {
            sendQuestAccept(privilegedQuestId);
        }
    });

    btnCancel.addEventListener('click', () => {
        if (privilegedQuestId != null) {
            sendQuestCancel(privilegedQuestId);
        }
    });

    btnTurnIn.addEventListener('click', () => {
        if (privilegedQuestId != null) {
            sendQuestTurnIn(privilegedQuestId);
        }
    });

    div.addEventListener('click', (ev) => {
        const li = ev.target.closest('li[data-quest-id]');
        if (!li) return;
        const id = parseInt(li.getAttribute('data-quest-id'), 10);
        if (!isNaN(id)) {
            privilegeQuestById(id);
        }
    });
}


    function toggleQuestWindow() {
        const w = document.getElementById('questWindow');
        if (!w) return;
        if (w.style.display === 'none' || !w.style.display) {
            w.style.display = 'block';
            renderQuestWindow();
        } else {
            w.style.display = 'none';
        }
    }

    function renderQuestWindow() {
        const w = document.getElementById('questWindow');
        if (!w || w.style.display === 'none') return;

        const listUl = document.getElementById('questList');
        const detailTitle = document.getElementById('questDetailTitle');
        const detailCat = document.getElementById('questDetailCategory');
        const objectivesUl = document.getElementById('questObjectives');

        if (!listUl || !detailTitle || !objectivesUl) return;

        listUl.innerHTML = "";
        objectivesUl.innerHTML = "";

        const ids = Object.keys(quests).map(x => parseInt(x, 10)).sort((a, b) => a - b);

        if (ids.length === 0) {
            detailTitle.textContent = "Aucune quête disponible";
            detailCat.textContent = "";
            return;
        }

        if (privilegedQuestId == null || !quests[privilegedQuestId]) {
            privilegedQuestId = ids[0];
        }

        for (const id of ids) {
            const q = quests[id];
            const li = document.createElement('li');
            li.setAttribute('data-quest-id', id.toString());
            li.textContent = q.title || ("Quête " + id);

            if (id === privilegedQuestId) {
                li.classList.add('activeQuest');
            }
            listUl.appendChild(li);
        }

        const activeQuest = quests[privilegedQuestId];
        if (!activeQuest) {
            detailTitle.textContent = "Aucune quête sélectionnée";
            detailCat.textContent = "";
            return;
        }

        detailTitle.textContent = activeQuest.title || ("Quête " + activeQuest.id);
        detailCat.textContent = "Catégorie : " + (activeQuest.category || "std");

        const condIds = Object.keys(activeQuest.flatConditions).map(x => parseInt(x, 10)).sort((a, b) => a - b);

        for (const condId of condIds) {
            const c = activeQuest.flatConditions[condId];
            const li = document.createElement('li');

            let cssClass = "";
            if (c.visibility === 0) {
                cssClass = "questObjectiveHidden";
            } else if (c.current >= c.target && c.target > 0) {
                cssClass = "questObjectiveDone";
            } else if (c.runstate) {
                cssClass = "questObjectiveRunning";
            }

            if (cssClass) li.classList.add(cssClass);

            const progress = (c.target > 0) ? `${c.current}/${c.target}` : `${c.current}`;
            li.textContent = `[#${c.id}] ${progress} (type=${c.typeKey}, mod="${c.modifier}")`;

            objectivesUl.appendChild(li);
        }
    }


    // ========================================================
    // INTERFACE SPACEBALL (Scoreboard) - Avec bouton Fermer
    // ========================================================
    function initSpaceballHUD() {
        const style = document.createElement('style');
        style.innerHTML = `
            #sbWindow {
                position: absolute; top: 10px; left: 50%; transform: translateX(-50%);
                width: 300px; height: auto;
                background: rgba(0, 0, 0, 0.8);
                border: 2px solid #444;
                color: white; font-family: Consolas, monospace; font-size: 14px;
                display: none; 
                flex-direction: column; z-index: 900;
                padding: 5px;
            }
            .sbHeader { display: flex; justify-content: space-between; border-bottom: 1px solid #555; margin-bottom: 5px; padding-bottom: 2px; }
            .sbTitle { color: #ffff00; font-weight: bold; }
            .sbClose { cursor: pointer; color: #ff4444; font-weight: bold; padding: 0 5px; }
            .sbClose:hover { color: #ff0000; background: rgba(255,255,255,0.1); }
            
            .sbRow { display: flex; justify-content: space-between; margin: 2px 0; }
            .sbMmo { color: #ff9933; }
            .sbEic { color: #00aaff; }
            .sbVru { color: #00ff00; }
            .sbInfo { font-size: 11px; color: #ccc; text-align: center; margin-top: 4px;}
        `;
        document.head.appendChild(style);

        const div = document.createElement('div');
        div.id = 'sbWindow';
        div.innerHTML = `
            <div class="sbHeader">
                <span class="sbTitle">SPACEBALL</span>
                <span class="sbClose" id="sbCloseBtn">[x]</span>
            </div>
            <div class="sbRow"><span class="sbMmo">MMO</span> <span id="sbScore1">0</span></div>
            <div class="sbRow"><span class="sbEic">EIC</span> <span id="sbScore2">0</span></div>
            <div class="sbRow"><span class="sbVru">VRU</span> <span id="sbScore3">0</span></div>
            <div class="sbInfo" id="sbStatus">En attente...</div>
        `;
        document.body.appendChild(div);

        // Gestion du clic sur la croix
        document.getElementById('sbCloseBtn').addEventListener('click', () => {
            div.style.display = 'none';
        });
    }
	// ========================================================
    // INTERFACE JOURNAL DE BORD (LOG UTILISATEUR)
    // ========================================================
   function initGameLogWindow() {
    if (document.getElementById('gameLogWindow')) return;
    const style = document.createElement('style');
    style.innerHTML += `
        /* Fenêtre du journal (style simple) */
        #gameLogWindow {
            position: absolute; top: ${WINDOW_DEFAULT_POS.log ? WINDOW_DEFAULT_POS.log.top : 10}px; left: ${WINDOW_DEFAULT_POS.log ? WINDOW_DEFAULT_POS.log.left : 10}px;
            width: 280px; height: 160px;
            background: rgba(0, 10, 20, 0.85);
            border: 1px solid #4a6b8c;
            ${UI_SPRITES.windowSide ? `border-image: url('${UI_SPRITES.windowSide}') 4 fill stretch;` : ""}
            font-family: Arial, sans-serif; font-size: 11px;
            display: flex; flex-direction: column;
            z-index: 1150; pointer-events: auto;
            box-shadow: 0 0 8px #000;
        }

        .logHeader {
            background: rgba(0,0,0,0.75);
            color: #ccc; padding: 3px 5px;
            font-weight: bold; border-bottom: 1px solid #4a6b8c;
            display: flex; justify-content: space-between; align-items:center; cursor: move;
        }
        .logClose {
            width:16px; height:16px; cursor:pointer; background:#a00; color:#fff; display:flex; align-items:center; justify-content:center;
            border:1px solid #700; font-weight:bold;
        }

        /* Zone de texte du journal */
        #logContent {
            flex: 1; overflow-y: auto; padding: 4px;
            color: #ccc;
            text-shadow: 1px 1px 0 #000;
            background: rgba(0,0,0,0.35);
            background-size: 100% 100%;
        }

        #logContent::-webkit-scrollbar { width: 4px; }
        #logContent::-webkit-scrollbar-thumb { background: #00aaff; }

        .logEntry { margin-bottom: 2px; }
        .logEntry .time { color: #666; margin-right: 4px; font-size: 10px; }
        .logEntry .text { color: #bde5ff; }
    `;
    document.head.appendChild(style);

    const logDiv = document.createElement('div');
    logDiv.id = 'gameLogWindow';
    logDiv.innerHTML = `
        <div class="logHeader" id="logHeaderBar">
            <span>JOURNAL</span>
            <span class="logClose" id="logCloseBtn">×</span>
        </div>
        <div id="logContent">
            <div class="logEntry"><span class="text">Système prêt.</span></div>
        </div>
    `;
    document.body.appendChild(logDiv);
    const closeBtn = document.getElementById('logCloseBtn');
    if (closeBtn) closeBtn.addEventListener('click', () => toggleWindow('log', false));
    const header = document.getElementById('logHeaderBar');
    if (header) makeElementDraggable(logDiv, header);
}



 
    // Fonction interne pour ajouter une ligne au journal
    function addLogEntry(text) {
        const container = document.getElementById('logContent');
        if (!container) return;

        const now = new Date();
        const timeStr = `${now.getHours().toString().padStart(2,'0')}:${now.getMinutes().toString().padStart(2,'0')}`;

        const div = document.createElement('div');
        div.className = 'logEntry';
        div.innerHTML = `<span class="time">[${timeStr}]</span> <span class="text">${text}</span>`;
        
        container.appendChild(div);
        
        // --- CORRECTION : AUTO-SCROLL ---
        // On force la barre de défilement tout en bas
        container.scrollTop = container.scrollHeight;
    }
    
    function updateSpaceballHUD(mmo, eic, vru, speed, owner) {
        const win = document.getElementById('sbWindow');
        if(!win) return;
        
        // Si on reçoit des données, on affiche la fenêtre
        win.style.display = 'flex';
        
        if (mmo !== null) document.getElementById('sbScore1').innerText = mmo;
        if (eic !== null) document.getElementById('sbScore2').innerText = eic;
        if (vru !== null) document.getElementById('sbScore3').innerText = vru;
        
        if (owner !== null || speed !== null) {
            const lbl = document.getElementById('sbStatus');
            if (owner === 0) lbl.innerText = "Balle neutre";
            else if (owner === 1) lbl.innerText = "MMO a la balle !";
            else if (owner === 2) lbl.innerText = "EIC a la balle !";
            else if (owner === 3) lbl.innerText = "VRU a la balle !";
        }
    }
    
    // Fonction utilitaire pour ajouter une ligne dans la fenêtre
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
	
	// --- SAUVEGARDE DE L'INTERFACE ---
    function saveInterfaceLayout() {
        const drawer = document.getElementById("actionDrawerContainer");
        let drawerMode = 0; // 0=Hori, 1=Grid, 2=Vert
        if (drawer) {
            if (drawer.classList.contains("grid")) drawerMode = 1;
            else if (drawer.classList.contains("vertical")) drawerMode = 2;
        }

        const layoutData = {
            // Quickbar (Canvas)
            qb: {
                x: quickbarPosition.x,
                y: quickbarPosition.y,
                mode: quickbarLayoutMode, // 0,1,2,3
                locked: quickbarLocked,
                minimized: quickbarMinimized
            },
            // Action Drawer (HTML)
            ad: {
                x: drawer ? drawer.offsetLeft : (window.innerWidth/2 - 300),
                y: drawer ? drawer.offsetTop : 450,
                mode: drawerMode,
                cat: actionDrawerCategory
            }
        };
        localStorage.setItem("andromeda_layout_v1", JSON.stringify(layoutData));
    }

    // --- CHARGEMENT DE L'INTERFACE ---
    function loadInterfaceLayout() {
        const raw = localStorage.getItem("andromeda_layout_v1");
        if (!raw) return; // Pas de sauvegarde, on garde les défauts

        try {
            const data = JSON.parse(raw);

            // 1. Appliquer Quickbar
            if (data.qb) {
                quickbarPosition.x = data.qb.x;
                quickbarPosition.y = data.qb.y;
                quickbarLayoutMode = data.qb.mode || 0;
                quickbarVertical = (quickbarLayoutMode === 1); // Important pour drawQuickbar
                quickbarLocked = data.qb.locked;
                quickbarMinimized = !!data.qb.minimized;
                quickbarInitialized = true; // Empêche le centrage auto au démarrage
            }

            // 2. Appliquer Action Drawer
            const drawer = document.getElementById("actionDrawerContainer");
            if (data.ad && drawer) {
                drawer.style.left = data.ad.x + "px";
                drawer.style.top  = data.ad.y + "px";
                drawer.style.transform = "none"; // Enlève le centrage CSS
                
                // Appliquer le mode (Horizontal/Grid/Vertical)
                drawer.classList.remove("grid", "vertical");
                if (data.ad.mode === 1) {
                    drawer.classList.add("grid");
                    drawer.style.width = "250px";
                } else if (data.ad.mode === 2) {
                    drawer.classList.add("vertical");
                    drawer.style.width = "auto";
                } else {
                    drawer.style.width = "600px";
                }

                // Catégorie active
                if (data.ad.cat) {
                    actionDrawerCategory = data.ad.cat;
                    // Mise à jour visuelle des onglets
                    const tabs = document.querySelectorAll('.adTab');
                    tabs.forEach(t => {
                        t.classList.remove('active');
                        if(t.dataset.cat === actionDrawerCategory) t.classList.add('active');
                    });
                    renderActionDrawerItems();
                }
            }

        } catch (e) {
            console.error("Erreur chargement layout:", e);
        }
    }


    // -------------------------------------------------
    // 11. BOUCLE RENDU
    // -------------------------------------------------

    let lastTime = performance.now();
    let shieldAnimTime = 0;

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

        updateLaserBeams(now);
        updateRocketAttacks(now);
        updateDamageBubbles(now);
        updateShieldBursts(now);
        updateExplosions(now);

        // Centrage caméra
        cameraX = shipX;
        cameraY = shipY;

        // --- 2. DESSIN (L'ordre est important !) ---

        // A. Fond noir (Le plus en dessous)
        ctx.fillStyle = "black";
        ctx.fillRect(0, 0, canvas.width, canvas.height);

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
        drawShieldBursts();
        drawExplosions();
        drawRocketAttacks();
        drawLaserBeams();
        drawDamageBubbles();

        // G. Interfaces (HUD, Minimap, Fenêtres)
        drawRadiationOverlay();
        drawPvpOverlay();
        
        if (window.showMinimap !== false) {
            drawMiniMap();
        }
        
        updateHtmlWindows();
        drawTargetWindow();
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
    initChatInterface();
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

})();