<x-app-layout>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pilih Perusahaan</h5>
                    <span class="text-muted">Multi-perusahaan siap dikonsolidasi</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('companies.store') }}" class="vstack gap-3">
                        @csrf
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="company_id" id="company_all" value="all" {{ ($consolidated ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="company_all">
                                Konsolidasi Semua Perusahaan
                            </label>
                            <div class="small text-muted">Gabungkan data keuangan dari seluruh entitas.</div>
                        </div>
                        <hr>
                        @foreach($companies as $company)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="company_id" id="company_{{ $company->id }}" value="{{ $company->id }}" {{ ($activeCompanyId ?? null) == $company->id ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="company_{{ $company->id }}">
                                    {{ $company->name }}
                                </label>
                                <div class="small text-muted">Kode: {{ $company->code }}</div>
                            </div>
                        @endforeach
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">Simpan Pilihan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
