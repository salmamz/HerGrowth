<?php
// Configuration de la base de données
// On utilise les variables d'environnement (Docker) ou les valeurs par défaut (XAMPP)
define('DB_HOST',     getenv('DB_HOST') ?: 'localhost');
define('DB_NAME',     getenv('DB_NAME') ?: 'hergrowth');
define('DB_USER',     getenv('DB_USER') ?: 'root');
define('DB_PASSWORD', getenv('DB_PASS') ?: '');

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log($e->getMessage());
    die("Une erreur est survenue lors de la connexion à la base de données.");
}