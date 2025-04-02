<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop de Primeiros Socorros - Habilitar Mais Vida</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <style>
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
            margin: 60px 0;
            text-align: center;
        }

        .workshop-topics h2 {
            color: var(--primary-color);
            margin-bottom: 40px;
            font-size: 2.5em;
        }

        .topics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }

        .topic-card {
            background-color: var(--card-bg);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .topic-card:hover {
            transform: translateY(-5px);
        }

        .topic-card h3 {
            color: var(--primary-color);
            margin: 15px 0;
            font-size: 1.3em;
        }

        .topic-card i {
            font-size: 2em;
            color: var(--primary-color);
        }

        #workshop-details {
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        #workshop-details h1 {
            color: var(--primary-color);
            margin-bottom: 20px;
            font-size: 2.8em;
        }

        .workshop-description {
            font-size: 1.2em;
            color: var(--text-dark);
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .workshop-location {
            margin: 40px 0;
        }

        .location-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
            background-color: var(--bg-light);
            border-radius: 10px;
        }

        .info-item i {
            font-size: 24px;
            color: var(--primary-color);
        }

        .info-item div {
            text-align: left;
        }

        .info-item h3 {
            color: var(--primary-color);
            margin-bottom: 10px;
            font-size: 1.2em;
        }

        .workshop-cta {
            text-align: center;
            margin: 40px 0;
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

        @media (max-width: 768px) {
            .workshop {
                padding: 40px 0;
            }

            #workshop-details h1 {
                font-size: 2.2em;
            }

            .workshop-topics h2 {
                font-size: 2em;
            }

            .cta-button {
                padding: 15px 40px;
                font-size: 18px;
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
                <a href="https://wa.me/5500000000000" class="contact-button">
                    <i class="fab fa-whatsapp"></i> Contato
                </a>
            </div>
        </div>
    </header>

    <!-- Workshop Content -->
    <section class="workshop">
        <div class="container">
            <!-- Carousel -->
            <div class="workshop-carousel">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <a href="#workshop-details">
                                <img src="assets/images/workshop-1.jpg" alt="Workshop de Primeiros Socorros">
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="#workshop-details">
                                <img src="assets/images/workshop-2.jpg" alt="Técnicas de RCP">
                            </a>
                        </div>
                        <div class="swiper-slide">
                            <a href="#workshop-details">
                                <img src="assets/images/workshop-3.jpg" alt="Atendimento de Emergência">
                            </a>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>

            <!-- Workshop Details -->
            <div id="workshop-details" class="workshop-details">
                <h1>Workshop Avançado de Primeiros Socorros</h1>
                <p class="workshop-description">
                    Aprenda técnicas essenciais de primeiros socorros e esteja preparado para agir em situações de emergência. 
                    Este workshop oferece conhecimento teórico e prático ministrado por profissionais experientes da área de saúde.
                </p>

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

                <!-- Workshop Location and Date -->
                <div class="workshop-location">
                    <div class="location-info">
                        <div class="info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <h3>Local</h3>
                                <p>Centro de Treinamento Médico</p>
                                <p>Av. Paulista, 1000 - São Paulo, SP</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-calendar-alt"></i>
                            <div>
                                <h3>Data</h3>
                                <p>15, 16 e 17 de Maio de 2025</p>
                                <p>Das 8h às 17h (intervalo para almoço)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="workshop-cta">
                    <a href="https://example.com/comprar" class="cta-button">COMPRAR AGORA</a>
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
</body>
</html>

