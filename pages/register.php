<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inscription — HerGrowth</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
</head>
<body>

  <nav class="navbar">
    <div class="container nav-inner">
      <a href="../index.php" class="logo">Her<span>Growth</span></a>
      <ul class="nav-links"><li><a href="../index.php">Accueil</a></li></ul>
      <a href="login.php" class="btn btn-outline btn-sm">Connexion</a>
    </div>
  </nav>

  <div class="auth-page">
    <div class="auth-card" style="max-width:600px">
      <h2>Crée ton compte</h2>
      <p class="sub">Rejoins la communauté HerGrowth et commence ton évolution.</p>

      <form action="../php/register.php" method="POST">
        <div class="form-row">
          <div class="form-group">
            <label>Prénom</label>
            <input type="text" name="prenom" placeholder="Sarra" required>
          </div>
          <div class="form-group">
            <label>Nom</label>
            <input type="text" name="nom" placeholder="Ben Slimen" required>
          </div>
        </div>

        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" placeholder="sarra@exemple.com" required>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password" placeholder="••••••••" required>
          </div>
          <div class="form-group">
            <label>Confirme le mot de passe</label>
            <input type="password" name="password2" placeholder="••••••••" required>
          </div>
        </div>

        <div class="form-group">
          <label>Je souhaite m'inscrire en tant que :</label>
          <div style="display:flex;gap:20px;margin-top:10px">
            <label style="display:flex;align-items:center;gap:8px;font-weight:400;text-transform:none;letter-spacing:0">
              <input type="radio" name="role" value="user" checked> Utilisatrice
            </label>
            <label style="display:flex;align-items:center;gap:8px;font-weight:400;text-transform:none;letter-spacing:0">
              <input type="radio" name="role" value="coach"> Coach
            </label>
          </div>
        </div>

        <div class="form-group">
          <label>Quels sont tes objectifs ? (optionnel)</label>
          <textarea name="objectif" style="width:100%;padding:12px;border-radius:12px;border:1.5px solid var(--border);min-height:80px;font-family:inherit" placeholder="Ex: Perte de poids, reconversion tech..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;margin-top:12px">Créer mon compte</button>
      </form>

      <p class="form-link">Déjà membre ? <a href="login.php">Se connecter</a></p>
    </div>
  </div>

  <footer class="footer">
    <div class="footer-bottom">© 2025 HerGrowth · Tous droits réservés.</div>
  </footer>

</body>
</html>