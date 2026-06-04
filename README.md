# NzolaNet 🌐

> Rede social académica desenvolvida como projecto da disciplina de Aplicações Web (AW).

---

## 📋 Índice

- [Sobre o Projecto](#sobre-o-projecto)
- [Funcionalidades](#funcionalidades)
- [Stack Tecnológica](#stack-tecnológica)
- [Arquitectura](#arquitectura)
- [Requisitos](#requisitos)
- [Instalação e Configuração](#instalação-e-configuração)
- [Endpoints da API](#endpoints-da-api)
- [Estrutura do Projecto](#estrutura-do-projecto)
- [Base de Dados](#base-de-dados)
- [Equipa](#equipa)

---

## 📖 Sobre o Projecto

O **NzolaNet** é uma plataforma de rede social que permite aos utilizadores publicarem conteúdos, interagirem através de **bazes** (reacções) e comentários, e manterem um perfil pessoal. O projecto foi desenvolvido seguindo boas práticas de engenharia de software, com arquitectura limpa, separação de responsabilidades e segurança na autenticação.

---

## ✅ Funcionalidades

### Gestão de Utilizadores
- [x] Registo de novos utilizadores
- [x] Autenticação com JWT
- [x] Edição de perfil
- [x] Alteração de foto de perfil
- [ ] Seguir / Deixar de seguir utilizadores
- [ ] Recuperação de senha

### Gestão de Publicações
- [ ] Criar publicações com texto, imagem e/ou vídeo
- [ ] Editar publicações próprias
- [ ] Eliminar publicações próprias
- [ ] Visualizar publicações em ordem cronológica

### Gestão de Comentários
- [x] Adicionar comentários
- [x] Editar comentários próprios
- [x] Eliminar comentários próprios
- [x] Listar comentários por publicação

### Bazes
- [ ] Dar baze numa publicação
- [ ] Remover baze
- [ ] Impedir bazes duplicadas do mesmo utilizador

### Feed de Notícias
- [ ] Feed principal com publicações recentes
- [ ] Publicações de utilizadores seguidos
- [ ] Ordenação cronológica

### Notificações
- [ ] Notificação ao receber baze
- [ ] Notificação ao receber comentário
- [ ] Notificação ao ganhar novo seguidor

---

## 🛠️ Stack Tecnológica

| Camada | Tecnologia | Versão |
|--------|-----------|--------|
| Frontend | Angular | 21.x |
| Backend | PHP Laravel | 12.x |
| Base de Dados | MySQL | 8.x |
| Autenticação | JWT (tymon/jwt-auth) | 2.x |
| Servidor Local | XAMPP | — |

---

## 🏗️ Arquitectura

O backend foi desenvolvido com uma arquitectura limpa em camadas:
Request (HTTP)
↓
Controller  → recebe o pedido e devolve a resposta JSON
↓
Service     → contém toda a lógica de negócio
↓
Repository  → comunica com a base de dados
↓
Model       → representa a tabela na base de dados

**Padrões utilizados:**
- Repository Pattern
- Service Layer
- DTO (Data Transfer Object)
- Interface Segregation

---

## 📦 Requisitos

Antes de começar, garante que tens instalado:

- [PHP](https://www.php.net/) >= 8.2
- [Composer](https://getcomposer.org/) >= 2.x
- [Node.js](https://nodejs.org/) >= 20.x
- [NPM](https://www.npmjs.com/) >= 10.x
- [Angular CLI](https://angular.io/cli) >= 21.x
- [XAMPP](https://www.apachefriends.org/) (MySQL + Apache)

---

## ⚙️ Instalação e Configuração

### 1. Clonar o repositório

```bash
git clone https://github.com/seu-usuario/nzolanet.git
cd nzolanet
```

### 2. Instalar dependências do backend

```bash
composer install
```

### 3. Configurar o ficheiro de ambiente

```bash
cp .env.example .env
```

Edita o ficheiro `.env` com as tuas configurações:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nzolanet
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Gerar a chave da aplicação

```bash
php artisan key:generate
```

### 5. Gerar a chave JWT

```bash
php artisan jwt:secret
```

### 6. Criar a base de dados

Abre o phpMyAdmin em `http://localhost/phpmyadmin` e cria uma base de dados chamada `nzolanet`.

### 7. Executar as migrations

```bash
php artisan migrate
```

### 8. Criar o link simbólico para o storage

```bash
php artisan storage:link
```

### 9. Iniciar o servidor

```bash
php artisan serve
```

A API estará disponível em: `http://127.0.0.1:8000`

---

## 🔌 Endpoints da API

### Autenticação — Públicos

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `POST` | `/api/auth/register` | Registar novo utilizador |
| `POST` | `/api/auth/login` | Login e obtenção de token JWT |

### Autenticação — Protegidos 🔒

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/auth/me` | Ver dados do utilizador autenticado |
| `PUT` | `/api/auth/perfil` | Editar perfil |
| `POST` | `/api/auth/foto-perfil` | Actualizar foto de perfil |
| `POST` | `/api/auth/logout` | Terminar sessão |
| `POST` | `/api/auth/refresh` | Renovar token JWT |

> 🔒 As rotas protegidas requerem o header: `Authorization: Bearer {token}`

---

## 🗄️ Base de Dados

O projecto utiliza **6 tabelas** no MySQL:

| Tabela | Descrição |
|--------|-----------|
| `utilizadores` | Dados dos utilizadores registados |
| `publicacoes` | Publicações criadas pelos utilizadores |
| `comentarios` | Comentários nas publicações |
| `bazes` | Reacções (likes) nas publicações |
| `seguidores` | Relações de seguidor entre utilizadores |
| `notificacoes` | Notificações geradas pelo sistema |

---

## 👨‍💻 Equipa

| Nome | Responsabilidade |
|------|-----------------|
| [Palmira Nzau] | Gestão de Utilizadores + Frontend |
| [Florindo Albino] | Gestão de Publicações + Frontend |
| [Josué Dosidiana] | Gestão de Comentários + Frontend |

---

## 📄 Licença

Este projecto foi desenvolvido para fins académicos no âmbito da disciplina de **Aplicações Web** do **Instituto Superior Politécnico de Tecnologias e Ciências**.

---

<p align="center">
  <i>May The Code Be With You 🚀</i>
</p>


