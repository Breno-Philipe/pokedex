# Pokédex Laravel App

Aplicação web desenvolvida em **Laravel** que consome a **PokéAPI**, permite importar Pokémons para um banco **MySQL**, favoritar Pokémons e gerenciar usuários com diferentes níveis de permissão.

Este projeto foi desenvolvido como parte de um **desafio técnico para vaga de desenvolvedor Laravel Pleno**.

---

# Tecnologias utilizadas

- PHP 8+
- Laravel 12
- Blade
- MySQL
- PokéAPI
- Git

---

# Funcionalidades

## Autenticação e permissões

A aplicação possui três níveis de acesso:

### Viewer
- Pode visualizar Pokémon importados
- Pode pesquisar Pokémon salvos no banco

### Editor
- Pode importar Pokémon da PokéAPI
- Pode favoritar Pokémon

### Admin
- Pode gerenciar usuários
- Pode alterar roles
- Pode remover Pokémon importados

---

# Integração com PokéAPI

A aplicação consome dados da **PokéAPI**:

https://pokeapi.co/

A integração foi implementada através de um **service dedicado**:

`App\Services\PokeApiClient`

Recursos implementados:

- Cache de respostas da API
- Retry automático em requisições
- Timeout configurado
- Logs de falha de integração

---

# Persistência de dados

Pokémon importados são armazenados no banco de dados.

Campos armazenados:

- api_id
- name
- height
- weight
- sprite

Relacionamentos:

Pokemon ↔ Types (Many to Many)

Também existe relacionamento para favoritos:

User ↔ Pokemon (Favorites)

---

# Arquitetura do projeto

O projeto segue uma organização baseada em separação de responsabilidades.

Principais camadas:

Controllers
Services
FormRequests
Policies
Blade Components

Principais serviços:

- `PokeApiClient`
- `PokemonImporter`
- `PokemonDashboardService`
- `PokemonDetailsService`
- `PokemonSearchService`
- `FavoritePokemonService`
- `FavoriteService`
- `UserManagementService`

---

# Cache da API

As respostas da PokéAPI são armazenadas em cache por **5 minutos** para melhorar performance e reduzir requisições externas.

---

# Logs de integração

Falhas de comunicação com a PokéAPI são registradas em:

storage/logs/laravel.log

---

## Requisitos

- PHP 8.2+
- Composer
- MySQL

---

# Instalação do projeto

Clone o repositório:

```bash
git clone https://github.com/Breno-Philipe/pokedex.git
```

Entre na pasta do projeto:

```bash
cd pokedex
```

Instale as dependências:

```bash
composer install
```

---

# Configuração do ambiente

Copie o arquivo de ambiente:

```bash
cp .env.example .env
```

Crie o banco de dados e depois configure o mesmo no .env:

Exemplo:

```bash
DB_DATABASE=pokedex
DB_USERNAME=root
DB_PASSWORD=
```

Gerar chave da aplicação:

```bash
php artisan key:generate
```

Executar migrations e seeders:

```bash
php artisan migrate --seed
```

Rodar a aplicação:

```bash
php artisan serv
```

---

# Usuários de teste

Após executar os seeders, os seguintes usuários estarão disponíveis:

| Role   | Email            | Password |
| ------ | ---------------- | -------- |
| Admin  | admin@email.com  | 123456   |
| Editor | editor@email.com | 123456   |
| Viewer | viewer@email.com | 123456   |

---

# Recomendações finais

É recomendado acessar primeiro com um usuário **editor** ou **admin**, pois o usuário **viewer** apenas visualiza Pokémons que já foram importados para o banco de dados.