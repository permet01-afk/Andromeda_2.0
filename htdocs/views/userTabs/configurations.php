<?php
// Onglet Configurations : uniquement l'UI equip_ui.html

?>

<div class="andromeda-panel">

    <div class="andromeda-panel-header">
        <h2>Pilot command hub</h2>
        <p>Andromeda — Equipment configurations</p>
    </div>

    <!-- UI d'équipement -->
    <div class="equip-ui-wrapper">
        <iframe
            src="equip_ui.html"
            class="equip-ui-frame"
            title="Equipment UI"
            loading="lazy"
        ></iframe>
    </div>

</div>

<style>
    .andromeda-panel {
        padding: 20px;
        color: #f5f7ff;
        font-family: system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
    }

    .andromeda-panel-header h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 600;
    }

    .andromeda-panel-header p {
        margin: 4px 0 16px;
        opacity: .7;
    }

    /* Conteneur de l’iframe */
    .equip-ui-wrapper {
        height: 560px; /* ajustable */
        width: 100%;
        overflow: hidden; /* empêche tout débordement horizontal */
        border-radius: 16px;
        box-shadow: 0 0 0 1px rgba(255,255,255,0.03);
        position: relative;
    }

    /* IFRAME */
    .equip-ui-frame {
        width: 100%;
        height: 100%;
        border: none;

        /* empêche le scroll horizontal */
        overflow-x: hidden !important;

        /* évite un débordement intrinsèque */
        display: block;
    }
</style>
