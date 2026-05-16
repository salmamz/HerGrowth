<?php
session_start();
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Administration – HerGrowth</title>
  <link rel="stylesheet" href="../css/style.css?v=1.1" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
</head>
<body>

  <nav class="navbar">
    <div class="container nav-inner">
      <a href="../index.php" class="logo">Her<span>Growth</span></a>
      <ul class="nav-links">
        <li><a href="#stats">Stats</a></li>
        <li><a href="#coachs-admin">Coachs</a></li>
        <li><a href="#reservations-admin">R&eacute;servations</a></li>
      </ul>
      <div class="nav-actions">
        <span style="font-size:13px;color:var(--text-muted);margin-right:16px">Espace Admin</span>
        <a href="../php/logout.php" class="btn btn-outline btn-sm">D&eacute;connexion</a>
      </div>
    </div>
  </nav>

  <div class="dashboard-layout" style="padding-top:68px">
    <!-- Sidebar admin -->
    <aside class="sidebar">
      <div class="sidebar-avatar" style="background:var(--rose-light)">👩‍💼</div>
      <div class="sidebar-name">Administratrice</div>
      <div class="sidebar-role">Super Admin</div>
      <nav class="sidebar-nav">
        <a href="javascript:void(0)" onclick="showAdminSection('stats')" class="active" id="nav-stats"><span class="icon">📊</span> Tableau de bord</a>
        <a href="javascript:void(0)" onclick="showAdminSection('coachs')" id="nav-coachs"><span class="icon">👩‍🏫</span> Gestion coachs</a>
        <a href="javascript:void(0)" onclick="showAdminSection('reservations')" id="nav-reservations"><span class="icon">📅</span> R&eacute;servations</a>
        <a href="javascript:void(0)" onclick="showAdminSection('users')" id="nav-users"><span class="icon">👩‍💻</span> Utilisatrices</a>
      </nav>
    </aside>

    <!-- Contenu admin -->
    <main class="main-content">

      <!-- Stats globales -->
      <div id="admin-section-stats" class="dashboard-section">
        <div class="page-title">Tableau de bord</div>
        <div class="page-sub">Vue d'ensemble de la plateforme HerGrowth en temps r&eacute;el.</div>

        <div class="metrics">
          <div class="metric-card">
            <div class="metric-label">Utilisatrices</div>
            <div class="metric-value" id="stat-users">...</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Coachs</div>
            <div class="metric-value" id="stat-coachs">...</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">R&eacute;servations</div>
            <div class="metric-value" id="stat-resas">...</div>
          </div>
          <div class="metric-card">
            <div class="metric-label">Chiffre d'Affaires</div>
            <div class="metric-value" id="stat-revenue" style="color:var(--rose-deep)">...</div>
          </div>
        </div>

        <div class="table-card" style="margin-top:32px">
          <div class="table-header"><h3>Activit&eacute; r&eacute;cente</h3></div>
          <div style="padding:24px; text-align:center; color:var(--text-muted)">
            <p>Les graphiques de progression seront bient&ocirc;t disponibles ici.</p>
          </div>
        </div>
      </div>

      <!-- Gestion coachs -->
      <div id="admin-section-coachs" class="dashboard-section" style="display:none">
        <div class="table-card">
          <div class="table-header">
            <h3>Gestion des coachs</h3>
          </div>
          <table class="data-table">
            <thead>
              <tr>
                <th>Nom</th>
                <th>Domaine</th>
                <th>Prix</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="admin-coachs-tbody">
              <!-- Rempli par JS -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- R&eacute;servations admin -->
      <div id="admin-section-reservations" class="dashboard-section" style="display:none">
        <div class="table-card">
          <div class="table-header"><h3>Toutes les r&eacute;servations</h3></div>
          <table class="data-table">
            <thead>
              <tr><th>Cliente</th><th>Coach</th><th>Date</th><th>Statut</th><th>Prix</th><th>Actions</th></tr>
            </thead>
            <tbody id="admin-resas-tbody">
              <!-- Rempli par JS -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Utilisatrices -->
      <div id="admin-section-users" class="dashboard-section" style="display:none">
        <div class="table-card">
          <div class="table-header"><h3>Gestion des utilisatrices</h3></div>
          <table class="data-table">
            <thead>
              <tr><th>Nom</th><th>Email</th><th>Inscrite le</th><th>Role</th><th>Actions</th></tr>
            </thead>
            <tbody id="admin-users-tbody">
              <!-- Rempli par JS -->
            </tbody>
          </table>
        </div>
      </div>

    </main>
  </div>

  <script src="../js/app.js?v=1.1"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      if (typeof initAdminDashboard === 'function') initAdminDashboard();
    });
  </script>
</body>
</html>
