<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['client_id'])) {
    header("Location: index.php");
    exit();
}

$client_id = $_SESSION['client_id'];

// Vérifier ID bien passé en GET
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$bien_id = (int)$_GET['id'];

// Récupérer info bien
$stmt = $pdo->prepare("SELECT * FROM biens WHERE id = ?");
$stmt->execute([$bien_id]);
$bien = $stmt->fetch();

if (!$bien) {
    echo "Bien non trouvé.";
    exit();
}

// Préparer tableau images
$images = [];
if (!empty($bien['image_principale'])) {
    $images[] = $bien['image_principale'];
}
if (!empty($bien['autres_images'])) {
    $autres = explode(',', $bien['autres_images']);
    foreach ($autres as $img) {
        if (trim($img) !== '') {
            $images[] = trim($img);
        }
    }
}

// Traiter ajout d'avis
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commentaire'])) {
    $commentaire = trim($_POST['commentaire']);
    $note = (int)$_POST['note'];

    if (empty($commentaire) || $note < 1 || $note > 5) {
        $error = "Veuillez remplir correctement le formulaire.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO avis (client_id, bien_id, commentaire, note) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$client_id, $bien_id, $commentaire, $note])) {
            $success = "Merci pour votre avis !";
        } else {
            $error = "Erreur lors de l'envoi de l'avis.";
        }
    }
}

// Récupérer les avis
$stmt = $pdo->prepare("SELECT a.*, c.nom FROM avis a JOIN clients c ON a.client_id = c.id WHERE bien_id = ? ORDER BY date_avis DESC");
$stmt->execute([$bien_id]);
$avis_list = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($bien['titre']); ?> | ImmoPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="logo.png" alt="Logo" width="50" class="me-2">
            ImmoPlus
        </a>
    </div>
    <a href="dashboard.php" class="btn btn-light text-primary ms-3">Retour</a>
</nav>

<div class="container mt-5">
    <h1><?php echo htmlspecialchars($bien['titre']); ?></h1>

    <!-- Carousel Bootstrap -->
    <?php if ($images) : ?>
    <div id="carouselBien" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($images as $index => $img) : ?>
                <div class="carousel-item <?php if ($index === 0) echo 'active'; ?>">
                    <img src="uploads/<?php echo htmlspecialchars($img); ?>" class="d-block w-100" style="max-height: 500px; object-fit: cover;" alt="Image du bien">
                </div>
            <?php endforeach; ?>
        </div>
        <?php if (count($images) > 1) : ?>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselBien" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselBien" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <h4>Description</h4>
    <p><?php echo nl2br(htmlspecialchars($bien['description'])); ?></p>

    <p><strong>Type :</strong> <?php echo htmlspecialchars($bien['type']); ?></p>
    <p><strong>Statut :</strong> <?php echo htmlspecialchars($bien['statut']); ?></p>
    <p><strong>Prix :</strong> <?php echo number_format($bien['prix'], 0, ',', ' '); ?> FCFA</p>
    <p><strong>Adresse :</strong> <?php echo htmlspecialchars($bien['adresse']); ?></p>

    <?php if ($bien['statut'] === 'vente') : ?>
    <a href="commande.php?id=<?php echo $bien_id; ?>" class="btn btn-success mb-3">Acheter</a>
    <?php else : ?>
    <a href="reservation.php?id=<?php echo $bien_id; ?>" class="btn btn-primary mb-3">Réserver</a>
    <?php endif; ?>

    <hr>

    <h4>Laissez un avis</h4>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="commentaire" class="form-label">Votre avis</label>
            <textarea name="commentaire" id="commentaire" rows="4" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label for="note" class="form-label">Note</label>
            <select name="note" id="note" class="form-select" required>
                <option value="">Sélectionner une note</option>
                <?php for ($i = 1; $i <= 5; $i++) : ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?> ⭐</option>
                <?php endfor; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>

    <hr>

    <h4>Avis des utilisateurs</h4>
    <?php if ($avis_list) : ?>
        <?php foreach ($avis_list as $avis) : ?>
            <div class="mb-3">
                <strong><?php echo htmlspecialchars($avis['nom']); ?></strong> - <?php echo $avis['note']; ?> ⭐<br>
                <small><?php echo date('d/m/Y', strtotime($avis['date_avis'])); ?></small>
                <p><?php echo nl2br(htmlspecialchars($avis['commentaire'])); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>Aucun avis pour ce bien pour le moment.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
