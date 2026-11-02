<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SIMDES Desa Tiaro') }}</title>
    <meta name="description" content="Dashboard Utama Sistem Informasi Manajemen Desa (SIMDES) Pemerintah Desa Tiaro.">
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages.css') }}">
    @stack('header')
</head>

<body data-root-prefix="../">
    {{-- <a href="#mainContent" class="skip-link">Lewati ke konten utama</a> --}}

    <div class="app-shell">
        <x-app-aside />
        <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>
        <div class="app-main" id="appMainWrapper">
            <x-app-header />
            {{ $slot }}
            <x-app-footer />
        </div>
    </div>

    <div class="modal fade simdes-modal" id="userProfileModal" tabindex="-1" aria-labelledby="userProfileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="userProfileModalLabel">
                        <i class="fa-regular fa-id-card text-simdes-primary me-2" aria-hidden="true"></i>Profil
                        Aparatur Pengguna
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom"
                        style="border-color:var(--simdes-border) !important;">
                        <div class="sidebar-user-avatar"
                            style="width:54px; height:54px; flex-basis:54px; font-size:1.1rem;" data-user-initials>
                            BS</div>
                        <div>
                            <div class="fw-bolder fs-6" style="color:var(--simdes-deep-green);" data-user-fullname>
                                Budi Santoso, S.AP</div>
                            <div class="badge-simdes badge-simdes-primary mt-1" data-user-role>Sekretaris Desa
                            </div>
                        </div>
                    </div>
                    <div class="village-meta-table">
                        <div class="village-meta-row">
                            <span class="village-meta-key">Nama Pengguna</span>
                            <span class="village-meta-val" data-user-username>sekdes.tiaro</span>
                        </div>
                        <div class="village-meta-row">
                            <span class="village-meta-key">NIPD (Dummy)</span>
                            <span class="village-meta-val tabular-nums" data-user-nipd>19880412 201503 1 002</span>
                        </div>
                        <div class="village-meta-row">
                            <span class="village-meta-key">Unit Kerja</span>
                            <span class="village-meta-val" data-user-unit>Sekretariat Pemerintah Desa Tiaro</span>
                        </div>
                        <div class="village-meta-row">
                            <span class="village-meta-key">Hak Akses</span>
                            <span class="village-meta-val">Koordinator Administrasi Desa</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn-simdes-outline" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade simdes-modal" id="logoutConfirmModal" tabindex="-1"
        aria-labelledby="logoutConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="logoutConfirmModalLabel">Konfirmasi Keluar</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body" style="font-size:0.875rem; color:var(--simdes-text-muted);">
                    Apakah Anda yakin ingin mengakhiri sesi kerja pada <strong>SIMDES Desa Tiaro</strong> dan
                    kembali ke halaman masuk?
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn-simdes-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="confirmLogoutBtn" class="btn-simdes-primary"
                        style="background-color:var(--simdes-danger); border-color:var(--simdes-danger);">
                        <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>
                        <span>Ya, Keluar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    @stack('footer')
</body>

</html>
