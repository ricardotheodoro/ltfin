# LTFin

Aplicação administrativa para controle financeiro mensal, baseada em Laravel + Metronic.

## Instalação do sistema

### 1) Pré-requisitos

- PHP 7.4+ (recomendado PHP 8.x)
- Composer
- Node.js e NPM
- MySQL

### 2) Instalar dependências

```bash
composer install
npm install
```

### 3) Configurar ambiente

```bash
cp .env.example .env
```

No Windows (Prompt), use:

```bash
copy .env.example .env
```

Depois, ajuste as variáveis de banco no arquivo `.env`:

- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

### 4) Gerar chave da aplicação

```bash
php artisan key:generate
```

### 5) Criar estrutura do banco e dados iniciais

```bash
php artisan migrate:fresh --seed
```

### 6) Compilar assets front-end

```bash
npm run dev
```

### 7) Subir a aplicação

```bash
php artisan serve
```

Acesse no navegador: `http://localhost:8000`

## Uso das telas principais

Após login, utilize o menu lateral para navegar nas telas abaixo:

- **Dashboard** (`/index`): visão geral com indicadores principais.
- **Lançamentos Mensais** (`/monthly-periods`): cadastro e consulta dos lançamentos do mês.
- **Previsão Mensal** (`/monthly-forecasts`): planejamento e acompanhamento das previsões.
- **Relatórios > Gastos x Previsão Mensal** (`/reports/expenses-vs-forecast`): comparação entre realizado e previsto.
- **Cadastros > Categorias de Gastos** (`/expense-categories`): manutenção das categorias de despesas.
- **Cadastros > Categorias de Recebimento** (`/income-categories`): manutenção das categorias de receitas.
- **Minha Conta > Visão Geral** (`/account/overview`): dados de perfil do usuário logado.
- **Minha Conta > Configurações** (`/account/settings`): atualização de informações da conta e preferências.
