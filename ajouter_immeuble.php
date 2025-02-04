<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Immeuble</title>
    <!-- <link rel="stylesheet" href="assets/css/styleAjouterImmeuble.css"> -->
     <style>
        /* assets/css/styleAjouterImmeuble.css */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #007bff, #dff3f8);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 38px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 782px;
        }

        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea {
            width: 95%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1em;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1em;
        }

        .submit-btn:hover {
            background-color: #218838;
        }
     </style>
</head>
<body>
    <div class="container">
        <h1>Ajouter un Immeuble</h1>
        <form method="post" action="controllers/traitement_immeuble.php">
            <div class="form-group">
                <label for="libelle">Libellé :</label>
                <input type="text" id="libelle" name="libelle" placeholder="Libellé de l'immeuble" required>
            </div>
            <div class="form-group">
                <label for="superficie">Superficie (m²) :</label>
                <input type="number" id="superficie" name="superficie" placeholder="Superficie" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="nbreAppartement">Nombre de pièces :</label>
                <input type="number" id="nbreAppartement" name="nbreAppartement" placeholder="Nombre de pièces" required>
            </div>
            <div class="form-group">
                <label for="dateDispo">Date de disponibilité :</label>
                <input type="date" id="dateDispo" name="dateDispo" required>
            </div>
            <div class="form-group">
                <label for="prix">Prix (€) :</label>
                <input type="number" id="prix" name="prix" placeholder="Prix" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="description">Description :</label>
                <textarea id="description" name="description" placeholder="Description de l'immeuble"></textarea>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse :</label>
                <input type="text" id="adresse" name="adresse" placeholder="Adresse" required>
            </div>
            <div class="form-group">
                <label for="quartier">Quartier :</label>
                <input type="text" id="quartier" name="quartier" placeholder="Quartier" required>
            </div>
            <div class="form-group">
                <label for="ville">Ville :</label>
                <input type="text" id="ville" name="ville" placeholder="Ville" required>
            </div>
            <button type="submit" class="submit-btn">Ajouter</button>
        </form>
    </div>
</body>
</html>