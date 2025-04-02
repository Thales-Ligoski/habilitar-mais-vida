<?php
session_start();
require_once 'includes/content_manager.php';

// Habilitar exibição de erros para debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Debug da sessão
error_log("Dashboard - Estado da sessão: " . print_r($_SESSION, true));

// Verificar se está logado
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    error_log("Dashboard - Usuário não está logado, redirecionando para login.php");
    header('Location: login.php');
    exit;
}

// Processar logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Inicializar arrays com valores padrão
$advantages = [];
$faq = [];
$team = [];
$services = [];
$workshop = [];

// Carregar dados existentes com valores padrão
$hero = [
    'title' => getContent('hero', 'title') ?? 'Treinamentos Especializados em Primeiros Socorros',
    'description' => getContent('hero', 'description') ?? 'Aprenda técnicas que podem salvar vidas com profissionais qualificados e experientes.'
];

// Carregar vantagens
$advantagesData = getAllContent('advantages');
$advantages = json_decode($advantagesData['items'] ?? '[]', true) ?: [];
$advantagesTitle = $advantagesData['title'] ?? 'Nossas Vantagens';

// Garantir que temos 6 vantagens
while (count($advantages) < 6) {
    $advantages[] = [
        'title' => '',
        'description' => '',
        'icon' => ''
    ];
}

// Carregar FAQ
$faqData = getAllContent('faq');
$faq = json_decode($faqData['items'] ?? '[]', true) ?: [];
$faqTitle = $faqData['title'] ?? 'Perguntas Frequentes';

// Garantir que temos pelo menos 4 perguntas
while (count($faq) < 4) {
    $faq[] = [
        'question' => '',
        'answer' => ''
    ];
}

// Carregar equipe
$teamData = getAllContent('team');
$team = json_decode($teamData['items'] ?? '[]', true) ?: [];
$teamTitle = $teamData['title'] ?? 'Nossa Equipe';

// Garantir que temos pelo menos 2 membros
while (count($team) < 2) {
    $team[] = [
        'name' => '',
        'role' => '',
        'bio' => ''
    ];
}

// Carregar serviços
$servicesData = getAllContent('services');
$services = json_decode($servicesData['items'] ?? '[]', true) ?: [];
$servicesTitle = $servicesData['title'] ?? 'Nossos Serviços';

// Garantir que temos 6 serviços
while (count($services) < 6) {
    $services[] = [
        'title' => '',
        'description' => ''
    ];
}

// Carregar workshop
$workshopData = getAllContent('workshop');
$workshop = json_decode($workshopData['data'] ?? '{}', true) ?: [
    'active' => false,
    'title' => '',
    'description' => '',
    'location' => '',
    'address' => '',
    'date' => '',
    'time' => '',
    'link' => '',
    'topics' => []
];

// Garantir que temos 6 tópicos no workshop
if (!isset($workshop['topics']) || !is_array($workshop['topics'])) {
    $workshop['topics'] = [];
}
while (count($workshop['topics']) < 6) {
    $workshop['topics'][] = [
        'title' => '',
        'description' => ''
    ];
}

// Processar formulários
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = "";
    $messageType = "";
    
    // Processar seção Hero
    if (isset($_POST['update_hero'])) {
        saveContent('hero', 'title', $_POST['heroTitle']);
        saveContent('hero', 'description', $_POST['heroDescription']);
        $message = "Seção Hero atualizada com sucesso!";
        $messageType = "success";
    }
    
    // Processar seção Vantagens
    if (isset($_POST['update_advantages'])) {
        $advantages = [];
        foreach ($_POST['advantageTitles'] as $key => $title) {
            $advantages[] = [
                'title' => $title,
                'description' => $_POST['advantageDescs'][$key],
                'icon' => $_POST['advantageIcons'][$key]
            ];
        }
        saveContent('advantages', 'items', json_encode($advantages));
        saveContent('advantages', 'title', $_POST['advantagesTitle']);
        $message = "Seção Vantagens atualizada com sucesso!";
        $messageType = "success";
    }
    
    // Processar seção FAQ
    if (isset($_POST['update_faq'])) {
        $faqs = [];
        foreach ($_POST['faqQuestions'] as $key => $question) {
            $faqs[] = [
                'question' => $question,
                'answer' => $_POST['faqAnswers'][$key]
            ];
        }
        saveContent('faq', 'items', json_encode($faqs));
        saveContent('faq', 'title', $_POST['faqTitle']);
        $message = "Seção FAQ atualizada com sucesso!";
        $messageType = "success";
    }
    
    // Processar seção Equipe
    if (isset($_POST['update_team'])) {
        $team = [];
        foreach ($_POST['teamNames'] as $key => $name) {
            $member = [
                'name' => $name,
                'role' => $_POST['teamRoles'][$key],
                'bio' => $_POST['teamBios'][$key]
            ];
            
            // Processar foto se foi enviada
            if (isset($_FILES['teamPhoto' . ($key + 1)]) && $_FILES['teamPhoto' . ($key + 1)]['error'] === UPLOAD_ERR_OK) {
                $image_data = file_get_contents($_FILES['teamPhoto' . ($key + 1)]['tmp_name']);
                saveImage('team', 'member_' . ($key + 1), $image_data);
            }
            
            $team[] = $member;
        }
        saveContent('team', 'items', json_encode($team));
        saveContent('team', 'title', $_POST['teamTitle']);
        $message = "Seção Equipe atualizada com sucesso!";
        $messageType = "success";
    }
    
    // Processar seção Serviços
    if (isset($_POST['update_services'])) {
        $services = [];
        foreach ($_POST['serviceTitles'] as $key => $title) {
            $services[] = [
                'title' => $title,
                'description' => $_POST['serviceDescs'][$key]
            ];
        }
        saveContent('services', 'items', json_encode($services));
        saveContent('services', 'title', $_POST['servicesTitle']);
        $message = "Seção Serviços atualizada com sucesso!";
        $messageType = "success";
    }
    
    // Processar seção Workshop
    if (isset($_POST['update_workshop'])) {
        $workshop = [
            'active' => isset($_POST['workshopActive']),
            'title' => $_POST['workshopTitle'],
            'description' => $_POST['workshopDesc'],
            'location' => $_POST['workshopLocation'],
            'address' => $_POST['workshopAddress'],
            'date' => $_POST['workshopDate'],
            'time' => $_POST['workshopTime'],
            'link' => $_POST['workshopLink']
        ];
        
        // Processar tópicos
        $topics = [];
        foreach ($_POST['topicTitles'] as $key => $title) {
            $topics[] = [
                'title' => $title,
                'description' => $_POST['topicDescs'][$key]
            ];
        }
        $workshop['topics'] = $topics;
        
        // Processar imagens
        for ($i = 1; $i <= 3; $i++) {
            if (isset($_FILES['workshopImage' . $i]) && $_FILES['workshopImage' . $i]['error'] === UPLOAD_ERR_OK) {
                $image_data = file_get_contents($_FILES['workshopImage' . $i]['tmp_name']);
                saveImage('workshop', 'image_' . $i, $image_data);
            }
        }
        
        saveContent('workshop', 'data', json_encode($workshop));
        $message = "Seção Workshop atualizada com sucesso!";
        $messageType = "success";
    }
}

// ... resto do código HTML ...
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Habilitar Mais Vida</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #1270ae; /* Cor primária do site original */
            --highlight-color: #44cc85; /* Cor de destaque do site original */
            --accent-color: #31a86b; /* Cor de acento do site original */
            --dark-blue: #0b4e74; /* Cor escura do site original */
            --light-blue: #e7f4fa; /* Cor clara do site original */
            --text-light: #ffffff;
            --text-dark: #333333;
            --border-color: #e0e0e0;
            --bg-light: #f8f9fa;
            --card-bg: #ffffff;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }
        
        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Sidebar */
        .dashboard-sidebar {
            width: var(--sidebar-width);
            background-color: var(--primary-color);
            color: var(--text-light);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: linear-gradient(rgba(18, 112, 174, 0.95), rgba(18, 112, 174, 0.95)), 
                        url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='400' viewBox='0 0 800 800'%3E%3Cg fill='none' stroke='%230b4e74' stroke-width='1'%3E%3Cpath d='M769 229L1037 260.9M927 880L731 737 520 660 309 538 40 599 295 764 126.5 879.5 40 599-197 493 102 382-31 229 126.5 79.5-69-63'/%3E%3Cpath d='M-31 229L237 261 390 382 603 493 308.5 537.5 101.5 381.5M370 905L295 764'/%3E%3Cpath d='M520 660L578 842 731 737 840 599 603 493 520 660 295 764 309 538 390 382 539 269 769 229 577.5 41.5 370 105 295 -36 126.5 79.5 237 261 102 382 40 599 -69 737 127 880'/%3E%3Cpath d='M520-140L578.5 42.5 731-63M603 493L539 269 237 261 370 105M902 382L539 269M390 382L102 382'/%3E%3Cpath d='M-222 42L126.5 79.5 370 105 539 269 577.5 41.5 927 80 769 229 902 382 603 493 731 737M295-36L577.5 41.5M578 842L295 764M40-201L127 80M102 382L-261 269'/%3E%3C/g%3E%3Cg fill='%2344cc85'%3E%3Ccircle cx='769' cy='229' r='5'/%3E%3Ccircle cx='539' cy='269' r='5'/%3E%3Ccircle cx='603' cy='493' r='5'/%3E%3Ccircle cx='731' cy='737' r='5'/%3E%3Ccircle cx='520' cy='660' r='5'/%3E%3Ccircle cx='309' cy='538' r='5'/%3E%3Ccircle cx='295' cy='764' r='5'/%3E%3Ccircle cx='40' cy='599' r='5'/%3E%3Ccircle cx='102' cy='382' r='5'/%3E%3Ccircle cx='127' cy='80' r='5'/%3E%3Ccircle cx='370' cy='105' r='5'/%3E%3Ccircle cx='578' cy='42' r='5'/%3E%3Ccircle cx='237' cy='261' r='5'/%3E%3Ccircle cx='390' cy='382' r='5'/%3E%3C/g%3E%3C/svg%3E");
            background-size: cover;
            position: relative;
            padding-top: 30px;
            padding-bottom: 30px;
        }
        
        .sidebar-header img {
            width: 70px;
            margin-bottom: 15px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        
        .sidebar-header h2 {
            color: var(--text-light);
            font-size: 18px;
            margin: 0;
            font-weight: 500;
        }
        
        .sidebar-user {
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--highlight-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: 500;
            overflow: hidden;
        }
        
        .user-avatar i {
            color: var(--text-light);
            font-size: 20px;
        }
        
        .user-info {
            flex: 1;
        }
        
        .user-info h3 {
            font-size: 16px;
            margin: 0;
            color: var(--text-light);
        }
        
        .user-info p {
            font-size: 12px;
            margin: 0;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .sidebar-nav {
            padding: 20px 0;
            flex: 1;
        }
        
        .nav-section {
            margin-bottom: 15px;
        }
        
        .nav-section-title {
            padding: 10px 20px;
            font-size: 12px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }
        
        .nav-items {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .nav-item {
            padding: 0;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: var(--text-light);
        }
        
        .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .sidebar-footer {
            padding: 15px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background-color: var(--primary-color);
        }
        
        .logout-btn {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            padding: 10px;
            border-radius: 5px;
        }
        
        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: var(--text-light);
        }
        
        .logout-btn i {
            margin-right: 10px;
        }
        
        /* Main Content */
        .dashboard-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 0;
            transition: margin-left 0.3s ease;
            width: calc(100% - var(--sidebar-width));
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 30px;
            background-color: var(--primary-color);
            color: var(--text-light);
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .dashboard-title {
            font-size: 24px;
            font-weight: 500;
            color: var(--text-light);
            margin: 0;
        }
        
        .dashboard-actions {
            display: flex;
            gap: 10px;
        }
        
        .dashboard-actions .btn {
            background-color: rgba(255, 255, 255, 0.2);
            color: var(--text-light);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .dashboard-actions .btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }
        
        .content-wrapper {
            padding: 0 30px 30px 30px;
        }
        
        .dashboard-card {
            background-color: var(--card-bg);
            border-radius: 10px;
            box-shadow: 0 2px 10px var(--shadow-color);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .card-title {
            font-size: 20px;
            font-weight: 500;
            color: var(--primary-color);
            margin: 0;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: var(--text-dark);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border-radius: 5px;
            border: 1px solid var(--border-color);
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: var(--card-bg);
            color: var(--text-dark);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--highlight-color);
            box-shadow: 0 0 0 3px rgba(68, 204, 133, 0.2);
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-primary {
            background-color: var(--highlight-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--accent-color);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
        }
        
        .btn-sm {
            padding: 8px 16px;
            font-size: 14px;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* Elementos personalizados */
        .collapsible-section {
            border: 1px solid var(--border-color);
            border-radius: 5px;
            margin-bottom: 20px;
            overflow: visible; /* Alterado para visible */
            background-color: var(--card-bg);
        }
        
        .collapsible-header {
            padding: 15px 20px;
            background-color: var(--bg-light);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .collapsible-header h4 {
            margin: 0;
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .collapsible-content {
            padding: 20px;
            border-top: 1px solid var(--border-color);
            max-height: none; /* Removido o limite de altura */
            overflow: visible; /* Alterado para visible */
            transition: all 0.3s ease;
        }
        
        .collapsible-content.hidden {
            display: none;
        }
        
        .toggle-icon i {
            transition: transform 0.3s ease;
            color: var(--primary-color);
        }
        
        .collapsible-section.active .toggle-icon i {
            transform: rotate(180deg);
        }
        
        .image-preview {
            margin-top: 10px;
            margin-bottom: 15px;
            max-width: 200px;
            height: 150px;
            border-radius: 5px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            background-color: #f8f9fa;
        }
        
        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .custom-file-input {
            position: relative;
            overflow: hidden;
            display: inline-block;
            margin-bottom: 15px;
        }
        
        .custom-file-input input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .upload-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .upload-button:hover {
            background-color: var(--dark-blue);
        }
        
        /* Switch (Toggle) */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
            margin-right: 15px;
        }
        
        .switch input { 
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
        }
        
        .slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
        }
        
        input:checked + .slider {
            background-color: var(--highlight-color);
        }
        
        input:focus + .slider {
            box-shadow: 0 0 1px var(--highlight-color);
        }
        
        input:checked + .slider:before {
            transform: translateX(30px);
        }
        
        .slider.round {
            border-radius: 30px;
        }
        
        .slider.round:before {
            border-radius: 50%;
        }
        
        .toggle-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .toggle-label {
            font-weight: 500;
            color: var(--text-dark);
        }
        
        /* Mobile Styles */
        .sidebar-toggle {
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1000;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 20px;
            display: none;
        }
        
        /* Estilos para o gerenciador de mídia */
        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .media-item {
            border: 1px solid var(--border-color);
            border-radius: 5px;
            overflow: hidden;
            background-color: var(--card-bg);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .media-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .media-preview {
            height: 150px;
            overflow: hidden;
        }
        
        .media-info {
            padding: 15px;
        }
        
        .media-name {
            margin: 0 0 15px 0;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: var(--text-dark);
        }
        
        .media-actions {
            display: flex;
            gap: 10px;
        }
        
        /* Estilos para o drop zone */
        .drop-zone {
            border: 2px dashed var(--border-color);
            padding: 40px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: var(--bg-light);
        }
        
        .drop-zone:hover {
            border-color: var(--primary-color);
            background-color: rgba(18, 112, 174, 0.05);
        }
        
        .drop-zone i {
            font-size: 48px;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .drop-zone p {
            color: var(--text-dark);
            margin: 0;
        }
        
        /* Estilos para os tópicos do workshop */
        .workshop-topics {
            margin-top: 30px;
            margin-bottom: 30px;
        }
        
        .workshop-topics h3 {
            margin-bottom: 20px;
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .topics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .topic-item {
            border: 1px solid var(--border-color);
            border-radius: 5px;
            padding: 20px;
            background-color: var(--bg-light);
            margin-bottom: 15px; /* Adicionado espaçamento entre os itens */
        }
        
        .topic-item .form-group:last-child {
            margin-bottom: 0;
        }
        
        /* Botão para adicionar novo tópico */
        .add-topic-btn {
            margin-bottom: 30px;
        }
        
        /* Estilos para mensagens de sucesso */
        .success-message {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: var(--highlight-color);
            color: white;
            padding: 15px 25px;
            border-radius: 5px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .success-message i {
            font-size: 20px;
        }
        
        /* Estilos para o formulário de workshop */
        .workshop-images {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .workshop-image-item {
            border: 1px solid var(--border-color);
            border-radius: 5px;
            overflow: hidden;
            background-color: var(--card-bg);
        }
        
        .workshop-image-preview {
            height: 150px;
            overflow: hidden;
        }
        
        .workshop-image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .workshop-image-actions {
            padding: 15px;
        }
        
        /* Responsividade */
        @media (max-width: 992px) {
            .topics-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width);
            }
            
            .dashboard-sidebar.active {
                transform: translateX(0);
            }
            
            .dashboard-content {
                margin-left: 0;
                width: 100%;
            }
            
            .sidebar-toggle {
                display: flex;
            }
            
            .dashboard-header {
                padding-left: 60px;
            }
            
            .workshop-images {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 576px) {
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .dashboard-card {
                padding: 15px;
            }
            
            .btn {
                width: 100%;
            }
            
            .media-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Estilo para campos readonly */
        input[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
            opacity: 0.7;
        }

        /* Estilos para a seção de workshop */
        .workshop-info {
            margin-top: 30px;
            padding: 30px;
            background-color: var(--bg-light);
            border-radius: 10px;
        }

        .workshop-info h3 {
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .workshop-images {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .workshop-image-item {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .workshop-image-item:hover {
            transform: translateY(-5px);
        }

        .workshop-image-preview {
            height: 250px;
        }

        .workshop-image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .workshop-topics {
            margin: 40px 0;
            padding: 30px;
            background-color: var(--bg-light);
            border-radius: 10px;
        }

        .topics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .topic-item {
            background-color: var(--card-bg);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .topic-item:hover {
            transform: translateY(-3px);
        }

        /* Estilo para o botão de compra no index.php */
        .workshop-buy-button {
            display: inline-block;
            padding: 15px 40px;
            font-size: 18px;
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

        .workshop-buy-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            background: linear-gradient(45deg, var(--highlight-color), var(--primary-color));
        }

        .workshop-buy-button-container {
            text-align: center;
            margin: 40px 0;
        }
    </style>
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button id="sidebar-toggle" class="sidebar-toggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Sidebar -->
    <aside class="dashboard-sidebar" id="dashboard-sidebar">
        <div class="sidebar-header">
            <img src="assets/images/logo.png" alt="Habilitar Mais Vida Logo">
            <h2>Painel Administrativo</h2>
        </div>
        
        <div class="sidebar-user">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-info">
                <h3><?php echo htmlspecialchars($_SESSION['username'] ?? 'Administrador'); ?></h3>
                <p><?php echo htmlspecialchars($_SESSION['email'] ?? 'admin@habilitarmaisvida.com'); ?></p>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Conteúdo do Site</div>
                <ul class="nav-items">
                    <li class="nav-item">
                        <a href="#hero" class="nav-link active" data-tab="hero">
                            <i class="fas fa-home"></i> Seção Principal
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#vantagens" class="nav-link" data-tab="vantagens">
                            <i class="fas fa-medal"></i> Vantagens
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#faq" class="nav-link" data-tab="faq">
                            <i class="fas fa-question-circle"></i> Perguntas Frequentes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#equipe" class="nav-link" data-tab="equipe">
                            <i class="fas fa-users"></i> Nossa Equipe
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#servicos" class="nav-link" data-tab="servicos">
                            <i class="fas fa-briefcase"></i> Serviços
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#workshop" class="nav-link" data-tab="workshop">
                            <i class="fas fa-calendar-alt"></i> Workshop
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Configurações</div>
                <ul class="nav-items">
                    <li class="nav-item">
                        <a href="#media" class="nav-link" data-tab="media">
                            <i class="fas fa-images"></i> Gerenciar Mídia
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php" class="nav-link" target="_blank">
                            <i class="fas fa-external-link-alt"></i> Ver Site
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        
        <div class="sidebar-footer">
            <a href="dashboard.php?logout=1" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Sair
            </a>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="dashboard-content">
        <?php if (isset($message)): ?>
        <div class="alert alert-success">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>
        
        <div class="dashboard-header">
            <h1 class="dashboard-title">Dashboard</h1>
            <div class="dashboard-actions">
                <a href="index.php" class="btn btn-secondary" target="_blank">
                    <i class="fas fa-eye"></i> Visualizar Site
                </a>
            </div>
        </div>
        
        <div class="content-wrapper">
            <!-- Seção Principal (Hero) -->
            <div class="tab-content active" id="hero-content">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title">Seção Principal</h2>
                    </div>
                    <form method="post" action="dashboard.php">
                        <div class="form-group">
                            <label for="heroTitle">Título Principal</label>
                            <input type="text" id="heroTitle" name="heroTitle" class="form-control" value="<?php echo htmlspecialchars($hero['title']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="heroDescription">Descrição</label>
                            <textarea id="heroDescription" name="heroDescription" class="form-control" rows="3" required><?php echo htmlspecialchars($hero['description']); ?></textarea>
                        </div>
                        
                        <button type="submit" name="update_hero" class="btn btn-primary">
                            <i class="fas fa-save"></i> Salvar Alterações
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Vantagens -->
            <div class="tab-content" id="vantagens-content">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title">Vantagens</h2>
                    </div>
                    <form method="post" action="dashboard.php">
                        <div class="form-group">
                            <label for="advantagesTitle">Título da Seção</label>
                            <input type="text" id="advantagesTitle" name="advantagesTitle" class="form-control" value="<?php echo htmlspecialchars($advantagesTitle); ?>" required>
                        </div>
                        
                        <!-- Vantagem 1 -->
                        <div class="collapsible-section">
                            <div class="collapsible-header">
                                <h4>Certificação Reconhecida</h4>
                                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="collapsible-content">
                                <div class="form-group">
                                    <label for="advantageTitle1">Título</label>
                                    <input type="text" id="advantageTitle1" name="advantageTitles[]" class="form-control" value="<?php echo htmlspecialchars($advantages[0]['title']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="advantageDesc1">Descrição</label>
                                    <textarea id="advantageDesc1" name="advantageDescs[]" class="form-control" required><?php echo htmlspecialchars($advantages[0]['description']); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="advantageIcon1">Ícone</label>
                                    <input type="text" id="advantageIcon1" name="advantageIcons[]" class="form-control" value="<?php echo htmlspecialchars($advantages[0]['icon']); ?>" required>
                                    <small class="form-text text-muted">Use classes do Font Awesome. Ex: fa-certificate, fa-users, etc.</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Vantagem 2 -->
                        <div class="collapsible-section">
                            <div class="collapsible-header">
                                <h4>Instrutores Qualificados</h4>
                                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="collapsible-content hidden">
                                <div class="form-group">
                                    <label for="advantageTitle2">Título</label>
                                    <input type="text" id="advantageTitle2" name="advantageTitles[]" class="form-control" value="<?php echo htmlspecialchars($advantages[1]['title']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="advantageDesc2">Descrição</label>
                                    <textarea id="advantageDesc2" name="advantageDescs[]" class="form-control" required><?php echo htmlspecialchars($advantages[1]['description']); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="advantageIcon2">Ícone</label>
                                    <input type="text" id="advantageIcon2" name="advantageIcons[]" class="form-control" value="<?php echo htmlspecialchars($advantages[1]['icon']); ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Vantagem 3 -->
                        <div class="collapsible-section">
                            <div class="collapsible-header">
                                <h4>Prática Supervisionada</h4>
                                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="collapsible-content hidden">
                                <div class="form-group">
                                    <label for="advantageTitle3">Título</label>
                                    <input type="text" id="advantageTitle3" name="advantageTitles[]" class="form-control" value="<?php echo htmlspecialchars($advantages[2]['title']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="advantageDesc3">Descrição</label>
                                    <textarea id="advantageDesc3" name="advantageDescs[]" class="form-control" required><?php echo htmlspecialchars($advantages[2]['description']); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="advantageIcon3">Ícone</label>
                                    <input type="text" id="advantageIcon3" name="advantageIcons[]" class="form-control" value="<?php echo htmlspecialchars($advantages[2]['icon']); ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Vantagem 4 -->
                        <div class="collapsible-section">
                            <div class="collapsible-header">
                                <h4>Instalações Modernas</h4>
                                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="collapsible-content hidden">
                                <div class="form-group">
                                    <label for="advantageTitle4">Título</label>
                                    <input type="text" id="advantageTitle4" name="advantageTitles[]" class="form-control" value="<?php echo htmlspecialchars($advantages[3]['title']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="advantageDesc4">Descrição</label>
                                    <textarea id="advantageDesc4" name="advantageDescs[]" class="form-control" required><?php echo htmlspecialchars($advantages[3]['description']); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="advantageIcon4">Ícone</label>
                                    <input type="text" id="advantageIcon4" name="advantageIcons[]" class="form-control" value="<?php echo htmlspecialchars($advantages[3]['icon']); ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Vantagem 5 -->
                        <div class="collapsible-section">
                            <div class="collapsible-header">
                                <h4>Material Completo</h4>
                                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="collapsible-content hidden">
                                <div class="form-group">
                                    <label for="advantageTitle5">Título</label>
                                    <input type="text" id="advantageTitle5" name="advantageTitles[]" class="form-control" value="<?php echo htmlspecialchars($advantages[4]['title']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="advantageDesc5">Descrição</label>
                                    <textarea id="advantageDesc5" name="advantageDescs[]" class="form-control" required><?php echo htmlspecialchars($advantages[4]['description']); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="advantageIcon5">Ícone</label>
                                    <input type="text" id="advantageIcon5" name="advantageIcons[]" class="form-control" value="<?php echo htmlspecialchars($advantages[4]['icon']); ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Vantagem 6 -->
                        <div class="collapsible-section">
                            <div class="collapsible-header">
                                <h4>Suporte Pós-Curso</h4>
                                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="collapsible-content hidden">
                                <div class="form-group">
                                    <label for="advantageTitle6">Título</label>
                                    <input type="text" id="advantageTitle6" name="advantageTitles[]" class="form-control" value="<?php echo htmlspecialchars($advantages[5]['title']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="advantageDesc6">Descrição</label>
                                    <textarea id="advantageDesc6" name="advantageDescs[]" class="form-control" required><?php echo htmlspecialchars($advantages[5]['description']); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="advantageIcon6">Ícone</label>
                                    <input type="text" id="advantageIcon6" name="advantageIcons[]" class="form-control" value="<?php echo htmlspecialchars($advantages[5]['icon']); ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" name="update_advantages" class="btn btn-primary">
                            <i class="fas fa-save"></i> Salvar Alterações
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Perguntas Frequentes -->
            <div class="tab-content" id="faq-content">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title">Perguntas Frequentes</h2>
                    </div>
                    <form method="post" action="dashboard.php">
                        <div class="form-group">
                            <label for="faqTitle">Título da Seção</label>
                            <input type="text" id="faqTitle" name="faqTitle" class="form-control" value="<?php echo htmlspecialchars($faqTitle); ?>" required>
                        </div>
                        
                        <!-- Pergunta 1 -->
                        <div class="collapsible-section">
                            <div class="collapsible-header">
                                <h4>Pergunta 1</h4>
                                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="collapsible-content">
                                <div class="form-group">
                                    <label for="faqQuestion1">Pergunta</label>
                                    <input type="text" id="faqQuestion1" name="faqQuestions[]" class="form-control" value="<?php echo htmlspecialchars($faq[0]['question']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="faqAnswer1">Resposta</label>
                                    <textarea id="faqAnswer1" name="faqAnswers[]" class="form-control" required><?php echo htmlspecialchars($faq[0]['answer']); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pergunta 2 -->
                        <div class="collapsible-section">
                            <div class="collapsible-header">
                                <h4>Pergunta 2</h4>
                                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="collapsible-content hidden">
                                <div class="form-group">
                                    <label for="faqQuestion2">Pergunta</label>
                                    <input type="text" id="faqQuestion2" name="faqQuestions[]" class="form-control" value="<?php echo htmlspecialchars($faq[1]['question']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="faqAnswer2">Resposta</label>
                                    <textarea id="faqAnswer2" name="faqAnswers[]" class="form-control" required><?php echo htmlspecialchars($faq[1]['answer']); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pergunta 3 -->
                        <div class="collapsible-section">
                            <div class="collapsible-header">
                                <h4>Pergunta 3</h4>
                                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="collapsible-content hidden">
                                <div class="form-group">
                                    <label for="faqQuestion3">Pergunta</label>
                                    <input type="text" id="faqQuestion3" name="faqQuestions[]" class="form-control" value="<?php echo htmlspecialchars($faq[2]['question']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="faqAnswer3">Resposta</label>
                                    <textarea id="faqAnswer3" name="faqAnswers[]" class="form-control" required><?php echo htmlspecialchars($faq[2]['answer']); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pergunta 4 -->
                        <div class="collapsible-section">
                            <div class="collapsible-header">
                                <h4>Pergunta 4</h4>
                                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="collapsible-content hidden">
                                <div class="form-group">
                                    <label for="faqQuestion4">Pergunta</label>
                                    <input type="text" id="faqQuestion4" name="faqQuestions[]" class="form-control" value="<?php echo htmlspecialchars($faq[3]['question']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="faqAnswer4">Resposta</label>
                                    <textarea id="faqAnswer4" name="faqAnswers[]" class="form-control" required><?php echo htmlspecialchars($faq[3]['answer']); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" id="addFaqButton" class="btn btn-secondary" style="margin-bottom: 20px;">
                            <i class="fas fa-plus"></i> Adicionar Nova Pergunta
                        </button>
                        
                        <button type="submit" name="update_faq" class="btn btn-primary">
                            <i class="fas fa-save"></i> Salvar Alterações
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Nossa Equipe -->
            <div class="tab-content" id="equipe-content">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title">Nossa Equipe</h2>
                    </div>
                    <form method="post" action="dashboard.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="teamTitle">Título da Seção</label>
                            <input type="text" id="teamTitle" name="teamTitle" class="form-control" value="<?php echo htmlspecialchars($teamTitle); ?>" required>
                        </div>
                        
                        <!-- Membro 1 -->
                        <div class="collapsible-section">
                            <div class="collapsible-header">
                                <h4>Dr. Carlos Silva</h4>
                                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="collapsible-content">
                                <div class="form-group">
                                    <label for="teamName1">Nome</label>
                                    <input type="text" id="teamName1" name="teamNames[]" class="form-control" value="<?php echo htmlspecialchars($team[0]['name']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="teamRole1">Especialidade</label>
                                    <input type="text" id="teamRole1" name="teamRoles[]" class="form-control" value="<?php echo htmlspecialchars($team[0]['role']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="teamBio1">Currículo</label>
                                    <textarea id="teamBio1" name="teamBios[]" class="form-control" required><?php echo htmlspecialchars($team[0]['bio']); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Foto Atual</label>
                                    <div class="image-preview">
                                        <img src="assets/images/team-1.jpg" alt="Dr. Carlos Silva">
                                    </div>
                                    <div class="custom-file-input">
                                        <input type="file" id="teamPhoto1" name="teamPhoto1" accept="image/*">
                                        <label for="teamPhoto1" class="upload-button">Trocar Imagem</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Membro 2 -->
                        <div class="collapsible-section">
                            <div class="collapsible-header">
                                <h4>Enf. Ana Martins</h4>
                                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="collapsible-content hidden">
                                <div class="form-group">
                                    <label for="teamName2">Nome</label>
                                    <input type="text" id="teamName2" name="teamNames[]" class="form-control" value="<?php echo htmlspecialchars($team[1]['name']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="teamRole2">Especialidade</label>
                                    <input type="text" id="teamRole2" name="teamRoles[]" class="form-control" value="<?php echo htmlspecialchars($team[1]['role']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="teamBio2">Currículo</label>
                                    <textarea id="teamBio2" name="teamBios[]" class="form-control" required><?php echo htmlspecialchars($team[1]['bio']); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Foto Atual</label>
                                    <div class="image-preview">
                                        <img src="assets/images/team-2.jpg" alt="Enf. Ana Martins">
                                    </div>
                                    <div class="custom-file-input">
                                        <input type="file" id="teamPhoto2" name="teamPhoto2" accept="image/*">
                                        <label for="teamPhoto2" class="upload-button">Trocar Imagem</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" id="addTeamButton" class="btn btn-secondary" style="margin-bottom: 20px;">
                            <i class="fas fa-plus"></i> Adicionar Novo Membro
                        </button>
                        
                        <button type="submit" name="update_team" class="btn btn-primary">
                            <i class="fas fa-save"></i> Salvar Alterações
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Serviços -->
            <div class="tab-content" id="servicos-content">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title">Serviços</h2>
                    </div>
                    <form method="post" action="dashboard.php">
                        <div class="form-group">
                            <label for="servicesTitle">Título da Seção</label>
                            <input type="text" id="servicesTitle" name="servicesTitle" class="form-control" value="<?php echo htmlspecialchars($servicesTitle); ?>" required>
                        </div>
                        
                        <!-- Serviço 1 -->
                        <div class="collapsible-section">
                            <div class="collapsible-header">
                                <h4>Serviço 1</h4>
                                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="collapsible-content">
                                <div class="form-group">
                                    <label for="serviceTitle1">Título</label>
                                    <input type="text" id="serviceTitle1" name="serviceTitles[]" class="form-control" value="<?php echo htmlspecialchars($services[0]['title']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="serviceDesc1">Descrição</label>
                                    <textarea id="serviceDesc1" name="serviceDescs[]" class="form-control" required><?php echo htmlspecialchars($services[0]['description']); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Serviço 2 -->
                        <div class="collapsible-section">
                            <div class="collapsible-header">
                                <h4>Serviço 2</h4>
                                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="collapsible-content hidden">
                                <div class="form-group">
                                    <label for="serviceTitle2">Título</label>
                                    <input type="text" id="serviceTitle2" name="serviceTitles[]" class="form-control" value="<?php echo htmlspecialchars($services[1]['title']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="serviceDesc2">Descrição</label>
                                    <textarea id="serviceDesc2" name="serviceDescs[]" class="form-control" required><?php echo htmlspecialchars($services[1]['description']); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Serviço 3 -->
                        <div class="collapsible-section">
                            <div class="collapsible-header">
                                <h4>Serviço 3</h4>
                                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="collapsible-content hidden">
                                <div class="form-group">
                                    <label for="serviceTitle3">Título</label>
                                    <input type="text" id="serviceTitle3" name="serviceTitles[]" class="form-control" value="<?php echo htmlspecialchars($services[2]['title']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="serviceDesc3">Descrição</label>
                                    <textarea id="serviceDesc3" name="serviceDescs[]" class="form-control" required><?php echo htmlspecialchars($services[2]['description']); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Serviço 4 -->
                        <div class="collapsible-section">
                            <div class="collapsible-header">
                                <h4>Serviço 4</h4>
                                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="collapsible-content hidden">
                                <div class="form-group">
                                    <label for="serviceTitle4">Título</label>
                                    <input type="text" id="serviceTitle4" name="serviceTitles[]" class="form-control" value="<?php echo htmlspecialchars($services[3]['title']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="serviceDesc4">Descrição</label>
                                    <textarea id="serviceDesc4" name="serviceDescs[]" class="form-control" required><?php echo htmlspecialchars($services[3]['description']); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Serviço 5 -->
                        <div class="collapsible-section">
                            <div class="collapsible-header">
                                <h4>Serviço 5</h4>
                                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="collapsible-content hidden">
                                <div class="form-group">
                                    <label for="serviceTitle5">Título</label>
                                    <input type="text" id="serviceTitle5" name="serviceTitles[]" class="form-control" value="<?php echo htmlspecialchars($services[4]['title']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="serviceDesc5">Descrição</label>
                                    <textarea id="serviceDesc5" name="serviceDescs[]" class="form-control" required><?php echo htmlspecialchars($services[4]['description']); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Serviço 6 -->
                        <div class="collapsible-section">
                            <div class="collapsible-header">
                                <h4>Serviço 6</h4>
                                <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="collapsible-content hidden">
                                <div class="form-group">
                                    <label for="serviceTitle6">Título</label>
                                    <input type="text" id="serviceTitle6" name="serviceTitles[]" class="form-control" value="<?php echo htmlspecialchars($services[5]['title']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="serviceDesc6">Descrição</label>
                                    <textarea id="serviceDesc6" name="serviceDescs[]" class="form-control" required><?php echo htmlspecialchars($services[5]['description']); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" name="update_services" class="btn btn-primary">
                            <i class="fas fa-save"></i> Salvar Alterações
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Workshop -->
            <div class="tab-content" id="workshop-content">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title">Gerenciar Workshop</h2>
                    </div>
                    <form method="post" action="dashboard.php" enctype="multipart/form-data">
                        <!-- Imagens do Workshop -->
                        <div class="form-group">
                            <label>Imagens do Workshop</label>
                            <div class="workshop-images">
                                <div class="workshop-image-item">
                                    <div class="workshop-image-preview">
                                        <img src="assets/images/workshop-1.jpg" alt="Workshop 1">
                                    </div>
                                    <div class="workshop-image-actions">
                                        <div class="custom-file-input">
                                            <input type="file" id="workshopImage1" name="workshopImage1" accept="image/*">
                                            <label for="workshopImage1" class="upload-button">Trocar Imagem</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="workshop-image-item">
                                    <div class="workshop-image-preview">
                                        <img src="assets/images/workshop-2.jpg" alt="Workshop 2">
                                    </div>
                                    <div class="workshop-image-actions">
                                        <div class="custom-file-input">
                                            <input type="file" id="workshopImage2" name="workshopImage2" accept="image/*">
                                            <label for="workshopImage2" class="upload-button">Trocar Imagem</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="workshop-image-item">
                                    <div class="workshop-image-preview">
                                        <img src="assets/images/workshop-3.jpg" alt="Workshop 3">
                                    </div>
                                    <div class="workshop-image-actions">
                                        <div class="custom-file-input">
                                            <input type="file" id="workshopImage3" name="workshopImage3" accept="image/*">
                                            <label for="workshopImage3" class="upload-button">Trocar Imagem</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tópicos do Workshop -->
                        <div class="workshop-topics">
                            <h3>O Que Você Vai Aprender</h3>
                            <div class="topics-grid">
                                <div class="topic-item">
                                    <div class="form-group">
                                        <label for="topicTitle1">Título do Tópico 1</label>
                                        <input type="text" id="topicTitle1" name="topicTitles[]" class="form-control" value="<?php echo htmlspecialchars($workshop['topics'][0]['title']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="topicDesc1">Descrição</label>
                                        <textarea id="topicDesc1" name="topicDescs[]" class="form-control"><?php echo htmlspecialchars($workshop['topics'][0]['description']); ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="topic-item">
                                    <div class="form-group">
                                        <label for="topicTitle2">Título do Tópico 2</label>
                                        <input type="text" id="topicTitle2" name="topicTitles[]" class="form-control" value="<?php echo htmlspecialchars($workshop['topics'][1]['title']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="topicDesc2">Descrição</label>
                                        <textarea id="topicDesc2" name="topicDescs[]" class="form-control"><?php echo htmlspecialchars($workshop['topics'][1]['description']); ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="topic-item">
                                    <div class="form-group">
                                        <label for="topicTitle3">Título do Tópico 3</label>
                                        <input type="text" id="topicTitle3" name="topicTitles[]" class="form-control" value="<?php echo htmlspecialchars($workshop['topics'][2]['title']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="topicDesc3">Descrição</label>
                                        <textarea id="topicDesc3" name="topicDescs[]" class="form-control"><?php echo htmlspecialchars($workshop['topics'][2]['description']); ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="topic-item">
                                    <div class="form-group">
                                        <label for="topicTitle4">Título do Tópico 4</label>
                                        <input type="text" id="topicTitle4" name="topicTitles[]" class="form-control" value="<?php echo htmlspecialchars($workshop['topics'][3]['title']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="topicDesc4">Descrição</label>
                                        <textarea id="topicDesc4" name="topicDescs[]" class="form-control"><?php echo htmlspecialchars($workshop['topics'][3]['description']); ?></textarea>
                                    </div>
                                </div>
                                <div class="topic-item">
                                    <div class="form-group">
                                        <label for="topicTitle5">Título do Tópico 5</label>
                                        <input type="text" id="topicTitle5" name="topicTitles[]" class="form-control" value="<?php echo htmlspecialchars($workshop['topics'][4]['title']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="topicDesc5">Descrição</label>
                                        <textarea id="topicDesc5" name="topicDescs[]" class="form-control"><?php echo htmlspecialchars($workshop['topics'][4]['description']); ?></textarea>
                                    </div>
                                </div>

                                <div class="topic-item">
                                    <div class="form-group">
                                        <label for="topicTitle6">Título do Tópico 6</label>
                                        <input type="text" id="topicTitle6" name="topicTitles[]" class="form-control" value="<?php echo htmlspecialchars($workshop['topics'][5]['title']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="topicDesc6">Descrição</label>
                                        <textarea id="topicDesc6" name="topicDescs[]" class="form-control"><?php echo htmlspecialchars($workshop['topics'][5]['description']); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informações do Workshop -->
                        <div class="workshop-info">
                            <h3>Informações do Workshop</h3>
                            
                            <div class="workshop-status">
                                <div class="toggle-container">
                                    <label class="switch">
                                        <input type="checkbox" id="workshopActive" name="workshopActive" checked>
                                        <span class="slider round"></span>
                                    </label>
                                    <span class="toggle-label">Workshop Ativo</span>
                                </div>
                                <p class="form-text text-muted">Quando desativado, o botão "Participar do Workshop" não aparecerá na página inicial.</p>
                            </div>
                            
                            <div class="form-group">
                                <label for="workshopTitle">Título</label>
                                <input type="text" id="workshopTitle" name="workshopTitle" class="form-control" value="<?php echo htmlspecialchars($workshop['title']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="workshopDesc">Descrição</label>
                                <textarea id="workshopDesc" name="workshopDesc" class="form-control" rows="3" required><?php echo htmlspecialchars($workshop['description']); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="workshopLocation">Local</label>
                                <input type="text" id="workshopLocation" name="workshopLocation" class="form-control" value="<?php echo htmlspecialchars($workshop['location']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="workshopAddress">Endereço</label>
                                <input type="text" id="workshopAddress" name="workshopAddress" class="form-control" value="<?php echo htmlspecialchars($workshop['address']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="workshopDate">Data</label>
                                <input type="text" id="workshopDate" name="workshopDate" class="form-control" value="<?php echo htmlspecialchars($workshop['date']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="workshopTime">Horário</label>
                                <input type="text" id="workshopTime" name="workshopTime" class="form-control" value="<?php echo htmlspecialchars($workshop['time']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="workshopLink">Link de Compra</label>
                                <input type="text" id="workshopLink" name="workshopLink" class="form-control" value="<?php echo htmlspecialchars($workshop['link']); ?>" required>
                            </div>
                        </div>
                        
                        <button type="submit" name="update_workshop" class="btn btn-primary">
                            <i class="fas fa-save"></i> Salvar Alterações
                        </button>
                    </form>
                </div>
            </div>

            <style>
                /* Estilos para a seção de workshop */
                .workshop-info {
                    margin-top: 30px;
                    padding: 30px;
                    background-color: var(--bg-light);
                    border-radius: 10px;
                }

                .workshop-info h3 {
                    color: var(--primary-color);
                    margin-bottom: 20px;
                }

                .workshop-images {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 20px;
                    margin-bottom: 40px;
                }

                .workshop-image-item {
                    border-radius: 10px;
                    overflow: hidden;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    transition: transform 0.3s ease;
                }

                .workshop-image-item:hover {
                    transform: translateY(-5px);
                }

                .workshop-image-preview {
                    height: 250px;
                }

                .workshop-image-preview img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                .workshop-topics {
                    margin: 40px 0;
                    padding: 30px;
                    background-color: var(--bg-light);
                    border-radius: 10px;
                }

                .topics-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 20px;
                }

                .topic-item {
                    background-color: var(--card-bg);
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                    transition: transform 0.3s ease;
                }

                .topic-item:hover {
                    transform: translateY(-3px);
                }

                /* Estilo para o botão de compra no index.php */
                .workshop-buy-button {
                    display: inline-block;
                    padding: 15px 40px;
                    font-size: 18px;
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

                .workshop-buy-button:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
                    background: linear-gradient(45deg, var(--highlight-color), var(--primary-color));
                }

                .workshop-buy-button-container {
                    text-align: center;
                    margin: 40px 0;
                }
            </style>
            
            <!-- Gerenciar Mídia -->
            <div class="tab-content" id="media-content">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title">Gerenciador de Mídia</h2>
                    </div>
                    <div class="upload-area" style="margin-bottom: 30px;">
                        <h3>Enviar Nova Imagem</h3>
                        <form method="post" action="dashboard.php" enctype="multipart/form-data" class="upload-form">
                            <div class="drop-zone" id="dropZone">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Arraste arquivos aqui ou clique para selecionar</p>
                                <input type="file" id="fileUpload" name="fileUpload" style="display: none;" accept="image/*" multiple>
                            </div>
                            <button type="submit" name="upload_media" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Enviar
                            </button>
                        </form>
                    </div>
                    
                    <div class="media-library">
                        <h3>Biblioteca de Mídia</h3>
                        <div class="media-grid">
                            <div class="media-item">
                                <div class="media-preview">
                                    <img src="assets/images/media-1.jpg" alt="Media 1">
                                </div>
                                <div class="media-info">
                                    <p class="media-name">imagem1.jpg</p>
                                    <div class="media-actions">
                                        <button class="btn btn-secondary btn-sm" data-url="/assets/images/media-1.jpg">
                                            <i class="fas fa-link"></i> Copiar URL
                                        </button>
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Excluir
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="media-item">
                                <div class="media-preview">
                                    <img src="assets/images/media-2.jpg" alt="Media 2">
                                </div>
                                <div class="media-info">
                                    <p class="media-name">imagem2.jpg</p>
                                    <div class="media-actions">
                                        <button class="btn btn-secondary btn-sm" data-url="/assets/images/media-2.jpg">
                                            <i class="fas fa-link"></i> Copiar URL
                                        </button>
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Excluir
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="media-item">
                                <div class="media-preview">
                                    <img src="assets/images/media-3.jpg" alt="Media 3">
                                </div>
                                <div class="media-info">
                                    <p class="media-name">imagem3.jpg</p>
                                    <div class="media-actions">
                                        <button class="btn btn-secondary btn-sm" data-url="/assets/images/media-3.jpg">
                                            <i class="fas fa-link"></i> Copiar URL
                                        </button>
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Excluir
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Função para alternar a visibilidade da barra lateral no mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('dashboard-sidebar');
            sidebar.classList.toggle('active');
        }
        
        // Navegação entre abas
        document.addEventListener('DOMContentLoaded', function() {
            // Botão de toggle para mobile
            const sidebarToggle = document.getElementById('sidebar-toggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
            }
            
            // Navegação principal
            const navLinks = document.querySelectorAll('.nav-link');
            const tabContents = document.querySelectorAll('.tab-content');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remover classe ativa de todos os links
                    navLinks.forEach(link => link.classList.remove('active'));
                    
                    // Adicionar classe ativa ao link clicado
                    this.classList.add('active');
                    
                    // Mostrar conteúdo da aba correspondente
                    const tabId = this.getAttribute('data-tab');
                    tabContents.forEach(tab => tab.classList.remove('active'));
                    document.getElementById(tabId + '-content').classList.add('active');
                    
                    // Fechar o menu no mobile após clicar em um item
                    if (window.innerWidth <= 768) {
                        document.getElementById('dashboard-sidebar').classList.remove('active');
                    }
                });
            });
            
            // Interatividade dos painéis colapsáveis
            const collapsibleHeaders = document.querySelectorAll('.collapsible-header');

            collapsibleHeaders.forEach(header => {
                // Abrir todas as seções por padrão
                const content = header.nextElementSibling;
                const section = header.parentElement;
                
                // Remover a classe hidden de todos os conteúdos
                content.classList.remove('hidden');
                // Adicionar a classe active a todas as seções
                section.classList.add('active');
                // Garantir que o conteúdo seja visível
                content.style.display = 'block';
                content.style.maxHeight = 'none';
                content.style.overflow = 'visible';
                
                // Adicionar o evento de clique
                header.addEventListener('click', function() {
                    const content = this.nextElementSibling;
                    const section = this.parentElement;
                    
                    // Alternar a classe hidden no conteúdo
                    content.classList.toggle('hidden');
                    
                    // Alternar a classe active na seção
                    section.classList.toggle('active');
                    
                    // Garantir que o conteúdo seja visível quando ativo
                    if (!content.classList.contains('hidden')) {
                        content.style.display = 'block';
                        content.style.maxHeight = 'none';
                        content.style.overflow = 'visible';
                    }
                });
            });
            
            // Adicionar interatividade ao botão de tema
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
            
            // Adicionar funcionalidade para upload de arquivos via drag-and-drop
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('fileUpload');
            
            if (dropZone && fileInput) {
                dropZone.addEventListener('click', () => {
                    fileInput.click();
                });
                
                dropZone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropZone.style.backgroundColor = '#e9ecef';
                });
                
                dropZone.addEventListener('dragleave', () => {
                    dropZone.style.backgroundColor = '';
                });
                
                dropZone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropZone.style.backgroundColor = '';
                    
                    if (e.dataTransfer.files.length) {
                        fileInput.files = e.dataTransfer.files;
                        // Atualizar a UI para mostrar os arquivos selecionados
                        const fileNames = Array.from(fileInput.files).map(file => file.name).join(', ');
                        dropZone.querySelector('p').textContent = fileNames;
                    }
                });
                
                fileInput.addEventListener('change', () => {
                    // Atualizar a UI para mostrar os arquivos selecionados
                    if (fileInput.files.length) {
                        const fileNames = Array.from(fileInput.files).map(file => file.name).join(', ');
                        dropZone.querySelector('p').textContent = fileNames;
                    } else {
                        dropZone.querySelector('p').textContent = 'Arraste arquivos aqui ou clique para selecionar';
                    }
                });
            }
            
            // Toggle para ativar/desativar workshop
            const workshopActiveToggle = document.getElementById('workshopActive');
            if (workshopActiveToggle) {
                workshopActiveToggle.addEventListener('change', function() {
                    localStorage.setItem('workshopActive', this.checked);
                    showSuccessMessage(this.checked ? 'Workshop ativado com sucesso!' : 'Workshop desativado com sucesso!');
                });
                
                // Verificar estado salvo
                const workshopActive = localStorage.getItem('workshopActive');
                if (workshopActive !== null) {
                    workshopActiveToggle.checked = workshopActive === 'true';
                }
            }
            
            // Adicionar funcionalidade para os botões de copiar URL
            const copyUrlButtons = document.querySelectorAll('[data-url]');
            copyUrlButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const url = this.getAttribute('data-url');
                    navigator.clipboard.writeText(url).then(() => {
                        const originalText = this.innerHTML;
                        this.innerHTML = '<i class="fas fa-check"></i> Copiado!';
                        setTimeout(() => {
                            this.innerHTML = originalText;
                        }, 2000);
                    });
                });
            });
            
            // Adicionar funcionalidade para adicionar nova pergunta FAQ
            const addFaqButton = document.getElementById('addFaqButton');
            if (addFaqButton) {
                addFaqButton.addEventListener('click', function() {
                    const faqContainer = document.querySelector('#faq-content form');
                    const faqCount = faqContainer.querySelectorAll('.collapsible-section').length + 1;
                    
                    const newFaqItem = document.createElement('div');
                    newFaqItem.className = 'collapsible-section';
                    newFaqItem.innerHTML = `
                        <div class="collapsible-header">
                            <h4>Nova Pergunta</h4>
                            <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                        </div>
                        <div class="collapsible-content">
                            <div class="form-group">
                                <label for="faqQuestion${faqCount}">Pergunta</label>
                                <input type="text" id="faqQuestion${faqCount}" name="faqQuestions[]" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="faqAnswer${faqCount}">Resposta</label>
                                <textarea id="faqAnswer${faqCount}" name="faqAnswers[]" class="form-control" required></textarea>
                            </div>
                            <button type="button" class="btn btn-danger remove-item">
                                <i class="fas fa-trash"></i> Remover
                            </button>
                        </div>
                    `;
                    
                    // Inserir antes do botão de adicionar
                    faqContainer.insertBefore(newFaqItem, addFaqButton.parentNode);
                    
                    // Adicionar evento para o cabeçalho colapsável
                    const header = newFaqItem.querySelector('.collapsible-header');
                    header.addEventListener('click', function() {
                        const content = this.nextElementSibling;
                        const section = this.parentElement;
                        
                        content.classList.toggle('hidden');
                        section.classList.toggle('active');
                    });
                    
                    // Adicionar evento para o botão de remover
                    const removeButton = newFaqItem.querySelector('.remove-item');
                    removeButton.addEventListener('click', function() {
                        if (confirm('Tem certeza que deseja remover esta pergunta?')) {
                            newFaqItem.remove();
                            showSuccessMessage('Pergunta removida com sucesso!');
                        }
                    });
                    
                    // Abrir o novo item
                    newFaqItem.classList.add('active');
                    newFaqItem.querySelector('.collapsible-content').classList.remove('hidden');
                    
                    showSuccessMessage('Nova pergunta adicionada!');
                });
            }
            
            // Adicionar funcionalidade para adicionar novo membro da equipe
            const addTeamButton = document.getElementById('addTeamButton');
            if (addTeamButton) {
                addTeamButton.addEventListener('click', function() {
                    const teamContainer = document.querySelector('#equipe-content form');
                    const teamCount = teamContainer.querySelectorAll('.collapsible-section').length + 1;
                    
                    const newTeamItem = document.createElement('div');
                    newTeamItem.className = 'collapsible-section';
                    newTeamItem.innerHTML = `
                        <div class="collapsible-header">
                            <h4>Novo Membro</h4>
                            <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
                        </div>
                        <div class="collapsible-content">
                            <div class="form-group">
                                <label for="teamName${teamCount}">Nome</label>
                                <input type="text" id="teamName${teamCount}" name="teamNames[]" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="teamRole${teamCount}">Especialidade</label>
                                <input type="text" id="teamRole${teamCount}" name="teamRoles[]" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="teamBio${teamCount}">Currículo</label>
                                <textarea id="teamBio${teamCount}" name="teamBios[]" class="form-control" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Foto</label>
                                <div class="image-preview">
                                    <img src="/placeholder.svg?height=150&width=200" alt="Preview">
                                </div>
                                <div class="custom-file-input">
                                    <input type="file" id="teamPhoto${teamCount}" name="teamPhoto${teamCount}" accept="image/*">
                                    <label for="teamPhoto${teamCount}" class="upload-button">Escolher Imagem</label>
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger remove-item">
                                <i class="fas fa-trash"></i> Remover
                            </button>
                        </div>
                    `;
                    
                    // Inserir antes do botão de adicionar
                    teamContainer.insertBefore(newTeamItem, addTeamButton.parentNode);
                    
                    // Adicionar evento para o cabeçalho colapsável
                    const header = newTeamItem.querySelector('.collapsible-header');
                    header.addEventListener('click', function() {
                        const content = this.nextElementSibling;
                        const section = this.parentElement;
                        
                        content.classList.toggle('hidden');
                        section.classList.toggle('active');
                    });
                    
                    // Adicionar evento para o input de arquivo
                    const fileInput = newTeamItem.querySelector('input[type="file"]');
                    fileInput.addEventListener('change', function() {
                        if (this.files && this.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const preview = newTeamItem.querySelector('.image-preview img');
                                preview.src = e.target.result;
                            };
                            reader.readAsDataURL(this.files[0]);
                        }
                    });
                    
                    // Adicionar evento para o botão de remover
                    const removeButton = newTeamItem.querySelector('.remove-item');
                    removeButton.addEventListener('click', function() {
                        if (confirm('Tem certeza que deseja remover este membro?')) {
                            newTeamItem.remove();
                            showSuccessMessage('Membro removido com sucesso!');
                        }
                    });
                    
                    // Abrir o novo item
                    newTeamItem.classList.add('active');
                    newTeamItem.querySelector('.collapsible-content').classList.remove('hidden');
                    
                    showSuccessMessage('Novo membro adicionado!');
                });
            }
            
            // Adicionar funcionalidade para adicionar novo tópico do workshop
            const addTopicButton = document.getElementById('addTopicButton');
            if (addTopicButton) {
                // Remover o botão, pois não queremos adicionar novos tópicos além dos 6 existentes
                addTopicButton.remove();
            }
            
            // Inicializar eventos para os inputs de arquivo existentes
            const fileInputs = document.querySelectorAll('input[type="file"]');
            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const preview = this.closest('.form-group')?.querySelector('.image-preview img') || 
                                       this.closest('.workshop-image-item')?.querySelector('.workshop-image-preview img');
                        
                        if (preview) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.src = e.target.result;
                            };
                            reader.readAsDataURL(this.files[0]);
                        }
                    }
                });
            });
        });
        
        // Função para mostrar mensagem de sucesso
        function showSuccessMessage(message) {
            // Remover mensagens existentes
            const existingMessages = document.querySelectorAll('.success-message');
            existingMessages.forEach(msg => msg.remove());
            
            // Criar nova mensagem
            const messageElement = document.createElement('div');
            messageElement.className = 'success-message';
            messageElement.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
            
            // Adicionar ao documento
            document.body.appendChild(messageElement);
            
            // Remover após 3 segundos
            setTimeout(() => {
                messageElement.remove();
            }, 3000);
        }
    </script>
</body>
</html>

