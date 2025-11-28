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
    let lastLabCargoSig = "";
    let lastLabPriceSig = "";

    const LAB_PRODUCTS = [
        {
            id: 11,
            code: "prometid",
            name: "Prometid",
            inputs: { prometium: 20, endurium: 10 },
            outputUnit: 1
        },
        {
            id: 12,
            code: "duranium",
            name: "Duranium",
            inputs: { terbium: 20, endurium: 10 },
            outputUnit: 1
        },
        {
            id: 13,
            code: "promerium",
            name: "Promerium",
            inputs: { prometid: 10, duranium: 10, xenomit: 1 },
            outputUnit: 1
        }
    ];

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

        const oreKeys = Object.keys(cargo);
        if (oreKeys.length === 0) {
            html += `<p style="color:#888;">Aucun minerai reçu du serveur pour l'instant.</p>`;
        }

        for (const key of oreKeys) {
            const count = cargo[key];
            const price = prices[key] || 0;
            const value = count * price;
            const isSellable = count > 0 && price > 0;
            const sellLabel = isSellable ? `Vendre (Valeur: ${value.toLocaleString()} Cr.)` : "Pas de valeur";
            const disabledAttr = isSellable ? "" : "disabled";

            html += `
                <div class="oreItem">
                    <span class="oreName">${key.toUpperCase()} :</span>
                    <span class="oreCount">${count.toLocaleString()}</span>
                    <button class="btnAction btnSellOre" data-ore="${key}" data-amount="all" ${disabledAttr}>${sellLabel}</button>
                </div>
            `;
        }

        container.innerHTML = html;

        // --- Logique d'envoi VENDRE ---
        container.querySelectorAll('.btnSellOre').forEach(button => {
            button.addEventListener('click', (e) => {
                const oreType = e.currentTarget.getAttribute('data-ore');
                const amount = cargo[oreType]; // Vendre tout le stock pour cet ore
                if (amount > 0) {
                    sendSellOre(oreType, amount);
                }
            });
        });
    }

    // Affiche l'interface de production (raffinage)
    function displayRefineView(container) {
        const cargo = window.oreCargo || {};

        const productCards = LAB_PRODUCTS.map(prod => {
            const canBuildOnce = hasEnoughFor(prod, 1, cargo);
            const maxAmount = computeMaxCraft(prod, cargo);
            const buttonDisabled = maxAmount <= 0 ? "disabled" : "";
            const inputs = Object.entries(prod.inputs)
                .map(([ore, count]) => `${count} ${ore.toUpperCase()}`)
                .join(' + ');

            return `
                <div class="oreItem">
                    <div class="oreName">${prod.name}</div>
                    <div class="oreCount">Recette : ${inputs}</div>
                    <div style="display:flex; gap:8px; align-items:center; margin-top:6px;">
                        <input type="number" class="refineAmount" data-prod="${prod.id}" value="${canBuildOnce ? Math.min(100, maxAmount) : 0}" min="1" max="${Math.max(1, maxAmount)}" style="width:80px;">
                        <button class="btnAction doButton btnProduce" data-prod="${prod.id}" ${buttonDisabled}>Produire</button>
                        <span style="color:${canBuildOnce ? '#0f0' : '#f66'}">${maxAmount > 0 ? `Max ${maxAmount}` : 'Ressources insuffisantes'}</span>
                    </div>
                </div>`;
        }).join('');

        container.innerHTML = `
            <h3>Production (Raffinage) :</h3>
            ${productCards}
        `;

        container.querySelectorAll('.btnProduce').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const prodId = parseInt(e.currentTarget.getAttribute('data-prod'), 10);
                const input = container.querySelector(`input.refineAmount[data-prod="${prodId}"]`);
                const amount = parseInt(input.value, 10);
                const product = LAB_PRODUCTS.find(p => p.id === prodId);
                if (!product || isNaN(amount) || amount <= 0) return;

                if (!hasEnoughFor(product, amount, cargo)) {
                    addInfoMessage("Ressources insuffisantes pour produire.");
                    return;
                }
                sendProduce(prodId, amount);
            });
        });
    }

    function hasEnoughFor(product, amount, cargo) {
        if (!product || amount <= 0) return false;
        for (const [ore, count] of Object.entries(product.inputs)) {
            if ((cargo[ore] || 0) < count * amount) {
                return false;
            }
        }
        return true;
    }

    function computeMaxCraft(product, cargo) {
        let max = Infinity;
        for (const [ore, count] of Object.entries(product.inputs)) {
            const available = cargo[ore] || 0;
            max = Math.min(max, Math.floor(available / count));
        }
        if (!isFinite(max)) return 0;
        return Math.max(0, max);
    }

    // Rafraîchit la fenêtre labo quand des données cargo/prix changent
    function refreshLabWindowIfNeeded() {
        if (!labWindowVisible) return;
        const cargoSig = JSON.stringify(window.oreCargo || {});
        const priceSig = JSON.stringify(window.orePrices || {});
        if (cargoSig !== lastLabCargoSig || priceSig !== lastLabPriceSig) {
            lastLabCargoSig = cargoSig;
            lastLabPriceSig = priceSig;
            setLabMode(labMode);
        }
    }

    setInterval(refreshLabWindowIfNeeded, 1000);
	
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
        const btnAccept = document.getElementById('questBtnAccept');
        const btnCancel = document.getElementById('questBtnCancel');
        const btnTurnIn = document.getElementById('questBtnTurnIn');

        if (!listUl || !detailTitle || !objectivesUl) return;

        listUl.innerHTML = "";
        objectivesUl.innerHTML = "";

        const ids = Object.keys(quests).map(x => parseInt(x, 10)).sort((a, b) => a - b);

        if (ids.length === 0) {
            detailTitle.textContent = "Aucune quête disponible";
            detailCat.textContent = "Acceptez des missions pour commencer";
            if (btnAccept) btnAccept.disabled = true;
            if (btnCancel) btnCancel.disabled = true;
            if (btnTurnIn) btnTurnIn.disabled = true;
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

        const questState = getQuestState(activeQuest);
        if (btnAccept) {
            btnAccept.textContent = questState.hasRunning ? "Continuer" : "Accepter";
            btnAccept.disabled = questState.hasRunning;
        }
        if (btnCancel) {
            btnCancel.disabled = !questState.hasRunning;
        }
        if (btnTurnIn) {
            btnTurnIn.disabled = !questState.readyToTurnIn;
        }

        const condIds = Object.keys(activeQuest.flatConditions).map(x => parseInt(x, 10)).sort((a, b) => a - b);

        for (const condId of condIds) {
            const c = activeQuest.flatConditions[condId];
            const li = document.createElement('li');

            let cssClass = "";
            if (c.visibility === 0) {
                cssClass = "questObjectiveHidden";
            } else if (isConditionCompleted(c)) {
                cssClass = "questObjectiveDone";
            } else if (c.runstate) {
                cssClass = "questObjectiveRunning";
            }

            if (cssClass) li.classList.add(cssClass);

            const progress = (c.target > 0) ? `${c.current}/${c.target}` : `${c.current}`;
            const description = c.description || c.modifier || `type=${c.typeKey}`;

            li.textContent = c.visibility === 0
                ? "???"
                : `[#${c.id}] ${description}${progress ? " — " + progress : ""}`;

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

