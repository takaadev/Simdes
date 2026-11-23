   <section class="dashboard-welcome" aria-label="Sapaan Pengguna">
       <div class="welcome-content">
           <div>
               <div class="welcome-eyebrow">
                   <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                   <span id="dashboardCurrentDate"></span>
               </div>
               <h2 class="welcome-title"> {{ $sapaan }}, {{ strtoupper($namaUser) }}</h2>
               <p class="welcome-desc">
                   Seluruh buku administrasi Desa Tiaro berada dalam pemantauan berkala. Terdapat
               </p>
           </div>
           <div class="welcome-meta-pills">
               <div class="welcome-pill">
                   <span class="welcome-pill-label">Kode Desa</span>
                   <span class="welcome-pill-val tabular-nums">{{ $appSetting->kode_wilayah }}</span>
               </div>
               <div class="welcome-pill">
                   <span class="welcome-pill-label">Total Buku</span>
                   <span class="welcome-pill-val tabular-nums">14 Buku Resmi</span>
               </div>
           </div>
       </div>
   </section>

   <!-- Key Statistics Summary (4 KPI Cards) -->
   @include('pages.dashboard.widgets')
   <!-- Data Visualizations Row -->
   <section class="row g-4 mb-4" aria-label="Visualisasi Data Pemerintahan Desa">
       <!-- Left: Monthly Administrative & Population Trend -->
       <div class="col-12 col-lg-7 col-xl-8">
           <div class="simdes-card">
               <div class="simdes-card-header">
                   <div>
                       <h2 class="simdes-card-title">
                           <i class="fa-solid fa-chart-column text-simdes-primary" aria-hidden="true"></i>
                           Tren Pencatatan Buku Administrasi Desa
                       </h2>
                       <p class="simdes-card-subtitle">
                           Perbandingan frekuensi pencatatan dokumen Administrasi Umum dan
                           peristiwa Kependudukan (Data Dummy 2026)
                       </p>
                   </div>
                   <div class="simdes-segmented" role="group" aria-label="Rentang waktu grafik">
                       <button type="button" class="simdes-segmented-btn active" data-trend-period="6m"
                           aria-pressed="true">
                           6 Bulan
                       </button>
                       <button type="button" class="simdes-segmented-btn" data-trend-period="10m" aria-pressed="false">
                           Jan &ndash; Okt
                       </button>
                   </div>
               </div>
               <div class="simdes-card-body">
                   <div class="chart-legend">
                       <span class="chart-legend-item">
                           <span class="chart-legend-swatch" style="background-color:#174C3A;"></span>
                           Administrasi Umum (Surat/Produk Hukum/Aset)
                       </span>
                       <span class="chart-legend-item">
                           <span class="chart-legend-swatch" style="background-color:#D9AE52;"></span>
                           Administrasi Penduduk (Layanan/Mutasi)
                       </span>
                   </div>
                   <div class="chart-stage">
                       <div id="adminTrendChart"></div>
                       <div id="trendChartTooltip" class="chart-tooltip" role="tooltip"></div>
                   </div>
               </div>
           </div>
       </div>

       <!-- Right: Population Distribution by Dusun -->
       @include('pages.dashboard.pie-cart')
   </section>

   <!-- Summary of Administrasi Umum & Administrasi Penduduk -->
   <section class="row g-4 mb-4" aria-label="Ringkasan Buku Administrasi">
       <!-- A. Ringkasan Administrasi Umum -->
       <div class="col-12 col-xl-6" id="sectionAdmUmum">
           <div class="simdes-card">
               <div class="simdes-card-header">
                   <div>
                       <h2 class="simdes-card-title">
                           <i class="fa-solid fa-book-bookmark text-simdes-primary" aria-hidden="true"></i>
                           Ringkasan Administrasi Umum
                       </h2>
                       <p class="simdes-card-subtitle">Status pencatatan pada 9 Buku Administrasi
                           Umum Desa Tiaro</p>
                   </div>
                   <span class="badge-simdes badge-simdes-primary">9 Buku</span>
               </div>
               <div class="simdes-card-body">
                   <div id="summaryAdmUmumList" class="register-summary-grid"></div>
               </div>
           </div>
       </div>

       <!-- B. Ringkasan Administrasi Penduduk -->
       <div class="col-12 col-xl-6" id="sectionAdmPenduduk">
           <div class="simdes-card">
               <div class="simdes-card-header">
                   <div>
                       <h2 class="simdes-card-title">
                           <i class="fa-solid fa-users-viewfinder text-simdes-primary" aria-hidden="true"></i>
                           Ringkasan Administrasi Penduduk
                       </h2>
                       <p class="simdes-card-subtitle">Dinamika mutasi bulan berjalan &amp; status
                           5 Buku Administrasi Penduduk</p>
                   </div>
                   <span class="badge-simdes badge-simdes-accent">5 Buku</span>
               </div>
               <div class="simdes-card-body">
                   <!-- Monthly Demographic Highlights -->
                   <div class="mutasi-mini-grid" aria-label="Mutasi bulan Oktober 2026">
                       <div class="mutasi-mini-box">
                           <span class="mutasi-mini-val">+6</span>
                           <span class="mutasi-mini-lbl">Kelahiran</span>
                       </div>
                       <div class="mutasi-mini-box">
                           <span class="mutasi-mini-val">+11</span>
                           <span class="mutasi-mini-lbl">Pendatang</span>
                       </div>
                       <div class="mutasi-mini-box">
                           <span class="mutasi-mini-val">-4</span>
                           <span class="mutasi-mini-lbl">Pindah Keluar</span>
                       </div>
                       <div class="mutasi-mini-box">
                           <span class="mutasi-mini-val">-2</span>
                           <span class="mutasi-mini-lbl">Kematian</span>
                       </div>
                   </div>

                   <div id="summaryAdmPendudukList" class="register-summary-grid"></div>
               </div>
           </div>
       </div>
   </section>

   <!-- Recent Activities & Village Administration Info -->
   <section class="row g-4" aria-label="Aktivitas Terbaru dan Informasi Desa">
       <!-- Left: Aktivitas Terbaru -->
       <div class="col-12 col-lg-7" id="sectionAktivitas">
           <div class="simdes-card">
               <div class="simdes-card-header">
                   <div>
                       <h2 class="simdes-card-title">
                           <i class="fa-solid fa-clock-rotate-left text-simdes-primary" aria-hidden="true"></i>
                           Aktivitas Administrasi Terbaru
                       </h2>
                       <p class="simdes-card-subtitle">Riwayat pembaruan buku register oleh
                           perangkat Desa Tiaro</p>
                   </div>
                   <div class="simdes-segmented" role="group" aria-label="Filter kategori aktivitas">
                       <button type="button" class="simdes-segmented-btn active" data-activity-filter="all"
                           aria-pressed="true">
                           Semua
                       </button>
                       <button type="button" class="simdes-segmented-btn" data-activity-filter="umum"
                           aria-pressed="false">
                           Adm. Umum
                       </button>
                       <button type="button" class="simdes-segmented-btn" data-activity-filter="penduduk"
                           aria-pressed="false">
                           Penduduk
                       </button>
                   </div>
               </div>
               <div class="simdes-card-body">
                   <div id="recentActivityList" class="activity-timeline"></div>
               </div>
           </div>
       </div>

       <!-- Right: Informasi Administrasi Desa -->
       <div class="col-12 col-lg-5" id="sectionInfoDesa">
           <div class="simdes-card">
               <div class="simdes-card-header">
                   <div>
                       <h2 class="simdes-card-title">
                           <i class="fa-solid fa-landmark text-simdes-primary" aria-hidden="true"></i>
                           Informasi Administrasi Desa
                       </h2>
                       <p class="simdes-card-subtitle">Identitas kelembagaan &amp; ketertiban
                           administrasi Kantor Desa Tiaro</p>
                   </div>
               </div>
               <div class="simdes-card-body">
                   <div class="village-meta-table">
                       <div class="village-meta-row">
                           <span class="village-meta-key">Nama Desa</span>
                           <span class="village-meta-val">Desa Tiaro</span>
                       </div>
                       <div class="village-meta-row">
                           <span class="village-meta-key">Kode Wilayah (Dummy)</span>
                           <span class="village-meta-val tabular-nums">74.06.08.2004</span>
                       </div>
                       <div class="village-meta-row">
                           <span class="village-meta-key">Kepala Desa</span>
                           <span class="village-meta-val">H. Hendra Wijaya, S.Sos</span>
                       </div>
                       <div class="village-meta-row">
                           <span class="village-meta-key">Sekretaris Desa</span>
                           <span class="village-meta-val">Budi Santoso, S.AP</span>
                       </div>
                       <div class="village-meta-row">
                           <span class="village-meta-key">Cakupan Wilayah</span>
                           <span class="village-meta-val">4 Dusun &bull; 12 RT</span>
                       </div>
                       <div class="village-meta-row">
                           <span class="village-meta-key">Aparat Pemerintah Desa</span>
                           <span class="village-meta-val tabular-nums">16 Aparatur Aktif</span>
                       </div>
                       <div class="village-meta-row">
                           <span class="village-meta-key">Jam Layanan Kantor</span>
                           <span class="village-meta-val">Sen&ndash;Kam 08.00&ndash;15.30
                               WITA</span>
                       </div>
                   </div>

                   <div class="compliance-meter">
                       <div class="d-flex align-items-center justify-content-between">
                           <span class="fw-bold" style="font-size:0.82rem; color:var(--simdes-deep-green);">
                               Indeks Ketertiban Buku Desa
                           </span>
                           <span class="fw-bolder tabular-nums text-simdes-primary"
                               style="font-size:0.88rem;">92,8%</span>
                       </div>
                       <div class="compliance-track" role="progressbar" aria-valuenow="93" aria-valuemin="0"
                           aria-valuemax="100" aria-label="Indeks ketertiban buku administrasi desa 92,8 persen">
                           <div class="compliance-fill" style="width:92.8%;"></div>
                       </div>
                       <div class="text-simdes-muted" style="font-size:0.75rem;">
                           Mengacu pedoman administrasi pemerintahan desa (Permendagri Nomor 47
                           Tahun 2016).
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </section>
