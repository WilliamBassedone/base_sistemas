# Manual de Organização de Menus

## Objetivo

Definir uma regra simples para organizar a navegação lateral do CMS sem transformar `Configurações` em um agrupador genérico.

A ideia principal é:

- organizar os menus por função no negócio
- evitar classificar tudo apenas como "cadastro"
- separar entidades de domínio de entidades administrativas
- manter espaço para o crescimento do sistema sem perder clareza

## Princípio central

A pergunta não deve ser:

- "isso é um cadastro?"

A pergunta melhor é:

- "qual é o papel desse item dentro do sistema?"

Dois menus podem ser tecnicamente "cadastros", mas pertencerem a áreas diferentes.

Exemplo:

- `Participantes` é cadastro de domínio
- `Usuários` é cadastro administrativo

Por isso, a navegação deve refletir função de uso e não apenas tipo técnico de registro.

## Regra prática de classificação

### Cadastros

Usar quando o item representa uma entidade de negócio do sistema.

Exemplos:

- empresas
- clientes
- lojas
- participantes
- professores

Pergunta guia:

- isso representa uma pessoa, organização ou estrutura do negócio?

Se sim, tende a ir para `Cadastros`.

### Operacional

Usar quando o item representa fluxo diário de trabalho.

Exemplos:

- conteúdos
- formulários
- processos internos de operação

Pergunta guia:

- isso é usado no dia a dia da operação para executar trabalho?

Se sim, tende a ir para `Operacional`.

### Relatórios

Usar quando o foco principal do menu é consulta, análise, auditoria ou saída de dados.

Exemplos:

- participantes, quando a tela for analítica
- erros
- notas fiscais
- números

Pergunta guia:

- a principal função da tela é consultar, medir, listar ou exportar?

Se sim, tende a ir para `Relatórios`.

### Administrativo

Usar quando o item controla acesso, segurança, estrutura interna ou governança do sistema.

Exemplos:

- usuários
- grupos
- menus
- api tokens

Pergunta guia:

- isso controla quem acessa, o que pode fazer ou como o sistema é administrado?

Se sim, tende a ir para `Administrativo`.

### Configurações

Usar apenas para parâmetros globais e comportamento geral do sistema.

Exemplos:

- preferências globais
- parâmetros institucionais
- integrações que mudam o comportamento do sistema

Pergunta guia:

- isso altera o comportamento global do CMS?

Se sim, tende a ir para `Configurações`.

## Regra de desempate

Quando houver dúvida sobre um menu, use esta ordem:

1. Se for controle de acesso ou governança: `Administrativo`
2. Se for entidade de negócio: `Cadastros`
3. Se for fluxo diário: `Operacional`
4. Se for consulta analítica: `Relatórios`
5. Se for parâmetro global: `Configurações`

## Casos específicos discutidos no projeto

### Usuários

Mesmo sendo um cadastro, `Usuários` não deve ficar em `Cadastros`.

Motivo:

- representa acesso ao sistema
- conversa com grupos e permissões
- é governança interna

Classificação recomendada:

- `Administrativo`

### Participantes

Pode existir em mais de um contexto.

Se a tela for:

- manutenção de registro: `Cadastros`
- consulta analítica: `Relatórios`

Recomendação:

- se houver um módulo de manutenção, ele fica em `Cadastros`
- se houver uma visão analítica, ela pode existir também em `Relatórios`

### Números

Se seguir a lógica do sistema de referência mencionado durante o projeto, deve ficar em `Relatórios`.

Motivo:

- o papel esperado é de consulta/listagem analítica

Classificação recomendada:

- `Relatórios`

### Formulários

Depende do uso principal:

- se for criação e gestão operacional: `Operacional`
- se for apenas estrutura base de cadastro: `Cadastros`

Recomendação atual:

- `Operacional`

### API Tokens

Mesmo vinculados a empresas, continuam mais próximos de controle de acesso e integração do que de cadastro de negócio.

Classificação recomendada:

- `Administrativo`

## Estrutura sugerida para este CMS

### Dashboard

- dashboard

### Cadastros

- empresas
- clientes
- lojas
- participantes
- professores

### Operacional

- conteúdos
- formulários

### Relatórios

- participantes, quando houver tela analítica
- erros
- notas fiscais
- números

### Administrativo

- usuários
- grupos
- menus
- api tokens

### Configurações

- manter vazio ou mínimo até existirem parâmetros globais reais

### Desenvolvimento

- componentes

## O que evitar

- colocar tudo em `Configurações`
- usar `Cadastros` como destino de qualquer tela com formulário
- misturar entidade de negócio com controle de acesso
- usar `Relatórios` para telas que na prática são manutenção de dados

## Resumo executivo

- `Cadastros`: entidades de negócio
- `Operacional`: execução do dia a dia
- `Relatórios`: consulta e análise
- `Administrativo`: acesso, segurança e governança
- `Configurações`: parâmetros globais do sistema

Se houver dúvida entre `Cadastros` e `Administrativo`, a regra é:

- cadastro de domínio vai para `Cadastros`
- cadastro de acesso e controle vai para `Administrativo`
