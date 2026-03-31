<?php
// 1. POUR L'ACCUEIL : Récupérer tous les articles AVEC leur image principale
function getAllArticles($pdo) {
    // On ajoute la jointure LEFT JOIN info_images pour que l'accueil trouve 'image_url'
    $sql = "SELECT i.*, r.nom as region_nom, img.path
            FROM info i 
            LEFT JOIN region r ON i.region_id = r.id 
            LEFT JOIN image_info img ON i.id = img.id 
            GROUP BY i.id 
            ORDER BY date_publication DESC";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

// 2. POUR LES DÉTAILS : Récupérer les infos d'un seul article
function getArticleById($pdo, $id) {
    $sql = "SELECT i.*, r.nom as region_nom 
            FROM info i 
            LEFT JOIN region r ON i.region_id = r.id 
            WHERE i.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// 3. POUR LES DÉTAILS : Récupérer TOUTES les images d'un article (Galerie)
function getArticleImages($pdo, $info_id) {
    $sql = "SELECT * FROM image_info WHERE info_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$info_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>