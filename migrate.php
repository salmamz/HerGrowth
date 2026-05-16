<?php
require_once 'php/db.php';

$queries = [
    "DROP TABLE IF EXISTS avis", // On force le reset pour corriger le nom de la colonne
    "CREATE TABLE IF NOT EXISTS avis (id INT AUTO_INCREMENT PRIMARY KEY, reservation_id INT NOT NULL, coach_id INT NOT NULL, user_id INT NOT NULL, note INT, commentaire TEXT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)",
    "CREATE TABLE IF NOT EXISTS coach_dispos (id INT AUTO_INCREMENT PRIMARY KEY, coach_id INT NOT NULL, jour_semaine INT, heure_debut TIME, heure_fin TIME)",
    "CREATE TABLE IF NOT EXISTS coach_certifs (id INT AUTO_INCREMENT PRIMARY KEY, coach_id INT NOT NULL, titre VARCHAR(255), annee INT, image_url VARCHAR(255))",
    "CREATE TABLE IF NOT EXISTS messages (id INT AUTO_INCREMENT PRIMARY KEY, expediteur_id INT NOT NULL, destinataire_id INT NOT NULL, contenu TEXT NOT NULL, lu TINYINT(1) DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)",
    "CREATE TABLE IF NOT EXISTS programmes (id INT AUTO_INCREMENT PRIMARY KEY, coach_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description TEXT, prix DECIMAL(10, 2), duree_jours INT, image_url VARCHAR(255))",
    "CREATE TABLE IF NOT EXISTS abonnements (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, type VARCHAR(50) DEFAULT 'VIP', statut VARCHAR(20) DEFAULT 'actif', date_expiration DATETIME, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)"
];

foreach ($queries as $q) {
    try {
        $pdo->exec($q);
        echo "Success: " . substr($q, 0, 50) . "...<br>";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
    }
}
echo "Migration terminée.";
