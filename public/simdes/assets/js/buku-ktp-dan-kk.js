/* ==========================================================================
   SIMDES DESA TIARO — Buku Kartu Tanda Penduduk dan Buku Kartu Keluarga (Kode B.5)
   File: assets/js/buku-ktp-dan-kk.js
   Struktur tabel sumber (19 kolom, header 2 tingkat):
     1 NOMOR URUT | 2 NO. KK | 3 NAMA LENGKAP | 4 NIK | 5 JENIS KELAMIN
     6 TEMPAT/TANGGAL LAHIR | 7 GOL. DARAH | 8 AGAMA | 9 PENDIDIKAN | 10 PEKERJAAN
     11 ALAMAT | 12 STATUS PERKAWINAN | 13 TEMPAT DAN TANGGAL DIKELUARKAN
     14 STATUS HUB. KELUARGA | 15 KEWARGANEGARAAN | 16 AYAH | 17 IBU (ORANG TUA)
     18 TGL MULAI TINGGAL DI DESA | 19 KET
   Tahun buku ditentukan oleh tahun TANGGAL DIKELUARKAN.
   Logika umum (CRUD, filter, sorting, pagination, cetak) ada pada ledger-common.js.
   ========================================================================== */

(() => {
  'use strict';

  const L = window.SimdesLedger;
  if (!L) return;

  const $ = (id) => document.getElementById(id);
  const { esc, DASH, dashOr, formatDate } = L;

  // ---------- Konfigurasi pilihan (mudah diubah) ----------
  const AGAMA_OPTIONS = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Kepercayaan terhadap Tuhan YME'];
  const PENDIDIKAN_OPTIONS = [
    'Tidak/Belum Sekolah',
    'Belum Tamat SD/Sederajat',
    'Tamat SD/Sederajat',
    'SLTP/Sederajat',
    'SLTA/Sederajat',
    'Diploma I/II',
    'Akademi/Diploma III/Sarjana Muda',
    'Diploma IV/Strata I',
    'Strata II',
    'Strata III'
  ];
  const STATUS_KAWIN_OPTIONS = ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'];
  const GOL_DARAH_OPTIONS = ['A', 'B', 'AB', 'O', 'Tidak Diketahui'];
  const STATUS_HUBUNGAN_OPTIONS = ['Kepala Keluarga', 'Istri', 'Anak', 'Anggota Keluarga Lainnya'];

  // ---------- Data contoh fiktif (NIK/KK berawalan 9974 — bukan identitas sungguhan) ----------
  const ALAMAT = {
    '9974060000270101': 'Jl. Poros Desa No. 21, RT 002 / RW 001, Dusun I Krajan',
    '9974060000270102': 'Jl. Mekar Sari No. 8, RT 001 / RW 002, Dusun II Mekar',
    '0974060000270403': 'Jl. Pesisir Indah No. 3, RT 001 / RW 003, Dusun III Pesisir',
    '9974060000270104': 'Jl. Harapan Baru No. 12, RT 003 / RW 002, Dusun IV Harapan',
    '9974060000270105': 'Jl. Lestari No. 5, RT 002 / RW 004, Dusun V Lestari',
    '9974060000270106': 'Jl. Sejahtera No. 17, RT 001 / RW 004, Dusun VI Sejahtera',
    '9974060000270107': 'Jl. Poros Desa No. 40, RT 004 / RW 001, Dusun I Krajan'
  };

  // [noKk, nama, nik, jk, tempatLahir, tglLahir, golDarah, agama, pendidikan, pekerjaan, statusKawin, hubungan,
  //  ayah, ibu, tempatKeluar, tglKeluar, wn, mulaiTinggal, ket]
  const SEED_ROWS = [
    ['9974060000270101', 'Sutrisno Hadi Wibowo', '9974061205780001', 'Laki-laki', 'Bombana', '1978-05-12', 'B', 'Islam', 'SLTA/Sederajat', 'Petani/Pekebun', 'Kawin', 'Kepala Keluarga', 'Hadi Wibowo', 'Sumarni', 'Kab. Bombana', '2026-01-15', 'WNI', '1978-05-12', ''],
    ['9974060000270101', 'Endang Lestari Wibowo', '9974062308810002', 'Perempuan', 'Kendari', '1981-08-23', 'O', 'Islam', 'SLTP/Sederajat', 'Ibu Rumah Tangga', 'Kawin', 'Istri', 'Slamet Riyadi', 'Kartini', 'Kab. Bombana', '2026-01-15', 'WNI', '2003-06-14', ''],
    ['9974060000270101', 'Dimas Aditya Wibowo', '9974061702050003', 'Laki-laki', 'Bombana', '2005-02-17', 'A', 'Islam', 'SLTA/Sederajat', 'Pelajar/Mahasiswa', 'Belum Kawin', 'Anak', 'Sutrisno Hadi Wibowo', 'Endang Lestari Wibowo', 'Kab. Bombana', '2026-02-03', 'WNI', '2005-02-17', 'KTP pertama setelah genap 17 tahun (contoh).'],
    ['9974060000270102', 'Hendra Gunawan Simanjuntak', '9974060911850004', 'Laki-laki', 'Medan', '1985-11-09', 'AB', 'Kristen', 'Diploma IV/Strata I', 'Karyawan Swasta', 'Kawin', 'Kepala Keluarga', 'Parlindungan Simanjuntak', 'Rosmaida Sinaga', 'Kab. Bombana', '2026-03-10', 'WNI', '2012-01-20', ''],
    ['9974060000270102', 'Maria Magdalena Gunawan', '9974063004880005', 'Perempuan', 'Manado', '1988-04-30', 'A', 'Kristen', 'Diploma IV/Strata I', 'Guru', 'Kawin', 'Istri', 'Frans Lengkong', 'Yuliana Tumbel', 'Kab. Bombana', '2026-03-10', 'WNI', '2012-01-20', ''],
    ['0974060000270403', 'Ketut Wiradana Mahendra', '0074060407900001', 'Laki-laki', 'Denpasar', '1990-07-04', 'O', 'Hindu', 'Akademi/Diploma III/Sarjana Muda', 'Pedagang', 'Cerai Hidup', 'Kepala Keluarga', 'Wayan Mahendra', 'Made Sukerti', 'Kab. Bombana', '2026-04-22', 'WNI', '2019-08-17', 'Nomor KK dan NIK contoh berawalan angka nol.'],
    ['9974060000270104', 'Rahmat Hidayat Pratama', '9974061402830006', 'Laki-laki', 'Kolaka', '1983-02-14', 'B', 'Islam', 'SLTA/Sederajat', 'Nelayan', 'Kawin', 'Kepala Keluarga', 'Hidayat Pratama', 'Nurhayati', 'Kab. Bombana', '2026-05-06', 'WNI', '2008-09-01', ''],
    ['9974060000270104', 'Claire Marie Dubois', '9974061910870007', 'Perempuan', 'Lyon', '1987-10-19', 'O', 'Katolik', 'Diploma IV/Strata I', 'Pengajar', 'Kawin', 'Istri', 'Henri Dubois', 'Sophie Dubois', 'Kab. Bombana', '2026-05-06', 'WNA', '2015-03-12', 'Warga negara asing, anggota KK suami (contoh).'],
    ['9974060000270104', 'Aurelie Dubois Hidayat', '9974062506140008', 'Perempuan', 'Kendari', '2014-06-25', 'A', 'Islam', 'Belum Tamat SD/Sederajat', 'Pelajar/Mahasiswa', 'Belum Kawin', 'Anak', 'Rahmat Hidayat Pratama', 'Claire Marie Dubois', 'Kab. Bombana', '2026-05-06', 'WNI', '2014-06-25', ''],
    ['9974060000270107', 'Ani Kusumawati', '9974060104950009', 'Perempuan', 'Magelang', '1995-04-01', 'Tidak Diketahui', 'Buddha', 'Strata II', 'Aparatur Sipil Negara', 'Belum Kawin', 'Kepala Keluarga', 'Sugeng Kusumo', 'Tutik Hartini', 'Kab. Bombana', '2026-06-18', 'WNI', '2021-02-01', ''],
    ['9974060000270105', 'Liem Hong Sen', '9974061809690010', 'Laki-laki', 'Makassar', '1969-09-18', 'AB', 'Konghucu', 'SLTA/Sederajat', 'Pedagang', 'Kawin', 'Kepala Keluarga', 'Liem Tjoen', 'Oey Siok Hwa', 'Kab. Bombana', '2025-08-20', 'WNI', '1995-04-02', ''],
    ['9974060000270105', 'Tan Mei Lan', '9974062703720011', 'Perempuan', 'Kendari', '1972-03-27', 'A', 'Konghucu', 'SLTP/Sederajat', 'Pedagang', 'Kawin', 'Istri', 'Tan Bun Liong', 'Go Lie Nio', 'Kab. Bombana', '2025-08-20', 'WNI', '1995-04-02', ''],
    ['9974060000270106', 'Darsono Karto Wijoyo', '9974060801600012', 'Laki-laki', 'Magelang', '1960-01-08', 'Tidak Diketahui', 'Kepercayaan terhadap Tuhan YME', 'SLTP/Sederajat', 'Petani/Pekebun', 'Cerai Mati', 'Kepala Keluarga', 'Karto Wijoyo', 'Sarinem', 'Kab. Bombana', '2025-11-12', 'WNI', '1990-05-05', ''],
    ['9974060000270106', 'Siti Rahayu Karto Wijoyo', '9974061506890013', 'Perempuan', 'Magelang', '1989-06-15', 'O', 'Kepercayaan terhadap Tuhan YME', 'SLTA/Sederajat', 'Pedagang', 'Belum Kawin', 'Anak', 'Darsono Karto Wijoyo', 'Wartini', 'Kab. Bombana', '2025-11-12', 'WNI', '1990-05-05', '']
  ];

  const seed = () =>
    SEED_ROWS.map((r, i) => ({
      id: `ktp-seed-${String(i + 1).padStart(3, '0')}`,
      noKk: r[0],
      namaLengkap: r[1],
      nik: r[2],
      jenisKelamin: r[3],
      tempatLahir: r[4],
      tanggalLahir: r[5],
      golonganDarah: r[6],
      agama: r[7],
      pendidikan: r[8],
      pekerjaan: r[9],
      alamat: ALAMAT[r[0]] || '',
      statusPerkawinan: r[10],
      statusHubungan: r[11],
      namaAyah: r[12],
      namaIbu: r[13],
      tempatDikeluarkan: r[14],
      tanggalDikeluarkan: r[15],
      kewarganegaraan: r[16],
      tanggalMulaiTinggal: r[17],
      keterangan: r[18]
    }));

  const txt = (v) => String(v ?? '').trim();
  const digits = (v) => String(v ?? '').replace(/\D+/g, ''); // tetap string: angka nol di depan terjaga
  const isoOrEmpty = (v) => (/^\d{4}-\d{2}-\d{2}$/.test(txt(v)) ? txt(v) : '');
  const oneOf = (v, list, fallback) => (list.includes(v) ? v : fallback);

  function sanitize(raw, idx = 0) {
    return {
      id: String(raw?.id || `ktp-${Date.now()}-${idx}`),
      noKk: digits(raw?.noKk),
      namaLengkap: txt(raw?.namaLengkap),
      nik: digits(raw?.nik),
      jenisKelamin: raw?.jenisKelamin === 'Perempuan' ? 'Perempuan' : 'Laki-laki',
      tempatLahir: txt(raw?.tempatLahir),
      tanggalLahir: isoOrEmpty(raw?.tanggalLahir),
      golonganDarah: oneOf(raw?.golonganDarah, GOL_DARAH_OPTIONS, 'Tidak Diketahui'),
      agama: txt(raw?.agama),
      pendidikan: txt(raw?.pendidikan),
      pekerjaan: txt(raw?.pekerjaan),
      alamat: txt(raw?.alamat),
      statusPerkawinan: oneOf(raw?.statusPerkawinan, STATUS_KAWIN_OPTIONS, 'Belum Kawin'),
      statusHubungan: oneOf(raw?.statusHubungan, STATUS_HUBUNGAN_OPTIONS, 'Anggota Keluarga Lainnya'),
      namaAyah: txt(raw?.namaAyah),
      namaIbu: txt(raw?.namaIbu),
      tempatDikeluarkan: txt(raw?.tempatDikeluarkan),
      tanggalDikeluarkan: isoOrEmpty(raw?.tanggalDikeluarkan),
      kewarganegaraan: raw?.kewarganegaraan === 'WNA' ? 'WNA' : 'WNI',
      tanggalMulaiTinggal: isoOrEmpty(raw?.tanggalMulaiTinggal),
      keterangan: txt(raw?.keterangan)
    };
  }

  const tglText = (iso) => (iso ? esc(formatDate(iso)) : '—');

  function renderRow(row, no) {
    const jk =
      row.jenisKelamin === 'Perempuan'
        ? '<span class="badge-simdes badge-simdes-accent no-print">Perempuan</span><span class="print-only-inline">Perempuan</span>'
        : '<span class="badge-simdes badge-simdes-primary no-print">Laki-laki</span><span class="print-only-inline">Laki-laki</span>';
    return `
      <tr data-row-id="${esc(row.id)}">
        <td class="col-no-urut sticky-col-first group-divider-end">${no}</td>
        <td class="tabular-nums text-center" style="white-space:nowrap;"><span class="doc-num-badge report-badge mb-0">${esc(row.noKk)}</span></td>
        <td>
          <button type="button" class="tentang-title-btn" data-row-action="detail" data-id="${esc(row.id)}" title="Lihat detail ${esc(row.namaLengkap)}">${esc(row.namaLengkap)}</button>
        </td>
        <td class="tabular-nums text-center" style="white-space:nowrap;"><span class="doc-num-badge mb-0">${esc(row.nik)}</span></td>
        <td class="text-center" style="white-space:nowrap;">${jk}</td>
        <td>${row.tempatLahir ? esc(row.tempatLahir) : '—'}, ${tglText(row.tanggalLahir)}</td>
        <td class="text-center fw-semibold">${esc(row.golonganDarah)}</td>
        <td style="white-space:nowrap;">${dashOr(row.agama)}</td>
        <td>${dashOr(row.pendidikan)}</td>
        <td>${dashOr(row.pekerjaan)}</td>
        <td>${dashOr(row.alamat)}</td>
        <td class="text-center" style="white-space:nowrap;">${dashOr(row.statusPerkawinan)}</td>
        <td>${row.tempatDikeluarkan ? esc(row.tempatDikeluarkan) : '—'}, ${tglText(row.tanggalDikeluarkan)}</td>
        <td style="white-space:nowrap;"><span class="fw-semibold" style="color:var(--simdes-deep-green);">${dashOr(row.statusHubungan)}</span></td>
        <td class="text-center fw-semibold" style="white-space:nowrap;">${esc(row.kewarganegaraan)}</td>
        <td>${dashOr(row.namaAyah)}</td>
        <td class="group-divider-end">${dashOr(row.namaIbu)}</td>
        <td class="tabular-nums text-center" style="white-space:nowrap;">${row.tanggalMulaiTinggal ? esc(formatDate(row.tanggalMulaiTinggal)) : DASH}</td>
        ${L.ketCell(row.keterangan, row.id, row.namaLengkap, no)}
      </tr>`;
  }

  const cmpDate = (key) => (a, b) => {
    const x = a[key] || '';
    const y = b[key] || '';
    if (!x && !y) return 0;
    if (!x) return 1;
    if (!y) return -1;
    return x.localeCompare(y);
  };

  const FIELDS = {
    noKk: 'ktpNoKk', nama: 'ktpNama', nik: 'ktpNik', tempatLahir: 'ktpTempatLahir', tanggalLahir: 'ktpTanggalLahir',
    agama: 'ktpAgama', pendidikan: 'ktpPendidikan', pekerjaan: 'ktpPekerjaan', alamat: 'ktpAlamat', ayah: 'ktpAyah',
    ibu: 'ktpIbu', tempatDikeluarkan: 'ktpTempatDikeluarkan', tanggalDikeluarkan: 'ktpTanggalDikeluarkan',
    mulaiTinggal: 'ktpMulaiTinggal', keterangan: 'ktpKeterangan'
  };
  const val = (k) => $(FIELDS[k])?.value ?? '';
  const setVal = (k, v) => {
    const el = $(FIELDS[k]);
    if (el) el.value = v ?? '';
  };
  const formEl = () => $('ledgerForm');
  const setText = (id, t) => {
    const el = $(id);
    if (el) el.textContent = t;
  };
  const show = (v) => (txt(v) ? String(v) : '—');
  const dateText = (iso) => (iso ? formatDate(iso) : '—');

  function fillForm(rec) {
    setVal('noKk', rec?.noKk);
    setVal('nama', rec?.namaLengkap);
    setVal('nik', rec?.nik);
    setVal('tempatLahir', rec?.tempatLahir);
    setVal('tanggalLahir', rec?.tanggalLahir);
    setVal('agama', rec?.agama);
    setVal('pendidikan', rec?.pendidikan);
    setVal('pekerjaan', rec?.pekerjaan);
    setVal('alamat', rec?.alamat);
    setVal('ayah', rec?.namaAyah);
    setVal('ibu', rec?.namaIbu);
    setVal('tempatDikeluarkan', rec?.tempatDikeluarkan);
    setVal('tanggalDikeluarkan', rec?.tanggalDikeluarkan);
    setVal('mulaiTinggal', rec?.tanggalMulaiTinggal);
    setVal('keterangan', rec?.keterangan);
    L.setRadio(formEl(), 'jenisKelamin', rec?.jenisKelamin || '');
    L.setRadio(formEl(), 'golonganDarah', rec?.golonganDarah || 'Tidak Diketahui');
    L.setRadio(formEl(), 'statusPerkawinan', rec?.statusPerkawinan || 'Belum Kawin');
    L.setRadio(formEl(), 'statusHubungan', rec?.statusHubungan || '');
    L.setRadio(formEl(), 'kewarganegaraan', rec?.kewarganegaraan || 'WNI');
  }

  const optionsHtml = (allLabel, list) =>
    `<option value="ALL">${esc(allLabel)}</option>${list.map((o) => `<option value="${esc(o)}">${esc(o)}</option>`).join('')}`;

  L.createBook({
    rootId: 'bukuKtpKkPageRoot',
    storageKey: 'simdes_tiaro_buku_ktp_kk',
    idPrefix: 'ktp',
    defaultYear: '2026',
    columnCount: 19,
    seed,
    sanitize,
    getYear: (rec) => L.yearOfDate(rec.tanggalDikeluarkan),
    orderDate: (rec) => rec.tanggalDikeluarkan,
    recordName: (rec) => rec.namaLengkap,
    titleText: (year) => `BUKU KARTU TANDA PENDUDUK TAHUN ${year} DAN BUKU KARTU KELUARGA`,
    searchFields: ['namaLengkap', 'nik', 'noKk'],
    filters: [
      { selectId: 'filterGenderSelect', key: 'jenisKelamin' },
      { selectId: 'filterAgamaSelect', key: 'agama' },
      { selectId: 'filterStatusKawinSelect', key: 'statusPerkawinan' },
      { selectId: 'filterKewarganegaraanSelect', key: 'kewarganegaraan' }
    ],
    comparators: {
      tanggalLahir: cmpDate('tanggalLahir'),
      tanggalDikeluarkan: cmpDate('tanggalDikeluarkan'),
      tanggalMulaiTinggal: cmpDate('tanggalMulaiTinggal')
    },
    labels: {
      unit: 'penduduk',
      addButton: 'Tambah Data Penduduk',
      addTitle: 'Tambah Data Penduduk',
      editTitle: 'Edit Data Penduduk',
      saveButton: 'Simpan Data Penduduk'
    },
    renderRow,
    setup: () => {
      L.fillSelect($(FIELDS.agama), AGAMA_OPTIONS, 'Pilih agama...');
      L.fillSelect($(FIELDS.pendidikan), PENDIDIKAN_OPTIONS, 'Pilih pendidikan...');
      const fAgama = $('filterAgamaSelect');
      if (fAgama) fAgama.innerHTML = optionsHtml('Semua Agama', AGAMA_OPTIONS);
      const fKawin = $('filterStatusKawinSelect');
      if (fKawin) fKawin.innerHTML = optionsHtml('Semua Status Kawin', STATUS_KAWIN_OPTIONS);
      // NIK & Nomor KK: hanya angka, maksimal 16 digit (tetap berupa teks)
      [FIELDS.noKk, FIELDS.nik].forEach((id) => {
        const el = $(id);
        el?.addEventListener('input', () => {
          const cleaned = digits(el.value).slice(0, 16);
          if (el.value !== cleaned) el.value = cleaned;
        });
      });
    },
    updateSummary: (rows) => {
      const count = (fn) => rows.filter(fn).length;
      const set = (id, n) => setText(id, n.toLocaleString('id-ID'));
      set('statTotal', rows.length);
      set('statLaki', count((r) => r.jenisKelamin === 'Laki-laki'));
      set('statPerempuan', count((r) => r.jenisKelamin === 'Perempuan'));
      set('statWni', count((r) => r.kewarganegaraan === 'WNI'));
      set('statWna', count((r) => r.kewarganegaraan === 'WNA'));
      // Jumlah Kartu Keluarga = Nomor KK unik (bukan jumlah baris)
      set('statKk', new Set(rows.map((r) => r.noKk).filter(Boolean)).size);
    },
    form: {
      focusId: FIELDS.noKk,
      fillNew: () => {
        fillForm(null);
        setRadioDefaults();
      },
      fillEdit: (rec) => fillForm(rec),
      read: () => ({
        noKk: val('noKk'),
        namaLengkap: val('nama'),
        nik: val('nik'),
        jenisKelamin: L.getRadio(formEl(), 'jenisKelamin', ''),
        tempatLahir: val('tempatLahir'),
        tanggalLahir: val('tanggalLahir'),
        golonganDarah: L.getRadio(formEl(), 'golonganDarah', 'Tidak Diketahui'),
        agama: val('agama'),
        pendidikan: val('pendidikan'),
        pekerjaan: val('pekerjaan'),
        alamat: val('alamat'),
        statusPerkawinan: L.getRadio(formEl(), 'statusPerkawinan', 'Belum Kawin'),
        statusHubungan: L.getRadio(formEl(), 'statusHubungan', ''),
        namaAyah: val('ayah'),
        namaIbu: val('ibu'),
        tempatDikeluarkan: val('tempatDikeluarkan'),
        tanggalDikeluarkan: val('tanggalDikeluarkan'),
        kewarganegaraan: L.getRadio(formEl(), 'kewarganegaraan', 'WNI'),
        tanggalMulaiTinggal: val('mulaiTinggal'),
        keterangan: val('keterangan')
      }),
      validate: (d, records, editId) => {
        if (!/^\d{16}$/.test(d.noKk)) return { message: 'Nomor KK harus tepat 16 digit angka.', focusId: FIELDS.noKk };
        if (!/^\d{16}$/.test(d.nik)) return { message: 'NIK harus tepat 16 digit angka.', focusId: FIELDS.nik };
        if (records.some((r) => r.id !== editId && r.nik === d.nik)) {
          return { message: `NIK ${d.nik} sudah terdaftar pada penduduk lain.`, focusId: FIELDS.nik };
        }
        if (d.tanggalLahir && d.tanggalDikeluarkan && d.tanggalDikeluarkan < d.tanggalLahir) {
          return { message: 'Tanggal dikeluarkan tidak boleh lebih awal daripada tanggal lahir.', focusId: FIELDS.tanggalDikeluarkan };
        }
        if (d.tanggalLahir && d.tanggalMulaiTinggal && d.tanggalMulaiTinggal < d.tanggalLahir) {
          return { message: 'Tanggal mulai tinggal tidak boleh lebih awal daripada tanggal lahir.', focusId: FIELDS.mulaiTinggal };
        }
        if (
          d.statusHubungan === 'Kepala Keluarga' &&
          records.some((r) => r.id !== editId && r.noKk === d.noKk && r.statusHubungan === 'Kepala Keluarga')
        ) {
          return { message: `Kartu Keluarga ${d.noKk} sudah memiliki Kepala Keluarga.`, focusId: 'ktpShKepala' };
        }
        return null;
      }
    },
    detail: (rec) => {
      setText('detNomorUrut', `#${rec.urutan ?? '-'}`);
      setText('detTahun', L.yearOfDate(rec.tanggalDikeluarkan));
      setText('detNama', rec.namaLengkap);
      setText('detBadge', rec.statusHubungan || '—');
      setText('detKtpNoKk', show(rec.noKk));
      setText('detKtpNik', show(rec.nik));
      setText('detKtpNama', show(rec.namaLengkap));
      setText('detKtpJk', show(rec.jenisKelamin));
      setText('detKtpGolDarah', show(rec.golonganDarah));
      setText('detKtpTtl', `${rec.tempatLahir || '—'}, ${dateText(rec.tanggalLahir)}`);
      setText('detKtpAgama', show(rec.agama));
      setText('detKtpPendidikan', show(rec.pendidikan));
      setText('detKtpPekerjaan', show(rec.pekerjaan));
      setText('detKtpAlamat', show(rec.alamat));
      setText('detKtpStatusKawin', show(rec.statusPerkawinan));
      setText('detKtpStatusHub', show(rec.statusHubungan));
      setText('detKtpAyah', show(rec.namaAyah));
      setText('detKtpIbu', show(rec.namaIbu));
      setText('detKtpDikeluarkan', `${rec.tempatDikeluarkan || '—'}, ${dateText(rec.tanggalDikeluarkan)}`);
      setText('detKtpWn', show(rec.kewarganegaraan));
      setText('detKtpMulaiTinggal', dateText(rec.tanggalMulaiTinggal));
      setText('detKtpKeterangan', show(rec.keterangan));
    },
    deleteInfo: (rec) => ({
      name: rec.namaLengkap,
      meta: `NIK ${rec.nik} • KK ${rec.noKk} • ${rec.statusHubungan}`
    }),
    printMeta: (rows) => {
      const kk = new Set(rows.map((r) => r.noKk).filter(Boolean)).size;
      return `Total penduduk: ${rows.length} • Jumlah Kartu Keluarga: ${kk}`;
    }
  });

  function setRadioDefaults() {
    L.setRadio(formEl(), 'golonganDarah', 'Tidak Diketahui');
    L.setRadio(formEl(), 'statusPerkawinan', 'Belum Kawin');
    L.setRadio(formEl(), 'kewarganegaraan', 'WNI');
  }
})();

