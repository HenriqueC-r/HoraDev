# Login System PHP

![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white)
![Composer](https://img.shields.io/badge/Composer-885630?style=flat&logo=composer&logoColor=white)
![JWT](https://img.shields.io/badge/JWT-000000?style=flat&logo=jsonwebtokens&logoColor=white)
![Git](https://img.shields.io/badge/Git-F05032?style=flat&logo=git&logoColor=white)

API de autenticação em PHP puro, construída com foco em segurança e boas práticas de mercado. Desenvolvida como back-end para uma aplicação em **Quasar (Vue.js)**, integrando login, cadastro e rotas protegidas por token JWT.

> 🔗 Este é o back-end do projeto. O front-end (Quasar/Vue.js) que consome esta API está em: **[mirianrl/Quasar-Login-Vue.js](https://github.com/mirianrl/Quasar-Login-Vue.js)**

## Sobre o projeto

Este projeto implementa um fluxo completo de autenticação stateless, sem uso de frameworks — apenas PHP e as bibliotecas essenciais para o que realmente importa: segurança de senha, geração/validação de token e acesso a dados via queries preparadas.

## Funcionalidades

- Cadastro de usuário com senha criptografada (`password_hash`)
- Login com geração de token JWT (expiração de 1h)
- Rotas protegidas por middleware de autenticação
- Consulta de perfil autenticado
- Proteção contra SQL Injection via PDO com *prepared statements*
- Configuração de CORS centralizada
- Variáveis sensíveis (banco de dados, segredo do JWT) isoladas via `.env`

## Tecnologias

- PHP (nativo, sem framework)
- MySQL
- [firebase/php-jwt](https://github.com/firebase/php-jwt) — geração e validação de tokens JWT
- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) — carregamento de variáveis de ambiente
- Composer

## Estrutura do projeto

```
login_system/
├── config/
│   ├── cors.php               # Headers de CORS centralizados
│   ├── env.php                # Carregamento do .env
│   └── jwt.php                # Definição do segredo JWT
├── middleware/
│   └── auth.php               # Middleware de validação de token JWT
├── api/
│   ├── index.php              # Health check da API
│   ├── cadastro.php           # Endpoint de registro de usuário
│   ├── login.php              # Endpoint de login / geração de token
│   ├── perfil.php             # Endpoint protegido: dados do usuário logado
│   └── usuarios.php           # Endpoint protegido: listagem de usuários
├── conexao.php                # Conexão PDO com o MySQL
├── database.sql               # Script de criação do banco e tabela
├── composer.json
└── .env.example                # Modelo de variáveis de ambiente
```

## Como rodar localmente

### Pré-requisitos
- PHP 8+
- MySQL
- Composer

### Passo a passo

```bash
# 1. Clone o repositório
git clone https://github.com/HenriqueC-r/Login-System-PHP.git
cd login_system

# 2. Instale as dependências
composer install

# 3. Configure o ambiente
cp .env.example .env
# edite o .env com suas credenciais de banco e um segredo JWT forte

# 4. Crie o banco de dados
mysql -u root -p < database.sql

# 5. Suba um servidor local
php -S localhost:8000
```

A API ficará disponível em `http://localhost:8000/api/`.

> Para testar o fluxo completo (telas de login, cadastro e dashboard), suba também o [front-end](https://github.com/mirianrl/Quasar-Login-Vue.js) apontando para essa URL.

## Endpoints

| Método | Rota                  | Descrição                              | Autenticação |
|--------|------------------------|------------------------------------------|:---:|
| GET    | `/api/index.php`       | Verifica se a API está online            | Não |
| POST   | `/api/cadastro.php`    | Cria um novo usuário                     | Não |
| POST   | `/api/login.php`       | Autentica e retorna um token JWT         | Não |
| GET    | `/api/perfil.php`      | Retorna os dados do usuário autenticado  | Sim (Bearer Token) |
| GET    | `/api/usuarios.php`    | Lista os usuários cadastrados            | Sim (Bearer Token) |
| POST   | `/api/projetos/criar.php` | Cria um projeto do usuário logado      | Sim (Bearer Token) |
| GET    | `/api/projetos/listar.php` | Lista os projetos do usuário logado    | Sim (Bearer Token) |

### Exemplo — Cadastro

```http
POST /api/cadastro.php
Content-Type: application/json

{
  "nome": "Caio Silva",
  "email": "caio@exemplo.com",
  "senha": "senhaSegura123"
}
```

### Exemplo — Login

```http
POST /api/login.php
Content-Type: application/json

{
  "email": "caio@exemplo.com",
  "senha": "senhaSegura123"
}
```

Resposta:

```json
{
  "status": true,
  "mensagem": "Login realizado com sucesso.",
  "token": "eyJhbGciOiJIUzI1NiIs...",
  "usuario": {
    "id": 1,
    "nome": "Caio Silva",
    "email": "caio@exemplo.com"
  }
}
```

### Exemplo — Rota protegida

```http
GET /api/perfil.php
Authorization: Bearer eyJhbGciOiJIUzI1NiIs...
```

## Segurança

- Senhas nunca armazenadas em texto puro — sempre hasheadas com `password_hash` (bcrypt).
- Todas as queries usam *prepared statements* via PDO, eliminando SQL Injection.
- Segredos de ambiente (JWT e banco de dados) nunca vão para o repositório — apenas `.env.example` como referência.
- Tokens JWT expiram em 1 hora, reduzindo a janela de uso indevido em caso de vazamento.

## Projeto completo

Este back-end faz parte de um sistema de autenticação full stack desenvolvido em dupla:

- **Back-end (PHP)** — este repositório
- **Front-end (Quasar/Vue.js)** — [mirianrl/Quasar-Login-Vue.js](https://github.com/mirianrl/Quasar-Login-Vue.js)

## Autores

- [Caio](https://github.com/HenriqueC-r) — Back-end (PHP)
- [Mirian](https://github.com/mirianrl) — Front-end (Quasar/Vue.js)
# HoraDev
