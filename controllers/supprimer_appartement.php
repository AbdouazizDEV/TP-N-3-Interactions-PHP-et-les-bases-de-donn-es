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

if (isset($_GET['id'])) {
    $idApp = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!$idApp) {
        // Rediriger avec un message d'erreur si l'ID n'est pas valide
        header("Location: ../appartements.php?error=ID+invalide");
        exit();
    }

    try {
        // Démarrer la transaction
        $pdo->beginTransaction();

        // Récupérer les chemins des images associées à l'appartement
        $sqlSelectImages = "SELECT url FROM ImageApp WHERE idApp = :idApp";
        $stmtSelectImages = $pdo->prepare($sqlSelectImages);
        $stmtSelectImages->execute([':idApp' => $idApp]);
        $images = $stmtSelectImages->fetchAll(PDO::FETCH_COLUMN);

        // Supprimer les images de la base de données
        $sqlDeleteImages = "DELETE FROM ImageApp WHERE idApp = :idApp";
        $stmtDeleteImages = $pdo->prepare($sqlDeleteImages);
        $stmtDeleteImages->execute([':idApp' => $idApp]);

        // Supprimer l'appartement
        $sqlDeleteAppartement = "DELETE FROM Appartement WHERE idApp = :idApp";
        $stmtDeleteAppartement = $pdo->prepare($sqlDeleteAppartement);
        $stmtDeleteAppartement->execute([':idApp' => $idApp]);

        // Valider la transaction
        $pdo->commit();

        // Supprimer les fichiers physiques des images
        foreach ($images as $image) {
            $filePath = __DIR__ . '/../' . $image;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Rediriger avec un message de succès
        header("Location: ../appartements.php?success=1");
        exit();

    } catch (Exception $e) {
        // En cas d'erreur, annuler la transaction
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        // Rediriger avec un message d'erreur
        header("Location: ../appartements.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Si aucun ID n'est fourni, rediriger vers la page des appartements
    header("Location: ../appartements.php");
    exit();
}
?>