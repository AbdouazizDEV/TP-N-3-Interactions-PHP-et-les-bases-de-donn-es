<?php
    require 'includes/db.php';

    if (!isset($_GET['id'])) {
        header('Location: appartements.php');
        exit();
    }

    $idApp = $_GET['id'];

    // Récupérer les informations de l'appartement
    $query = "SELECT a.*, i.libelle as immeuble_libelle, i.ville, i.quartier, i.prix, i.description
            FROM Appartement a 
            JOIN Immeuble i ON a.idIm = i.idIm
            WHERE a.idApp = :idApp";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idApp', $idApp);
    $stmt->execute();
    $appartement = $stmt->fetch(PDO::FETCH_ASSOC);

    // Récupérer les images de l'appartement
    $query = "SELECT url FROM ImageApp WHERE idApp = :idApp";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':idApp', $idApp);
    $stmt->execute();
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'Appartement</title>
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
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2em;
        }

        /* Détails de l'appartement */
        .appartement-details {
            margin-bottom: 30px;
        }

        .appartement-details h2 {
            color: #007bff;
            margin-bottom: 20px;
        }

        .appartement-details p {
            margin: 10px 0;
            font-size: 1.1em;
            color: #333;
        }

        .appartement-details strong {
            color: #2c3e50;
        }

        /* Bouton Retour */
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 1em;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        /* Carrousel */
        .carousel-container {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .carousel {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .carousel-item {
            flex: 0 0 100%;
            box-sizing: border-box;
        }

        .carousel-item img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 8px;
        }

        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 50%;
            font-size: 1.5em;
            z-index: 10;
        }

        .carousel-btn:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        .carousel-btn.prev {
            left: 10px;
        }

        .carousel-btn.next {
            right: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .carousel-item {
                width: 200px;
            }

            .appartement-details p {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Détails de l'Appartement</h1>
        
        <div class="appartement-details">
            <h2><?= htmlspecialchars($appartement['immeuble_libelle']) ?></h2>
            <p><strong>Ville:</strong> <?= htmlspecialchars($appartement['ville']) ?></p>
            <p><strong>Quartier:</strong> <?= htmlspecialchars($appartement['quartier']) ?></p>
            <p><strong>Prix:</strong> <?= htmlspecialchars($appartement['prix']) ?> F cfa</p>
            <p><strong>Description:</strong> <?= htmlspecialchars($appartement['description']) ?></p>
            <p><strong>Chambres:</strong> <?= htmlspecialchars($appartement['nbreChambres']) ?></p>
            <p><strong>Salles de bain:</strong> <?= htmlspecialchars($appartement['nbreSalleBain']) ?></p>
            <p><strong>Étage:</strong> <?= htmlspecialchars($appartement['etage']) ?></p>
        </div>

        <div class="carousel-container">
            <div class="carousel">
                <?php foreach ($images as $image): ?>
                    <div class="carousel-item">
                        <img src="<?= htmlspecialchars($image['url']) ?>" alt="Appartement Image">
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-btn prev">◀</button>
            <button class="carousel-btn next">▶</button>
        </div>

        <a href="appartements.php" class="back-btn">Retour à la liste</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const carousel = document.querySelector('.carousel');
            const items = document.querySelectorAll('.carousel-item');
            const prevButton = document.querySelector('.carousel-btn.prev');
            const nextButton = document.querySelector('.carousel-btn.next');
            let currentIndex = 0;

            function updateCarousel() {
                const offset = -currentIndex * 100;
                carousel.style.transform = `translateX(${offset}%)`;
            }

            function showNext() {
                currentIndex = (currentIndex + 1) % items.length;
                updateCarousel();
            }

            function showPrev() {
                currentIndex = (currentIndex - 1 + items.length) % items.length;
                updateCarousel();
            }

            prevButton.addEventListener('click', showPrev);
            nextButton.addEventListener('click', showNext);

            // Défilement automatique (optionnel)
            let autoScroll = setInterval(showNext, 5000);

            // Arrêter le défilement automatique lorsque l'utilisateur interagit
            carousel.addEventListener('mouseenter', () => clearInterval(autoScroll));
            carousel.addEventListener('mouseleave', () => autoScroll = setInterval(showNext, 5000));
        });
    </script>
</body>
</html>