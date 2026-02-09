# Burh Desafio Backend

API RESTful para o desafio técnico da Burh, desenvolvida em Laravel 12.

## Tecnologias
- Laravel 12 / PHP 8.2
- PostgreSQL 15 (Docker)
- Nginx (Docker)

## Funcionalidades
- **Docker:** Ambiente isolado com Docker Compose.
- **Banco de Dados:** Postgres configurado na porta `5433` do host para evitar conflitos.
- **Validação de CNPJ:** Suporte ao novo padrão alfanumérico (Receita Federal 07/2026).
- **Validação de CPF:** Algoritmo completo com verificação de dois dígitos e bloqueio de repetições.
- **Tradução:** Mensagens de erro e respostas da API em pt-BR.
- **Respostas JSON:** Validação de erros retorna 422 (JSON) automaticamente, sem necessidade do header `Accept`.
- **Filtros:** Busca de usuários por nome, email ou CPF com listagem detalhada de vagas.

## Setup
1. Suba os containers:
   ```bash
   docker-compose up -d
   ```
2. Instale as dependências e rode as migrations:
   ```bash
   docker exec -it burh-app composer install
   docker exec -it burh-app php artisan key:generate
   docker exec -it burh-app php artisan migrate
   ```

## API
- Endpoint base: `http://localhost:8000/api`
- Postgres (Host): `localhost:5433` | User: `burh_user` | Pass: `burh_password`

---

## Checklist de Requisitos
- [x] CRUD Empresa (nome, descrição, CNPJ, plano)
- [x] CRUD Vaga (título, descrição, tipo, salário, horário)
- [x] CRUD Usuário (nome, e-mail, CPF, idade)
- [x] Rota de candidatura
- [x] Validação de e-mail/CPF/CNPJ únicos
- [x] Limites de vagas por plano (Free: 5, Premium: 10)
- [x] Salário/horário obrigatórios para CLT e Estágio
- [x] Salário mínimo CLT (R$ 1.212,00)
- [x] Horário máximo Estágio (6h)
- [x] Busca de usuários com filtros e relação de vagas
- [x] Ambiente Docker
- [x] Tratamento de erros e pt-BR local

---
*Instruções originais do desafio movidas para [DESCRICAO.md](DESCRICAO.md).*
