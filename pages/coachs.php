<?php
session_start();
$loggedIn = !empty($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Trouver une coach &mdash; HerGrowth</title>
  <link rel="stylesheet" href="../css/style.css?v=1.1" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar">
    <div class="container nav-inner">
      <a href="../index.php" class="logo">Her<span>Growth</span></a>
      <ul class="nav-links">
        <li><a href="../index.php">Accueil</a></li>
        <li><a href="coachs.php" class="active">Coachs</a></li>
        <?php if ($loggedIn): ?>
          <li><a href="dashboard.php">Mon espace</a></li>
        <?php endif; ?>
      </ul>
      <div class="nav-actions">
        <?php if ($loggedIn): ?>
          <a href="../php/logout.php" class="btn btn-primary btn-sm">D&eacute;connexion</a>
        <?php else: ?>
          <a href="login.php" class="btn btn-outline btn-sm">Connexion</a>
          <a href="register.php" class="btn btn-primary btn-sm">S'inscrire</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <div style="padding-top:68px;min-height:100vh;background:var(--beige)">
    <div class="container section-pad">

      <div class="section-label">Nos coachs</div>
      <h1 class="section-title">Trouve ta coach id&eacute;ale</h1>
      <p class="section-sub" style="margin-bottom:40px">Des expertes certifi&eacute;es dans plusieurs domaines pour t'accompagner vers tes objectifs.</p>

      <!-- Filtres -->
      <div class="filters-bar">
        <div class="filter-group" style="flex:2">
          <label>Rechercher</label>
          <input type="text" id="f-search" placeholder="Nom, sp&eacute;cialit&eacute;..." />
        </div>
        <div class="filter-group">
          <label>Domaine</label>
          <select id="f-domaine">
            <option value="">Tous</option>
            <option value="Sport">Sport &amp; Bien-&ecirc;tre</option>
            <option value="Nutrition">Nutrition</option>
            <option value="Metier">Coding &amp; Carri&egrave;re</option>
            <option value="Developpement Perso">D&eacute;veloppement perso</option>
            <option value="Parentalite">Parentalit&eacute;</option>
          </select>
        </div>
        <div class="filter-group">
          <label>Prix</label>
          <select id="f-prix">
            <option value="">Tous</option>
            <option value="low">Moins de 45DT/h</option>
            <option value="mid">45DT - 60DT/h</option>
            <option value="high">Plus de 60DT/h</option>
          </select>
        </div>
        <div class="filter-group">
          <label>Note</label>
          <select id="f-note">
            <option value="0">Toutes</option>
            <option value="4.5">4.5+ &#9733;</option>
            <option value="4.8">4.8+ &#9733;</option>
          </select>
        </div>
        <div class="filter-group">
          <label>Mode</label>
          <select id="f-mode">
            <option value="">Tous</option>
            <option value="online">En ligne</option>
            <option value="presential">Pr&eacute;sentiel</option>
          </select>
        </div>
      </div>

      <!-- Grille de coachs -->
      <div class="coachs-grid" id="all-coachs-grid">
        <!-- Cards inject&eacute;es par JS -->
      </div>

    </div>
  </div>

  <!-- Modal R&eacute;servation -->
  <div class="modal-overlay" id="booking-modal">
    <div class="modal">
      <button class="modal-close" onclick="closeBooking()">&times;</button>
      <h3>R&eacute;server une s&eacute;ance</h3>
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
        <div id="modal-coach-avatar" style="width:40px;height:40px;border-radius:10px;background:var(--rose-light);display:flex;align-items:center;justify-content:center;font-size:20px">&#129496;&zwj;&#9792;&#65039;</div>
        <div>
          <div id="modal-coach-name" style="font-weight:600">Coach Name</div>
          <div id="modal-coach-role" style="font-size:12px;color:var(--text-muted)">Sp&eacute;cialit&eacute;</div>
        </div>
        <div id="modal-coach-price" style="margin-left:auto;font-weight:600;color:var(--rose-deep)">50DT/h</div>
      </div>

      <div class="form-group">
        <label>1. Choisis une date</label>
        <div class="calendar-grid" id="cal-grid"></div>
      </div>

      <div class="form-group">
        <label>2. Choisis un cr&eacute;neau</label>
        <div class="time-slots">
          <div class="time-slot" onclick="selectTime('09:00',this)">09:00</div>
          <div class="time-slot" onclick="selectTime('10:00',this)">10:00</div>
          <div class="time-slot" onclick="selectTime('11:00',this)">11:00</div>
          <div class="time-slot" onclick="selectTime('14:00',this)">14:00</div>
          <div class="time-slot" onclick="selectTime('15:00',this)">15:00</div>
          <div class="time-slot" onclick="selectTime('16:00',this)">16:00</div>
          <div class="time-slot" onclick="selectTime('17:00',this)">17:00</div>
          <div class="time-slot" onclick="selectTime('18:00',this)">18:00</div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Type de s&eacute;ance</label>
          <select id="seance-type" style="width:100%;padding:10px;border-radius:8px;border:1.5px solid var(--border)">
            <option value="Individuelle">Individuelle</option>
            <option value="Groupe">Groupe (-40%)</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label>Un message pour la coach ? (optionnel)</label>
        <textarea style="width:100%;padding:12px;border-radius:12px;border:1.5px solid var(--border);min-height:80px;font-family:inherit" placeholder="Tes attentes, tes questions..."></textarea>
      </div>

      <button class="btn btn-primary" style="width:100%;margin-top:12px" onclick="confirmBooking()">Confirmer la r&eacute;servation</button>
    </div>
  </div>

  <footer class="footer">
    <div class="footer-bottom">&copy; 2025 HerGrowth &middot; Tous droits r&eacute;serv&eacute;s.</div>
  </footer>

  <script src="../js/app.js?v=1.1"></script>
</body>
</html>