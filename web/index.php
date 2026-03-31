<?php
// On charge les fichiers nécessaires
require_once('view/config/db.php');
require_once('../app/models/Article.php');

// Connexion à la base


// Routage simple
$action = $_GET['action'] ?? 'home';
$id = $_GET['id'] ?? null;

if ($pdo === null) {
    die("La connexion à la base de données a échoué. Vérifiez vos paramètres XAMPP.");
}

switch($action) {
    case 'article':
        $article = getArticleById($pdo, $id); 
        $titre_page = $article ? $article['titre'] : "Article introuvable";
        $view = 'article_details.php';
        break;
        
    default:
        $articles = getAllArticles($pdo); 
        $titre_page = "Accueil - Guerre en Iran";
        $view = 'home.php';
        break;
}

// Construction de la page
include('../app/views/layouts/header.php');
include('../app/views/' . $view);
// Le footer ferme les balises </main> </body> </html>
include('../app/views/layouts/footer.php');