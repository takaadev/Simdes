@php
    $appName = $appSetting?->app_name ?? config('app.name', 'SIMDES');
    $namaDesa = $appSetting?->nama_desa ?? 'Desa';
    $logoUrl = !empty($appSetting?->logo) ? asset('storage/' . $appSetting->logo) : asset('logo.png');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $appName }} &mdash; {{ $namaDesa }}</title>
    <meta name="description"
        content="{{ $appSetting?->deskripsi ?? 'Dashboard Utama Sistem Informasi Manajemen Desa (' . $appName . ') Pemerintah ' . $namaDesa . '.' }}">
    <link rel="icon" type="image/png" href="{{ $logoUrl }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/datatable/datatables.min.css') }}">
    @stack('header')
</head>

<body data-root-prefix="../">
    {{-- <a href="#mainContent" class="skip-link">Lewati ke konten utama</a> --}}

    <div class="app-shell">
        <x-app-aside :appSetting="$appSetting" />
        <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>
        <div class="app-main" id="appMainWrapper">
            <x-app-header :appSetting="$appSetting" />
            {{ $slot }}
            {{-- <x-app-footer /> --}}
        </div>
    </div>

    <x-modal-print />
    <x-user-official-profile />
    <x-action-logout />
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/tooltip.js') }}"></script>
    <script src="{{ asset('assets/js/datatables-global.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/modal.js') }}"></script>
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/toast.js') }}"></script>
    @stack('footer')
</body>

</html>
