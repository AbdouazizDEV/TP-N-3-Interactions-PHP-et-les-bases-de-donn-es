<?php
require 'includes/db.php';

// Récupérer les appartements
$query = "SELECT * FROM Appartement";
if (isset($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    $query .= " WHERE libelle LIKE :search OR ville LIKE :search";
}

$stmt = $pdo->prepare($query);

if (isset($search)) {
    $stmt->bindParam(':search', $search);
}

$stmt->execute();
$appartements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appartements</title>
    <link rel="stylesheet" href="assets/css/styleAppartements.css">
</head>
<body>
    <h1>Liste des Appartements</h1>
    <form method="get">
        <input type="text" name="search" placeholder="Rechercher un appartement">
        <button type="submit">Rechercher</button>
    </form>
    <form method="post">
        <button type="button" onclick="window.location.href='ajouter_appartement.php'">Ajouter un appartement</button>
    </form>
    <table border="1">
        <thead>
            <tr>
                <th>Libelle</th>
                <th>Superficie</th>
                <th>Prix</th>
                <th>Ville</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appartements as $appartement): ?>
            <tr>
                <td><?= htmlspecialchars($appartement['libelle']) ?></td>
                <td><?= htmlspecialchars($appartement['superficie']) ?> m²</td>
                <td><?= htmlspecialchars($appartement['prix']) ?> €</td>
                <td><?= htmlspecialchars($appartement['ville']) ?></td>
                <td>
                    <a href="modifier_appartement.php?id=<?= $appartement['id'] ?>">Modifier</a>
                    <a href="supprimer_appartement.php?id=<?= $appartement['id'] ?>">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
