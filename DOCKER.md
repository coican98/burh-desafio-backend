# Desafio Backend BURH - Docker

Este projeto utiliza Docker para facilitar o desenvolvimento e testes.

## 🐳 Requisitos

- Docker
- Docker Compose

## 🚀 Como Rodar

### 1. Clone o repositório (se ainda não fez)
```bash
git clone <seu-repositorio>
cd burh-desafio-backend
```

### 2. Suba os containers
```bash
docker-compose up -d
```

Isso irá criar 3 containers:
- **burh-app**: Aplicação Laravel (PHP 8.2)
- **burh-nginx**: Servidor web Nginx
- **burh-db**: Banco de dados PostgreSQL 15

### 3. Instale as dependências (primeira vez)
```bash
docker-compose exec app composer install
```

### 4. Rode as migrations
```bash
docker-compose exec app php artisan migrate
```

### 5. Acesse a aplicação
A API estará disponível em: **http://localhost:8000**

## 📝 Comandos Úteis

```bash
# Ver logs dos containers
docker-compose logs -f

# Acessar o container da aplicação
docker-compose exec app bash

# Rodar migrations
docker-compose exec app php artisan migrate

# Rodar migrations do zero (apaga tudo)
docker-compose exec app php artisan migrate:fresh

# Rodar testes
docker-compose exec app php artisan test

# Parar os containers
docker-compose down

# Parar e remover volumes (apaga banco de dados)
docker-compose down -v
```

## 🗄️ Banco de Dados

**Conexão PostgreSQL:**
- Host: `localhost` (ou `db` dentro do container)
- Porta: `5432`
- Database: `burh_desafio`
- Usuário: `burh_user`
- Senha: `burh_password`

## 📦 Estrutura Docker

- `Dockerfile`: Imagem customizada do PHP 8.2 com extensões necessárias
- `docker-compose.yml`: Orquestração dos serviços
- `docker/nginx/default.conf`: Configuração do Nginx

## 🔧 Troubleshooting

### Erro de permissão
```bash
docker-compose exec app chmod -R 777 storage bootstrap/cache
```

### Recriar containers
```bash
docker-compose down
docker-compose up -d --build
```
