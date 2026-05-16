<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$prenom = $_SESSION['prenom'] ?? 'Utilisatrice';
$role   = $_SESSION['role'] ?? 'user';
$isCoach = ($role === 'coach');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mon espace &mdash; HerGrowth</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet" />
</head>
<body>

  <!-- Navbar simple -->
  <nav class="navbar">
    <div class="container nav-inner">
      <a href="../index.php" class="logo">Her<span>Growth</span></a>
      <ul class="nav-links">
        <li><a href="../index.php">Accueil</a></li>
        <li><a href="coachs.php">Coachs</a></li>
      </ul>
      <div class="nav-actions">
        <a href="../php/logout.php" class="btn btn-ghost btn-sm">D&eacute;connexion</a>
      </div>
    </div>
  </nav>

  <div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-avatar"><?php echo $isCoach ? '&#128105;&zwj;&#127810;' : '&#128105;'; ?></div>
      <div class="sidebar-name"><?php echo htmlspecialchars($prenom); ?></div>
      <div class="sidebar-role"><?php echo $isCoach ? 'Coach Certifi&eacute;e' : 'Membre HerGrowth'; ?></div>

      <nav class="sidebar-nav">
        <a href="javascript:void(0)" onclick="showSection('overview')" class="active" id="nav-overview"><span class="icon">&#127968;</span> Dashboard</a>
        <?php if ($isCoach): ?>
          <a href="javascript:void(0)" onclick="showSection('reservations')" id="nav-reservations"><span class="icon">&#128197;</span> Mes S&eacute;ances</a>
          <a href="javascript:void(0)" onclick="showSection('profile')" id="nav-profile"><span class="icon">&#128100;</span> Mon Profil</a>
        <?php else: ?>
          <a href="javascript:void(0)" onclick="showSection('reservations')" id="nav-reservations"><span class="icon">&#128197;</span> Mes R&eacute;servations</a>
          <a href="javascript:void(0)" onclick="showSection('badges')" id="nav-badges"><span class="icon">🏆</span> Badges</a>
        <a href="javascript:void(0)" onclick="showSection('bootcamps')" id="nav-bootcamps"><span class="icon">🎓</span> Bootcamps</a>
        <a href="javascript:void(0)" onclick="showSection('vip')" id="nav-vip"><span class="icon">👑</span> Programme VIP</a>
        <a href="javascript:void(0)" onclick="showSection('messages')" id="nav-messages"><span class="icon">💬</span> Messagerie</a>
        <a href="javascript:void(0)" onclick="showSection('settings')" id="nav-settings"><span class="icon">&#9881;&#65039;</span> Param&egrave;tres</a>
        <?php endif; ?>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <h1 class="page-title">Bonjour, <?php echo htmlspecialchars($prenom); ?> !</h1>
      <p class="page-sub">Heureux de te revoir. Voici le r&eacute;capitulatif de ton activit&eacute;.</p>

      <!-- Section OVERVIEW (Default) -->
      <div id="section-overview" class="dashboard-section">
        <?php if (!$isCoach): ?>
          <div class="metrics">
            <div class="metric-card">
              <div class="metric-label">S&eacute;ances totales</div>
              <div class="metric-value">12</div>
            </div>
            <div class="metric-card">
              <div class="metric-label">Heures</div>
              <div class="metric-value">10.5h</div>
            </div>
            <div class="metric-card">
              <div class="metric-label">Badges</div>
              <div class="metric-value">4</div>
            </div>
            <div class="metric-card">
              <div class="metric-label">Prochaine</div>
              <div class="metric-value" style="font-size:16px">Demain 10:00</div>
            </div>
          </div>
        <?php else: ?>
          <div class="metrics">
            <div class="metric-card">
              <div class="metric-label">Revenus</div>
              <div class="metric-value">840DT</div>
            </div>
            <div class="metric-card">
              <div class="metric-label">S&eacute;ances</div>
              <div class="metric-value">18</div>
            </div>
            <div class="metric-card">
              <div class="metric-label">Note</div>
              <div class="metric-value">4.9 &#9733;</div>
            </div>
            <div class="metric-card">
              <div class="metric-label">Messages</div>
              <div class="metric-value">3</div>
            </div>
          </div>
        <?php endif; ?>

        <div class="table-card">
          <div class="table-header">
            <h3><?php echo $isCoach ? 'Derni&egrave;res activit&eacute;s' : 'Prochaines s&eacute;ances'; ?></h3>
            <?php if (!$isCoach): ?><a href="coachs.php" class="btn btn-primary btn-sm">Nouvelle s&eacute;ance</a><?php endif; ?>
          </div>
          <table class="data-table">
            <thead>
              <tr>
                <?php if ($isCoach): ?>
                  <th>Cliente</th><th>Date</th><th>Statut</th><th>Actions</th>
                <?php else: ?>
                  <th>Coach</th><th>Date</th><th>Statut</th><th>Actions</th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody id="<?php echo $isCoach ? 'coach-resa-tbody-short' : 'resa-tbody-short'; ?>">
              <!-- Version courte pour le dashboard -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Section RESERVATIONS -->
      <div id="section-reservations" class="dashboard-section" style="display:none">
        <div class="table-card">
          <div class="table-header">
            <h3>Toutes mes s&eacute;ances</h3>
          </div>
          <table class="data-table">
            <thead>
              <tr>
                <?php if ($isCoach): ?>
                  <th>Cliente</th><th>Email</th><th>Date & Heure</th><th>Format</th><th>Statut</th><th>Prix</th><th>Actions</th>
                <?php else: ?>
                  <th>Coach</th><th>Sp&eacute;cialit&eacute;</th><th>Date & Heure</th><th>Format</th><th>Statut</th><th>Prix</th><th>Actions</th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody id="<?php echo $isCoach ? 'coach-resa-tbody' : 'resa-tbody'; ?>">
              <!-- Inject&eacute; par JS -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Section BADGES (User only) -->
      <?php if (!$isCoach): ?>
      <div id="section-badges" class="dashboard-section" style="display:none">
        <div class="badges-grid" style="margin-top:20px">
          <div class="badge-card"><div class="badge-icon">&#128170;</div><div><div class="badge-name">Motiv&eacute;e</div><div class="badge-desc">3 s&eacute;ances compl&eacute;t&eacute;es.</div></div></div>
          <div class="badge-card"><div class="badge-icon">&#127793;</div><div><div class="badge-name">En &eacute;quilibre</div><div class="badge-desc">Suivi nutritionnel activ&eacute;.</div></div></div>
          <div class="badge-card" style="opacity:0.5"><div class="badge-icon">&#127942;</div><div><div class="badge-name">Achiever</div><div class="badge-desc">Bient&ocirc;t disponible.</div></div></div>
        </div>
      </div>
      <?php endif; ?>

      <!-- Section MESSAGES -->
      <div id="section-messages" class="dashboard-section" style="display:none">
        <div class="table-card" style="padding:40px; text-align:center; color:var(--text-muted)">
          <div style="font-size:40px; margin-bottom:16px">&#128172;</div>
          <h3>Ta messagerie HerGrowth</h3>
          <p>Bient&ocirc;t, tu pourras discuter en direct avec tes coachs ici.</p>
        </div>
      </div>

      <!-- Section SETTINGS -->
      <div id="section-settings" class="dashboard-section" style="display:none">
        <div class="auth-card" style="max-width:100%; box-shadow:none; border:1px solid var(--border)">
          <h3>Param&egrave;tres du compte</h3>
          <div class="form-group" style="margin-top:20px">
            <label>Pr&eacute;nom</label>
            <input type="text" value="<?php echo htmlspecialchars($prenom); ?>" />
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" />
          </div>
          <button class="btn btn-primary">Enregistrer les modifications</button>
        </div>
      </div>

      <!-- Section: Bootcamps -->
      <div id="section-bootcamps" class="dashboard-section" style="display:none">
        <div class="section-header">
          <h2>🎓 Formations Intensives</h2>
          <p>Boostez vos compétences avec nos programmes accélérés.</p>
        </div>
        <div class="grid" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap:24px">
          <div class="card" style="background:white; padding:20px; border-radius:16px; border:1px solid var(--border)">
            <div style="background:#FFE4E6; height:140px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:40px; margin-bottom:16px">💻</div>
            <h3>Bootcamp Coding 7 jours</h3>
            <p style="color:var(--text-muted); font-size:14px; margin:12px 0">Apprenez les bases du web avec une coach dédiée. Certification à la fin.</p>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:20px">
              <span style="font-weight:bold; font-size:18px">199DT</span>
              <button class="btn btn-primary btn-sm">S'inscrire</button>
            </div>
          </div>
          <div class="card" style="background:white; padding:20px; border-radius:16px; border:1px solid var(--border)">
            <div style="background:#F0FDF4; height:140px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:40px; margin-bottom:16px">🥗</div>
            <h3>Challenge Perte de poids 30j</h3>
            <p style="color:var(--text-muted); font-size:14px; margin:12px 0">Un plan complet et un suivi quotidien. Revenez au top de votre forme.</p>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:20px">
              <span style="font-weight:bold; font-size:18px">99DT</span>
              <button class="btn btn-primary btn-sm">S'inscrire</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Section: VIP -->
      <div id="section-vip" class="dashboard-section" style="display:none">
        <div class="section-header">
          <h2>👑 Programme VIP Exclusive</h2>
          <p>Accédez au meilleur de HerGrowth pour un abonnement mensuel.</p>
        </div>
        <div class="vip-card" style="background: linear-gradient(135deg, #1e1b4b, #312e81); color: white; padding: 40px; border-radius: 24px; text-align: center; max-width: 500px; margin: 0 auto;">
          <div style="font-size: 48px; margin-bottom: 16px;">✨</div>
          <h3 style="font-size: 24px; margin-bottom: 24px;">Tier Premium</h3>
          <ul style="list-style: none; padding: 0; margin: 0 0 32px 0; line-height: 2.5; text-align: left; display: inline-block;">
            <li>✓ Coach réservée en priorité</li>
            <li>✓ Séances illimitées</li>
            <li>✓ Contenu exclusif & Masterclasses</li>
            <li>✓ Groupe privé WhatsApp</li>
          </ul>
          <div style="font-size: 32px; font-weight: bold; margin-bottom: 24px;">49DT<span style="font-size: 16px; font-weight: normal; opacity: 0.7;">/mois</span></div>
          <button class="btn btn-white" style="width: 100%; padding: 16px;">Devenir VIP Maintenant</button>
        </div>
      </div>

    </main>
  </div>

  <script src="../js/app.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      if (typeof initDashboard === 'function' && !<?php echo $isCoach ? 'true' : 'false'; ?>) initDashboard();
      if (typeof initCoachDashboard === 'function' && <?php echo $isCoach ? 'true' : 'false'; ?>) initCoachDashboard();
    });
  </script>
</body>
</html>