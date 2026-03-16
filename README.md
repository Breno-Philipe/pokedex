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

`Pokemon ↔ Types (Many to Many)`

Também existe relacionamento para favoritos:

`User ↔ Pokemon (Favorites)`

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

`storage/logs/laravel.log`

---

## Requisitos

- PHP 8.2+
- Composer
- MySQL

---

# Instalação do projeto
Caso não possua instalado os requisitos, recomendo o uso da ferramenta xampp para instalar o PHP e o MySQL.
O XAMPP fornece o ambiente local com **Apache**, **PHP** e **MySQL** necessários para executar o projeto.

# Windows

`Baixar o XAMPP`

Acesse o site oficial:

https://www.apachefriends.org/index.html

Baixe a versão para **Windows**.

`Instalar`

1. Execute o instalador (`xampp-installer.exe`)
2. Clique em **Next**
3. Selecione os componentes:

```
✔ Apache
✔ MySQL
✔ PHP
```

(os demais são opcionais)

4. Escolha o diretório de instalação (recomendado):

```
C:\xampp
```

5. Finalize a instalação.

`Iniciar serviços`

Abra o **XAMPP Control Panel** e clique em:

```
Start → Apache
Start → MySQL
```

`Testar instalação`

Abra no navegador:

```
http://localhost
```

Se aparecer a página do XAMPP, está funcionando ✅

# Linux

`Baixar o XAMPP`

```bash
wget https://www.apachefriends.org/xampp-files/latest/xampp-linux-x64-installer.run
```

`Dar permissão de execução`

```bash
chmod +x xampp-linux-x64-installer.run
```

`Executar instalador`

```bash
sudo ./xampp-linux-x64-installer.run
```

Siga os passos do instalador gráfico.

`Iniciar serviços`

```bash
sudo /opt/lampp/lampp start
```

`Testar`

Abra no navegador:

```
http://localhost
```

---

# macOS

`Baixar o XAMPP`

Acesse:

https://www.apachefriends.org/index.html

Baixe a versão para **macOS**.

---

`Instalar`

1. Abra o arquivo `.dmg`
2. Arraste o **XAMPP** para a pasta **Applications**
3. Abra o aplicativo XAMPP.

---

`Iniciar serviços`

Na aba **Manage Servers**, inicie:

```
Start → Apache Web Server
Start → MySQL Database
```


`Testar`

Abra:

```
http://localhost
```

# Diretório dos projetos

Coloque o projeto na pasta:

```
Windows: C:\xampp\htdocs
Linux:   /opt/lampp/htdocs
macOS:   /Applications/XAMPP/htdocs
```

Exemplo:

```
htdocs/meu-projeto
```

Acesse:

```
http://localhost/meu-projeto
```

---

# Problemas comuns

- Porta 80 ocupada → fechar IIS/Skype/Apache antigo
- Firewall bloqueando Apache
- Executar XAMPP como administrador (Windows)

Após instalar o xampp será necessário configurar a variável de ambiente **PATH**:

# Windows

`Localize a pasta do PHP`

Exemplos comuns:

```bash
C:\php
C:\xampp\php
C:\laragon\bin\php\php-8.x.x
```

`Adicionar ao PATH`

1. Abra o menu iniciar e pesquise por:
   ```bash
   variáveis de ambiente
   ```
2. Clique em **Editar variáveis de ambiente do sistema**
3. Clique em **Variáveis de Ambiente**
4. Em **Variáveis do sistema**, selecione **Path**
5. Clique em **Editar**
6. Clique em **Novo**
7. Adicione o caminho da pasta do PHP, por exemplo:
   ```
   C:\xampp\php
   ```
8. Clique em **OK** em todas as janelas.

`Reinicie o terminal`

Feche e abra o terminal novamente.

`Verificar`

```bash
php -v
```

---

# Linux

`Verificar instalação do PHP`

```bash
php -v
```

Se não estiver instalado:

```bash
sudo apt update
sudo apt install php
```

`Descobrir caminho do PHP`

```bash
which php
```

Exemplo de retorno:

```
/usr/bin/php
```

`Adicionar ao PATH (caso necessário)`

Abra o arquivo:

```bash
nano ~/.bashrc
```

Adicione no final:

```bash
export PATH="$PATH:/usr/bin"
```

Aplicar alterações:

```bash
source ~/.bashrc
```

`Verificar`

```bash
php -v
```

---

# macOS

`Instalar PHP (via Homebrew)`

```bash
brew install php
```

`Adicionar ao PATH`

```bash
echo 'export PATH="/opt/homebrew/bin:$PATH"' >> ~/.zshrc
source ~/.zshrc
```

`Verificar`

```bash
php -v
```

---

Após configurar o php você deve instalar o composer.
O Composer é o gerenciador de dependências do PHP utilizado neste projeto.

#Windows

`Baixar o instalador`

Acesse:

https://getcomposer.org/download/

Baixe o **Composer-Setup.exe**.

`Executar instalação`

1. Execute o instalador.
2. Quando solicitado, selecione o caminho do PHP, por exemplo:

```bash
C:\xampp\php\php.exe
```

3. Continue a instalação normalmente.

O instalador adicionará o Composer ao PATH automaticamente.

`Verificar instalação`

Abra um novo terminal e execute:

```bash
composer -V
```

# Linux

`Baixar o Composer`

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
```

`Instalar`

```bash
php composer-setup.php
```

`Mover para uso global`

```bash
sudo mv composer.phar /usr/local/bin/composer
```

`Verificar instalação`

```bash
composer -V
```

# macOS

`Instalar via Homebrew (recomendado)`

```bash
brew install composer
```

`Verificar instalação`

```bash
composer -V
```

---

## ✅ Testar funcionamento

Dentro do projeto execute:

```bash
composer install
```

Isso instalará todas as dependências do projeto.

---

## ⚠️ Problemas comuns

Se o comando não for reconhecido:

- Reinicie o terminal
- Verifique se o PHP está no PATH
- Execute:

```bash
composer diagnose
```


---

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