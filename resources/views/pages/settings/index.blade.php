<x-appcontent>
    <section class="row g-4 mb-3" aria-label="Pengaturan Aplikasi dan Profil Desa" id="settingsPageRoot">
        {{-- Aside: Menu Tab Vertikal --}}
        <aside class="col-12 col-lg-3">
            <div class="simdes-card">
                <div class="simdes-card-body p-2">
                    <div class="simdes-segmented d-flex flex-lg-column w-100 gap-1" role="tablist"
                        aria-label="Menu pengaturan">
                        <button type="button" class="simdes-segmented-btn active text-start w-100" id="tab-profil-btn"
                            data-bs-toggle="tab" data-bs-target="#tab-profil" role="tab" aria-controls="tab-profil"
                            aria-selected="true">
                            <i class="fa-solid fa-landmark me-2" aria-hidden="true"></i>
                            Profil Desa
                        </button>
                        <button type="button" class="simdes-segmented-btn text-start w-100" id="tab-sistem-btn"
                            data-bs-toggle="tab" data-bs-target="#tab-sistem" role="tab" aria-controls="tab-sistem"
                            aria-selected="false">
                            <i class="fa-solid fa-gear me-2" aria-hidden="true"></i>
                            Pengaturan Sistem
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main: Konten Tab --}}
        <div class="col-12 col-lg-9">
            <div class="tab-content">
                {{-- Tab 1: Profil Desa --}}
                <div class="tab-pane fade show active" id="tab-profil" role="tabpanel" aria-labelledby="tab-profil-btn">
                    <div class="simdes-card">
                        <div class="simdes-card-header">
                            <div>
                                <h2 class="simdes-card-title">
                                    <i class="fa-solid fa-landmark text-simdes-primary" aria-hidden="true"></i>
                                    Informasi Administrasi Desa
                                </h2>
                                <p class="simdes-card-subtitle">
                                    Identitas kelembagaan &amp; ketertiban administrasi Kantor
                                    <span id="val-subtitle-desa">-</span>
                                </p>
                            </div>
                            @include('pages.settings.update-setting')
                        </div>
                        <div class="simdes-card-body">
                            <div class="d-flex justify-content-center mb-4">
                                <img id="val-logo" src="{{ asset('logo.png') }}" alt="Logo Desa"
                                    class="img-fluid shadow-sm rounded-3 object-fit-contain p-2 bg-body"
                                    style="max-height: 200px; max-width: 200px;">
                            </div>

                            <div class="village-meta-table">
                                <div class="village-meta-row">
                                    <span class="village-meta-key">Nama Desa</span>
                                    <span class="village-meta-val" id="val-nama-desa"></span>
                                </div>
                                <div class="village-meta-row">
                                    <span class="village-meta-key">Kode Wilayah</span>
                                    <span class="village-meta-val tabular-nums" id="val-kode-wilayah">-</span>
                                </div>
                                <div class="village-meta-row">
                                    <span class="village-meta-key">Wilayah Administrasi</span>
                                    <span class="village-meta-val" id="val-wilayah-admin">-</span>
                                </div>
                                <div class="village-meta-row">
                                    <span class="village-meta-key">Alamat Kantor</span>
                                    <span class="village-meta-val" id="val-alamat-kantor">-</span>
                                </div>
                                <div class="village-meta-row">
                                    <span class="village-meta-key">Kontak</span>
                                    <span class="village-meta-val" id="val-kontak">-</span>
                                </div>
                                <div class="village-meta-row">
                                    <span class="village-meta-key">Kepala Desa</span>
                                    <span class="village-meta-val" id="val-nama-kades">-</span>
                                </div>
                                <div class="village-meta-row">
                                    <span class="village-meta-key">Sekretaris Desa</span>
                                    <span class="village-meta-val" id="val-nama-sekdes">-</span>
                                </div>
                                <div class="village-meta-row">
                                    <span class="village-meta-key">Cakupan Wilayah</span>
                                    <span class="village-meta-val tabular-nums" id="val-cakupan-wilayah">0 RW &bull; 0
                                        RT</span>
                                </div>
                                <div class="village-meta-row">
                                    <span class="village-meta-key">Jam Layanan Kantor</span>
                                    <span class="village-meta-val" id="val-jam-operasional">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tab 2: Pengaturan Sistem --}}
                <div class="tab-pane fade" id="tab-sistem" role="tabpanel" aria-labelledby="tab-sistem-btn">
                    <div class="simdes-card">
                        <div class="simdes-card-header">
                            <div>
                                <h2 class="simdes-card-title">
                                    <i class="fa-solid fa-gear text-simdes-primary" aria-hidden="true"></i>
                                    Pengaturan Sistem
                                </h2>
                                <p class="simdes-card-subtitle">
                                    Konfigurasi nama aplikasi, logo, zona waktu, dan deskripsi sistem
                                </p>
                            </div>
                            @include('pages.settings.update-sistem')
                        </div>
                        <div class="simdes-card-body">
                            <div class="village-meta-table">
                                <div class="village-meta-row">
                                    <span class="village-meta-key">Nama Aplikasi</span>
                                    <span class="village-meta-val">{{ config('app.name') }}</span>
                                </div>
                                <div class="village-meta-row">
                                    <span class="village-meta-key">Zona Waktu</span>
                                    <span class="village-meta-val" id="val-time-zone">-</span>
                                </div>
                                <div class="village-meta-row">
                                    <span class="village-meta-key">Deskripsi</span>
                                    <span class="village-meta-val" id="val-deskripsi">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('footer')
        <script>
            $(function() {
                const storageBaseUrl = "{{ asset('storage') }}";
                const defaultLogoUrl = "{{ asset('logo.png') }}";
                const updateProfileUrlTemplate = "{{ route('settings.update.profile', ':id') }}";
                const updateSistemUrlTemplate = "{{ route('settings.update.system', ':id') }}";

                let currentSettings = null;
                let isSettingsSubmitted = false;

                function fillProfileModal(modal, d) {
                    if (!d) return;

                    if (d.id) {
                        modal.find('form').attr('action', updateProfileUrlTemplate.replace(':id', d.id));
                    }

                    Object.keys(d).forEach(function(key) {
                        const input = modal.find(`[name="${key}"]`).not('[type="file"]');
                        if (input.length) {
                            input.val(d[key] ?? '');
                        }
                    });
                }

                function fillSistemModal(modal, d) {
                    if (!d) return;

                    if (d.id) {
                        modal.find('form').attr('action', updateSistemUrlTemplate.replace(':id', d.id));
                    }
                    modal.find('[name="deskripsi"]').val(d.deskripsi ?? '');
                    modal.find('[name="time_zone"]').val(d.time_zone ?? 'Asia/Jakarta').trigger('change');

                    if (d.logo) {
                        const logoUrl = `${storageBaseUrl}/${String(d.logo).replace(/^\/+/, '')}`;
                        $('#preview-logo').attr('src', logoUrl).show();
                    } else {
                        $('#preview-logo').attr('src', '').hide();
                    }
                }

                window.loadSettings = function() {
                    $.ajax({
                        url: "{{ route('settings.index') }}",
                        type: "GET",
                        dataType: "json",
                        success: function(response) {
                            if (!response.success || !response.data) {
                                showToast('error', 'Data pengaturan tidak ditemukan.');
                                return;
                            }

                            const d = response.data;
                            currentSettings = d;

                            if (d.id) {
                                const profileUrl = updateProfileUrlTemplate.replace(':id', d.id);
                                const sistemUrl = updateSistemUrlTemplate.replace(':id', d.id);

                                $('[id*="updateProfileDesa"]').find('form').attr('action', profileUrl);
                                $('[id*="sistem"]').find('form').attr('action', sistemUrl);

                                $('[data-bs-target*="updateProfileDesa"]').attr({
                                    href: profileUrl,
                                    'data-href': profileUrl
                                });
                                $('[data-bs-target*="sistem"]').attr({
                                    href: sistemUrl,
                                    'data-href': sistemUrl
                                });
                            }

                            // Logo (tampil di Tab 1: Profil Desa)
                            const logoUrl = d.logo ?
                                `${storageBaseUrl}/${String(d.logo).replace(/^\/+/, '')}` :
                                defaultLogoUrl;
                            $('#val-logo').attr('src', logoUrl);

                            // Tab 1: Profil Desa
                            $('#val-subtitle-desa').text(d.nama_desa || '-');
                            $('#val-nama-desa').text(d.nama_desa || '-');
                            $('#val-kode-wilayah').text(d.kode_wilayah || '-');
                            $('#val-wilayah-admin').text(
                                `Kec. ${d.kecamatan || '-'}, Kab. ${d.kabupaten || '-'}, ${d.provinsi || '-'}`
                            );
                            $('#val-alamat-kantor').text(
                                `${d.alamat_kantor || '-'}${d.kode_pos ? ' (' + d.kode_pos + ')' : ''}`
                            );
                            $('#val-kontak').text(`${d.telepon || '-'} \u2022 ${d.email || '-'}`);
                            $('#val-nama-kades').text(d.nama_kades || '-');
                            $('#val-nama-sekdes').text(d.nama_sekdes || '-');
                            $('#val-cakupan-wilayah').text(
                                `${d.total_rw ?? 0} RW \u2022 ${d.total_rt ?? 0} RT`
                            );
                            $('#val-jam-operasional').text(d.jam_operasional || '-');
                            $('#val-time-zone').text(d.time_zone || '-');
                            $('#val-deskripsi').text(d.deskripsi || '-');
                        },
                        error: function(xhr) {
                            const message = xhr.responseJSON?.message ||
                                'Gagal memuat data pengaturan.';
                            showToast('error', message);
                        }
                    });
                };

                // Isi ulang form setiap kali modal dibuka agar tidak kosong setelah di-reset modal global
                $(document).on('show.bs.modal', '[id*="updateProfileDesa"]', function() {
                    isSettingsSubmitted = false;
                    fillProfileModal($(this), currentSettings);
                });

                $(document).on('show.bs.modal', '[id*="sistem"]', function() {
                    isSettingsSubmitted = false;
                    fillSistemModal($(this), currentSettings);
                });

                // Tandai saat form di-submit
                $(document).on('submit', '[id*="updateProfileDesa"] form, [id*="sistem"] form', function() {
                    isSettingsSubmitted = true;
                });

                // Refresh data otomatis saat modal tertutup setelah submit
                $(document).on('hidden.bs.modal', '[id*="updateProfileDesa"], [id*="sistem"]', function() {
                    if (isSettingsSubmitted) {
                        isSettingsSubmitted = false;
                        window.loadSettings();
                    }
                });
                window.loadSettings();
            });
        </script>
    @endpush
</x-appcontent>
