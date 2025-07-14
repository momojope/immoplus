<?php
session_start();
require_once 'config.php';

// Vérifier connexion
if (!isset($_SESSION['client_id'])) {
    header("Location: index.php");
    exit();
}

$client_id = $_SESSION['client_id'];

// Vérifier ID commande
if (!isset($_GET['id'])) {
    header("Location: profile.php");
    exit();
}

$commande_id = (int)$_GET['id'];

// Charger commande
$stmt = $pdo->prepare("SELECT co.*, b.titre, b.prix FROM commandes co JOIN biens b ON co.bien_id = b.id WHERE co.id = ? AND co.client_id = ?");
$stmt->execute([$commande_id, $client_id]);
$commande = $stmt->fetch();

if (!$commande) {
    die("Commande introuvable.");
}

if ($commande['statut'] !== 'confirmée') {
    die("Paiement impossible. Statut actuel : " . htmlspecialchars($commande['statut']));
}

$success = false;
$error = '';

// Traitement formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['methode'])) {
    $methode = $_POST['methode'];

    if ($methode === 'carte') {
        $num_carte = trim($_POST['num_carte'] ?? '');
        $exp = trim($_POST['exp'] ?? '');
        $cvv = trim($_POST['cvv'] ?? '');
        if ($num_carte && $exp && $cvv) {
            $pdo->prepare("UPDATE commandes SET statut = 'payée' WHERE id = ?")->execute([$commande_id]);
            $success = true;
        } else {
            $error = "Veuillez remplir toutes les informations de la carte.";
        }
    } elseif ($methode === 'wave') {
        $numero_wave = trim($_POST['numero_wave'] ?? '');
        if ($numero_wave) {
            $pdo->prepare("UPDATE commandes SET statut = 'payée' WHERE id = ?")->execute([$commande_id]);
            $success = true;
        } else {
            $error = "Veuillez saisir votre numéro Wave.";
        }
    } else {
        $error = "Méthode invalide.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Payer Commande | ImmoPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .card { max-width: 500px; margin: auto; }
    </style>
</head>
<body class="py-5">
<a href="dashboard.php" class="btn btn-secondary">⬅ Retour au Dashboard</a>
<hr>
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Paiement Commande #<?= htmlspecialchars($commande['id']) ?></h5>
        </div>
        <div class="card-body">
            <h6>Bien : <?= htmlspecialchars($commande['titre']) ?></h6>
            <p>Prix : <strong><?= number_format($commande['prix'], 0, ',', ' ') ?> FCFA</strong></p>

            <?php if (!$success): ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="post" id="paymentForm">
                    <div class="mb-3">
                        <label class="form-label">Choisir une méthode :</label>
                        <select name="methode" id="methode" class="form-select" required>
                            <option value="">-- Sélectionnez --</option>
                            <option value="carte">💳 Carte Bancaire</option>
                            <option value="wave">🌊 Wave</option>
                        </select>
                    </div>

                    <!-- Carte -->
                    <div id="carteFields" class="mb-3 d-none">
                        <label>Numéro de carte</label>
                        <input type="text" name="num_carte" class="form-control mb-2" placeholder="XXXX XXXX XXXX XXXX">
                        <label>Expiration</label>
                        <input type="text" name="exp" class="form-control mb-2" placeholder="MM/YY">
                        <label>CVV</label>
                        <input type="text" name="cvv" class="form-control mb-2" placeholder="XXX">
                    </div>

                    <!-- Wave -->
                    <div id="waveFields" class="mb-3 d-none">
                        <label>Numéro Wave</label>
                        <input type="text" name="numero_wave" class="form-control" placeholder="77 XXX XX XX">
                    </div>

                    <button type="submit" class="btn btn-success w-100">Payer Maintenant</button>
                </form>
            <?php else: ?>
                <div class="toast-container position-fixed top-0 end-0 p-3">
                    <div id="successToast" class="toast align-items-center text-bg-success border-0 show" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">
                                ✅ Votre paiement a été enregistré. Merci !
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                </div>
                <a href="profile.php?paiement=success" class="btn btn-primary w-100 mt-3">Retour au profil</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const methodeSelect = document.getElementById('methode');
    const carteFields = document.getElementById('carteFields');
    const waveFields = document.getElementById('waveFields');

    methodeSelect?.addEventListener('change', function() {
        carteFields.classList.add('d-none');
        waveFields.classList.add('d-none');

        if (this.value === 'carte') {
            carteFields.classList.remove('d-none');
        } else if (this.value === 'wave') {
            waveFields.classList.remove('d-none');
        }
    });

    <?php if ($success): ?>
    const toast = new bootstrap.Toast(document.getElementById('successToast'))
    toast.show()
    <?php endif; ?>
</script>
</body>
</html>
