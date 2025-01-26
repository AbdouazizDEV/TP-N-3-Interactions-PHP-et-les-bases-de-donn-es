<?php
$host = '127.0.0.1';
$dbname = 'gestImmo';
$username = 'demba';
$password = 'dembaBame';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie avec MySQL !";
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
