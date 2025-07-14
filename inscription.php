<?php
require_once 'config.php';

$succes = '';
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($nom) || empty($email) || empty($password) || empty($confirm_password)) {
        $erreur = "Veuillez remplir tous les champs obligatoires.";
    } elseif ($password !== $confirm_password) {
        $erreur = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT id FROM clients WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $erreur = "Cet email est déjà utilisé.";
        } else {
            // Hasher le mot de passe
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Insérer dans la base
            $stmt = $pdo->prepare("INSERT INTO clients (nom, email, telephone, mot_de_passe) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$nom, $email, $telephone, $hash])) {
                $succes = "Inscription réussie ! Vous pouvez maintenant <a href='index.php'>vous connecter</a>.";
            } else {
                $erreur = "Erreur lors de l'inscription.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription | ImmoPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: url('https://t3.ftcdn.net/jpg/03/26/64/54/240_F_326645437_u6cHeqmBoDDZrp7BZPTTeeBJJfGKkbqU.jpg') no-repeat center center/cover;
        }
        .register-box {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
        }
        .register-box h2 {
            margin-bottom: 1.5rem;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Créer un compte ImmoPlus</h2>

    <?php if ($erreur): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($erreur); ?></div>
    <?php endif; ?>

    <?php if ($succes): ?>
        <div class="alert alert-success"><?php echo $succes; ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom complet</label>
            <input type="text" name="nom" id="nom" class="form-control" placeholder="Jean Dupont" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="exemple@mail.com" required>
        </div>

        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="text" name="telephone" id="telephone" class="form-control" placeholder="+221...">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="********" required>
        </div>

        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="********" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
    </form>

    <p class="mt-3 text-center">
        Déjà inscrit ? <a href="index.php">Se connecter</a>
    </p>
</div>

</body>
</html>

