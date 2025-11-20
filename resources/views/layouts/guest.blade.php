<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Accounting ERP') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        :root {
            --erp-primary: #2563eb;
            --erp-accent: #1e293b;
            --erp-soft: #e0e7ff;
        }
        body.auth-body {
            font-family: 'Figtree', sans-serif;
            min-height: 100vh;
            background: radial-gradient(circle at 20% 20%, rgba(64, 123, 255, 0.18), transparent 32%),
                radial-gradient(circle at 80% 0%, rgba(14, 116, 144, 0.16), transparent 25%),
                linear-gradient(135deg, #f8fafc 0%, #eef2ff 45%, #f8fafc 100%);
            position: relative;
            color: #0f172a;
        }
        .auth-accent-circle {
            position: absolute;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            filter: blur(70px);
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.16), rgba(59, 130, 246, 0.28));
            top: -120px;
            right: -60px;
            z-index: 0;
        }
        .auth-shell {
            position: relative;
            z-index: 1;
            padding: 32px 16px 48px;
        }
        .auth-header {
            max-width: 1080px;
            margin: 0 auto 18px;
        }
        .auth-card {
            max-width: 1080px;
            margin: 0 auto;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            backdrop-filter: blur(6px);
        }
        .auth-hero {
            background: radial-gradient(circle at 30% 20%, rgba(255, 255, 255, 0.12), transparent 35%),
                linear-gradient(135deg, #0f172a, #1e293b);
            color: #e2e8f0;
        }
        .auth-hero .badge {
            background: rgba(255, 255, 255, 0.08);
            color: #e2e8f0;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .auth-hero .feature {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .auth-hero .feature i {
            color: #93c5fd;
        }
        .auth-form-panel {
            background: #fff;
        }
        .auth-form-panel .welcome-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #eff6ff;
            color: #1d4ed8;
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .auth-form-panel .brand {
            font-weight: 800;
            color: var(--erp-primary);
        }
        .auth-footer {
            text-align: center;
            margin-top: 18px;
            color: #64748b;
            font-size: 0.9rem;
        }
        @media (max-width: 991px) {
            .auth-hero { display: none; }
            .auth-card { border-radius: 18px; }
        }
    </style>
</head>
<body class="auth-body">
<div class="auth-accent-circle"></div>
<div class="auth-shell container">
    <div class="auth-header d-flex justify-content-between align-items-center">
        <div>
            <div class="text-muted small">ERP Keuangan • Multi-Perusahaan</div>
            <div class="h4 fw-bold brand mb-0">{{ config('app.name', 'Accounting ERP') }}</div>
        </div>
        <div class="d-flex align-items-center gap-2 text-muted small">
            <i class="fa-solid fa-shield-check text-success"></i>
            <span>Login aman dengan AdminLTE + Laravel 12</span>
        </div>
    </div>

    <div class="auth-card row g-0 shadow-lg">
        <div class="col-lg-5 p-4 p-md-5 auth-hero">
            <div class="badge rounded-pill mb-3"><i class="fa-solid fa-building-columns me-2"></i>5 Perusahaan • 1 Dasbor</div>
            <h3 class="fw-bold mb-3">Pantau arus kas, laba rugi, dan saldo real-time</h3>
            <p class="text-secondary-emphasis mb-4">Filter transaksi berdasarkan aktiva/pasiva, beban, proyek, atau deskripsi. Ringkas, cepat, dan siap dipakai.</p>
            <div class="vstack gap-3">
                <div class="feature">
                    <i class="fa-solid fa-chart-line mt-1"></i>
                    <div>
                        <div class="fw-semibold">Dashboard grafis</div>
                        <div class="small text-secondary">In/outflow, top akun, dan tren bulanan.</div>
                    </div>
                </div>
                <div class="feature">
                    <i class="fa-solid fa-building-user mt-1"></i>
                    <div>
                        <div class="fw-semibold">Multi-perusahaan</div>
                        <div class="small text-secondary">Pilih satu atau konsolidasi 5 entitas Anda.</div>
                    </div>
                </div>
                <div class="feature">
                    <i class="fa-solid fa-file-invoice-dollar mt-1"></i>
                    <div>
                        <div class="fw-semibold">Report cepat</div>
                        <div class="small text-secondary">Laba rugi, neraca saldo, balance sheet, dan lajur kas.</div>
                    </div>
                </div>
            </div>
            <div class="mt-4 d-flex align-items-center gap-3">
                <div class="avatar bg-white text-dark fw-bold rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 46px; height: 46px;">
                    <i class="fa-solid fa-key"></i>
                </div>
                <div>
                    <div class="fw-semibold">Akun demo siap pakai</div>
                    <div class="small">admin@example.com / password</div>
                </div>
            </div>
        </div>
        <div class="col-lg-7 p-4 p-md-5 auth-form-panel">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="welcome-tag"><i class="fa-solid fa-sparkles"></i><span>Selamat datang</span></div>
                <div class="text-muted small"><i class="fa-regular fa-clock me-1"></i>Akses 24/7</div>
            </div>
            {{ $slot }}
        </div>
    </div>

    <div class="auth-footer">
        AdminLTE styled • Laravel 12 • PostgreSQL & Docker ready
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/js/adminlte.min.js"></script>
</body>
</html>
