<?php
/* require 'includes/db.php';

// Récupérer les immeubles
$query = "SELECT * FROM Immeuble";
if (isset($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    $query .= " WHERE adresse LIKE :search OR ville LIKE :search";
}

$stmt = $pdo->prepare($query);

if (isset($search)) {
    $stmt->bindParam(':search', $search);
}

$stmt->execute();
$immeubles = $stmt->fetchAll(PDO::FETCH_ASSOC); */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Immeubles</title>
    <link rel="stylesheet" href="assets/css/styleImmeubles.css">
</head>
<body>
    <h1>Liste des Immeubles</h1>
    <form method="get">
        <input type="text" name="search" placeholder="Rechercher un immeuble">
        <button type="submit">Rechercher</button>
    </form>
    <form method="post">
        <button type="button" onclick="window.location.href='ajouter_immeuble.php'">Ajouter un immeuble</button>
    </form>
    <table border="1">
        <thead>
            <tr>
                <th>Adresse</th>
                <th>Ville</th>
                <th>Code Postal</th>
                <th>Nombre d'Appartements</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($immeubles as $immeuble): ?>
            <tr>
                <td><?= htmlspecialchars($immeuble['adresse']) ?></td>
                <td><?= htmlspecialchars($immeuble['ville']) ?></td>
                <td><?= htmlspecialchars($immeuble['codePostal']) ?></td>
                <td><?= htmlspecialchars($immeuble['nbreAppartements']) ?></td>
                <td>
                    <a href="modifier_immeuble.php?id=<?= $immeuble['id'] ?>">Modifier</a>
                    <a href="supprimer_immeuble.php?id=<?= $immeuble['id'] ?>">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>