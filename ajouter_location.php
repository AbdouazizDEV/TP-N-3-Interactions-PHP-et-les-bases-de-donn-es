<?php
require 'includes/db.php';

// Récupérer la liste des appartements disponibles
$stmt = $pdo->query("SELECT id, libelle FROM Appartement");
$appartements = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idAppartement = $_POST['idAppartement'];
    $nomLocataire = $_POST['nomLocataire'];
    $dateDebut = $_POST['dateDebut'];
    $dateFin = $_POST['dateFin'];
    $loyerMensuel = $_POST['loyerMensuel'];

    $query = "INSERT INTO Location (idAppartement, nomLocataire, dateDebut, dateFin, loyerMensuel)
              VALUES (:idAppartement, :nomLocataire, :dateDebut, :dateFin, :loyerMensuel)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(compact('idAppartement', 'nomLocataire', 'dateDebut', 'dateFin', 'loyerMensuel'));

    header('Location: locations.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Location</title>
    <link rel="stylesheet" href="assets/css/styleAjouterLocation.css">
</head>
<body>
    <h1>Ajouter une Location</h1>
    <form method="post">
        <select name="idAppartement" required>
            <option value="">Sélectionner un appartement</option>
            <?php foreach ($appartements as $appartement): ?>
                <option value="<?= $appartement['id'] ?>">
                    <?= htmlspecialchars($appartement['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="nomLocataire" placeholder="Nom du Locataire" required>
        <label>Date de Début</label>
        <input type="date" name="dateDebut" required>
        <label>Date de Fin</label>
        <input type="date" name="dateFin" required>
        <input type="number" name="loyerMensuel" placeholder="Loyer Mensuel" step="0.01" required>
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>