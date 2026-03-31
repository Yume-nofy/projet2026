<div class="progress-container">
    <div class="progress-bar" id="readingBar"></div>
</div>

<div class="container" style="max-width: 900px; margin: 0 auto; padding: 40px 20px;">
    
    <nav style="margin-bottom: 30px;">
        <a href="/projet2026-main/web/" class="back-link">
            <span style="font-size: 1.2rem;">←</span> TOUTES LES ACTUALITÉS
        </a>
    </nav>

    <?php if ($article): ?>
        <article class="main-article">
            
            <header style="margin-bottom: 40px;">
                <h1 class="article-full-title"><?php echo htmlspecialchars($article['titre']); ?></h1>
                
                <div class="meta-info">
                    <span class="author">PAR LA RÉDACTION</span>
                    <span class="separator">•</span>
                    <span class="date">LE <?php echo date('d/m/Y', strtotime($article['date_publication'])); ?></span>
                    <span class="separator">•</span>
                    <span class="location"><?php echo htmlspecialchars($article['region_nom']); ?></span>
                </div>
            </header>

            <div class="article-body">
                <?php echo nl2br(htmlspecialchars($article['contenu'])); ?>
            </div>

            <section class="multimedia-section">
                <h3 style="text-transform: uppercase; letter-spacing: 1px; margin-bottom: 25px; border-bottom: 2px solid #d9534f; display: inline-block;">
                    Galerie du reportage
                </h3>
                
                <div class="gallery-grid">
                    <?php if (!empty($images)): ?>
                        <?php foreach ($images as $img): ?>
                            <div class="gallery-item-wrapper">
                                <div class="gallery-item">
                                    <img src="/projet2026-main/public/images/<?php echo $img['image_url']; ?>" alt="Photo du reportage">
                                    <div class="caption">
                                        📸 <?php echo htmlspecialchars($img['image_alt']); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="font-style: italic; color: #888;">Aucun visuel supplémentaire disponible pour ce rapport.</p>
                    <?php endif; ?>
                </div>
            </section>
        </article>
    <?php else: ?>
        <div class="not-found">
            <h2 style="font-size: 2.5rem; color: #d9534f;">Dépêche introuvable</h2>
            <p>Le contenu que vous recherchez n'est pas disponible dans nos archives.</p>
            <a href="/projet2026-main/web/" class="btn-read">Retourner à la une</a>
        </div>
    <?php endif; ?>
</div>

<script>
window.onscroll = function() {
    var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
    var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    var scrolled = (winScroll / height) * 100;
    document.getElementById("readingBar").style.width = scrolled + "%";
};
</script>