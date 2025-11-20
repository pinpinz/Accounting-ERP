<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        [$activeCompanyId, $companyIds] = $this->resolveCompanyContext($request);

        $baseQuery = Transaction::with(['company', 'account', 'user'])
            ->whereIn('company_id', $companyIds)
            ->latest('transacted_at');

        if ($request->filled('description')) {
            $baseQuery->where('description', 'like', '%'.$request->input('description').'%');
        }
        if ($request->filled('direction')) {
            $baseQuery->where('direction', $request->input('direction'));
        }
        if ($request->filled('account_type')) {
            $baseQuery->whereHas('account', fn ($query) => $query->where('type', $request->input('account_type')));
        }
        if ($request->filled('start_date')) {
            $baseQuery->whereDate('transacted_at', '>=', $request->date('start_date'));
        }
        if ($request->filled('end_date')) {
            $baseQuery->whereDate('transacted_at', '<=', $request->date('end_date'));
        }

        $transactions = $baseQuery->paginate(15)->withQueryString();

        $accounts = Account::with('company')
            ->whereIn('company_id', $companyIds)
            ->orderBy('name')
            ->get();

        return view('transactions.index', [
            'transactions' => $transactions,
            'accounts' => $accounts,
            'activeCompanyId' => $activeCompanyId,
            'filters' => $request->only(['description', 'direction', 'account_type', 'start_date', 'end_date']),
        ]);
    }

    public function create(Request $request): View
    {
        [, $companyIds] = $this->resolveCompanyContext($request);
        $accounts = Account::with('company')
            ->whereIn('company_id', $companyIds)
            ->orderBy('name')
            ->get();

        return view('transactions.create', [
            'accounts' => $accounts,
            'defaultCompanyId' => $companyIds->count() === 1 ? $companyIds->first() : null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        [$activeCompanyId, $companyIds] = $this->resolveCompanyContext($request);

        $data = $request->validate([
            'company_id' => ['required', 'integer'],
            'account_id' => ['required', 'integer'],
            'direction' => ['required', 'in:inflow,outflow'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'transacted_at' => ['required', 'date'],
        ]);

        if (! $companyIds->contains($data['company_id'])) {
            abort(403, 'Perusahaan tidak valid untuk pengguna ini.');
        }

        $account = Account::where('company_id', $data['company_id'])
            ->findOrFail($data['account_id']);

        Transaction::create([
            'company_id' => $data['company_id'],
            'account_id' => $account->id,
            'direction' => $data['direction'],
            'amount' => $data['amount'],
            'description' => $data['description'] ?? null,
            'transacted_at' => $data['transacted_at'],
            'user_id' => $request->user()->id,
        ]);

        Session::put('active_company_id', $activeCompanyId);

        return redirect()->route('transactions.index')->with('status', 'Transaksi berhasil disimpan.');
    }

    private function resolveCompanyContext(Request $request): array
    {
        $companies = $request->user()->companies()->orderBy('name')->get();
        $activeCompanyId = Session::get('active_company_id', $companies->first()?->id);
        $companyIds = $activeCompanyId === 'all' ? $companies->pluck('id') : collect([(int) $activeCompanyId]);

        return [$activeCompanyId, $companyIds];
    }
}
