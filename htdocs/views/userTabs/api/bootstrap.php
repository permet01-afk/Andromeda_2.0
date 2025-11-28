<?php
/**
 * bootstrap.php
 * Ce fichier initialise la connexion à la base de données + la session joueur.
 * Il sera utilisé par tous les petits scripts "API" qu'on va créer.
 */

session_start();

// 🔹 Connexion à la base de données locale (XAMPP)
try {
    $db = new PDO(
        'mysql:host=127.0.0.1;dbname=andromeda;charset=utf8',
        'root',      // nom d’utilisateur par défaut sous XAMPP
        '',          // mot de passe vide par défaut
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die(json_encode([
        'error' => 'db_connection_failed',
        'message' => $e->getMessage()
    ]));
}

// 🔹 Vérification de la session (sécurité)
if (!isset($_SESSION['player_id'])) {
    // Pour tester en local, on peut forcer un ID temporaire
    // ⚠️ à retirer quand on sera en ligne sur le vrai jeu
    $_SESSION['player_id'] = 1;
}
