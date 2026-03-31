<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="robots" content="noindex, nofollow">
    <title>Information-Admin | Connexion</title>
    <link rel="preload" href="../css/style2.css" as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="../css/style2.css"></noscript>
</head>
<body>
    
    <form action="/web/view/traitementLogin.php" method="POST" id="loginForm">
        <div id="messageContainer"></div>
 
        <label for="username">Nom d'utilisateur</label>
        <input type="text" id="username" value="admin" name="username" placeholder="Entrez votre nom d'utilisateur" required autocomplete="username">
        
        <label for="password">Mot de passe</label>
        <input type="password" id="password" value="MrAdmin"  name="password" placeholder="••••••••" required autocomplete="current-password">
        
        <input type="submit" value="Se connecter">
        
        <div class="login-footer">
            <p>© 2024 - Administration Iran | <a href="#">Mot de passe oublié ?</a></p>
        </div>
    </form>

    <script src="../js/login.min.js" defer></script>
</body>
</html>