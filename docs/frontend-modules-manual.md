# Frontend Modules Manual

## Objetivo
Organizar a lógica de frontend por responsabilidade de módulo para que novos desenvolvedores encontrem cada comportamento no lugar certo.

## Regra prática
- `resources/js/app.js`: agregador mínimo da aplicação.
- `Modules/Panel/resources/assets/js/app.js`: comportamento estrutural do painel.
- `Modules/UI/resources/assets/js/app.js`: comportamento de componentes reutilizáveis.
- `Modules/<Modulo>/resources/assets/js/app.js`: comportamento específico daquele domínio.

## O que fica em cada lugar

### App
Arquivo: [resources/js/app.js](/home/wbassedone/projetos/interativacom/cms/resources/js/app.js)

Responsável por:
- bootstrap global do Laravel
- libs globais
- importar os módulos de frontend necessários

Não deve concentrar regra de negócio nem comportamento específico de tela.

### Panel
Arquivo: [Modules/Panel/resources/assets/js/app.js](/home/wbassedone/projetos/interativacom/cms/Modules/Panel/resources/assets/js/app.js)

Responsável por:
- sidebar
- submenu
- destaque de navegação ativa
- sincronização de título da página/topbar

Tudo que pertence ao shell do painel deve ficar aqui.

### UI
Arquivo: [Modules/UI/resources/assets/js/app.js](/home/wbassedone/projetos/interativacom/cms/Modules/UI/resources/assets/js/app.js)

Responsável por:
- campos reutilizáveis
- Jodit
- carregamento sob demanda de componentes de UI, como a tabela `data-table-tw`

Tudo que é reutilizável entre módulos deve nascer aqui.

### Group
Arquivo: [Modules/Group/resources/assets/js/app.js](/home/wbassedone/projetos/interativacom/cms/Modules/Group/resources/assets/js/app.js)

Responsável por:
- matriz de permissões da tela de grupos

Se amanhã existir outro comportamento exclusivo de grupos, ele deve ser adicionado aqui.

## Como decidir onde colocar um script novo
- Se afeta o shell inteiro do admin: `Panel`
- Se é componente reutilizável: `UI`
- Se pertence a uma feature de domínio: módulo da feature
- Se só orquestra imports e bootstrap: `resources/js/app.js`

## Convenção recomendada
- Use `data-*` no HTML para ligar comportamento JS.
- Prefira inicialização idempotente com `dataset.bound`.
- Para telas carregadas por Turbo, sempre ouvir `turbo:load`, `turbo:frame-load` e, quando necessário, `turbo:render`.
- Se um componente só deve carregar quando existe na tela, o `import()` dinâmico deve ficar no módulo dono do componente.
