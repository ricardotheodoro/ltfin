# AGENTS.md — Guia para agentes de IA neste projeto

> Este arquivo é lido automaticamente pelo Cursor e por outros agentes de IA.
> Mantenha-o curto, opinativo e atualizado.

## 1. Visão geral

- **Projeto:** LTFin — aplicação administrativa construída sobre o tema
  [Metronic 8](https://keenthemes.com/metronic) (KeenThemes).
- **Stack:**
  - PHP 7.4+/8.x · Laravel 8 (`laravel/framework ^8.12`)
  - Blade · Bootstrap 5.1 · SASS · jQuery 3.6 (legado do tema)
  - Laravel Mix (Webpack) para build de assets
  - MySQL (via configuração `.env`)
- **Pacotes-chave:**
  - `anlutro/l4-settings` — store de settings (chave/valor) em DB
  - `spatie/laravel-permission` — RBAC (roles/permissions)
  - `spatie/laravel-activitylog` — auditoria
  - `yajra/laravel-datatables-*` — tabelas server-side
  - `laravel/breeze` — scaffolding de auth (já aplicado)

## 2. Estrutura relevante

```
app/
  Http/
    Controllers/         # PagesController, UsersController, Auth/, Account/, Logs/
    Requests/            # FormRequests (Auth/, Account/)
    Middleware/
  Models/                # User, UserInfo
  Providers/
  Rules/                 # MatchOldPassword
  View/Components/       # BaseLayout, AuthLayout (componentes de layout Blade)
  helpers.php            # registrado em composer.json (autoload.files)
config/
  demo1..demo7/          # 7 variações de tema: general.php, menu.php, pages.php
  global/                # general/menu/pages globais
  settings.php           # store ativa é 'database', default demo = 'demo1'
database/
  migrations/            # users, permissions (spatie), activity_log, settings, user_infos
  seeders/               # Database, Roles, Permissions, Users
  factories/             # UserFactory
resources/
  views/
    auth/                # login, register, forgot/confirm/verify password, layout
    base/                # base.blade.php (layout raiz)
    layout/              # layouts compostos (header/footer/aside)
    pages/               # páginas reais da app
    partials/            # widgets, charts, tables, explore, general, etc.
    inner.blade.php
  sass/                  # estilos custom (compilados pelo Mix)
  js/                    # JS custom
  _keenthemes/           # FONTES do tema KeenThemes (NÃO EDITAR — vendido)
routes/
  web.php · auth.php · api.php · console.php · channels.php
tests/
  Feature/               # AuthenticationTest, RegistrationTest, SystemLogTest, ...
  Unit/                  # ExampleTest
public/                  # Saída do Mix; demoX/, assets/, css/, js/, media/, plugins/
storage/                 # logs, framework/, app/, settings.json
```

## 3. Como rodar localmente

```bash
composer install
npm install
cp .env.example .env             # Linux/macOS
# copy .env.example .env         # Windows

php artisan key:generate
php artisan migrate:fresh --seed # cria schema + seeds (Roles, Permissions, Users)

npm run dev                      # compila assets via Mix
php artisan serve                # http://localhost:8000
```

### Watch / hot reload de assets

```bash
npm run watch       # rebuild on change
npm run hot         # HMR
```

### Build de produção (todas as variações de tema)

```bash
make production     # roda npm run prod para demo1..demo7 (RTL/dark mode)
```

## 4. Comandos essenciais

| Tarefa                       | Comando                                       |
| ---------------------------- | --------------------------------------------- |
| Subir servidor               | `php artisan serve`                           |
| Migrations + seed limpos     | `php artisan migrate:fresh --seed`            |
| Rodar testes                 | `php artisan test` ou `vendor/bin/phpunit`    |
| Rodar 1 teste                | `php artisan test --filter=NomeDoMetodo`      |
| Limpar caches                | `php artisan optimize:clear`                  |
| Tinker (REPL)                | `php artisan tinker`                          |
| Gerar IDE helper             | `php artisan ide-helper:generate && ide-helper:meta` |
| Build assets (dev/prod)      | `npm run dev` / `npm run prod`                |
| Watch assets                 | `npm run watch`                               |

## 5. Convenções de código

### PHP (Laravel)

- **PSR-12** + preset **Laravel** do StyleCI (`.styleci.yml`).
- Indentação: **4 espaços** (`.editorconfig`).
- Validação **sempre via `FormRequest`** (ver `app/Http/Requests/`),
  nunca `$request->validate(...)` inline em controllers.
- Controllers finos: orquestram, delegam para Services/Actions/Models.
- Eloquent: use **relacionamentos** e **scopes** em vez de queries cruas.
- Mutators/Accessors quando houver transformação de dados.
- Para **permissões**, use sempre `spatie/laravel-permission`
  (middleware `permission:` ou `$user->can('...')`).
- Para **auditoria**, registre via `spatie/laravel-activitylog`
  (já configurado pela migration `add_event_column_to_activity_log_table`).
- Para **settings da aplicação**, use o facade do `anlutro/l4-settings`
  (`Settings::get('demo', 'demo1')`).
- Nomenclatura:
  - Controllers no plural: `UsersController`, `PostsController`.
  - Models no singular: `User`, `Post`.
  - Migrations: `YYYY_MM_DD_HHMMSS_descricao_em_snake_case.php`.
  - FormRequests: `<Acao><Recurso>Request` (ex.: `StoreUserRequest`).

### Blade

- Use **`x-` components** quando faz sentido reusar (ver `app/View/Components/`).
- Para layout, estenda `resources/views/base/base.blade.php` ou use
  `<x-base-layout>` / `<x-auth-layout>`.
- Partials começam com `_` e ficam em `resources/views/partials/...`.
- **Não inline scripts grandes**: use `@push('scripts')` / `@stack('scripts')`.
- **Não inline CSS**: adicione em `resources/sass/` e recompile.
- Para tabelas server-side, prefira `yajra/laravel-datatables`
  (já há partials em `partials/widgets/tables/`).
- Use `__('...')` para strings traduzíveis (`resources/lang/en/`).

### JS/SASS

- Arquivos custom em `resources/js/` e `resources/sass/`.
- **NUNCA editar** `resources/_keenthemes/`, `public/assets/`,
  `public/plugins/`, `public/demo*/` — são gerados ou pertencem ao tema vendido.
- O bundler é o **Mix** (`webpack.mix.js`). Após mudanças, rodar `npm run dev`.

### Tema ativo

- A demo ativa é controlada pela setting `'demo'` (default `'demo1'`).
- Cada demo tem seu próprio `general.php`, `menu.php`, `pages.php` em
  `config/demoN/`. Para adicionar uma página ao menu, edite `menu.php`
  da(s) demo(s) relevante(s) e mapeie em `pages.php`.

## 6. Testes

- Framework: **PHPUnit 9** (não Pest).
- Suítes definidas em `phpunit.xml`: `Unit/` e `Feature/`.
- Padrão **Arrange-Act-Assert**.
- Use `RefreshDatabase` em Feature tests que tocam o DB.
- Factories em `database/factories/` (já existe `UserFactory`).
- Auth helpers: `$this->actingAs($user)` ou `$this->postJson(...)`.
- Para evitar flakiness, **não** dependa do `demo` ativo nem de tema em testes.

## 7. Segurança e dados sensíveis

- **NUNCA** comitar `.env`, chaves em `storage/*.key`, ou dumps do banco.
- **NUNCA** usar `innerHTML` ou `{!! !!}` com input do usuário sem
  passar por `e()` / sanitização explícita.
- Senhas: sempre via `Hash::make()` (já é o padrão do Breeze).
- Rate limiting de login já está em `LoginRequest` (5 tentativas).

## 8. O que **NÃO** mexer

- `public/assets/`, `public/css/`, `public/js/`, `public/media/`,
  `public/plugins/`, `public/demo*/` — gerados pelo Mix / tema.
- `resources/_keenthemes/` — fonte original do tema KeenThemes.
- `vendor/`, `node_modules/`, `bootstrap/cache/`.
- Migrations já aplicadas em produção — crie novas em vez de editar.

## 9. Estilo de resposta esperado do agente

- Comunicar em **português brasileiro** com o usuário.
- Antes de mudanças amplas (multi-arquivo, refactors, novos pacotes),
  **mostrar plano** e pedir confirmação.
- Ao adicionar um pacote Composer/NPM, justificar a escolha e a versão.
- Quando criar/alterar rotas, atualizar:
  1. arquivo de rota (`routes/web.php` ou `routes/api.php`)
  2. controller correspondente
  3. menu da demo ativa (`config/demoN/menu.php`) quando for página
  4. testes em `tests/Feature/`
- Após editar PHP relevante, sugerir rodar `php artisan test`.
- Após editar SASS/JS, sugerir rodar `npm run dev`.
