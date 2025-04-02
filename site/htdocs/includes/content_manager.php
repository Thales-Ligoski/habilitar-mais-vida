<?php
// Habilitar exibição de erros para debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Diretório para armazenar os arquivos JSON
define('CONTENT_DIR', __DIR__ . '/../data/content/');
define('IMAGES_DIR', __DIR__ . '/../data/images/');

// Criar diretórios se não existirem
if (!file_exists(CONTENT_DIR)) {
    if (!mkdir(CONTENT_DIR, 0755, true)) {
        error_log("Erro ao criar diretório CONTENT_DIR: " . CONTENT_DIR);
        die("Erro ao criar diretório de conteúdo");
    }
}

if (!file_exists(IMAGES_DIR)) {
    if (!mkdir(IMAGES_DIR, 0755, true)) {
        error_log("Erro ao criar diretório IMAGES_DIR: " . IMAGES_DIR);
        die("Erro ao criar diretório de imagens");
    }
}

// Verificar permissões dos diretórios
if (!is_writable(CONTENT_DIR)) {
    error_log("Diretório CONTENT_DIR não tem permissão de escrita: " . CONTENT_DIR);
    die("Erro de permissão no diretório de conteúdo");
}

if (!is_writable(IMAGES_DIR)) {
    error_log("Diretório IMAGES_DIR não tem permissão de escrita: " . IMAGES_DIR);
    die("Erro de permissão no diretório de imagens");
}

/**
 * Salva conteúdo em um arquivo JSON
 */
function saveContent($section, $field, $content) {
    try {
        $file = CONTENT_DIR . $section . '.json';
        $data = [];
        
        // Carregar dados existentes se o arquivo existir
        if (file_exists($file)) {
            $json_content = file_get_contents($file);
            if ($json_content === false) {
                error_log("Erro ao ler arquivo: " . $file);
                return false;
            }
            $data = json_decode($json_content, true) ?? [];
        }
        
        // Atualizar ou adicionar o campo
        $data[$field] = $content;
        
        // Salvar no arquivo
        $result = file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
        if ($result === false) {
            error_log("Erro ao salvar arquivo: " . $file);
            return false;
        }
        return true;
    } catch (Exception $e) {
        error_log("Erro ao salvar conteúdo: " . $e->getMessage());
        return false;
    }
}

/**
 * Recupera conteúdo de um arquivo JSON
 */
function getContent($section, $field) {
    try {
        $file = CONTENT_DIR . $section . '.json';
        
        if (file_exists($file)) {
            $json_content = file_get_contents($file);
            if ($json_content === false) {
                error_log("Erro ao ler arquivo: " . $file);
                return null;
            }
            $data = json_decode($json_content, true);
            if ($data === null) {
                error_log("Erro ao decodificar JSON do arquivo: " . $file);
                return null;
            }
            return $data[$field] ?? null;
        }
        
        return null;
    } catch (Exception $e) {
        error_log("Erro ao recuperar conteúdo: " . $e->getMessage());
        return null;
    }
}

/**
 * Salva uma imagem em um arquivo JSON
 */
function saveImage($section, $field, $image_data) {
    try {
        $file = IMAGES_DIR . $section . '.json';
        $data = [];
        
        // Carregar dados existentes se o arquivo existir
        if (file_exists($file)) {
            $json_content = file_get_contents($file);
            if ($json_content === false) {
                error_log("Erro ao ler arquivo: " . $file);
                return false;
            }
            $data = json_decode($json_content, true) ?? [];
        }
        
        // Converter imagem para base64
        $base64_image = base64_encode($image_data);
        
        // Atualizar ou adicionar o campo
        $data[$field] = $base64_image;
        
        // Salvar no arquivo
        $result = file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
        if ($result === false) {
            error_log("Erro ao salvar arquivo: " . $file);
            return false;
        }
        return true;
    } catch (Exception $e) {
        error_log("Erro ao salvar imagem: " . $e->getMessage());
        return false;
    }
}

/**
 * Recupera uma imagem de um arquivo JSON
 */
function getImage($section, $field) {
    try {
        $file = IMAGES_DIR . $section . '.json';
        
        if (file_exists($file)) {
            $json_content = file_get_contents($file);
            if ($json_content === false) {
                error_log("Erro ao ler arquivo: " . $file);
                return null;
            }
            $data = json_decode($json_content, true);
            if ($data === null) {
                error_log("Erro ao decodificar JSON do arquivo: " . $file);
                return null;
            }
            $base64_image = $data[$field] ?? null;
            
            if ($base64_image) {
                return base64_decode($base64_image);
            }
        }
        
        return null;
    } catch (Exception $e) {
        error_log("Erro ao recuperar imagem: " . $e->getMessage());
        return null;
    }
}

/**
 * Recupera todo o conteúdo de uma seção
 */
function getAllContent($section) {
    try {
        $file = CONTENT_DIR . $section . '.json';
        
        if (file_exists($file)) {
            $json_content = file_get_contents($file);
            if ($json_content === false) {
                error_log("Erro ao ler arquivo: " . $file);
                return [];
            }
            $data = json_decode($json_content, true);
            if ($data === null) {
                error_log("Erro ao decodificar JSON do arquivo: " . $file);
                return [];
            }
            return $data;
        }
        
        return [];
    } catch (Exception $e) {
        error_log("Erro ao recuperar todo o conteúdo: " . $e->getMessage());
        return [];
    }
}

/**
 * Recupera todas as imagens de uma seção
 */
function getAllImages($section) {
    try {
        $file = IMAGES_DIR . $section . '.json';
        
        if (file_exists($file)) {
            $json_content = file_get_contents($file);
            if ($json_content === false) {
                error_log("Erro ao ler arquivo: " . $file);
                return [];
            }
            $data = json_decode($json_content, true);
            if ($data === null) {
                error_log("Erro ao decodificar JSON do arquivo: " . $file);
                return [];
            }
            
            $images = [];
            foreach ($data as $field => $base64_image) {
                $images[$field] = base64_decode($base64_image);
            }
            return $images;
        }
        
        return [];
    } catch (Exception $e) {
        error_log("Erro ao recuperar todas as imagens: " . $e->getMessage());
        return [];
    }
}

// Função para garantir que arrays tenham valores padrão
function getDefaultArray($data, $defaultCount = 6) {
    if (empty($data) || !is_array($data)) {
        $data = [];
    }
    
    // Preencher com valores padrão se necessário
    while (count($data) < $defaultCount) {
        $data[] = [
            'title' => '',
            'description' => '',
            'icon' => '',
            'question' => '',
            'answer' => '',
            'name' => '',
            'role' => '',
            'bio' => ''
        ];
    }
    
    return $data;
}
?> 