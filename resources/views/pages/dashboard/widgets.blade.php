<section class="row g-3 mb-4" aria-label="Ringkasan Statistik Utama" id="dashboardWidgets">
    <x-widgets>
        {{-- Total Penduduk --}}
        <x-widget-item id="widget-penduduk" icon="users" label="Total Penduduk Desa" value="0" color="green">
            <div class="stat-meta">
                <span id="meta-penduduk-gender">0 L &bull; 0 P</span>
                <span class="badge-simdes badge-simdes-success" id="meta-penduduk-bulan">
                    <i class="fa-solid fa-arrow-trend-up" aria-hidden="true"></i>
                    +0 bln ini
                </span>
            </div>
        </x-widget-item>

        {{-- Kepala Keluarga --}}
        <x-widget-item id="widget-kk" icon="house-user" label="Kepala Keluarga (KK)" value="0" color="secondary">
            <div class="stat-meta">
                <span id="meta-kk-wilayah">0 RW &bull; 0 RT</span>
                <span class="badge-simdes badge-simdes-primary">Data Aktif</span>
            </div>
        </x-widget-item>

        {{-- Administrasi Umum --}}
        <x-widget-item id="widget-arsip" icon="book-open-reader" label="Arsip Administrasi Umum" value="0"
            color="gold">
            <div class="stat-meta">
                <span id="meta-arsip-buku">0 Buku Register Aktif</span>
                <span class="badge-simdes badge-simdes-accent" id="meta-arsip-bulan">+0 bln ini</span>
            </div>
        </x-widget-item>

        {{-- Aset Desa --}}
        <x-widget-item id="widget-aset" icon="map-location-dot" label="Aset & Tanah Kas Desa" value="0"
            color="info">
            <div class="stat-meta">
                <span id="meta-aset-detail">0 Inventaris &bull; 0 Bidang</span>
                <span class="badge-simdes badge-simdes-success">Terverifikasi</span>
            </div>
        </x-widget-item>
    </x-widgets>
</section>

@push('footer')
    <script>
        $(function() {
            const formatNumber = (num) => Number(num || 0).toLocaleString('id-ID');

            $.ajax({
                url: "{{ route('dashboard') }}",
                type: "GET",
                dataType: "json",
                success: function(res) {
                    if (!res.success || !res.dataWidget) return;

                    const {
                        penduduk,
                        kk,
                        arsip_umum,
                        aset_desa
                    } = res.dataWidget;

                    // 1. Total Penduduk
                    $('#widget-penduduk .stat-value').text(formatNumber(penduduk.total));
                    $('#meta-penduduk-gender').html(
                        `${formatNumber(penduduk.laki_laki)} L &bull; ${formatNumber(penduduk.perempuan)} P`
                    );
                    $('#meta-penduduk-bulan').html(
                        `<i class="fa-solid fa-arrow-trend-up" aria-hidden="true"></i> +${formatNumber(penduduk.bulan_ini)} bln ini`
                    );

                    // 2. Kepala Keluarga
                    $('#widget-kk .stat-value').text(formatNumber(kk.total));
                    $('#meta-kk-wilayah').html(
                        `${formatNumber(kk.total_rw)} RW &bull; ${formatNumber(kk.total_rt)} RT`
                    );

                    // 3. Arsip Administrasi Umum
                    $('#widget-arsip .stat-value').text(formatNumber(arsip_umum.total));
                    $('#meta-arsip-buku').text(`${arsip_umum.buku_aktif} Buku Register Aktif`);
                    $('#meta-arsip-bulan').text(
                        `+${formatNumber(arsip_umum.bulan_ini)} ${arsip_umum.label_bln}`);

                    // 4. Aset & Tanah Kas Desa
                    $('#widget-aset .stat-value').text(formatNumber(aset_desa.total));
                    $('#meta-aset-detail').html(
                        `${formatNumber(aset_desa.inventaris)} Inventaris &bull; ${formatNumber(aset_desa.tanah_kas)} Bidang`
                    );
                }
            });
        });
    </script>
@endpush
