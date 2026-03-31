<?php
require_once './inc/function.php';
session_start();

$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: " . ($isLoggedIn ? "dashboard.php" : "/"));
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("
      SELECT 
          info.id,
          info.titre,
          info.date_publication,
          info.contenu,
          region.nom AS region,
          pays.nom AS pays,
          img.path AS image_path
        FROM info
        JOIN region ON info.region_id = region.id
        JOIN pays ON region.pays_id = pays.id
    LEFT JOIN image_info img ON info.id = img.idinfo
    WHERE info.id = :id
");
$stmt->execute(['id' => $id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    header("Location: " . ($isLoggedIn ? "dashboard.php" : "/"));
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['titre']) ?> | Iran News</title>
    
    <link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:title" content="<?= htmlspecialchars($article['titre'], ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:description" content="<?= $metaDescription ?>">
    <?php if (!empty($ogImage)): ?>
        <meta property="og:image" content="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
    <meta property="og:type" content="article">
    <meta property="og:url" content="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="preload" href="../css/style.min.css" as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="../css/style.min.css"></noscript>
    <style>
        /* Styles spécifiques pour la page article */
        .article-page {
            background: #f3f4f6;
            min-height: 100vh;
        }
        
        /* Conteneur principal avec la navbar */
        .article-wrapper {
            margin-left: 260px; /* Même largeur que la sidebar */
            padding: 2rem;
            min-height: 100vh;
        }
        
        .article-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .article-card {
            background: white;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }
        
        .article-title {
            padding: 2rem 2rem 1rem;
        }
        
        .article-title h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            line-height: 1.3;
            margin-bottom: 1rem;
        }
        
        .article-metadata {
            padding: 0 2rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .article-metadata span {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .article-image-full {
            width: 100%;
            max-height: 450px;
            object-fit: cover;
        }
        
        .article-body {
            padding: 2rem;
            font-size: 1rem;
            line-height: 1.7;
            color: #374151;
        }
        
        /* Styles pour le contenu TinyMCE */
        .article-body h2 {
            font-size: 1.75rem;
            font-weight: 600;
            margin: 1.75rem 0 1rem;
            color: #1f2937;
        }
        
        .article-body h3 {
            font-size: 1.375rem;
            font-weight: 600;
            margin: 1.5rem 0 0.75rem;
            color: #374151;
        }
        
        .article-body h4 {
            font-size: 1.125rem;
            font-weight: 600;
            margin: 1.25rem 0 0.5rem;
            color: #4b5563;
        }
        
        .article-body p {
            margin-bottom: 1rem;
        }
        
        .article-body img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 1rem 0;
        }
        
        .article-body ul, 
        .article-body ol {
            margin: 1rem 0 1.5rem;
            padding-left: 2rem;
        }
        
        .article-body li {
            margin: 0.5rem 0;
        }
        
        .article-body blockquote {
            border-left: 4px solid #2563eb;
            padding-left: 1rem;
            margin: 1rem 0;
            color: #4b5563;
            font-style: italic;
        }
        
        .article-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }
        
        .article-body th,
        .article-body td {
            border: 1px solid #e5e7eb;
            padding: 0.5rem;
            text-align: left;
        }
        
        .article-body th {
            background: #f9fafb;
        }
        
        .article-footer-actions {
            padding: 1rem 2rem;
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
            display: flex;
            gap: 1rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background: #2563eb;
            color: white;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary:hover {
            background: #1d4ed8;
        }
        
        .btn-secondary {
            background: white;
            border: 1px solid #e5e7eb;
            color: #374151;
        }
        
        .btn-secondary:hover {
            background: #f9fafb;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .article-wrapper {
                margin-left: 0;
                padding: 1rem;
            }
            
            .article-title h1 {
                font-size: 1.5rem;
            }
            
            .article-body {
                padding: 1.5rem;
            }
            
            .article-title {
                padding: 1.5rem 1.5rem 0.5rem;
            }
            
            .article-metadata {
                padding: 0 1.5rem 1rem;
            }
            
            .article-footer-actions {
                padding: 1rem 1.5rem;
            }
        }
    </style>
</head>
<body class="article-page">
    <!-- Inclusion de la navbar -->
    <?php include './inc/nav.php'; ?>
    
    <!-- Contenu principal avec marge pour la navbar -->
    <div class="article-wrapper">
        <div class="article-container">
            <div class="article-card">
                <div class="article-title">
                    <h1><?= htmlspecialchars($article['titre']) ?></h1>
                </div>
                
                <div class="article-metadata">
                    <span><?= date('d/m/Y', strtotime($article['date_publication'])) ?></span>
                    <?php if (!empty($article['pays_nom'])): ?>
                        <span><?= htmlspecialchars($article['pays_nom']) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($article['region_nom'])): ?>
                        <span> <?= htmlspecialchars($article['region_nom']) ?></span>
                    <?php endif; ?>
                   
                </div>
                
                <?php if (!empty($article['image_path']) && file_exists("../" . $article['image_path'])): 
                    // Get intrinsic dimensions to avoid layout shift
                    $mainImgPath = "../" . $article['image_path'];
                    $mainWidth = null; $mainHeight = null;
                    if (file_exists($mainImgPath)) {
                        $size = @getimagesize($mainImgPath);
                        if ($size) {
                            $mainWidth = $size[0];
                            $mainHeight = $size[1];
                        }
                    }
                ?>
                    <img src="../<?= htmlspecialchars($article['image_path']) ?>" alt="<?= htmlspecialchars($article['titre']) ?>" class="article-image-full"<?= $mainWidth && $mainHeight ? " width=\"{$mainWidth}\" height=\"{$mainHeight}\"" : '' ?> loading="lazy" decoding="async">
                <?php endif; ?>

                <div class="article-body">
                    <?php
                    // Parse article HTML and add width/height/loading for local images to reduce CLS
                    $content = $article['contenu'];
                    if (!empty($content)) {
                        libxml_use_internal_errors(true);
                        $dom = new DOMDocument();
                        // Ensure proper UTF-8 handling
                        $dom->loadHTML('<?xml encoding="utf-8"?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                        $imgs = $dom->getElementsByTagName('img');
                        foreach ($imgs as $img) {
                            $src = $img->getAttribute('src');
                            // Skip external images
                            if (preg_match('#^https?://#i', $src)) {
                                // but still lazily load external images
                                if (!$img->hasAttribute('loading')) $img->setAttribute('loading', 'lazy');
                                continue;
                            }
                            // Resolve local path relative to this file
                            $candidate = $src;
                            // If path starts with ../ or ./ handle accordingly; otherwise assume relative to web/
                            if (strpos($candidate, '../') === 0 || strpos($candidate, './') === 0) {
                                $localPath = __DIR__ . '/' . $candidate;
                            } else {
                                $localPath = __DIR__ . '/../' . ltrim($candidate, '/');
                            }
                            // Normalize path
                            $localPath = realpath($localPath);
                            if ($localPath && file_exists($localPath)) {
                                $size = @getimagesize($localPath);
                                if ($size) {
                                    if (!$img->hasAttribute('width')) $img->setAttribute('width', $size[0]);
                                    if (!$img->hasAttribute('height')) $img->setAttribute('height', $size[1]);
                                }
                                if (!$img->hasAttribute('loading')) $img->setAttribute('loading', 'lazy');
                                if (!$img->hasAttribute('decoding')) $img->setAttribute('decoding', 'async');
                            } else {
                                // Fallback: ensure lazy loading to avoid large layout shifts
                                if (!$img->hasAttribute('loading')) $img->setAttribute('loading', 'lazy');
                            }
                        }
                        // Output modified HTML
                        $bodyHtml = '';
                        $bodyNode = $dom->getElementsByTagName('body')->item(0);
                        if ($bodyNode) {
                            foreach ($bodyNode->childNodes as $child) {
                                $bodyHtml .= $dom->saveHTML($child);
                            }
                        } else {
                            // Fallback: if no body element was created, output entire document's HTML
                            $bodyHtml = $dom->saveHTML();
                        }
                        echo $bodyHtml;
                        libxml_clear_errors();
                    } else {
                        echo '';
                    }
                    ?>
                </div>
                
                <?php if ($isLoggedIn): ?>
                    <div class="article-footer-actions">
                        <a href="editArticle.php?id=<?= $article['id'] ?>" class="btn btn-primary">
                             Modifier
                        </a>
                        <a href="dashboard.php" class="btn btn-secondary">
                            ← Retour au dashboard
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>