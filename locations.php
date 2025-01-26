<?php
/* require 'includes/db.php';

// Récupérer les locations
$query = "SELECT l.*, a.libelle as appartement 
          FROM Location l 
          JOIN Appartement a ON l.idAppartement = a.id";

if (isset($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    $query .= " WHERE a.libelle LIKE :search OR l.nomLocataire LIKE :search";
}

$stmt = $pdo->prepare($query);

if (isset($search)) {
    $stmt->bindParam(':search', $search);
}

$stmt->execute();
$locations = $stmt->fetchAll(PDO::FETCH_ASSOC); */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locations</title>
    <link rel="stylesheet" href="assets/css/styleLocations.css">
</head>
<body>
    <h1>Liste des Locations</h1>
    <form method="get">
        <input type="text" name="search" placeholder="Rechercher une location">
        <button type="submit">Rechercher</button>
    </form>
    <form method="post">
        <button type="button" onclick="window.location.href='ajouter_location.php'">Ajouter une location</button>
    </form>
    <table border="1">
        <thead>
            <tr>
                <th>Appartement</th>
                <th>Nom Locataire</th>
                <th>Date Début</th>
                <th>Date Fin</th>
                <th>Loyer Mensuel</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($locations as $location): ?>
            <tr>
                <td><?= htmlspecialchars($location['appartement']) ?></td>
                <td><?= htmlspecialchars($location['nomLocataire']) ?></td>
                <td><?= htmlspecialchars($location['dateDebut']) ?></td>
                <td><?= htmlspecialchars($location['dateFin']) ?></td>
                <td><?= htmlspecialchars($location['loyerMensuel']) ?> €</td>
                <td>
                    <a href="modifier_location.php?id=<?= $location['id'] ?>">Modifier</a>
                    <a href="supprimer_location.php?id=<?= $location['id'] ?>">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>