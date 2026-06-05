# NzolaNet

NzolaNet e uma aplicacao com backend Laravel e frontend Angular.

- Backend: `nzolanet`
- Frontend: `nzolanet-frontend`
- API local: `http://localhost:8000/api`
- Frontend local: `http://localhost:4200`

## Funcionalidades ligadas

- Login e cadastro com JWT.
- Redirecionamento inicial do frontend para a tela de login/cadastro quando nao existe sessao.
- Feed de publicacoes.
- Criacao, edicao e remocao de publicacoes protegidas por autenticacao.
- Comentarios protegidos por autenticacao, usando o utilizador do token em vez de `utilizador_id` enviado pelo cliente.
- Pesquisa de utilizadores.
- Notificacoes do utilizador autenticado.
- Seguidores: seguir, deixar de seguir, listar seguidores e seguindo.

## Requisitos

- PHP 8.2 ou superior
- Composer
- Node.js e npm
- MySQL ou outro banco configurado no `.env`

## Configurar o backend

```bash
cd nzolanet
composer install
copy .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate
php artisan storage:link
php artisan serve --host=127.0.0.1 --port=8000
```

No `.env`, confirma pelo menos estes valores:

```env
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:4200
CORS_ALLOWED_ORIGINS=http://localhost:4200

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nzolanet
DB_USERNAME=root
DB_PASSWORD=
```

## Configurar o frontend

```bash
cd nzolanet-frontend
npm install
npm start
```

O frontend usa `src/environments/environment.ts`:

```ts
export const environment = {
  production: false,
  apiUrl: 'http://localhost:8000/api'
};
```

## Fluxo de autenticacao

Ao abrir `http://localhost:4200`, a aplicacao verifica se existe token JWT no `localStorage`.

- Sem token: mostra obrigatoriamente a tela de login/cadastro.
- Com token valido: carrega o perfil e mostra o feed.
- Com token invalido/expirado: remove a sessao local e volta para login/cadastro.

O backend tambem redireciona `GET /` para `FRONTEND_URL`, para facilitar o arranque durante desenvolvimento.

## Rotas principais da API

Rotas publicas:

```http
POST /api/auth/register
POST /api/auth/login
GET  /api/publicacoes
GET  /api/publicacoes/{id}
```

Rotas protegidas por JWT:

```http
POST   /api/auth/logout
GET    /api/auth/me
POST   /api/auth/refresh
PUT    /api/auth/perfil
POST   /api/auth/foto-perfil

POST   /api/publicacoes
PUT    /api/publicacoes/{id}
DELETE /api/publicacoes/{id}

GET    /api/publicacoes/{publicacao_id}/comentarios
POST   /api/publicacoes/{publicacao_id}/comentarios
PUT    /api/comentarios/{id}
DELETE /api/comentarios/{id}

GET    /api/utilizadores/pesquisa?q=termo
GET    /api/notificacoes
POST   /api/notificacoes/{id}/ler

POST   /api/utilizadores/{id}/seguir
DELETE /api/utilizadores/{id}/seguir
GET    /api/utilizadores/{id}/seguidores
GET    /api/utilizadores/{id}/seguindo
```

## Validacao importante

O backend valida os campos obrigatorios com Form Requests e valida o utilizador autenticado pelo JWT.

- Cadastro exige `nome`, `username`, `email`, `password` e `password_confirmation`.
- Login exige `email` e `password`.
- Publicacao exige pelo menos `texto`, `imagem` ou `video`.
- Comentario exige `texto`; o autor e sempre obtido pelo token.

## Verificacao

Com os dois servidores ativos:

1. Abre `http://localhost:4200`.
2. Confirma que a primeira tela e login/cadastro.
3. Cria uma conta ou faz login.
4. Testa criar publicacao, comentar, pesquisar utilizadores e abrir notificacoes.

Para validar rotas no backend:

```bash
cd nzolanet
php artisan route:list --path=api
```

Para validar o build do frontend:

```bash
cd nzolanet-frontend
npx ng build --configuration development --progress=false
```
