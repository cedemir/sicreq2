# SICREQ — Catálogo de quadrinhos

Sistema web para catalogar um acervo de quadrinhos: títulos, exemplares, histórias, personagens, artistas e tabelas auxiliares. Foi gerado a partir do esquema legado em [`export.sql`](export.sql) e tem CRUD completo (listagem com busca e paginação, cadastro, edição e exclusão) para as 15 tabelas do acervo.

## Tecnologias

### Back-end

| Tecnologia | Versão | Uso |
| --- | --- | --- |
| PHP | 8.3 | Linguagem |
| Laravel | 13 | Framework |
| Eloquent | — | Models com os atributos `#[Table]`, `#[Fillable]` e `#[Scope]` (busca nas listagens) |
| Migrations | — | Esquema com chaves estrangeiras simples e compostas que atualizam em cascata e bloqueiam a exclusão de registros em uso |
| Form Requests | — | Validação, incluindo a existência de chaves compostas e a unicidade da chave composta |
| Resource Controllers | — | Rotas `Route::resource` para cada tabela |
| Storage (disco `public`) | — | Upload das fotos de artistas e das capas de exemplares |
| laravel-dompdf | 3.1 | Geração dos relatórios em PDF (`barryvdh/laravel-dompdf`) |

### Banco de dados

- **MySQL** no ambiente de desenvolvimento e produção.
- **SQLite** em memória na suíte de testes.

### Front-end

- **Blade** com componentes reaproveitáveis em [`resources/views/components`](resources/views/components): layout, cabeçalho de página, card, campos de formulário, busca e ações das linhas.
- **Tailwind CSS 4** com as cores do tema escuro definidas em [`resources/css/theme.css`](resources/css/theme.css) (`@theme`).
- **Vite 8** para o build dos assets. Se não houver build (`public/build/manifest.json`) nem servidor Vite rodando, o layout carrega o Tailwind por CDN (`@tailwindcss/browser`) usando o mesmo arquivo de tema.
- Sem framework JavaScript. O único JS é a confirmação antes de excluir.

### Qualidade e desenvolvimento

- **Pest 4**: testes de feature do CRUD de cada tabela e do painel.
- **Factories e Seeders**: geram dados de exemplo ligados entre si ([`AcervoSeeder`](database/seeders/AcervoSeeder.php)).
- **Laravel Pint**: padronização do código.
- **Laravel Boost**: servidor MCP para desenvolvimento assistido por IA.

## Requisitos

- PHP 8.3+ com as extensões `pdo_mysql` e `gd` (a `gd` é usada nos testes de upload)
- Composer
- MySQL 8+
- Node.js e npm (opcional, só para gerar o build do Vite)

## Instalação

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure a conexão com o MySQL no `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sicreq2
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

Crie as tabelas, o link do storage (necessário para exibir as imagens) e, se quiser, os dados de exemplo:

```bash
php artisan migrate
php artisan storage:link
php artisan db:seed    # opcional
```

Opcionalmente, gere os assets com o Vite:

```bash
npm install
npm run build
```

Crie o primeiro usuário (o comando pede a senha de forma oculta):

```bash
php artisan app:create-user --name="Seu Nome" --email=voce@example.com
```

Para iniciar o servidor:

```bash
php artisan serve
```

## Acesso

Todas as telas exigem login. A autenticação usa o próprio Laravel (Auth e sessões), sem pacotes adicionais:

- **Login** em `/login`, com a opção "Manter conectado". Depois de 5 tentativas erradas para o mesmo e-mail e IP, o acesso fica bloqueado por alguns segundos.
- **Usuários** (menu Administração): cadastro, edição e exclusão. Na edição, deixar a senha em branco mantém a atual, e não é possível excluir o próprio usuário.
- O seeder cria o usuário `test@example.com` com a senha `password`, só para desenvolvimento.

## Módulos

O menu lateral é configurado em [`config/menu.php`](config/menu.php).

| Grupo | Telas | Tabelas |
| --- | --- | --- |
| Acervo | Títulos, Exemplares, Histórias | `titulo`, `exemplar`, `historia` |
| Personagens | Personagens, Imagens, Criadores, Aparições em histórias | `personagem`, `imagempersonagem`, `personagem_criador`, `historiapersonagem` |
| Artistas | Artistas, Participações | `artista`, `artista_historia` |
| Tabelas auxiliares | Editoras, Universos, Tipos de título, Situações de título, Tamanhos, Armazenagens | `editora`, `universo`, `tipo_titulo`, `situacao_titulo`, `tamanho`, `armazenagem` |
| Administração | Usuários | `users` |

### Geração de exemplares em lote

Na listagem de exemplares, o botão **Gerar em lote** abre a tela em `/exemplares/lote`. Ela cadastra todos os exemplares de um título, do número inicial ao final, aplicando a mesma armazenagem, tamanho e nº de páginas a todos. Números que já existem para o título são mantidos sem alteração e informados na mensagem final. O limite é de 1.000 exemplares por vez.

### Relatórios

- **Relatório de exemplares** (botão **Relatório PDF** na listagem de exemplares, `/exemplares/relatorio`): gera direto, em nova aba, um PDF com todos os exemplares cadastrados, agrupados por título em ordem alfabética e numerados em ordem (subtítulo, data, armazenagem, tamanho, situação e páginas), com o total de títulos, exemplares e páginas no topo.

## Diferenças em relação ao `export.sql`

Algumas adaptações foram feitas para que o esquema legado funcione bem com o Eloquent:

- **Tabelas com código simples** (editora, título, artista, personagem etc.): o código virou chave primária com auto incremento.
- **Tabelas com chave composta** (exemplar, história e as tabelas de relacionamento): ganharam uma coluna `id` usada nas URLs. Os códigos originais continuam lá, protegidos por um índice único.
- **`personagem`**: a chave original era universo + personagem. Passou a ser só `personagem_codigo`, porque é assim que as outras tabelas fazem referência a ele.
- **`historia.historia_numero`**: renomeado de `HISTORIA_CODIGO`. Nas tabelas `artista_historia` e `historiapersonagem` a coluna continua `historia_codigo`, referenciando `historia_numero`.
- **`historia_resumo` e `personagem_origem`**: passaram de `varchar(255)` para `text` (até 16.000 caracteres na validação).
- **`historia_cor`**: tratado como sim/não ("Colorida"), gravado como 0/1.
- **Nomes de tabelas e colunas**: em minúsculas, sem `created_at`/`updated_at`, para facilitar a importação dos dados antigos.
- **Índices com nomes longos**: as chaves compostas de `artista_historia` e `historiapersonagem` têm nomes explícitos e curtos, por causa do limite de 64 caracteres do MySQL.
- **`Erros_ao_colar`**: tabela que o Access cria automaticamente quando uma colagem falha. Não faz parte do sistema e foi removida.

## Testes

```bash
php artisan test --compact
```

Os testes usam SQLite em memória (configurado em [`phpunit.xml`](phpunit.xml)), então não alteram o banco MySQL.

## Formatação

```bash
vendor/bin/pint
```
