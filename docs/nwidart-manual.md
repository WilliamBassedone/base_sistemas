# Manual do `nwidart/laravel-modules`

## O que e o `nwidart`

`nwidart/laravel-modules` e uma biblioteca para organizar um projeto Laravel em modulos.

Em vez de colocar tudo apenas em:

- `app/`
- `routes/`
- `resources/`

voce pode ter blocos separados como:

- `Modules/Blog`
- `Modules/Panel`
- `Modules/Catalog`

Cada modulo pode ter:

- controllers
- rotas
- views
- config
- migrations
- seeders
- testes

## O que a biblioteca faz no dia a dia

O papel principal dela e:

- criar a estrutura de um modulo novo
- registrar modulos existentes
- ajudar o Laravel a encontrar rotas, views e arquivos do modulo

Exemplo de comando:

```bash
php artisan module:make Catalog
```

Esse comando cria a pasta `Modules/Catalog` com varios arquivos iniciais.

## Onde essa biblioteca aparece no projeto

### Dependencia Composer

A biblioteca esta instalada como dependencia do projeto em:

- `composer.json`

### Configuracao publicada

O arquivo mais importante dela no projeto e:

- `config/modules.php`

Esse arquivo e a configuracao do `nwidart` dentro da sua aplicacao.

Ele nao fica em `vendor/` porque o objetivo e justamente permitir customizacao no projeto.

## O que e `config/modules.php`

Pense nesse arquivo como o painel de controle da biblioteca.

E nele que voce define, por exemplo:

- onde os modulos vao ser criados
- quais pastas e arquivos um novo modulo deve gerar
- quais stubs devem ser usados
- quais providers extras devem ou nao ser criados

No projeto, esse arquivo foi adaptado para o padrao arquitetural local.

Arquivo:

- `config/modules.php`

## O que e `stub`

`stub` e um molde.

Quando o `nwidart` cria um arquivo novo, ele nao escreve o codigo “do zero”. Ele copia um modelo e troca algumas palavras.

Exemplo:

- nome do modulo
- namespace
- nome da classe

Esses modelos sao os `stubs`.

### Exemplo simples

Quando voce roda:

```bash
php artisan module:make Catalog
```

a biblioteca usa um stub para gerar o provider do modulo.

No seu projeto, esse stub customizado esta aqui:

- `stubs/module/scaffold/provider.stub`

Conteudo simplificado:

```php
class $CLASS$ extends ModuleServiceProvider
{
    protected string $moduleName = '$MODULE$';

    protected string $moduleNameLower = '$LOWER_NAME$';
}
```

As partes como `$CLASS$` e `$MODULE$` sao substituidas pelo `nwidart` no momento da geracao.

## O que e `provider`

`provider` e uma classe do Laravel usada para registrar comportamento da aplicacao.

De forma simples, um provider pode dizer ao Laravel:

- quais rotas carregar
- quais views carregar
- quais configuracoes registrar
- quais servicos inicializar

No contexto dos modulos, o provider principal de um modulo e o arquivo que “liga” o modulo no sistema.

Exemplo:

- `Modules/Catalog/app/Providers/CatalogServiceProvider.php`

## Como o projeto usa provider hoje

O projeto tem uma base compartilhada:

- `app/Support/Modules/ModuleServiceProvider.php`

Essa classe centraliza a parte estrutural que antes ficava repetida em todos os modulos.

Ela cuida de:

- traducoes
- configs
- views
- rotas web
- rotas api
- migrations

Assim, cada modulo fica com um provider principal pequeno.

Exemplo:

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

## O que foi alterado no `nwidart` dentro deste projeto

Nao foi alterado o codigo da biblioteca em `vendor/`.

As mudancas foram feitas no modo como o projeto usa a biblioteca.

### 1. Configuracao

Foi customizado:

- `config/modules.php`

Principais ajustes:

- `stubs.enabled = true`
- `stubs.path = base_path('stubs/module')`
- `event-provider = false`
- `route-provider = false`
- `vite.config.js` nao e mais gerado
- `package.json` nao e mais gerado

### 2. Stub local

Foi criado:

- `stubs/module/scaffold/provider.stub`

Esse stub faz o provider principal do modulo nascer no padrao certo do projeto.

### 3. Provider base local

Foi criado:

- `app/Support/Modules/ModuleServiceProvider.php`

Esse arquivo nao faz parte do `nwidart`.
Ele e uma adaptacao da sua aplicacao para evitar duplicacao nos modulos.

## O que muda quando voce cria um modulo novo

Antes, um modulo podia vir com:

- `NomeServiceProvider`
- `EventServiceProvider`
- `RouteServiceProvider`
- `vite.config.js`
- `package.json`

Agora, a intencao do projeto e gerar apenas o necessario.

O esperado para um modulo novo e:

- provider principal no padrao `ModuleServiceProvider`
- sem `EventServiceProvider`
- sem `RouteServiceProvider`
- sem `vite.config.js`
- sem `package.json`

## Fluxo de criacao de modulo

### 1. Criar o modulo

```bash
./vendor/bin/sail artisan module:make Inventory
```

### 2. Conferir o provider principal

Arquivo esperado:

- `Modules/Inventory/app/Providers/InventoryServiceProvider.php`

Ele deve estender:

- `App\Support\Modules\ModuleServiceProvider`

### 3. Conferir se arquivos extras indevidos nao foram criados

Verifique se nao existem:

- `Modules/Inventory/app/Providers/EventServiceProvider.php`
- `Modules/Inventory/app/Providers/RouteServiceProvider.php`
- `Modules/Inventory/vite.config.js`
- `Modules/Inventory/package.json`

### 4. Criar uma rota simples de teste

Arquivo:

- `Modules/Inventory/routes/web.php`

Exemplo:

```php
<?php

use Illuminate\Support\Facades\Route;

Route::get('/inventory', fn () => 'Inventory ok')->name('inventory.index');
```

### 5. Validar

```bash
./vendor/bin/sail artisan route:list --name=inventory
```

Se a rota aparecer, o modulo esta sendo carregado corretamente.

## O que e `module.json`

Cada modulo tem um arquivo:

- `Modules/<Modulo>/module.json`

Ele funciona como um manifesto simples do modulo.

Normalmente guarda:

- nome
- alias
- provider principal

Exemplo:

```json
{
    "name": "Catalog",
    "alias": "catalog",
    "providers": [
        "Modules\\Catalog\\Providers\\CatalogServiceProvider"
    ]
}
```

## O que o `ModuleServiceProvider` faz em linguagem simples

Arquivo:

- `app/Support/Modules/ModuleServiceProvider.php`

Explicando por partes:

- `registerTranslations()`:
  procura traducoes do modulo

- `registerConfig()`:
  carrega arquivos de config do modulo

- `registerViews()`:
  registra as views do modulo

- `registerRoutes()`:
  carrega `routes/web.php` e `routes/api.php`

- `loadMigrationsFrom(...)`:
  registra migrations do modulo

Ou seja, ele e o lugar central que faz o modulo “funcionar” dentro do Laravel.

## Diferenca entre codigo da biblioteca e codigo do projeto

### Biblioteca

Fica em:

- `vendor/nwidart/laravel-modules`

Esse e o codigo original do pacote.

### Projeto

Fica em:

- `config/modules.php`
- `stubs/module/...`
- `app/Support/Modules/...`
- `Modules/...`

Esses sao os pontos corretos para customizar o comportamento do pacote no seu sistema.

## Regra importante

Nao editar `vendor/` para resolver comportamento de modulo.

Motivos:

- atualizacoes do Composer podem sobrescrever tudo
- dificulta manutencao
- dificulta upgrade

O caminho correto e:

- configurar em `config/modules.php`
- sobrescrever stubs localmente
- adaptar a aplicacao no proprio codigo dela

## O que consultar quando tiver duvida

Se voce ficar em duvida sobre o `nwidart`, consulte nesta ordem:

1. `config/modules.php`
2. `stubs/module/`
3. `app/Support/Modules/ModuleServiceProvider.php`
4. `Modules/<Modulo>/module.json`
5. `Modules/<Modulo>/app/Providers/<Modulo>ServiceProvider.php`

## Resumo final

- `nwidart` cria e organiza modulos
- `config/modules.php` controla como ele trabalha no projeto
- `stub` e o molde dos arquivos gerados
- `provider` e a classe que registra o modulo no Laravel
- `ModuleServiceProvider` e a base compartilhada do projeto
- o projeto usa o `nwidart` de forma customizada, sem alterar `vendor/`
