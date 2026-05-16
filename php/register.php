<?php
session_start();
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom   = trim($_POST['prenom'] ?? '');
    $nom      = trim($_POST['nom'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? 'user';

    if (empty($prenom) || empty($nom) || empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'Cet email est déjà utilisé.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO users (prenom, nom, email, password, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
                $stmt->execute([$prenom, $nom, $email, $hash, $role]);

                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['prenom']  = $prenom;
                $_SESSION['role']    = $role;

                header('Location: ../pages/dashboard.php');
                exit;
            }
        } catch (PDOException $e) {
            $error = 'Erreur : ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - HerGrowth</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <a href="../index.php" style="display:inline-block;margin-bottom:24px;color:var(--text-muted)">← Retour</a>
        <h2>Rejoins HerGrowth</h2>
        <p class="sub">Crée ton compte en quelques secondes.</p>

        <?php if ($error): ?>
            <div style="background:#FEE2E2; color:#B91C1C; padding:12px; border-radius:12px; margin-bottom:24px; font-size:14px">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label>Prénom</label>
                    <input type="text" name="prenom" placeholder="Prénom" required>
                </div>
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" placeholder="Nom" required>
                </div>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="email@exemple.com" required>
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <div class="form-group">
                <label>Je suis :</label>
                <select name="role" style="width:100%; padding:14px; border-radius:12px; border:1px solid var(--border); font-family:inherit">
                    <option value="user">Une cliente</option>
                    <option value="coach">Une coach</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:12px">S'inscrire</button>
        </form>

        <p class="form-link">Déjà un compte ? <a href="login.php">Se connecter</a></p>
    </div>
</body>
</html>
