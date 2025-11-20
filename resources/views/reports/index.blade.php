<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Laporan Keuangan</h4>
        <form class="d-flex gap-2" method="GET">
            <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="form-control form-control-sm">
            <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="form-control form-control-sm">
            <button class="btn btn-secondary btn-sm" type="submit">Filter</button>
        </form>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Laba Rugi</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Pendapatan</span>
                        <strong>Rp{{ number_format($profitLoss['income'], 2, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Beban</span>
                        <strong class="text-danger">Rp{{ number_format($profitLoss['expenses'], 2, ',', '.') }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-semibold">Laba / Rugi Bersih</span>
                        <strong class="text-primary">Rp{{ number_format($profitLoss['net'], 2, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Arus Kas Sederhana</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Kas Masuk</span>
                        <strong class="text-success">Rp{{ number_format($cashFlow['inflow'], 2, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Kas Keluar</span>
                        <strong class="text-danger">Rp{{ number_format($cashFlow['outflow'], 2, ',', '.') }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-semibold">Kenaikan Bersih</span>
                        <strong class="text-primary">Rp{{ number_format($cashFlow['net'], 2, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Neraca Saldo</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Akun</th>
                                    <th class="text-end">Debit</th>
                                    <th class="text-end">Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trialBalance['accounts'] as $account)
                                    <tr>
                                        <td>{{ $account->code }}</td>
                                        <td>{{ $account->name }}</td>
                                        <td class="text-end">Rp{{ number_format($account->debit, 2, ',', '.') }}</td>
                                        <td class="text-end">Rp{{ number_format($account->credit, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-semibold">
                                    <td colspan="2">Total</td>
                                    <td class="text-end">Rp{{ number_format($trialBalance['totals']['debit'], 2, ',', '.') }}</td>
                                    <td class="text-end">Rp{{ number_format($trialBalance['totals']['credit'], 2, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Neraca (Balance Sheet)</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Aktiva</span>
                        <strong>Rp{{ number_format($balanceSheet['assets'], 2, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Pasiva</span>
                        <strong>Rp{{ number_format($balanceSheet['liabilities'], 2, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Ekuitas</span>
                        <strong>Rp{{ number_format($balanceSheet['equity'], 2, ',', '.') }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-semibold">Pasiva + Ekuitas</span>
                        <strong class="text-primary">Rp{{ number_format($balanceSheet['liabilities'] + $balanceSheet['equity'], 2, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
