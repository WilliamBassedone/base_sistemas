# Manual de Comandos Linux

## Objetivo

Este documento reune comandos Linux uteis para o dia a dia do projeto.

A ideia e facilitar navegacao, inspecao de arquivos, permissoes e diagnostico rapido.

## Navegacao

Ver diretorio atual:

```bash
pwd
```

Listar arquivos:

```bash
ls
```

Listar com detalhes:

```bash
ls -la
```

Entrar em uma pasta:

```bash
cd Modules
```

Voltar um nivel:

```bash
cd ..
```

## Buscar arquivos e texto

Buscar arquivos pelo nome com `find`:

```bash
find . -name "*Group*"
```

Buscar texto com `grep`:

```bash
grep -R "groups.index" .
```

Buscar texto com `rg`:

```bash
rg "groups.index"
```

Listar arquivos com `rg`:

```bash
rg --files
```

Listar apenas arquivos Blade:

```bash
rg --files | rg "blade\\.php$"
```

## Ler arquivos

Mostrar arquivo inteiro:

```bash
cat arquivo.txt
```

Mostrar primeiras linhas:

```bash
head -n 20 arquivo.txt
```

Mostrar ultimas linhas:

```bash
tail -n 20 arquivo.txt
```

Acompanhar arquivo em tempo real:

```bash
tail -f storage/logs/laravel.log
```

Mostrar trecho de arquivo:

```bash
sed -n '1,120p' routes/web.php
```

## Permissoes e propriedade

Ver permissoes de pasta:

```bash
ls -ld storage storage/logs
```

Ver owner, group e permissao:

```bash
stat -c '%U %G %a %n' storage storage/logs
```

Alterar dono de arquivos:

```bash
sudo chown -R wbassedone:wbassedone storage bootstrap/cache
```

Dar permissao de escrita em grupo:

```bash
chmod -R ug+rwX storage bootstrap/cache
```

Definir permissao de diretorios:

```bash
find Modules -type d -exec chmod 755 {} \\;
```

Definir permissao de arquivos:

```bash
find Modules -type f -exec chmod 644 {} \\;
```

## Processos

Listar processos:

```bash
ps aux
```

Buscar processo especifico:

```bash
ps aux | grep php
```

Ver portas abertas:

```bash
ss -tulpn
```

Matar processo por PID:

```bash
kill 12345
```

Forcar encerramento:

```bash
kill -9 12345
```

## Docker e containers

Ver containers em execucao:

```bash
docker ps
```

Ver todos os containers:

```bash
docker ps -a
```

Ver logs de container:

```bash
docker logs nome-do-container
```

Ver logs em tempo real:

```bash
docker logs -f nome-do-container
```

Entrar no shell de um container:

```bash
docker exec -it nome-do-container bash
```

## Rede

Testar conexao HTTP:

```bash
curl -I http://localhost
```

Baixar conteudo de uma rota:

```bash
curl http://localhost/configuracoes/grupos
```

Ver IP local:

```bash
hostname -I
```

## Git

Ver status:

```bash
git status
```

Ver diferencas:

```bash
git diff
```

Ver historico resumido:

```bash
git log --oneline --decorate -n 10
```

Ver arquivo alterado:

```bash
git diff -- Modules/Panel/config/config.php
```

## Arquivos e diretorios

Criar pasta:

```bash
mkdir -p docs/exemplos
```

Copiar arquivo:

```bash
cp .env.example .env
```

Mover ou renomear:

```bash
mv arquivo-antigo.txt arquivo-novo.txt
```

Remover arquivo:

```bash
rm arquivo.txt
```

Remover pasta recursivamente:

```bash
rm -rf pasta-temporaria
```

## Variaveis e usuario

Ver usuario atual:

```bash
whoami
```

Ver UID:

```bash
id -u
```

Ver GID:

```bash
id -g
```

Ver variaveis de ambiente:

```bash
printenv
```

Buscar variaveis especificas:

```bash
printenv | rg "WWWUSER|WWWGROUP|APP_ENV"
```

## Utilitarios uteis no projeto

Ver tamanho das pastas:

```bash
du -sh Modules/* docs/* 2>/dev/null
```

Ordenar por tamanho:

```bash
du -sh ./* | sort -h
```

Contar linhas de um arquivo:

```bash
wc -l Modules/Group/resources/views/groups/index.blade.php
```

## Exemplos de diagnostico

### Descobrir porque uma rota nao existe

```bash
rg "groups.index"
./vendor/bin/sail artisan route:list --name=groups
```

### Descobrir problema de permissao em logs

```bash
ls -ld storage storage/logs
stat -c '%U %G %a %n' storage storage/logs
tail -n 50 storage/logs/laravel.log
```

### Ver se o container esta no ar

```bash
docker ps
./vendor/bin/sail ps
```
