<div class="col-12 col-lg-5 col-xl-4">
    <div class="simdes-card">
        <div class="simdes-card-header">
            <div>
                <h2 class="simdes-card-title">
                    <i class="fa-solid fa-chart-pie text-simdes-primary" aria-hidden="true"></i>
                    Sebaran Penduduk per RT
                </h2>
                <p class="simdes-card-subtitle">Distribusi warga aktif per wilayah {{ $appSetting->nama_desa??'Desa' }}</p>
            </div>
        </div>
        <div class="simdes-card-body">
            <div class="donut-wrap">
                <div class="donut-svg-box">
                    <div id="rtDonutSvgWrap"></div>
                    <div class="donut-center-label">
                        <span class="donut-total-num" id="donutCenterNumber">0</span>
                        <span class="donut-total-caption" id="donutCenterCaption">Total Jiwa (0 RT)</span>
                    </div>
                </div>
                <div class="dusun-breakdown-list" id="rtBreakdownList"></div>
            </div>
        </div>
    </div>
</div>

@push('header')
    <style>
        .donut-slice {
            cursor: pointer;
            transition:
                stroke-dasharray 0.85s cubic-bezier(0.22, 1, 0.36, 1),
                opacity 0.25s ease,
                stroke-width 0.25s ease;
        }

        .donut-slice.is-active {
            stroke-width: 25;
        }
    </style>
@endpush

@push('footer')
    <script>
        $(function() {
            const formatNumber = (num) => Number(num || 0).toLocaleString('id-ID');

            function renderRtDonut(pieData) {
                const $svgBox = $('#rtDonutSvgWrap');
                const $listBox = $('#rtBreakdownList');
                const $centerCap = $('#donutCenterCaption');

                if (!$svgBox.length || !$listBox.length) return;

                const items = Array.isArray(pieData?.items) ? pieData.items : [];
                const totalJiwa = Number(
                    pieData?.total_jiwa ?? items.reduce((acc, d) => acc + Number(d.jiwa || 0), 0)
                );
                const totalRt = Number(pieData?.total_rt ?? items.length);

                const radius = 72;
                const circumference = 2 * Math.PI * radius;

                if (!items.length) {
                    $svgBox.html(`
                        <svg viewBox="0 0 196 196" width="196" height="196" aria-label="Proporsi penduduk per RT">
                            <circle cx="98" cy="98" r="${radius}" fill="transparent" stroke="#ECEFEA" stroke-width="22" />
                        </svg>
                    `);
                    updateWidgetCounter('donutCenterNumber', 0);
                    $centerCap.text('Belum ada data');
                    $listBox.html(
                        '<div class="text-center text-muted small py-3">Belum ada data penduduk aktif per RT.</div>'
                    );
                    return;
                }

                const activeSlices = items.filter((d) => Number(d.jiwa || 0) > 0).length;
                const gap = activeSlices > 1 ? 3 : 0;
                let cumulativeFraction = 0;

                const slicesSvg = totalJiwa > 0 ?
                    items.map((d, idx) => {
                        const jiwa = Number(d.jiwa || 0);
                        if (jiwa <= 0) return '';

                        const fraction = jiwa / totalJiwa;
                        const dashLen = Math.max(0, fraction * circumference - gap);
                        const dashOffset = -cumulativeFraction * circumference;
                        cumulativeFraction += fraction;

                        return `
                            <circle class="donut-slice"
                                    data-rt-idx="${idx}"
                                    data-dash="${dashLen} ${circumference}"
                                    cx="98" cy="98" r="${radius}"
                                    fill="transparent"
                                    stroke="${d.color}"
                                    stroke-width="22"
                                    stroke-dashoffset="${dashOffset}"
                                    style="stroke-dasharray: 0 ${circumference};"
                                    transform="rotate(-90 98 98)" />
                        `;
                    }).join('') :
                    '';

                $svgBox.html(`
                    <svg viewBox="0 0 196 196" width="196" height="196" aria-label="Proporsi penduduk per RT">
                        <circle cx="98" cy="98" r="${radius}" fill="transparent" stroke="#ECEFEA" stroke-width="22" />
                        ${slicesSvg}
                    </svg>
                `);

                const listHtml = items.map((d, idx) => {
                    const jiwa = Number(d.jiwa || 0);
                    const kk = Number(d.kk || 0);
                    const pct = totalJiwa > 0 ?
                        ((jiwa / totalJiwa) * 100).toFixed(1).replace('.', ',') :
                        '0,0';

                    return `
                        <div class="dusun-row" data-rt-row="${idx}">
                            <div class="d-flex align-items-center gap-2 min-w-0">
                                <span style="width:10px; height:10px; border-radius:50%; background:${d.color}; flex-shrink:0;"></span>
                                <div class="text-truncate">
                                    <div class="fw-semibold" style="font-size:0.82rem; color:var(--simdes-text);">${d.name}</div>
                                    <div style="font-size:0.71rem; color:var(--simdes-text-muted);">${formatNumber(kk)} KK terdaftar</div>
                                </div>
                            </div>
                            <div class="text-end tabular-nums">
                                <span class="fw-bold" style="font-size:0.84rem; color:var(--simdes-deep-green);">${formatNumber(jiwa)}</span>
                                <span class="badge-simdes badge-simdes-neutral ms-1">${pct}%</span>
                            </div>
                        </div>
                    `;
                }).join('');

                $listBox.html(listHtml);

                updateWidgetCounter('donutCenterNumber', totalJiwa);
                $centerCap.text(`Total Jiwa (${totalRt} RT)`);

                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        $svgBox.find('.donut-slice').each(function() {
                            $(this).css('stroke-dasharray', $(this).data('dash'));
                        });
                    });
                });

                const highlightRt = (idx) => {
                    const item = items[idx];
                    if (!item) return;

                    const jiwa = Number(item.jiwa || 0);
                    const pct = totalJiwa > 0 ?
                        ((jiwa / totalJiwa) * 100).toFixed(1).replace('.', ',') :
                        '0,0';

                    updateWidgetCounter('donutCenterNumber', jiwa);
                    $centerCap.text(`${item.name} (${pct}%)`);

                    $listBox.find('.dusun-row').each(function() {
                        const isMatch = Number($(this).data('rt-row')) === idx;
                        $(this).toggleClass('active-dusun', isMatch);
                    });

                    $svgBox.find('.donut-slice').each(function() {
                        const isMatch = Number($(this).data('rt-idx')) === idx;
                        $(this)
                            .toggleClass('is-active', isMatch)
                            .css('opacity', isMatch ? '1' : '0.4');
                    });
                };

                const resetRt = () => {
                    updateWidgetCounter('donutCenterNumber', totalJiwa);
                    $centerCap.text(`Total Jiwa (${totalRt} RT)`);

                    $listBox.find('.dusun-row').removeClass('active-dusun');
                    $svgBox.find('.donut-slice')
                        .removeClass('is-active')
                        .css('opacity', '1');
                };

                $svgBox
                    .off('mouseenter.rtdonut mouseleave.rtdonut')
                    .on('mouseenter.rtdonut', '.donut-slice', function() {
                        highlightRt(Number($(this).data('rt-idx')));
                    })
                    .on('mouseleave.rtdonut', '.donut-slice', resetRt);

                $listBox
                    .off('mouseenter.rtdonut mouseleave.rtdonut')
                    .on('mouseenter.rtdonut', '.dusun-row', function() {
                        highlightRt(Number($(this).data('rt-row')));
                    })
                    .on('mouseleave.rtdonut', '.dusun-row', resetRt);
            }

            $.ajax({
                url: "{{ route('dashboard') }}",
                type: "GET",
                dataType: "json",
                success: function(res) {
                    if (!res.success) return;

                    if (res.dataWidget) {
                        const {
                            penduduk,
                            kk,
                            arsip_umum,
                            aset_desa
                        } = res.dataWidget;

                        updateWidgetCounter('widget-penduduk', penduduk.total ?? 0);
                        $('#meta-penduduk-gender').html(
                            `${formatNumber(penduduk.laki_laki)} L &bull; ${formatNumber(penduduk.perempuan)} P`
                        );
                        $('#meta-penduduk-bulan').html(
                            `<i class="fa-solid fa-arrow-trend-up" aria-hidden="true"></i> +${formatNumber(penduduk.bulan_ini)} bln ini`
                        );

                        updateWidgetCounter('widget-kk', kk.total ?? 0);
                        $('#meta-kk-wilayah').html(
                            `${formatNumber(kk.total_rw)} RW &bull; ${formatNumber(kk.total_rt)} RT`
                        );

                        updateWidgetCounter('widget-arsip', arsip_umum.total ?? 0);
                        $('#meta-arsip-buku').text(`${arsip_umum.buku_aktif} Buku Register Aktif`);
                        $('#meta-arsip-bulan').text(
                            `+${formatNumber(arsip_umum.bulan_ini)} ${arsip_umum.label_bln}`
                        );

                        updateWidgetCounter('widget-aset', aset_desa.total ?? 0);
                        $('#meta-aset-detail').html(
                            `${formatNumber(aset_desa.inventaris)} Inventaris &bull; ${formatNumber(aset_desa.tanah_kas)} Bidang`
                        );
                    }

                    if (res.dataPieCart) {
                        renderRtDonut(res.dataPieCart);
                    }
                },
                error: function() {
                    if (typeof window.showToast === 'function') {
                        window.showToast({
                            title: 'Gagal Memuat Statistik',
                            message: 'Data ringkasan dashboard tidak dapat diambil. Silakan muat ulang halaman.',
                            variant: 'danger',
                            duration: 4000
                        });
                    }
                }
            });
        });
    </script>
@endpush
