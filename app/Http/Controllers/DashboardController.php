<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $userCompanies = $request->user()->companies()->orderBy('name')->get();
        $activeCompanyId = Session::get('active_company_id', $userCompanies->first()?->id);
        if ($activeCompanyId === null) {
            abort(403, 'Tidak ada perusahaan yang tersedia untuk pengguna ini.');
        }

        $companyIds = $activeCompanyId === 'all' ? $userCompanies->pluck('id') : collect([(int) $activeCompanyId]);

        $baseQuery = Transaction::with(['company', 'account'])
            ->whereIn('company_id', $companyIds);

        if ($request->filled('start_date')) {
            $baseQuery->whereDate('transacted_at', '>=', $request->date('start_date'));
        }
        if ($request->filled('end_date')) {
            $baseQuery->whereDate('transacted_at', '<=', $request->date('end_date'));
        }
        if ($request->filled('search')) {
            $baseQuery->where('description', 'like', '%'.$request->input('search').'%');
        }

        $totalInflow = (clone $baseQuery)->where('direction', 'inflow')->sum('amount');
        $totalOutflow = (clone $baseQuery)->where('direction', 'outflow')->sum('amount');
        $net = $totalInflow - $totalOutflow;

        $incomeByCategory = (clone $baseQuery)
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->selectRaw('accounts.category as label, sum(transactions.amount) as total')
            ->where('transactions.direction', 'inflow')
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $expenseByCategory = (clone $baseQuery)
            ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->selectRaw('accounts.category as label, sum(transactions.amount) as total')
            ->where('transactions.direction', 'outflow')
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $trend = (clone $baseQuery)
            ->selectRaw('date(transacted_at) as date, sum(case when direction = "inflow" then amount else 0 end) as income, sum(case when direction = "outflow" then amount else 0 end) as expense')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('dashboard', [
            'companies' => $userCompanies,
            'activeCompanyId' => $activeCompanyId,
            'totalInflow' => $totalInflow,
            'totalOutflow' => $totalOutflow,
            'net' => $net,
            'incomeByCategory' => $incomeByCategory,
            'expenseByCategory' => $expenseByCategory,
            'trend' => $trend,
            'filters' => $request->only(['start_date', 'end_date', 'search']),
        ]);
    }
}
