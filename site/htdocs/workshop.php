<?php
session_start();
require_once 'includes/content_manager.php';

// Carregar dados do workshop
$workshopData = getAllContent('workshop');
$workshop = json_decode($workshopData['data'] ?? '{}', true) ?: [];

// Se o workshop não estiver ativo, redirecionar para a página inicial
if (!isset($workshop['active']) || !$workshop['active']) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop - <?php echo htmlspecialchars($workshop['title']); ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <style>
        /* Estilos do Carrossel */
        .workshop-carousel {
            margin-bottom: 60px;
        }

        .swiper {
            width: 100%;
            height: 500px;
            border-radius: 15px;
            overflow: hidden;
        }

        .swiper-slide {
            text-align: center;
            background: var(--bg-light);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            background: var(--primary-color);
            opacity: 0.5;
        }

        .swiper-pagination-bullet-active {
            opacity: 1;
        }

        /* Botão WhatsApp Flutuante */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #25d366;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            text-align: center;
            font-size: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        /* Restante dos estilos */
        .workshop {
            padding: 60px 0;
        }

        .workshop-images {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 60px;
        }

        .workshop-image {
            position: relative;
            padding-top: 66.67%; /* Proporção 3:2 */
            background-color: var(--bg-light);
        }

        .workshop-image img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .workshop-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0));
            border-radius: 10px;
            z-index: 1;
        }

        .workshop-image:hover img {
            transform: scale(1.05);
        }

        .workshop-topics {
            margin: 60px auto;
            text-align: center;
            max-width: 100%;
        }

        .workshop-topics h2 {
            color: var(--primary-color);
            margin-bottom: 40px;
            font-size: 2.5em;
        }

        .topics-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-bottom: 60px;
            max-width: 100%;
            margin-left: auto;
            margin-right: auto;
            padding: 0 15px;
        }

        .topic-card {
            background-color: var(--card-bg);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            text-align: center;
        }

        .topic-card i {
            font-size: 2.5em;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .topic-card h3 {
            color: var(--primary-color);
            margin: 15px 0;
            font-size: 1.3em;
            line-height: 1.3;
        }

        .topic-card p {
            margin: 0;
            line-height: 1.5;
            color: var(--text-dark);
        }

        @media (max-width: 992px) {
            .topics-grid {
                grid-template-columns: repeat(2, 1fr);
                max-width: 800px;
            }
            
            .swiper {
                height: 400px;
            }
        }

        @media (max-width: 768px) {
            .workshop-carousel {
                height: 300px;
            }
            
            .swiper {
                height: 300px;
            }
            
            .workshop-top {
                grid-template-columns: 1fr !important;
                height: auto !important;
            }
            
            #workshop-details {
                padding: 30px 20px !important;
            }
            
            .location-info {
                flex-direction: column;
                align-items: center;
            }
            
            .info-item {
                width: 100%;
                max-width: 400px;
                margin-bottom: 15px;
            }
            
            .topics-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .workshop-topics h2 {
                font-size: 2em;
            }
            
            .cta-button {
                padding: 12px 30px;
                font-size: 16px;
            }
        }

        @media (max-width: 576px) {
            .topics-grid {
                grid-template-columns: 1fr;
                max-width: 400px;
            }
            
            .workshop-carousel {
                height: 250px;
            }
            
            .swiper {
                height: 250px;
            }
            
            .workshop-topics h2 {
                font-size: 1.8em;
            }
            
            .topic-card {
                padding: 20px;
            }
            
            .topic-card i {
                font-size: 2em;
            }
            
            .topic-card h3 {
                font-size: 1.2em;
            }
            
            #workshop-details h1 {
                font-size: 2em !important;
            }
            
            .workshop-description {
                font-size: 1em !important;
            }
        }

        #workshop-details {
            text-align: center;
            max-width: 100%;
            margin: 0;
            padding: 20px;
            background: var(--bg-light);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        #workshop-details h1 {
            color: var(--primary-color);
            margin-bottom: 20px;
            font-size: 2.8em;
        }

        .workshop-description {
            font-size: 1.1em;
            line-height: 1.5;
            margin-bottom: 15px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .workshop-location {
            margin: 0 auto;
            padding: 0;
            position: relative;
            width: 100%;
        }

        .location-info {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 0 auto;
            padding: 0 15px;
            flex-wrap: wrap;
        }

        .info-item {
            padding: 15px;
            margin: 0;
            flex: 0 1 auto;
            min-width: 200px;
            max-width: 300px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .info-item i {
            font-size: 20px;
            color: var(--primary-color);
            margin-top: 3px;
        }

        .info-item div {
            text-align: left;
        }

        .info-item h3 {
            color: var(--primary-color);
            margin-bottom: 5px;
            font-size: 1.1em;
        }

        .info-item p {
            margin: 0;
            line-height: 1.4;
            font-size: 0.95em;
        }

        .workshop-cta {
            text-align: center;
            margin: 20px 0 0 0;
        }

        .cta-button {
            display: inline-block;
            padding: 20px 50px;
            font-size: 20px;
            font-weight: 600;
            text-align: center;
            color: var(--text-light);
            background: linear-gradient(45deg, var(--primary-color), var(--highlight-color));
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            margin: 30px auto;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            background: linear-gradient(45deg, var(--highlight-color), var(--primary-color));
        }

        .cta-button i {
            margin-right: 10px;
        }

        .workshop .container {
            max-width: 100%;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        .workshop-top {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            height: 50vh;
            margin-bottom: 40px;
            background: var(--background);
        }

        .workshop-carousel {
            margin: 0;
            width: 100%;
            height: 100%;
            position: relative;
            border-right: 1px solid var(--border-color);
        }

        .swiper {
            width: 100%;
            height: 100%;
            border-radius: 0;
            overflow: hidden;
        }

        .workshop-topics {
            padding: 60px 20px;
            background: var(--light-blue);
        }

        .topics-grid {
            max-width: 1200px;
            margin: 0 auto;
        }

        #workshop-details {
            text-align: center;
            max-width: 100%;
            margin: 0;
            padding: 30px;
            background: transparent;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: var(--text-primary);
        }

        #workshop-details h1 {
            color: var(--primary-blue);
            margin-bottom: 20px;
            font-size: 2.4em;
        }

        .workshop-description {
            font-size: 1.1em;
            line-height: 1.5;
            margin-bottom: 15px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            color: var(--text-primary);
        }

        .info-item {
            padding: 15px;
            margin: 0;
            flex: 0 1 auto;
            min-width: 200px;
            max-width: 300px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: var(--light-blue);
            border-radius: 10px;
            box-shadow: 0 4px 15px var(--shadow-color);
        }

        .info-item i {
            font-size: 24px;
            color: var(--primary-blue);
        }

        .info-item h3 {
            color: var(--primary-blue);
            margin-bottom: 5px;
            font-size: 1.1em;
        }

        .info-item p {
            margin: 0;
            line-height: 1.4;
            font-size: 0.95em;
            color: var(--text-primary);
        }

        .cta-button {
            background: var(--primary-green);
            color: var(--text-light);
            border: 2px solid transparent;
            padding: 15px 40px;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            background: transparent;
            border-color: var(--text-light);
            transform: translateY(-2px);
        }

        .workshop-topics h2 {
            color: var(--primary-blue);
            margin-bottom: 40px;
            font-size: 2.5em;
            position: relative;
            display: inline-block;
        }

        .workshop-topics h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, var(--primary-blue), var(--primary-green));
            border-radius: 2px;
        }

        .topic-card {
            background: var(--background);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px var(--shadow-color);
            transition: transform 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            text-align: center;
            border: 1px solid var(--border-color);
        }

        .topic-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px var(--shadow-color);
        }

        .topic-card i {
            font-size: 2.5em;
            color: var(--primary-blue);
            margin-bottom: 15px;
            background: var(--light-blue);
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .topic-card h3 {
            color: var(--primary-blue);
            margin: 15px 0;
            font-size: 1.3em;
            line-height: 1.3;
        }

        .topic-card p {
            margin: 0;
            line-height: 1.5;
            color: var(--text-secondary);
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
            box-shadow: 0 2px 10px var(--shadow-color);
            position: sticky;
            top: 0;
            z-index: 50;
            padding: 15px 0;
        }

        .center-link a {
            color: var(--text-light);
            font-weight: bold;
            font-size: 1.2rem;
        }

        .center-link a:hover {
            color: var(--light-blue);
        }

        .theme-toggle {
            color: var(--text-light);
        }

        /* Menu mobile */
        .menu-toggle {
            display: none;
            font-size: 24px;
            color: var(--text-light);
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
                    <li><a href="index.php">Início</a></li>
                    <li><a href="index.php#vantagens">Vantagens</a></li>
                    <li><a href="index.php#equipe">Equipe</a></li>
                    <li><a href="index.php#servicos">Serviços</a></li>
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

    <!-- Workshop Content -->
    <section class="workshop">
        <div class="container">
            <div class="workshop-top">
                <!-- Carousel -->
                <div class="workshop-carousel">
                    <div class="swiper mySwiper">
                        <div class="swiper-wrapper">
                            <?php for ($i = 1; $i <= 3; $i++): 
                                $image = getImage('workshop', 'image_' . $i);
                                $imageUrl = $image ? 'data:image/jpeg;base64,' . base64_encode($image) : 'assets/images/workshop-' . $i . '.jpg';
                            ?>
                            <div class="swiper-slide">
                                <img src="<?php echo $imageUrl; ?>" alt="Workshop de Primeiros Socorros">
                            </div>
                            <?php endfor; ?>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>

                <!-- Workshop Details -->
                <div id="workshop-details" class="workshop-details">
                    <h1><?php echo htmlspecialchars($workshop['title']); ?></h1>
                    <p class="workshop-description">
                        <?php echo htmlspecialchars($workshop['description']); ?>
                    </p>

                    <!-- Workshop Location and Date -->
                    <div class="workshop-location">
                        <div class="location-info">
                            <div class="info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <h3>Local</h3>
                                    <p><?php echo htmlspecialchars($workshop['location']); ?></p>
                                    <p><?php echo htmlspecialchars($workshop['address']); ?></p>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-calendar-alt"></i>
                                <div>
                                    <h3>Data</h3>
                                    <p><?php echo htmlspecialchars($workshop['date']); ?></p>
                                    <p><?php echo htmlspecialchars($workshop['time']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="workshop-cta">
                        <a href="<?php echo htmlspecialchars($workshop['link']); ?>" class="cta-button">
                            <i class="fas fa-ticket-alt"></i> COMPRAR AGORA
                        </a>
                    </div>
                </div>
            </div>

            <!-- Workshop Topics -->
            <div class="workshop-topics">
                <h2>O que você vai aprender:</h2>
                <div class="topics-grid">
                    <div class="topic-card">
                        <i class="fas fa-clock"></i>
                        <h3>20 horas de treinamento</h3>
                        <p>Distribuídas em 3 dias consecutivos</p>
                    </div>
                    <div class="topic-card">
                        <i class="fas fa-heartbeat"></i>
                        <h3>RCP e DEA</h3>
                        <p>Ressuscitação cardiopulmonar e uso de desfibrilador</p>
                    </div>
                    <div class="topic-card">
                        <i class="fas fa-medkit"></i>
                        <h3>Controle de Hemorragias</h3>
                        <p>Técnicas eficientes para controle de sangramento</p>
                    </div>
                    <div class="topic-card">
                        <i class="fas fa-bone"></i>
                        <h3>Fraturas e Imobilizações</h3>
                        <p>Identificação e primeiro atendimento em traumas</p>
                    </div>
                    <div class="topic-card">
                        <i class="fas fa-certificate"></i>
                        <h3>Certificação</h3>
                        <p>Certificado reconhecido nacionalmente</p>
                    </div>
                    <div class="topic-card">
                        <i class="fas fa-coffee"></i>
                        <h3>Coffee Break</h3>
                        <p>Intervalos com lanches e bebidas inclusos</p>
                    </div>
                </div>
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

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        // Inicialização do Swiper
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            effect: "fade",
            fadeEffect: {
                crossFade: true
            }
        });

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

            // Alternar tema
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

<?php
session_start();
require_once 'includes/content_manager.php';

// Carregar dados do workshop
$workshopData = getAllContent('workshop');
$workshop = json_decode($workshopData['data'] ?? '{}', true) ?: [];

// Se o workshop não estiver ativo, redirecionar para a página inicial
if (!isset($workshop['active']) || !$workshop['active']) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop - <?php echo htmlspecialchars($workshop['title']); ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <style>
        /* Estilos do Carrossel */
        .workshop-carousel {
            margin-bottom: 60px;
        }

        .swiper {
            width: 100%;
            height: 500px;
            border-radius: 15px;
            overflow: hidden;
        }

        .swiper-slide {
            text-align: center;
            background: var(--bg-light);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            background: var(--primary-color);
            opacity: 0.5;
        }

        .swiper-pagination-bullet-active {
            opacity: 1;
        }

        /* Botão WhatsApp Flutuante */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #25d366;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            text-align: center;
            font-size: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        /* Restante dos estilos */
        .workshop {
            padding: 60px 0;
        }

        .workshop-images {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 60px;
        }

        .workshop-image {
            position: relative;
            padding-top: 66.67%; /* Proporção 3:2 */
            background-color: var(--bg-light);
        }

        .workshop-image img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .workshop-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0));
            border-radius: 10px;
            z-index: 1;
        }

        .workshop-image:hover img {
            transform: scale(1.05);
        }

        .workshop-topics {
            margin: 60px auto;
            text-align: center;
            max-width: 100%;
        }

        .workshop-topics h2 {
            color: var(--primary-color);
            margin-bottom: 40px;
            font-size: 2.5em;
        }

        .topics-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-bottom: 60px;
            max-width: 100%;
            margin-left: auto;
            margin-right: auto;
            padding: 0 15px;
        }

        .topic-card {
            background-color: var(--card-bg);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            text-align: center;
        }

        .topic-card i {
            font-size: 2.5em;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .topic-card h3 {
            color: var(--primary-color);
            margin: 15px 0;
            font-size: 1.3em;
            line-height: 1.3;
        }

        .topic-card p {
            margin: 0;
            line-height: 1.5;
            color: var(--text-dark);
        }

        @media (max-width: 992px) {
            .topics-grid {
                grid-template-columns: repeat(2, 1fr);
                max-width: 800px;
            }
            
            .swiper {
                height: 400px;
            }
        }

        @media (max-width: 768px) {
            .workshop-carousel {
                height: 300px;
            }
            
            .swiper {
                height: 300px;
            }
            
            .workshop-top {
                grid-template-columns: 1fr !important;
                height: auto !important;
            }
            
            #workshop-details {
                padding: 30px 20px !important;
            }
            
            .location-info {
                flex-direction: column;
                align-items: center;
            }
            
            .info-item {
                width: 100%;
                max-width: 400px;
                margin-bottom: 15px;
            }
            
            .topics-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .workshop-topics h2 {
                font-size: 2em;
            }
            
            .cta-button {
                padding: 12px 30px;
                font-size: 16px;
            }
        }

        @media (max-width: 576px) {
            .topics-grid {
                grid-template-columns: 1fr;
                max-width: 400px;
            }
            
            .workshop-carousel {
                height: 250px;
            }
            
            .swiper {
                height: 250px;
            }
            
            .workshop-topics h2 {
                font-size: 1.8em;
            }
            
            .topic-card {
                padding: 20px;
            }
            
            .topic-card i {
                font-size: 2em;
            }
            
            .topic-card h3 {
                font-size: 1.2em;
            }
            
            #workshop-details h1 {
                font-size: 2em !important;
            }
            
            .workshop-description {
                font-size: 1em !important;
            }
        }

        #workshop-details {
            text-align: center;
            max-width: 100%;
            margin: 0;
            padding: 20px;
            background: var(--bg-light);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        #workshop-details h1 {
            color: var(--primary-color);
            margin-bottom: 20px;
            font-size: 2.8em;
        }

        .workshop-description {
            font-size: 1.1em;
            line-height: 1.5;
            margin-bottom: 15px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .workshop-location {
            margin: 0 auto;
            padding: 0;
            position: relative;
            width: 100%;
        }

        .location-info {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 0 auto;
            padding: 0 15px;
            flex-wrap: wrap;
        }

        .info-item {
            padding: 15px;
            margin: 0;
            flex: 0 1 auto;
            min-width: 200px;
            max-width: 300px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .info-item i {
            font-size: 20px;
            color: var(--primary-color);
            margin-top: 3px;
        }

        .info-item div {
            text-align: left;
        }

        .info-item h3 {
            color: var(--primary-color);
            margin-bottom: 5px;
            font-size: 1.1em;
        }

        .info-item p {
            margin: 0;
            line-height: 1.4;
            font-size: 0.95em;
        }

        .workshop-cta {
            text-align: center;
            margin: 20px 0 0 0;
        }

        .cta-button {
            display: inline-block;
            padding: 20px 50px;
            font-size: 20px;
            font-weight: 600;
            text-align: center;
            color: var(--text-light);
            background: linear-gradient(45deg, var(--primary-color), var(--highlight-color));
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            margin: 30px auto;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            background: linear-gradient(45deg, var(--highlight-color), var(--primary-color));
        }

        .cta-button i {
            margin-right: 10px;
        }

        .workshop .container {
            max-width: 100%;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        .workshop-top {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            height: 50vh;
            margin-bottom: 40px;
            background: var(--background);
        }

        .workshop-carousel {
            margin: 0;
            width: 100%;
            height: 100%;
            position: relative;
            border-right: 1px solid var(--border-color);
        }

        .swiper {
            width: 100%;
            height: 100%;
            border-radius: 0;
            overflow: hidden;
        }

        .workshop-topics {
            padding: 60px 20px;
            background: var(--light-blue);
        }

        .topics-grid {
            max-width: 1200px;
            margin: 0 auto;
        }

        #workshop-details {
            text-align: center;
            max-width: 100%;
            margin: 0;
            padding: 30px;
            background: transparent;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: var(--text-primary);
        }

        #workshop-details h1 {
            color: var(--primary-blue);
            margin-bottom: 20px;
            font-size: 2.4em;
        }

        .workshop-description {
            font-size: 1.1em;
            line-height: 1.5;
            margin-bottom: 15px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            color: var(--text-primary);
        }

        .info-item {
            padding: 15px;
            margin: 0;
            flex: 0 1 auto;
            min-width: 200px;
            max-width: 300px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: var(--light-blue);
            border-radius: 10px;
            box-shadow: 0 4px 15px var(--shadow-color);
        }

        .info-item i {
            font-size: 24px;
            color: var(--primary-blue);
        }

        .info-item h3 {
            color: var(--primary-blue);
            margin-bottom: 5px;
            font-size: 1.1em;
        }

        .info-item p {
            margin: 0;
            line-height: 1.4;
            font-size: 0.95em;
            color: var(--text-primary);
        }

        .cta-button {
            background: var(--primary-green);
            color: var(--text-light);
            border: 2px solid transparent;
            padding: 15px 40px;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            background: transparent;
            border-color: var(--text-light);
            transform: translateY(-2px);
        }

        .workshop-topics h2 {
            color: var(--primary-blue);
            margin-bottom: 40px;
            font-size: 2.5em;
            position: relative;
            display: inline-block;
        }

        .workshop-topics h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, var(--primary-blue), var(--primary-green));
            border-radius: 2px;
        }

        .topic-card {
            background: var(--background);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px var(--shadow-color);
            transition: transform 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            text-align: center;
            border: 1px solid var(--border-color);
        }

        .topic-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px var(--shadow-color);
        }

        .topic-card i {
            font-size: 2.5em;
            color: var(--primary-blue);
            margin-bottom: 15px;
            background: var(--light-blue);
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .topic-card h3 {
            color: var(--primary-blue);
            margin: 15px 0;
            font-size: 1.3em;
            line-height: 1.3;
        }

        .topic-card p {
            margin: 0;
            line-height: 1.5;
            color: var(--text-secondary);
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
            box-shadow: 0 2px 10px var(--shadow-color);
            position: sticky;
            top: 0;
            z-index: 50;
            padding: 15px 0;
        }

        .center-link a {
            color: var(--text-light);
            font-weight: bold;
            font-size: 1.2rem;
        }

        .center-link a:hover {
            color: var(--light-blue);
        }

        .theme-toggle {
            color: var(--text-light);
        }

        /* Menu mobile */
        .menu-toggle {
            display: none;
            font-size: 24px;
            color: var(--text-light);
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
                    <li><a href="index.php">Início</a></li>
                    <li><a href="index.php#vantagens">Vantagens</a></li>
                    <li><a href="index.php#equipe">Equipe</a></li>
                    <li><a href="index.php#servicos">Serviços</a></li>
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

    <!-- Workshop Content -->
    <section class="workshop">
        <div class="container">
            <div class="workshop-top">
                <!-- Carousel -->
                <div class="workshop-carousel">
                    <div class="swiper mySwiper">
                        <div class="swiper-wrapper">
                            <?php for ($i = 1; $i <= 3; $i++): 
                                $image = getImage('workshop', 'image_' . $i);
                                $imageUrl = $image ? 'data:image/jpeg;base64,' . base64_encode($image) : 'assets/images/workshop-' . $i . '.jpg';
                            ?>
                            <div class="swiper-slide">
                                <img src="<?php echo $imageUrl; ?>" alt="Workshop de Primeiros Socorros">
                            </div>
                            <?php endfor; ?>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>

                <!-- Workshop Details -->
                <div id="workshop-details" class="workshop-details">
                    <h1><?php echo htmlspecialchars($workshop['title']); ?></h1>
                    <p class="workshop-description">
                        <?php echo htmlspecialchars($workshop['description']); ?>
                    </p>

                    <!-- Workshop Location and Date -->
                    <div class="workshop-location">
                        <div class="location-info">
                            <div class="info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <h3>Local</h3>
                                    <p><?php echo htmlspecialchars($workshop['location']); ?></p>
                                    <p><?php echo htmlspecialchars($workshop['address']); ?></p>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-calendar-alt"></i>
                                <div>
                                    <h3>Data</h3>
                                    <p><?php echo htmlspecialchars($workshop['date']); ?></p>
                                    <p><?php echo htmlspecialchars($workshop['time']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="workshop-cta">
                        <a href="<?php echo htmlspecialchars($workshop['link']); ?>" class="cta-button">
                            <i class="fas fa-ticket-alt"></i> COMPRAR AGORA
                        </a>
                    </div>
                </div>
            </div>

            <!-- Workshop Topics -->
            <div class="workshop-topics">
                <h2>O que você vai aprender:</h2>
                <div class="topics-grid">
                    <div class="topic-card">
                        <i class="fas fa-clock"></i>
                        <h3>20 horas de treinamento</h3>
                        <p>Distribuídas em 3 dias consecutivos</p>
                    </div>
                    <div class="topic-card">
                        <i class="fas fa-heartbeat"></i>
                        <h3>RCP e DEA</h3>
                        <p>Ressuscitação cardiopulmonar e uso de desfibrilador</p>
                    </div>
                    <div class="topic-card">
                        <i class="fas fa-medkit"></i>
                        <h3>Controle de Hemorragias</h3>
                        <p>Técnicas eficientes para controle de sangramento</p>
                    </div>
                    <div class="topic-card">
                        <i class="fas fa-bone"></i>
                        <h3>Fraturas e Imobilizações</h3>
                        <p>Identificação e primeiro atendimento em traumas</p>
                    </div>
                    <div class="topic-card">
                        <i class="fas fa-certificate"></i>
                        <h3>Certificação</h3>
                        <p>Certificado reconhecido nacionalmente</p>
                    </div>
                    <div class="topic-card">
                        <i class="fas fa-coffee"></i>
                        <h3>Coffee Break</h3>
                        <p>Intervalos com lanches e bebidas inclusos</p>
                    </div>
                </div>
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

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        // Inicialização do Swiper
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            effect: "fade",
            fadeEffect: {
                crossFade: true
            }
        });

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

            // Alternar tema
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

