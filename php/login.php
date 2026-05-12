<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$host   = 'localhost';
$dbname = 'hergrowth';
$user   = 'root';
$pass   = '';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password =      $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Merci de remplir tous les champs.';
    } else {
        try {
            $pdo  = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $u = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$u || !password_verify($password, $u['password'])) {
                $error = 'Email ou mot de passe incorrect.';
            } elseif ((int)$u['is_blocked'] === 1) {
                $error = 'Compte suspendu.';
            } else {
                session_regenerate_id(true);
                $_SESSION['user_id']   = $u['id'];
                $_SESSION['user_name'] = $u['prenom'] . ' ' . $u['nom'];
                $_SESSION['user_role'] = $u['role'];

                if ($u['role'] === 'admin') {
                    header('Location: ../admin/dashboard.html');
                } else {
                    header('Location: ../pages/dashboard.html');
                }
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
  <title>Connexion – HerGrowth</title>
  <link rel="stylesheet" href="../css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>

<nav class="navbar">
  <a href="../index.html" class="logo">Her<span>Growth</span></a>
  <ul class="nav-links">
    <li><a href="../index.html">Accueil</a></li>
  </ul>
  <a href="register.php" class="btn btn-primary">S'inscrire</a>
</nav>

<div class="auth-page">
  <div class="auth-card">

    <div class="section-label">Bienvenue</div>
    <h2>Connexion</h2>
    <p class="sub">Contente de te revoir ✨</p>

    <?php if ($error): ?>
      <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="form-group">
        <label for="email">Adresse e-mail</label>
        <input type="email" id="email" name="email"
               placeholder="toi@example.com" required
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password"
               placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn btn-primary"
              style="width:100%;padding:15px;margin-top:8px">
        Se connecter
      </button>
    </form>

    <div style="margin-top:24px;padding:16px;background:#FAF6F0;
                border-radius:12px;font-size:13px;color:#8A7068;
                border:1px solid rgba(228,180,180,0.35)">
      <strong style="color:#2D1B14">Comptes de test (mot de passe : password) :</strong><br><br>
      Admin : admin@hergrowth.com<br>
      Utilisatrice : fatima@example.com<br>
      Coach : sara@hergrowth.com
    </div>

    <div class="form-link" style="text-align:center;margin-top:20px;font-size:14px">
      Pas encore de compte ? <a href="register.php" style="color:#E8879D">S'inscrire</a>
    </div>

  </div>
</div>

</body>
</html>