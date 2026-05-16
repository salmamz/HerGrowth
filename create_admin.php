<?php
$host   = 'localhost';
$dbname = 'hergrowth';
$user   = 'root';
$pass   = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $email = 'admin@hergrowth.com';
    $password = 'admin123';
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Supprimer l'existant pour être sûr
    $pdo->prepare("DELETE FROM users WHERE email = ?")->execute([$email]);

    $stmt = $pdo->prepare("INSERT INTO users (prenom, nom, email, password, role, created_at) VALUES ('Admin', 'HerGrowth', ?, ?, 'admin', NOW())");
    $stmt->execute([$email, $hash]);

    echo "Compte admin réinitialisé !<br>";
    echo "Email : <b>$email</b><br>";
    echo "Mot de passe : <b>$password</b><br>";
    echo "<a href='pages/login.php'>Se connecter maintenant</a>";

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
