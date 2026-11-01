<aside class="app-sidebar" id="appSidebar" aria-label="Navigasi Utama SIMDES">
            <!-- Brand Identity -->
            <div class="sidebar-brand">
                <img src="{{ asset('logo.png') }}" alt="Lambang Desa Tiaro" class="sidebar-brand-logo" width="42"
                    height="42">
                <div class="sidebar-brand-text">
                    <span class="sidebar-brand-title">SIMDES TIARO</span>
                    <span class="sidebar-brand-subtitle">Pemerintah Desa Tiaro</span>
                </div>
                <button type="button" id="sidebarCloseMobileBtn" class="sidebar-logout-btn d-lg-none ms-auto"
                    aria-label="Tutup menu navigasi">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="sidebar-nav-scroll" aria-label="Menu Buku Administrasi">
                <div class="sidebar-section-label">DASHBOARD</div>
                <ul class="sidebar-menu mb-2">
                    <li>
                        <a href="dashboard.html" class="sidebar-link active" aria-current="page"
                            data-sidebar-tooltip="Dashboard">
                            <span class="sidebar-link-icon">
                                <i class="fa-solid fa-table-columns" aria-hidden="true"></i>
                            </span>
                            <span class="sidebar-link-text">Dashboard</span>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-section-label">MODUL ADMINISTRASI</div>
                <ul class="sidebar-menu">
                    <!-- Group A: Administrasi Umum -->
                    <li>
                        <button type="button" class="sidebar-link" data-sidebar-group="umum"
                            data-sidebar-tooltip="Administrasi Umum (9 Buku)" aria-expanded="false"
                            aria-controls="sidebarGroupUmumInfo">
                            <span class="sidebar-link-icon">
                                <i class="fa-solid fa-book-bookmark" aria-hidden="true"></i>
                            </span>
                            <span class="sidebar-link-text">Administrasi Umum</span>
                            <span class="sidebar-badge">9</span>
                            <i class="fa-solid fa-chevron-down sidebar-chevron" aria-hidden="true"></i>
                        </button>
                        <div id="sidebarGroupUmumInfo" class="sidebar-group-panel" hidden>
                            Memuat kelompok <strong>9 Buku Administrasi Umum</strong> (Peraturan Desa s.d. Lembaran
                            &amp; Berita Desa).
                        </div>
                    </li>

                    <!-- Group B: Administrasi Penduduk -->
                    <li>
                        <button type="button" class="sidebar-link" data-sidebar-group="penduduk"
                            data-sidebar-tooltip="Administrasi Penduduk (5 Buku)" aria-expanded="false"
                            aria-controls="sidebarGroupPendudukInfo">
                            <span class="sidebar-link-icon">
                                <i class="fa-solid fa-users" aria-hidden="true"></i>
                            </span>
                            <span class="sidebar-link-text">Administrasi Penduduk</span>
                            <span class="sidebar-badge">5</span>
                            <i class="fa-solid fa-chevron-down sidebar-chevron" aria-hidden="true"></i>
                        </button>
                        <div id="sidebarGroupPendudukInfo" class="sidebar-group-panel" hidden>
                            Memuat kelompok <strong>5 Buku Administrasi Penduduk</strong> (Induk Penduduk s.d. KTP
                            &amp; KK).
                        </div>
                    </li>
                </ul>
            </nav>

            <!-- Sidebar User Profile Footer -->
            <div class="sidebar-footer">
                <div class="sidebar-user-card">
                    <button type="button"
                        class="border-0 bg-transparent p-0 d-flex align-items-center gap-2 text-start flex-grow-1 min-w-0"
                        data-action="open-profile-modal" data-sidebar-tooltip="Profil Pengguna"
                        aria-label="Lihat profil pengguna">
                        <span class="sidebar-user-avatar" data-user-initials>BS</span>
                        <span class="sidebar-user-meta">
                            <span class="sidebar-user-name" data-user-fullname>Budi Santoso, S.AP</span>
                            <span class="sidebar-user-role" data-user-role>Sekretaris Desa</span>
                        </span>
                    </button>
                    <button type="button" class="sidebar-logout-btn" data-action="confirm-logout"
                        title="Keluar Sistem" aria-label="Keluar dari SIMDES">
                        <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </aside>