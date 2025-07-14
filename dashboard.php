<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['client_id'])) {
    header("Location: index.php");
    exit();
}

$client_nom = $_SESSION['client_nom'];

//  Toasts
$success = '';
$error = '';

//  Traitement Newsletter
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Adresse e-mail invalide.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM newsletters WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Cet e-mail est déjà inscrit.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO newsletters (email) VALUES (?)");
            if ($stmt->execute([$email])) {
                $success = "Merci pour votre inscription !";
            } else {
                $error = "Une erreur est survenue. Veuillez réessayer.";
            }
        }
    }
}

//  Biens récents
$stmt = $pdo->query("SELECT * FROM biens ORDER BY date_publication DESC LIMIT 4");
$biens = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mon Espace | ImmoPlus</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background: #f5f7fa; }
    .hero {
      color: black;
      padding: 100px 0;
      text-align: center;
      background: url('https://t4.ftcdn.net/jpg/04/16/02/15/240_F_416021569_rhrh8vXNHTEHilM17VWZHZ9lcRv9OTz6.jpg') no-repeat center center/cover;
    }
    .hero h1 {
      font-weight: bold;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    .biens-section {
      padding: 60px 0;
    }
    .card img {
      height: 200px;
      object-fit: cover;
    }
    .newsletter {
      background: linear-gradient(135deg, #007bff 0%, #00c3ff 100%);
      color: white;
      padding: 60px 0;
      text-align: center;
    }
    footer {
      background: #343a40;
      color: #ccc;
      padding: 40px 0;
    }
    footer a { color: #ccc; text-decoration: none; }
    footer a:hover { color: #fff; }
    .social-icons i { font-size: 24px; margin: 0 10px; }
    .partners img { height: 40px; margin: 0 15px; }
  </style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="#">
      <img src="logo.png" alt="Logo" width="60" class="me-2"> ImmoPlus
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="biens.php">Biens</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">À propos</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="profile.php">Profil</a></li>
        <li class="nav-item">
          <a href="logout.php" class="btn btn-light text-primary ms-3">Déconnexion</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero -->
<section class="hero">
  <div class="container">
    <h1>Bienvenue, <?= htmlspecialchars($client_nom); ?> 👋</h1>
    <p class="lead">Immoplus un toit pour rêver, un plus pour grandir
</p>
  </div>
</section>

<!-- Biens récents -->
<section class="biens-section">
  <div class="container">
    <h2 class="text-center mb-5">Nos Biens Récents</h2>
    <div class="row">
      <?php if ($biens) : ?>
        <?php foreach ($biens as $bien) : ?>
          <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
              <img src="uploads/<?= htmlspecialchars($bien['image_principale']); ?>" class="card-img-top" alt="Image Bien">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($bien['titre']); ?></h5>
                <p class="mb-2">
                  <span class="badge bg-primary"><?= ucfirst($bien['type']); ?></span>
                  <?php if ($bien['statut'] === 'vente') : ?>
                    <span class="badge bg-success">Vente</span>
                  <?php else : ?>
                    <span class="badge bg-warning text-dark">Location</span>
                  <?php endif; ?>
                </p>
                <p class="fw-bold"><?= number_format($bien['prix'], 0, ',', ' '); ?> FCFA</p>
                <a href="detail.php?id=<?= $bien['id']; ?>" class="btn btn-outline-primary w-100">
                  <i class="fas fa-info-circle me-1"></i> Voir plus
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
        <p>Aucun bien trouvé pour le moment.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Newsletter -->
<section class="newsletter">
  <div class="container">
    <h2>Abonnez-vous à notre Newsletter</h2>
    <p>Recevez les dernières offres et actualités directement par e-mail.</p>
    <form method="post" action="">
      <div class="input-group mb-3 justify-content-center">
        <input type="email" name="email" class="form-control w-auto" placeholder="Votre adresse e-mail" required>
        <button class="btn btn-light text-primary" type="submit">S'abonner</button>
      </div>
    </form>
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
  <?php endif; ?>
  <?php if ($error): ?>
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
      <img src="whatsapp.png" alt="Partenaire 1">
      <img src="youtube.png" alt="Partenaire 2">
      <img src="assets/img/partner3.png" alt="Partenaire 3">
    </div>
    <p>&copy; <?= date('Y'); ?> ImmoPlus - Tous droits réservés.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const toastElList = [].slice.call(document.querySelectorAll('.toast'));
  toastElList.map(function (toastEl) {
    return new bootstrap.Toast(toastEl).show();
  });
</script>

</body>
</html>
