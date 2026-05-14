<?php
session_start();

$host   = 'localhost';
$dbname = 'hergrowth';
$user   = 'root';
$pass   = '';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $u = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$u || !password_verify($password, $u['password'])) {
                $error = 'Email ou mot de passe incorrect.';
            } else {
                $_SESSION['user_id'] = $u['id'];
                $_SESSION['prenom']  = $u['prenom'];
                $_SESSION['role']    = $u['role'];
                
                header('Location: ../pages/dashboard.php');
                exit;
            }
        } catch (PDOException $e) {
            $error = 'Erreur BDD : ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - HerGrowth</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <a href="../index.php" style="display:inline-block;margin-bottom:24px;color:var(--text-muted)">← Retour</a>
        <h2>Bon retour !</h2>
        <p class="sub">Connecte-toi pour accéder à ton espace coaching.</p>

        <?php if ($error): ?>
            <div style="background:#FEE2E2; color:#B91C1C; padding:12px; border-radius:12px; margin-bottom:24px; font-size:14px">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="ton-email@exemple.com" required>
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:12px">Se connecter</button>
        </form>

        <p class="form-link">Pas encore de compte ? <a href="../pages/register.php">S'inscrire gratuitement</a></p>
    </div>
</body>
</html>