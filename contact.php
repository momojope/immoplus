<?php
session_start();
require_once 'config.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if ($nom && filter_var($email, FILTER_VALIDATE_EMAIL) && $message) {
        $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
        if ($stmt->execute([$nom, $email, $message])) {
            $success = "Votre message a bien été envoyé. Merci !";
        } else {
            $error = "Une erreur s'est produite, veuillez réessayer.";
        }
    } else {
        $error = "Tous les champs sont requis et l'email doit être valide.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Contact | ImmoPlus</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; }
    .contact-section { padding: 60px 0; }
    .contact-info i { font-size: 20px; color: #007bff; margin-right: 10px; }
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
      <img src="logo.png" alt="Logo ImmoPlus" width="40" class="me-2"> ImmoPlus
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="biens.php">Biens</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">À propos</a></li>
        <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="profile.php">Profil</a></li>
        <li class="nav-item">
          <a href="logout.php" class="btn btn-light text-primary ms-3">Déconnexion</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Contact -->
<section class="contact-section">
  <div class="container">
    <h1 class="mb-4">Contactez-nous</h1>
    <div class="row">
      <div class="col-md-6 mb-4">
        <h5>Nos coordonnées</h5>
        <p class="contact-info"><i class="fas fa-map-marker-alt"></i> 123 Avenue Immo, Dakar, Sénégal</p>
        <p class="contact-info"><i class="fas fa-phone"></i> +221 77 123 45 67</p>
        <p class="contact-info"><i class="fas fa-envelope"></i> contact@immoplus.sn</p>

        <div class="mt-4">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d123488.80135179509!2d-17.54822780236748!3d14.711175940337776!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xec172f5b3c5bb71%3A0xb17c17d92d5db21f!2sDakar!5e0!3m2!1sfr!2ssn!4v1751598150194!5m2!1sfr!2ssn"
            width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>

      <div class="col-md-6">
        <h5>Envoyez-nous un message</h5>
        <form method="post">
          <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" value="<?= isset($nom) ? htmlspecialchars($nom) : '' ?>" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Adresse Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
          </div>
          <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" name="message" rows="5" required><?= isset($message) ? htmlspecialchars($message) : '' ?></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Toasts -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
  <?php if ($success): ?>
    <div class="toast align-items-center text-bg-success show" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body"><?= htmlspecialchars($success); ?></div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  <?php elseif ($error): ?>
    <div class="toast align-items-center text-bg-danger show" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body"><?= htmlspecialchars($error); ?></div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  <?php endif; ?>
</div>

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
      <img src="whatsapp.png" alt="WhatsApp">
      <img src="youtube.png" alt="YouTube">
      <img src="assets/img/partner3.png" alt="Partenaire">
    </div>
    <p class="mb-0">&copy; <?= date('Y'); ?> ImmoPlus — Tous droits réservés.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
