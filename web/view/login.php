<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Information-Admin | Connexion</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    
    <form action="traitementLogin.php" method="POST" id="loginForm">
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

    <script>
    (function() {
        const form = document.getElementById('loginForm');
        const submitBtn = form.querySelector('input[type="submit"]');
        
        form.addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!username || !password) {
                e.preventDefault();
                showMessage('Veuillez remplir tous les champs', 'error');
                return;
            }
            
            submitBtn.classList.add('loading');
            submitBtn.value = 'Connexion en cours...';
        });
        
        function showMessage(message, type) {
            const container = document.getElementById('messageContainer');
            const existingMessage = container.querySelector('.error-message, .success-message');
            if (existingMessage) {
                existingMessage.remove();
            }
            
            const messageDiv = document.createElement('div');
            messageDiv.className = type === 'error' ? 'error-message' : 'success-message';
            messageDiv.textContent = message;
            container.appendChild(messageDiv);
            
            setTimeout(() => {
                messageDiv.style.opacity = '0';
                setTimeout(() => messageDiv.remove(), 300);
            }, 5000);
        }
        
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');
        if (error === 'invalid') {
            showMessage('Nom d\'utilisateur ou mot de passe incorrect', 'error');
        } else if (error === 'timeout') {
            showMessage('Session expirée, veuillez vous reconnecter', 'error');
        }
    })();
    </script>
</body>
</html>