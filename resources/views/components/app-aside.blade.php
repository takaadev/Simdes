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
    @endphp

    @if ($authUser)
        <div class="sidebar-footer">
            <div class="sidebar-user-card">
                <button type="button"
                    class="border-0 bg-transparent p-0 d-flex align-items-center gap-2 text-start flex-grow-1 min-w-0"
                    data-bs-toggle="modal" data-bs-target="#userProfileModal" data-sidebar-tooltip="Profil Pengguna"
                    aria-label="Lihat profil pengguna">
                    <span class="sidebar-user-avatar">
                        @if ($authUser->avatar_url)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($authUser->avatar_url) }}"
                                alt="{{ $displayName }}" class="w-100 h-100 rounded-circle object-fit-cover">
                        @else
                            {{ $initials }}
                        @endif
                    </span>
                    <span class="sidebar-user-meta">
                        <span class="sidebar-user-name">{{ $displayName }}</span>
                        <span class="sidebar-user-role">{{ $roleLabel }}</span>
                    </span>
                </button>
                <button type="button" class="sidebar-logout-btn" data-bs-toggle="modal"
                    data-bs-target="#logoutConfirmModal" title="Keluar Sistem" aria-label="Keluar dari SIMDES">
                    <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    @endif
</aside>
