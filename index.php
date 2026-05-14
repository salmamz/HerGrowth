<?php
session_start();
$loggedIn = !empty($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HerGrowth &mdash; Coaching 100% au f&eacute;minin</title>
  <link rel="stylesheet" href="css/style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;700&display=swap" rel="stylesheet" />
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar">
    <div class="container nav-inner">
      <a href="index.php" class="logo">Her<span>Growth</span></a>
      
      <ul class="nav-links">
        <li><a href="#domaines">Domaines</a></li>
        <li><a href="pages/coachs.php">Coachs</a></li>
        <li><a href="#comment">Comment &ccedil;a marche</a></li>
        <?php if ($loggedIn): ?>
          <li><a href="pages/dashboard.php">Mon espace</a></li>
        <?php endif; ?>
      </ul>

      <div class="nav-actions">
        <?php if ($loggedIn): ?>
          <a href="php/logout.php" class="btn btn-primary btn-sm">D&eacute;connexion</a>
        <?php else: ?>
          <a href="pages/login.php" class="btn btn-outline btn-sm">Connexion</a>
          <a href="pages/register.php" class="btn btn-primary btn-sm">S'inscrire</a>
        <?php endif; ?>
        <div class="burger" id="burger"><span></span><span></span><span></span></div>
      </div>
    </div>
  </nav>

  <!-- Hero -->
  <section class="hero section-pad container">
    <div class="hero-content">
      <div class="section-label">Coaching 100% au f&eacute;minin</div>
      <h1 class="hero-title">Deviens la femme que tu as <em>toujours</em> voulu &ecirc;tre</h1>
      <p class="hero-sub">Trouve la coach id&eacute;ale pour t'accompagner dans chaque &eacute;tape de ta vie : carri&egrave;re, sport, nutrition et &eacute;panouissement personnel.</p>
      <div class="hero-btns">
        <a href="pages/coachs.php" class="btn btn-primary">Trouver une coach</a>
        <a href="#comment" class="btn btn-ghost">Comment &ccedil;a marche ?</a>
      </div>
      <div class="hero-stats">
        <div class="stat-item"><div class="stat-num">240+</div><div class="stat-label">Coachs certifi&eacute;es</div></div>
        <div class="stat-item"><div class="stat-num">4.9 &#9733;</div><div class="stat-label">Note moyenne</div></div>
        <div class="stat-item"><div class="stat-num">12k</div><div class="stat-label">Femmes accompagn&eacute;es</div></div>
      </div>
    </div>
    <div class="hero-visual">
      <div class="coach-card"><div class="coach-avatar avatar-sport">&#129496;&zwj;&#9792;&#65039;</div><div class="coach-info"><div class="coach-name">In&egrave;s Benmoussa</div><div class="coach-domain">Sport post-partum &middot; En ligne</div></div><div class="coach-rating">&#9733; 4.9</div></div>
      <div class="coach-card"><div class="coach-avatar avatar-code">&#128187;</div><div class="coach-info"><div class="coach-name">Sara Khalil</div><div class="coach-domain">Reconversion tech &middot; Pr&eacute;sentiel</div></div><div class="coach-rating">&#9733; 5.0</div></div>
      <div class="coach-card"><div class="coach-avatar avatar-nutri">&#129367;</div><div class="coach-info"><div class="coach-name">Amira Toumi</div><div class="coach-domain">Nutrition PCOS &middot; En ligne</div></div><div class="coach-rating">&#9733; 4.8</div></div>
    </div>
  </section>

  <!-- Domaines -->
  <section class="domaines section-pad" id="domaines">
    <div class="container">
      <div class="section-label">Nos domaines</div>
      <h2 class="section-title">Une coach pour chaque<br>&eacute;tape de ta vie</h2>
      <p class="section-sub">Des programmes pens&eacute;s pour les probl&eacute;matiques r&eacute;elles des femmes, par des coachs sp&eacute;cialis&eacute;es.</p>
      <div class="domaines-grid">
        <div class="domaine-card">
          <div class="domaine-icon icon-sport">&#127947;&zwj;&#9792;&#65039;</div>
          <h3>Sport &amp; Bien-&ecirc;tre</h3>
          <p>Remise en forme, yoga, pilates, reprise post-partum &mdash; &agrave; ton rythme et selon tes objectifs.</p>
          <div class="tags"><span class="tag">Fitness</span><span class="tag">Yoga</span><span class="tag">Post-partum</span></div>
        </div>
        <div class="domaine-card">
          <div class="domaine-icon icon-nutri">&#129367;</div>
          <h3>Nutrition</h3>
          <p>R&eacute;&eacute;quilibrage alimentaire, PCOS, grossesse, m&eacute;nopause &mdash; une approche bienveillante.</p>
          <div class="tags"><span class="tag">PCOS</span><span class="tag">Grossesse</span><span class="tag">R&eacute;&eacute;quilibrage</span></div>
        </div>
        <div class="domaine-card">
          <div class="domaine-icon icon-code">&#128187;</div>
          <h3>Coding &amp; Carri&egrave;re</h3>
          <p>Reconversion tech, freelancing, leadership f&eacute;minin &mdash; des coachs qui ont v&eacute;cu le chemin.</p>
          <div class="tags"><span class="tag">Reconversion</span><span class="tag">Freelance</span><span class="tag">Tech</span></div>
        </div>
        <div class="domaine-card">
          <div class="domaine-icon icon-perso">&#129504;</div>
          <h3>D&eacute;veloppement personnel</h3>
          <p>Confiance en soi, gestion du stress, retour apr&egrave;s pause carri&egrave;re &mdash; avance sereinement.</p>
          <div class="tags"><span class="tag">Confiance</span><span class="tag">Stress</span><span class="tag">Mindset</span></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Coachs -->
  <section class="section-pad bg-beige" id="coachs">
    <div class="container">
      <div class="section-label">Nos coachs</div>
      <h2 class="section-title">Des expertes qui te <em>comprennent</em></h2>
      <div class="coachs-grid" id="coachs-grid">
        <!-- Cards inject&eacute;es par JS -->
      </div>
      <div class="center-btn">
        <a href="pages/coachs.php" class="btn btn-outline">Voir toutes les coachs</a>
      </div>
    </div>
  </section>

  <!-- Comment &ccedil;a marche -->
  <section class="section-pad bg-white" id="comment">
    <div class="container">
      <div class="section-label text-center">Comment &ccedil;a marche</div>
      <h2 class="section-title text-center">4 &eacute;tapes vers ta meilleure version</h2>
      <div class="steps">
        <div class="step"><div class="step-num">1</div><h4>Cr&eacute;e ton profil</h4><p>D&eacute;finis tes objectifs, pr&eacute;f&eacute;rences et budget pour un matching personnalis&eacute;.</p></div>
        <div class="step"><div class="step-num">2</div><h4>Trouve ta coach</h4><p>Notre algorithme te propose les coachs les plus adapt&eacute;es &agrave; ton profil.</p></div>
        <div class="step"><div class="step-num">3</div><h4>R&eacute;serve en ligne</h4><p>Choisis ton cr&eacute;neau, ton format (solo / groupe) et paye en toute s&eacute;curit&eacute;.</p></div>
        <div class="step"><div class="step-num">4</div><h4>Progresse !</h4><p>Suis tes objectifs, accumule des badges et mesure ta progression.</p></div>
      </div>
    </div>
  </section>

  <!-- Badges / Gamification -->
  <section class="section-pad bg-beige">
    <div class="container">
      <div class="section-label">Syst&egrave;me de progression</div>
      <h2 class="section-title">Tes badges, ton parcours</h2>
      <p class="section-sub">Chaque &eacute;tape franchie m&eacute;rite d'&ecirc;tre c&eacute;l&eacute;br&eacute;e.</p>
      <div class="badges-grid">
        <div class="badge-card"><div class="badge-icon">&#128170;</div><div><div class="badge-name">Motiv&eacute;e</div><div class="badge-desc">3 s&eacute;ances compl&eacute;t&eacute;es sans jamais l&acirc;cher.</div></div></div>
        <div class="badge-card"><div class="badge-icon">&#128105;&zwj;&#128187;</div><div><div class="badge-name">D&eacute;butante en code</div><div class="badge-desc">Ta premi&egrave;re s&eacute;ance coding valid&eacute;e.</div></div></div>
        <div class="badge-card"><div class="badge-icon">&#127793;</div><div><div class="badge-name">En &eacute;quilibre</div><div class="badge-desc">1 mois de suivi nutrition accompli.</div></div></div>
        <div class="badge-card"><div class="badge-icon">&#129496;&zwj;&#9792;&#65039;</div><div><div class="badge-name">Confiance retrouv&eacute;e</div><div class="badge-desc">5 s&eacute;ances d&eacute;veloppement personnel.</div></div></div>
        <div class="badge-card"><div class="badge-icon">&#127942;</div><div><div class="badge-name">Achiever</div><div class="badge-desc">Objectif mensuel atteint.</div></div></div>
        <div class="badge-card"><div class="badge-icon">&#129419;</div><div><div class="badge-name">Transformation</div><div class="badge-desc">6 mois et un objectif majeur accompli.</div></div></div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta-section">
    <div class="container text-center">
      <div class="section-label">Rejoins-nous</div>
      <h2 class="section-title" style="color:#fff">Ton &eacute;volution commence aujourd'hui</h2>
      <p class="section-sub" style="color:rgba(255,255,255,.55);margin:0 auto 44px">Rejoins 12 000 femmes qui avancent avec HerGrowth chaque semaine.</p>
      <div class="cta-actions">
        <a href="pages/register.php" class="btn btn-white">Cr&eacute;er mon compte gratuitement</a>
        <a href="pages/register.php?role=coach" class="btn btn-ghost">Devenir coach</a>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-inner">
      <div class="footer-brand">
        <a href="index.php" class="logo">Her<span>Growth</span></a>
        <p>Toutes les femmes m&eacute;ritent la meilleure version d'elles-m&ecirc;mes.</p>
      </div>
      <div class="footer-links">
        <div><h5>Plateforme</h5><a href="pages/coachs.php">Trouver une coach</a><a href="pages/register.php">S'inscrire</a><a href="pages/login.php">Connexion</a></div>
        <div><h5>Domaines</h5><a href="#">Sport &amp; Bien-&ecirc;tre</a><a href="#">Nutrition</a><a href="#">Coding &amp; Carri&egrave;re</a><a href="#">Dev. personnel</a></div>
        <div><h5>L&eacute;gal</h5><a href="#">Confidentialit&eacute;</a><a href="#">CGU</a><a href="#">Contact</a></div>
      </div>
    </div>
    <div class="footer-bottom">&copy; 2025 HerGrowth &middot; Tous droits r&eacute;serv&eacute;s.</div>
  </footer>

  <script src="js/app.js"></script>
</body>
</html>