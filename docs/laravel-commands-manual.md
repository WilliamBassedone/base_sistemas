# Manual de Comandos Laravel

## Objetivo

Este documento reune comandos Laravel e comandos relacionados ao ecossistema do projeto.

A ideia e manter um guia rapido para tarefas comuns de desenvolvimento.

## Regra pratica

Quando o projeto estiver rodando com Sail, prefira:

```bash
./vendor/bin/sail artisan ...
```

Em vez de:

```bash
php artisan ...
```

Assim, o comando executa dentro do container configurado para a aplicacao.

## Comandos basicos

### Subir e parar ambiente

Subir containers:

```bash
./vendor/bin/sail up -d
```

Parar containers:

```bash
./vendor/bin/sail down
```

Subir reconstruindo imagem:

```bash
./vendor/bin/sail up -d --build
```

### Entrar no shell do container

```bash
./vendor/bin/sail shell
```

Ou executar shell direto no servico principal:

```bash
./vendor/bin/sail exec laravel.test bash
```

## Artisan geral

Ver lista de comandos:

```bash
./vendor/bin/sail artisan list
```

Ver ajuda de um comando:

```bash
./vendor/bin/sail artisan help migrate
```

Ver versao do Laravel:

```bash
./vendor/bin/sail artisan --version
```

## Banco de dados

Rodar migrations:

```bash
./vendor/bin/sail artisan migrate
```

Rodar migrations do zero:

```bash
./vendor/bin/sail artisan migrate:fresh
```

Rodar migrations e seeders:

```bash
./vendor/bin/sail artisan migrate --seed
```

Desfazer ultima migration:

```bash
./vendor/bin/sail artisan migrate:rollback
```

Desfazer tudo:

```bash
./vendor/bin/sail artisan migrate:reset
```

## Cache e limpeza

Limpar caches principais:

```bash
./vendor/bin/sail artisan optimize:clear
```

Limpar cache de configuracao:

```bash
./vendor/bin/sail artisan config:clear
```

Limpar cache de rotas:

```bash
./vendor/bin/sail artisan route:clear
```

Limpar cache de views:

```bash
./vendor/bin/sail artisan view:clear
```

Gerar cache de configuracao:

```bash
./vendor/bin/sail artisan config:cache
```

Gerar cache de rotas:

```bash
./vendor/bin/sail artisan route:cache
```

## Rotas

Listar rotas:

```bash
./vendor/bin/sail artisan route:list
```

Filtrar rotas por nome:

```bash
./vendor/bin/sail artisan route:list --name=groups
```

Filtrar rotas por caminho:

```bash
./vendor/bin/sail artisan route:list --path=configuracoes
```

## Modulos com `nwidart`

Criar modulo novo:

```bash
./vendor/bin/sail artisan module:make Component
```

Criar model dentro do modulo com arquivos auxiliares:

```bash
./vendor/bin/sail artisan module:make-model Component Component -mcrR
```

Criar controller em modulo:

```bash
./vendor/bin/sail artisan module:make-controller ComponentController Component
```

Criar migration em modulo:

```bash
./vendor/bin/sail artisan module:make-migration create_components_table Component
```

Criar view em modulo:

```bash
./vendor/bin/sail artisan module:make-view components/index Component
```

Criar seeder em modulo:

```bash
./vendor/bin/sail artisan module:make-seed ComponentSeeder Component
```

Rodar seeders de modulo:

```bash
./vendor/bin/sail artisan module:seed Component
```

Ver ajuda do comando de modulo:

```bash
./vendor/bin/sail artisan help module:make
```

## Geracao de classes Laravel

Criar controller:

```bash
./vendor/bin/sail artisan make:controller ExampleController
```

Criar model com migration, controller e factory:

```bash
./vendor/bin/sail artisan make:model Example -mcf
```

Criar request:

```bash
./vendor/bin/sail artisan make:request StoreExampleRequest
```

Criar seeder:

```bash
./vendor/bin/sail artisan make:seeder ExampleSeeder
```

Criar command:

```bash
./vendor/bin/sail artisan make:command SyncExamples
```

## Testes

Rodar todos os testes:

```bash
./vendor/bin/sail artisan test
```

Rodar um arquivo de teste:

```bash
./vendor/bin/sail artisan test tests/Feature/ExampleTest.php
```

Rodar filtrando por metodo:

```bash
./vendor/bin/sail artisan test --filter=test_example
```

## Tinker

Abrir console interativo do Laravel:

```bash
./vendor/bin/sail artisan tinker
```

## Filas e jobs

Executar worker:

```bash
./vendor/bin/sail artisan queue:work
```

Executar em modo listen:

```bash
./vendor/bin/sail artisan queue:listen --tries=1
```

## Logs

Abrir visualizacao de logs com Pail:

```bash
./vendor/bin/sail artisan pail
```

Pail sem timeout:

```bash
./vendor/bin/sail artisan pail --timeout=0
```

## Assets e frontend

Instalar dependencias JS:

```bash
npm install
```

Subir Vite em desenvolvimento:

```bash
npm run dev
```

Gerar build de producao:

```bash
npm run build
```

## Composer

Instalar dependencias PHP:

```bash
composer install
```

Atualizar autoload:

```bash
composer dump-autoload
```

Rodar script de desenvolvimento do projeto:

```bash
composer run dev
```

## Exemplos de fluxo

### Criar um modulo novo

```bash
./vendor/bin/sail artisan module:make Component
./vendor/bin/sail artisan module:make-model Component Component -mcrR
./vendor/bin/sail artisan module:make-view components/index Component
./vendor/bin/sail artisan module:make-view components/create Component
./vendor/bin/sail artisan module:make-view components/edit Component
./vendor/bin/sail artisan migrate
```

### Corrigir cache apos mudar rotas, configs ou views

```bash
./vendor/bin/sail artisan optimize:clear
```

### Verificar se uma rota existe

```bash
./vendor/bin/sail artisan route:list --name=components
```
