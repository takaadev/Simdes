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
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">

</head>

<body>

    <div class="login-page">
        <!-- Left Civic Institutional Showcase (Desktop) -->
        <aside class="login-showcase" aria-label="Identitas Sistem Informasi Manajemen Desa Tiaro">
            <div class="login-showcase-top">
                <span class="login-official-pill">
                    <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                    Portal Resmi Pemerintah Desa Tiaro
                </span>
            </div>

            <div class="login-showcase-center">
                <div class="login-crest-lockup">
                    <img src="{{ asset('logo.png') }}" alt="Lambang Resmi Pemerintah Desa Tiaro" class="login-crest-img"
                        width="72" height="72">
                    <div>
                        <div class="fw-bold text-uppercase"
                            style="letter-spacing:0.08em; font-size:0.8rem; color:var(--simdes-accent);">
                            Sistem Informasi Manajemen Desa
                        </div>
                        <div class="fw-bolder fs-4 text-white" style="letter-spacing:-0.01em;">
                            SIMDES DESA TIARO
                        </div>
                    </div>
                </div>

                <h1 class="login-headline">
                    Tata Kelola Administrasi Desa yang <span>Tertib, Akurat,</span> dan Terpadu.
                </h1>
                <p class="login-lead">
                    Platform kerja digital perangkat Kantor Desa Tiaro untuk pengelolaan buku administrasi umum dan
                    administrasi kependudukan secara terstruktur sesuai standar pemerintahan desa.
                </p>

                <div class="login-pillars">
                    <div class="login-pillar-item">
                        <div class="login-pillar-icon">
                            <i class="fa-solid fa-book-bookmark" aria-hidden="true"></i>
                        </div>
                        <div class="login-pillar-title">9 Buku Administrasi Umum</div>
                        <p class="login-pillar-desc">
                            Pengelolaan produk hukum, keputusan kepala desa, inventaris aset, tanah kas, agenda, hingga
                            lembaran desa.
                        </p>
                    </div>
                    <div class="login-pillar-item">
                        <div class="login-pillar-icon">
                            <i class="fa-solid fa-users-rectangle" aria-hidden="true"></i>
                        </div>
                        <div class="login-pillar-title">5 Buku Administrasi Penduduk</div>
                        <p class="login-pillar-desc">
                            Pencatatan induk penduduk, mutasi warga, rekapitulasi bulanan, penduduk sementara, serta KTP
                            dan KK.
                        </p>
                    </div>
                </div>
            </div>

            <div class="login-showcase-bottom">
                <span><i class="fa-solid fa-building-columns me-2 text-simdes-accent" aria-hidden="true"></i>Kantor
                    Pemerintah Desa Tiaro</span>
                <span>Standar Permendagri No. 47 Tahun 2016</span>
            </div>
        </aside>

        <!-- Right Authentication Panel -->
        <main class="login-panel-wrap">
            {{ $slot }}
            <p class="mt-4 mb-0 text-center text-simdes-muted" style="font-size:0.78rem;">
                &copy; 2026 Pemerintah Desa Tiaro &mdash; Sistem Informasi Manajemen Desa (SIMDES)
            </p>
        </main>
    </div>

</body>

</html>
