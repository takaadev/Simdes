/* ==========================================================================
   SIMDES DESA TIARO — Buku Penduduk Sementara (Kode B.4)
   File: assets/js/buku-penduduk-sementara.js
   Struktur tabel sumber (15 kolom, header 2 tingkat):
     1 NOMOR URUT | 2 NAMA LENGKAP | 3 L | 4 P (JENIS KELAMIN)
     5 KEBANGSAAN | 6 KETURUNAN (NOMOR IDENTITAS/TANDA PENGENAL)
     7 TEMPAT DAN TANGGAL LAHIR/UMUR | 8 PEKERJAAN | 9 KEWARGANEGARAAN
     10 DATANG DARI | 11 MAKSUD DAN TUJUAN KEDATANGAN | 12 NAMA DAN ALAMAT YG DIDATANGI
     13 DATANG TANGGAL | 14 PERGI TANGGAL | 15 KET
   Logika umum (CRUD, filter, sorting, pagination, cetak) ada pada ledger-common.js.
   ========================================================================== */

(() => {
  'use strict';

  const L = window.SimdesLedger;
  if (!L) return;

  const $ = (id) => document.getElementById(id);
  const { esc, DASH, dashOr, formatDate } = L;

  const STATUS_MASIH = 'Masih Tinggal';
  const STATUS_PERGI = 'Sudah Pergi';

  const statusOf = (rec) => (rec.tanggalPergi ? STATUS_PERGI : STATUS_MASIH);

  /** Data contoh fiktif (bukan identitas sungguhan). Umur dihitung pada tanggal datang. */
  const SEED_ROWS = [
    ['Andi Prasetyo Wicaksono', 'Laki-laki', 'TP-SIM-26001', 'Indonesia', 'Jawa', 'Surakarta', '1992-03-14', 'Teknisi Jaringan', 'WNI', 'Kota Kendari', 'Pemasangan jaringan internet desa', 'Budi Santoso, S.AP (Sekretaris Desa)', 'Kantor Desa Tiaro, Jl. Poros Desa No. 1', '2026-02-10', '2026-02-24', 'Penugasan penyedia layanan internet (contoh).'],
    ['Ni Luh Ayu Mahendra', 'Perempuan', 'TP-SIM-26002', 'Indonesia', 'Bali', 'Denpasar', '1996-07-21', 'Dokter Muda', 'WNI', 'Kota Makassar', 'Praktik kerja lapangan di Pustu Desa Tiaro', 'Sri Wahyuni, Amd.Keb (Bidan Desa)', 'Pustu Desa Tiaro, Dusun II Mekar', '2026-03-02', '2026-05-30', ''],
    ['Kenji Takahashi', 'Laki-laki', 'PP-SIM-JP0001', 'Jepang', 'Jepang', 'Osaka', '1986-07-09', 'Pendamping Teknis Budidaya Laut', 'WNA', 'Jakarta Selatan', 'Pendampingan budidaya rumput laut', 'Ruslan Efendi Tahir (Ketua Kelompok Pembudidaya)', 'Dusun III Pesisir, RT 001 / RW 002', '2026-04-15', '', 'Pemegang izin tinggal terbatas (contoh).'],
    ['Maria Fransiska Lengkong', 'Perempuan', 'TP-SIM-26003', 'Indonesia', 'Minahasa', 'Manado', '1993-11-30', 'Bidan', 'WNI', 'Kota Manado', 'Penugasan tenaga kesehatan', 'Sri Wahyuni, Amd.Keb (Bidan Desa)', 'Pustu Desa Tiaro, Dusun II Mekar', '2026-05-11', '', ''],
    ['Rudi Hartono Saputra', 'Laki-laki', 'TP-SIM-26004', 'Indonesia', 'Tionghoa', 'Bandung', '1980-09-02', 'Pedagang Hasil Bumi', 'WNI', 'Kab. Kolaka', 'Membeli hasil panen kakao', 'Hasnawati Binti Rahman (Pengepul Desa)', 'Dusun IV Harapan, RT 003 / RW 002', '2026-06-03', '2026-06-05', ''],
    ['Siti Nurhaliza Putri', 'Perempuan', 'TP-SIM-26005', 'Indonesia', 'Melayu', 'Palembang', '2001-12-18', 'Mahasiswa KKN', 'WNI', 'Kota Kendari', 'Kuliah Kerja Nyata tematik', 'Ilham Maulana Yusuf (Koordinator Posko KKN)', 'Dusun II Mekar, Rumah Posko KKN', '2026-07-20', '2026-09-05', 'Program KKN tematik desa (contoh).'],
    ['Michael Anderson Reed', 'Laki-laki', 'PP-SIM-US0002', 'Amerika Serikat', 'Anglo-Amerika', 'Austin', '1979-04-27', 'Peneliti Ekologi Pesisir', 'WNA', 'Jakarta Pusat', 'Penelitian ekosistem mangrove', 'Darwis Laode Mappatunru (Kepala Dusun III)', 'Dusun III Pesisir', '2026-08-08', '2026-08-29', ''],
    ['Yohanes Pratama Lumbantobing', 'Laki-laki', 'TP-SIM-26006', 'Indonesia', 'Batak', 'Medan', '1989-10-05', 'Penyuluh Pertanian', 'WNI', 'Kab. Bombana', 'Penyuluhan budidaya padi sawah', 'Kelompok Tani Sinar Tiaro (Ketua: Fajar Nugroho)', 'Dusun V Lestari', '2026-10-12', '', ''],
    ['Dewi Kartika Sari', 'Perempuan', 'TP-SIM-25001', 'Indonesia', 'Sunda', 'Bandung', '1994-05-09', 'Fotografer Dokumentasi', 'WNI', 'Kota Bandung', 'Dokumentasi potensi wisata desa', 'Budi Santoso, S.AP (Sekretaris Desa)', 'Kantor Desa Tiaro, Jl. Poros Desa No. 1', '2025-03-12', '2025-03-20', ''],
    ['Ahmad Fauzi Rahman', 'Laki-laki', 'TP-SIM-25002', 'Indonesia', 'Bugis', 'Bone', '1987-01-23', 'Kontraktor', 'WNI', 'Kab. Bone', 'Perbaikan jembatan penghubung dusun', 'Darwis Laode Mappatunru (Kepala Dusun III)', 'Dusun III Pesisir', '2025-06-02', '2025-06-30', ''],
    ['Elena Petrova', 'Perempuan', 'PP-SIM-RU0003', 'Rusia', 'Slavia', 'Kazan', '1990-08-14', 'Wisatawan', 'WNA', 'Bali', 'Wisata bahari dan menyelam', 'Ruslan Efendi Tahir (Ketua Kelompok Pembudidaya)', 'Dusun III Pesisir, RT 001 / RW 002', '2025-09-14', '2025-09-21', 'Wisatawan mancanegara (contoh).'],
    ['Bagus Setiawan', 'Laki-laki', 'TP-SIM-25003', 'Indonesia', 'Jawa', 'Yogyakarta', '1991-12-02', 'Guru Pengganti', 'WNI', 'Kota Yogyakarta', 'Mengajar sementara di SD Negeri Tiaro', 'Kepala SD Negeri Tiaro', 'Jl. Pendidikan No. 3, Dusun I Krajan', '2025-11-04', '', 'Masih tercatat tinggal sejak akhir 2025 (contoh).']
  ];

  const seed = () =>
    SEED_ROWS.map((r, i) => ({
      id: `psm-seed-${String(i + 1).padStart(3, '0')}`,
      namaLengkap: r[0],
      jenisKelamin: r[1],
      nomorIdentitas: r[2],
      kebangsaan: r[3],
      keturunan: r[4],
      tempatLahir: r[5],
      tanggalLahir: r[6],
      umur: L.calcAge(r[6], r[13]),
      pekerjaan: r[7],
      kewarganegaraan: r[8],
      datangDari: r[9],
      maksudTujuan: r[10],
      namaDidatangi: r[11],
      alamatDidatangi: r[12],
      tanggalDatang: r[13],
      tanggalPergi: r[14],
      keterangan: r[15]
    }));

  const isoOrEmpty = (v) => (/^\d{4}-\d{2}-\d{2}$/.test(String(v || '').trim()) ? String(v).trim() : '');
  const txt = (v) => String(v ?? '').trim();

  function sanitize(raw, idx = 0) {
    const umurNum = parseInt(raw?.umur, 10);
    return {
      id: String(raw?.id || `psm-${Date.now()}-${idx}`),
      namaLengkap: txt(raw?.namaLengkap),
      jenisKelamin: raw?.jenisKelamin === 'Perempuan' ? 'Perempuan' : 'Laki-laki',
      nomorIdentitas: txt(raw?.nomorIdentitas),
      kebangsaan: txt(raw?.kebangsaan),
      keturunan: txt(raw?.keturunan),
      tempatLahir: txt(raw?.tempatLahir),
      tanggalLahir: isoOrEmpty(raw?.tanggalLahir),
      umur: Number.isFinite(umurNum) && umurNum >= 0 ? umurNum : '',
      pekerjaan: txt(raw?.pekerjaan),
      kewarganegaraan: raw?.kewarganegaraan === 'WNA' ? 'WNA' : 'WNI',
      datangDari: txt(raw?.datangDari),
      maksudTujuan: txt(raw?.maksudTujuan),
      namaDidatangi: txt(raw?.namaDidatangi),
      alamatDidatangi: txt(raw?.alamatDidatangi),
      tanggalDatang: isoOrEmpty(raw?.tanggalDatang),
      tanggalPergi: isoOrEmpty(raw?.tanggalPergi),
      keterangan: txt(raw?.keterangan)
    };
  }

  const checkMark = (label) =>
    `<span class="ledger-check-mark" aria-hidden="true"><i class="fa-solid fa-check"></i></span><span class="visually-hidden-accessible">${label}</span>`;

  function renderRow(row, no) {
    const isL = row.jenisKelamin === 'Laki-laki';
    const badge =
      row.status === STATUS_PERGI
        ? '<span class="badge-simdes badge-simdes-neutral no-print mt-1">Sudah Pergi</span>'
        : '<span class="badge-simdes badge-simdes-success no-print mt-1">Masih Tinggal</span>';
    const ttl = `${row.tempatLahir ? esc(row.tempatLahir) : '—'}, ${row.tanggalLahir ? esc(formatDate(row.tanggalLahir)) : '—'}`;
    const umurLine = row.umur === '' ? '' : `<span class="cell-sub-line">Umur ${esc(row.umur)} tahun</span>`;
    return `
      <tr data-row-id="${esc(row.id)}">
        <td class="col-no-urut sticky-col-first group-divider-end">${no}</td>
        <td>
          <button type="button" class="tentang-title-btn" data-row-action="detail" data-id="${esc(row.id)}" title="Lihat detail ${esc(row.namaLengkap)}">${esc(row.namaLengkap)}</button>
          <span class="cell-sub-line">No. Identitas: ${esc(row.nomorIdentitas)}</span>
          ${badge}
        </td>
        <td class="text-center">${isL ? checkMark('Laki-laki') : DASH}</td>
        <td class="text-center group-divider-end">${isL ? DASH : checkMark('Perempuan')}</td>
        <td style="white-space:nowrap;">${dashOr(row.kebangsaan)}</td>
        <td class="group-divider-end" style="white-space:nowrap;">${dashOr(row.keturunan)}</td>
        <td>${ttl}${umurLine}</td>
        <td>${dashOr(row.pekerjaan)}</td>
        <td class="text-center fw-semibold group-divider-end" style="white-space:nowrap;">${esc(row.kewarganegaraan)}</td>
        <td>${dashOr(row.datangDari)}</td>
        <td>${dashOr(row.maksudTujuan)}</td>
        <td>${row.namaDidatangi ? `<strong>${esc(row.namaDidatangi)}</strong>` : DASH}${row.alamatDidatangi ? `<span class="cell-sub-line">${esc(row.alamatDidatangi)}</span>` : ''}</td>
        <td class="tabular-nums text-center" style="white-space:nowrap;">${row.tanggalDatang ? esc(formatDate(row.tanggalDatang)) : DASH}</td>
        <td class="tabular-nums text-center group-divider-end" style="white-space:nowrap;">${row.tanggalPergi ? esc(formatDate(row.tanggalPergi)) : DASH}</td>
        ${L.ketCell(row.keterangan, row.id, row.namaLengkap, no)}
      </tr>`;
  }

  const cmpDate = (key) => (a, b) => {
    const x = a[key] || '';
    const y = b[key] || '';
    if (!x && !y) return 0;
    if (!x) return 1; // kosong selalu di akhir saat naik
    if (!y) return -1;
    return x.localeCompare(y);
  };

  const FIELDS = {
    nama: 'psmNama', nomor: 'psmNomorIdentitas', kebangsaan: 'psmKebangsaan', keturunan: 'psmKeturunan',
    tempatLahir: 'psmTempatLahir', tanggalLahir: 'psmTanggalLahir', umur: 'psmUmur', pekerjaan: 'psmPekerjaan',
    datangDari: 'psmDatangDari', maksud: 'psmMaksudTujuan', namaDidatangi: 'psmNamaDidatangi',
    alamatDidatangi: 'psmAlamatDidatangi', tanggalDatang: 'psmTanggalDatang', tanggalPergi: 'psmTanggalPergi',
    keterangan: 'psmKeterangan'
  };
  const val = (key) => $(FIELDS[key])?.value ?? '';
  const setVal = (key, v) => {
    const el = $(FIELDS[key]);
    if (el) el.value = v ?? '';
  };

  let umurAuto = true;

  function autoUmur() {
    if (!umurAuto) return;
    const age = L.calcAge(val('tanggalLahir'), val('tanggalDatang') || L.todayIso());
    setVal('umur', age === '' ? '' : String(age));
  }

  const formEl = () => $('ledgerForm');

  function fillForm(rec) {
    Object.entries({
      nama: rec?.namaLengkap, nomor: rec?.nomorIdentitas, kebangsaan: rec?.kebangsaan, keturunan: rec?.keturunan,
      tempatLahir: rec?.tempatLahir, tanggalLahir: rec?.tanggalLahir, umur: rec?.umur, pekerjaan: rec?.pekerjaan,
      datangDari: rec?.datangDari, maksud: rec?.maksudTujuan, namaDidatangi: rec?.namaDidatangi,
      alamatDidatangi: rec?.alamatDidatangi, tanggalDatang: rec?.tanggalDatang, tanggalPergi: rec?.tanggalPergi,
      keterangan: rec?.keterangan
    }).forEach(([k, v]) => setVal(k, v === undefined ? '' : v));
    L.setRadio(formEl(), 'jenisKelamin', rec?.jenisKelamin || '');
    L.setRadio(formEl(), 'kewarganegaraan', rec?.kewarganegaraan || 'WNI');
  }

  const setText = (id, text) => {
    const el = $(id);
    if (el) el.textContent = text;
  };
  const show = (v) => (String(v ?? '').trim() ? String(v) : '—');

  L.createBook({
    rootId: 'bukuPendudukSementaraPageRoot',
    storageKey: 'simdes_tiaro_buku_penduduk_sementara',
    idPrefix: 'psm',
    defaultYear: '2026',
    columnCount: 15,
    seed,
    sanitize,
    getYear: (rec) => L.yearOfDate(rec.tanggalDatang),
    orderDate: (rec) => rec.tanggalDatang,
    decorate: (rec) => ({ status: statusOf(rec) }),
    recordName: (rec) => rec.namaLengkap,
    titleText: (year) => `BUKU PENDUDUK SEMENTARA TAHUN ${year}`,
    searchFields: ['namaLengkap', 'nomorIdentitas', 'namaDidatangi'],
    filters: [
      { selectId: 'filterGenderSelect', key: 'jenisKelamin' },
      { selectId: 'filterKewarganegaraanSelect', key: 'kewarganegaraan' },
      { selectId: 'filterStatusSelect', key: 'status' }
    ],
    comparators: {
      tanggalLahir: cmpDate('tanggalLahir'),
      tanggalDatang: cmpDate('tanggalDatang'),
      tanggalPergi: cmpDate('tanggalPergi')
    },
    labels: {
      unit: 'penduduk sementara',
      addButton: 'Tambah Penduduk Sementara',
      addTitle: 'Tambah Penduduk Sementara',
      editTitle: 'Edit Penduduk Sementara',
      saveButton: 'Simpan Penduduk Sementara'
    },
    renderRow,
    setup: () => {
      $(FIELDS.tanggalLahir)?.addEventListener('change', autoUmur);
      $(FIELDS.tanggalDatang)?.addEventListener('change', autoUmur);
      $(FIELDS.umur)?.addEventListener('input', () => {
        umurAuto = false;
      });
    },
    updateSummary: (rows) => {
      const count = (fn) => rows.filter(fn).length;
      const set = (id, n) => setText(id, n.toLocaleString('id-ID'));
      set('statTotal', rows.length);
      set('statLaki', count((r) => r.jenisKelamin === 'Laki-laki'));
      set('statPerempuan', count((r) => r.jenisKelamin === 'Perempuan'));
      set('statMasihTinggal', count((r) => r.status === STATUS_MASIH));
      set('statSudahPergi', count((r) => r.status === STATUS_PERGI));
    },
    form: {
      focusId: FIELDS.nama,
      fillNew: ({ year }) => {
        fillForm(null);
        const today = L.todayIso();
        setVal('tanggalDatang', today.startsWith(year) ? today : `${year}-01-01`);
        umurAuto = true;
        autoUmur();
      },
      fillEdit: (rec) => {
        fillForm(rec);
        umurAuto = false;
      },
      read: () => ({
        namaLengkap: val('nama'),
        jenisKelamin: L.getRadio(formEl(), 'jenisKelamin', ''),
        nomorIdentitas: val('nomor'),
        kebangsaan: val('kebangsaan'),
        keturunan: val('keturunan'),
        tempatLahir: val('tempatLahir'),
        tanggalLahir: val('tanggalLahir'),
        umur: val('umur'),
        pekerjaan: val('pekerjaan'),
        kewarganegaraan: L.getRadio(formEl(), 'kewarganegaraan', 'WNI'),
        datangDari: val('datangDari'),
        maksudTujuan: val('maksud'),
        namaDidatangi: val('namaDidatangi'),
        alamatDidatangi: val('alamatDidatangi'),
        tanggalDatang: val('tanggalDatang'),
        tanggalPergi: val('tanggalPergi'),
        keterangan: val('keterangan')
      }),
      validate: (d, records, editId) => {
        if (d.tanggalLahir && d.tanggalDatang && d.tanggalLahir > d.tanggalDatang) {
          return { message: 'Tanggal datang tidak boleh lebih awal daripada tanggal lahir.', focusId: FIELDS.tanggalDatang };
        }
        if (d.tanggalPergi && d.tanggalDatang && d.tanggalPergi < d.tanggalDatang) {
          return { message: 'Tanggal pergi tidak boleh lebih awal daripada tanggal datang.', focusId: FIELDS.tanggalPergi };
        }
        const umur = d.umur === '' ? NaN : Number(d.umur);
        if (d.umur !== '' && (!Number.isInteger(umur) || umur < 0 || umur > 120)) {
          return { message: 'Umur harus berupa bilangan bulat antara 0 dan 120 tahun.', focusId: FIELDS.umur };
        }
        const dup = records.find(
          (r) => r.id !== editId && r.nomorIdentitas.toLowerCase() === d.nomorIdentitas.toLowerCase() && r.tanggalDatang === d.tanggalDatang
        );
        if (dup) {
          return { message: `Nomor identitas ${d.nomorIdentitas} sudah tercatat datang pada tanggal yang sama (${dup.namaLengkap}).`, focusId: FIELDS.nomor };
        }
        return null;
      }
    },
    detail: (rec) => {
      setText('detNomorUrut', `#${rec.urutan ?? '-'}`);
      setText('detTahun', L.yearOfDate(rec.tanggalDatang));
      setText('detNama', rec.namaLengkap);
      const status = statusOf(rec);
      const badge = $('detBadge');
      if (badge) {
        badge.className = `badge-simdes ${status === STATUS_MASIH ? 'badge-simdes-success' : 'badge-simdes-neutral'}`;
        badge.textContent = status;
      }
      setText('detPsmNama', show(rec.namaLengkap));
      setText('detPsmJk', show(rec.jenisKelamin));
      setText('detPsmWn', show(rec.kewarganegaraan));
      setText('detPsmNomor', show(rec.nomorIdentitas));
      setText('detPsmKebangsaan', show(rec.kebangsaan));
      setText('detPsmKeturunan', show(rec.keturunan));
      setText('detPsmTtl', rec.tempatLahir || rec.tanggalLahir ? `${rec.tempatLahir || '—'}, ${rec.tanggalLahir ? formatDate(rec.tanggalLahir) : '—'}` : '—');
      setText('detPsmUmur', rec.umur === '' ? '—' : `${rec.umur} tahun`);
      setText('detPsmPekerjaan', show(rec.pekerjaan));
      setText('detPsmDatangDari', show(rec.datangDari));
      setText('detPsmTglDatang', rec.tanggalDatang ? formatDate(rec.tanggalDatang) : '—');
      setText('detPsmMaksud', show(rec.maksudTujuan));
      setText('detPsmNamaDidatangi', show(rec.namaDidatangi));
      setText('detPsmAlamatDidatangi', show(rec.alamatDidatangi));
      setText('detPsmTglPergi', rec.tanggalPergi ? formatDate(rec.tanggalPergi) : '—');
      setText('detPsmStatus', status);
      setText('detPsmKeterangan', show(rec.keterangan));
    },
    deleteInfo: (rec) => ({
      name: rec.namaLengkap,
      meta: `${rec.nomorIdentitas} • Datang ${rec.tanggalDatang ? formatDate(rec.tanggalDatang) : '—'} • ${statusOf(rec)}`
    }),
    printMeta: (rows) => {
      const masih = rows.filter((r) => r.status === STATUS_MASIH).length;
      return `Total penduduk sementara: ${rows.length} (Masih tinggal: ${masih}, Sudah pergi: ${rows.length - masih})`;
    }
  });
})();

