<?php
// Paramètres de connexion
$host = 'localhost';    // Serveur MySQL (localhost si en local)
$db   = 'immoplus';     // Nom de ta base de données
$user = 'root';         // Nom d'utilisateur MySQL
$pass = '';             
$charset = 'utf8mb4';   

// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Options PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
    PDO::ATTR_EMULATE_PREPARES   => false,                  
];

// Connexion
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    //echo "Connexion réussie !"; 
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit;
}
?>
