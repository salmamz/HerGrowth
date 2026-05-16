<?php
$host   = 'localhost';
$dbname = 'hergrowth';
$user   = 'root';
$pass   = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $stmt = $pdo->prepare("SELECT id, email, password, role FROM users WHERE email = ?");
    $stmt->execute(['admin@hergrowth.com']);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($u) {
        echo "Utilisateur trouvé :<br>";
        echo "ID : " . $u['id'] . "<br>";
        echo "Role : " . $u['role'] . "<br>";
        echo "Hash en base : " . $u['password'] . "<br>";
        
        $test_pass = 'admin123';
        if (password_verify($test_pass, $u['password'])) {
            echo "VÉRIFICATION RÉUSSIE pour 'admin123'";
        } else {
            echo "ÉCHEC DE VÉRIFICATION pour 'admin123'";
            echo "<br>Hash généré en direct : " . password_hash($test_pass, PASSWORD_DEFAULT);
        }
    } else {
        echo "Utilisateur NON TROUVÉ.";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
