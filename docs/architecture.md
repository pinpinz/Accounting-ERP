# Architecture & Implementation Guide

This document outlines how to assemble the requested ERP-style financial dashboard using **Laravel**, **PostgreSQL**, and **AdminLTE**. It is structured so you can implement the features once Composer/npm access is available.

## Core requirements
- **Multi-company**: five companies supported with consolidated view.
- **Auth & company gate**: login required; post-login company selector stored in session. Users only see companies they are assigned to.
- **Financial categories**: assets, liabilities, equity, income, expenses, with subcategories (e.g., operating expenses/beban). Use a Chart of Accounts (CoA) table.
- **Transactions & journals**: store transactions as journal entries to keep double-entry integrity.
- **Dashboards & filters**: filter by company, account type, subcategory, description, and date range.
- **Reports**: Profit & Loss, Balance Sheet, Trial Balance, Cash Flow (simplified/lajur kas).

## Proposed data model (tables)
- `companies` — id, name, code, currency, is_active.
- `users` — standard Laravel auth fields.
- `company_user` (pivot) — user_id, company_id, role (admin/accountant).
- `accounts` — id, company_id (nullable for shared CoA), code, name, type (asset/liability/equity/income/expense), category (e.g., operating expense/beban), parent_id (for hierarchy).
- `journal_entries` — id, company_id, entry_date, reference, description.
- `journal_lines` — journal_entry_id, account_id, debit, credit, description.
- `cash_movements` (optional shortcut for dashboards) — company_id, occurred_at, direction (in/out), amount, account_id, description.

## Laravel setup steps
1. **Bootstrap**: `composer create-project laravel/laravel .`
2. **Auth**: `php artisan breeze:install blade` (or Jetstream), migrate.
3. **AdminLTE**: `npm install admin-lte@^4` and import assets in `resources/js/app.js` and `resources/css/app.css`.
4. **DB config**: set `.env` to PostgreSQL (see below) and run migrations/seeders.

### Example `.env` keys for PostgreSQL
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=erp_finance
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

## Routing sketch
- `routes/web.php`
  - Auth routes (Breeze/Jetstream).
  - `/company/switch` — POST; stores `company_id` in session.
  - `/dashboard` — overview charts (income vs expense, category breakdown, cash trend).
  - `/transactions` — CRUD and filters by account type/category/description/date.
  - `/reports/profit-loss`, `/reports/balance-sheet`, `/reports/trial-balance`, `/reports/cash-flow`.

## Controller responsibilities
- **CompanySwitcherController**: show modal/dropdown of assigned companies; write selection to session; support "All companies" (consolidated).
- **DashboardController**: query aggregates per company or consolidated (joins on `journal_lines` grouped by account type/category).
- **TransactionController**: CRUD for journal entries & lines; ensures balanced debits/credits; tagging by category/description.
- **ReportController**: builds P&L, Balance Sheet, Trial Balance, Cash Flow queries.

## UI/layout notes
- Wrap views with AdminLTE layout (`resources/views/layouts/app.blade.php`).
- Add a topbar company selector dropdown; show consolidated/individual toggles.
- Use charting library (e.g., Chart.js) for income vs expense, cash trend, category breakdowns.
- Provide filters in dashboard and transaction list (company, account type, category, description, date range).

## Migrations outline
- `create_companies_table`
- `create_accounts_table`
- `create_journal_entries_table`
- `create_journal_lines_table`
- `create_company_user_pivot`

Seed initial data: five companies, baseline Chart of Accounts, demo transactions for charts.

## Reporting queries (high level)
- **Profit & Loss**: sum income (credit - debit) and expenses (debit - credit) per period, by company selection.
- **Balance Sheet**: compute assets, liabilities, equity from account balances.
- **Trial Balance**: list all accounts with period debit/credit and net balance; ensure totals match.
- **Cash Flow (lajur kas)**: filter cash/bank accounts and compute inflow/outflow by date.

## Next steps checklist
- [ ] Run `composer create-project laravel/laravel .` (networked environment).
- [ ] Install Breeze/Jetstream for auth & scaffolding.
- [ ] Wire AdminLTE layout and company selector UI.
- [ ] Implement migrations/models/controllers per data model.
- [ ] Build dashboards and report queries with Chart.js visuals.
- [ ] Add role-based access checks and session-scoped company filtering middleware.
