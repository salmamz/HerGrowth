<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mon espace – HerGrowth</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
</head>
<body>

  <nav class="navbar">
    <a href="../index.html" class="logo">Her<span>Growth</span></a>
    <ul class="nav-links">
      <li><a href="../index.html">Accueil</a></li>
      <li><a href="coachs.html">Coachs</a></li>
      <li><a href="dashboard.html" class="active">Mon espace</a></li>
    </ul>
    <a href="login.html" class="btn btn-outline btn-sm">Déconnexion</a>
  </nav>

  <div class="dashboard-layout">

    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-avatar">👩‍💻</div>
      <div class="sidebar-name">Fatima El Amrani</div>
      <div class="sidebar-role">Utilisatrice · Membre depuis Juin 2025</div>
      <nav class="sidebar-nav">
        <a href="#tableau" class="active"><span class="icon">📊</span> Tableau de bord</a>
        <a href="#reservations"><span class="icon">📅</span> Mes réservations</a>
        <a href="#progression"><span class="icon">🏆</span> Ma progression</a>
        <a href="#coachs-fav"><span class="icon">💖</span> Coachs favorites</a>
        <a href="#profil"><span class="icon">👤</span> Mon profil</a>
        <a href="coachs.html"><span class="icon">🔎</span> Trouver une coach</a>
      </nav>
    </aside>

    <!-- Contenu principal -->
    <main class="main-content">

      <!-- Tableau de bord -->
      <section id="tableau">
        <div class="page-title">Bonjour Fatima 👋</div>
        <div class="page-sub">Voici un résumé de ton parcours sur HerGrowth.</div>

        <div class="metrics">
          <div class="metric-card">
            <div class="metric-label">Séances réalisées</div>
            <div class="metric-value">8</div>
            <div class="metric-delta up">↑ +2 ce mois</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Prochaine séance</div>
            <div class="metric-value" style="font-size:20px">12 Août</div>
            <div class="metric-delta" style="color:var(--text-muted)">10:00 · Inès B.</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Badges obtenus</div>
            <div class="metric-value">2</div>
            <div class="metric-delta up">↑ Nouveau badge !</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Budget dépensé</div>
            <div class="metric-value">285€</div>
            <div class="metric-delta" style="color:var(--text-muted)">Ce trimestre</div>
          </div>
        </div>
      </section>

      <!-- Réservations -->
      <section id="reservations" style="margin-top:40px">
        <div class="table-card">
          <div class="table-header">
            <h3>Mes réservations</h3>
            <a href="coachs.html" class="btn btn-primary btn-sm">+ Nouvelle réservation</a>
          </div>
          <div style="overflow-x:auto">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Coach</th>
                  <th>Domaine</th>
                  <th>Date & heure</th>
                  <th>Type</th>
                  <th>Statut</th>
                  <th>Prix</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="resa-tbody">
                <!-- Rempli par JS -->
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- Progression / Badges -->
      <section id="progression" style="margin-top:40px">
        <div class="page-title" style="font-size:22px;margin-bottom:8px">Mes badges</div>
        <p class="page-sub">Continue comme ça, 4 badges encore à débloquer !</p>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:24px" id="badge-list">
          <!-- Rempli par JS -->
        </div>

        <div style="margin-top:32px;background:var(--white);border-radius:18px;border:1px solid var(--border);padding:28px">
          <div style="font-size:15px;font-weight:500;margin-bottom:16px">Objectif en cours</div>
          <div style="color:var(--text-muted);font-size:14px;margin-bottom:12px">Compléter 10 séances de sport post-partum</div>
          <div style="background:var(--beige);border-radius:50px;height:10px;overflow:hidden">
            <div style="width:80%;height:100%;background:var(--rose-deep);border-radius:50px;transition:width .6s"></div>
          </div>
          <div style="font-size:13px;color:var(--text-muted);margin-top:8px">8 / 10 séances</div>
        </div>
      </section>

      <!-- Profil -->
      <section id="profil" style="margin-top:40px">
        <div class="page-title" style="font-size:22px;margin-bottom:24px">Mon profil</div>
        <div style="background:var(--white);border-radius:18px;border:1px solid var(--border);padding:32px;max-width:600px">
          <div class="form-row">
            <div class="form-group">
              <label>Prénom</label>
              <input type="text" value="Fatima" />
            </div>
            <div class="form-group">
              <label>Nom</label>
              <input type="text" value="El Amrani" />
            </div>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" value="fatima@example.com" />
          </div>
          <div class="form-group">
            <label>Objectif principal</label>
            <select>
              <option selected>Reprendre après congé maternité</option>
              <option>Perdre du poids / remise en forme</option>
              <option>Apprendre à coder</option>
            </select>
          </div>
          <div class="form-group">
            <label>Préférences de coaching</label>
            <select>
              <option>En ligne uniquement</option>
              <option>Présentiel uniquement</option>
              <option selected>Les deux</option>
            </select>
          </div>
          <button class="btn btn-primary" onclick="showToast('Profil mis à jour ✓')">Enregistrer les modifications</button>
        </div>
      </section>

    </main>
  </div>

  <script src="../js/app.js"></script>
</body>
</html>