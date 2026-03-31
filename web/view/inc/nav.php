<?php
// inc/nav.php - Barre de navigation latérale (sidebar)
if (!isset($_SESSION)) {
    session_start();
}
?>

<aside class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <!-- <span class="logo-icon">🇮🇷</span> -->
            <span class="logo-text">Iran News</span>
        </div>
        <span class="logo-badge">Admin</span>
    </div>
    
    <nav class="sidebar-nav">
    <a href="/web/view/dashboardAdmin.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2h-5v-7H9v7H4a2 2 0 0 1-2-2z"/>
            </svg>
            <span>Dashboard</span>
        </a>
        
    <a href="/web/view/dashboardAdmin.php#add-article" class="nav-link">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="12" y1="18" x2="12" y2="12"/>
                <line x1="9" y1="15" x2="15" y2="15"/>
            </svg>
            <span>Nouvel article</span>
        </a>
        
    <a href="/web/view/dashboardAdmin.php#list-articles" class="nav-link">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="12 2 2 7 12 12 22 7 12 2"/>
                <polyline points="2 17 12 22 22 17"/>
                <polyline points="2 12 12 17 22 12"/>
            </svg>
            <span>Articles</span>
        </a>
    </nav>
    
    <div class="sidebar-footer">
        <div class="user-info-sidebar">
            <div class="user-avatar">
                <span><?= strtoupper(substr($_SESSION['username'], 0, 1)) ?></span>
            </div>
            <div class="user-details">
                <span class="user-name"><?= htmlspecialchars($_SESSION['username']) ?></span>
                <span class="user-role">Administrateur</span>
            </div>
        </div>
    <a href="/web/view/logout.php" class="logout-btn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            <span>Déconnexion</span>
        </a>
    </div>
</aside>