# Lumen Multi-Tenancy REST API

Projeto API REST multitenancy com isolamento completo de dados, autenticação JWT e auditoria segregada entre core e tenants.

![Docker](https://img.shields.io/badge/Docker-%232496ED?logo=docker&logoColor=white)
![Lumen](https://img.shields.io/badge/Lumen-%23FF2D20?logo=laravel&logoColor=white)
![PHPUnit](https://img.shields.io/badge/PHPUnit-%234484C5?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-%2300758F?logo=mysql&logoColor=white)
![JWT](https://img.shields.io/badge/JWT-%23000000?logo=jsonwebtokens&logoColor=white)

## 📋 Índice
- [Visão Geral](#-visão-geral)
- [Endpoints Principais](#-endpoints-principais)
- [Instalação](#-instalação)
- [Uso com Postman](#-uso-com-postman)
- [Migrações](#-migrações)
- [Testes](#-testes)
- [Comandos Docker](#-comandos-docker)

## 🌟 Visão Geral
Arquitetura modular com dois contextos principais:
1. **Core API** (`/core`): 
   - Gestão central de tenants
   - Autenticação master
   - Auditoria administrativa

2. **Tenant API** (`/`):
   - Operações específicas do tenant
   - Usuários locais
   - Auditoria segregada

## 🚀 Endpoints Principais

### 🔑 Autenticação
| Método | Endpoint               | Descrição                     |
|--------|------------------------|-------------------------------|
| POST   | `/core/login`          | Login no sistema core         |
| POST   | `/login`               | Login em tenant específico    |

**Exemplo Request:**
```json
{
  "email": "admin@example.com",
  "password": "1234"
}
```

### 👥 Gestão de Tenants (Core)
| Método | Endpoint                      | Ação                          |
|--------|-------------------------------|-------------------------------|
| POST   | `/api/v1/core/tenants`        | Criar novo tenant             |
| GET    | `/api/v1/core/tenants`        | Listar todos tenants          |
| PUT    | `/api/v1/core/tenants/{slug}` | Atualizar tenant              |
| DELETE | `/api/v1/core/tenants/{slug}` | Remover tenant                |

**Exemplo Create Tenant:**
```bash
curl -X POST http://localhost:3001/core/tenants \
  -H "Authorization: Bearer {JWT_CORE}" \
  -d '{
    "name": "Meu Tenant",
    "slug": "tenant",
    "db_host": "db",
    "db_name": "tenant_01",
    "db_user": "root",
    "db_password": "root"
  }'
```

### 👤 Operações por Tenant
| Método | Endpoint                     | Descrição                 |
|--------|------------------------------|---------------------------|
| POST   | `/api/v1/users`            | Criar usuário no tenant   |
| GET    | `/api/v1/users`            | Listar usuários           |
| PUT    | `/api/v1/users/{username}` | Atualizar usuário         |

**Exemplo Header:**
```bash
-H "X-Tenant: tenant" 
-H "Authorization: Bearer {JWT_TENANT}"
```

### 📊 Auditoria
| Contexto | Endpoint           | Dados Auditados               |
|----------|--------------------|-------------------------------|
| Core     | `/core/logs`       | Operações administrativas     |
| Tenant   | `/{tenant}/logs`   | Ações específicas do tenant   |

## 📦 Instalação
```bash
git clone https://github.com/raulntjj/multitenancy-api.git
cd multitenancy-api
make build
```
## 📦 Migrações
**Para tenants específicos:**
```bash
docker compose exec multitenancy php artisan tenant:migrate tenant
```

**Rollback:**
```bash
docker compose exec multitenancy php artisan tenant:rollback --step=2
```

## 🧪 Testes
Para testes está sendo utilizado a framework PHPUnit, para rodar os testes utilize:
```bash
make test
```

## 🐳 Comandos Docker/Makefile
| Comando       | Descrição                                  |
|---------------|--------------------------------------------|
| `make build`  | Constrói os containers e inicia a API.     |
| `make kill`   | Para e remove todos os containers/volumes. |
| `make start`  | Inicia os containers.                      |
| `make stop`   | Para os containers.                        |
| `make restart`| Reinicia os containers.                    |
| `make logs`   | Exibe logs em tempo real.                  |
| `make shell`  | Acessa o shell do container da API.        |
| `make test`   | Executa testes PHPUnit.                    |

## 📄 Licença
[MIT](https://choosealicense.com/licenses/mit/) - Consulte `LICENSE` para detalhes.