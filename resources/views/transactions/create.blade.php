<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="{{ route('transactions.index') }}" class="text-decoration-none"><i class="fa fa-arrow-left me-2"></i>Kembali</a>
            <h4 class="mb-0 mt-2">Tambah Transaksi</h4>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('transactions.store') }}" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Perusahaan</label>
                    <select name="company_id" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        @foreach($accounts->groupBy('company_id') as $companyId => $companyAccounts)
                            <option value="{{ $companyId }}" @selected($defaultCompanyId === $companyId)>{{ optional($companyAccounts->first()->company)->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Akun</label>
                    <select name="account_id" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" data-company="{{ $account->company_id }}">{{ $account->name }} ({{ strtoupper($account->type) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jenis</label>
                    <select name="direction" class="form-select" required>
                        <option value="inflow">Inflow</option>
                        <option value="outflow">Outflow</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nominal</label>
                    <input type="number" step="0.01" name="amount" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal</label>
                    <input type="datetime-local" name="transacted_at" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" rows="3" class="form-control" placeholder="Catatan singkat"></textarea>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-save me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    const companySelect = document.querySelector('select[name="company_id"]');
    const accountSelect = document.querySelector('select[name="account_id"]');

    function filterAccounts() {
        const selectedCompany = companySelect.value;
        Array.from(accountSelect.options).forEach(option => {
            if (!option.value) return;
            option.hidden = selectedCompany && option.dataset.company !== selectedCompany;
        });
    }

    companySelect.addEventListener('change', filterAccounts);
    filterAccounts();
</script>
@endpush
