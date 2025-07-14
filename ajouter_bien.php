<?php
        session_start();
        require_once 'config.php';

        if (!isset($_SESSION['admin_logged'])) {
            header("Location: index.php");
            exit();
        }

        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = trim($_POST['titre']);
            $prix = (float)$_POST['prix'];
            $type = $_POST['type'];
            $statut = $_POST['statut'];

            // Image principale
            $image_name = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image_tmp = $_FILES['image']['tmp_name'];
                $image_name = basename($_FILES['image']['name']);
                move_uploaded_file($image_tmp, "uploads/" . $image_name);
            }

            // Autres images
            $autres_images = [];
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

            $stmt = $pdo->prepare("INSERT INTO biens (titre, prix, type, statut, image_principale, autres_images, date_publication) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$titre, $prix, $type, $statut, $image_name, $autres_images_str]);

            $message = "Bien ajouté avec succès !";
        }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter un Bien | ImmoPlus</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
    }
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }
    h1 {
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
      <h1 class="mb-4 text-center">Ajouter un Bien</h1>

      <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>

      <div class="card p-4">
        <form method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Titre du Bien</label>
            <input type="text" name="titre" class="form-control" placeholder="Ex : Villa moderne à Dakar" required>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Prix (FCFA)</label>
              <input type="number" name="prix" step="0.01" class="form-control" placeholder="Ex : 25000000" required>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Type</label>
              <select name="type" class="form-select" required>
                <option disabled selected>Choisir...</option>
                <option value="villa">Villa</option>
                <option value="appartement">Appartement</option>
                <option value="terrain">Terrain</option>
              </select>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Statut</label>
              <select name="statut" class="form-select" required>
                <option disabled selected>Choisir...</option>
                <option value="vente">Vente</option>
                <option value="location">Location</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Image principale</label>
            <input type="file" name="image" class="form-control" required>
          </div>

          <div class="mb-4">
            <label class="form-label">Autres images</label>
            <input type="file" name="autres_images[]" class="form-control" multiple>
            <small class="text-muted">Vous pouvez sélectionner plusieurs fichiers.</small>
          </div>

          <div class="d-flex justify-content-between">
            <a href="admin_crud.php" class="btn btn-secondary">
              <i class="bi bi-arrow-left"></i> Retour
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-plus-circle"></i> Ajouter le Bien
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
