<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Company;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        $companies = collect([
            ['name' => 'Alpha Holdings', 'code' => 'ALPHA'],
            ['name' => 'Beta Manufacturing', 'code' => 'BETA'],
            ['name' => 'Gamma Services', 'code' => 'GAMMA'],
            ['name' => 'Delta Trading', 'code' => 'DELTA'],
            ['name' => 'Epsilon Ventures', 'code' => 'EPS'],
        ])->map(fn ($data) => Company::updateOrCreate(
            ['code' => $data['code']],
            ['name' => $data['name'], 'description' => $data['name'].' entity']
        ));

        $user->companies()->sync($companies->pluck('id'));

        $accountTemplates = [
            ['name' => 'Kas & Bank', 'code' => '1010', 'type' => 'asset', 'category' => 'cash'],
            ['name' => 'Piutang Usaha', 'code' => '1100', 'type' => 'asset', 'category' => 'receivable'],
            ['name' => 'Utang Usaha', 'code' => '2100', 'type' => 'liability', 'category' => 'payable'],
            ['name' => 'Modal', 'code' => '3100', 'type' => 'equity', 'category' => 'equity'],
            ['name' => 'Penjualan', 'code' => '4100', 'type' => 'income', 'category' => 'sales'],
            ['name' => 'Beban Operasional', 'code' => '5100', 'type' => 'expense', 'category' => 'operational'],
            ['name' => 'Beban Gaji', 'code' => '5200', 'type' => 'expense', 'category' => 'payroll'],
        ];

        $companies->each(function (Company $company) use ($accountTemplates, $user) {
            $accounts = collect($accountTemplates)->map(fn ($template) => Account::updateOrCreate(
                ['company_id' => $company->id, 'code' => $template['code']],
                $template + ['company_id' => $company->id]
            ));

            $start = Carbon::now()->startOfMonth();
            foreach (range(0, 10) as $offset) {
                $date = $start->copy()->subDays($offset * 2);
                $incomeAccount = $accounts->firstWhere('type', 'income');
                $expenseAccount = $accounts->firstWhere('category', 'operational');
                $cashAccount = $accounts->firstWhere('category', 'cash');

                Transaction::create([
                    'company_id' => $company->id,
                    'account_id' => $incomeAccount->id,
                    'user_id' => $user->id,
                    'direction' => 'inflow',
                    'amount' => rand(5_000_00, 10_000_00) / 100,
                    'description' => 'Pendapatan penjualan',
                    'transacted_at' => $date->copy()->addHours(10),
                ]);

                Transaction::create([
                    'company_id' => $company->id,
                    'account_id' => $expenseAccount->id,
                    'user_id' => $user->id,
                    'direction' => 'outflow',
                    'amount' => rand(1_500_00, 3_000_00) / 100,
                    'description' => 'Beban operasional',
                    'transacted_at' => $date->copy()->addHours(14),
                ]);

                Transaction::create([
                    'company_id' => $company->id,
                    'account_id' => $cashAccount->id,
                    'user_id' => $user->id,
                    'direction' => 'inflow',
                    'amount' => rand(500_00, 1_000_00) / 100,
                    'description' => 'Penyesuaian kas',
                    'transacted_at' => $date->copy()->addHours(16),
                ]);
            }
        });
    }
}
