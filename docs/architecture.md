# Architecture & Implementation Guide

This document outlines the implemented ERP-style financial dashboard using **Laravel 12**, **PostgreSQL**, and **AdminLTE**. The repository already includes the Laravel scaffold, AdminLTE layout, multi-company flows, and demo seed data; use this as a reference when extending the system.

## Core requirements
- **Multi-company**: five companies supported with consolidated view.
- **Auth & company gate**: login required; post-login company selector stored in session. Users only see companies they are assigned to.
- **Financial categories**: assets, liabilities, equity, income, expenses, with subcategories (e.g., operating expenses/beban). Use a Chart of Accounts (CoA) table.
- **Transactions & journals**: store transactions as journal entries to keep double-entry integrity.
- **Dashboards & filters**: filter by company, account type, subcategory, description, and date range.
- **Reports**: P&L, Trial Balance, Balance Sheet, and simplified Cash Flow/Laporan Lajur Kas.

## Data model (implemented)
- `companies`: id, name, currency, settings.
- `accounts`: id, company_id, code, name, type (asset/liability/equity/income/expense), subtype (e.g., operating_expense), parent_id for hierarchy.
- `transactions`: id, company_id, account_id, direction (inflow/outflow), amount, description, occurred_at.
- `company_user`: pivot to link users to accessible companies.
- `users`: Laravel Breeze auth scaffold with profile info.

## Key flows
- **Auth**: Laravel Breeze handles login, registration, password reset, and verification.
- **Company selection**: Middleware `EnsureCompanySelected` redirects authenticated users to `/companies/select` until they choose a company (or "All Companies" consolidation). Selection stored in session.
- **Dashboard**: `DashboardController@index` pulls totals and Chart.js datasets for inflow/outflow trends and top categories, scoped by selected company/consolidation.
- **Transactions**: CRUD via `TransactionController` with filters for date range, account type, direction, and description search. Uses AdminLTE tables and forms.
- **Reports**: `ReportController` builds P&L, Trial Balance, Balance Sheet, and Cash Flow (direct) aggregations using query builders grouped by account type.
- **Layouts**: AdminLTE-based Blade layouts in `resources/views/layouts` with navigation that surfaces company selector, dashboard, transactions, and reports.

## Frontend stack
- Blade + AdminLTE 4 styling.
- Chart.js for dashboards; Alpine for simple interactions.
- Vite handles bundling; Tailwind is available for utility classes alongside AdminLTE.

## Testing & seeding
- `php artisan migrate --seed` loads five demo companies, baseline accounts, sample transactions, and an admin user (`admin@example.com` / `password`).
- Feature tests cover auth flows; add tests for reports/transactions as the system grows.

## Deployment notes
- Target PostgreSQL; update `.env` accordingly.
- For production, run `php artisan config:cache && php artisan route:cache && npm run build`.
- Use Supervisor or similar to keep queue workers active if you enable jobs.

