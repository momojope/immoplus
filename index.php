<?php 
session_start();
require_once 'config.php';

// Traitement formulaire
$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {

        // 1️⃣ Vérifier si c’est l’ADMIN
        if ($email === 'immoplus@gmail.sn' && $password === 'immoplus221') {
            $_SESSION['admin_logged'] = true;
            header("Location: admin_crud.php");
            exit();
        }

        // 2️⃣ Sinon, tester côté client en base
        $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = ?");
        $stmt->execute([$email]);
        $client = $stmt->fetch();

        if ($client && password_verify($password, $client['mot_de_passe'])) {
            $_SESSION['client_id'] = $client['id'];
            $_SESSION['client_nom'] = $client['nom'];
            header("Location: dashboard.php");
            exit();
        } else {
            $erreur = "Email ou mot de passe incorrect.";
        }

    } else {
        $erreur = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion | ImmoPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: url('https://t3.ftcdn.net/jpg/03/26/64/54/240_F_326645437_u6cHeqmBoDDZrp7BZPTTeeBJJfGKkbqU.jpg') no-repeat center center/cover;
        }
        .login-box {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            max-width: 400px;
            width: 100%;
        }
        .login-box h2 {
            margin-bottom: 1.5rem;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Connexion ImmoPlus</h2>

    <?php if ($erreur): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($erreur); ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="exemple@mail.com" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="********" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
    </form>

    <p class="mt-3 text-center">
        Pas encore inscrit ? <a href="inscription.php">Créer un compte</a>
    </p>
</div>

</body>
</html>
