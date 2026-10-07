# burh-desafio-backend

# Laravel Backend Challenge

REST API implemented for a backend technical challenge using **Laravel 12, PHP 8.2, PostgreSQL, Docker, and Nginx**.

The project focuses on API design, relational data, validation, business rules, filtering, and a reproducible containerized development environment.

## Tech Stack

- Laravel 12
- PHP 8.2
- PostgreSQL 15
- Docker / Docker Compose
- Nginx

## Features

- Company CRUD.
- Job opening CRUD.
- User CRUD.
- Job application endpoint.
- Unique e-mail, CPF, and CNPJ validation.
- Custom CPF validation with check-digit verification and repeated-digit protection.
- CNPJ validation compatible with the newer alphanumeric format.
- Plan-based job limits:
  - Free: up to 5 job openings.
  - Premium: up to 10 job openings.
- Employment-type business rules for salary and working hours.
- User filtering by name, e-mail, or CPF.
- Related job/application data in API responses.
- Structured JSON validation errors with HTTP 422 responses.
- pt-BR validation and response messages.
- Containerized local environment.

## API

Base URL:

```text
http://localhost:8000/api
```

Main resources:

```text
/api/empresas
/api/usuarios
/api/vagas
/api/vagas/candidatar
```

## Running Locally

Start the containers:

```bash
docker-compose up -d
```

Install dependencies, generate the application key, and run migrations:

```bash
docker exec -it burh-app composer install
docker exec -it burh-app php artisan key:generate
docker exec -it burh-app php artisan migrate
```

PostgreSQL is exposed on host port `5433` to avoid conflicts with a local PostgreSQL installation.

## Validation Examples

The repository includes screenshots under `docs/img` demonstrating custom validation and business-rule behavior, including CPF validation, plan limits, and job-removal flows.

## Original Challenge

The original challenge instructions are preserved in [DESCRICAO.md](DESCRICAO.md).

---

## Português

API REST desenvolvida em **Laravel 12**, com **PostgreSQL, Docker e Nginx**, implementando CRUDs relacionais, candidatura a vagas, filtros, validações customizadas de CPF/CNPJ e regras de negócio baseadas em planos e tipos de contratação.
