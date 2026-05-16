<?php
$host   = 'localhost';
$dbname = 'hergrowth';
$user   = 'root';
$pass   = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $stmt = $pdo->query("DESCRIBE users");
    echo "<pre>";
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    echo "</pre>";
} catch (Exception $e) {
    echo $e->getMessage();
}
