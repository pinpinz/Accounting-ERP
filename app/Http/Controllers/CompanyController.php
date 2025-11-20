<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function select(Request $request): View
    {
        $companies = $request->user()->companies()->orderBy('name')->get();

        return view('companies.select', [
            'companies' => $companies,
            'activeCompanyId' => Session::get('active_company_id'),
            'consolidated' => Session::get('active_company_id') === 'all',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'company_id' => ['required'],
        ]);

        $companyId = $request->input('company_id');
        if ($companyId !== 'all') {
            $companyId = (int) $companyId;
            $company = $request->user()->companies()->where('companies.id', $companyId)->firstOrFail();
            Session::put('active_company_id', $company->id);
        } else {
            Session::put('active_company_id', 'all');
        }

        return redirect()->route('dashboard')->with('status', 'Perusahaan aktif diperbarui.');
    }
}
