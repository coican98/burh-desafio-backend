# Setup Local

Siga os passos abaixo para rodar o projeto utilizando o Docker.

## Instalação

1. Clone o repositório:
```bash
git clone <url-do-repositorio>
cd burh-desafio-backend
```

2. Crie o arquivo `.env`:
```bash
cp .env.example .env
```

3. Suba os containers:
```bash
docker-compose up -d
```

4. Instale as dependências e gere a chave:
```bash
docker exec -it burh-app composer install
docker exec -it burh-app php artisan key:generate
```

5. Execute as migrations:
```bash
docker exec -it burh-app php artisan migrate
```

## Configuração do .env

As variáveis principais para o funcionamento local (Docker) são:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_LOCALE=pt_BR

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=burh_db
DB_USERNAME=burh_user
DB_PASSWORD=burh_password
```

## Acesso
- **API:** http://localhost:8000/api
- **Postgres (External):** localhost:5433
