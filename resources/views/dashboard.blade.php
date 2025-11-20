<x-app-layout>
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted text-uppercase fw-semibold">Total Inflow</div>
                    <div class="h3 mb-0 text-success">Rp{{ number_format($totalInflow, 2, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted text-uppercase fw-semibold">Total Outflow</div>
                    <div class="h3 mb-0 text-danger">Rp{{ number_format($totalOutflow, 2, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted text-uppercase fw-semibold">Saldo Bersih</div>
                    <div class="h3 mb-0">Rp{{ number_format($net, 2, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted text-uppercase fw-semibold">Perusahaan Aktif</div>
                    <div class="h5 mb-0">{{ $activeCompanyId === 'all' ? 'Konsolidasi '.$companies->count().' Perusahaan' : optional($companies->firstWhere('id', $activeCompanyId))->name }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Ringkasan Arus Keuangan</h5>
                <form class="d-flex gap-2" method="GET">
                    <input type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}" class="form-control form-control-sm">
                    <input type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}" class="form-control form-control-sm">
                    <input type="text" name="search" placeholder="Cari deskripsi" value="{{ $filters['search'] ?? '' }}" class="form-control form-control-sm">
                    <button class="btn btn-primary btn-sm" type="submit">Filter</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-lg-8">
                    <canvas id="trendChart" height="110"></canvas>
                </div>
                <div class="col-lg-4">
                    <h6 class="fw-semibold">Inflow per Kategori</h6>
                    <canvas id="incomeChart" height="90"></canvas>
                    <h6 class="fw-semibold mt-4">Outflow per Kategori</h6>
                    <canvas id="expenseChart" height="90"></canvas>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    const trendData = @json($trend);
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: trendData.map(item => item.date),
            datasets: [
                {
                    label: 'Inflow',
                    data: trendData.map(item => item.income),
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22,163,74,0.15)',
                    tension: 0.3,
                    fill: true,
                },
                {
                    label: 'Outflow',
                    data: trendData.map(item => item.expense),
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220,38,38,0.15)',
                    tension: 0.3,
                    fill: true,
                },
            ],
        },
        options: {
            plugins: { legend: { position: 'bottom' } },
            scales: {
                y: { beginAtZero: true },
            }
        }
    });

    const incomeCtx = document.getElementById('incomeChart').getContext('2d');
    const incomeData = @json($incomeByCategory);
    new Chart(incomeCtx, {
        type: 'doughnut',
        data: {
            labels: incomeData.map(item => item.label ?? 'Lainnya'),
            datasets: [{
                data: incomeData.map(item => item.total),
                backgroundColor: ['#0ea5e9','#22c55e','#a855f7','#f97316','#6366f1','#14b8a6'],
            }]
        },
        options: { plugins: { legend: { position: 'bottom' } } }
    });

    const expenseCtx = document.getElementById('expenseChart').getContext('2d');
    const expenseData = @json($expenseByCategory);
    new Chart(expenseCtx, {
        type: 'doughnut',
        data: {
            labels: expenseData.map(item => item.label ?? 'Lainnya'),
            datasets: [{
                data: expenseData.map(item => item.total),
                backgroundColor: ['#fbbf24','#ef4444','#10b981','#6366f1','#8b5cf6','#14b8a6'],
            }]
        },
        options: { plugins: { legend: { position: 'bottom' } } }
    });
</script>
@endpush
