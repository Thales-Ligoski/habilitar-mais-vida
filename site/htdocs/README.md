# Habilitar Mais Vida - Sistema de Gerenciamento de Conteúdo

Este é um sistema de gerenciamento de conteúdo para o site Habilitar Mais Vida, desenvolvido em PHP e utilizando arquivos JSON para armazenamento de dados.

## Estrutura do Projeto

```
/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── data/
│   ├── content/     # Arquivos JSON para conteúdo de texto
│   └── images/      # Arquivos JSON para imagens
├── includes/
│   └── content_manager.php
├── .htaccess
├── dashboard.php
├── index.php
├── login.php
└── README.md
```

## Requisitos

- PHP 7.4 ou superior
- Servidor web (Apache/Nginx)
- Permissões de escrita no diretório `data/`

## Instalação

1. Clone o repositório para seu servidor web
2. Certifique-se que o diretório `data/` e seus subdiretórios têm permissões de escrita:
   ```bash
   chmod -R 777 data/
   ```
3. Configure seu servidor web para apontar para o diretório do projeto
4. Acesse o site através do navegador

## Funcionalidades

### Gerenciamento de Conteúdo

O sistema permite gerenciar o conteúdo do site através do painel administrativo (`dashboard.php`), incluindo:

- Seção Hero (título e descrição)
- Seção Vantagens (títulos, descrições e ícones)
- Seção FAQ (perguntas e respostas)
- Seção Equipe (membros, funções e biografias)
- Seção Serviços (títulos e descrições)
- Seção Workshop (informações, tópicos e imagens)

### Armazenamento de Dados

O sistema utiliza arquivos JSON para armazenar os dados, separados em:

- `data/content/`: Armazena conteúdo de texto
- `data/images/`: Armazena imagens em formato base64

### Segurança

- Proteção do diretório de dados através do `.htaccess`
- Validação de sessão para acesso ao painel administrativo
- Escape de caracteres especiais para prevenir XSS
- Forçamento de HTTPS

## Uso

1. Faça login no painel administrativo (`dashboard.php`)
2. Navegue pelas diferentes seções do site
3. Edite o conteúdo conforme necessário
4. Clique em "Salvar" para persistir as alterações

## Manutenção

### Backup

Para fazer backup do conteúdo:

1. Copie o diretório `data/` para um local seguro
2. Mantenha uma cópia dos arquivos JSON

### Restauração

Para restaurar um backup:

1. Substitua o conteúdo do diretório `data/` pelo backup
2. Certifique-se que as permissões estão corretas

## Suporte

Em caso de problemas ou dúvidas, entre em contato através do email: suporte@habilitarmaisvida.com.br

## Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.