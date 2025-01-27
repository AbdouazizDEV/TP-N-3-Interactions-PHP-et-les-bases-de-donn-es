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
    try {
        // Validation des données
        $nbreChambres = filter_input(INPUT_POST, 'nbreChambres', FILTER_VALIDATE_INT);
        $nbreSalleBain = filter_input(INPUT_POST, 'nbreSalleBain', FILTER_VALIDATE_INT);
        $etage = filter_input(INPUT_POST, 'etage', FILTER_VALIDATE_INT);
        $idIm = filter_input(INPUT_POST, 'idIm', FILTER_VALIDATE_INT);

        // Vérification des valeurs requises
        if (!$nbreChambres || !$nbreSalleBain || !isset($etage) || !$idIm) {
            throw new Exception("Toutes les données requises doivent être fournies");
        }

        // Vérification des valeurs positives
        if ($nbreChambres < 1 || $nbreSalleBain < 1 || $etage < 0) {
            throw new Exception("Les nombres de chambres et de salles de bain doivent être positifs, et l'étage ne peut pas être négatif");
        }

        // Vérifier si l'immeuble existe
        $stmtCheckImmeuble = $pdo->prepare("SELECT idIm FROM Immeuble WHERE idIm = ?");
        $stmtCheckImmeuble->execute([$idIm]);
        if (!$stmtCheckImmeuble->fetch()) {
            throw new Exception("L'immeuble sélectionné n'existe pas");
        }

        // Démarrer la transaction
        $pdo->beginTransaction();

        // Insérer l'appartement
        $sqlAppartement = "INSERT INTO Appartement (nbreChambres, nbreSalleBain, etage, idIm) 
                          VALUES (:nbreChambres, :nbreSalleBain, :etage, :idIm)";
        $stmtAppartement = $pdo->prepare($sqlAppartement);
        $stmtAppartement->execute([
            ':nbreChambres' => $nbreChambres,
            ':nbreSalleBain' => $nbreSalleBain,
            ':etage' => $etage,
            ':idIm' => $idIm
        ]);

        $idApp = $pdo->lastInsertId();

        // Traitement des images
        // Gestion du téléchargement de l'image
        $uploadedFiles = [];
        foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
            if ($_FILES['images']['error'][$index] === UPLOAD_ERR_OK) {
                $photoName = uniqid() . '-' . basename($_FILES['images']['name'][$index]);
                $destinationPath = __DIR__ . '/../images/' . $photoName;
                if (move_uploaded_file($tmpName, $destinationPath)) {
                    $uploadedFiles[] = 'images/' . $photoName; // Enregistrez le chemin pour la base de données
                } else {
                    throw new Exception("Erreur lors du déplacement de l'image.");
                }
            } else {
                throw new Exception("Erreur lors du téléchargement de l'image.");
            }
        }
        /* ajouter les images dans la base de données dans le table CREATE TABLE ImageApp (
            id INT AUTO_INCREMENT PRIMARY KEY,
            url VARCHAR(255) NOT NULL,
            idApp INT NOT NULL,
            FOREIGN KEY (idApp) REFERENCES Appartement(idApp) ON DELETE CASCADE
        ); */

        // Insérer les images dans la base de données
        $sqlImages = "INSERT INTO ImageApp (url, idApp) VALUES (:url, :idApp)";
        $stmtImages = $pdo->prepare($sqlImages);
        foreach ($uploadedFiles as $file) {
            $stmtImages->execute([':url' => $file, ':idApp' => $idApp]);
        }
        // Valider la transaction
        $pdo->commit();

        // Rediriger avec un message de succès
        header("Location: ../appartements.php?success=1");
        exit();

    } catch (Exception $e) {
        // En cas d'erreur, annuler la transaction
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        // Supprimer les fichiers téléchargés en cas d'erreur
        if (isset($uploadedFiles)) {
            foreach ($uploadedFiles as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }

        // Rediriger avec un message d'erreur
        header("Location: ../appartements.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Si ce n'est pas une requête POST, rediriger vers la page des appartements
    header("Location: ../appartements.php");
    exit();
}
?>