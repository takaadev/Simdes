<aside class="app-sidebar" id="appSidebar" aria-label="Navigasi Utama SIMDES">
    <!-- Brand Identity -->
    <div class="sidebar-brand">
        <img src="{{ asset('logo.png') }}" alt="Lambang Desa Tiaro" class="sidebar-brand-logo" width="42" height="42">
        <div class="sidebar-brand-text">
            <span class="sidebar-brand-title">SIMDES TIARO</span>
            <span class="sidebar-brand-subtitle">Pemerintah Desa Tiaro</span>
        </div>
        <button type="button" id="sidebarCloseMobileBtn" class="sidebar-logout-btn d-lg-none ms-auto"
            aria-label="Tutup menu navigasi">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </div>
    <x-menu />
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
            <button type="button" class="sidebar-logout-btn" data-action="confirm-logout" title="Keluar Sistem"
                aria-label="Keluar dari SIMDES">
                <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</aside>
