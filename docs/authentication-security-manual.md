# Manual de Autenticação e Segurança

Este documento descreve a implementação do primeiro fluxo de autenticação administrativa do CMS. A solução foi criada para funcionar com a arquitetura atual do projeto, que usa Laravel, Nwidart Modules e Turbo Laravel para navegação com comportamento de SPA dentro do painel.

## Objetivo

O objetivo foi criar um login moderno em `/autenticacao`, usando um módulo separado para autenticação, protegendo o `/dashboard` e as principais áreas administrativas. Este primeiro login trabalha com um usuário root, porque o cadastro completo de usuários, grupos e permissões será desenvolvido depois.

A implementação também adicionou reforços de segurança para reduzir riscos comuns como CSRF, XSS, clickjacking, uso indevido de cache após logout, tentativas repetidas de login e requisições mutáveis vindas de origens inesperadas.

## Visão Geral do Fluxo

O fluxo principal ficou assim:

1. O usuário acessa `GET /autenticacao`.
2. O módulo `Authentication` renderiza a tela de login.
3. O formulário envia `POST /autenticacao`.
4. O `LoginRequest` valida e aplica limite de tentativas.
5. O Laravel Auth tenta autenticar com `email` e `password`.
6. Depois da autenticação, o sistema exige que o usuário esteja ativo e seja root.
7. A sessão é regenerada para evitar fixation.
8. O sistema grava `last_login_at` e `last_login_ip`.
9. O usuário é redirecionado para `/dashboard`.
10. Ao clicar em sair, o sistema executa `POST /autenticacao/sair`, invalida a sessão, regenera o token CSRF e volta para `/autenticacao`.

## Módulo Criado

Foi criado o módulo:

```text
Modules/Authentication
```

Ele segue o padrão dos demais módulos Nwidart do projeto.

### Registro do Módulo

Arquivo:

```text
Modules/Authentication/module.json
```

Esse arquivo registra o módulo e aponta para o provider:

```text
Modules\Authentication\Providers\AuthenticationServiceProvider
```

O módulo também foi ativado em:

```text
modules_statuses.json
```

### Provider

Arquivo:

```text
Modules/Authentication/app/Providers/AuthenticationServiceProvider.php
```

Esse provider estende:

```text
App\Support\Modules\ModuleServiceProvider
```

Com isso, o módulo ganha automaticamente o mesmo comportamento dos outros módulos:

- carregamento de rotas web;
- carregamento de views;
- carregamento de config;
- carregamento de migrations;
- namespace Blade do módulo.

## Rotas de Autenticação

Arquivo:

```text
Modules/Authentication/routes/web.php
```

Rotas criadas:

```php
Route::middleware('guest')->group(function () {
    Route::get('/autenticacao', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/autenticacao', [AuthenticatedSessionController::class, 'store'])->name('authentication.store');
});

Route::post('/autenticacao/sair', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
```

### GET /autenticacao

Renderiza a tela de login.

Nome da rota:

```text
login
```

Esse nome foi usado porque o middleware `auth` do Laravel procura uma rota chamada `login` para redirecionar usuários não autenticados.

### POST /autenticacao

Processa o login.

Nome da rota:

```text
authentication.store
```

### POST /autenticacao/sair

Processa o logout.

Nome da rota:

```text
logout
```

Foi usado `POST`, e não `GET`, porque logout altera estado de sessão. Isso mantém o fluxo coerente com proteção CSRF.

## Controller de Sessão

Arquivo:

```text
Modules/Authentication/app/Http/Controllers/AuthenticatedSessionController.php
```

Responsabilidades:

- exibir a tela de login;
- autenticar o usuário;
- regenerar sessão após login;
- registrar último acesso;
- fazer logout seguro.

### Método create

Renderiza:

```text
authentication::login
```

Essa view está em:

```text
Modules/Authentication/resources/views/login.blade.php
```

### Método store

Fluxo:

```php
$request->authenticate();
$request->session()->regenerate();

$request->user()->forceFill([
    'last_login_at' => now(),
    'last_login_ip' => $request->ip(),
])->save();

return redirect()->intended(route('dashboard'));
```

Pontos importantes:

- `authenticate()` fica no `LoginRequest`, separando validação e regra de login do controller.
- `session()->regenerate()` evita session fixation.
- `redirect()->intended(route('dashboard'))` permite que, se o usuário tentou acessar uma página protegida antes do login, ele volte para ela depois de autenticar. Se não houver destino anterior, cai em `/dashboard`.

### Método destroy

Fluxo:

```php
Auth::guard('web')->logout();
$request->session()->invalidate();
$request->session()->regenerateToken();
return redirect()->route('login');
```

Pontos importantes:

- encerra a sessão do guard `web`;
- invalida os dados da sessão;
- regenera o token CSRF;
- volta para a tela de login.

## LoginRequest

Arquivo:

```text
Modules/Authentication/app/Http/Requests/LoginRequest.php
```

Esse FormRequest concentra:

- validação dos campos;
- rate limit;
- autenticação;
- validação de usuário ativo;
- validação de usuário root.

### Validação dos Campos

Regras atuais:

```php
'email' => ['required', 'string', 'email:rfc', 'max:255'],
'password' => ['required', 'string', 'min:8', 'max:255'],
```

Foi usado `email:rfc`, e não `email:dns`, para evitar falhas em ambientes internos, e-mails locais ou domínios ainda não publicados em DNS.

### Rate Limit

O login usa:

```php
RateLimiter
```

A chave do rate limit combina:

```text
email + ip
```

Isso reduz tentativas repetidas contra a mesma conta a partir do mesmo IP.

Configurações:

```env
AUTH_LOGIN_MAX_ATTEMPTS=5
AUTH_LOGIN_DECAY_SECONDS=60
```

Essas variáveis alimentam:

```text
Modules/Authentication/config/config.php
```

### Autenticação

A autenticação usa:

```php
Auth::attempt($credentials, false)
```

Isso usa o guard padrão `web`, configurado em:

```text
config/auth.php
```

Como o projeto usa o model padrão:

```text
App\Models\User
```

o login consulta a tabela `users`.

### Bloqueio de Usuário Não Root ou Inativo

Mesmo se o e-mail e a senha estiverem corretos, o login só é aceito quando:

```php
$user->is_active === true
$user->is_root === true
```

Caso contrário, o sistema executa logout imediatamente e retorna mensagem genérica de falha.

Isso é importante porque, no futuro, o sistema terá cadastro de usuários e grupos. Neste momento, apenas o usuário root deve entrar.

## Tela de Login

Arquivo:

```text
Modules/Authentication/resources/views/login.blade.php
```

A tela foi construída como página completa, fora do shell do painel. Isso é importante porque o painel usa Turbo Frames para navegação interna, mas o login é uma fronteira de autenticação e deve carregar como documento completo.

Características:

- layout moderno em duas áreas no desktop;
- versão compacta para mobile;
- formulário centralizado;
- ícones Font Awesome;
- campo de senha reutilizando componente do módulo `UI`;
- `@csrf` no formulário;
- `data-turbo="false"` no formulário.

### Por Que data-turbo="false"?

O login foi deixado com submissão tradicional para manter o fluxo previsível:

- envia formulário;
- servidor valida;
- servidor redireciona;
- navegador carrega `/dashboard`.

Isso reduz efeitos colaterais de frame ou snapshot do Turbo durante troca de sessão.

## Usuário Root

O usuário root é configurado por variáveis de ambiente.

Arquivo:

```text
.env
```

Valores atuais configurados localmente:

```env
AUTH_ROOT_NAME=William
AUTH_ROOT_EMAIL=william@interativacom.com
AUTH_ROOT_PASSWORD=Interativa@2026
```

Também foram adicionados em:

```text
.env.example
```

para documentar o setup esperado.

## Seeder do Root

Arquivo:

```text
Modules/Authentication/database/seeders/RootUserSeeder.php
```

O seeder faz:

```php
User::updateOrCreate(
    ['email' => $email],
    [
        'name' => config('authentication.root.name', 'Root'),
        'password' => $password,
        'is_root' => true,
        'is_active' => true,
        'email_verified_at' => now(),
    ],
);
```

Pontos importantes:

- se o usuário já existir, ele é atualizado;
- se não existir, ele é criado;
- a senha passa pelo cast `hashed` do model `User`;
- o root é marcado como ativo;
- o root recebe `email_verified_at`.

O `DatabaseSeeder` chama o `RootUserSeeder`, então o comando abaixo cria o root:

```bash
php artisan db:seed
```

Também é possível rodar apenas ele:

```bash
php artisan db:seed --class=Modules\\Authentication\\Database\\Seeders\\RootUserSeeder --force
```

No Sail, foi usado:

```bash
docker compose exec -T laravel.test php artisan db:seed --class=Modules\\Authentication\\Database\\Seeders\\RootUserSeeder --force
```

## Alterações na Tabela users

Arquivo:

```text
Modules/Authentication/database/migrations/2026_05_04_000000_add_root_authentication_fields_to_users_table.php
```

Campos adicionados:

```php
$table->boolean('is_root')->default(false);
$table->boolean('is_active')->default(true);
$table->timestamp('last_login_at')->nullable();
$table->string('last_login_ip', 45)->nullable();
```

### is_root

Define se o usuário pode entrar neste primeiro estágio do sistema.

### is_active

Permite bloquear um usuário sem apagar o registro.

### last_login_at

Registra data e hora do último login bem-sucedido.

### last_login_ip

Registra o IP usado no último login bem-sucedido.

## Model User

Arquivo:

```text
app/Models/User.php
```

Foram adicionados os campos em `$fillable`:

```php
'is_root',
'is_active',
'last_login_at',
'last_login_ip',
```

Foram adicionados casts:

```php
'is_root' => 'boolean',
'is_active' => 'boolean',
'last_login_at' => 'datetime',
```

O cast de senha já existia:

```php
'password' => 'hashed',
```

Esse cast é importante porque garante hash automático quando uma senha é atribuída ao model.

## Proteção do Dashboard e Módulos Administrativos

O `/dashboard` era público. Agora está protegido por `auth`.

Arquivo:

```text
Modules/Panel/routes/web.php
```

Também foram protegidas rotas administrativas dos módulos:

```text
Modules/Group/routes/web.php
Modules/User/routes/web.php
Modules/Company/routes/web.php
Modules/Token/routes/web.php
Modules/Content/routes/web.php
Modules/Development/routes/web.php
Modules/Teste/routes/web.php
Modules/Inventory/routes/web.php
Modules/UI/routes/web.php
```

Com isso, usuários não autenticados são redirecionados para:

```text
/autenticacao
```

## Redirecionamentos de Autenticação

Arquivo:

```text
bootstrap/app.php
```

Foi configurado:

```php
$middleware->redirectTo(
    guests: fn () => route('login'),
    users: fn () => route('dashboard'),
);
```

Isso faz:

- usuário convidado tentando acessar rota protegida vai para `/autenticacao`;
- usuário autenticado tentando acessar rota `guest`, como `/autenticacao`, volta para `/dashboard`.

## Logout no Topbar

Arquivo:

```text
Modules/Panel/resources/views/partials/topbar.blade.php
```

Antes havia texto fixo `ROOT` e link `Sair` sem ação real.

Agora:

- exibe o nome do usuário autenticado;
- usa formulário `POST`;
- inclui `@csrf`;
- chama a rota `logout`;
- usa `data-turbo="false"` para evitar snapshot ou interceptação desnecessária do Turbo no logout.

## SecurityHeaders

Arquivo:

```text
app/Http/Middleware/SecurityHeaders.php
```

Esse middleware foi criado porque o Laravel não envia, por padrão, um conjunto completo de headers de segurança HTTP.

Ele foi registrado globalmente em:

```text
bootstrap/app.php
```

Registro:

```php
$middleware->append(SecurityHeaders::class);
```

Isso significa que o middleware passa por todas as requisições que chegam ao Laravel.

Importante: assets estáticos servidos diretamente pelo servidor web, como alguns arquivos em `public/build`, podem não passar pelo Laravel. Nesses casos, headers específicos de assets devem ser configurados no Nginx/Apache/Caddy, se necessário.

### Headers Aplicados

O middleware adiciona:

```text
X-Content-Type-Options: nosniff
X-Frame-Options: SAMEORIGIN
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(), usb=(), magnetometer=(), gyroscope=(), accelerometer=()
Content-Security-Policy: ...
Strict-Transport-Security: ...
```

O HSTS só é enviado quando a requisição é HTTPS.

### Content-Security-Policy

A CSP restringe de onde scripts, estilos, fontes, imagens, conexões e frames podem carregar.

Diretivas atuais:

```text
default-src 'self'
script-src 'self' 'unsafe-inline'
style-src 'self' 'unsafe-inline' fonts.googleapis.com
font-src 'self' data: fonts.gstatic.com
img-src 'self' data: blob:
connect-src 'self'
frame-src 'self'
object-src 'none'
base-uri 'self'
form-action 'self'
frame-ancestors 'self'
```

Em HTTPS, também adiciona:

```text
upgrade-insecure-requests
```

### Liberação do Vite em Desenvolvimento

Durante o desenvolvimento, o Laravel Vite plugin cria:

```text
public/hot
```

Esse arquivo aponta para o servidor Vite ativo, por exemplo:

```text
http://127.0.0.1:5174
```

O middleware lê esse arquivo em ambiente local/debug e libera esse origin na CSP:

- `script-src`;
- `style-src`;
- `connect-src`;
- WebSocket do HMR.

Isso foi necessário porque, sem essa liberação, a página `/autenticacao` carregava sem CSS/JS.

## Validação de Origem

O `SecurityHeaders` também valida a origem de requisições mutáveis:

```text
POST
PUT
PATCH
DELETE
```

Ele compara `Origin` ou `Referer` com o host atual da aplicação.

Se a origem existir e não bater com o host da aplicação, retorna:

```text
403
```

Isso complementa o CSRF do Laravel. CORS não impede que o servidor processe um POST; ele apenas controla se o navegador pode ler a resposta. Por isso a validação server-side de origem é útil para ações sensíveis.

## Controle de Cache Após Logout

Foi identificado que, após clicar em sair, o navegador podia voltar pelo botão "voltar" e exibir uma cópia visual da tela anterior.

Isso não significava que a sessão continuava válida no servidor, mas era uma experiência ruim e perigosa visualmente.

Para resolver, o `SecurityHeaders` aplica cache bloqueado em páginas autenticadas e rotas administrativas.

Headers:

```text
Cache-Control: no-store, no-cache, must-revalidate, max-age=0
Pragma: no-cache
Expires: 0
```

Rotas sensíveis cobertas:

- usuário autenticado;
- `logout`;
- `dashboard`;
- `configuracoes*`;
- `conteudos*`;
- `desenvolvimento*`;
- `inventories*`;
- `uis*`;
- `testes*`.

## Turbo Cache

Arquivo:

```text
Modules/Panel/resources/views/app.blade.php
```

Foi adicionado:

```html
<meta name="turbo-cache-control" content="no-cache">
```

Isso instrui o Turbo a não manter snapshot do shell do painel.

Essa medida trabalha junto com os headers HTTP anti-cache para evitar restauração visual de tela protegida depois do logout.

## Traduções em Português

Foram criados:

```text
lang/pt_BR/auth.php
lang/pt_BR/validation.php
```

O `.env` e `.env.example` foram ajustados para:

```env
APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR
```

Com isso, mensagens como:

```text
These credentials do not match our records.
```

passam a aparecer como:

```text
As credenciais informadas não correspondem aos nossos registros.
```

Também foram corrigidas acentuações na tela de login:

- Autenticação;
- Operação;
- usuário;
- Sessão;
- inválidas;
- não autorizado.

## Credenciais Locais Criadas

No ambiente local atual, o root foi configurado como:

```text
E-mail: william@interativacom.com
Senha: Interativa@2026
```

Essas credenciais foram gravadas no `.env` local e o seeder foi executado no container Sail.

Em produção, a senha deve ser alterada antes do deploy. A senha inicial não deve permanecer como padrão.

## Comandos Executados

### Autoload

Após criar módulo/classes novas:

```bash
composer dump-autoload
```

No Sail:

```bash
docker compose exec -T laravel.test composer dump-autoload
```

### Migrations

No Sail:

```bash
docker compose exec -T laravel.test php artisan migrate --force
```

### Seeder do Root

No Sail:

```bash
docker compose exec -T laravel.test php artisan db:seed --class=Modules\\Authentication\\Database\\Seeders\\RootUserSeeder --force
```

### Limpeza de Cache

Local:

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

No Sail:

```bash
docker compose exec -T laravel.test php artisan config:clear
docker compose exec -T laravel.test php artisan view:clear
```

### Testes

```bash
composer test
```

Resultado validado:

```text
12 tests, 56 assertions
```

### Build Frontend

```bash
npm run build
```

O build passou. O Vite mostrou apenas aviso de chunk acima de 500 kB, relacionado ao bundle atual do projeto.

## Testes Criados e Ajustados

### AuthenticationFlowTest

Arquivo:

```text
tests/Feature/AuthenticationFlowTest.php
```

Cobre:

- tela de login disponível para convidados;
- usuário autenticado redirecionado para dashboard ao acessar login;
- dashboard exige autenticação;
- páginas autenticadas enviam headers anti-cache;
- painel contém `turbo-cache-control`.

### PanelTurboShellTest

Arquivo:

```text
tests/Feature/PanelTurboShellTest.php
```

Foi ajustado para autenticar um usuário root antes de acessar o painel.

Isso era necessário porque `/dashboard` deixou de ser público.

### ModuleRegistrationTest

Arquivo:

```text
tests/Feature/ModuleRegistrationTest.php
```

Foi ajustado para:

- garantir que convidados são redirecionados para login;
- garantir que usuários autenticados conseguem acessar as páginas protegidas;
- manter validação de nomes de rotas.

## Relação com Segurança Nativa do Laravel

O Laravel já fornece várias proteções:

- CSRF;
- hash de senha;
- escape no Blade;
- validação;
- sessão;
- autenticação;
- proteção contra SQL injection quando se usa Eloquent ou Query Builder.

A implementação atual não substitui isso. Ela complementa.

O que foi adicionado além do padrão:

- módulo dedicado de autenticação;
- bloqueio de login apenas para root ativo;
- rate limit por e-mail e IP;
- headers de segurança;
- CSP;
- validação de origem em métodos mutáveis;
- headers anti-cache para área autenticada;
- controle de cache do Turbo;
- traduções pt_BR;
- seeder root configurável por `.env`.

## Pontos de Atenção

### Senha Root

A senha atual é adequada apenas para desenvolvimento/homologação local. Antes de produção, configure uma senha forte e exclusiva no `.env`.

### CSP

A CSP atual permite `unsafe-inline` para scripts e estilos por compatibilidade com o estado atual do projeto. Uma etapa futura de endurecimento pode remover isso gradualmente usando nonce/hash ou movendo estilos inline para arquivos compilados.

### API

O `SecurityHeaders` é global, então também passa por rotas API. Isso é desejável para headers gerais, mas a validação de origem pode exigir ajustes se no futuro houver integrações externas legítimas fazendo `POST`, `PUT`, `PATCH` ou `DELETE` diretamente na API.

### Cadastro de Usuários e Grupos

Hoje o login só aceita root. Quando usuários e grupos forem implementados, a regra:

```php
$user->is_root
```

deverá evoluir para permissões, grupos ou policies.

### Cache do Navegador

Os headers `no-store` reduzem o risco de tela protegida aparecer pelo botão voltar. O servidor já invalida a sessão no logout, então qualquer nova requisição real ao painel volta para `/autenticacao`.

## Arquivos Principais

```text
Modules/Authentication/module.json
Modules/Authentication/composer.json
Modules/Authentication/app/Providers/AuthenticationServiceProvider.php
Modules/Authentication/config/config.php
Modules/Authentication/routes/web.php
Modules/Authentication/app/Http/Controllers/AuthenticatedSessionController.php
Modules/Authentication/app/Http/Requests/LoginRequest.php
Modules/Authentication/resources/views/login.blade.php
Modules/Authentication/database/migrations/2026_05_04_000000_add_root_authentication_fields_to_users_table.php
Modules/Authentication/database/seeders/RootUserSeeder.php
app/Http/Middleware/SecurityHeaders.php
app/Models/User.php
bootstrap/app.php
database/seeders/DatabaseSeeder.php
Modules/Panel/resources/views/partials/topbar.blade.php
Modules/Panel/resources/views/app.blade.php
lang/pt_BR/auth.php
lang/pt_BR/validation.php
tests/Feature/AuthenticationFlowTest.php
tests/Feature/PanelTurboShellTest.php
tests/Feature/ModuleRegistrationTest.php
```
