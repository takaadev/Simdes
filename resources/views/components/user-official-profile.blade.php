@if ($user)
    <div class="modal fade simdes-modal" id="userProfileModal" tabindex="-1" aria-labelledby="userProfileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="userProfileModalLabel">
                        <i class="fa-regular fa-id-card text-simdes-primary me-2" aria-hidden="true"></i>
                        Profil Aparatur Pengguna
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    {{-- Header Identitas --}}
                    <div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom"
                        style="border-color:var(--simdes-border) !important;">
                        <div class="sidebar-user-avatar"
                            style="width:54px; height:54px; flex-basis:54px; font-size:1.1rem;">
                            @if ($user->avatar_url)
                                <img src="{{ asset('/storage' . $user->avatar_url) }}" alt="{{ $displayName }}"
                                    class="w-100 h-100 rounded-circle object-fit-cover">
                            @else
                                {{ $initials }}
                            @endif
                        </div>
                        <div class="min-w-0">
                            <div class="fw-bolder fs-6 text-truncate" style="color:var(--simdes-deep-green);">
                                {{ $displayName }}
                            </div>
                            <div class="badge-simdes badge-simdes-{{ $roleColor }} mt-1">
                                {{ $roleLabel }}
                            </div>
                        </div>
                    </div>

                    {{-- Navigasi Tab Bootstrap 5 --}}
                    <ul class="nav nav-underline mb-3" id="userProfileTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-profile-btn" data-bs-toggle="tab"
                                data-bs-target="#tab-profile-pane" type="button" role="tab"
                                aria-controls="tab-profile-pane" aria-selected="true">
                                Profil Akun
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-official-btn" data-bs-toggle="tab"
                                data-bs-target="#tab-official-pane" type="button" role="tab"
                                aria-controls="tab-official-pane" aria-selected="false">
                                Data Kepegawaian
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-session-btn" data-bs-toggle="tab"
                                data-bs-target="#tab-session-pane" type="button" role="tab"
                                aria-controls="tab-session-pane" aria-selected="false">
                                Sesi Login
                            </button>
                        </li>
                    </ul>

                    {{-- Isi Tab --}}
                    <div class="tab-content" id="userProfileTabContent">
                        {{-- Tab 1: Profil Akun --}}
                        <div class="tab-pane fade show active" id="tab-profile-pane" role="tabpanel"
                            aria-labelledby="tab-profile-btn" tabindex="0">
                            <div class="village-meta-table">
                                <div class="village-meta-row">
                                    <span class="village-meta-key">Nama Lengkap</span>
                                    <span class="village-meta-val">{{ $displayName }}</span>
                                </div>
                                <div class="village-meta-row">
                                    <span class="village-meta-key">NIAP / Username</span>
                                    <span class="village-meta-val tabular-nums">{{ $user->username ?: '-' }}</span>
                                </div>
                                <div class="village-meta-row">
                                    <span class="village-meta-key">Alamat Surel</span>
                                    <span class="village-meta-val">{{ $user->email ?: '-' }}</span>
                                </div>
                                <div class="village-meta-row">
                                    <span class="village-meta-key">Hak Akses</span>
                                    <span class="village-meta-val">{{ $roleLabel }}</span>
                                </div>
                                <div class="village-meta-row">
                                    <span class="village-meta-key">Telepon</span>
                                    <span class="village-meta-val tabular-nums">{{ $official?->telpon ?: '-' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Tab 2: Data Kepegawaian --}}
                        <div class="tab-pane fade" id="tab-official-pane" role="tabpanel"
                            aria-labelledby="tab-official-btn" tabindex="0">
                            @if ($official)
                                <div class="village-meta-table">
                                    <div class="village-meta-row">
                                        <span class="village-meta-key">NIAP</span>
                                        <span class="village-meta-val tabular-nums">{{ $official->niap ?: '-' }}</span>
                                    </div>
                                    <div class="village-meta-row">
                                        <span class="village-meta-key">NIP</span>
                                        <span class="village-meta-val tabular-nums">{{ $official->nip ?: '-' }}</span>
                                    </div>
                                    <div class="village-meta-row">
                                        <span class="village-meta-key">Jabatan</span>
                                        <span class="village-meta-val">{{ $official->position?->nama ?? '-' }}</span>
                                    </div>
                                    <div class="village-meta-row">
                                        <span class="village-meta-key">Pangkat / Golongan</span>
                                        <span class="village-meta-val">{{ $official->pangkat_golongan ?: '-' }}</span>
                                    </div>
                                    <div class="village-meta-row">
                                        <span class="village-meta-key">Pendidikan</span>
                                        <span class="village-meta-val">{{ $enumLabel($official->pendidikan) }}</span>
                                    </div>
                                    <div class="village-meta-row">
                                        <span class="village-meta-key">No. Pengangkatan</span>
                                        <span
                                            class="village-meta-val">{{ $official->nomor_pengangkatan ?: '-' }}</span>
                                    </div>
                                    <div class="village-meta-row">
                                        <span class="village-meta-key">Tgl. Pengangkatan</span>
                                        <span
                                            class="village-meta-val">{{ $formatDate($official->tgl_pengangkatan) }}</span>
                                    </div>
                                    <div class="village-meta-row">
                                        <span class="village-meta-key">Status Aparat</span>
                                        <span class="village-meta-val">{{ $enumLabel($official->status) }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="text-center text-muted small py-3">
                                    Akun ini tidak terhubung dengan data kepegawaian aparatur desa.
                                </div>
                            @endif
                        </div>

                        {{-- Tab 3: Sesi Login --}}
                        <div class="tab-pane fade" id="tab-session-pane" role="tabpanel"
                            aria-labelledby="tab-session-btn" tabindex="0">
                            <div class="village-meta-table">
                                <div class="village-meta-row">
                                    <span class="village-meta-key">Status Sesi</span>
                                    <span class="village-meta-val text-success fw-semibold">Aktif (Online)</span>
                                </div>
                                <div class="village-meta-row">
                                    <span class="village-meta-key">Login Terakhir</span>
                                    <span
                                        class="village-meta-val">{{ $formatDate($user->last_login_at, 'd M Y, H:i') }}</span>
                                </div>
                                <div class="village-meta-row">
                                    <span class="village-meta-key">Alamat IP</span>
                                    <span class="village-meta-val tabular-nums">{{ request()->ip() }}</span>
                                </div>
                                <div class="village-meta-row">
                                    <span class="village-meta-key">Akun Dibuat</span>
                                    <span class="village-meta-val">{{ $formatDate($user->created_at) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn-simdes-outline" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endif
