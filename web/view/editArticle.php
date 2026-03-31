<?php
require_once './inc/function.php';
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: /view/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("ID manquant");
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM info WHERE id = :id");
$stmt->execute(['id' => $id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    die("Article introuvable");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Critical inline CSS to avoid CLS on admin pages */
        .sidebar{width:260px;position:fixed;height:100vh;overflow:auto}
        .main-content{margin-left:260px}
        .user-avatar{width:36px;height:36px;display:flex;align-items:center;justify-content:center}
        .sidebar-footer{padding:1rem 1.5rem;border-top:1px solid #e5e7eb;margin-top:auto}
    </style>
    
    <title>Modifier l'article | Iran News</title>
    <link rel="preload" href="../css/style.min.css" as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="../css/style.min.css"></noscript>
</head>
<body class="dashboard-page">
    <div class="dashboard-wrapper">
        <?php include './inc/nav.php'; ?>

        <main class="main-content">
            <header class="top-header">
                <div class="header-title">
                    <h1>Modifier l'article</h1>
                    <p>Modifiez le contenu de votre article</p>
                </div>
                <div class="header-stats">
                    <div class="stat-badge">
                        <span class="stat-label">ID Article</span>
                        <span class="stat-number"><?= $article['id'] ?></span>
                    </div>
                </div>
            </header>

            <section class="content-card">
                <div class="card-header">
                    <h2>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 14.66V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.34"/>
                            <polygon points="18 2 22 6 12 16 8 16 8 12 18 2"/>
                        </svg>
                        Formulaire de modification
                    </h2>
                    <p>Modifiez les champs ci-dessous</p>
                </div>
                
                <form action="updateArticle.php" method="POST" enctype="multipart/form-data" class="article-form" id="editForm">
                    <input type="hidden" name="id" value="<?= $article['id'] ?>">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="titre">Titre de l'article</label>
                            <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($article['titre']) ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contenu">Contenu de l'article</label>
                        <textarea class="tinymce-editor" name="contenu" id="contenu"><?= htmlspecialchars($article['contenu']) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image">Nouvelle image (optionnel)</label>
                        <div class="file-upload">
                            <input type="file" name="image" id="image" accept="image/*">
                            <label for="image" class="file-label">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                                Choisir une nouvelle image
                            </label>
                            <span class="file-name">Aucun fichier sélectionné</span>
                        </div>
                        <small style="color: #6b7280; font-size: 0.7rem;">Laissez vide pour conserver l'image actuelle</small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                <polyline points="17 21 17 13 7 13 7 21"/>
                                <polyline points="7 3 7 8 15 8"/>
                            </svg>
                            Mettre à jour
                        </button>
                        <a href="dashboard.php" class="btn-secondary">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"/>
                                <line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                            Annuler
                        </a>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <script src="../js/admin.min.js" defer></script>
    <script src="../js/imagePreview.js" defer></script>
</body>
</html>