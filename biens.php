<?php
session_start();
require_once 'config.php';

//  Pagination
$perPage = 6;
$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$offset = ($page - 1) * $perPage;

//  Filtres
$type = $_GET['type'] ?? '';
$statut = $_GET['statut'] ?? '';

$query = "SELECT * FROM biens WHERE 1";
$params = [];

if ($type) {
    $query .= " AND type = ?";
    $params[] = $type;
}
if ($statut) {
    $query .= " AND statut = ?";
    $params[] = $statut;
}

//  Total pour pagination
$totalStmt = $pdo->prepare($query);
$totalStmt->execute($params);
$totalBiens = $totalStmt->rowCount();

$query .= " ORDER BY date_publication DESC LIMIT $perPage OFFSET $offset";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$biens = $stmt->fetchAll();

$totalPages = ceil($totalBiens / $perPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Nos Biens | ImmoPlus</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background: #f0f2f5; }
    .card {
      border: none;
      overflow: hidden;
      transition: transform .3s, box-shadow .3s;
    }
    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .card-img-top { transition: transform .5s; }
    .card:hover .card-img-top { transform: scale(1.05); }
    .badge-type { background: #0d6efd; }
    .badge-statut-vente { background: #dc3545; }
    .badge-statut-location { background: #20c997; }
    footer {
      background: #343a40;
      color: #ccc;
      padding: 40px 0;
    }
    footer a { color: #ccc; text-decoration: none; }
    footer a:hover { color: #fff; }
    .social-icons i { font-size: 24px; margin: 0 10px; }
    .partners img { height: 40px; margin: 0 10px; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">
      <img src="logo.png" alt="Logo" width="40" class="me-2">
      ImmoPlus
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Accueil</a></li>
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

<div class="container my-5">
  <h1 class="mb-4">Nos Biens</h1>

  <!--  Filtres -->
  <form class="row g-3 mb-4" method="get">
    <div class="col-md-3">
      <select name="type" class="form-select">
        <option value="">-- Type --</option>
        <option value="villa" <?= $type === 'villa' ? 'selected' : ''; ?>>Villa</option>
        <option value="appartement" <?= $type === 'appartement' ? 'selected' : ''; ?>>Appartement</option>
        <option value="terrain" <?= $type === 'terrain' ? 'selected' : ''; ?>>Terrain</option>
      </select>
    </div>
    <div class="col-md-3">
      <select name="statut" class="form-select">
        <option value="">-- Statut --</option>
        <option value="vente" <?= $statut === 'vente' ? 'selected' : ''; ?>>Vente</option>
        <option value="location" <?= $statut === 'location' ? 'selected' : ''; ?>>Location</option>
      </select>
    </div>
    <div class="col-auto">
      <button type="submit" class="btn btn-primary">Filtrer</button>
      <a href="biens.php" class="btn btn-outline-secondary">Réinitialiser</a>
    </div>
  </form>

  <!--  Résultats -->
  <div class="row">
    <?php if ($biens) : ?>
      <?php foreach ($biens as $bien) : ?>
        <div class="col-md-4 mb-4">
          <div class="card h-100">
            <img src="uploads/<?= htmlspecialchars($bien['image_principale']); ?>" class="card-img-top" alt="Image Bien">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($bien['titre']); ?></h5>
              <p class="mb-2">
                <span class="badge badge-type"><?= ucfirst($bien['type']); ?></span>
                <?php if ($bien['statut'] === 'vente') : ?>
                  <span class="badge badge-statut-vente">Vente</span>
                <?php else : ?>
                  <span class="badge badge-statut-location">Location</span>
                <?php endif; ?>
              </p>
              <p class="fw-bold mb-2"><?= number_format($bien['prix'], 0, ',', ' '); ?> FCFA</p>
              <a href="detail.php?id=<?= $bien['id']; ?>" class="btn btn-outline-primary w-100">
                <i class="fas fa-info-circle me-1"></i> Voir plus
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else : ?>
      <p class="text-center">Aucun bien trouvé pour ces critères.</p>
    <?php endif; ?>
  </div>

  <!--  Pagination -->
  <?php if ($totalPages > 1) : ?>
    <nav class="mt-4">
      <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
          <li class="page-item <?= $i === $page ? 'active' : ''; ?>">
            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])); ?>">
              <?= $i; ?>
            </a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  <?php endif; ?>
</div>

<!--  Footer -->
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
    <p>&copy; <?= date('Y'); ?> ImmoPlus - Tous droits réservés.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
