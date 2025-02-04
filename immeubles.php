<?php
require 'includes/db.php';

// Récupérer les immeubles
$query = "SELECT * FROM Immeuble";
if (isset($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    $query .= " WHERE libelle LIKE :search OR ville LIKE :search OR quartier LIKE :search";
}

$stmt = $pdo->prepare($query);

if (isset($search)) {
    $stmt->bindParam(':search', $search);
}

$stmt->execute();
$immeubles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Immeubles</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            max-width: 100%;
            margin: 0 auto;
            padding: 45px;
            background: linear-gradient(to right, #007bff, #dff3f8);
            line-height: 1.6;
        }

        h1 {
            text-align: center;
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }

        form {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
        }

        table thead {
            background-color: #007bff;
            color: white;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #e6e6e6;
        }

        .actions a {
            margin-right: 10px;
            color: #007bff;
            text-decoration: none;
        }

        .actions a:hover {
            text-decoration: underline;
        }
    </style>
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
                <th>Libellé</th>
                <th>Adresse</th>
                <th>Ville</th>
                <th>Quartier</th>
                <th>Superficie (m²)</th>
                <th>Nombre de pièces</th>
                <th>Date de disponibilité</th>
                <th>Prix (€)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($immeubles as $immeuble): ?>
            <tr>
                <td><?= htmlspecialchars($immeuble['libelle']) ?></td>
                <td><?= htmlspecialchars($immeuble['adresse']) ?></td>
                <td><?= htmlspecialchars($immeuble['ville']) ?></td>
                <td><?= htmlspecialchars($immeuble['quartier']) ?></td>
                <td><?= htmlspecialchars($immeuble['superficie']) ?></td>
                <td><?= htmlspecialchars($immeuble['nbreAppartement']) ?></td>
                <td><?= htmlspecialchars($immeuble['dateDispo']) ?></td>
                <td><?= htmlspecialchars($immeuble['prix']) ?></td>
                <td class="actions">
                    <a href="modifier_immeuble.php?id=<?= $immeuble['idIm'] ?>">Modifier</a>
                    <a href="supprimer_immeuble.php?id=<?= $immeuble['idIm'] ?>">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>