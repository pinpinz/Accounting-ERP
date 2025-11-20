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
</head>
<body class="hold-transition login-page" style="font-family: 'Figtree', sans-serif; background: radial-gradient(circle at 20% 20%, #e0e7ff, transparent 25%), radial-gradient(circle at 80% 0%, #c7d2fe, transparent 20%), #f8fafc;">
<div class="login-box">
    <div class="login-logo">
        <a href="/" class="fw-bold text-primary">ERP <b>Keuangan</b></a>
    </div>
    <div class="card shadow-sm">
        <div class="card-body login-card-body">
            {{ $slot }}
        </div>
    </div>
    <p class="mt-3 text-center text-muted small">
        AdminLTE styled • Laravel 12 + PostgreSQL
    </p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/js/adminlte.min.js"></script>
</body>
</html>
