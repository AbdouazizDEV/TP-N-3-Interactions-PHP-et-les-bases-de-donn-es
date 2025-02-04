<?php
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);
 
 require_once __DIR__ . '/../vendor/autoload.php';
 use Dotenv\Dotenv;
 
 $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
 $dotenv->load();
 
 try {
     // Connexion à la base de données
     $pdo = new PDO(
         "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}",
         $_ENV['DB_USER'],
         $_ENV['DB_PASS']
     );
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 } catch (PDOException $e) {
     die("Erreur de connexion à la base de données : " . $e->getMessage());
 }