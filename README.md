# Accounting ERP (Laravel + AdminLTE + PostgreSQL)

A design blueprint for a multi-company financial dashboard and reporting system. The target stack uses Laravel, PostgreSQL, and the AdminLTE UI theme. The system is intended to handle five (or more) companies with consolidated and per-entity financial insights.

## Goals
- Secure authentication and company selection immediately after login.
- Multi-company transaction management with income/expense categorization (assets, liabilities, equity, income, expense, and subcategories like operating expenses/beban).
- Interactive dashboards with filters by category, description, and date ranges.
- Financial statements: Profit & Loss, Trial Balance, Cash Flow (simplified), and Balance Sheet.
- Attractive, responsive UI using AdminLTE.

## Current state
Network restrictions in this environment prevented pulling Composer packages (Laravel skeleton). The repository currently contains documentation and scaffolding guidance so you can initialize the codebase locally or in a CI runner with network access.

### Why nothing appears on GitHub yet
- No Git remote is configured in this workspace, so commits stay local by default.
- Use the included helper script to configure a remote and push:
  ```bash
  export GIT_REMOTE_URL="https://github.com/<owner>/<repo>.git"  # or git@github.com:...git
  # Optional overrides
  # export GIT_REMOTE_NAME=origin
  # export GIT_BRANCH=work

  ./scripts/push-to-remote.sh
  ```
- The script adds/updates the remote and pushes the current branch (default: `work`).
- Ensure your SSH key or token is available in the environment where you run the push.

## Quick start (when network access is available)
1. **Clone & scaffold**
   ```bash
   git clone <repo-url>
   cd Accounting-ERP
   composer create-project laravel/laravel .
   ```
2. **Install UI/auth starter (example with Breeze + Blade)**
   ```bash
   php artisan breeze:install blade
   npm install && npm run build
   ```
3. **Add AdminLTE**
   - Install via npm: `npm install admin-lte@^4`.
   - Import the AdminLTE CSS/JS in `resources/js/app.js` and `resources/css/app.css`, then re-run `npm run build`.
4. **Database**
   - Use PostgreSQL; set credentials in `.env` (see `docs/architecture.md`).
   - Run migrations and seeders after creating the schema: `php artisan migrate --seed`.

## Key features to implement
- **Company switcher** presented after login; allow selecting one company or a consolidated view.
- **Transactions** with typed accounts (asset, liability, equity, income, expense) and subcategories (e.g., operating expense/beban). Support description search and date/category filters.
- **Dashboards** with charts for inflow/outflow, category breakdowns, and company toggles.
- **Reports**: Profit & Loss, Trial Balance, Balance Sheet, and simplified Cash Flow/Laporan Lajur Kas.
- **User roles**: admin vs standard accountant, with company-level access control.

## Folder map (expected after scaffolding)
- `app/Models` — Company, Account, Transaction, JournalEntry, UserCompany pivot.
- `app/Http/Controllers` — DashboardController, TransactionController, ReportController, CompanySwitcherController.
- `database/migrations` — tables for companies, accounts (CoA), transactions/journal entries, user-company pivot.
- `resources/views` — Blade layouts using AdminLTE, dashboard pages, transaction forms, report views.
- `routes/web.php` — auth routes (Breeze/Fortify), dashboard, transactions, reports, company switcher middleware.

For deeper design details, see `docs/architecture.md`.
