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

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $tel = $_POST['tel'];
    $role = $_POST['role'];

    // Vérifier si les mots de passe correspondent
    if ($password !== $confirmPassword) {
        echo "<script>alert('Les mots de passe ne correspondent pas.'); window.location.href='../register.php';</script>";
        exit();
    }

    // Hasher le mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Gestion du téléchargement de l'image
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoTmpPath = $_FILES['photo']['tmp_name'];
        $photoName = uniqid() . '-' . basename($_FILES['photo']['name']);
        $destinationPath = __DIR__ . '/../images/' . $photoName;
        $imagePath = 'images/' . $photoName;

        if (!move_uploaded_file($photoTmpPath, $destinationPath)) {
            echo "<script>alert('Erreur lors du déplacement de l\'image.'); window.location.href='../register.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Erreur lors du téléchargement de l\'image.'); window.location.href='../register.php';</script>";
        exit();
    }

    try {
        // Démarrer la transaction
        $pdo->beginTransaction();

        // Déterminer l'idRole en fonction du rôle sélectionné
        $sqlRole = "SELECT idRole FROM Role WHERE libelle = :role";
        $stmtRole = $pdo->prepare($sqlRole);
        $roleLabel = ($role === 'user') ? 'client' : 'commercial';
        $stmtRole->execute([':role' => $roleLabel]);
        $roleData = $stmtRole->fetch(PDO::FETCH_ASSOC);

        if (!$roleData) {
            $pdo->rollBack();
            echo "<script>alert('Rôle non valide.'); window.location.href='../register.php';</script>";
            exit();
        }

        // Vérifier si l'email existe déjà dans la table Client
        $sqlCheckEmail = "SELECT COUNT(*) FROM Client WHERE email = :email";
        $stmtCheckEmail = $pdo->prepare($sqlCheckEmail);
        $stmtCheckEmail->execute([':email' => $email]);
        $emailExists = $stmtCheckEmail->fetchColumn();

        if ($emailExists) {
            $pdo->rollBack();
            echo "<script>alert('Cet email est déjà utilisé.'); window.location.href='../register.php';</script>";
            exit();
        }

        // Insérer dans la table Client
        $sqlClient = "INSERT INTO Client (nom, prenom, email, password, tel, photo, idRole) 
                     VALUES (:nom, :prenom, :email, :password, :tel, :photo, :idRole)";
        $stmtClient = $pdo->prepare($sqlClient);
        $stmtClient->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':tel' => $tel,
            ':photo' => $imagePath,
            ':idRole' => $roleData['idRole']
        ]);

        // Si c'est un commercial, insérer aussi dans la table AgentCom
        if ($role === 'agent') {
            $sqlAgent = "INSERT INTO AgentCom (nom, prenom, email, password, tel, photo) 
                        VALUES (:nom, :prenom, :email, :password, :tel, :photo)";
            $stmtAgent = $pdo->prepare($sqlAgent);
            $stmtAgent->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':tel' => $tel,
                ':photo' => $imagePath
            ]);
        }

        // Valider la transaction
        $pdo->commit();

        // Redirection avec message de succès
        echo "<script>alert('Inscription réussie'); window.location.href='../login.php';</script>";
        exit();
    } catch (PDOException $e) {
        // En cas d'erreur, annuler la transaction
        $pdo->rollBack();
        echo "<script>alert('Erreur lors de l\'inscription : " . addslashes($e->getMessage()) . "'); window.location.href='../register.php';</script>";
        exit();
    }
}
?>