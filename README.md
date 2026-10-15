
# HoraDev 🚀

API para gerenciamento de projetos e acompanhamento de tempo de desenvolvimento.

O **HoraDev** é um projeto desenvolvido para praticar desenvolvimento backend com PHP, autenticação JWT, banco de dados MySQL, APIs REST e Docker.

## ✨ Funcionalidades

- Cadastro e autenticação de usuários
- Autenticação baseada em JWT
- Consulta de perfil autenticado
- Criação e listagem de projetos
- Controle de sessões de tempo por projeto
- Início e encerramento de cronômetros
- Cálculo do tempo acumulado em cada projeto
- Ambiente conteinerizado com Docker Compose

## 🛠️ Tecnologias

- PHP 8.3
- MySQL 8.4
- Apache
- Docker e Docker Compose
- JWT (JSON Web Token)
- PDO
- Insomnia para testes da API

## 📋 Pré-requisitos

- Docker
- Docker Compose
- Git
- Insomnia ou outra ferramenta para testar APIs

## 🚀 Como executar

### 1. Clone o repositório

```bash
git clone https://github.com/HenriqueC-r/HoraDev.git
cd HoraDev
```

### 2. Configure as variáveis de ambiente

Configure o arquivo `.env` com as credenciais do banco de dados e a chave secreta do JWT.

Não compartilhe credenciais reais nem publique o arquivo `.env` no GitHub.

### 3. Inicie a aplicação

```bash
docker compose up --build -d
```

### 4. Verifique se a API está online

Acesse:

```text
http://localhost:8000/api/index.php
```

Resposta esperada:

```json
{
  "status": true,
  "mensagem": "API online"
}
```

### 5. Verifique os containers

```bash
docker compose ps
```

Para acompanhar os logs:

```bash
docker compose logs -f app
```

## 🔌 Endpoints da API

URL base:

```text
http://localhost:8000/api
```

### Autenticação e perfil

| Método | Endpoint | Descrição | Autenticação |
|--------|----------|-----------|--------------|
| GET | `/index.php` | Verifica se a API está online | Não |
| POST | `/cadastro.php` | Cadastra um usuário | Não |
| POST | `/login.php` | Autentica um usuário | Não |
| GET | `/perfil.php` | Consulta o perfil do usuário | JWT |

### Gerenciamento de projetos

| Método | Endpoint | Descrição | Autenticação |
|--------|----------|-----------|--------------|
| POST | `/projetos/criar.php` | Cria um projeto | JWT |
| GET | `/projetos/listar.php` | Lista os projetos do usuário autenticado | JWT |

### Controle de tempo

| Método | Endpoint | Descrição | Autenticação |
|--------|----------|-----------|--------------|
| POST | `/tempo/iniciar.php` | Inicia o cronômetro de um projeto | JWT |
| POST | `/tempo/parar.php` | Encerra o cronômetro de um projeto | JWT |

## 🔐 Autenticação JWT

As rotas protegidas exigem um token JWT válido.

Após realizar o login, utilize o token retornado na resposta da API.

Adicione o seguinte cabeçalho HTTP às requisições autenticadas:

```http
Authorization: Bearer SEU_TOKEN_JWT
```

Substitua `SEU_TOKEN_JWT` pelo token recebido no login.

### Exemplo: criar um projeto

**Endpoint:**

```http
POST /api/projetos/criar.php
```

**Corpo da requisição:**

```json
{
  "titulo": "Meu projeto",
  "descricao": "Descrição do projeto",
  "repositorio_url": "https://github.com/HenriqueC-r",
  "demo_url": "https://example.com"
}
```

**Exemplo de resposta:**

```json
{
  "status": true,
  "mensagem": "Projeto criado com sucesso.",
  "projeto_id": 1
}
```

*Observação: confira o formato exato das respostas no código da API, pois ele pode variar conforme a implementação.*

## 🧪 Testes realizados

Os principais fluxos foram testados manualmente utilizando o Insomnia:

- Cadastro de usuários
- Login e geração de token JWT
- Consulta de perfil autenticado
- Criação e listagem de projetos
- Início e encerramento de cronômetros
- Cálculo do tempo acumulado por projeto

## 🐳 Infraestrutura Docker

O ambiente utiliza Docker Compose para executar:

- **Aplicação:** PHP 8.3 com Apache
- **Banco de dados:** MySQL 8.4
- **Persistência:** volume Docker para os dados do banco
- **Configuração:** variáveis de ambiente

## 🗺️ Próximos passos

- Desenvolvimento do frontend
- Expansão da documentação dos endpoints
- Implementação de testes automatizados
- Melhorias contínuas na validação e no tratamento de erros

## 👨‍💻 Autor

**Caio Henrique Ramos**

- GitHub: [HenriqueC-r](https://github.com/HenriqueC-r)

---

Projeto desenvolvido para aprendizado prático de desenvolvimento backend e engenharia de software.
