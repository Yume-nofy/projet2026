<?php 
require_once './inc/function.php';
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: /web/view/login.php");
    exit();
}
?>
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success" style="background: #dcfce7; color: #166534; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; border-left: 3px solid #22c55e;">
        <?= htmlspecialchars($_SESSION['success']) ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error" style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; border-left: 3px solid #ef4444;">
        <?= htmlspecialchars($_SESSION['error']) ?>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Critical inline CSS to avoid CLS: reserve sidebar + main content spacing
           This ensures layout doesn't jump before the main stylesheet loads. */
        .sidebar{width:260px;position:fixed;height:100vh;overflow:auto}
        .main-content{margin-left:260px}
        .user-avatar{width:36px;height:36px;display:flex;align-items:center;justify-content:center}
        .sidebar-footer{padding:1rem 1.5rem;border-top:1px solid #e5e7eb;margin-top:auto}
    </style>
    <title>Dashboard Admin | Iran News</title>
        <link rel="preload" href="../css/style.min.css" as="style" onload="this.rel='stylesheet'">
        <noscript><link rel="stylesheet" href="../css/style.min.css"></noscript>
</head>
<body class="dashboard-page">
    <div class="dashboard-wrapper">
        <!-- Inclusion de la navigation -->
        <?php include './inc/nav.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-header">
                <div class="header-title">
                    <h1>Tableau de bord</h1>
                    <p>Gérez vos articles et contenus</p>
                </div>
                <div class="header-stats">
                    <div class="stat-badge">
                        <span class="stat-label">Total articles</span>
                        <span class="stat-number"><?= count(getAllArticles($pdo)) ?></span>
                    </div>
                </div>
            </header>

            <!-- Add Article Section -->
            <section id="add-article" class="content-card">
                <div class="card-header">
                    <h2>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="12" y1="18" x2="12" y2="12"/>
                            <line x1="9" y1="15" x2="15" y2="15"/>
                        </svg>
                        Nouvel article
                    </h2>
                    <p>Créez et publiez un nouvel article sur l'Iran</p>
                </div>
                
                <form action="traitementArticle.php" method="POST" enctype="multipart/form-data" class="article-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="titre">Titre de l'article</label>
                            <input type="text" name="titre" id="titre" placeholder="Ex: Les trésors cachés de Persépolis" required>
                        </div>
                    </div>

                    <div class="form-row two-cols">
                        <div class="form-group">
                            <label for="pays">Pays</label>
                            <select name="idPays" id="pays">
                                <?php
                                $pays = getAllPays($pdo);
                                foreach ($pays as $p) {
                                    echo "<option value='{$p['id']}'>{$p['nom']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="region">Région</label>
                            <select name="idRegion" id="region">
                                <?php
                                $regions = getAllRegions($pdo);
                                foreach ($regions as $r) {
                                    echo "<option value='{$r['id']}'>{$r['nom']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contenu">Contenu de l'article</label>
                        <textarea class="tinymce-editor" name="contenu" placeholder="Rédigez votre article ici..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image">Image à la une</label>
                        <div class="file-upload">
                            <input type="file" name="image" id="image" accept="image/*">
                            <label for="image" class="file-label">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                                Choisir une image
                            </label>
                            <span class="file-name">Aucun fichier sélectionné</span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                <polyline points="17 21 17 13 7 13 7 21"/>
                                <polyline points="7 3 7 8 15 8"/>
                            </svg>
                            Publier l'article
                        </button>
                        <button type="reset" class="btn-secondary">Réinitialiser</button>
                    </div>
                </form>
            </section>

            <!-- Articles List Section -->
            <section id="list-articles" class="content-card">
                <div class="card-header">
                    <h2>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 2 7 12 12 22 7 12 2"/>
                            <polyline points="2 17 12 22 22 17"/>
                            <polyline points="2 12 12 17 22 12"/>
                        </svg>
                        Articles existants
                    </h2>
                    <p><?= count(getAllArticles($pdo)) ?> article(s) au total</p>
                </div>
                
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Pays</th>
                                <th>Région</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $articles = getAllArticles($pdo);
                            if (empty($articles)) {
                                echo '<tr><td colspan="6" class="empty-state">Aucun article trouvé</td></tr>';
                            } else {
                                foreach ($articles as $a) {
                                    echo "<tr>
                                        <td data-label='ID'>{$a['id']}</td>
                                        <td data-label='Titre' class='article-title'>" . htmlspecialchars($a['titre']) . "</td>
                                        <td data-label='Pays'>" . htmlspecialchars($a['pays']) . "</td>
                                        <td data-label='Région'>" . htmlspecialchars($a['region']) . "</td>
                                        <td data-label='Date'>" . date('d/m/Y', strtotime($a['date_publication'])) . "</td>
                                        <td data-label='Actions' class='actions'>
                                            <a href='editArticle.php?id={$a['id']}' class='action-btn edit' title='Modifier'>
                                                <svg width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'>
                                                    <path d='M20 14.66V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.34'/>
                                                    <polygon points='18 2 22 6 12 16 8 16 8 12 18 2'/>
                                                </svg>
                                            </a>
                                            <a href='deleteArticle.php?id={$a['id']}' class='action-btn delete' onclick='return confirm(\"Supprimer cet article ?\");' title='Supprimer'>
                                                <svg width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'>
                                                    <polyline points='3 6 5 6 21 6'/>
                                                    <path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'/>
                                                </svg>
                                            </a>
                                            <a href='viewArticle.php?id={$a['id']}' class='action-btn view' title='Voir'>
                                                <svg width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'>
                                                    <path d='M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z'/>
                                                    <circle cx='12' cy='12' r='3'/>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script src="../js/admin.min.js" defer></script>
    <script src="../js/imagePreview.js" defer></script>
</body>
</html>