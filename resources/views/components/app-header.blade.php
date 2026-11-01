<header class="app-topbar">
    <div class="topbar-left">
        <button type="button" id="sidebarToggleBtn" class="btn-icon-topbar" aria-label="Ciutkan panel navigasi samping"
            aria-controls="appSidebar" aria-expanded="true">
            <i class="fa-solid fa-bars-staggered" aria-hidden="true"></i>
        </button>

        <!-- Topbar Breadcrumb -->
        <nav aria-label="Breadcrumb Topbar" class="d-none d-md-block">
            <ol class="simdes-breadcrumb">
                <li><a href="dashboard.html">SIMDES Tiaro</a></li>
                <li class="active" aria-current="page">Dashboard</li>
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
        <div class="dropdown">
            <button type="button" class="topbar-profile-btn" id="userDropdownBtn" data-bs-toggle="dropdown"
                aria-expanded="false" aria-label="Menu profil pengguna">
                <span class="topbar-avatar" data-user-initials>BS</span>
                <span class="topbar-profile-info d-none d-md-block">
                    <span class="topbar-profile-name" data-user-fullname>Budi Santoso, S.AP</span>
                    <span class="topbar-profile-role" data-user-role>Sekretaris Desa</span>
                </span>
                <i class="fa-solid fa-chevron-down text-simdes-muted d-none d-md-inline-block"
                    style="font-size:0.7rem;" aria-hidden="true"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end simdes-dropdown-menu" aria-labelledby="userDropdownBtn">
                <li class="px-3 py-2 border-bottom mb-1" style="border-color:var(--simdes-border) !important;">
                    <div class="fw-bold" style="font-size:0.84rem;" data-user-fullname>Budi Santoso,
                        S.AP</div>
                    <div class="text-simdes-muted" style="font-size:0.74rem;" data-user-unit>
                        Sekretariat Pemerintah Desa Tiaro</div>
                </li>
                <li>
                    <a class="dropdown-item" href="#userProfileModal" data-action="open-profile-modal">
                        <i class="fa-regular fa-id-badge text-simdes-secondary" aria-hidden="true"></i>
                        Profil Aparatur
                    </a>
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
                    <a class="dropdown-item text-danger" href="../login.html" data-action="confirm-logout">
                        <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>
                        Keluar Sistem
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>
