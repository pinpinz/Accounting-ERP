<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        [$activeCompanyId, $companyIds] = $this->resolveCompanyContext($request);

        $baseQuery = Transaction::with('account')
            ->whereIn('company_id', $companyIds);

        if ($request->filled('start_date')) {
            $baseQuery->whereDate('transacted_at', '>=', $request->date('start_date'));
        }
        if ($request->filled('end_date')) {
            $baseQuery->whereDate('transacted_at', '<=', $request->date('end_date'));
        }

        $profitLoss = $this->buildProfitLoss(clone $baseQuery);
        $trialBalance = $this->buildTrialBalance(clone $baseQuery);
        $balanceSheet = $this->buildBalanceSheet(clone $baseQuery);
        $cashFlow = $this->buildCashFlow(clone $baseQuery);

        return view('reports.index', [
            'profitLoss' => $profitLoss,
            'trialBalance' => $trialBalance,
            'balanceSheet' => $balanceSheet,
            'cashFlow' => $cashFlow,
            'filters' => $request->only(['start_date', 'end_date']),
            'activeCompanyId' => $activeCompanyId,
        ]);
    }

    private function buildProfitLoss($query): array
    {
        $income = (clone $query)
            ->whereHas('account', fn ($q) => $q->where('type', 'income'))
            ->where('direction', 'inflow')
            ->sum('amount');

        $expenses = (clone $query)
            ->whereHas('account', fn ($q) => $q->where('type', 'expense'))
            ->where('direction', 'outflow')
            ->sum('amount');

        return [
            'income' => $income,
            'expenses' => $expenses,
            'net' => $income - $expenses,
        ];
    }

    private function buildTrialBalance($query): array
    {
        $accounts = (clone $query)
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->selectRaw('accounts.name, accounts.code, accounts.type, sum(case when direction = "outflow" then transactions.amount else 0 end) as debit, sum(case when direction = "inflow" then transactions.amount else 0 end) as credit')
            ->groupBy('accounts.name', 'accounts.code', 'accounts.type')
            ->orderBy('accounts.code')
            ->get();

        $totals = [
            'debit' => $accounts->sum('debit'),
            'credit' => $accounts->sum('credit'),
        ];

        return compact('accounts', 'totals');
    }

    private function buildBalanceSheet($query): array
    {
        $accounts = (clone $query)
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->selectRaw('accounts.type, sum(case when direction = "inflow" then amount else -amount end) as balance')
            ->groupBy('accounts.type')
            ->pluck('balance', 'accounts.type');

        return [
            'assets' => (float) ($accounts['asset'] ?? 0),
            'liabilities' => (float) ($accounts['liability'] ?? 0),
            'equity' => (float) ($accounts['equity'] ?? 0),
        ];
    }

    private function buildCashFlow($query): array
    {
        $inflow = (clone $query)->where('direction', 'inflow')->sum('amount');
        $outflow = (clone $query)->where('direction', 'outflow')->sum('amount');

        return [
            'inflow' => $inflow,
            'outflow' => $outflow,
            'net' => $inflow - $outflow,
        ];
    }

    private function resolveCompanyContext(Request $request): array
    {
        $companies = $request->user()->companies()->orderBy('name')->get();
        $activeCompanyId = Session::get('active_company_id', $companies->first()?->id);
        $companyIds = $activeCompanyId === 'all' ? $companies->pluck('id') : collect([(int) $activeCompanyId]);

        return [$activeCompanyId, $companyIds];
    }
}
