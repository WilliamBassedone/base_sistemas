# CMS Modular com Laravel 12

Aplicacao Laravel 12 organizada em modulos com `nwidart/laravel-modules`, frontend centralizado com Vite/Tailwind e navegacao da area administrativa baseada em Turbo.

O projeto serve como base para um CMS/painel administrativo modular, com shell reutilizavel em `Panel`, componentes compartilhados em `UI` e dominios separados em `Modules/<NomeDoModulo>`.

## Stack

- PHP 8.2+
- Laravel 12
- `nwidart/laravel-modules`
- Hotwire Turbo (`hotwired-laravel/turbo-laravel` e `@hotwired/turbo`)
- Vite 7
- Tailwind CSS 4
- Jodit Editor
- PHPUnit 11

## Visao Geral da Arquitetura

### Monolito modular

Cada dominio fica em `Modules/<Modulo>`, mantendo rotas, views, migrations, configuracoes e classes do proprio contexto.

Exemplos atuais:

- `Modules/Panel`: shell da area autenticada
- `Modules/UI`: componentes visuais compartilhados
- `Modules/Development`: telas de desenvolvimento/componentizacao
- `Modules/Group`: configuracoes de grupos
- `Modules/Blog`, `Modules/Catalog`, `Modules/Content`, `Modules/Inventory`, `Modules/Novo`, `Modules/Teste`

### Provider base para modulos

O arquivo [`app/Support/Modules/ModuleServiceProvider.php`](/home/wbassedone/projetos/interativacom/cms/app/Support/Modules/ModuleServiceProvider.php) centraliza o boot estrutural dos modulos:

- traducoes
- merge de configuracao
- registro de views
- registro de rotas web e api
- carga de migrations

Isso reduz duplicacao e evita criar `RouteServiceProvider` e `EventServiceProvider` por modulo sem necessidade.

### Frontend unificado

Mesmo com a organizacao por modulos, a pipeline de frontend fica na raiz:

- [`vite.config.js`](/home/wbassedone/projetos/interativacom/cms/vite.config.js)
- [`package.json`](/home/wbassedone/projetos/interativacom/cms/package.json)

Os assets podem morar dentro dos modulos, mas a compilacao continua centralizada no Vite da aplicacao.

## Estrutura do Projeto

```text
app/
  Http/Controllers
  Services
  Support/Modules
config/
docs/
Modules/
  Panel/
  UI/
  Development/
  Group/
  ...
resources/
  css/
  js/
  views/
routes/
tests/
stubs/
```

## Requisitos

- PHP 8.2 ou superior
- Composer
- Node.js 20+ com npm
- Extensoes PHP compativeis com Laravel 12

## Setup Local

1. Instale dependencias do backend e frontend:

```bash
composer install
npm install
```

2. Crie o arquivo de ambiente:

```bash
cp .env.example .env
php artisan key:generate
```

3. O projeto usa SQLite por padrao e o arquivo [`database/database.sqlite`](/home/wbassedone/projetos/interativacom/cms/database/database.sqlite) ja existe no repositorio. Rode as migrations:

```bash
php artisan migrate
```

4. Suba a aplicacao e o frontend:

```bash
composer run dev
```

Esse comando sobe em paralelo:

- servidor Laravel
- listener da fila
- `pail` para logs
- Vite em modo desenvolvimento

### Setup rapido

Existe tambem um script Composer que automatiza a configuracao inicial:

```bash
composer run setup
```

## Variaveis de Ambiente

O `.env.example` cobre o setup padrao com:

- `DB_CONNECTION=sqlite`
- `SESSION_DRIVER=database`
- `CACHE_STORE=database`
- `QUEUE_CONNECTION=database`

Para os endpoints de IA, adicione no `.env`:

```env
GEMINI_API_KEY=
GEMINI_MODEL=gemini-1.5-flash
```

As categorias de triagem estao em [`config/ai.php`](/home/wbassedone/projetos/interativacom/cms/config/ai.php) e podem ser ajustadas conforme o dominio do projeto.

## Principais Rotas

### Aplicacao base

- `/`: home
- `/sobre`: pagina institucional
- `/audio/gravador`: gravador para envio de audio
- `/gemini/models`: lista modelos Gemini configurados
- `POST /audio/triage`: envia um audio para transcricao e classificacao

### Area modular

- `/dashboard`: shell do painel
- `/configuracoes`: modulo `Teste`
- `/configuracoes/grupos`: modulo `Group`
- `/desenvolvimento/componentes`: modulo `Development`
- `/blog/turbo`: fluxo Turbo do modulo `Blog`
- `/novo/turbo`: fluxo Turbo do modulo `Novo`

As rotas de API dos modulos sao carregadas automaticamente com prefixo `/api`, normalmente em `/api/v1/...`.

## Modulos

Os modulos sao gerados com `nwidart/laravel-modules`, mas com convencoes locais definidas em [`config/modules.php`](/home/wbassedone/projetos/interativacom/cms/config/modules.php) e no stub [`stubs/module/scaffold/provider.stub`](/home/wbassedone/projetos/interativacom/cms/stubs/module/scaffold/provider.stub).

### Criar um novo modulo

```bash
php artisan module:make Inventory
```

O esperado neste projeto e que o modulo:

- estenda `App\Support\Modules\ModuleServiceProvider`
- nao gere `RouteServiceProvider`
- nao gere `EventServiceProvider`
- nao gere `vite.config.js`
- nao gere `package.json`

## Testes e Qualidade

Executar a suite:

```bash
composer test
```

Ou diretamente:

```bash
php artisan test
```

Cobertura atual relevante:

- registro automatico de rotas de modulos
- renderizacao do shell do painel com Turbo
- resposta parcial via `Turbo-Frame`

## Documentacao Interna

- [Manual de Arquitetura](docs/architecture-manual.md)
- [Manual do Nwidart](docs/nwidart-manual.md)
- [Manual de Comandos Laravel](docs/laravel-commands-manual.md)
- [Manual de Comandos Linux](docs/linux-commands-manual.md)

## Desenvolvedor

William Pereira Bassedone

## Observacoes

- O projeto compila assets de modulos pela pipeline raiz do Vite.
- A area administrativa foi desenhada para trabalhar com navegacao parcial via Turbo.
- Existem integracoes experimentais com Gemini para listagem de modelos e triagem de audio.
