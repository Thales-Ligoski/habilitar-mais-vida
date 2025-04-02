<?php
// Verificar se o usuário está logado como administrador
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit;
}

// Configurar exibição de erros  !== true) {
    header('Location: login.php');
    exit;
}

// Configurar exibição de erros
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Função para ler o arquivo de log
function readErrorLog() {
    $logFile = __DIR__ . '/error.log';
    if (file_exists($logFile)) {
        return file_get_contents($logFile);
    }
    return "Nenhum log de erro encontrado.";
}

// Exibir o conteúdo do log
$logContent = readErrorLog();
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log de Erros - Habilitar Mais Vida</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos adicionais para responsividade */
        @media (max-width: 768px) {
            .log-content {
                padding: 15px;
                font-size: 0.9rem;
            }
            
            .error-log h1 {
                font-size: 1.8rem;
            }
        }
        
        @media (max-width: 576px) {
            .log-content {
                padding: 10px;
                font-size: 0.8rem;
            }
            
            .error-log h1 {
                font-size: 1.5rem;
            }
        }
        
        /* Menu mobile */
        .menu-toggle {
            display: none;
            font-size: 24px;
            color: var(--text-primary);
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .main-menu {
                position: fixed;
                top: 80px;
                left: -100%;
                width: 80%;
                height: calc(100vh - 80px);
                background-color: var(--background);
                box-shadow: 0 5px 15px var(--shadow-color);
                transition: left 0.3s ease;
                z-index: 100;
            }

            .main-menu.active {
                left: 0;
            }

            .main-menu ul {
                flex-direction: column;
                padding: 20px;
            }

            .main-menu a {
                display: block;
                padding: 15px;
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
            
            <div class="menu-toggle" id="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
            
            <nav class="main-menu" id="main-menu">
                <ul>
                    <li><a href="dashboard.php">Painel</a></li>
                    <li><a href="index.php">Voltar ao Site</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Log Content -->
    <section class="error-log">
        <div class="container">
            <h1>Log de Erros</h1>
            <div class="log-content">
                <pre><?php echo htmlspecialchars($logContent); ?></pre>
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
            </div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
    <script>
        // Script para o menu mobile
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const mainMenu = document.getElementById('main-menu');
            
            if (menuToggle && mainMenu) {
                menuToggle.addEventListener('click', function() {
                    mainMenu.classList.toggle('active');
                });
                
                // Fechar menu ao clicar em um link
                const menuLinks = mainMenu.querySelectorAll('a');
                menuLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        mainMenu.classList.remove('active');
                    });
                });
            }
            
            // Fechar menu ao clicar fora
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.main-menu') && !event.target.closest('.menu-toggle')) {
                    if (mainMenu.classList.contains('active')) {
                        mainMenu.classList.remove('active');
                    }
                }
            });
        });
    </script>
</body>
</html>

