<?php
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $libelle = $_POST['libelle'];
    $superficie = $_POST['superficie'];
    $nbreAppartement = $_POST['nbreAppartement'];
    $dateDispo = $_POST['dateDispo'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];
    $adresse = $_POST['adresse'];
    $quartier = $_POST['quartier'];
    $ville = $_POST['ville'];

    // Requête SQL pour insérer un nouvel immeuble
    $query = "INSERT INTO Immeuble (libelle, superficie, nbreAppartement, dateDispo, prix, description, adresse, quartier, ville)
              VALUES (:libelle, :superficie, :nbreAppartement, :dateDispo, :prix, :description, :adresse, :quartier, :ville)";
    $stmt = $pdo->prepare($query);

    // Exécuter la requête avec les données du formulaire
    $stmt->execute([
        ':libelle' => $libelle,
        ':superficie' => $superficie,
        ':nbreAppartement' => $nbreAppartement,
        ':dateDispo' => $dateDispo,
        ':prix' => $prix,
        ':description' => $description,
        ':adresse' => $adresse,
        ':quartier' => $quartier,
        ':ville' => $ville
    ]);

    // Rediriger vers la page des immeubles après l'ajout
    header('Location: ../immeubles.php');
    exit;
}
?>