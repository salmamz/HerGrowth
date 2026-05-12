<?php
session_start();

$host = 'localhost';
$dbname = 'hergrowth';
$user = 'root';
$pass = '';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom    = trim($_POST['prenom']    ?? '');
    $nom       = trim($_POST['nom']       ?? '');
    $email     = trim($_POST['email']     ?? '');
    $password  =      $_POST['password']  ?? '';
    $password2 =      $_POST['password2'] ?? '';
    $role      =      $_POST['role']      ?? 'user';
    $objectif  = trim($_POST['objectif']  ?? '');

    if (empty($prenom) || empty($nom) || empty($email) || empty($password)) {
        $error = 'Tous les champs obligatoires doivent être remplis.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresse email invalide.';
    } elseif (strlen($password) < 8) {
        $error = 'Le mot de passe doit contenir au moins 8 caractères.';
    } elseif ($password !== $password2) {
        $error = 'Les mots de passe ne correspondent pas.';
    } else {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Vérifier unicité email
            $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'Cette adresse email est déjà utilisée.';
            } else {
                $hash = password_hash($password, PASSWORD_BCRYPT);

                $pdo->beginTransaction();

                $stmt = $pdo->prepare('INSERT INTO users (prenom, nom, email, password, role, objectif, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())');
                $stmt->execute([$prenom, $nom, $email, $hash, $role, $objectif]);
                $userId = (int)$pdo->lastInsertId();

                if ($role === 'coach') {
                    $specialite = trim($_POST['specialite'] ?? '');
                    $bio        = trim($_POST['bio']        ?? '');
                    $prix       = (int)($_POST['prix']      ?? 0);
                    $nomCoach   = $prenom . ' ' . $nom;
                    $domaine    = trim($_POST['domaine'] ?? 'Sport');

                    $stmt = $pdo->prepare('INSERT INTO coachs (user_id, nom, specialite, domaine, bio, prix, valide, created_at) VALUES (?, ?, ?, ?, ?, ?, 0, NOW())');
                    $stmt->execute([$userId, $nomCoach, $specialite, $domaine, $bio, $prix]);
                }

                $pdo->commit();

                session_regenerate_id(true);
                $_SESSION['user_id']   = $userId;
                $_SESSION['user_name'] = $prenom . ' ' . $nom;
                $_SESSION['user_role'] = $role;

                header('Location: ../pages/dashboard.html');
                exit;
            }
        } catch (PDOException $e) {
            if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
            $error = 'Erreur serveur : ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription – HerGrowth</title>
  <link rel="stylesheet" href="../css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>

<nav class="navbar">
  <a href="../index.html" class="logo">Her<span>Growth</span></a>
  <ul class="nav-links">
    <li><a href="../index.html">Accueil</a></li>
  </ul>
  <a href="login.php" class="btn btn-outline">Connexion</a>
</nav>

<div class="auth-page">
  <div class="auth-card" style="max-width:560px">

    <div class="section-label">Bienvenue</div>
    <h2>Créer mon compte</h2>
    <p class="sub">Rejoins 12 000 femmes qui avancent avec HerGrowth ✨</p>

    <?php if ($error): ?>
      <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Tabs rôle -->
    <div class="role-tabs" id="role-tabs">
      <div class="role-tab active" onclick="setRole('user',this)">👤 Utilisatrice</div>
      <div class="role-tab" onclick="setRole('coach',this)">🎓 Je suis coach</div>
    </div>

    <form method="POST" action="">
      <input type="hidden" name="role" id="role-input" value="user">

      <div class="form-row">
        <div class="form-group">
          <label>Prénom *</label>
          <input type="text" name="prenom" placeholder="Ton prénom" required
                 value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Nom *</label>
          <input type="text" name="nom" placeholder="Ton nom" required
                 value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
        </div>
      </div>

      <div class="form-group">
        <label>Adresse e-mail *</label>
        <input type="email" name="email" placeholder="toi@example.com" required
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label>Mot de passe * (min. 8 caractères)</label>
        <input type="password" name="password" placeholder="••••••••" required>
      </div>

      <div class="form-group">
        <label>Confirmer le mot de passe *</label>
        <input type="password" name="password2" placeholder="••••••••" required>
      </div>

      <!-- Champs coach -->
      <div id="coach-fields" style="display:none">
        <div class="form-group">
          <label>Domaine principal</label>
          <select name="domaine">
            <option value="Sport">Sport & Bien-être</option>
            <option value="Nutrition">Nutrition</option>
            <option value="Coding">Coding & Carrière</option>
            <option value="Perso">Développement personnel</option>
          </select>
        </div>
        <div class="form-group">
          <label>Spécialité</label>
          <input type="text" name="specialite" placeholder="ex : Yoga post-partum, React JS...">
        </div>
        <div class="form-group">
          <label>Présentation</label>
          <textarea name="bio" placeholder="Décris ton parcours et ta spécialité..."></textarea>
        </div>
        <div class="form-group">
          <label>Tarif horaire (€)</label>
          <input type="number" name="prix" placeholder="50" min="10" max="500">
        </div>
      </div>

      <div class="form-group">
        <label>Ton objectif principal</label>
        <select name="objectif">
          <option value="">Sélectionner...</option>
          <option>Perdre du poids / remise en forme</option>
          <option>Apprendre à coder / reconversion</option>
          <option>Mieux manger / rééquilibrage</option>
          <option>Gagner en confiance</option>
          <option>Gérer mon stress</option>
          <option>Reprendre après un congé maternité</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%;padding:15px;margin-top:8px">
        Créer mon compte
      </button>
    </form>

    <div class="form-link">
      Déjà un compte ? <a href="login.php">Se connecter</a>
    </div>

  </div>
</div>

<script>
function setRole(role, el) {
  document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
  document.getElementById('role-input').value = role;
  document.getElementById('coach-fields').style.display = role === 'coach' ? 'block' : 'none';
}
</script>

</body>
</html>
