<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>À propos | ImmoPlus</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <style>
    body { background: #f8f9fa; }
    .about-section { padding: 80px 0; }
    .about-section h1 { font-weight: bold; margin-bottom: 30px; }
    .about-text p { line-height: 1.8; }
    .team img { border-radius: 50%; height: 120px; width: 120px; object-fit: cover; }
    footer { background: #343a40; color: #ccc; padding: 40px 0; }
    footer a { color: #ccc; text-decoration: none; }
    footer a:hover { color: #fff; }
    .social-icons i { font-size: 24px; margin: 0 10px; }
    .partners img { height: 40px; margin: 0 10px; }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">
      <img src="logo.png" alt="Logo" width="40" class="me-2" />
      ImmoPlus
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="biens.php">Biens</a></li>
        <li class="nav-item"><a class="nav-link active" href="about.php">À propos</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="profile.php">Profil</a></li>
        <li class="nav-item">
          <a href="logout.php" class="btn btn-light text-primary ms-3">Déconnexion</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- À propos -->
<section class="about-section">
  <div class="container">
    <h1 class="text-center">Qui sommes-nous ?</h1>
    <div class="row align-items-center">
      <div class="col-md-6 about-text">
        <p>
          Bienvenue chez <strong>ImmoPlus</strong> — votre partenaire de confiance pour tous vos projets immobiliers.
          Notre mission est de vous offrir une plateforme simple, rapide et sécurisée pour trouver, acheter ou louer
          le bien immobilier de vos rêves.
        </p>
        <p>
          Depuis notre création, nous accompagnons nos clients avec professionnalisme et passion. Nous croyons en
          la transparence, l’innovation et la satisfaction client. Avec ImmoPlus, vous bénéficiez d’une équipe
          expérimentée et à votre écoute.
        </p>
        <p>
          Rejoignez-nous et vivez une nouvelle expérience de l’immobilier !
        </p>
      </div>
      <div class="col-md-6">
        <img src="a propos.jpg" alt="Notre équipe ImmoPlus" class="img-fluid rounded shadow-sm" />
      </div>
    </div>

    <h2 class="mt-5 mb-4 text-center">Nos Agents</h2>
    <div class="row text-center team">
      <div class="col-md-4 mb-4">
        <img src="agent2.jpg" alt="Jean Dupont" class="shadow" />
        <h5 class="mt-3 mb-0">Jean Dupont</h5>
        <p class="text-muted">Directeur Général</p>
      </div>
      <div class="col-md-4 mb-4">
        <img src="agent1.jpg" alt="Aminata Ndiaye" class="shadow" />
        <h5 class="mt-3 mb-0">Aminata Ndiaye</h5>
        <p class="text-muted">Responsable Commerciale</p>
      </div>
      <div class="col-md-4 mb-4">
        <img src="agent3.jpg" alt="Moussa Sow" class="shadow" />
        <h5 class="mt-3 mb-0">Moussa Sow</h5>
        <p class="text-muted">Chargé Clientèle</p>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer>
  <div class="container text-center">
    <div class="mb-3">
      <a href="#"><i class="fab fa-facebook social-icons"></i></a>
      <a href="#"><i class="fab fa-twitter social-icons"></i></a>
      <a href="#"><i class="fab fa-instagram social-icons"></i></a>
      <a href="#"><i class="fab fa-linkedin social-icons"></i></a>
    </div>
    <div class="partners mb-3">
      <img src="whatsapp.png" alt="WhatsApp" />
      <img src="youtube.png" alt="YouTube" />
      <img src="assets/img/partner3.png" alt="Partenaire" />
    </div>
    <p class="mb-0">&copy; <?= date('Y'); ?> ImmoPlus — Tous droits réservés.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
