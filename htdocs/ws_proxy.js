// ws_proxy.js
// Petit proxy WebSocket -> TCP pour parler avec l'émulateur Andromeda

const WebSocket = require('ws');
const net = require('net');

// ⚠️ PORTS :
// - le navigateur se connecte à ws://127.0.0.1:8082
// - le proxy se connecte en TCP sur 127.0.0.1:8080 (l'émulateur)
const WS_PORT   = 8082;
const TCP_HOST  = '127.0.0.1';
const TCP_PORT  = 8080;

// Lancement du serveur WebSocket
const wss = new WebSocket.Server({ port: WS_PORT }, () => {
    console.log('========================================');
    console.log(`[Proxy] WebSocket -> TCP lancé`);
    console.log(`[Proxy] WS listening on ws://127.0.0.1:${WS_PORT}`);
    console.log(`[Proxy] Forwarding TCP to ${TCP_HOST}:${TCP_PORT}`);
    console.log('========================================');
});

// Quand un navigateur se connecte en WebSocket
wss.on('connection', (ws) => {
    console.log('\n[WS] Nouveau client connecté');

    // On ouvre une connexion TCP vers l’émulateur
    const tcp = net.createConnection({ host: TCP_HOST, port: TCP_PORT }, () => {
        console.log('[TCP] Connecté à l’émulateur.');
    });

    // ------------ WebSocket -> TCP ------------
    ws.on('message', (data) => {
        const text = data.toString();
        console.log('[WS -> TCP]', JSON.stringify(text));

        // On envoie tel quel à l’émulateur
        tcp.write(text);
    });

    // ------------ TCP -> WebSocket ------------
    tcp.on('data', (chunk) => {
        const text = chunk.toString('utf8');
        console.log('[TCP -> WS]', JSON.stringify(text));

        if (ws.readyState === WebSocket.OPEN) {
            ws.send(text);
        }
    });

    // ------------ Gestion des erreurs / fermetures ------------
    const closeBoth = (why) => {
        console.log('[CLOSE]', why);
        if (ws.readyState === WebSocket.OPEN) {
            ws.close();
        }
        if (!tcp.destroyed) {
            tcp.destroy();
        }
    };

    ws.on('close', () => closeBoth('WebSocket fermé par le client'));
    ws.on('error', (err) => {
        console.error('[WS] Erreur :', err.message);
        closeBoth('Erreur WebSocket');
    });

    tcp.on('close', () => closeBoth('Connexion TCP fermée par l’émulateur'));
    tcp.on('error', (err) => {
        console.error('[TCP] Erreur :', err.message);
        closeBoth('Erreur TCP');
    });
});
