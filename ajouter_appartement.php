<?php
require 'includes/db.php';

// Récupérer les immeubles pour le select
$queryImmeubles = "SELECT * FROM Immeuble";
$stmtImmeubles = $pdo->prepare($queryImmeubles);
$stmtImmeubles->execute();
$immeubles = $stmtImmeubles->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Appartement</title>
    <!-- <link rel="stylesheet" href="assets/css/styleAjouterAppartement.css"> -->
     <style>
        /* Style global */
        body {
            font-family: 'Arial', sans-serif;
            max-width: 47%;
            margin: 0 auto;
            padding: 45px;
            background: linear-gradient(to right, #007bff, #dff3f8);
            line-height: 1.6;
        }

        /* Titre principal */
        h1 {
            text-align: center;
            color: #222;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        /* Formulaire */
        form {
            background-color: white;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        form:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }

        /* Champs de formulaire */
        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1rem;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        /* Effets de focus sur les champs */
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            background-color: #fff;
        }

        /* Boutons */
        button {
            width: 20%;
            padding: 14px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            margin: 12px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Lien pour ajouter ou supprimer des images */
        .add-image-btn, .remove-image-btn {
            display: inline-block;
            margin-top: 10px;
            font-size: 0.9rem;
            color: #007bff;
            text-decoration: none;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .add-image-btn:hover, .remove-image-btn:hover {
            color: #0056b3;
        }

        /* Conteneur pour les images */
        .image-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }

        .image-preview {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 5px;
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }

        .image-preview:hover {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

     </style>
</head>
<body>
    <h1>Ajouter un Appartement</h1>
     <!-- Modal pour l'ajout d'appartement -->
     <div id="addApartmentModal" class="modal">
            <div class="modal-content">
                
                <h2>Ajouter un appartement</h2>
                <form id="apartmentForm" action="controllers/traitement_appartement.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="immeuble">Immeuble:</label>
                        <select name="idIm" id="immeuble" required>
                            <option value="">Sélectionner un immeuble</option>
                            <?php foreach ($immeubles as $immeuble): ?>
                                <option value="<?= $immeuble['idIm'] ?>">
                                    <?= htmlspecialchars($immeuble['libelle']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nbreChambres">Nombre de chambres:</label>
                        <input type="number" id="nbreChambres" name="nbreChambres" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="nbreSalleBain">Nombre de salles de bain:</label>
                        <input type="number" id="nbreSalleBain" name="nbreSalleBain" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="etage">Étage:</label>
                        <input type="number" id="etage" name="etage" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="images">Images:</label>
                        <div id="imageInputs">
                            <div class="image-input">
                                <input type="file" name="images[]" accept="image/*" required>
                                <button type="button" class="remove-image" style="display: none;">Supprimer</button>
                            </div>
                        </div>
                        <button type="button" id="addImageBtn">Ajouter une image</button>
                        <div id="imageErrors" class="error-messages"></div>

                    </div>

                    <button type="submit" class="submit-btn">Enregistrer</button>
                </form>
            </div>
        </div>
   <!--  <script src="assets/js/appartements.js"></script> -->
     <script>
        const addImageBtn = document.getElementById('addImageBtn');
        const imageInputs = document.getElementById('imageInputs');
        
        /* en cliquant sur le bouton ajouter une image on doit ajouter un nouveau champ image-input */
        addImageBtn.addEventListener('click', function() {
            const imageInput = document.createElement('div');
            imageInput.className = 'image-input';
            imageInput.innerHTML = `
            <div class="image-input">
                <input type="file" name="images[]" accept="image/*" required>
                <button type="button" style="background-color: red" class="remove-image">Supprimer</button>
            </div>
            `;
            imageInputs.appendChild(imageInput);
        });

        /* lorsque on clique sur le bouton supprimer d'une image, on supprime le champ de saisie */
        imageInputs.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-image')) {
                e.target.parentElement.remove();
            }
        })

     </script>

</body>
</html>
