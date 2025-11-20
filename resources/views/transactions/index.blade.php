<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Transaksi</h4>
        <a class="btn btn-primary" href="{{ route('transactions.create') }}"><i class="fa fa-plus me-1"></i> Tambah Transaksi</a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form class="row g-3" method="GET">
                <div class="col-md-3">
                    <label class="form-label">Deskripsi</label>
                    <input type="text" name="description" value="{{ $filters['description'] ?? '' }}" class="form-control" placeholder="Cari deskripsi">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Arah</label>
                    <select name="direction" class="form-select">
                        <option value="">Semua</option>
                        <option value="inflow" @selected(($filters['direction'] ?? '') === 'inflow')>Inflow</option>
                        <option value="outflow" @selected(($filters['direction'] ?? '') === 'outflow')>Outflow</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jenis Akun</label>
                    <select name="account_type" class="form-select">
                        <option value="">Semua</option>
                        @foreach(['asset' => 'Aktiva', 'liability' => 'Pasiva', 'equity' => 'Ekuitas', 'income' => 'Pendapatan', 'expense' => 'Beban'] as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['account_type'] ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Dari</label>
                    <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sampai</label>
                    <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="form-control">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button class="btn btn-secondary w-100" type="submit">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Perusahaan</th>
                        <th>Akun</th>
                        <th>Jenis</th>
                        <th class="text-end">Nominal</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->transacted_at->format('d M Y') }}</td>
                            <td>{{ $transaction->company->name }}</td>
                            <td>{{ $transaction->account->name }} ({{ strtoupper($transaction->account->type) }})</td>
                            <td>
                                <span class="badge bg-{{ $transaction->direction === 'inflow' ? 'success' : 'danger' }}">{{ ucfirst($transaction->direction) }}</span>
                            </td>
                            <td class="text-end">Rp{{ number_format($transaction->amount, 2, ',', '.') }}</td>
                            <td>{{ $transaction->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada transaksi untuk filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $transactions->links() }}
        </div>
    </div>
</x-app-layout>
