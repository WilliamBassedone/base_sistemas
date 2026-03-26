# Manual de Arquitetura

## Objetivo

Este projeto usa Laravel 12 com organizacao modular baseada em `nwidart/laravel-modules`.

O objetivo desta arquitetura e:

- manter os modulos organizados por dominio
- reduzir acoplamento com internals do Laravel
- evitar multiplas pipelines de frontend no mesmo projeto
- facilitar upgrades futuros de Laravel e PHP

## Principios

- Cada modulo deve conter apenas o codigo do seu dominio: controllers, rotas, views, migrations, config e assets locais quando necessario.
- A infraestrutura de boot do modulo deve ficar centralizada.
- O frontend deve ser compilado por uma unica pipeline Vite na raiz do projeto.
- Sempre que uma regra estrutural se repetir em mais de um modulo, ela deve ser movida para uma base compartilhada.

## Estrutura Geral

### Aplicacao raiz

- `app/`
- `bootstrap/`
- `config/`
- `resources/`
- `routes/`
- `tests/`

### Modulos

Cada modulo fica em `Modules/<NomeDoModulo>/`.

Exemplo:

- `Modules/Blog/app/Http/Controllers`
- `Modules/Blog/routes`
- `Modules/Blog/resources/views`
- `Modules/Blog/database/migrations`
- `Modules/Blog/config`

## Provider base de modulos

O ponto central da arquitetura modular e o arquivo:

- `app/Support/Modules/ModuleServiceProvider.php`

Esse provider base concentra:

- registro de traducoes
- merge de configuracoes
- registro de views
- registro de rotas web e api
- carga de migrations

Com isso, cada modulo deixa de repetir `RouteServiceProvider`, `EventServiceProvider` e boa parte da logica de boot.

### Regra

O provider principal de cada modulo deve apenas declarar:

- nome do modulo
- alias em minusculo

Exemplo esperado:

```php
<?php

namespace Modules\Blog\Providers;

use App\Support\Modules\ModuleServiceProvider;

class BlogServiceProvider extends ModuleServiceProvider
{
    protected string $moduleName = 'Blog';

    protected string $moduleNameLower = 'blog';
}
```

### O que nao fazer

- nao criar `RouteServiceProvider` por modulo
- nao criar `EventServiceProvider` vazio por modulo
- nao duplicar logica de rotas, views e config em cada modulo
- nao adicionar boot customizado no modulo sem necessidade real

Se um modulo precisar de comportamento especial, a preferencia e:

1. avaliar se a regra serve para todos os modulos e deve subir para o provider base
2. se for especifica, implementar no provider do modulo com o minimo de codigo possivel

## Registro de rotas

As rotas de modulo sao carregadas pelo provider base.

Convencao:

- `Modules/<Modulo>/routes/web.php`
- `Modules/<Modulo>/routes/api.php`

Regras atuais:

- rotas web entram com middleware `web`
- rotas api entram com prefixo `api` e middleware `api`

Exemplo:

- `Modules/Blog/routes/web.php` gera rotas como `/blog/turbo`
- `Modules/Blog/routes/api.php` gera rotas como `/api/v1/blogs`

## Configuracao do nwidart

O comportamento de geracao de modulos fica em:

- `config/modules.php`
- `stubs/module/scaffold/provider.stub`

### Decisoes atuais

- `stubs.enabled` ligado para permitir override local de stubs
- `stubs.path` apontando para `stubs/module`
- `route-provider` desabilitado
- `event-provider` desabilitado
- `vite.config.js` nao faz parte dos stubs do modulo
- `package.json` nao faz parte dos stubs do modulo

Isso existe para evitar que cada modulo recrie infraestrutura de frontend e boot propria.

### Stub customizado do provider principal

O comando `module:make` usa um stub local em:

- `stubs/module/scaffold/provider.stub`

Esse stub faz o provider principal nascer diretamente no padrao do projeto, estendendo:

- `App\Support\Modules\ModuleServiceProvider`

Com isso, novos modulos nao exigem mais ajuste manual no provider logo apos a criacao.

## Frontend

### Diretriz

Existe uma unica pipeline de frontend:

- `vite.config.js` da raiz
- `package.json` da raiz

Os layouts de modulo devem consumir o bundle principal por `@vite(...)`.

Exemplo:

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

### O que nao fazer

- nao criar `vite.config.js` por modulo
- nao criar `package.json` por modulo
- nao criar versoes diferentes de Vite em modulos diferentes
- nao manter pipelines paralelas sem necessidade forte

### Quando um modulo precisar de JS ou CSS proprio

O asset continua pertencendo ao modulo, por exemplo:

- `Modules/Blog/resources/assets/js/app.js`
- `Modules/Blog/resources/assets/sass/app.scss`

Mas a compilacao continua sendo responsabilidade da stack da raiz.

Se for necessario usar esses arquivos, a equipe deve decidir entre:

- importar o asset do modulo a partir de um entrypoint da raiz
- adicionar o asset do modulo como input da configuracao Vite da raiz

A regra e simples: o asset pode morar no modulo, mas a pipeline nao.

## Organizacao da interface administrativa

Quando a aplicacao tiver uma area autenticada com sidebar, topbar, breadcrumbs e frame principal do Turbo, essa estrutura deve ser tratada como um shell da aplicacao, nao como parte de um dominio de negocio.

### Separacao recomendada

- `Modules/Panel`: shell da area autenticada
- `Modules/UI`: componentes Blade compartilhados
- `Modules/<ModuloDeNegocio>`: funcionalidades como `Group`, `Catalog`, `Inventory`, `Blog`

### Papel de cada modulo

#### Panel

O modulo `Panel` deve concentrar apenas a casca da area autenticada:

- layout base do painel
- sidebar
- topbar
- slots de titulo, acoes e breadcrumbs
- `turbo-frame` principal para navegacao interna
- rota inicial `/dashboard`
- montagem do menu com links para os modulos

Exemplos de view que pertencem ao `Panel`:

- `panel::app`
- `panel::partials.sidebar`
- `panel::partials.topbar`
- `panel::partials.flash`

#### UI

O modulo `UI` nao deve representar uma funcionalidade de negocio.
Ele existe para reunir componentes reutilizaveis de interface, por exemplo:

- botoes
- tabelas
- badges
- cards
- modais
- componentes de formulario

Exemplos esperados:

- `ui::components.button`
- `ui::components.table`
- `ui::components.empty-state`

#### Modulos de negocio

Cada modulo de negocio deve cuidar apenas do proprio fluxo:

- rotas do dominio
- controllers
- regras de aplicacao
- models e queries
- views da funcionalidade

Esses modulos devem usar o shell do `Panel`, sem recriar layout completo.

Exemplo:

```blade
@extends('panel::app')

@section('title', 'Grupos')
@section('page_title', 'Grupos')

@section('content')
    {{-- tela do modulo --}}
@endsection
```

### Regra pratica

Se o codigo responde a pergunta "como o painel parece e navega?", ele pertence ao `Panel`.

Se responde "como um elemento visual reutilizavel funciona?", ele pertence ao `UI`.

Se responde "como o cadastro, listagem ou fluxo de um dominio funciona?", ele pertence ao modulo de negocio.

### Sidebar e navegacao

A sidebar nao deve ficar espalhada entre modulos de negocio.

O ideal e que o `Panel` seja dono da estrutura de navegacao, enquanto cada modulo de negocio apenas expoe:

- rota
- label
- icone
- permissao necessaria

Isso pode ser resolvido mais adiante com uma configuracao central ou contrato compartilhado, mas a responsabilidade visual continua sendo do `Panel`.

### Turbo dentro da arquitetura

O Turbo nao muda a divisao de responsabilidades.

Ele apenas influencia como as telas sao renderizadas:

- o layout principal do `Panel` pode conter um `turbo-frame` como area de conteudo
- os modulos de negocio retornam views completas ou parciais para esse frame
- formularios e links internos podem usar atributos do Turbo sem mover layout para dentro do modulo

Em resumo:

- `Panel` define o frame e a navegacao
- modulo de negocio define o conteudo carregado nesse frame

### O que evitar

- nao duplicar `master.blade.php` em todo modulo
- nao colocar sidebar dentro de `Group`, `Catalog` ou `Inventory`
- nao transformar `UI` em modulo de regras de negocio
- nao deixar cada modulo decidir sua propria estrutura de painel

### Convencao recomendada para este projeto

Considerando o estado atual do repositorio, a direcao recomendada e:

- manter `Panel` como modulo de shell administrativo
- manter `UI` como biblioteca de componentes Blade compartilhados
- manter `Group`, `Catalog`, `Inventory` e similares como modulos de dominio
- fazer as telas desses modulos herdarem `panel::app`
- reduzir progressivamente layouts duplicados em `resources/views/components/layouts/master.blade.php` dentro dos modulos de negocio

## Estado atual implementado

No estado atual deste projeto, a organizacao esperada da area administrativa ja esta aplicada no codigo.

### Shell administrativo

O shell do painel fica no modulo `Panel`.

Arquivos centrais:

- `Modules/Panel/resources/views/app.blade.php`
- `Modules/Panel/resources/views/partials/sidebar.blade.php`
- `Modules/Panel/resources/views/partials/topbar.blade.php`
- `Modules/Panel/resources/views/partials/page.blade.php`
- `Modules/Panel/config/config.php`

Responsabilidades atuais do `Panel`:

- renderizar o HTML completo da area autenticada
- conter a sidebar e a topbar
- definir o `turbo-frame` principal com id `main`
- centralizar o menu administrativo em configuracao
- atualizar titulo de pagina e estrutura base do painel

### Navegacao via Turbo

O layout `panel::app` possui duas formas de resposta:

- requisicao normal: retorna o shell completo
- requisicao com header `Turbo-Frame: main`: retorna apenas o conteudo do frame `main`

Essa regra existe para evitar que o layout inteiro seja renderizado dentro do frame durante navegacao Turbo.

Fluxo esperado:

1. o usuario acessa `/dashboard` e recebe o shell completo
2. a sidebar aponta links para `data-turbo-frame="main"`
3. o modulo de negocio responde usando `@extends('panel::app')`
4. se a requisicao vier do frame, o layout devolve apenas `<turbo-frame id="main">...</turbo-frame>`

### Regra obrigatoria para links internos do admin

Sempre que um link da area administrativa precisar trocar apenas o conteudo central, usar:

```blade
data-turbo-frame="main" data-turbo-action="advance"
```

Isso vale especialmente para:

- links da sidebar
- botoes de voltar
- botoes de cancelar
- links de listagem para formularios internos

### Regra obrigatoria para views do admin

Telas administrativas devem estender:

```blade
@extends('panel::app')
```

E declarar pelo menos:

```blade
@section('title', 'Titulo da Aba')
@section('page_title', 'Titulo da Tela')
@section('content')
```

O `title` e usado para o `<title>` do navegador.

O `page_title` e usado no topo do painel.

### Regra obrigatoria para menu

Itens da sidebar nao devem ser hardcoded em views de modulos de negocio.

O menu administrativo deve ser alterado em:

- `Modules/Panel/config/config.php`

Se um novo modulo administrativo for criado, o agente deve:

1. criar o modulo de negocio normalmente
2. adicionar suas rotas e views no proprio modulo
3. fazer suas views herdarem `panel::app`
4. registrar sua entrada de menu no `Panel`

### Regra obrigatoria para componentes compartilhados

Componentes visuais reutilizaveis devem ficar no modulo `UI`.

Exemplos:

- botoes
- badges
- tabelas
- cards
- empty states

O modulo `UI` nao deve receber regras de negocio.

### O que um agente futuro nao deve fazer

- nao recriar sidebar dentro de `Group`, `Catalog` ou outro modulo de negocio
- nao criar novo `master.blade.php` por modulo para area administrativa
- nao montar um segundo shell administrativo fora do `Panel`
- nao usar `data-turbo="false"` em links internos do painel sem necessidade real
- nao mover componentes compartilhados para modulos de dominio

### O que um agente futuro deve fazer

- usar `Panel` como dono do shell administrativo
- usar `UI` como biblioteca de componentes compartilhados
- manter modulos de negocio focados em dominio
- preservar a navegacao interna via `Turbo Frame`
- se mudar a arquitetura do painel, atualizar este manual no mesmo commit

### Validacao minima esperada

Mudancas no shell administrativo ou navegacao Turbo devem ser cobertas por testes de feature.

Referencia atual:

- `tests/Feature/PanelTurboShellTest.php`

Esse teste garante hoje:

- renderizacao do shell completo em requisicao normal
- renderizacao apenas do frame `main` em requisicao Turbo
- carregamento correto de modulo administrativo dentro do frame

## Criacao de novos modulos

### Passo 1

Criar o modulo com o comando do `nwidart`.

Exemplo:

```bash
php artisan module:make Catalog
```

### Passo 2

Verificar o provider principal gerado em:

- `Modules/Catalog/app/Providers/CatalogServiceProvider.php`

Ele deve nascer automaticamente extendendo o provider base:

```php
<?php

namespace Modules\Catalog\Providers;

use App\Support\Modules\ModuleServiceProvider;

class CatalogServiceProvider extends ModuleServiceProvider
{
    protected string $moduleName = 'Catalog';

    protected string $moduleNameLower = 'catalog';
}
```

### Passo 3

Confirmar que o `module.json` aponta para esse provider.

Exemplo:

```json
{
    "providers": [
        "Modules\\Catalog\\Providers\\CatalogServiceProvider"
    ]
}
```

### Passo 4

Criar apenas o que o modulo realmente precisa:

- rotas
- controllers
- views
- config
- migrations
- testes

### Passo 5

Se houver assets do modulo, manter os arquivos no proprio modulo, mas sem criar pipeline separada.

## Estado atual da geracao de modulos

Atualmente o projeto:

- impede a geracao de `RouteServiceProvider` por modulo
- impede a geracao de `EventServiceProvider` por modulo
- impede a geracao de `vite.config.js` por modulo
- impede a geracao de `package.json` por modulo
- gera o provider principal no padrao de `ModuleServiceProvider`

Ainda assim, todo modulo novo deve passar por uma revisao rapida para confirmar que o pacote respeitou os stubs locais.

## Testes minimos esperados

Todo modulo novo deve adicionar pelo menos:

- um teste de smoke para a rota principal
- um teste para confirmar que as rotas do modulo foram registradas
- testes dos fluxos publicos mais importantes

Referencia atual:

- `tests/Feature/ModuleRegistrationTest.php`

## Regras de manutencao

- upgrades de Laravel devem ser feitos mantendo o provider base como unico ponto de adaptacao estrutural
- novos modulos nao devem introduzir infraestrutura paralela
- qualquer excecao deve ser documentada neste manual
- se uma decisao arquitetural mudar, atualizar este arquivo no mesmo commit

## Resumo operacional

- Modulos organizam dominio, nao infraestrutura duplicada.
- O boot estrutural dos modulos mora em `ModuleServiceProvider`.
- O frontend e centralizado na raiz.
- Novos modulos devem seguir o provider simplificado.
- Toda quebra dessa regra aumenta o custo de upgrade futuro.
