<?php
    require 'includes/db.php';
    // Récupérer les appartements avec les informations de l'immeuble
    $query = "SELECT a.*, i.libelle as immeuble_libelle
            FROM Appartement a 
            JOIN Immeuble i ON a.idIm = i.idIm";
    if (isset($_GET['search'])) {
        $search = '%' . $_GET['search'] . '%';
        $query .= " WHERE i.libelle LIKE :search";
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
    <title>Gestion des Appartements</title>
    <!-- <link rel="stylesheet" href="./assets/css/styleAppartements.css"> -->
     <style>
        /* Style général */
body {
    font-family: 'Arial', sans-serif;
    max-width: 100%;
    margin: 0 auto;
    padding: 45px;
    background: linear-gradient(to right, #007bff, #dff3f8);
    line-height: 1.6;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
}

h1 {
    color: #2c3e50;
    text-align: center;
    margin-bottom: 30px;
    font-size: 2em;
}

/* Barre de recherche */
.search-form {
    display: flex;
    gap: 10px;
    max-width: 600px;
    margin: 0 auto 20px;
}

.search-form input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1em;
}

.search-form button {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

/* Bouton Ajouter */
.add-btn {
    display: block;
    margin: 20px auto;
    padding: 12px 24px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1em;
}

.add-btn:hover {
    background-color: #218838;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.modal-content {
    position: relative;
    background-color: white;
    margin: 50px auto;
    padding: 25px;
    width: 90%;
    max-width: 600px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.close {
    position: absolute;
    right: 20px;
    top: 15px;
    font-size: 24px;
    cursor: pointer;
    color: #666;
}

/* Formulaire */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 500;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1em;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
}

/* Section images */
.image-input {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.image-input input[type="file"] {
    flex: 1;
}

.remove-image {
    padding: 8px 16px;
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

#addImageBtn {
    margin-top: 10px;
    background-color: #6c757d;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
}

/* Bouton submit */
.submit-btn {
    width: 100%;
    padding: 12px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1em;
    margin-top: 20px;
}

.submit-btn:hover {
    background-color: #218838;
}

/* Table */
.apartments-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 30px;
    background-color: white;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

.apartments-table th,
.apartments-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.apartments-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #333;
}

.apartments-table tr:hover {
    background-color: #f5f5f5;
}

.actions {
    display: flex;
    gap: 8px;
}

.edit-btn,
.delete-btn {
    padding: 6px 12px;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 0.9em;
}

.edit-btn {
    background-color: #ffc107;
}

.delete-btn {
    background-color: #dc3545;
}

.edit-btn:hover {
    background-color: #e0a800;
}

.delete-btn:hover {
    background-color: #c82333;
}

/* Responsive */
@media (max-width: 768px) {
    .modal-content {
        margin: 20px;
        padding: 15px;
    }

    .apartments-table {
        display: block;
        overflow-x: auto;
    }

    .actions {
        flex-direction: column;
    }

    .edit-btn,
    .delete-btn {
        text-align: center;
    }
}   
.add-btn {
    position: relative;
    left: 487px;
    height: 39px;
    background-color: blue;
}
     </style>
</head>
<body>
    <div class="container">
        <h1>Liste des Appartements</h1>
        
        <form method="get" class="search-form">
            <input type="text" name="search" placeholder="Rechercher un appartement">
            <button type="submit">Rechercher</button>
        </form>
        <form method="post">
            <button class="add-btn" type="button" onclick="window.location.href='ajouter_appartement.php'">Ajouter un appartement</button>
        </form>
       

        <table class="apartments-table">
            <thead>
                <tr>
                    <th>Immeuble</th>
                    <th>Chambres</th>
                    <th>Salles de bain</th>
                    <th>Étage</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appartements as $appartement): ?>
                <tr>
                    <td><?= htmlspecialchars($appartement['immeuble_libelle']) ?></td>
                    <td><?= htmlspecialchars($appartement['nbreChambres']) ?></td>
                    <td><?= htmlspecialchars($appartement['nbreSalleBain']) ?></td>
                    <td><?= htmlspecialchars($appartement['etage']) ?></td>
                    <td class="actions">
                        <a href="modifier_appartement.php?id=<?= $appartement['idApp'] ?>" class="edit-btn">Modifier</a>
                        <a href="supprimer_appartement.php?id=<?= $appartement['idApp'] ?>" class="delete-btn">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="assets/js/appartements.js"></script>
</body>
</html>