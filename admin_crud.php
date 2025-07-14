<?php 
session_start();
require_once 'config.php';

// Vérifier admin connecté
if (!isset($_SESSION['admin_logged'])) {
    header("Location: index.php");
    exit();
}

// Compter
$nb_biens = $pdo->query("SELECT COUNT(*) FROM biens")->fetchColumn();
$nb_commandes = $pdo->query("SELECT COUNT(*) FROM commandes")->fetchColumn();
$nb_reservations = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();

// Filtre actif
$onglet = $_GET['onglet'] ?? 'biens';

// CRUD Biens
$biens = $pdo->query("SELECT * FROM biens ORDER BY date_publication DESC")->fetchAll();
if (isset($_GET['delete_bien'])) {
    $id = (int)$_GET['delete_bien'];
    $pdo->prepare("DELETE FROM biens WHERE id = ?")->execute([$id]);
    header("Location: admin_crud.php?onglet=biens");
    exit();
}

// CRUD Commandes
$stmt = $pdo->query("
    SELECT co.*, cl.nom AS client_nom, b.titre AS bien_titre
    FROM commandes co
    JOIN clients cl ON co.client_id = cl.id
    JOIN biens b ON co.bien_id = b.id
    ORDER BY co.date_commande DESC
");
$commandes = $stmt->fetchAll();

// Valider / Annuler Commande
if (isset($_GET['valider_commande'])) {
    $pdo->prepare("UPDATE commandes SET statut = 'confirmée' WHERE id = ?")->execute([(int)$_GET['valider_commande']]);
    header("Location: admin_crud.php?onglet=commandes");
    exit();
}
if (isset($_GET['annuler_commande'])) {
    $pdo->prepare("UPDATE commandes SET statut = 'annulée' WHERE id = ?")->execute([(int)$_GET['annuler_commande']]);
    header("Location: admin_crud.php?onglet=commandes");
    exit();
}

// CRUD Réservations
$stmt = $pdo->query("
    SELECT re.*, cl.nom AS client_nom, b.titre AS bien_titre
    FROM reservations re
    JOIN clients cl ON re.client_id = cl.id
    JOIN biens b ON re.bien_id = b.id
    ORDER BY re.date_reservation DESC
");
$reservations = $stmt->fetchAll();

// Valider / Annuler Réservation
if (isset($_GET['valider_reservation'])) {
    $pdo->prepare("UPDATE reservations SET statut = 'confirmée' WHERE id = ?")->execute([(int)$_GET['valider_reservation']]);
    header("Location: admin_crud.php?onglet=reservations");
    exit();
}
if (isset($_GET['annuler_reservation'])) {
    $pdo->prepare("UPDATE reservations SET statut = 'annulée' WHERE id = ?")->execute([(int)$_GET['annuler_reservation']]);
    header("Location: admin_crud.php?onglet=reservations");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Admin CRUD | ImmoPlus</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; }
    .nav-tabs .nav-link.active { background: #0d6efd; color: #fff; }
    .table img { width: 80px; height: 50px; object-fit: cover; }
    .badge { font-size: 0.9rem; }
  </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <span class="navbar-brand">ImmoPlus - Admin</span>
    <a href="logout.php" class="btn btn-outline-light">Déconnexion</a>
  </div>
</nav>

<div class="container my-5">
  <h1 class="mb-4">Tableau de Bord</h1>

  <!-- Nav Tabs -->
  <ul class="nav nav-tabs mb-4">
    <li class="nav-item">
      <a class="nav-link <?= $onglet === 'biens' ? 'active' : '' ?>" href="?onglet=biens">Biens (<?= $nb_biens ?>)</a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $onglet === 'commandes' ? 'active' : '' ?>" href="?onglet=commandes">Commandes (<?= $nb_commandes ?>)</a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $onglet === 'reservations' ? 'active' : '' ?>" href="?onglet=reservations">Réservations (<?= $nb_reservations ?>)</a>
    </li>
  </ul>

  <!-- Onglet Biens -->
  <?php if ($onglet === 'biens'): ?>
    <div class="d-flex justify-content-between mb-3">
      <h4>Gestion des Biens</h4>
      <a href="ajouter_bien.php" class="btn btn-primary">+ Ajouter</a>
    </div>
    <table class="table table-striped">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Image</th>
          <th>Titre</th>
          <th>Prix</th>
          <th>Type</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($biens as $b): ?>
          <tr>
            <td><?= $b['id'] ?></td>
            <td><img src="uploads/<?= htmlspecialchars($b['image_principale']) ?>" alt=""></td>
            <td><?= htmlspecialchars($b['titre']) ?></td>
            <td><?= number_format($b['prix'], 0, ',', ' ') ?> FCFA</td>
            <td><?= ucfirst($b['type']) ?></td>
            <td><?= ucfirst($b['statut']) ?></td>
            <td>
              <a href="editer_bien.php?id=<?= $b['id'] ?>" class="btn btn-warning btn-sm">Éditer</a>
              <a href="?delete_bien=<?= $b['id'] ?>&onglet=biens" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ?')">Supprimer</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  <!-- Onglet Commandes -->
  <?php elseif ($onglet === 'commandes'): ?>
    <h4>Gestion des Commandes</h4>
    <table class="table table-striped">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Client</th>
          <th>Bien</th>
          <th>Date</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($commandes as $co): ?>
          <tr>
            <td><?= $co['id'] ?></td>
            <td><?= htmlspecialchars($co['client_nom']) ?></td>
            <td><?= htmlspecialchars($co['bien_titre']) ?></td>
            <td><?= date('d/m/Y', strtotime($co['date_commande'])) ?></td>
            <td>
              <?php
                $s = $co['statut'];
                $badge = $s === 'en_attente' ? 'primary' : ($s === 'confirmée' ? 'success' : 'danger');
              ?>
              <span class="badge bg-<?= $badge ?>"><?= ucfirst($s) ?></span>
            </td>
            <td>
              <?php if ($s === 'en_attente'): ?>
                <a href="?valider_commande=<?= $co['id'] ?>&onglet=commandes" class="btn btn-success btn-sm">Confirmer</a>
                <a href="?annuler_commande=<?= $co['id'] ?>&onglet=commandes" class="btn btn-danger btn-sm">Annuler</a>
              <?php else: ?>
                <span class="text-muted">Aucune action</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  <!-- Onglet Réservations -->
  <?php elseif ($onglet === 'reservations'): ?>
    <h4>Gestion des Réservations</h4>
    <table class="table table-striped">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Client</th>
          <th>Bien</th>
          <th>Début</th>
          <th>Fin</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reservations as $re): ?>
          <tr>
            <td><?= $re['id'] ?></td>
            <td><?= htmlspecialchars($re['client_nom']) ?></td>
            <td><?= htmlspecialchars($re['bien_titre']) ?></td>
            <td><?= htmlspecialchars($re['date_debut']) ?></td>
            <td><?= htmlspecialchars($re['date_fin']) ?></td>
            <td>
              <?php
                $s = $re['statut'];
                $badge = $s === 'en_attente' ? 'primary' : ($s === 'confirmée' ? 'success' : 'danger');
              ?>
              <span class="badge bg-<?= $badge ?>"><?= ucfirst($s) ?></span>
            </td>
            <td>
              <?php if ($s === 'en_attente'): ?>
                <a href="?valider_reservation=<?= $re['id'] ?>&onglet=reservations" class="btn btn-success btn-sm">Confirmer</a>
                <a href="?annuler_reservation=<?= $re['id'] ?>&onglet=reservations" class="btn btn-danger btn-sm">Annuler</a>
              <?php else: ?>
                <span class="text-muted">Aucune action</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
