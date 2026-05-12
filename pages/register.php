<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inscription – HerGrowth</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
</head>
<body>

  <nav class="navbar">
    <a href="../index.html" class="logo">Her<span>Growth</span></a>
    <ul class="nav-links"><li><a href="../index.html">Accueil</a></li></ul>
    <a href="login.html" class="btn btn-outline">Connexion</a>
  </nav>

  <div class="auth-page">
    <div class="auth-card" style="max-width:560px">
      <div class="section-label">Bienvenue</div>
      <h2>Créer mon compte</h2>
      <p class="sub">Rejoins 12 000 femmes qui avancent avec HerGrowth ✨</p>

      <div class="role-tabs" id="role-tabs">
        <div class="role-tab active" data-role="user">👤 Utilisatrice</div>
        <div class="role-tab" data-role="coach">🎓 Je suis coach</div>
      </div>

      <form id="register-form" action="../php/register.php" method="POST">
        <input type="hidden" name="role" id="role-input" value="user" />

        <div class="form-row">
          <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" placeholder="Ton prénom" required />
          </div>
          <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" placeholder="Ton nom" required />
          </div>
        </div>

        <div class="form-group">
          <label for="email">Adresse e-mail</label>
          <input type="email" id="email" name="email" placeholder="toi@example.com" required />
        </div>

        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password" placeholder="Minimum 8 caractères" required minlength="8" />
        </div>

        <div class="form-group">
          <label for="password2">Confirmer le mot de passe</label>
          <input type="password" id="password2" name="password2" placeholder="••••••••" required />
        </div>

        <!-- Champs coach uniquement -->
        <div id="coach-fields" style="display:none">
          <div class="form-group">
            <label for="specialite">Domaine de coaching</label>
            <select id="specialite" name="specialite">
              <option value="">Sélectionner un domaine</option>
              <option>Sport & Bien-être</option>
              <option>Nutrition</option>
              <option>Coding & Carrière</option>
              <option>Développement personnel</option>
            </select>
          </div>
          <div class="form-group">
            <label for="bio">Présentation courte</label>
            <textarea id="bio" name="bio" placeholder="Décris ton parcours et ta spécialité..."></textarea>
          </div>
          <div class="form-group">
            <label for="prix">Tarif horaire (€)</label>
            <input type="number" id="prix" name="prix" placeholder="ex : 50" min="10" max="300" />
          </div>
        </div>

        <div class="form-group">
          <label for="objectif">Ton objectif principal</label>
          <select id="objectif" name="objectif">
            <option value="">Sélectionner un objectif</option>
            <option>Perdre du poids / remise en forme</option>
            <option>Apprendre à coder / reconversion</option>
            <option>Mieux manger / rééquilibrage</option>
            <option>Gagner en confiance</option>
            <option>Gérer mon stress</option>
            <option>Reprendre après un congé maternité</option>
          </select>
        </div>

        <div class="form-check" style="margin-bottom:24px">
          <input type="checkbox" id="cgu" name="cgu" required />
          <label for="cgu">J'accepte les <a href="#" style="color:var(--rose-deep)">conditions d'utilisation</a></label>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;padding:15px">Créer mon compte</button>
      </form>

      <div class="form-link">
        Déjà un compte ? <a href="login.html">Se connecter</a>
      </div>
    </div>
  </div>

  <script src="../js/app.js"></script>
  <script>
    // Toggle coach fields
    document.querySelectorAll(".role-tab").forEach(tab => {
      tab.addEventListener("click", () => {
        document.querySelectorAll(".role-tab").forEach(t => t.classList.remove("active"));
        tab.classList.add("active");
        const role = tab.dataset.role;
        document.getElementById("role-input").value = role;
        document.getElementById("coach-fields").style.display = role === "coach" ? "block" : "none";
      });
    });
  </script>
</body>
</html>