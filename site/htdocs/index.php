<?php
require_once 'includes/content_manager.php';

// Carregar dados do conteúdo
$hero = [
    'title' => getContent('hero', 'title') ?? 'Treinamentos Especializados em Primeiros Socorros',
    'description' => getContent('hero', 'description') ?? 'Aprenda técnicas que podem salvar vidas com profissionais qualificados e experientes.'
];

$advantages = json_decode(getContent('advantages', 'items') ?? '[]', true);
$advantagesTitle = getContent('advantages', 'title') ?? 'Nossas Vantagens';

$faq = json_decode(getContent('faq', 'items') ?? '[]', true);
$faqTitle = getContent('faq', 'title') ?? 'Perguntas Frequentes';

$team = json_decode(getContent('team', 'items') ?? '[]', true);
$teamTitle = getContent('team', 'title') ?? 'Nossa Equipe';

$services = json_decode(getContent('services', 'items') ?? '[]', true);
$servicesTitle = getContent('services', 'title') ?? 'Nossos Serviços';

$workshop = json_decode(getContent('workshop', 'data') ?? '{}', true);
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilitar Mais Vida - Treinamentos e Consultoria</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            
            <div class="menu-toggle" id="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
            
            <nav class="main-menu" id="main-menu">
                <ul>
                    <li><a href="#inicio">Início</a></li>
                    <li><a href="#vantagens">Vantagens</a></li>
                    <li><a href="#treinamentos">Treinamentos</a></li>
                    <li><a href="#equipe">Equipe</a></li>
                    <li><a href="#servicos">Serviços</a></li>
                </ul>
            </nav>
            
            <div class="nav-buttons">
                <button id="theme-toggle" class="theme-toggle">
                    <i class="fas fa-moon"></i>
                </button>
                <a href="https://wa.me/5500000000000" class="contact-button">
                    <i class="fab fa-whatsapp"></i> Contato
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="inicio" class="hero">
        <div class="container">
            <div class="hero-content">
                <h1><?php echo htmlspecialchars($hero['title']); ?></h1>
                <p><?php echo htmlspecialchars($hero['description']); ?></p>
                <?php if ($workshop['active'] ?? false): ?>
                <div id="workshop-button-container">
                    <a href="workshop.php" class="cta-button">Participar do Workshop</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Advantages Section -->
    <section id="vantagens" class="advantages">
        <div class="container">
            <h2 class="section-title"><?php echo htmlspecialchars($advantagesTitle); ?></h2>
            <div class="advantages-grid">
                <?php foreach ($advantages as $advantage): ?>
                <div class="advantage-card">
                    <div class="icon"><i class="fas <?php echo htmlspecialchars($advantage['icon']); ?>"></i></div>
                    <h3><?php echo htmlspecialchars($advantage['title']); ?></h3>
                    <p><?php echo htmlspecialchars($advantage['description']); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="faq">
        <div class="container">
            <h2 class="section-title"><?php echo htmlspecialchars($faqTitle); ?></h2>
            <div class="faq-container">
                <?php foreach ($faq as $item): ?>
                <div class="faq-item">
                    <div class="faq-question">
                        <h3><?php echo htmlspecialchars($item['question']); ?></h3>
                        <span class="faq-toggle"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p><?php echo htmlspecialchars($item['answer']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section id="equipe" class="team">
        <div class="container">
            <h2 class="section-title"><?php echo htmlspecialchars($teamTitle); ?></h2>
            <div class="team-grid">
                <?php foreach ($team as $member): ?>
                <div class="team-member">
                    <div class="member-image">
                        <img src="assets/images/team-1.jpg" alt="<?php echo htmlspecialchars($member['name']); ?>">
                    </div>
                    <div class="member-info">
                        <h3><?php echo htmlspecialchars($member['name']); ?></h3>
                        <h4><?php echo htmlspecialchars($member['role']); ?></h4>
                        <p><?php echo htmlspecialchars($member['bio']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="servicos" class="services">
        <div class="container">
            <h2 class="section-title"><?php echo htmlspecialchars($servicesTitle); ?></h2>
            <div class="services-grid">
                <?php foreach ($services as $service): ?>
                <div class="service-card">
                    <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                    <p><?php echo htmlspecialchars($service['description']); ?></p>
                </div>
                <?php endforeach; ?>
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

