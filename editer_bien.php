<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['admin_logged'])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM biens WHERE id = ?");
$stmt->execute([$id]);
$bien = $stmt->fetch();

if (!$bien) {
    die("Bien introuvable.");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $prix = (float)$_POST['prix'];
    $type = $_POST['type'];
    $statut = $_POST['statut'];

    $image_name = $bien['image_principale'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        move_uploaded_file($image_tmp, "uploads/" . $image_name);
    }

    // Gérer nouvelles autres images
    $autres_images = explode(',', $bien['autres_images']);
    if (!empty($_FILES['autres_images']['name'][0])) {
        foreach ($_FILES['autres_images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['autres_images']['error'][$key] === UPLOAD_ERR_OK) {
                $file_name = basename($_FILES['autres_images']['name'][$key]);
                move_uploaded_file($tmp_name, "uploads/" . $file_name);
                $autres_images[] = $file_name;
            }
        }
    }
    $autres_images_str = implode(',', $autres_images);

    $stmt = $pdo->prepare("UPDATE biens SET titre = ?, prix = ?, type = ?, statut = ?, image_principale = ?, autres_images = ? WHERE id = ?");
    $stmt->execute([$titre, $prix, $type, $statut, $image_name, $autres_images_str, $id]);

    $message = "Bien mis à jour avec succès !";
    header("Refresh:1; url=admin_crud.php");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Éditer Bien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
    <h1>Éditer Bien</h1>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Titre</label>
            <input type="text" name="titre" class="form-control" value="<?= htmlspecialchars($bien['titre']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Prix</label>
            <input type="number" name="prix" step="0.01" class="form-control" value="<?= $bien['prix'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select">
                <option value="villa" <?= $bien['type'] === 'villa' ? 'selected' : '' ?>>Villa</option>
                <option value="appartement" <?= $bien['type'] === 'appartement' ? 'selected' : '' ?>>Appartement</option>
                <option value="terrain" <?= $bien['type'] === 'terrain' ? 'selected' : '' ?>>Terrain</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Statut</label>
            <select name="statut" class="form-select">
                <option value="vente" <?= $bien['statut'] === 'vente' ? 'selected' : '' ?>>Vente</option>
                <option value="location" <?= $bien['statut'] === 'location' ? 'selected' : '' ?>>Location</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Image principale</label><br>
            <img src="uploads/<?= htmlspecialchars($bien['image_principale']) ?>" width="150">
            <input type="file" name="image" class="form-control mt-2">
        </div>
        <div class="mb-3">
            <label class="form-label">Autres images actuelles</label><br>
            <?php foreach (explode(',', $bien['autres_images']) as $img) : ?>
                <?php if ($img) : ?>
                    <img src="uploads/<?= htmlspecialchars($img) ?>" width="100" class="me-2 mb-2">
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Ajouter d'autres images</label>
            <input type="file" name="autres_images[]" class="form-control" multiple>
        </div>
        <button type="submit" class="btn btn-success">Mettre à jour</button>
        <a href="admin_crud.php" class="btn btn-secondary">Retour</a>
    </form>
</div>
</body>
</html>
