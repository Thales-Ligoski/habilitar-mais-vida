<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termo de Uso - Habilitar Mais Vida</title>
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

    <!-- Terms Content -->
    <section class="terms">
        <div class="container">
            <h1 class="section-title">Termo de Uso</h1>
            <div class="terms-content">
                <h2>1. Aceitação dos Termos</h2>
                <p>
                    Ao acessar e utilizar este site, você concorda em cumprir e estar vinculado aos seguintes termos e condições de uso. 
                    Se você não concordar com qualquer parte destes termos, não deverá usar nosso site.
                </p>
                
                <h2>2. Uso do Site</h2>
                <p>
                    O conteúdo deste site é apenas para fins informativos e educacionais. 
                    As informações sobre primeiros socorros e procedimentos médicos fornecidas não substituem aconselhamento, diagnóstico ou tratamento médico profissional.
                </p>
                
                <h2>3. Propriedade Intelectual</h2>
                <p>
                    Todo o conteúdo deste site, incluindo textos, gráficos, logos, ícones, imagens, clipes de áudio, downloads digitais 
                    e compilações de dados, é propriedade exclusiva da nossa empresa ou de nossos fornecedores de conteúdo e está protegido por leis nacionais 
                    e internacionais de direitos autorais.
                </p>
                
                <h2>4. Limitação de Responsabilidade</h2>
                <p>
                    As informações fornecidas neste site são apresentadas "como estão" sem qualquer garantia, expressa ou implícita. 
                    Em nenhum caso seremos responsáveis por quaisquer danos diretos, indiretos, incidentais, especiais ou consequentes 
                    resultantes do uso ou incapacidade de usar este site ou o conteúdo nele apresentado.
                </p>
                
                <h2>5. Links para Sites de Terceiros</h2>
                <p>
                    Este site pode conter links para sites de terceiros que não são controlados por nós. Não somos responsáveis pelo 
                    conteúdo ou práticas de privacidade desses sites. A inclusão de qualquer link não implica endosso por nossa parte.
                </p>
                
                <h2>6. Modificações</h2>
                <p>
                    Reservamo-nos o direito de revisar estes termos a qualquer momento sem aviso prévio. Ao usar este site, você concorda 
                    em ficar vinculado à versão atual destes Termos de Serviço.
                </p>
                
                <h2>7. Lei Aplicável</h2>
                <p>
                    Estes termos serão regidos e interpretados de acordo com as leis do Brasil, sem consideração a conflitos de disposições legais.
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

