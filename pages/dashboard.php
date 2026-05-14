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
        <a href="#" class="active"><span class="icon">&#127968;</span> Dashboard</a>
        <?php if ($isCoach): ?>
          <a href="#"><span class="icon">&#128197;</span> Mes S&eacute;ances Clients</a>
          <a href="#"><span class="icon">&#128100;</span> Mon Profil Coach</a>
        <?php else: ?>
          <a href="#"><span class="icon">&#128197;</span> Mes R&eacute;servations</a>
          <a href="#"><span class="icon">&#128142;</span> Mes Badges</a>
        <?php endif; ?>
        <a href="#"><span class="icon">&#128172;</span> Messagerie</a>
        <a href="#"><span class="icon">&#9881;&#65039;</span> Param&egrave;tres</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <h1 class="page-title">Bonjour, <?php echo htmlspecialchars($prenom); ?> !</h1>
      <p class="page-sub">Heureux de te revoir. Voici le r&eacute;capitulatif de ton activit&eacute;.</p>

      <?php if (!$isCoach): ?>
        <!-- Section Utilisatrice -->
        <div class="metrics">
          <div class="metric-card">
            <div class="metric-label">S&eacute;ances totales</div>
            <div class="metric-value">12</div>
            <div class="metric-delta up">&uarr; 2 ce mois</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Heures de coaching</div>
            <div class="metric-value">10.5h</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Badges obtenus</div>
            <div class="metric-value">4</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Prochaine s&eacute;ance</div>
            <div class="metric-value" style="font-size:18px">Demain, 10:00</div>
          </div>
        </div>

        <div class="table-card">
          <div class="table-header">
            <h3>Mes derni&egrave;res r&eacute;servations</h3>
            <a href="coachs.php" class="btn btn-primary btn-sm">R&eacute;server une s&eacute;ance</a>
          </div>
          <table class="data-table">
            <thead>
              <tr>
                <th>Coach</th>
                <th>Domaine</th>
                <th>Date &amp; Heure</th>
                <th>Format</th>
                <th>Statut</th>
                <th>Prix</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="resa-tbody">
              <!-- Inject&eacute; par JS -->
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <!-- Section Coach -->
        <div class="metrics">
          <div class="metric-card">
            <div class="metric-label">Revenus du mois</div>
            <div class="metric-value">840DT</div>
            <div class="metric-delta up">&uarr; 12% vs mois dernier</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">S&eacute;ances ce mois</div>
            <div class="metric-value">18</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Note moyenne</div>
            <div class="metric-value">4.9 &#9733;</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Nouvelles demandes</div>
            <div class="metric-value">3</div>
          </div>
        </div>

        <div class="table-card">
          <div class="table-header">
            <h3>S&eacute;ances &agrave; venir / Demandes</h3>
          </div>
          <table class="data-table">
            <thead>
              <tr>
                <th>Cliente</th>
                <th>Email</th>
                <th>Date &amp; Heure</th>
                <th>Format</th>
                <th>Statut</th>
                <th>Prix</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="coach-resa-tbody">
              <!-- Inject&eacute; par JS -->
            </tbody>
          </table>
        </div>
      <?php endif; ?>

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