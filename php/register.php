<?php
session_start();

$host = 'localhost';
$dbname = 'hergrowth';
$user = 'root';
$pass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom    = trim($_POST['prenom']    ?? '');
    $nom       = trim($_POST['nom']       ?? '');
    $email     = trim($_POST['email']     ?? '');
    $password  =      $_POST['password']  ?? '';
    $password2 =      $_POST['password2'] ?? '';
    $role      =      $_POST['role']      ?? 'user';

    if ($password !== $password2) {
        exit('Les mots de passe ne correspondent pas.');
    }

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verif email
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            exit('Cet email est déjà utilisé.');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (prenom, nom, email, password, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
        $stmt->execute([$prenom, $nom, $email, $hash, $role]);

        $userId = $pdo->lastInsertId();

        // Si coach, ajouter à la table coachs (en attente de validation)
        if ($role === 'coach') {
            $stmt = $pdo->prepare('INSERT INTO coachs (user_id, nom, specialite, domaine, prix, bio, valide, created_at) VALUES (?, ?, ?, ?, ?, ?, 0, NOW())');
            $stmt->execute([$userId, $prenom . ' ' . $nom, 'Nouvelle Coach', 'Perso', 50, 'En attente de validation de profil.', 0]);
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['prenom']  = $prenom;
        $_SESSION['role']    = $role;

        header('Location: ../pages/dashboard.php');
        exit;

    } catch (PDOException $e) {
        exit('Erreur BDD : ' . $e->getMessage());
    }
}
?>
