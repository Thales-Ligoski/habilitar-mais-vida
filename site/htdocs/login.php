<?php
// Start session
session_start();

// Habilitar exibição de erros para debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Processar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['username'] = 'Administrador';
        $_SESSION['email'] = 'admin@habilitarmaisvida.com';
        error_log("Login bem-sucedido. Sessão: " . print_r($_SESSION, true));
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Usuário ou senha inválidos.";
        error_log("Tentativa de login falhou. Usuário: $username");
    }
}

// Handle logout
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    // Destroy the session
    session_destroy();
    
    // Redirect to index page
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Habilitar Mais Vida</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos adicionais para responsividade */
        @media (max-width: 576px) {
            .login-container {
                padding: 20px;
                width: 90%;
                margin: 0 auto;
            }
            
            .form-buttons {
                flex-direction: column;
                gap: 15px;
            }
            
            .login-button, .back-button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="container">
            <div class="logo">
                <a href="index.php">
                    <img src="assets/images/logo.png" alt="Logo Habilitar Mais Vida">
                </a>
            </div>
            
            <div class="center-link">
                <a href="index.php">Início</a>
            </div>
            
            <div class="nav-buttons">
                <button id="theme-toggle" class="theme-toggle">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Login Section -->
    <section class="login">
        <div class="container">
            <div class="login-container">
                <h1>Acesso Administrativo</h1>
                <form id="loginForm" method="post">
                    <div class="form-group">
                        <label for="username">Usuário</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div id="errorMessage" class="error-message"><?php echo $error ?? ''; ?></div>
                    <div class="form-buttons">
                        <button type="submit" class="login-button">Entrar</button>
                        <a href="index.php" class="back-button">Voltar para página principal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="assets/images/logo-footer.png" alt="Logo Habilitar Mais Vida">
                    <p>© 2025 Habilitar Mais Vida. Todos os direitos reservados.</p>
                </div>
                <div class="footer-links">
                    <a href="termo.php">Termo de Uso</a>
                    <a href="privacidade.php">Política de Privacidade</a>
                    <a href="login.php">Login</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
    <script>
        // Script para alternar o tema
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('theme-toggle');
            
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const htmlElement = document.documentElement;
                    const currentTheme = htmlElement.getAttribute('data-theme');
                    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                    
                    htmlElement.setAttribute('data-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                    
                    // Atualizar ícone do botão
                    const icon = this.querySelector('i');
                    if (newTheme === 'dark') {
                        icon.classList.remove('fa-moon');
                        icon.classList.add('fa-sun');
                    } else {
                        icon.classList.remove('fa-sun');
                        icon.classList.add('fa-moon');
                    }
                });
            }
            
            // Inicializar tema ao carregar a página
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                document.documentElement.setAttribute('data-theme', savedTheme);
                
                // Atualizar ícone do botão de tema
                if (themeToggle) {
                    const icon = themeToggle.querySelector('i');
                    if (savedTheme === 'dark') {
                        icon.classList.remove('fa-moon');
                        icon.classList.add('fa-sun');
                    } else {
                        icon.classList.remove('fa-sun');
                        icon.classList.add('fa-moon');
                    }
                }
            }
        });
    </script>
</body>
</html>

