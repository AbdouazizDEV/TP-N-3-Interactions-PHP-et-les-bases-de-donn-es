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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Vérifier si l'utilisateur existe et récupérer ses informations avec le rôle
    $sql = "SELECT c.*, r.libelle AS role 
            FROM Client c
            JOIN Role r ON c.idRole = r.idRole 
            WHERE c.email = :username LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Vérifier le mot de passe
        if (password_verify($password, $user['password'])) {
            // Démarrer une session pour l'utilisateur
            session_start();
            $_SESSION['idClient'] = $user['idClient'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirection basée sur le rôle
            switch ($user['role']) {
                case 'administrateur':
                    header("Location: ../AccueilAdmin.php");
                    break;
                case 'commercial':
                case 'client':
                    header("Location: ../accueil.php");
                    break;
                default:
                    // Rôle non reconnu
                    echo "<script>alert('Rôle non autorisé.'); window.location.href='../login.php';</script>";
            }
            exit();
        } else {
            // Mot de passe incorrect
            echo "<script>alert('Mot de passe incorrect.'); window.location.href='../login.php';</script>";
        }
    } else {
        // Utilisateur non trouvé
        echo "<script>alert('Nom d\'utilisateur introuvable.'); window.location.href='../login.php';</script>";
    }
}
?>