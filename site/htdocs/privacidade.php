<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidade - Habilitar Mais Vida</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos adicionais para responsividade */
        @media (max-width: 768px) {
            .terms-content, .privacy-content {
                padding: 20px;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
        }
        
        @media (max-width: 576px) {
            .terms-content, .privacy-content {
                padding: 15px;
            }
            
            .terms-content h2, .privacy-content h2 {
                font-size: 1.3rem;
            }
            
            .terms-content p, .privacy-content p {
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/5500000000000" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

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

    <!-- Privacy Content -->
    <section class="privacy">
        <div class="container">
            <h1 class="section-title">Política de Privacidade</h1>
            <div class="privacy-content">
                <h2>1. Coleta de Informações</h2>
                <p>
                    Coletamos informações pessoais como nome, endereço de e-mail, número de telefone e outras informações 
                    que você nos fornece voluntariamente ao preencher formulários em nosso site, se inscrever em treinamentos 
                    ou solicitar informações.
                </p>
                
                <h2>2. Uso das Informações</h2>
                <p>
                    Utilizamos as informações coletadas para fornecer, manter e melhorar nossos serviços, processar transações, 
                    enviar comunicações relacionadas aos serviços, fornecer suporte ao cliente e cumprir obrigações legais.
                </p>
                
                <h2>3. Cookies e Tecnologias Semelhantes</h2>
                <p>
                    Utilizamos cookies e tecnologias semelhantes para melhorar a experiência do usuário, entender padrões de uso do site 
                    e personalizar conteúdo. Você pode controlar o uso de cookies através das configurações do seu navegador.
                </p>
                
                <h2>4. Compartilhamento de Informações</h2>
                <p>
                    Não vendemos, alugamos ou trocamos suas informações pessoais com terceiros para fins de marketing. 
                    Podemos compartilhar suas informações com prestadores de serviços de confiança que nos auxiliam na operação 
                    do nosso site e na prestação de serviços, sempre sob rigorosos acordos de confidencialidade.
                </p>
                
                <h2>5. Segurança de Dados</h2>
                <p>
                    Implementamos medidas de segurança adequadas para proteger suas informações pessoais contra acesso não autorizado, 
                    alteração, divulgação ou destruição. No entanto, nenhum método de transmissão pela internet ou método de armazenamento 
                    eletrônico é 100% seguro.
                </p>
                
                <h2>6. Direitos do Titular dos Dados</h2>
                <p>
                    Você tem o direito de acessar, corrigir, atualizar ou solicitar a exclusão de seus dados pessoais. 
                    Para exercer esses direitos, entre em contato conosco através dos canais disponibilizados abaixo.
                </p>
                
                <h2>7. Alterações na Política de Privacidade</h2>
                <p>
                    Podemos atualizar nossa Política de Privacidade periodicamente para refletir mudanças em nossas práticas de informação. 
                    Recomendamos que você revise esta página regularmente para obter as informações mais recentes sobre nossas práticas de privacidade.
                </p>
                
                <h2>8. Contato</h2>
                <p>
                    Se você tiver dúvidas sobre esta Política de Privacidade ou sobre o tratamento de seus dados pessoais, 
                    entre em contato conosco através do e-mail: contato@habilitarmaisvida.com
                </p>
                
                <p><strong>Data da última atualização:</strong> 01 de abril de 2025</p>
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

