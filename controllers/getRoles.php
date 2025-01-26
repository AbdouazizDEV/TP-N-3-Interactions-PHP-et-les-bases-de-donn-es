<!-- Récupérer les roles dans la base de données - -->
<?php

// Récupérer les roles dans la base de données
$sql = "SELECT * FROM Role";
$stmt = $pdo->query($sql);
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>