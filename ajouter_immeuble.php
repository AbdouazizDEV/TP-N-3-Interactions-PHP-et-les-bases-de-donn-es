<?php
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adresse = $_POST['adresse'];
    $ville = $_POST['ville'];
    $codePostal = $_POST['codePostal'];
    $nbreAppartements = $_POST['nbreAppartements'];
    $description = $_POST['description'];

    $query = "INSERT INTO Immeuble (adresse, ville, codePostal, nbreAppartements, description)
              VALUES (:adresse, :ville, :codePostal, :nbreAppartements, :description)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(compact('adresse', 'ville', 'codePostal', 'nbreAppartements', 'description'));

    header('Location: immeubles.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Immeuble</title>
    <link rel="stylesheet" href="assets/css/styleAjouterImmeuble.css">
</head>
<body>
    <h1>Ajouter un Immeuble</h1>
    <form method="post">
        <input type="text" name="adresse" placeholder="Adresse" required>
        <input type="text" name="ville" placeholder="Ville" required>
        <input type="text" name="codePostal" placeholder="Code Postal" required>
        <input type="number" name="nbreAppartements" placeholder="Nombre d'Appartements" required>
        <textarea name="description" placeholder="Description"></textarea>
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>