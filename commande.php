<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['client_id'])) {
    header("Location: index.php");
    exit();
}

$client_id = $_SESSION['client_id'];

// Vérifier bien
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$bien_id = (int)$_GET['id'];

// Vérifier bien existant et statut
$stmt = $pdo->prepare("SELECT * FROM biens WHERE id = ?");
$stmt->execute([$bien_id]);
$bien = $stmt->fetch();

if (!$bien || $bien['statut'] !== 'vente') {
    echo "Bien non disponible à la vente.";
    exit();
}

// Créer commande en_attente
$stmt = $pdo->prepare("INSERT INTO commandes (client_id, bien_id, statut, date_commande) VALUES (?, ?, 'en_attente', NOW())");
if ($stmt->execute([$client_id, $bien_id])) {
    header("Location: profile.php");
    exit();
} else {
    echo "Erreur lors de la création de la commande.";
}
