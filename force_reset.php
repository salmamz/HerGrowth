<?php
$host   = 'localhost';
$dbname = 'hergrowth';
$user   = 'root';
$pass   = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    
    $email = 'admin@hergrowth.com';
    $new_pass = 'admin123';
    $hash = password_hash($new_pass, PASSWORD_DEFAULT);
    
    // Update explicitly
    $stmt = $pdo->prepare("UPDATE users SET password = ?, role = 'admin' WHERE email = ?");
    $stmt->execute([$hash, $email]);
    
    if ($stmt->rowCount() > 0) {
        echo "MOT DE PASSE MIS À JOUR POUR $email.";
    } else {
        // Try insert if not exists
        $stmt = $pdo->prepare("INSERT INTO users (prenom, nom, email, password, role, created_at) VALUES ('Admin', 'HerGrowth', ?, ?, 'admin', NOW())");
        $stmt->execute([$email, $hash]);
        echo "COMPTE CRÉÉ POUR $email.";
    }
} catch (Exception $e) {
    echo "ERREUR : " . $e->getMessage();
}
