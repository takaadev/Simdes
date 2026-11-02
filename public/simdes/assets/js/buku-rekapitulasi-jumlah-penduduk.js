/* ==========================================================================
   SIMDES DESA TIARO — Buku Rekapitulasi Jumlah Penduduk Controller
   File: assets/js/buku-rekapitulasi-jumlah-penduduk.js
   Kode Buku: B.3 (Kelompok B. Administrasi Penduduk)

   Struktur tabel sumber: 33 posisi kolom bernomor, header bertingkat.
     1      NOMOR URUT
     2      NAMA DUSUN/LINGKUNGAN
     3–9    JUMLAH PENDUDUK AWAL BULAN
              WNI (L, P) | WNA (L, P) | JML KK | JML ANGGOTA KELUARGA | JML JIWA (7+8)
     10–17  TAMBAHAN BULAN INI
              LAHIR  (WNI L,P | WNA L,P) | DATANG (WNI L,P | WNA L,P)
     18–25  PENGURANGAN BULAN INI
              MENINGGAL (WNI L,P | WNA L,P) | PINDAH (WNI L,P | WNA L,P)
     26–32  JML PENDUDUK AKHIR BULAN
              [posisi 26–27: L, P] | [posisi 28–29: L, P] | JML KK | JML ANGGOTA KELUARGA | JML JIWA
     33     KET

   Seluruh header tabel, formulir, dan tampilan detail dibangun dari satu
   konfigurasi (HEADER_TREE). Jika pemetaan resmi kolom diperjelas, cukup ubah
   konfigurasi tersebut (label, urutan, atau pengelompokan).
   ========================================================================== */

(() => {
  'use strict';

  const STORAGE_KEY = 'simdes_tiaro_buku_rekapitulasi_penduduk';
  const DEFAULT_BULAN = '10';
  const DEFAULT_TAHUN = '2026';

  const BULAN_MAP = {
    '01': 'Januari',
    '02': 'Februari',
    '03': 'Maret',
    '04': 'April',
    '05': 'Mei',
    '06': 'Juni',
    '07': 'Juli',
    '08': 'Agustus',
    '09': 'September',
    '10': 'Oktober',
    '11': 'November',
    '12': 'Desember'
  };

  /**
   * ========================================================================
   * KONFIGURASI HEADER BERTINGKAT (HEADER_TREE)
   * ========================================================================
   * - `label: null` pada sebuah kelompok = nama kelompok pada sumber belum
   *   dapat dipastikan; kolom tetap dipertahankan sebagai posisi bernomor
   *   tanpa nama tambahan (sel header dibiarkan kosong).
   * - Pada KOLOM 26–29 (JML PENDUDUK AKHIR BULAN) sumber hanya menunjukkan
   *   pengelompokan L/P. Isi AKHIR_PAIR_LABELS dengan label resmi (mis. 'WNI',
   *   'WNA') apabila pemetaan telah dipastikan.
   * - Nilai `key` adalah nama properti penyimpanan data pada localStorage.
   */
  const AKHIR_PAIR_LABELS = [null, null];

  const leafL = (key) => ({ label: 'L', formLabel: 'Laki-laki (L)', key });
  const leafP = (key) => ({ label: 'P', formLabel: 'Perempuan (P)', key });
  const wniWnaPairs = (prefix) => [
    { label: 'WNI', children: [leafL(`${prefix}WniL`), leafP(`${prefix}WniP`)] },
    { label: 'WNA', children: [leafL(`${prefix}WnaL`), leafP(`${prefix}WnaP`)] }
  ];

  const HEADER_TREE = {
    leading: [
      { key: 'urutan', label: 'NOMOR URUT', formLabel: 'Nomor Urut', kind: 'auto' },
      { key: 'dusun', label: 'NAMA DUSUN/LINGKUNGAN', formLabel: 'Nama Dusun/Lingkungan', kind: 'text' }
    ],
    groups: [
      {
        id: 'awal',
        tone: 'awal',
        isGroup: true,
        label: 'JUMLAH PENDUDUK AWAL BULAN',
        formTitle: 'B. Jumlah Penduduk Awal Bulan',
        children: [
          { label: 'WNI', children: [leafL('awalWniL'), leafP('awalWniP')] },
          { label: 'WNA', children: [leafL('awalWnaL'), leafP('awalWnaP')] },
          { label: 'JML KK', formLabel: 'Jumlah KK', key: 'awalKk' },
          { label: 'JML ANGGOTA KELUARGA', formLabel: 'Jumlah Anggota Keluarga', key: 'awalAnggota' },
          { label: 'JML JIWA (7+8)', formLabel: 'Jumlah Jiwa (Kol. 7 + Kol. 8)', key: 'awalJiwa', total: true }
        ]
      },
      {
        id: 'tambahan',
        tone: 'tambahan',
        isGroup: true,
        label: 'TAMBAHAN BULAN INI',
        formTitle: 'C. Penambahan Bulan Ini',
        children: [
          { label: 'LAHIR', children: wniWnaPairs('lahir') },
          { label: 'DATANG', children: wniWnaPairs('datang') }
        ]
      },
      {
        id: 'pengurangan',
        tone: 'pengurangan',
        isGroup: true,
        label: 'PENGURANGAN BULAN INI',
        formTitle: 'D. Pengurangan Bulan Ini',
        children: [
          { label: 'MENINGGAL', children: wniWnaPairs('meninggal') },
          { label: 'PINDAH', children: wniWnaPairs('pindah') }
        ]
      },
      {
        id: 'akhir',
        tone: 'akhir',
        isGroup: true,
        label: 'JML PENDUDUK AKHIR BULAN',
        formTitle: 'E. Jumlah Penduduk Akhir Bulan',
        children: [
          { label: AKHIR_PAIR_LABELS[0], children: [leafL('akhirL1'), leafP('akhirP1')] },
          { label: AKHIR_PAIR_LABELS[1], children: [leafL('akhirL2'), leafP('akhirP2')] },
          { label: 'JML KK', formLabel: 'Jumlah KK', key: 'akhirKk' },
          { label: 'JML ANGGOTA KELUARGA', formLabel: 'Jumlah Anggota Keluarga', key: 'akhirAnggota' },
          { label: 'JML JIWA', formLabel: 'Jumlah Jiwa', key: 'akhirJiwa', total: true }
        ]
      }
    ],
    trailing: [{ key: 'keterangan', label: 'KET', formLabel: 'Keterangan', kind: 'text' }]
  };

  // ---------- Turunan konfigurasi: penomoran kolom, jumlah level header, daftar kolom ----------
  const COLUMNS = [];
  let columnCounter = 0;

  function walkTree(node, trail, group) {
    if (!node.children) {
      columnCounter += 1;
      Object.assign(node, {
        no: columnCounter,
        firstNo: columnCounter,
        lastNo: columnCounter,
        leafCount: 1,
        trail,
        group
      });
      COLUMNS.push(node);
      return;
    }
    node.group = group;
    node.firstNo = columnCounter + 1;
    const childTrail = node.isGroup ? [] : [...trail, node.label];
    node.children.forEach((child) => walkTree(child, childTrail, group || node));
    node.lastNo = columnCounter;
    node.leafCount = node.lastNo - node.firstNo + 1;
  }

  HEADER_TREE.leading.forEach((leaf) => walkTree(leaf, [], null));
  HEADER_TREE.groups.forEach((g) => walkTree(g, [], null));
  HEADER_TREE.trailing.forEach((leaf) => walkTree(leaf, [], null));

  const TOTAL_COLUMNS = COLUMNS.length;
  const nodeDepth = (node) => (node.children ? 1 + Math.max(...node.children.map(nodeDepth)) : 1);
  const HEADER_LEVELS = Math.max(...HEADER_TREE.groups.map(nodeDepth));

  const NUM_COLUMNS = COLUMNS.filter((c) => c.group);
  const NUM_KEYS = NUM_COLUMNS.map((c) => c.key);
  const groupLeafKeys = (id) => NUM_COLUMNS.filter((c) => c.group.id === id).map((c) => c.key);
  const TAMBAHAN_KEYS = groupLeafKeys('tambahan');
  const PENGURANGAN_KEYS = groupLeafKeys('pengurangan');
  const AWAL_LP_KEYS = ['awalWniL', 'awalWniP', 'awalWnaL', 'awalWnaP'];
  const AKHIR_LP_KEYS = ['akhirL1', 'akhirP1', 'akhirL2', 'akhirP2'];
  const byKey = (k) => COLUMNS.find((c) => c.key === k);
  const noOf = (k) => byKey(k).no;
  const sumKeys = (rec, keys) => keys.reduce((s, k) => s + (Number(rec[k]) || 0), 0);
  const groupDividerNos = new Set([2, ...HEADER_TREE.groups.map((g) => g.lastNo), TOTAL_COLUMNS - 1]);

  // ---------- Perhitungan & tinjauan konsistensi (hanya komponen yang strukturnya jelas) ----------
  const toInt = (v) => {
    const n = parseInt(v, 10);
    return Number.isFinite(n) && n > 0 ? n : 0;
  };

  function calcAwalJiwa(rec) {
    return toInt(rec.awalKk) + toInt(rec.awalAnggota); // Kol. 9 = Kol. 7 + Kol. 8 (sesuai sumber)
  }

  function calcAkhirJiwa(rec, awalJiwa = toInt(rec.awalJiwa)) {
    return awalJiwa + sumKeys(rec, TAMBAHAN_KEYS) - sumKeys(rec, PENGURANGAN_KEYS);
  }

  function buildReview(rec) {
    const awalJiwa = toInt(rec.awalJiwa);
    const akhirJiwa = toInt(rec.akhirJiwa);
    const tambah = sumKeys(rec, TAMBAHAN_KEYS);
    const kurang = sumKeys(rec, PENGURANGAN_KEYS);
    const expectedAkhir = awalJiwa + tambah - kurang;
    const awalLp = sumKeys(rec, AWAL_LP_KEYS);
    const akhirLp = sumKeys(rec, AKHIR_LP_KEYS);
    const kkAnggota = toInt(rec.awalKk) + toInt(rec.awalAnggota);
    const [a1, a2] = [AWAL_LP_KEYS[0], AWAL_LP_KEYS[3]].map(noOf);
    const [t1, t2] = [TAMBAHAN_KEYS[0], TAMBAHAN_KEYS[TAMBAHAN_KEYS.length - 1]].map(noOf);
    const [k1, k2] = [PENGURANGAN_KEYS[0], PENGURANGAN_KEYS[PENGURANGAN_KEYS.length - 1]].map(noOf);
    const [z1, z2] = [AKHIR_LP_KEYS[0], AKHIR_LP_KEYS[3]].map(noOf);
    const jiwaAwalNo = noOf('awalJiwa');
    const jiwaAkhirNo = noOf('akhirJiwa');

    return {
      info: [
        { label: `Total Penambahan (Lahir + Datang, Kol. ${t1}–${t2})`, value: tambah },
        { label: `Total Pengurangan (Meninggal + Pindah, Kol. ${k1}–${k2})`, value: kurang }
      ],
      checks: [
        {
          id: 'awal-kk-anggota',
          ok: awalJiwa === kkAnggota,
          label: `Kol. ${jiwaAwalNo} = Kol. ${noOf('awalKk')} + Kol. ${noOf('awalAnggota')}`,
          detail: `Jumlah KK + Anggota Keluarga = ${kkAnggota}; Jumlah Jiwa awal diisi ${awalJiwa}.`,
          value: kkAnggota
        },
        {
          id: 'awal-lp',
          ok: awalLp === awalJiwa,
          label: `Jumlah Kol. ${a1}–${a2} (WNI/WNA, L/P) = Kol. ${jiwaAwalNo}`,
          detail: `Jumlah WNI + WNA (L + P) = ${awalLp}; Jumlah Jiwa awal diisi ${awalJiwa}.`,
          value: awalLp
        },
        {
          id: 'akhir-rumus',
          ok: akhirJiwa === expectedAkhir && expectedAkhir >= 0,
          label: `Kol. ${jiwaAkhirNo} = Kol. ${jiwaAwalNo} + Penambahan − Pengurangan`,
          detail: `${awalJiwa} + ${tambah} − ${kurang} = ${expectedAkhir}; Jumlah Jiwa akhir diisi ${akhirJiwa}.`,
          value: expectedAkhir
        },
        {
          id: 'akhir-lp',
          ok: akhirLp === akhirJiwa,
          label: `Jumlah Kol. ${z1}–${z2} (L/P) = Kol. ${jiwaAkhirNo}`,
          detail: `Jumlah L + P pada posisi Kol. ${z1}–${z2} = ${akhirLp}; Jumlah Jiwa akhir diisi ${akhirJiwa}.`,
          value: akhirLp
        }
      ]
    };
  }

  const isConsistent = (rec) => buildReview(rec).checks.every((c) => c.ok);

  // ---------- Data contoh fiktif (angka dihitung konsisten secara matematis) ----------
  const SEED_BASE_SEPTEMBER = [
    { id: 'd1', dusun: 'Dusun I Krajan', v: [412, 405, 1, 0], kk: 238 },
    { id: 'd2', dusun: 'Dusun II Mekar', v: [358, 351, 0, 0], kk: 205 },
    { id: 'd3', dusun: 'Dusun III Pesisir', v: [297, 289, 2, 1], kk: 171 },
    { id: 'd4', dusun: 'Dusun IV Harapan', v: [264, 270, 0, 0], kk: 160 },
    { id: 'd5', dusun: 'Dusun V Lestari', v: [231, 226, 0, 0], kk: 139 },
    { id: 'd6', dusun: 'Dusun VI Sejahtera', v: [189, 195, 1, 1], kk: 118 }
  ];

  // Setiap vektor: [WNI L, WNI P, WNA L, WNA P]
  const SEED_MOVES = [
    {
      tahun: '2026',
      bulan: '09',
      rows: [
        { lahir: [2, 1, 0, 0], datang: [0, 0, 0, 0], meninggal: [0, 0, 0, 0], pindah: [0, 1, 0, 0], kk: 0, ket: 'Data contoh fiktif.' },
        { lahir: [1, 2, 0, 0], datang: [0, 0, 0, 0], meninggal: [1, 0, 0, 0], pindah: [0, 0, 0, 0], kk: 0, ket: 'Data contoh fiktif.' },
        { lahir: [1, 1, 0, 0], datang: [1, 0, 0, 0], meninggal: [0, 0, 0, 0], pindah: [0, 0, 0, 0], kk: 1, ket: 'Satu KK baru datang (contoh).' },
        { lahir: [0, 1, 0, 0], datang: [0, 1, 0, 0], meninggal: [0, 0, 0, 0], pindah: [0, 0, 0, 0], kk: 1, ket: 'Satu KK baru datang (contoh).' },
        { lahir: [1, 0, 0, 0], datang: [0, 0, 0, 0], meninggal: [0, 1, 0, 0], pindah: [1, 0, 0, 0], kk: 0, ket: 'Data contoh fiktif.' },
        { lahir: [0, 1, 0, 0], datang: [0, 0, 0, 0], meninggal: [0, 0, 0, 0], pindah: [0, 0, 0, 0], kk: 0, ket: 'Data contoh fiktif.' }
      ]
    },
    {
      tahun: '2026',
      bulan: '10',
      rows: [
        { lahir: [1, 2, 0, 0], datang: [1, 1, 0, 0], meninggal: [1, 0, 0, 0], pindah: [1, 0, 0, 0], kk: 1, ket: 'Awal bulan sama dengan akhir bulan September 2026.' },
        { lahir: [2, 1, 0, 0], datang: [1, 0, 0, 0], meninggal: [0, 0, 0, 0], pindah: [0, 0, 0, 0], kk: 1, ket: 'Awal bulan sama dengan akhir bulan September 2026.' },
        { lahir: [1, 0, 0, 0], datang: [0, 0, 1, 0], meninggal: [0, 0, 0, 0], pindah: [0, 1, 0, 0], kk: 0, ket: 'Termasuk 1 penduduk WNA datang (contoh).' },
        { lahir: [0, 1, 0, 0], datang: [0, 1, 0, 0], meninggal: [0, 1, 0, 0], pindah: [0, 0, 0, 0], kk: 0, ket: 'Awal bulan sama dengan akhir bulan September 2026.' },
        { lahir: [1, 1, 0, 0], datang: [0, 0, 0, 0], meninggal: [0, 0, 0, 0], pindah: [0, 1, 0, 0], kk: 0, ket: 'Awal bulan sama dengan akhir bulan September 2026.' },
        { lahir: [0, 0, 0, 0], datang: [1, 0, 0, 0], meninggal: [0, 0, 0, 0], pindah: [1, 0, 0, 0], kk: 0, ket: 'Tidak ada perubahan bersih (contoh).' }
      ]
    }
  ];

  function buildSeedData() {
    const state = SEED_BASE_SEPTEMBER.map((d) => ({ ...d, v: [...d.v] }));
    const out = [];
    SEED_MOVES.forEach((period) => {
      period.rows.forEach((mv, idx) => {
        const base = state[idx];
        const sumVec = (vec) => vec.reduce((s, n) => s + n, 0);
        const next = base.v.map((n, i) => n + mv.lahir[i] + mv.datang[i] - mv.meninggal[i] - mv.pindah[i]);
        const awalJiwa = sumVec(base.v);
        const akhirJiwa = sumVec(next);
        const akhirKk = base.kk + mv.kk;
        const rec = {
          id: `rkp-${period.tahun}-${period.bulan}-${base.id}`,
          dusun: base.dusun,
          bulan: period.bulan,
          tahun: period.tahun,
          keterangan: mv.ket,
          awalWniL: base.v[0],
          awalWniP: base.v[1],
          awalWnaL: base.v[2],
          awalWnaP: base.v[3],
          awalKk: base.kk,
          awalAnggota: awalJiwa - base.kk,
          awalJiwa,
          akhirL1: next[0],
          akhirP1: next[1],
          akhirL2: next[2],
          akhirP2: next[3],
          akhirKk,
          akhirAnggota: akhirJiwa - akhirKk,
          akhirJiwa
        };
        ['lahir', 'datang', 'meninggal', 'pindah'].forEach((grp) => {
          rec[`${grp}WniL`] = mv[grp][0];
          rec[`${grp}WniP`] = mv[grp][1];
          rec[`${grp}WnaL`] = mv[grp][2];
          rec[`${grp}WnaP`] = mv[grp][3];
        });
        out.push(rec);
        base.v = next;
        base.kk = akhirKk;
      });
    });
    return out;
  }

  function sanitizeRecord(raw, idx = 0) {
    const rec = {
      id: String(raw?.id || `rkp-${Date.now()}-${idx}`),
      dusun: String(raw?.dusun || '').trim(),
      bulan: BULAN_MAP[String(raw?.bulan || '').padStart(2, '0')] ? String(raw.bulan).padStart(2, '0') : DEFAULT_BULAN,
      tahun: /^\d{4}$/.test(String(raw?.tahun || '')) ? String(raw.tahun) : DEFAULT_TAHUN,
      keterangan: String(raw?.keterangan || '').trim()
    };
    NUM_KEYS.forEach((k) => {
      rec[k] = toInt(raw?.[k]);
    });
    return rec;
  }

  function loadRecords() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      const parsed = raw ? JSON.parse(raw) : null;
      if (Array.isArray(parsed)) return parsed.map((item, i) => sanitizeRecord(item, i));
    } catch (_) {
      // abaikan dan gunakan data contoh
    }
    const seeded = buildSeedData();
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(seeded));
    } catch (_) {
      // penyimpanan penuh / mode privat
    }
    return seeded;
  }

  function saveRecords(list) {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
    } catch (_) {
      // abaikan
    }
  }

  function esc(val) {
    if (window.SimdesApp?.escapeHtml) return window.SimdesApp.escapeHtml(val);
    return String(val ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  const fmtNum = (n) => Number(n || 0).toLocaleString('id-ID');
  const periodTitle = (bulan, tahun) =>
    `REKAPITULASI JUMLAH PENDUDUK BULAN ${(BULAN_MAP[bulan] || 'OKTOBER').toUpperCase()} TAHUN ${tahun || DEFAULT_TAHUN}`;
  const periodLabel = (bulan, tahun) => `${BULAN_MAP[bulan] || '-'} ${tahun || ''}`.trim();

  // ---------- Pembangun HTML dari konfigurasi ----------
  function buildTheadHtml() {
    const rows = Array.from({ length: HEADER_LEVELS }, () => []);
    const collect = (node, level) => {
      rows[level - 1].push({ node, level });
      (node.children || []).forEach((c) => collect(c, level + 1));
    };
    [...HEADER_TREE.leading, ...HEADER_TREE.groups, ...HEADER_TREE.trailing].forEach((n) => collect(n, 1));

    const thHtml = ({ node, level }) => {
      const leaf = !node.children;
      const rowspan = leaf ? HEADER_LEVELS - level + 1 : 1;
      const colspan = node.leafCount;
      const tone = node.group?.tone || node.tone;
      const classes = [];
      let style = '';

      if (node.key === 'urutan') {
        classes.push('col-no-urut', 'sticky-col-first');
        style = 'min-inline-size:84px;';
      } else if (node.key === 'dusun') {
        classes.push('text-start');
        style = 'min-inline-size:230px;';
      } else if (node.key === 'keterangan') {
        classes.push('text-start');
        style = 'min-inline-size:240px;';
      } else if (node.isGroup) {
        classes.push('th-group-top', `th-group-${tone}`);
      } else {
        classes.push(`th-sub-${tone}`);
        if (leaf) classes.push('th-leaf-num');
      }
      if (groupDividerNos.has(node.lastNo)) classes.push('group-divider-end');
      if (node.label === null) classes.push('th-unnamed');

      const attrs = [
        `scope="${leaf ? 'col' : 'colgroup'}"`,
        rowspan > 1 ? `rowspan="${rowspan}"` : '',
        colspan > 1 ? `colspan="${colspan}"` : '',
        `class="${classes.join(' ')}"`,
        style ? `style="${style}"` : '',
        `data-col-first="${node.firstNo}"`,
        `data-col-last="${node.lastNo}"`
      ]
        .filter(Boolean)
        .join(' ');

      const content =
        node.label === null
          ? `<span class="visually-hidden-accessible">Kelompok posisi kolom ${node.firstNo}–${node.lastNo} (label sumber belum ditetapkan)</span>`
          : esc(node.label);
      return `<th ${attrs}>${content}</th>`;
    };

    const headRows = rows.map((cells) => `<tr>${cells.map(thHtml).join('')}</tr>`).join('');
    const numberCells = COLUMNS.map((c) => {
      const cls = ['th-colno'];
      if (c.no === 1) cls.push('sticky-col-first');
      if (groupDividerNos.has(c.no)) cls.push('group-divider-end');
      return `<th scope="col" class="${cls.join(' ')}" data-col-no="${c.no}">${c.no}</th>`;
    }).join('');
    return `${headRows}<tr class="th-colno-row">${numberCells}</tr>`;
  }

  function inputHtml(leaf) {
    const labelPath = [...leaf.trail.filter(Boolean), leaf.formLabel || leaf.label].join(' · ');
    const groupShort = leaf.group.formTitle.replace(/^[A-Z]\.\s*/, '');
    const fieldLabel = `Kol. ${leaf.no} (${groupShort} · ${labelPath})`;
    return `
      <div class="simdes-field mb-2">
        <label for="inp_${leaf.key}" class="simdes-label">${esc(leaf.formLabel || leaf.label)}<span class="recap-col-badge">Kol. ${leaf.no}</span></label>
        <input
          type="number"
          id="inp_${leaf.key}"
          name="${leaf.key}"
          class="simdes-input tabular-nums"
          min="0"
          step="1"
          inputmode="numeric"
          value="0"
          required
          data-recap-key="${leaf.key}"
          data-field-label="${esc(fieldLabel)}"
          aria-describedby="err_${leaf.key}"
        >
        <div id="err_${leaf.key}" class="field-error-msg" role="status">
          <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
          <span class="field-error-text">${esc(fieldLabel)} wajib berupa bilangan bulat 0 atau lebih.</span>
        </div>
      </div>`;
  }

  function renderFormChildren(children, depth) {
    let html = '';
    let leafRun = [];
    let nestedRun = [];
    const flushLeaves = () => {
      if (leafRun.length) {
        html += `<div class="recap-input-grid mb-2">${leafRun.map(inputHtml).join('')}</div>`;
        leafRun = [];
      }
    };
    const flushNested = () => {
      if (nestedRun.length) {
        const colCls = depth === 0 ? 'col-12 col-xl-6' : 'col-12 col-sm-6';
        html += `<div class="row g-3 mb-2">${nestedRun
          .map((n) => {
            const title =
              n.label === null ? `Posisi Kol. ${n.firstNo}–${n.lastNo}` : n.label;
            return `<div class="${colCls}"><fieldset class="recap-subgroup"><legend>${esc(title)}</legend>${renderFormChildren(n.children, depth + 1)}</fieldset></div>`;
          })
          .join('')}</div>`;
        nestedRun = [];
      }
    };
    children.forEach((c) => {
      if (c.children) {
        flushLeaves();
        nestedRun.push(c);
      } else {
        flushNested();
        leafRun.push(c);
      }
    });
    flushNested();
    flushLeaves();
    return html;
  }

  function buildFormGroupsHtml() {
    return HEADER_TREE.groups
      .map(
        (g) => `
      <fieldset class="simdes-fieldset" data-recap-group="${g.id}">
        <legend class="simdes-legend">${esc(g.formTitle)} <span class="recap-col-badge">Kol. ${g.firstNo}–${g.lastNo}</span></legend>
        ${renderFormChildren(g.children, 0)}
      </fieldset>`
      )
      .join('');
  }

  function buildDetailGroupsHtml(rec) {
    return HEADER_TREE.groups
      .map((g) => {
        const cells = NUM_COLUMNS.filter((c) => c.group === g)
          .map((c) => {
            const path = [...c.trail.filter(Boolean), c.label].join(' · ');
            return `<div class="recap-detail-cell"><small>Kol. ${c.no} · ${esc(path)}</small><strong>${fmtNum(rec[c.key])}</strong></div>`;
          })
          .join('');
        return `
          <div class="detail-sheet-box mb-3">
            <div class="detail-item-label">${esc(g.formTitle)} &bull; ${esc(g.label)} (Kol. ${g.firstNo}–${g.lastNo})</div>
            <div class="recap-detail-grid mt-2">${cells}</div>
          </div>`;
      })
      .join('');
  }

  function initBukuRekapitulasiPenduduk() {
    const pageRoot = document.getElementById('bukuRekapPendudukPageRoot');
    if (!pageRoot || !window.SimdesTable) return;

    let records = loadRecords();
    let activeDetailId = null;
    let pendingDeleteId = null;

    const $ = (id) => document.getElementById(id);

    const bookPeriodTitleEl = $('bookPeriodTitle');
    const printPeriodTitleEl = $('printPeriodTitle');
    const printMetaEl = $('printRekapMeta');
    const periodBulanSelect = $('periodBulanSelect');
    const periodTahunSelect = $('periodTahunSelect');

    const statEls = {
      awal: $('statTotalAwal'),
      lahir: $('statTotalLahir'),
      datang: $('statTotalDatang'),
      meninggal: $('statTotalMeninggal'),
      pindah: $('statTotalPindah'),
      akhir: $('statTotalAkhir')
    };

    const btnTambah = $('btnTambahRekap');
    const btnCetak = $('btnCetakRekap');
    const btnResetSample = $('btnResetSampleRekap');

    const searchInput = $('tableSearchInput');
    const clearSearchBtn = $('clearSearchInputBtn');
    const filterDusunSelect = $('filterDusunSelect');
    const pageSizeSelect = $('pageSizeSelect');
    const resetFilterBtn = $('resetTableFilterBtn');

    const theadEl = $('rekapTableHead');
    const tbodyEl = $('rekapTableBody');
    const paginationInfoEl = $('tablePaginationInfo');
    const paginationListEl = $('tablePaginationList');

    const formModalEl = $('recapFormModal');
    const formModalTitle = $('recapFormModalLabel');
    const formEl = $('recapForm');
    const formAlert = $('recapFormAlert');
    const formAlertText = $('recapFormAlertText');
    const formSubmitBtn = $('recapFormSubmitBtn');
    const fId = $('recapRecordId');
    const fNomorUrut = $('recapNomorUrutPreview');
    const fDusun = $('recapDusun');
    const fDusunList = $('recapDusunList');
    const fBulan = $('recapBulan');
    const fTahun = $('recapTahun');
    const fKeterangan = $('recapKeterangan');
    const fAutoCalc = $('recapAutoCalc');
    const btnApplyCalc = $('btnApplyCalc');
    const reviewListEl = $('recapReviewList');

    const detailModalEl = $('recapDetailModal');
    const btnEditFromDetail = $('btnEditRekapFromDetail');
    const deleteModalEl = $('recapDeleteModal');
    const confirmDeleteBtn = $('confirmDeleteRecapBtn');

    // ---- Bangun header tabel & isi formulir dari konfigurasi ----
    if (theadEl) theadEl.innerHTML = buildTheadHtml();
    const groupsHost = $('recapFormGroups');
    if (groupsHost) groupsHost.innerHTML = buildFormGroupsHtml();

    const numInputs = {};
    NUM_KEYS.forEach((k) => {
      numInputs[k] = $(`inp_${k}`);
    });

    window.SimdesForms?.InteractionValidator?.bindForm(formEl);

    // ---- Periode, tahun & filter dusun ----
    const getPeriod = () => ({
      bulan: periodBulanSelect?.value || DEFAULT_BULAN,
      tahun: periodTahunSelect?.value || DEFAULT_TAHUN
    });
    const isNonDefaultPeriod = () => {
      const p = getPeriod();
      return p.bulan !== DEFAULT_BULAN || p.tahun !== DEFAULT_TAHUN;
    };

    function collectYears(extra = []) {
      const set = new Set(['2025', '2026', ...extra.filter(Boolean)]);
      records.forEach((r) => set.add(r.tahun));
      return Array.from(set).sort((a, b) => b.localeCompare(a));
    }

    function syncYearSelects(preferredPeriodYear = null) {
      if (periodTahunSelect) {
        const current = preferredPeriodYear || periodTahunSelect.value || DEFAULT_TAHUN;
        const years = collectYears([current]);
        periodTahunSelect.innerHTML = years.map((y) => `<option value="${esc(y)}">${esc(y)}</option>`).join('');
        periodTahunSelect.value = years.includes(current) ? current : DEFAULT_TAHUN;
      }
      if (fTahun) {
        const current = fTahun.value || getPeriod().tahun;
        const years = collectYears([current, '2024', '2027']);
        fTahun.innerHTML = years.map((y) => `<option value="${esc(y)}">${esc(y)}</option>`).join('');
        fTahun.value = years.includes(current) ? current : DEFAULT_TAHUN;
      }
    }

    function uniqueDusunNames() {
      const seen = new Set();
      const names = [];
      records.forEach((r) => {
        const key = r.dusun.toLowerCase();
        if (r.dusun && !seen.has(key)) {
          seen.add(key);
          names.push(r.dusun);
        }
      });
      return names;
    }

    function syncDusunOptions() {
      const names = uniqueDusunNames();
      if (fDusunList) fDusunList.innerHTML = names.map((n) => `<option value="${esc(n)}"></option>`).join('');
      if (filterDusunSelect) {
        const current = filterDusunSelect.value || 'ALL';
        filterDusunSelect.innerHTML = [
          '<option value="ALL">Semua Dusun/Lingkungan</option>',
          ...names.map((n) => `<option value="${esc(n)}">${esc(n)}</option>`)
        ].join('');
        const keep = current === 'ALL' || names.includes(current);
        filterDusunSelect.value = keep ? current : 'ALL';
        if (!keep) {
          delete tableCtrl.filters.dusun;
        }
      }
    }

    function updateHeadings() {
      const { bulan, tahun } = getPeriod();
      const text = periodTitle(bulan, tahun);
      if (bookPeriodTitleEl) bookPeriodTitleEl.textContent = text;
      if (printPeriodTitleEl) printPeriodTitleEl.textContent = text;
    }

    function getPeriodRecords() {
      const { bulan, tahun } = getPeriod();
      return records
        .filter((r) => r.bulan === bulan && r.tahun === tahun)
        .map((r, i) => ({ ...r, urutan: i + 1 }));
    }

    function updateSummary(rows) {
      const list = Array.isArray(rows) ? rows : [];
      const total = (keys) => list.reduce((s, r) => s + sumKeys(r, keys), 0);
      const lahirKeys = NUM_KEYS.filter((k) => k.startsWith('lahir'));
      const datangKeys = NUM_KEYS.filter((k) => k.startsWith('datang'));
      const meninggalKeys = NUM_KEYS.filter((k) => k.startsWith('meninggal'));
      const pindahKeys = NUM_KEYS.filter((k) => k.startsWith('pindah'));
      const values = {
        awal: total(['awalJiwa']),
        lahir: total(lahirKeys),
        datang: total(datangKeys),
        meninggal: total(meninggalKeys),
        pindah: total(pindahKeys),
        akhir: total(['akhirJiwa'])
      };
      Object.entries(values).forEach(([k, v]) => {
        if (statEls[k]) statEls[k].textContent = fmtNum(v);
      });
    }

    // ---- Render baris tabel (tepat 33 sel per baris) ----
    function numCell(rec, col) {
      const v = Number(rec[col.key]) || 0;
      const cls = ['td-recap-num'];
      if (v === 0) cls.push('is-zero');
      if (col.total) cls.push('is-total');
      if (groupDividerNos.has(col.no)) cls.push('group-divider-end');
      return `<td class="${cls.join(' ')}">${fmtNum(v)}</td>`;
    }

    function renderRowsHtml(rowsList, startIndex = 0, state = null) {
      if (rowsList.length === 0) {
        const { bulan, tahun } = getPeriod();
        const filtered = Boolean(state?.hasActiveQuery);
        return `
          <tr>
            <td colspan="${TOTAL_COLUMNS}">
              <div class="table-empty-state">
                <div class="table-empty-icon"><i class="fa-solid fa-table-list" aria-hidden="true"></i></div>
                <div class="fw-bold mb-1" style="color:var(--simdes-text); font-size:0.95rem;">
                  Belum Ada Rekapitulasi pada ${esc(periodLabel(bulan, tahun))}
                </div>
                <p class="mb-3" style="font-size:0.83rem; max-width:48ch; margin-inline:auto;">
                  Tidak ditemukan data dusun/lingkungan yang cocok dengan periode atau filter saat ini. Tambahkan rekapitulasi baru atau ubah periode.
                </p>
                <div class="d-inline-flex flex-wrap justify-content-center gap-2 no-print">
                  ${
                    filtered || isNonDefaultPeriod()
                      ? `<button type="button" class="btn-simdes-outline" data-empty-action="reset-filter">
                           <i class="fa-solid fa-rotate-left" aria-hidden="true"></i><span>Reset Filter &amp; Periode</span>
                         </button>`
                      : ''
                  }
                  <button type="button" class="btn-simdes-primary" data-empty-action="add-new">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i><span>Tambah Rekapitulasi</span>
                  </button>
                </div>
              </div>
            </td>
          </tr>`;
      }

      return rowsList
        .map((row, idx) => {
          const noUrut = startIndex + idx + 1;
          const cells = COLUMNS.map((col) => {
            if (col.key === 'urutan') {
              return `<td class="col-no-urut sticky-col-first group-divider-end">${noUrut}</td>`;
            }
            if (col.key === 'dusun') {
              return `<td class="group-divider-end" style="white-space:nowrap;">
                <button type="button" class="tentang-title-btn" data-row-action="detail" data-id="${esc(row.id)}" title="Lihat detail ${esc(row.dusun)}">${esc(row.dusun)}</button>
              </td>`;
            }
            if (col.key === 'keterangan') {
              return `<td>
                <div class="ket-cell-content">
                  <div class="ket-text">${row.keterangan ? esc(row.keterangan) : '<span class="text-simdes-muted">&mdash;</span>'}</div>
                  <div class="row-action-group no-print" role="group" aria-label="Aksi baris ${noUrut}">
                    <button type="button" class="btn-row-action" data-row-action="detail" data-id="${esc(row.id)}" title="Lihat Detail" aria-label="Lihat detail ${esc(row.dusun)}"><i class="fa-regular fa-eye" aria-hidden="true"></i></button>
                    <button type="button" class="btn-row-action" data-row-action="edit" data-id="${esc(row.id)}" title="Edit Rekapitulasi" aria-label="Edit rekapitulasi ${esc(row.dusun)}"><i class="fa-regular fa-pen-to-square" aria-hidden="true"></i></button>
                    <button type="button" class="btn-row-action action-delete" data-row-action="delete" data-id="${esc(row.id)}" title="Hapus Rekapitulasi" aria-label="Hapus rekapitulasi ${esc(row.dusun)}"><i class="fa-regular fa-trash-can" aria-hidden="true"></i></button>
                  </div>
                </div>
              </td>`;
            }
            return numCell(row, col);
          }).join('');
          return `<tr data-row-id="${esc(row.id)}">${cells}</tr>`;
        })
        .join('');
    }

    const tableCtrl = window.SimdesTable.create({
      data: getPeriodRecords(),
      pageSize: 5,
      defaultSortKey: 'urutan',
      defaultSortDirection: 'asc',
      searchFields: ['dusun'],
      comparators: { urutan: (a, b) => (a.urutan || 0) - (b.urutan || 0) },
      onRender: (state) => {
        updateSummary(state.allFilteredRows);
        if (resetFilterBtn) resetFilterBtn.disabled = !(state.hasActiveQuery || isNonDefaultPeriod());
        if (tbodyEl) tbodyEl.innerHTML = renderRowsHtml(state.rows, state.startIndex, state);
        if (paginationInfoEl) {
          paginationInfoEl.innerHTML =
            state.totalItems === 0
              ? 'Menampilkan <strong>0</strong> dari <strong>0</strong> data rekapitulasi dusun/lingkungan'
              : `Menampilkan <strong>${state.startEntry}&ndash;${state.endEntry}</strong> dari <strong>${state.totalItems}</strong> dusun/lingkungan${
                  state.hasActiveQuery ? ` (disaring dari ${state.totalAllItems} pada periode ini)` : ''
                }`;
        }
        window.SimdesTable.renderPagination(paginationListEl, state, (page) => tableCtrl.setPage(page));
      }
    });

    function refreshPeriodTable(preservePage = false) {
      updateHeadings();
      tableCtrl.setData(getPeriodRecords(), preservePage);
    }

    // ---- Formulir: pembacaan nilai, perhitungan otomatis & tinjauan ----
    function readFormNumbers() {
      const rec = {};
      NUM_KEYS.forEach((k) => {
        rec[k] = toInt(numInputs[k]?.value);
      });
      return rec;
    }

    function setNum(key, value) {
      if (numInputs[key]) numInputs[key].value = String(Math.max(0, value));
    }

    function applyAutoCalcToInputs() {
      const rec = readFormNumbers();
      const awalJiwa = calcAwalJiwa(rec);
      setNum('awalJiwa', awalJiwa);
      setNum('akhirJiwa', calcAkhirJiwa(rec, awalJiwa) < 0 ? 0 : calcAkhirJiwa(rec, awalJiwa));
    }

    function setJiwaReadonly(readonly) {
      ['awalJiwa', 'akhirJiwa'].forEach((k) => {
        const el = numInputs[k];
        if (!el) return;
        el.readOnly = readonly;
        el.classList.toggle('simdes-input-readonly', readonly);
        if (readonly) el.setAttribute('aria-readonly', 'true');
        else el.removeAttribute('aria-readonly');
      });
    }

    function renderReview() {
      if (!reviewListEl) return;
      const review = buildReview(readFormNumbers());
      const info = review.info
        .map(
          (i) => `<li class="recap-review-item">
            <i class="fa-solid fa-circle-info text-simdes-secondary mt-1" aria-hidden="true"></i>
            <div>${esc(i.label)}</div>
            <span class="recap-review-value">${fmtNum(i.value)}</span>
          </li>`
        )
        .join('');
      const checks = review.checks
        .map(
          (c) => `<li class="recap-review-item ${c.ok ? 'is-ok' : 'is-warn'}" data-check-id="${c.id}" data-check-ok="${c.ok}">
            <i class="fa-solid ${c.ok ? 'fa-circle-check text-success' : 'fa-triangle-exclamation text-danger'} mt-1" aria-hidden="true"></i>
            <div><strong>${esc(c.label)}</strong><div class="small text-simdes-muted">${esc(c.detail)}</div></div>
            <span class="recap-review-value">${c.ok ? 'Sesuai' : 'Tidak sesuai'}</span>
          </li>`
        )
        .join('');
      reviewListEl.innerHTML = info + checks;
    }

    function recomputeForm() {
      if (fAutoCalc?.checked) applyAutoCalcToInputs();
      renderReview();
    }

    function syncNomorUrutPreview() {
      if (!fNomorUrut) return;
      const bulan = fBulan?.value || DEFAULT_BULAN;
      const tahun = fTahun?.value || DEFAULT_TAHUN;
      const inPeriod = records.filter((r) => r.bulan === bulan && r.tahun === tahun);
      const editId = fId?.value || '';
      const label = periodLabel(bulan, tahun);
      if (editId) {
        const idx = inPeriod.findIndex((r) => r.id === editId);
        fNomorUrut.value = `#${idx >= 0 ? idx + 1 : inPeriod.length + 1} (${label})`;
      } else {
        fNomorUrut.value = `Otomatis (#${inPeriod.length + 1} — ${label})`;
      }
    }

    function showFormAlert(msg) {
      if (!formAlert || !formAlertText) return;
      formAlertText.textContent = msg;
      formAlert.classList.add('show');
    }

    function hideFormAlert() {
      formAlert?.classList.remove('show');
      if (formAlertText) formAlertText.textContent = '';
    }

    formEl?.addEventListener('input', (e) => {
      if (e.target?.matches?.('[data-recap-key]')) recomputeForm();
    });

    fAutoCalc?.addEventListener('change', () => {
      setJiwaReadonly(fAutoCalc.checked);
      recomputeForm();
    });

    btnApplyCalc?.addEventListener('click', () => {
      applyAutoCalcToInputs();
      renderReview();
      window.SimdesApp?.showToast({
        title: 'Hasil Hitung Diterapkan',
        message: 'Jumlah Jiwa awal bulan dan akhir bulan diperbarui sesuai rumus.',
        variant: 'primary'
      });
    });

    [fBulan, fTahun].forEach((el) => el?.addEventListener('change', syncNomorUrutPreview));

    // ---- Tambah, edit, detail & hapus ----
    function fillForm(rec) {
      if (fId) fId.value = rec?.id || '';
      if (fDusun) fDusun.value = rec?.dusun || '';
      const p = getPeriod();
      syncYearSelects();
      if (fBulan) fBulan.value = rec?.bulan || p.bulan;
      if (fTahun) {
        const want = rec?.tahun || p.tahun;
        if (![...fTahun.options].some((o) => o.value === want)) {
          fTahun.innerHTML += `<option value="${esc(want)}">${esc(want)}</option>`;
        }
        fTahun.value = want;
      }
      NUM_KEYS.forEach((k) => setNum(k, rec ? rec[k] : 0));
      if (fKeterangan) fKeterangan.value = rec?.keterangan || '';
      if (fAutoCalc) fAutoCalc.checked = true;
      setJiwaReadonly(true);
      hideFormAlert();
      syncNomorUrutPreview();
    }

    function openAddModal() {
      if (!formEl || !formModalEl) return;
      formEl.reset();
      fillForm(null);
      recomputeForm();
      if (formModalTitle) {
        formModalTitle.innerHTML =
          '<i class="fa-solid fa-plus-circle text-simdes-primary me-2" aria-hidden="true"></i>Tambah Rekapitulasi Penduduk';
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML = '<i class="fa-solid fa-floppy-disk" aria-hidden="true"></i><span>Simpan Rekapitulasi</span>';
      }
      window.bootstrap?.Modal.getOrCreateInstance(formModalEl).show();
      formModalEl.addEventListener('shown.bs.modal', () => fDusun?.focus(), { once: true });
    }

    function openEditModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !formEl || !formModalEl) return;
      formEl.reset();
      fillForm(item);
      // Data tersimpan dapat saja tidak sinkron: biarkan tinjauan memperlihatkan selisih tanpa menimpa nilai.
      if (fAutoCalc) fAutoCalc.checked = isConsistent(item);
      setJiwaReadonly(Boolean(fAutoCalc?.checked));
      renderReview();
      if (formModalTitle) {
        formModalTitle.innerHTML =
          '<i class="fa-regular fa-pen-to-square text-simdes-primary me-2" aria-hidden="true"></i>Edit Rekapitulasi Penduduk';
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML = '<i class="fa-solid fa-check" aria-hidden="true"></i><span>Simpan Perubahan</span>';
      }
      window.bootstrap?.Modal.getOrCreateInstance(formModalEl).show();
      formModalEl.addEventListener('shown.bs.modal', () => fDusun?.focus(), { once: true });
    }

    function openDetailModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !detailModalEl) return;
      activeDetailId = item.id;
      const inPeriod = records.filter((r) => r.bulan === item.bulan && r.tahun === item.tahun);
      const setText = (elId, text) => {
        const el = $(elId);
        if (el) el.textContent = text;
      };
      setText('detRecapNomorUrut', `#${inPeriod.findIndex((r) => r.id === item.id) + 1}`);
      setText('detRecapPeriode', periodLabel(item.bulan, item.tahun));
      setText('detRecapDusun', item.dusun);
      setText('detRecapKeterangan', item.keterangan || '—');
      const badge = $('detRecapStatusBadge');
      if (badge) {
        const ok = isConsistent(item);
        badge.className = `badge-simdes ${ok ? 'badge-simdes-success' : 'badge-simdes-warning'}`;
        badge.textContent = ok ? 'Perhitungan Konsisten' : 'Perlu Ditinjau';
      }
      const host = $('recapDetailGroups');
      if (host) host.innerHTML = buildDetailGroupsHtml(item);
      window.bootstrap?.Modal.getOrCreateInstance(detailModalEl).show();
    }

    function openDeleteModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !deleteModalEl) return;
      pendingDeleteId = item.id;
      const setText = (elId, text) => {
        const el = $(elId);
        if (el) el.textContent = text;
      };
      setText('deleteRecapTargetName', item.dusun);
      setText('deleteRecapTargetPeriode', `Periode ${periodLabel(item.bulan, item.tahun)}`);
      setText(
        'deleteRecapTargetMeta',
        `Jumlah jiwa awal ${fmtNum(item.awalJiwa)} • akhir ${fmtNum(item.akhirJiwa)}`
      );
      window.bootstrap?.Modal.getOrCreateInstance(deleteModalEl).show();
    }

    btnEditFromDetail?.addEventListener('click', () => {
      if (!activeDetailId) return;
      const targetId = activeDetailId;
      window.bootstrap?.Modal.getOrCreateInstance(detailModalEl)?.hide();
      window.setTimeout(() => openEditModal(targetId), 180);
    });

    formEl?.addEventListener('submit', (e) => {
      e.preventDefault();
      hideFormAlert();

      const valid = window.SimdesForms?.InteractionValidator
        ? window.SimdesForms.InteractionValidator.validateAll(formEl)
        : formEl.checkValidity();
      if (!valid) {
        showFormAlert('Mohon lengkapi seluruh kolom wajib dengan bilangan bulat 0 atau lebih sebelum menyimpan.');
        return;
      }

      const dusun = (fDusun?.value || '').trim();
      const bulan = fBulan?.value || DEFAULT_BULAN;
      const tahun = fTahun?.value || DEFAULT_TAHUN;
      const existingId = (fId?.value || '').trim();

      const duplicate = records.find(
        (r) => r.id !== existingId && r.bulan === bulan && r.tahun === tahun && r.dusun.toLowerCase() === dusun.toLowerCase()
      );
      if (duplicate) {
        showFormAlert(`Rekapitulasi ${dusun} untuk ${periodLabel(bulan, tahun)} sudah ada. Gunakan Edit pada data tersebut.`);
        fDusun?.focus();
        return;
      }

      const nums = readFormNumbers();
      const failing = buildReview(nums).checks.filter((c) => !c.ok);
      if (failing.length > 0) {
        showFormAlert(
          `Perhitungan belum konsisten: ${failing.map((c) => c.label).join('; ')}. Periksa panel "Tinjau Hasil Perhitungan".`
        );
        reviewListEl?.scrollIntoView?.({ block: 'center', behavior: 'smooth' });
        return;
      }

      const payload = sanitizeRecord({
        id: existingId || `rkp-${Date.now()}`,
        dusun,
        bulan,
        tahun,
        keterangan: fKeterangan?.value || '',
        ...nums
      });

      const isEdit = Boolean(existingId);
      if (isEdit) records = records.map((r) => (r.id === existingId ? payload : r));
      else records.push(payload);
      saveRecords(records);

      const cur = getPeriod();
      let switched = false;
      syncYearSelects(tahun);
      if (cur.tahun !== tahun && periodTahunSelect) {
        periodTahunSelect.value = tahun;
        switched = true;
      }
      if (cur.bulan !== bulan && periodBulanSelect) {
        periodBulanSelect.value = bulan;
        switched = true;
      }
      syncDusunOptions();
      refreshPeriodTable(isEdit && !switched);
      window.bootstrap?.Modal.getOrCreateInstance(formModalEl)?.hide();

      window.SimdesApp?.showToast({
        title: isEdit ? 'Rekapitulasi Diperbarui' : 'Rekapitulasi Ditambahkan',
        message: switched
          ? `"${payload.dusun}" disimpan dan periode dialihkan ke ${periodLabel(bulan, tahun)}.`
          : `Rekapitulasi "${payload.dusun}" berhasil tercatat.`,
        variant: 'success'
      });
    });

    confirmDeleteBtn?.addEventListener('click', () => {
      if (!pendingDeleteId) return;
      const target = records.find((r) => r.id === pendingDeleteId);
      records = records.filter((r) => r.id !== pendingDeleteId);
      pendingDeleteId = null;
      saveRecords(records);
      syncYearSelects();
      syncDusunOptions();
      refreshPeriodTable(true);
      window.bootstrap?.Modal.getOrCreateInstance(deleteModalEl)?.hide();
      window.SimdesApp?.showToast({
        title: 'Rekapitulasi Dihapus',
        message: target
          ? `Rekapitulasi "${target.dusun}" dihapus dan nomor urut disesuaikan otomatis.`
          : 'Data rekapitulasi berhasil dihapus.',
        variant: 'warning'
      });
    });

    // ---- Periode, pencarian, filter, reset, cetak ----
    function handleResetAll() {
      if (searchInput) searchInput.value = '';
      clearSearchBtn?.classList.remove('visible');
      if (filterDusunSelect) filterDusunSelect.value = 'ALL';
      if (periodBulanSelect) periodBulanSelect.value = DEFAULT_BULAN;
      syncYearSelects(DEFAULT_TAHUN);
      if (periodTahunSelect) periodTahunSelect.value = DEFAULT_TAHUN;
      updateHeadings();
      tableCtrl.searchQuery = '';
      tableCtrl.filters = {};
      tableCtrl.setData(getPeriodRecords(), false);
    }

    periodBulanSelect?.addEventListener('change', () => refreshPeriodTable(false));
    periodTahunSelect?.addEventListener('change', () => refreshPeriodTable(false));

    searchInput?.addEventListener('input', () => {
      clearSearchBtn?.classList.toggle('visible', searchInput.value.length > 0);
      tableCtrl.setSearch(searchInput.value);
    });
    clearSearchBtn?.addEventListener('click', () => {
      if (!searchInput) return;
      searchInput.value = '';
      clearSearchBtn.classList.remove('visible');
      tableCtrl.setSearch('');
      searchInput.focus();
    });
    filterDusunSelect?.addEventListener('change', () => {
      const val = filterDusunSelect.value;
      tableCtrl.setFilter('dusun', val === 'ALL' ? null : val);
    });
    pageSizeSelect?.addEventListener('change', () => tableCtrl.setPageSize(Number(pageSizeSelect.value) || 5));
    resetFilterBtn?.addEventListener('click', handleResetAll);
    btnTambah?.addEventListener('click', openAddModal);

    tbodyEl?.addEventListener('click', (e) => {
      const actionBtn = e.target.closest('[data-row-action]');
      if (actionBtn) {
        const action = actionBtn.getAttribute('data-row-action');
        const id = actionBtn.getAttribute('data-id');
        if (!id) return;
        if (action === 'detail') openDetailModal(id);
        else if (action === 'edit') openEditModal(id);
        else if (action === 'delete') openDeleteModal(id);
        return;
      }
      const emptyBtn = e.target.closest('[data-empty-action]');
      if (emptyBtn) {
        const act = emptyBtn.getAttribute('data-empty-action');
        if (act === 'reset-filter') handleResetAll();
        else if (act === 'add-new') openAddModal();
      }
    });

    btnResetSample?.addEventListener('click', () => {
      records = buildSeedData();
      saveRecords(records);
      syncDusunOptions();
      handleResetAll();
      window.SimdesApp?.showToast({
        title: 'Data Contoh Dipulihkan',
        message: `${records.length} data contoh fiktif Buku Rekapitulasi Jumlah Penduduk berhasil dimuat ulang.`,
        variant: 'primary'
      });
    });

    function preparePrintView() {
      updateHeadings();
      const state = tableCtrl.getState();
      if (tbodyEl) {
        tbodyEl.innerHTML = renderRowsHtml(state.allFilteredRows, 0, { ...state, totalItems: state.allFilteredRows.length });
      }
      if (printMetaEl) {
        const totalAkhir = state.allFilteredRows.reduce((s, r) => s + toInt(r.akhirJiwa), 0);
        let nowStr = '';
        try {
          nowStr = new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }).format(new Date());
        } catch (_) {
          nowStr = '';
        }
        printMetaEl.textContent = `Jumlah dusun/lingkungan: ${state.allFilteredRows.length} • Total jiwa akhir bulan: ${fmtNum(totalAkhir)}${nowStr ? ` • Dicetak pada ${nowStr}` : ''}`;
      }
    }

    window.addEventListener('beforeprint', preparePrintView);
    window.addEventListener('afterprint', () => tableCtrl.render());
    btnCetak?.addEventListener('click', () => {
      preparePrintView();
      window.print();
    });

    // ---- Inisialisasi ----
    syncYearSelects(DEFAULT_TAHUN);
    syncDusunOptions();
    updateHeadings();
    tableCtrl.render();
  }

  document.addEventListener('DOMContentLoaded', initBukuRekapitulasiPenduduk);
})();

