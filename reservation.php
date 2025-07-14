<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['client_id'])) {
    header("Location: index.php");
    exit();
}

$client_id = $_SESSION['client_id'];

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$bien_id = (int)$_GET['id'];

// Vérifier bien existant
$stmt = $pdo->prepare("SELECT * FROM biens WHERE id = ?");
$stmt->execute([$bien_id]);
$bien = $stmt->fetch();

if (!$bien || $bien['statut'] !== 'location') {
    echo "Bien non disponible à la location.";
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date_debut = $_POST['date_debut'] ?? '';
    $date_fin = $_POST['date_fin'] ?? '';

    if (strtotime($date_debut) >= strtotime($date_fin)) {
        $error = "La date de fin doit être après la date de début.";
    } else {
        // Vérifier chevauchement
        $stmt = $pdo->prepare("
            SELECT * FROM reservations 
            WHERE bien_id = ? 
              AND statut != 'annulée'
              AND (
                (date_debut <= ? AND date_fin > ?) OR
                (date_debut < ? AND date_fin >= ?) OR
                (date_debut >= ? AND date_fin <= ?)
              )
        ");
        $stmt->execute([$bien_id, $date_debut, $date_debut, $date_fin, $date_fin, $date_debut, $date_fin]);
        if ($stmt->fetch()) {
            $error = "Ce bien est déjà réservé pour ces dates.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO reservations (client_id, bien_id, date_debut, date_fin, statut, date_reservation) VALUES (?, ?, ?, ?, 'en_attente', NOW())");
            if ($stmt->execute([$client_id, $bien_id, $date_debut, $date_fin])) {
                header("Location: profile.php");
                exit();
            } else {
                $error = "Erreur lors de la création de la réservation.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réserver <?= htmlspecialchars($bien['titre']); ?> | ImmoPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">

<div class="container">
    <h1>Réserver : <?= htmlspecialchars($bien['titre']); ?></h1>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="card p-4 shadow-sm" style="max-width: 500px;">
        <div class="mb-3">
            <label>Date de début</label>
            <input type="date" name="date_debut" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Date de fin</label>
            <input type="date" name="date_fin" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Réserver</button>
    </form>

    <br>
<hr>
  <a href="dashboard.php" class="btn btn-secondary">⬅ Retour au Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
