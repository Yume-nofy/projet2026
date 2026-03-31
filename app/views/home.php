<div class="container">
    <h1 style="font-family: 'Lora', serif; font-size: 2.5rem; margin-bottom: 10px;">Actualités du Conflit</h1>
    <p style="color: #666; margin-bottom: 30px;">Direct de la région : les dernières analyses de nos correspondants.</p>

    <div class="news-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        <?php if (!empty($articles)): ?>
            <?php foreach ($articles as $article): ?>
                <div class="article-card" style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden; background: #fff;">
                    <div style="height: 200px; overflow: hidden;">
                        <img src="/<?php echo $article['path']; ?>" alt="Photo de l'article" style="width:100%; height:auto;"> 
                             alt="<?php echo htmlspecialchars($article['titre']); ?>"
                             style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    
                    <div style="padding: 20px;">
                        <span class="region-badge" style="background: #e63946; color: #fff; padding: 3px 8px; border-radius: 4px; font-size: 0.8rem;">
                            <?php echo htmlspecialchars($article['region_nom']); ?>
                        </span>
                        
                        <h2 style="font-size: 1.25rem; margin: 15px 0;"><?php echo htmlspecialchars($article['titre']); ?></h2>
                        
                        <p style="color: #555; font-size: 0.9rem;">
                            <?php echo substr(strip_tags($article['contenu']), 0, 100); ?>...
                        </p>
                        
                        <a href="article-<?php echo $article['slug']; ?>-<?php echo $article['id']; ?>.html" 
                           style="display: inline-block; margin-top: 15px; color: #e63946; font-weight: bold; text-decoration: none;">
                           Lire l'enquête →
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun article trouvé dans la base de données.</p>
        <?php endif; ?>
    </div>
</div>