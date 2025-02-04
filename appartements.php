<?php
    require 'includes/db.php';

    // Requête de base
    $query = "SELECT a.*, i.libelle as immeuble_libelle, i.ville, i.quartier, i.prix, i.description, 
                    (SELECT img.url FROM ImageApp img WHERE img.idApp = a.idApp LIMIT 1) as image_url
            FROM Appartement a 
            JOIN Immeuble i ON a.idIm = i.idIm
            WHERE 1=1"; // Ajout d'une condition toujours vraie pour faciliter l'ajout de filtres

    // Ajouter les clauses WHERE en fonction des filtres
    $conditions = [];
    $params = [];

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $conditions[] = "i.libelle LIKE :search";
        $params[':search'] = '%' . $_GET['search'] . '%';
    }

    if (isset($_GET['ville']) && !empty($_GET['ville'])) {
        $conditions[] = "i.ville LIKE :ville";
        $params[':ville'] = '%' . $_GET['ville'] . '%';
    }

    if (isset($_GET['quartier']) && !empty($_GET['quartier'])) {
        $conditions[] = "i.quartier LIKE :quartier";
        $params[':quartier'] = '%' . $_GET['quartier'] . '%';
    }

    // Combiner les conditions avec AND
    if (count($conditions) > 0) {
        $query .= " AND " . implode(" AND ", $conditions);
    }

    // Ajouter la clause ORDER BY
    $query .= " ORDER BY i.ville, i.quartier, i.prix DESC";

    // Préparer et exécuter la requête
    $stmt = $pdo->prepare($query);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
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
<!--     <link rel="stylesheet" href="./assets/css/styleAppartements.css"> -->
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
            max-width: 1827px;
            margin: -17px auto;
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
     <!-- /* style pour le modal  -->
     <style>
        /* Style pour le modal */
        .modal {
            display: none; /* Caché par défaut */
            position: fixed; /* Rester en place */
            z-index: 1; /* Rester au-dessus */
            left: 0;
            top: 0;
            width: 100%; /* Pleine largeur */
            height: 100%; /* Pleine hauteur */
            overflow: auto; /* Activer le défilement si nécessaire */
            background-color: rgb(0,0,0); /* Couleur de fond */
            background-color: rgba(0,0,0,0.4); /* Noir avec opacité */
        }

        /* Contenu du modal */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% du haut et centré */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Largeur du contenu */
            max-width: 500px; /* Largeur maximale */
        }

        /* Bouton de fermeture */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
     </style>
</head>
<body>
    <div class="container">
        <h1>Liste des Appartements</h1>
        
        <form method="get" class="search-form">
            <input type="text" name="search" placeholder="Rechercher par libellé immeuble">
            <input type="text" name="ville" placeholder="Rechercher par ville">
            <input type="text" name="quartier" placeholder="Rechercher par quartier">
            <button type="submit">Rechercher</button>
        </form>
        <form method="post">
            <button class="add-btn" type="button" onclick="window.location.href='ajouter_appartement.php'">Ajouter un appartement</button>
        </form>

        <table class="apartments-table">
            <thead>
                <tr>
                    <th>Ville</th>
                    <th>Quartier</th>
                    <th>Immeuble</th>
                    <th>Chambres</th>
                    <th>Salles de bain</th>
                    <th>Étage</th>
                    <th>Prix</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appartements as $appartement): ?>
                <tr>
                    <td><?= htmlspecialchars($appartement['ville']) ?></td>
                    <td><?= htmlspecialchars($appartement['quartier']) ?></td>
                    <td><?= htmlspecialchars($appartement['immeuble_libelle']) ?></td>
                    <td><?= htmlspecialchars($appartement['nbreChambres']) ?></td>
                    <td><?= htmlspecialchars($appartement['nbreSalleBain']) ?></td>
                    <td><?= htmlspecialchars($appartement['etage']) ?></td>
                    <td><?= htmlspecialchars($appartement['prix']) ?> F cfa</td>
                    <td><?= implode(' ', array_slice(explode(' ', htmlspecialchars($appartement['description'])), 0, 5)) ?>...</td>
                    <td>
                        <a href="detailAppartement.php?id=<?= $appartement['idApp'] ?>">
                            <img src="<?= htmlspecialchars($appartement['image_url']) ?>" alt="Appartement Image" style="width:100px; height:auto;">
                        </a>
                    </td>
                    <td class="actions">
                        <!-- <a href="detailAppartement.php?id=<?= $appartement['idApp'] ?>" class="view-btn">Voir les détails</a> -->
                        <a href="#" class="edit-btn" onclick="openEditModal(<?= $appartement['idApp'] ?>, '<?= htmlspecialchars($appartement['immeuble_libelle']) ?>', <?= $appartement['nbreChambres'] ?>, <?= $appartement['nbreSalleBain'] ?>, <?= $appartement['etage'] ?>, <?= $appartement['idIm'] ?>)">Modifier</a>
                        <a href="controllers/supprimer_appartement.php?id=<?= $appartement['idApp'] ?>" class="delete-btn">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Modal pour modifier un appartement -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Modifier l'appartement</h2>
            <form id="editForm" method="post" action="controllers/modifier_appartement.php">
                <input type="hidden" id="editIdApp" name="idApp">
                <div>
                    <label for="editImmeuble">Immeuble:</label>
                    <input type="text" id="editImmeuble" name="immeuble_libelle" readonly>
                </div>
                <div>
                    <label for="editNbreChambres">Nombre de chambres:</label>
                    <input type="number" id="editNbreChambres" name="nbreChambres" required>
                </div>
                <div>
                    <label for="editNbreSalleBain">Nombre de salles de bain:</label>
                    <input type="number" id="editNbreSalleBain" name="nbreSalleBain" required>
                </div>
                <div>
                    <label for="editEtage">Étage:</label>
                    <input type="number" id="editEtage" name="etage" required>
                </div>
                <button type="submit">Enregistrer les modifications</button>
            </form>
        </div>
    </div>
    <script>
        // Fonction pour ouvrir le modal et pré-remplir le formulaire
        function openEditModal(idApp, immeuble_libelle, nbreChambres, nbreSalleBain, etage) {
            document.getElementById('editIdApp').value = idApp;
            document.getElementById('editImmeuble').value = immeuble_libelle;
            document.getElementById('editNbreChambres').value = nbreChambres;
            document.getElementById('editNbreSalleBain').value = nbreSalleBain;
            document.getElementById('editEtage').value = etage;
            document.getElementById('editModal').style.display = 'block';
        }

        // Fonction pour fermer le modal
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Fermer le modal si l'utilisateur clique en dehors du modal
        window.onclick = function(event) {
            var modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
    <script src="assets/js/appartements.js"></script>
</body>
</html>