# Manual do reCAPTCHA v3

Este documento descreve a implementação do Google reCAPTCHA v3 invisível no CMS. A solução foi pensada para ser reutilizável em qualquer formulário do sistema, tanto em áreas administrativas quanto em futuros formulários públicos.

## Objetivo

O objetivo foi criar uma base reutilizável para proteger formulários contra automações abusivas sem exibir checkbox ao usuário.

O reCAPTCHA v3 funciona de forma invisível:

1. O usuário interage normalmente com o formulário.
2. No momento do submit, o JavaScript solicita um token ao Google.
3. O token é enviado junto com o formulário.
4. O backend valida esse token no Google.
5. O Google retorna `success`, `score`, `action` e metadados.
6. O sistema decide se aceita ou bloqueia com base no score mínimo.

## Arquitetura Implementada

A implementação foi dividida em camadas:

```text
Componente UI -> JS Turbo-aware -> Formulário -> Rule Laravel -> Service -> Google siteverify
```

Essa separação evita duplicação e permite reutilizar o reCAPTCHA em qualquer formulário.

## Arquivos Criados

### Configuração

```text
config/recaptcha.php
```

Centraliza as configurações do reCAPTCHA.

### Serviço Backend

```text
app/Services/Security/RecaptchaV3Verifier.php
app/Services/Security/RecaptchaVerificationResult.php
```

Responsáveis por validar o token no Google e retornar resultado padronizado.

### Rule Laravel

```text
app/Rules/RecaptchaV3.php
```

Permite usar o reCAPTCHA dentro de `FormRequest` ou `$request->validate()`.

### Trait Opcional

```text
app/Http/Requests/Concerns/ValidatesRecaptchaV3.php
```

Permite validar manualmente em requests/controllers quando a rule não for a melhor opção.

### Componente UI

```text
Modules/UI/resources/views/components/recaptcha-v3.blade.php
```

Componente Blade reutilizável.

### JavaScript

```text
Modules/UI/resources/assets/components/recaptcha-v3/index.js
```

Controla carregamento do script Google, geração do token e integração com submit.

O arquivo foi importado em:

```text
Modules/UI/resources/assets/js/app.js
```

### Testes

```text
tests/Unit/RecaptchaV3VerifierTest.php
tests/Feature/AuthenticationFlowTest.php
```

## Configuração no .env

Foram adicionadas as variáveis:

```env
RECAPTCHA_V3_ENABLED=true
RECAPTCHA_V3_SITE_KEY=
RECAPTCHA_V3_SECRET_KEY=
RECAPTCHA_V3_MIN_SCORE=0.5
RECAPTCHA_V3_LOGIN_MIN_SCORE=0.7
```

### RECAPTCHA_V3_ENABLED

Controla se o reCAPTCHA está ativo.

Quando `false`:

- o componente não renderiza inputs;
- o script do Google não carrega;
- a rule backend aprova sem chamar o Google;
- os formulários continuam funcionando normalmente.

Quando `true`:

- o componente renderiza os campos hidden;
- o script do Google é carregado;
- o token é gerado no submit;
- o backend valida o token no Google.

### RECAPTCHA_V3_SITE_KEY

Chave pública usada no navegador.

### RECAPTCHA_V3_SECRET_KEY

Chave privada usada no backend para validar o token.

Essa chave nunca deve aparecer no frontend.

### RECAPTCHA_V3_MIN_SCORE

Score mínimo padrão para ações sem configuração específica.

### RECAPTCHA_V3_LOGIN_MIN_SCORE

Score mínimo específico para a action `login`.

O login usa score maior porque é uma rota mais sensível.

## Criação da Chave no Google

No painel do Google reCAPTCHA:

1. Escolha o tipo:

```text
Com base em pontuação (v3)
```

2. Em domínios para desenvolvimento local, use:

```text
localhost
```

Opcionalmente, se acessar pelo IP:

```text
127.0.0.1
```

Não coloque porta. Use `localhost`, não `localhost:80` ou `localhost:5173`.

3. Para produção ou homologação, adicione os domínios reais:

```text
seudominio.com.br
www.seudominio.com.br
homologacao.seudominio.com.br
```

O Google valida domínio, não caminho. Então não é necessário cadastrar `/autenticacao`, `/contato` ou outras URLs específicas.

## Como Usar em um Formulário

No Blade, inclua o componente dentro do `<form>`:

```blade
<x-ui::recaptcha-v3 action="login" />
```

Exemplo:

```blade
<form method="post" action="{{ route('authentication.store') }}">
    @csrf

    <input name="email" type="email">
    <input name="password" type="password">

    <x-ui::recaptcha-v3 action="login" />

    <button type="submit">Entrar</button>
</form>
```

Quando a flag está ativa, o componente renderiza:

```html
<input type="hidden" name="recaptcha_token">
<input type="hidden" name="recaptcha_action" value="login">
```

## Como Validar no Backend

Use a rule:

```php
use App\Rules\RecaptchaV3;
```

Em um `FormRequest`:

```php
public function rules(): array
{
    return [
        'email' => ['required', 'email'],
        'recaptcha_token' => [new RecaptchaV3('login')],
    ];
}
```

Em controller com `$request->validate()`:

```php
$validated = $request->validate([
    'name' => ['required', 'string', 'max:120'],
    'recaptcha_token' => [new RecaptchaV3('company_store')],
]);
```

## Actions

Cada formulário deve usar uma action clara e específica.

Exemplos:

```text
login
company_store
company_update
user_store
user_update
group_store
token_store
contact_public_store
newsletter_public_store
```

Benefícios:

- facilita análise no painel Google;
- permite score diferente por contexto;
- evita aceitar token gerado para outra intenção;
- melhora rastreabilidade.

O backend valida se a action retornada pelo Google é igual à action esperada.

## Score

O reCAPTCHA v3 retorna um score. Quanto maior, mais confiável.

Exemplo:

```text
0.9 = interação provavelmente legítima
0.1 = interação provavelmente suspeita
```

O sistema compara o score retornado com o mínimo configurado.

Configuração padrão:

```env
RECAPTCHA_V3_MIN_SCORE=0.5
```

Configuração específica do login:

```env
RECAPTCHA_V3_LOGIN_MIN_SCORE=0.7
```

No arquivo `config/recaptcha.php`, a action `login` tem score próprio:

```php
'actions' => [
    'login' => [
        'min_score' => (float) env('RECAPTCHA_V3_LOGIN_MIN_SCORE', 0.7),
    ],
],
```

Outras actions usam o score padrão, a menos que sejam adicionadas nessa lista.

## Serviço RecaptchaV3Verifier

Arquivo:

```text
app/Services/Security/RecaptchaV3Verifier.php
```

Responsabilidades:

- verificar se a feature está habilitada;
- verificar se site key e secret key estão configuradas;
- enviar token para o Google;
- validar resposta HTTP;
- validar `success`;
- validar `action`;
- validar `hostname`, se configurado;
- validar `score`;
- retornar resultado padronizado.

Endpoint usado:

```text
https://www.google.com/recaptcha/api/siteverify
```

Parâmetros enviados:

```text
secret
response
remoteip
```

Referência oficial:

```text
https://developers.google.com/recaptcha/docs/verify
```

## Resultado Padronizado

Arquivo:

```text
app/Services/Security/RecaptchaVerificationResult.php
```

Retorna:

```php
public readonly bool $valid;
public readonly string $reason;
public readonly ?float $score;
public readonly array $payload;
```

Isso evita que controllers ou rules precisem conhecer detalhes da resposta do Google.

## Rule RecaptchaV3

Arquivo:

```text
app/Rules/RecaptchaV3.php
```

Uso:

```php
'recaptcha_token' => [new RecaptchaV3('login')]
```

Se o reCAPTCHA estiver desativado, a rule passa.

Se estiver ativo, a rule chama o `RecaptchaV3Verifier`.

Em caso de falha, retorna a mensagem:

```text
Não foi possível validar o reCAPTCHA. :reason
```

Essa mensagem está em:

```text
lang/pt_BR/validation.php
```

## Trait ValidatesRecaptchaV3

Arquivo:

```text
app/Http/Requests/Concerns/ValidatesRecaptchaV3.php
```

Uso opcional:

```php
use ValidatesRecaptchaV3;

$this->validateRecaptchaV3('login');
```

Esse trait é útil quando a validação precisa acontecer fora do array de rules, por exemplo depois de alguma regra condicional mais complexa.

## Componente Blade

Arquivo:

```text
Modules/UI/resources/views/components/recaptcha-v3.blade.php
```

Uso:

```blade
<x-ui::recaptcha-v3 action="login" />
```

Quando ativo e com site key configurada, ele renderiza:

```html
<div data-recaptcha-v3 data-recaptcha-v3-action="login" data-recaptcha-v3-site-key="...">
    <input type="hidden" name="recaptcha_token" data-recaptcha-v3-token>
    <input type="hidden" name="recaptcha_action" value="login">
</div>
```

Quando inativo, não renderiza nada.

## JavaScript

Arquivo:

```text
Modules/UI/resources/assets/components/recaptcha-v3/index.js
```

Responsabilidades:

- encontrar componentes `data-recaptcha-v3`;
- carregar o script do Google;
- fazer o badge aparecer no canto da tela;
- interceptar o submit;
- chamar `grecaptcha.execute(siteKey, { action })`;
- preencher `recaptcha_token`;
- reenviar o formulário.

O script do Google é carregado assim:

```text
https://www.google.com/recaptcha/api.js?render=SITE_KEY
```

## Compatibilidade com Turbo

O JS é inicializado em:

```js
turbo:load
turbo:frame-load
DOMContentLoaded
```

Isso permite usar o componente em:

- páginas completas;
- Turbo Frames;
- telas internas do painel;
- formulários carregados depois de navegação SPA.

## Badge no Canto da Tela

O reCAPTCHA v3 normalmente exibe um badge no canto inferior direito quando o script do Google é carregado.

Para isso acontecer, não basta gerar token no submit. O script precisa ser carregado quando a página encontra o componente.

Por isso o JS atual chama:

```js
loadRecaptchaScript(siteKey)
```

assim que encontra `[data-recaptcha-v3]`.

Se o badge não aparecer, verifique:

1. `RECAPTCHA_V3_ENABLED=true`;
2. `RECAPTCHA_V3_SITE_KEY` preenchida;
3. `php artisan config:clear`;
4. se o componente está renderizando no HTML;
5. se o CSP permite Google;
6. se o domínio atual está cadastrado no Google;
7. console do navegador.

## CSP

Arquivo:

```text
app/Http/Middleware/SecurityHeaders.php
```

Quando o reCAPTCHA está ativo, a CSP libera:

```text
www.google.com
www.gstatic.com
www.recaptcha.net
```

Em:

```text
script-src
connect-src
frame-src
```

Isso é necessário para:

- carregar o script do Google;
- executar o reCAPTCHA;
- abrir iframe invisível;
- validar comunicação do widget.

## Aplicação Inicial no Login

Arquivo:

```text
Modules/Authentication/resources/views/login.blade.php
```

Foi adicionado:

```blade
<x-ui::recaptcha-v3 action="login" />
```

Arquivo:

```text
Modules/Authentication/app/Http/Requests/LoginRequest.php
```

Foi adicionado:

```php
'recaptcha_token' => [new RecaptchaV3('login')],
```

Com a flag desligada, o login funciona normalmente sem chamar o Google.

Com a flag ligada, o login exige token válido.

## Como Ativar Localmente

No `.env`:

```env
RECAPTCHA_V3_ENABLED=true
RECAPTCHA_V3_SITE_KEY=sua_site_key
RECAPTCHA_V3_SECRET_KEY=sua_secret_key
```

Depois:

```bash
docker compose exec -T laravel.test php artisan config:clear
docker compose exec -T laravel.test php artisan view:clear
```

Se estiver usando Vite:

```bash
npm run dev
```

ou gere build:

```bash
npm run build
```

## Como Desativar

No `.env`:

```env
RECAPTCHA_V3_ENABLED=false
```

Depois limpe cache:

```bash
docker compose exec -T laravel.test php artisan config:clear
```

Com isso:

- o componente não aparece no HTML;
- o script do Google não é carregado;
- a validação backend passa sem chamada externa.

## Testes

Foram adicionados testes para:

- reCAPTCHA desligado passa sem chamada HTTP;
- resposta válida do Google é aceita;
- score baixo é recusado;
- action inválida é recusada;
- login não renderiza componente quando flag está desligada;
- login renderiza componente quando flag está ligada.

Comando:

```bash
composer test
```

Resultado validado:

```text
18 tests, 69 assertions
```

## Build Frontend

Como houve alteração no JS do módulo `UI`, foi executado:

```bash
npm run build
```

O build passou.

O Vite exibiu apenas aviso de chunk grande, já existente no contexto do bundle atual.

## Reutilização Futura

Para aplicar em um novo formulário:

1. Adicionar no Blade:

```blade
<x-ui::recaptcha-v3 action="nome_da_action" />
```

2. Validar no backend:

```php
'recaptcha_token' => [new RecaptchaV3('nome_da_action')],
```

3. Se a action precisar de score próprio, adicionar em:

```text
config/recaptcha.php
```

Exemplo:

```php
'actions' => [
    'login' => [
        'min_score' => (float) env('RECAPTCHA_V3_LOGIN_MIN_SCORE', 0.7),
    ],
    'contact_public_store' => [
        'min_score' => (float) env('RECAPTCHA_V3_CONTACT_MIN_SCORE', 0.6),
    ],
],
```

E no `.env`:

```env
RECAPTCHA_V3_CONTACT_MIN_SCORE=0.6
```

## Recomendações

- Use actions específicas por formulário.
- Não reutilize sempre `login` para tudo.
- Em formulários públicos, considere score mínimo maior.
- Em formulários internos autenticados, avalie se o reCAPTCHA é necessário em todos os casos.
- Monitore os scores no painel do Google antes de bloquear agressivamente.
- Mantenha `RECAPTCHA_V3_SECRET_KEY` apenas no backend.
- Não versione chaves reais em repositórios públicos.

