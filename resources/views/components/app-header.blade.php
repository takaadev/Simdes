<header class="app-topbar">
    <div class="topbar-left">
        <button type="button" id="sidebarToggleBtn" class="btn-icon-topbar" aria-label="Ciutkan panel navigasi samping"
            aria-controls="appSidebar" aria-expanded="true">
            <i class="fa-solid fa-bars-staggered" aria-hidden="true"></i>
        </button>

        <!-- Topbar Breadcrumb -->
        <nav aria-label="Breadcrumb Topbar" class="d-none d-md-block">
            <ol class="simdes-breadcrumb">
                <li>{{config('app.name')}}</li>
                {{-- <li class="active" aria-current="page">Dashboard</li> --}}
            </ol>
        </nav>
    </div>

    <div class="topbar-right">
        <!-- Quick Search -->
        <div class="topbar-search d-none d-sm-block">
            <i class="fa-solid fa-magnifying-glass topbar-search-icon" aria-hidden="true"></i>
            <input type="search" id="globalSearchInput" class="topbar-search-input"
                placeholder="Cari ringkasan atau modul..." autocomplete="off" autocorrect="off" autocapitalize="off"
                spellcheck="false" aria-label="Pencarian cepat halaman">
            <kbd class="topbar-search-kbd d-none d-lg-inline-block">Ctrl K</kbd>
            <div id="globalSearchResults" class="topbar-search-results" role="listbox"
                aria-label="Hasil pencarian cepat"></div>
        </div>

        <!-- Active Fiscal Period Badge -->
        <span class="fiscal-badge d-none d-xl-inline-flex" title="Tahun Anggaran Pemerintahan Desa Aktif">
            <i class="fa-regular fa-calendar-check" aria-hidden="true"></i>
            TA 2026
        </span>

        <!-- Dummy Notifications Dropdown -->
        <div class="dropdown">
            <button type="button" class="btn-icon-topbar" id="notifDropdownBtn" data-bs-toggle="dropdown"
                data-bs-auto-close="outside" aria-expanded="false"
                aria-label="Notifikasi administrasi desa (3 belum dibaca)">
                <i class="fa-regular fa-bell" aria-hidden="true"></i>
                <span class="notif-dot" id="topbarNotifDot" aria-hidden="true"></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end simdes-dropdown-menu simdes-notif-menu"
                aria-labelledby="notifDropdownBtn">
                <div class="notif-header">
                    <div>
                        <span class="fw-bold" style="font-size:0.86rem; color:var(--simdes-text);">Pemberitahuan
                            Sistem</span>
                        <span id="notifCountBadge" class="badge-simdes badge-simdes-accent ms-1">3
                            Baru</span>
                    </div>
                    <button type="button" id="markAllNotifReadBtn"
                        class="border-0 bg-transparent p-0 fw-semibold text-simdes-primary" style="font-size:0.76rem;">
                        Tandai dibaca
                    </button>
                </div>
                <div class="notif-list">
                    <div class="notif-item unread">
                        <div class="notif-icon stat-icon-primary">
                            <i class="fa-solid fa-file-signature" aria-hidden="true"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="fw-semibold" style="font-size:0.81rem; color:var(--simdes-text);">
                                Perdes No. 04/2026 telah diundangkan
                            </div>
                            <div class="text-simdes-muted" style="font-size:0.75rem;">
                                Tercatat dalam Buku Lembaran Desa dan Berita Desa.
                            </div>
                            <small class="text-simdes-muted" style="font-size:0.7rem;">35 menit yang
                                lalu</small>
                        </div>
                    </div>
                    <div class="notif-item unread">
                        <div class="notif-icon stat-icon-accent">
                            <i class="fa-solid fa-user-plus" aria-hidden="true"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="fw-semibold" style="font-size:0.81rem; color:var(--simdes-text);">
                                2 laporan mutasi datang Dusun II
                            </div>
                            <div class="text-simdes-muted" style="font-size:0.75rem;">
                                Menunggu verifikasi berkas pengantar pindah masuk.
                            </div>
                            <small class="text-simdes-muted" style="font-size:0.7rem;">2 jam yang
                                lalu</small>
                        </div>
                    </div>
                    <div class="notif-item unread">
                        <div class="notif-icon stat-icon-secondary">
                            <i class="fa-solid fa-calendar-day" aria-hidden="true"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="fw-semibold" style="font-size:0.81rem; color:var(--simdes-text);">
                                Rekapitulasi Penduduk Oktober 2026
                            </div>
                            <div class="text-simdes-muted" style="font-size:0.75rem;">
                                Jadwal penutupan buku bulanan akhir Oktober.
                            </div>
                            <small class="text-simdes-muted" style="font-size:0.7rem;">Hari ini, 08:00
                                WITA</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        @php
            $authUser = auth()->user()?->loadMissing('villageOfficial.position');
            $authOfficial = $authUser?->villageOfficial;

            $displayName = $authOfficial?->nama ?: $authUser?->name ?? 'Pengguna';

            $initials =
                collect(preg_split('/\s+/', trim($displayName)))
                    ->filter()
                    ->take(2)
                    ->map(fn($word) => mb_strtoupper(mb_substr($word, 0, 1)))
                    ->implode('') ?:
                'US';

            $roleEnum =
                $authUser?->role instanceof \App\Enums\RoleAccessType
                    ? $authUser->role
                    : \App\Enums\RoleAccessType::tryFrom((string) ($authUser?->role ?? ''));

            $roleLabel = $roleEnum?->label() ?? ($authUser?->role ?: 'Aparatur Desa');
            $positionLabel = $authOfficial?->position?->nama ?: 'Pemerintah Desa Tiaro';
        @endphp

        @if ($authUser)
            <div class="dropdown">
                <button type="button" class="topbar-profile-btn" id="userDropdownBtn" data-bs-toggle="dropdown"
                    aria-expanded="false" aria-label="Menu profil pengguna">
                    <span class="topbar-avatar">
                        @if ($authUser->avatar_url)
                            <img src="{{ asset('storage/' . $authUser->avatar_url) }}" alt="{{ $displayName }}"
                                class="w-100 h-100 rounded-circle object-fit-cover">
                        @else
                            {{ $initials }}
                        @endif
                    </span>
                    <span class="topbar-profile-info d-none d-md-block">
                        <span class="topbar-profile-name">{{ $displayName }}</span>
                        <span class="topbar-profile-role">{{ $roleLabel }}</span>
                    </span>
                    <i class="fa-solid fa-chevron-down text-simdes-muted d-none d-md-inline-block"
                        style="font-size:0.7rem;" aria-hidden="true"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end simdes-dropdown-menu" aria-labelledby="userDropdownBtn">
                    <li class="px-3 py-2 border-bottom mb-1" style="border-color:var(--simdes-border) !important;">
                        <div class="fw-bold text-truncate" style="font-size:0.84rem; max-width: 220px;">
                            {{ $displayName }}
                        </div>
                        <div class="text-simdes-muted text-truncate" style="font-size:0.74rem; max-width: 220px;">
                            {{ $positionLabel }}
                        </div>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                            data-bs-target="#userProfileModal">
                            <i class="fa-regular fa-id-badge text-simdes-secondary" aria-hidden="true"></i>
                            Profil Aparatur
                        </button>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#sectionInfoDesa">
                            <i class="fa-solid fa-landmark text-simdes-secondary" aria-hidden="true"></i>
                            Informasi Kantor Desa
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider" style="border-color:var(--simdes-border);">
                    </li>
                    <li>
                        <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal"
                            data-bs-target="#logoutConfirmModal">
                            <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>
                            Keluar Sistem
                        </button>
                    </li>
                </ul>
            </div>
        @endif
    </div>
</header>
