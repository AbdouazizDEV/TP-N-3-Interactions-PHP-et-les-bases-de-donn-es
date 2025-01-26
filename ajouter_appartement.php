<?php
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $libelle = $_POST['libelle'];
    $categorie = $_POST['categorie'];
    $superficie = $_POST['superficie'];
    $nbrePieces = $_POST['nbrePieces'];
    $dateDispo = $_POST['dateDispo'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];
    $adresse = $_POST['adresse'];
    $quartier = $_POST['quartier'];
    $ville = $_POST['ville'];

    $query = "INSERT INTO Appartement (libelle, categorie, superficie, nbrePieces, dateDispo, prix, description, adresse, quartier, ville)
              VALUES (:libelle, :categorie, :superficie, :nbrePieces, :dateDispo, :prix, :description, :adresse, :quartier, :ville)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(compact('libelle', 'categorie', 'superficie', 'nbrePieces', 'dateDispo', 'prix', 'description', 'adresse', 'quartier', 'ville'));

    header('Location: appartements.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Appartement</title>
    <link rel="stylesheet" href="assets/css/styleAjouterAppartement.css">
</head>
<body>
    <h1>Ajouter un Appartement</h1>
    <form method="post">
        <input type="text" name="libelle" placeholder="Libelle" required>
        <input type="text" name="categorie" placeholder="Categorie" required>
        <input type="number" name="superficie" placeholder="Superficie" step="0.01" required>
        <input type="number" name="nbrePieces" placeholder="Nombre de pièces" required>
        <input type="date" name="dateDispo" required>
        <input type="number" name="prix" placeholder="Prix" step="0.01" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input type="text" name="adresse" placeholder="Adresse">
        <input type="text" name="quartier" placeholder="Quartier">
        <input type="text" name="ville" placeholder="Ville" required>
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>
