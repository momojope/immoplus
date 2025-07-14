<?php
session_start();
require_once 'config.php';

// Vérifier connexion client
if (!isset($_SESSION['client_id'])) {
    header("Location: index.php");
    exit();
}

$client_id = $_SESSION['client_id'];

// Infos client
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch();

// Suppression commande
if (isset($_GET['delete_commande'])) {
    $id = (int)$_GET['delete_commande'];
    $stmt = $pdo->prepare("DELETE FROM commandes WHERE id = ? AND client_id = ?");
    $stmt->execute([$id, $client_id]);
    header("Location: profile.php");
    exit();
}

// Suppression réservation
if (isset($_GET['delete_reservation'])) {
    $id = (int)$_GET['delete_reservation'];
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ? AND client_id = ?");
    $stmt->execute([$id, $client_id]);
    header("Location: profile.php");
    exit();
}

// Commandes client
$stmt = $pdo->prepare("
    SELECT co.*, b.titre 
    FROM commandes co 
    JOIN biens b ON co.bien_id = b.id 
    WHERE co.client_id = ?
    ORDER BY co.date_commande DESC
");
$stmt->execute([$client_id]);
$commandes = $stmt->fetchAll();

// Réservations client
$stmt = $pdo->prepare("
    SELECT re.*, b.titre 
    FROM reservations re 
    JOIN biens b ON re.bien_id = b.id 
    WHERE re.client_id = ?
    ORDER BY re.date_reservation DESC
");
$stmt->execute([$client_id]);
$reservations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mon Profil | ImmoPlus</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; }
    .profile-header { background: #007bff; color: #fff; padding: 30px 20px; border-radius: 10px; }
    .table th, .table td { vertical-align: middle; }
    .badge { font-size: 0.9em; }
  </style>
</head>
<body>

<?php if (isset($_GET['paiement']) && $_GET['paiement'] === 'success'): ?>
  <div class="alert alert-success text-center mb-0 rounded-0">
    ✅ Votre paiement a été enregistré. Merci !
  </div>
<?php endif; ?>

<div class="container my-5">

  <!-- Header -->
  <div class="profile-header mb-4 text-center">
    <h1 class="mb-2">Bienvenue, <?= htmlspecialchars($client['nom']); ?> !</h1>
    <p class="mb-0">Voici vos informations personnelles et vos activités.</p>
  </div>

  <!-- Infos perso -->
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h4 class="card-title">Mes informations</h4>
      <ul class="list-group list-group-flush">
        <li class="list-group-item"><strong>Nom :</strong> <?= htmlspecialchars($client['nom']); ?></li>
        <li class="list-group-item"><strong>Email :</strong> <?= htmlspecialchars($client['email']); ?></li>
        <li class="list-group-item"><strong>Téléphone :</strong> <?= htmlspecialchars($client['telephone']); ?></li>
      </ul>
    </div>
  </div>

  <!-- Commandes -->
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h4 class="card-title mb-3">Mes Commandes</h4>
      <?php if ($commandes): ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-primary">
              <tr>
                <th>Bien</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($commandes as $commande): ?>
              <tr>
                <td><?= htmlspecialchars($commande['titre']); ?></td>
                <td><?= date('d/m/Y H:i', strtotime($commande['date_commande'])); ?></td>
                <td>
                  <?php
                    $s = $commande['statut'];
                    $badge = match($s) {
                      'en_attente' => 'primary',
                      'confirmée'  => 'warning',
                      'payée'      => 'success',
                      'annulée'    => 'danger',
                      default      => 'secondary'
                    };
                  ?>
                  <span class="badge bg-<?= $badge; ?>"><?= htmlspecialchars($s); ?></span>
                </td>
                <td>
                  <a href="profile.php?delete_commande=<?= $commande['id']; ?>"
                     onclick="return confirm('Supprimer cette commande ?')"
                     class="btn btn-sm btn-outline-danger">Supprimer</a>

                  <?php if ($s === 'confirmée'): ?>
                    <a href="payer_commande.php?id=<?= $commande['id']; ?>" class="btn btn-sm btn-success">Payer</a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-muted">Aucune commande enregistrée.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Réservations -->
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h4 class="card-title mb-3">Mes Réservations</h4>
      <?php if ($reservations): ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-primary">
              <tr>
                <th>Bien</th>
                <th>Date Début</th>
                <th>Date Fin</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($reservations as $reservation): ?>
              <tr>
                <td><?= htmlspecialchars($reservation['titre']); ?></td>
                <td><?= htmlspecialchars($reservation['date_debut']); ?></td>
                <td><?= htmlspecialchars($reservation['date_fin']); ?></td>
                <td>
                  <?php
                    $s = $reservation['statut'];
                    $badge = match($s) {
                      'en_attente' => 'primary',
                      'confirmée'  => 'warning',
                      'payée'      => 'success',
                      'annulée'    => 'danger',
                      default      => 'secondary'
                    };
                  ?>
                  <span class="badge bg-<?= $badge; ?>"><?= htmlspecialchars($s); ?></span>
                </td>
                <td>
                  <a href="profile.php?delete_reservation=<?= $reservation['id']; ?>"
                     onclick="return confirm('Annuler cette réservation ?')"
                     class="btn btn-sm btn-outline-danger">Annuler</a>

                  <?php if ($s === 'confirmée'): ?>
                    <a href="payer_reservation.php?id=<?= $reservation['id']; ?>" class="btn btn-sm btn-success">Payer</a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-muted">Aucune réservation enregistrée.</p>
      <?php endif; ?>
    </div>
  </div>

  <a href="dashboard.php" class="btn btn-secondary">⬅ Retour au Dashboard</a>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
