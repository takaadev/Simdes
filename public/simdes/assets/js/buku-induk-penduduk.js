/* ==========================================================================
   SIMDES DESA TIARO — Buku Induk Penduduk Controller (Kode: B.1)
   File: assets/js/buku-induk-penduduk.js
   Features:
   - Official 16-column 2-tier grouped header table (TEMPAT & TANGGAL LAHIR colspan="2"
     -> TEMPAT LAHIR & TGL kept strictly separate)
   - Automatic & consistent row numbering (NOMOR URUT, never using NIK)
   - 4-section structured modal form (A–D), detail modal, delete confirmation modal
   - Client-side Excel Import (.xlsx, .xls, .csv) + Official .xlsx Template Download
     built in 100% pure Vanilla JavaScript (zero external libraries/backends)
   - Search by Nama Lengkap/Panggilan, NIK, or Nomor KK
   - Multi-criteria filters: Jenis Kelamin, Status Perkawinan, Agama + Reset
   - Column sorting, pagination, full-table landscape print mode, and localStorage
     persistence (`simdes_tiaro_buku_induk_penduduk`)
   ========================================================================== */

(() => {
  'use strict';

  const STORAGE_KEY = 'simdes_tiaro_buku_induk_penduduk';

  /**
   * Data Dummy Fiktif Simulasi UI (12 Contoh Penduduk Desa Tiaro)
   * Seluruh nama, alamat, NIK (awalan 997406...), dan Nomor KK (awalan 997406...)
   * adalah data simulasi fiktif, bukan identitas pribadi penduduk nyata.
   */
  const SEED_DATA = [
    {
      id: 'bip-sim-001',
      namaLengkap: 'La Ode Rahmat Hidayat / Rahmat',
      jenisKelamin: 'Laki-laki',
      statusPerkawinan: 'Kawin',
      tempatLahir: 'Bombana',
      tanggalLahir: '1979-05-14',
      agama: 'Islam',
      pendidikanTerakhir: 'D-IV/S-1',
      pekerjaan: 'Petani/Pekebun',
      dapatMembaca: 'Ya',
      kewarganegaraan: 'WNI',
      alamatLengkap: 'Jl. Poros Desa No. 12, RT 001 / RW 001, Dusun I Krajan',
      kedudukanKeluarga: 'Kepala Keluarga',
      nik: '9974060800000001',
      nomorKk: '9974060000260101',
      keterangan: 'Data contoh fiktif — Ketua Kelompok Tani Krajan'
    },
    {
      id: 'bip-sim-002',
      namaLengkap: 'Wa Ode Nurhaliza, S.Pd. / Liza',
      jenisKelamin: 'Perempuan',
      statusPerkawinan: 'Kawin',
      tempatLahir: 'Bau-Bau',
      tanggalLahir: '1983-09-21',
      agama: 'Islam',
      pendidikanTerakhir: 'D-IV/S-1',
      pekerjaan: 'Guru/Tenaga Pendidik',
      dapatMembaca: 'Ya',
      kewarganegaraan: 'WNI',
      alamatLengkap: 'Jl. Poros Desa No. 12, RT 001 / RW 001, Dusun I Krajan',
      kedudukanKeluarga: 'Istri',
      nik: '9974060800000002',
      nomorKk: '9974060000260101',
      keterangan: 'Data contoh fiktif — Pendidik SD wilayah desa'
    },
    {
      id: 'bip-sim-003',
      namaLengkap: 'Muhammad Farhan Alfarizi / Farhan',
      jenisKelamin: 'Laki-laki',
      statusPerkawinan: 'Belum Kawin',
      tempatLahir: 'Kendari',
      tanggalLahir: '2006-02-11',
      agama: 'Islam',
      pendidikanTerakhir: 'SLTA/Sederajat',
      pekerjaan: 'Pelajar/Mahasiswa',
      dapatMembaca: 'Ya',
      kewarganegaraan: 'WNI',
      alamatLengkap: 'Jl. Poros Desa No. 12, RT 001 / RW 001, Dusun I Krajan',
      kedudukanKeluarga: 'Anak',
      nik: '9974060800000003',
      nomorKk: '9974060000260101',
      keterangan: 'Data contoh fiktif — Pemegang KTP-el pemula'
    },
    {
      id: 'bip-sim-004',
      namaLengkap: 'Yohanes Kurniawan Saputra / Yohan',
      jenisKelamin: 'Laki-laki',
      statusPerkawinan: 'Kawin',
      tempatLahir: 'Makassar',
      tanggalLahir: '1981-11-03',
      agama: 'Kristen',
      pendidikanTerakhir: 'SLTA/Sederajat',
      pekerjaan: 'Wiraswasta',
      dapatMembaca: 'Ya',
      kewarganegaraan: 'WNI',
      alamatLengkap: 'Jl. Cempaka Putih No. 05, RT 002 / RW 001, Dusun II Mekar Sari',
      kedudukanKeluarga: 'Kepala Keluarga',
      nik: '9974060800000004',
      nomorKk: '9974060000260102',
      keterangan: 'Data contoh fiktif — Pelaku UMKM penggilingan hasil bumi'
    },
    {
      id: 'bip-sim-005',
      namaLengkap: 'Maria Magdalena Rante, A.Md.Keb / Maria',
      jenisKelamin: 'Perempuan',
      statusPerkawinan: 'Kawin',
      tempatLahir: 'Toraja',
      tanggalLahir: '1986-04-18',
      agama: 'Kristen',
      pendidikanTerakhir: 'Akademi/D-III/Sarjana Muda',
      pekerjaan: 'Bidan/Perawat',
      dapatMembaca: 'Ya',
      kewarganegaraan: 'WNI',
      alamatLengkap: 'Jl. Cempaka Putih No. 05, RT 002 / RW 001, Dusun II Mekar Sari',
      kedudukanKeluarga: 'Istri',
      nik: '9974060800000005',
      nomorKk: '9974060000260102',
      keterangan: 'Data contoh fiktif — Kader kesehatan posyandu dusun'
    },
    {
      id: 'bip-sim-006',
      namaLengkap: 'I Made Surya Dharmawan / Made',
      jenisKelamin: 'Laki-laki',
      statusPerkawinan: 'Kawin',
      tempatLahir: 'Kolaka',
      tanggalLahir: '1975-07-28',
      agama: 'Hindu',
      pendidikanTerakhir: 'SLTP/Sederajat',
      pekerjaan: 'Petani/Pekebun',
      dapatMembaca: 'Ya',
      kewarganegaraan: 'WNI',
      alamatLengkap: 'Jl. Lembah Asri No. 08, RT 001 / RW 002, Dusun III Lembah Hijau',
      kedudukanKeluarga: 'Kepala Keluarga',
      nik: '9974060800000006',
      nomorKk: '9974060000260103',
      keterangan: 'Data contoh fiktif — Petani kakao dan lada'
    },
    {
      id: 'bip-sim-007',
      namaLengkap: 'Ni Luh Putu Ayu Saraswati / Putu',
      jenisKelamin: 'Perempuan',
      statusPerkawinan: 'Belum Kawin',
      tempatLahir: 'Bombana',
      tanggalLahir: '2003-12-09',
      agama: 'Hindu',
      pendidikanTerakhir: 'D-IV/S-1',
      pekerjaan: 'Karyawan Swasta',
      dapatMembaca: 'Ya',
      kewarganegaraan: 'WNI',
      alamatLengkap: 'Jl. Lembah Asri No. 08, RT 001 / RW 002, Dusun III Lembah Hijau',
      kedudukanKeluarga: 'Anak',
      nik: '9974060800000007',
      nomorKk: '9974060000260103',
      keterangan: 'Data contoh fiktif simulasi UI'
    },
    {
      id: 'bip-sim-008',
      namaLengkap: 'Hj. Halimah Daeng Bunga / Nenek Halimah',
      jenisKelamin: 'Perempuan',
      statusPerkawinan: 'Cerai Mati',
      tempatLahir: 'Rumbia',
      tanggalLahir: '1951-03-06',
      agama: 'Islam',
      pendidikanTerakhir: 'Belum Tamat SD/Sederajat',
      pekerjaan: 'Mengurus Rumah Tangga',
      dapatMembaca: 'Tidak',
      kewarganegaraan: 'WNI',
      alamatLengkap: 'Jl. Melati Desa No. 03, RT 003 / RW 001, Dusun I Krajan',
      kedudukanKeluarga: 'Kepala Keluarga',
      nik: '9974060800000008',
      nomorKk: '9974060000260104',
      keterangan: 'Data contoh fiktif — Lansia penerima layanan posbindu'
    },
    {
      id: 'bip-sim-009',
      namaLengkap: 'Fransiskus Xaverius Lolo / Frans',
      jenisKelamin: 'Laki-laki',
      statusPerkawinan: 'Cerai Hidup',
      tempatLahir: 'Maumere',
      tanggalLahir: '1984-08-19',
      agama: 'Katolik',
      pendidikanTerakhir: 'SLTA/Sederajat',
      pekerjaan: 'Tukang Kayu/Batu',
      dapatMembaca: 'Ya',
      kewarganegaraan: 'WNI',
      alamatLengkap: 'Jl. Dermaga Pesisir No. 19, RT 002 / RW 002, Dusun IV Tiaro Jaya',
      kedudukanKeluarga: 'Kepala Keluarga',
      nik: '9974060800000009',
      nomorKk: '9974060000260105',
      keterangan: 'Data contoh fiktif — Domisili tetap sejak 2018'
    },
    {
      id: 'bip-sim-010',
      namaLengkap: 'Ruslan Baharuddin / Ruslan',
      jenisKelamin: 'Laki-laki',
      statusPerkawinan: 'Kawin',
      tempatLahir: 'Kabaena',
      tanggalLahir: '1978-01-25',
      agama: 'Islam',
      pendidikanTerakhir: 'Tamat SD/Sederajat',
      pekerjaan: 'Nelayan/Perikanan',
      dapatMembaca: 'Ya',
      kewarganegaraan: 'WNI',
      alamatLengkap: 'Jl. Dermaga Pesisir No. 24, RT 003 / RW 002, Dusun IV Tiaro Jaya',
      kedudukanKeluarga: 'Kepala Keluarga',
      nik: '9974060800000010',
      nomorKk: '9974060000260106',
      keterangan: 'Data contoh fiktif — Kelompok nelayan tangkap pesisir'
    },
    {
      id: 'bip-sim-011',
      namaLengkap: 'Siti Aminah Latif / Aminah',
      jenisKelamin: 'Perempuan',
      statusPerkawinan: 'Kawin',
      tempatLahir: 'Kasipute',
      tanggalLahir: '1982-06-15',
      agama: 'Islam',
      pendidikanTerakhir: 'SLTP/Sederajat',
      pekerjaan: 'Pedagang',
      dapatMembaca: 'Ya',
      kewarganegaraan: 'WNI',
      alamatLengkap: 'Jl. Dermaga Pesisir No. 24, RT 003 / RW 002, Dusun IV Tiaro Jaya',
      kedudukanKeluarga: 'Istri',
      nik: '9974060800000011',
      nomorKk: '9974060000260106',
      keterangan: 'Data contoh fiktif — Pedagang hasil laut pasar desa'
    },
    {
      id: 'bip-sim-012',
      namaLengkap: 'Evelyn Wijaya Kusuma, M.M. / Evelyn',
      jenisKelamin: 'Perempuan',
      statusPerkawinan: 'Belum Kawin',
      tempatLahir: 'Kendari',
      tanggalLahir: '1994-10-30',
      agama: 'Buddha',
      pendidikanTerakhir: 'S-2',
      pekerjaan: 'Wiraswasta',
      dapatMembaca: 'Ya',
      kewarganegaraan: 'WNI',
      alamatLengkap: 'Jl. Mekar Utama No. 11, RT 001 / RW 001, Dusun II Mekar Sari',
      kedudukanKeluarga: 'Kepala Keluarga',
      nik: '9974060800000012',
      nomorKk: '9974060000260107',
      keterangan: 'Data contoh fiktif — Pengelola koperasi tani desa'
    }
  ];

  /* ------------------------------------------------------------------------
     Pure Vanilla JS Spreadsheet Engine (.xlsx / .xls / .csv)
     ------------------------------------------------------------------------ */
  const CRC32_TABLE = (() => {
    const tbl = new Uint32Array(256);
    for (let i = 0; i < 256; i += 1) {
      let c = i;
      for (let k = 0; k < 8; k += 1) {
        c = c & 1 ? 0xedb88320 ^ (c >>> 1) : c >>> 1;
      }
      tbl[i] = c >>> 0;
    }
    return tbl;
  })();

  function computeCrc32(bytes) {
    let crc = 0xffffffff;
    for (let i = 0; i < bytes.length; i += 1) {
      crc = CRC32_TABLE[(crc ^ bytes[i]) & 0xff] ^ (crc >>> 8);
    }
    return (crc ^ 0xffffffff) >>> 0;
  }

  function encodeXmlAttr(val) {
    return String(val ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function colIndexToLetters(idx) {
    let n = idx + 1;
    let letters = '';
    while (n > 0) {
      const rem = (n - 1) % 26;
      letters = String.fromCharCode(65 + rem) + letters;
      n = Math.floor((n - 1) / 26);
    }
    return letters;
  }

  function colLettersToIndex(cellRef) {
    const match = String(cellRef || '').match(/^[A-Za-z]+/);
    if (!match) return -1;
    const letters = match[0].toUpperCase();
    let idx = 0;
    for (let i = 0; i < letters.length; i += 1) {
      idx = idx * 26 + (letters.charCodeAt(i) - 64);
    }
    return idx - 1;
  }

  /**
   * Builds an uncompressed (STORE method 0) ZIP archive Uint8Array for a valid .xlsx file.
   */
  function createStoredZipBytes(entries) {
    const encoder = new TextEncoder();
    const localParts = [];
    const centralParts = [];
    let offset = 0;

    entries.forEach((entry) => {
      const nameBytes = encoder.encode(entry.name);
      const dataBytes = typeof entry.content === 'string' ? encoder.encode(entry.content) : entry.content;
      const crc = computeCrc32(dataBytes);
      const size = dataBytes.length;

      const localHeader = new Uint8Array(30 + nameBytes.length);
      const lv = new DataView(localHeader.buffer);
      lv.setUint32(0, 0x04034b50, true);
      lv.setUint16(4, 20, true);
      lv.setUint16(6, 0x0800, true); // UTF-8 filename flag
      lv.setUint16(8, 0, true); // method 0 = STORE
      lv.setUint16(10, 0, true);
      lv.setUint16(12, 0x5d61, true);
      lv.setUint32(14, crc, true);
      lv.setUint32(18, size, true);
      lv.setUint32(22, size, true);
      lv.setUint16(26, nameBytes.length, true);
      lv.setUint16(28, 0, true);
      localHeader.set(nameBytes, 30);

      const centralHeader = new Uint8Array(46 + nameBytes.length);
      const cv = new DataView(centralHeader.buffer);
      cv.setUint32(0, 0x02014b50, true);
      cv.setUint16(4, 20, true);
      cv.setUint16(6, 20, true);
      cv.setUint16(8, 0x0800, true);
      cv.setUint16(10, 0, true);
      cv.setUint16(12, 0, true);
      cv.setUint16(14, 0x5d61, true);
      cv.setUint32(16, crc, true);
      cv.setUint32(20, size, true);
      cv.setUint32(24, size, true);
      cv.setUint16(28, nameBytes.length, true);
      cv.setUint16(30, 0, true);
      cv.setUint16(32, 0, true);
      cv.setUint16(34, 0, true);
      cv.setUint16(36, 0, true);
      cv.setUint32(38, 0, true);
      cv.setUint32(42, offset, true);
      centralHeader.set(nameBytes, 46);

      localParts.push(localHeader, dataBytes);
      centralParts.push(centralHeader);
      offset += localHeader.length + dataBytes.length;
    });

    const centralOffset = offset;
    const centralSize = centralParts.reduce((acc, arr) => acc + arr.length, 0);

    const eocd = new Uint8Array(22);
    const ev = new DataView(eocd.buffer);
    ev.setUint32(0, 0x06054b50, true);
    ev.setUint16(4, 0, true);
    ev.setUint16(6, 0, true);
    ev.setUint16(8, entries.length, true);
    ev.setUint16(10, entries.length, true);
    ev.setUint32(12, centralSize, true);
    ev.setUint32(16, centralOffset, true);
    ev.setUint16(20, 0, true);

    const totalLen = centralOffset + centralSize + eocd.length;
    const out = new Uint8Array(totalLen);
    let pos = 0;
    [...localParts, ...centralParts, eocd].forEach((chunk) => {
      out.set(chunk, pos);
      pos += chunk.length;
    });
    return out;
  }

  /**
   * Generates an official 16-column .xlsx template workbook with fictitious sample rows.
   */
  function buildBukuIndukTemplateXlsxBytes() {
    const headers = [
      'NOMOR URUT',
      'NAMA LENGKAP/PANGGILAN',
      'JENIS KELAMIN',
      'STATUS PERKAWINAN',
      'TEMPAT LAHIR',
      'TGL',
      'AGAMA',
      'PENDIDIKAN TERAKHIR',
      'PEKERJAAN',
      'DAPAT MEMBACA HURUF',
      'KEWARGANEGARAAN',
      'ALAMAT LENGKAP',
      'KEDUDUKAN DLM KELUARGA',
      'NIK',
      'NOMOR KK',
      'KET'
    ];

    const sampleRows = [
      [
        '1',
        'Arman Syahputra, S.Pt. / Arman',
        'Laki-laki',
        'Kawin',
        'Bombana',
        '1985-04-12',
        'Islam',
        'D-IV/S-1',
        'Petani/Pekebun',
        'Ya',
        'WNI',
        'Jl. Poros Desa No. 21, RT 002 / RW 001, Dusun I Krajan',
        'Kepala Keluarga',
        '9974060800000101',
        '9974060000260201',
        'Data contoh fiktif import Excel'
      ],
      [
        '2',
        'Ratna Dewi Lestari / Ratna',
        'Perempuan',
        'Kawin',
        'Kendari',
        '1988-09-07',
        'Islam',
        'SLTA/Sederajat',
        'Pedagang',
        'Ya',
        'WNI',
        'Jl. Poros Desa No. 21, RT 002 / RW 001, Dusun I Krajan',
        'Istri',
        '9974060800000102',
        '9974060000260201',
        'Data contoh fiktif import Excel'
      ],
      [
        '3',
        'Damar Pratama Syahputra / Damar',
        'Laki-laki',
        'Belum Kawin',
        'Bombana',
        '2011-01-23',
        'Islam',
        'SLTP/Sederajat',
        'Pelajar/Mahasiswa',
        'Ya',
        'WNI',
        'Jl. Poros Desa No. 21, RT 002 / RW 001, Dusun I Krajan',
        'Anak',
        '9974060800000103',
        '9974060000260201',
        'Data contoh fiktif import Excel'
      ]
    ];

    const allRows = [headers, ...sampleRows];
    const colWidths = [12, 32, 16, 18, 18, 15, 14, 24, 22, 20, 18, 42, 24, 20, 20, 28];

    const colsXml = colWidths
      .map((w, idx) => `<col min="${idx + 1}" max="${idx + 1}" width="${w}" customWidth="1"/>`)
      .join('');

    const rowsXml = allRows
      .map((row, rIdx) => {
        const rowNum = rIdx + 1;
        const cellsXml = row
          .map((val, cIdx) => {
            const ref = `${colIndexToLetters(cIdx)}${rowNum}`;
            // Style 1 = header bold green, Style 2 = text '@' (prevents scientific notation on 16-digit NIK/KK), Style 0 = normal
            const styleIdx = rIdx === 0 ? 1 : cIdx === 13 || cIdx === 14 ? 2 : 0;
            return `<c r="${ref}" s="${styleIdx}" t="inlineStr"><is><t>${encodeXmlAttr(val)}</t></is></c>`;
          })
          .join('');
        return `<row r="${rowNum}">${cellsXml}</row>`;
      })
      .join('');

    const sheetXml =
      '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' +
      '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">' +
      `<cols>${colsXml}</cols>` +
      `<sheetData>${rowsXml}</sheetData>` +
      '</worksheet>';

    const stylesXml =
      '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' +
      '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">' +
      '<fonts count="2">' +
      '<font><sz val="10"/><color rgb="FF263A32"/><name val="Calibri"/></font>' +
      '<font><b/><sz val="10"/><color rgb="FF10382D"/><name val="Calibri"/></font>' +
      '</fonts>' +
      '<fills count="3">' +
      '<fill><patternFill patternType="none"/></fill>' +
      '<fill><patternFill patternType="gray125"/></fill>' +
      '<fill><patternFill patternType="solid"><fgColor rgb="FFEAF2EE"/></patternFill></fill>' +
      '</fills>' +
      '<borders count="2">' +
      '<border><left/><right/><top/><bottom/><diagonal/></border>' +
      '<border><left style="thin"><color rgb="FFCED9D1"/></left><right style="thin"><color rgb="FFCED9D1"/></right><top style="thin"><color rgb="FFCED9D1"/></top><bottom style="medium"><color rgb="FF174C3A"/></bottom><diagonal/></border>' +
      '</borders>' +
      '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>' +
      '<cellXfs count="3">' +
      '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>' +
      '<xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1"/>' +
      '<xf numFmtId="49" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>' +
      '</cellXfs>' +
      '</styleSheet>';

    const workbookXml =
      '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' +
      '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" ' +
      'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">' +
      '<sheets><sheet name="Buku Induk Penduduk" sheetId="1" r:id="rId1"/></sheets>' +
      '</workbook>';

    const workbookRelsXml =
      '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' +
      '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' +
      '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>' +
      '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>' +
      '</Relationships>';

    const rootRelsXml =
      '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' +
      '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' +
      '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>' +
      '</Relationships>';

    const contentTypesXml =
      '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' +
      '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">' +
      '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>' +
      '<Default Extension="xml" ContentType="application/xml"/>' +
      '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>' +
      '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>' +
      '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>' +
      '</Types>';

    return createStoredZipBytes([
      { name: '[Content_Types].xml', content: contentTypesXml },
      { name: '_rels/.rels', content: rootRelsXml },
      { name: 'xl/workbook.xml', content: workbookXml },
      { name: 'xl/_rels/workbook.xml.rels', content: workbookRelsXml },
      { name: 'xl/styles.xml', content: stylesXml },
      { name: 'xl/worksheets/sheet1.xml', content: sheetXml }
    ]);
  }

  async function decompressDeflateRaw(compressedBytes) {
    if (typeof DecompressionStream === 'undefined') {
      throw new Error('Peramban tidak mendukung pembacaan berkas terkompresi (.xlsx). Silakan simpan sebagai .csv.');
    }
    const ds = new DecompressionStream('deflate-raw');
    const stream = new Blob([compressedBytes]).stream().pipeThrough(ds);
    const ab = await new Response(stream).arrayBuffer();
    return new Uint8Array(ab);
  }

  /**
   * Reads a ZIP (.xlsx) ArrayBuffer via Central Directory and returns UTF-8 XML strings by path.
   */
  async function extractZipTextEntries(arrayBuffer) {
    const bytes = new Uint8Array(arrayBuffer);
    const view = new DataView(arrayBuffer);
    const decoder = new TextDecoder('utf-8');

    let eocdPos = -1;
    const minPos = Math.max(0, bytes.length - 65557);
    for (let i = bytes.length - 22; i >= minPos; i -= 1) {
      if (view.getUint32(i, true) === 0x06054b50) {
        eocdPos = i;
        break;
      }
    }
    if (eocdPos < 0) {
      throw new Error('Format arsip Excel (.xlsx) tidak valid atau rusak.');
    }

    const totalEntries = view.getUint16(eocdPos + 10, true);
    const centralDirOffset = view.getUint32(eocdPos + 16, true);
    const files = new Map();

    let ptr = centralDirOffset;
    for (let idx = 0; idx < totalEntries && ptr + 46 <= bytes.length; idx += 1) {
      if (view.getUint32(ptr, true) !== 0x02014b50) break;
      const method = view.getUint16(ptr + 10, true);
      const compSize = view.getUint32(ptr + 20, true);
      const nameLen = view.getUint16(ptr + 28, true);
      const extraLen = view.getUint16(ptr + 30, true);
      const commentLen = view.getUint16(ptr + 32, true);
      const localHeaderOffset = view.getUint32(ptr + 42, true);

      const fileName = decoder.decode(bytes.subarray(ptr + 46, ptr + 46 + nameLen));
      ptr += 46 + nameLen + extraLen + commentLen;

      if (!fileName.endsWith('.xml') && !fileName.endsWith('.rels')) continue;
      if (localHeaderOffset + 30 > bytes.length) continue;

      const localNameLen = view.getUint16(localHeaderOffset + 26, true);
      const localExtraLen = view.getUint16(localHeaderOffset + 28, true);
      const dataStart = localHeaderOffset + 30 + localNameLen + localExtraLen;
      const compSlice = bytes.subarray(dataStart, dataStart + compSize);

      let rawBytes;
      if (method === 0) {
        rawBytes = compSlice;
      } else if (method === 8) {
        rawBytes = await decompressDeflateRaw(compSlice);
      } else {
        continue;
      }
      files.set(fileName.replace(/^\/+/, ''), decoder.decode(rawBytes));
    }

    return files;
  }

  async function parseXlsxBuffer(arrayBuffer) {
    const files = await extractZipTextEntries(arrayBuffer);
    const parser = new DOMParser();

    // 1. Shared Strings Table
    const sharedStrings = [];
    const sstXml = files.get('xl/sharedStrings.xml');
    if (sstXml) {
      const sstDoc = parser.parseFromString(sstXml, 'application/xml');
      const siNodes = sstDoc.getElementsByTagName('si');
      for (let i = 0; i < siNodes.length; i += 1) {
        const tNodes = siNodes[i].getElementsByTagName('t');
        let combined = '';
        for (let j = 0; j < tNodes.length; j += 1) {
          combined += tNodes[j].textContent || '';
        }
        sharedStrings.push(combined);
      }
    }

    // 2. First Worksheet XML
    let sheetXml = files.get('xl/worksheets/sheet1.xml');
    if (!sheetXml) {
      for (const [pathKey, content] of files.entries()) {
        if (pathKey.startsWith('xl/worksheets/sheet') && pathKey.endsWith('.xml')) {
          sheetXml = content;
          break;
        }
      }
    }
    if (!sheetXml) {
      throw new Error('Lembar kerja (worksheet) tidak ditemukan di dalam berkas .xlsx.');
    }

    const sheetDoc = parser.parseFromString(sheetXml, 'application/xml');
    const rowNodes = sheetDoc.getElementsByTagName('row');
    const matrix = [];

    for (let r = 0; r < rowNodes.length; r += 1) {
      const cellNodes = rowNodes[r].getElementsByTagName('c');
      const rowValues = [];
      let nextFallbackCol = 0;

      for (let c = 0; c < cellNodes.length; c += 1) {
        const cell = cellNodes[c];
        const ref = cell.getAttribute('r');
        const colIdx = ref ? colLettersToIndex(ref) : nextFallbackCol;
        const targetCol = colIdx >= 0 ? colIdx : nextFallbackCol;
        nextFallbackCol = targetCol + 1;

        const type = cell.getAttribute('t') || '';
        let val = '';

        if (type === 'inlineStr') {
          const tNodes = cell.getElementsByTagName('t');
          for (let k = 0; k < tNodes.length; k += 1) {
            val += tNodes[k].textContent || '';
          }
        } else {
          const vNode = cell.getElementsByTagName('v')[0];
          const rawV = vNode ? vNode.textContent || '' : '';
          if (type === 's') {
            const sstIdx = Number.parseInt(rawV, 10);
            val = Number.isFinite(sstIdx) && sharedStrings[sstIdx] !== undefined ? sharedStrings[sstIdx] : rawV;
          } else if (type === 'b') {
            val = rawV === '1' ? 'Ya' : 'Tidak';
          } else {
            val = rawV;
          }
        }

        while (rowValues.length <= targetCol) rowValues.push('');
        rowValues[targetCol] = String(val ?? '').trim();
      }

      if (rowValues.some((cellVal) => cellVal !== '')) {
        matrix.push(rowValues);
      }
    }

    return matrix;
  }

  function parseCsvText(rawText) {
    const text = String(rawText || '').replace(/^\uFEFF/, '');
    const firstNonEmptyLine = text.split(/\r?\n/).find((line) => line.trim().length > 0) || '';
    const countChar = (str, ch) => {
      let count = 0;
      let inQuotes = false;
      for (let i = 0; i < str.length; i += 1) {
        if (str[i] === '"') inQuotes = !inQuotes;
        else if (!inQuotes && str[i] === ch) count += 1;
      }
      return count;
    };

    let delimiter = ',';
    const semiCount = countChar(firstNonEmptyLine, ';');
    const commaCount = countChar(firstNonEmptyLine, ',');
    const tabCount = countChar(firstNonEmptyLine, '\t');
    if (tabCount > commaCount && tabCount >= semiCount) delimiter = '\t';
    else if (semiCount > commaCount) delimiter = ';';

    const rows = [];
    let currentRow = [];
    let currentCell = '';
    let inQuotes = false;

    for (let i = 0; i < text.length; i += 1) {
      const ch = text[i];
      if (inQuotes) {
        if (ch === '"') {
          if (text[i + 1] === '"') {
            currentCell += '"';
            i += 1;
          } else {
            inQuotes = false;
          }
        } else {
          currentCell += ch;
        }
      } else if (ch === '"') {
        inQuotes = true;
      } else if (ch === delimiter) {
        currentRow.push(currentCell.trim());
        currentCell = '';
      } else if (ch === '\r' || ch === '\n') {
        if (ch === '\r' && text[i + 1] === '\n') i += 1;
        currentRow.push(currentCell.trim());
        if (currentRow.some((v) => v !== '')) {
          rows.push(currentRow);
        }
        currentRow = [];
        currentCell = '';
      } else {
        currentCell += ch;
      }
    }

    if (currentCell.length > 0 || currentRow.length > 0) {
      currentRow.push(currentCell.trim());
      if (currentRow.some((v) => v !== '')) {
        rows.push(currentRow);
      }
    }

    return rows;
  }

  function parseXlsXmlOrHtmlText(rawText) {
    const parser = new DOMParser();
    if (/<Workbook[\s>]/i.test(rawText) || /<ss:Workbook[\s>]/i.test(rawText)) {
      const xmlDoc = parser.parseFromString(rawText, 'application/xml');
      const rowNodes = xmlDoc.getElementsByTagName('Row');
      const matrix = [];
      for (let r = 0; r < rowNodes.length; r += 1) {
        const cells = rowNodes[r].getElementsByTagName('Cell');
        const rowArr = [];
        let colCursor = 0;
        for (let c = 0; c < cells.length; c += 1) {
          const ssIdxAttr =
            cells[c].getAttribute('ss:Index') || cells[c].getAttribute('Index');
          if (ssIdxAttr) {
            const parsedIdx = Number.parseInt(ssIdxAttr, 10) - 1;
            if (Number.isFinite(parsedIdx) && parsedIdx >= 0) colCursor = parsedIdx;
          }
          while (rowArr.length < colCursor) rowArr.push('');
          const dataNode = cells[c].getElementsByTagName('Data')[0];
          rowArr[colCursor] = String(dataNode ? dataNode.textContent : cells[c].textContent || '').trim();
          colCursor += 1;
        }
        if (rowArr.some((v) => v !== '')) matrix.push(rowArr);
      }
      if (matrix.length > 0) return matrix;
    }

    if (/<table[\s>]/i.test(rawText)) {
      const htmlDoc = parser.parseFromString(rawText, 'text/html');
      const trs = htmlDoc.querySelectorAll('table tr');
      const matrix = [];
      trs.forEach((tr) => {
        const vals = Array.from(tr.querySelectorAll('th, td')).map((cell) =>
          String(cell.textContent || '').trim()
        );
        if (vals.some((v) => v !== '')) matrix.push(vals);
      });
      if (matrix.length > 0) return matrix;
    }

    return parseCsvText(rawText);
  }

  async function parseSpreadsheetFile(file) {
    const buffer = await file.arrayBuffer();
    const bytes = new Uint8Array(buffer);

    // ZIP signature PK\x03\x04 -> Office Open XML (.xlsx)
    if (
      bytes.length >= 4 &&
      bytes[0] === 0x50 &&
      bytes[1] === 0x4b &&
      bytes[2] === 0x03 &&
      bytes[3] === 0x04
    ) {
      return parseXlsxBuffer(buffer);
    }

    // Legacy binary BIFF8 .xls check (D0 CF 11 E0)
    if (
      bytes.length >= 8 &&
      bytes[0] === 0xd0 &&
      bytes[1] === 0xcf &&
      bytes[2] === 0x11 &&
      bytes[3] === 0xe0
    ) {
      throw new Error(
        'Berkas .xls format biner lama (Excel 97-2003) terdeteksi. Silakan simpan ulang sebagai .xlsx atau .csv di Excel, atau gunakan tombol Unduh Template (.xlsx).'
      );
    }

    const text = new TextDecoder('utf-8').decode(bytes);
    const lowerName = String(file.name || '').toLowerCase();
    if (lowerName.endsWith('.xls') || /<(Workbook|ss:Workbook|table)[\s>]/i.test(text)) {
      return parseXlsXmlOrHtmlText(text);
    }
    return parseCsvText(text);
  }

  /* ------------------------------------------------------------------------
     Normalization & Validation of Imported Resident Rows
     ------------------------------------------------------------------------ */
  const INDO_MONTHS = {
    januari: '01', jan: '01',
    februari: '02', feb: '02', pebruari: '02',
    maret: '03', mar: '03',
    april: '04', apr: '04',
    mei: '05', may: '05',
    juni: '06', jun: '06',
    juli: '07', jul: '07',
    agustus: '08', agu: '08', ags: '08', aug: '08',
    september: '09', sep: '09', sept: '09',
    oktober: '10', okt: '10', oct: '10',
    november: '11', nov: '11', nopember: '11',
    desember: '12', des: '12', dec: '12'
  };

  function normalizeIsoDate(rawVal) {
    const s = String(rawVal ?? '').trim();
    if (!s) return '';

    // 1. Already YYYY-MM-DD
    const isoMatch = s.match(/^(\d{4})[-/.](\d{1,2})[-/.](\d{1,2})/);
    if (isoMatch) {
      const [, y, m, d] = isoMatch;
      return `${y}-${m.padStart(2, '0')}-${d.padStart(2, '0')}`;
    }

    // 2. DD/MM/YYYY or DD-MM-YYYY
    const dmyMatch = s.match(/^(\d{1,2})[-/.](\d{1,2})[-/.](\d{4})$/);
    if (dmyMatch) {
      const [, d, m, y] = dmyMatch;
      return `${y}-${m.padStart(2, '0')}-${d.padStart(2, '0')}`;
    }

    // 3. Indonesian text date e.g. "14 Mei 1985"
    const textMatch = s.toLowerCase().match(/^(\d{1,2})\s+([a-z]+)\s+(\d{4})$/);
    if (textMatch) {
      const [, d, monName, y] = textMatch;
      const monNum = INDO_MONTHS[monName];
      if (monNum) return `${y}-${monNum}-${d.padStart(2, '0')}`;
    }

    // 4. Excel Serial Date number (e.g. 29004 -> 1979-05-29)
    if (/^\d{4,5}(\.\d+)?$/.test(s)) {
      const serial = Math.floor(Number(s));
      if (serial >= 3650 && serial <= 65000) {
        const utcMs = Date.UTC(1899, 11, 30) + serial * 86400000;
        const dt = new Date(utcMs);
        if (!Number.isNaN(dt.getTime())) {
          const y = dt.getUTCFullYear();
          const m = String(dt.getUTCMonth() + 1).padStart(2, '0');
          const d = String(dt.getUTCDate()).padStart(2, '0');
          return `${y}-${m}-${d}`;
        }
      }
    }

    return '';
  }

  function normalizeGender(raw) {
    const v = String(raw ?? '').trim().toLowerCase();
    if (!v) return '';
    if (v === 'l' || v === 'lk' || v.includes('laki') || v === 'pria') return 'Laki-laki';
    if (v === 'p' || v === 'pr' || v.includes('perempuan') || v === 'wanita') return 'Perempuan';
    return '';
  }

  function normalizeMarital(raw) {
    const v = String(raw ?? '').trim().toLowerCase();
    if (!v) return 'Belum Kawin';
    if (v.includes('belum') || v === 'bk' || v === 'lajang') return 'Belum Kawin';
    if (v.includes('mati') || v === 'cm') return 'Cerai Mati';
    if (v.includes('hidup') || v.includes('cerai') || v === 'ch') return 'Cerai Hidup';
    if (v.includes('kawin') || v.includes('nikah') || v === 'k') return 'Kawin';
    return 'Kawin';
  }

  function normalizeAgama(raw) {
    const v = String(raw ?? '').trim().toLowerCase();
    if (!v) return 'Islam';
    if (v.includes('islam')) return 'Islam';
    if (v.includes('katolik') || v.includes('katholik')) return 'Katolik';
    if (v.includes('kristen') || v.includes('protestan')) return 'Kristen';
    if (v.includes('hindu')) return 'Hindu';
    if (v.includes('buddha') || v.includes('budha')) return 'Buddha';
    if (v.includes('konghucu') || v.includes('khonghucu')) return 'Konghucu';
    if (v.includes('kepercayaan')) return 'Kepercayaan terhadap Tuhan YME';
    return String(raw).trim();
  }

  function normalizeBaca(raw) {
    const v = String(raw ?? '').trim().toLowerCase();
    if (!v) return 'Ya';
    if (v === 'tidak' || v === 't' || v === 'tdk' || v === 'no' || v === 'buta huruf') return 'Tidak';
    return 'Ya';
  }

  function normalizeWarga(raw) {
    const v = String(raw ?? '').trim().toUpperCase();
    if (v === 'WNA' || v.includes('ASING')) return 'WNA';
    return 'WNI';
  }

  function mapHeaderColumns(matrix) {
    const cleanHeader = (h) =>
      String(h ?? '')
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, ' ')
        .trim();

    const row0 = (matrix[0] || []).map(cleanHeader);
    const hasHeaderKeywords = row0.some(
      (h) =>
        h.includes('nama') ||
        h === 'nik' ||
        h.includes('kelamin') ||
        h.includes('tempat') ||
        h.includes('alamat')
    );

    if (!hasHeaderKeywords) {
      // Positional fallback: 16-col (with NOMOR URUT) or 15-col (starting at NAMA)
      const firstRowCols = (matrix[0] || []).length;
      const offset = firstRowCols >= 16 && /^\d+$/.test(String(matrix[0]?.[0] || '').trim()) ? 1 : 0;
      return {
        dataStartRow: 0,
        colMap: {
          namaLengkap: offset + 0,
          jenisKelamin: offset + 1,
          statusPerkawinan: offset + 2,
          tempatLahir: offset + 3,
          tanggalLahir: offset + 4,
          agama: offset + 5,
          pendidikanTerakhir: offset + 6,
          pekerjaan: offset + 7,
          dapatMembaca: offset + 8,
          kewarganegaraan: offset + 9,
          alamatLengkap: offset + 10,
          kedudukanKeluarga: offset + 11,
          nik: offset + 12,
          nomorKk: offset + 13,
          keterangan: offset + 14
        }
      };
    }

    // Check if row 1 is a 2nd-tier subheader (e.g. TEMPAT LAHIR | TGL under TEMPAT & TANGGAL LAHIR)
    const row1 = (matrix[1] || []).map(cleanHeader);
    const isTwoRowHeader =
      row1.length > 0 &&
      row1.some((h) => h === 'tgl' || h === 'tempat lahir' || h === 'tanggal lahir') &&
      !row1.some((h) => /^\d{16}$/.test(h.replace(/\s+/g, '')));

    const maxCols = Math.max(row0.length, isTwoRowHeader ? row1.length : 0);
    const combinedHeaders = [];
    for (let c = 0; c < maxCols; c += 1) {
      const h0 = row0[c] || '';
      const h1 = isTwoRowHeader ? row1[c] || '' : '';
      combinedHeaders.push(h1 || h0);
    }

    const colMap = {};
    combinedHeaders.forEach((h, idx) => {
      if (!h) return;
      if (colMap.namaLengkap === undefined && h.includes('nama')) colMap.namaLengkap = idx;
      else if (colMap.jenisKelamin === undefined && (h.includes('kelamin') || h === 'jk' || h === 'l p')) colMap.jenisKelamin = idx;
      else if (colMap.statusPerkawinan === undefined && (h.includes('kawin') || h.includes('perkawinan') || h.includes('status'))) colMap.statusPerkawinan = idx;
      else if (colMap.tempatLahir === undefined && h.includes('tempat')) colMap.tempatLahir = idx;
      else if (colMap.tanggalLahir === undefined && (h === 'tgl' || h.includes('tgl') || h.includes('tanggal'))) colMap.tanggalLahir = idx;
      else if (colMap.agama === undefined && h.includes('agama')) colMap.agama = idx;
      else if (colMap.pendidikanTerakhir === undefined && h.includes('pendidikan')) colMap.pendidikanTerakhir = idx;
      else if (colMap.pekerjaan === undefined && h.includes('pekerjaan')) colMap.pekerjaan = idx;
      else if (colMap.dapatMembaca === undefined && (h.includes('membaca') || h.includes('huruf') || h.includes('baca'))) colMap.dapatMembaca = idx;
      else if (colMap.kewarganegaraan === undefined && (h.includes('kewarganegaraan') || h === 'wn' || h.includes('warga negara'))) colMap.kewarganegaraan = idx;
      else if (colMap.alamatLengkap === undefined && h.includes('alamat')) colMap.alamatLengkap = idx;
      else if (colMap.kedudukanKeluarga === undefined && (h.includes('kedudukan') || h.includes('keluarga') || h === 'shdk')) colMap.kedudukanKeluarga = idx;
      else if (colMap.nik === undefined && (h === 'nik' || h.includes('nomor induk kependudukan') || h.startsWith('nik '))) colMap.nik = idx;
      else if (colMap.nomorKk === undefined && (h.includes('kk') || h.includes('kartu keluarga'))) colMap.nomorKk = idx;
      else if (colMap.keterangan === undefined && (h === 'ket' || h.startsWith('ket ') || h.includes('keterangan'))) colMap.keterangan = idx;
    });

    return {
      dataStartRow: isTwoRowHeader ? 2 : 1,
      colMap
    };
  }

  function evaluateImportMatrix(matrix, existingRecords, mode) {
    if (!Array.isArray(matrix) || matrix.length === 0) {
      return { validRows: [], previewRows: [], skippedCount: 0 };
    }

    const { dataStartRow, colMap } = mapHeaderColumns(matrix);
    const existingNiks =
      mode === 'append'
        ? new Set(existingRecords.map((r) => String(r.nik || '').trim()))
        : new Set();
    const seenBatchNiks = new Set();

    const validRows = [];
    const previewRows = [];
    let skippedCount = 0;

    const getCell = (row, key) => {
      const idx = colMap[key];
      return idx !== undefined && idx < row.length ? String(row[idx] ?? '').trim() : '';
    };

    for (let r = dataStartRow; r < matrix.length; r += 1) {
      const rawRow = matrix[r];
      if (!rawRow || rawRow.every((cell) => String(cell ?? '').trim() === '')) continue;

      const namaLengkap = getCell(rawRow, 'namaLengkap');
      const jenisKelamin = normalizeGender(getCell(rawRow, 'jenisKelamin'));
      const statusPerkawinan = normalizeMarital(getCell(rawRow, 'statusPerkawinan'));
      const tempatLahir = getCell(rawRow, 'tempatLahir') || '-';
      const tanggalLahir = normalizeIsoDate(getCell(rawRow, 'tanggalLahir'));
      const agama = normalizeAgama(getCell(rawRow, 'agama'));
      const pendidikanTerakhir = getCell(rawRow, 'pendidikanTerakhir') || 'SLTA/Sederajat';
      const pekerjaan = getCell(rawRow, 'pekerjaan') || 'Belum/Tidak Bekerja';
      const dapatMembaca = normalizeBaca(getCell(rawRow, 'dapatMembaca'));
      const kewarganegaraan = normalizeWarga(getCell(rawRow, 'kewarganegaraan'));
      const alamatLengkap = getCell(rawRow, 'alamatLengkap') || 'Desa Tiaro';
      const kedudukanKeluarga = getCell(rawRow, 'kedudukanKeluarga') || 'Anggota Keluarga';
      const rawNik = getCell(rawRow, 'nik').replace(/^'+/, '').replace(/[\s.-]+/g, '');
      const rawKk = getCell(rawRow, 'nomorKk').replace(/^'+/, '').replace(/[\s.-]+/g, '');
      const keterangan = getCell(rawRow, 'keterangan');

      // Skip accidental repeated header rows
      if (namaLengkap.toLowerCase().includes('nama lengkap') && rawNik.toLowerCase() === 'nik') {
        continue;
      }

      let errorReason = '';
      if (!namaLengkap || namaLengkap.length < 2) {
        errorReason = 'Nama kosong';
      } else if (!jenisKelamin) {
        errorReason = 'Jenis kelamin tidak valid';
      } else if (!/^\d{16}$/.test(rawNik)) {
        errorReason = 'NIK wajib 16 digit angka';
      } else if (!/^\d{16}$/.test(rawKk)) {
        errorReason = 'Nomor KK wajib 16 digit angka';
      } else if (seenBatchNiks.has(rawNik)) {
        errorReason = 'NIK ganda dalam berkas';
      } else if (existingNiks.has(rawNik)) {
        errorReason = 'NIK sudah terdaftar';
      }

      const item = {
        id: `bip-imp-${Date.now()}-${r}`,
        namaLengkap,
        jenisKelamin: jenisKelamin || '-',
        statusPerkawinan,
        tempatLahir,
        tanggalLahir,
        agama,
        pendidikanTerakhir,
        pekerjaan,
        dapatMembaca,
        kewarganegaraan,
        alamatLengkap,
        kedudukanKeluarga,
        nik: rawNik || '-',
        nomorKk: rawKk || '-',
        keterangan
      };

      if (errorReason) {
        skippedCount += 1;
        previewRows.push({ rowNumber: r + 1, item, ok: false, reason: errorReason });
      } else {
        seenBatchNiks.add(rawNik);
        validRows.push(item);
        previewRows.push({ rowNumber: r + 1, item, ok: true, reason: 'Siap diimport' });
      }
    }

    return { validRows, previewRows, skippedCount };
  }

  /* ------------------------------------------------------------------------
     Storage & UI Helpers
     ------------------------------------------------------------------------ */
  function cloneSeedData() {
    return SEED_DATA.map((item) => ({ ...item }));
  }

  function loadRecords() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      if (raw) {
        const parsed = JSON.parse(raw);
        if (Array.isArray(parsed)) return parsed;
      }
      const initial = cloneSeedData();
      localStorage.setItem(STORAGE_KEY, JSON.stringify(initial));
      return initial;
    } catch (_) {
      return cloneSeedData();
    }
  }

  function saveRecords(records) {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(records));
    } catch (_) {
      // Ignore storage quota / privacy mode errors
    }
  }

  function getMaritalBadgeClass(status) {
    if (status === 'Kawin') return 'badge-simdes badge-simdes-success';
    if (status === 'Belum Kawin') return 'badge-simdes badge-simdes-primary';
    if (status === 'Cerai Hidup') return 'badge-simdes badge-simdes-warning';
    return 'badge-simdes badge-simdes-neutral';
  }

  function initBukuIndukPenduduk() {
    const pageRoot = document.getElementById('bukuIndukPendudukPageRoot');
    if (!pageRoot) return;

    const esc = window.SimdesApp ? window.SimdesApp.escapeHtml : (s) => String(s ?? '');
    const formatDate = window.SimdesTable ? window.SimdesTable.formatDateIndo : (s) => s;

    let records = loadRecords();
    let activeDetailId = null;
    let pendingDeleteId = null;

    // Import Excel State
    let cachedImportMatrix = null;
    let cachedImportFileName = '';
    let evaluatedImportBatch = { validRows: [], previewRows: [], skippedCount: 0 };

    // KPI & Print Elements
    const statTotalPenduduk = document.getElementById('statTotalPenduduk');
    const statKomposisiGender = document.getElementById('statKomposisiGender');
    const statTotalKkUnik = document.getElementById('statTotalKkUnik');
    const statKepalaKeluarga = document.getElementById('statKepalaKeluarga');
    const printMetaEl = document.getElementById('printPendudukMeta');

    // Table & Toolbar Elements
    const tableEl = document.getElementById('indukTable');
    const tbodyEl = document.getElementById('indukTableBody');
    const paginationInfoEl = document.getElementById('tablePaginationInfo');
    const paginationListEl = document.getElementById('tablePaginationList');

    const searchInput = document.getElementById('tableSearchInput');
    const clearSearchBtn = document.getElementById('clearSearchInputBtn');
    const filterGenderSelect = document.getElementById('filterGenderSelect');
    const filterMaritalSelect = document.getElementById('filterMaritalSelect');
    const filterAgamaSelect = document.getElementById('filterAgamaSelect');
    const pageSizeSelect = document.getElementById('pageSizeSelect');
    const resetFilterBtn = document.getElementById('resetTableFilterBtn');
    const resetSampleBtn = document.getElementById('btnResetSamplePenduduk');

    const btnImportExcel = document.getElementById('btnImportExcelPenduduk');
    const btnTambah = document.getElementById('btnTambahPenduduk');
    const btnCetak = document.getElementById('btnCetakPenduduk');

    // Form Modal Elements
    const formModalEl = document.getElementById('indukFormModal');
    const formModalTitle = document.getElementById('indukFormModalLabel');
    const formEl = document.getElementById('indukForm');
    const formAlertBox = document.getElementById('indukFormAlert');
    const formAlertText = document.getElementById('indukFormAlertText');
    const formSubmitBtn = document.getElementById('indukFormSubmitBtn');

    const fId = document.getElementById('indukRecordId');
    const fNomorUrutPreview = document.getElementById('indukNomorUrutPreview');
    const fNama = document.getElementById('indukNama');
    const fStatusPerkawinan = document.getElementById('indukStatusPerkawinan');
    const fTempatLahir = document.getElementById('indukTempatLahir');
    const fTanggalLahir = document.getElementById('indukTanggalLahir');
    const fAgama = document.getElementById('indukAgama');
    const fPendidikan = document.getElementById('indukPendidikan');
    const fPekerjaan = document.getElementById('indukPekerjaan');
    const fAlamat = document.getElementById('indukAlamat');
    const fKedudukan = document.getElementById('indukKedudukan');
    const fNik = document.getElementById('indukNik');
    const fNomorKk = document.getElementById('indukNomorKk');
    const fKeterangan = document.getElementById('indukKeterangan');

    // Import Excel Modal Elements
    const importModalEl = document.getElementById('indukImportExcelModal');
    const importFormEl = document.getElementById('importExcelForm');
    const importAlertBox = document.getElementById('importExcelAlert');
    const importAlertText = document.getElementById('importExcelAlertText');
    const btnDownloadTemplate = document.getElementById('btnDownloadTemplateExcel');
    const importDropzone = document.getElementById('importExcelDropzone');
    const importFileInput = document.getElementById('importExcelFileInput');
    const importPreviewSection = document.getElementById('importPreviewSection');
    const importFileBadge = document.getElementById('importFileBadge');
    const importValidBadge = document.getElementById('importValidBadge');
    const importSkipBadge = document.getElementById('importSkipBadge');
    const importPreviewBody = document.getElementById('importPreviewTableBody');
    const importPreviewNote = document.getElementById('importPreviewNote');
    const confirmImportBtnText = document.getElementById('confirmImportExcelBtnText');

    // Detail & Delete Modal Elements
    const detailModalEl = document.getElementById('indukDetailModal');
    const btnEditFromDetail = document.getElementById('btnEditPendudukFromDetail');

    const deleteModalEl = document.getElementById('indukDeleteModal');
    const deleteTargetNameEl = document.getElementById('deletePendudukTargetName');
    const deleteTargetKedudukanEl = document.getElementById('deletePendudukTargetKedudukan');
    const deleteTargetMetaEl = document.getElementById('deletePendudukTargetMeta');
    const confirmDeleteBtn = document.getElementById('confirmDeletePendudukBtn');

    function getRadioValue(containerEl, name, fallback = '') {
      if (!containerEl) return fallback;
      const checked = containerEl.querySelector(`input[type="radio"][name="${name}"]:checked`);
      return checked ? checked.value : fallback;
    }

    function setRadioValue(containerEl, name, val) {
      if (!containerEl) return;
      containerEl.querySelectorAll(`input[type="radio"][name="${name}"]`).forEach((radio) => {
        radio.checked = radio.value === val;
      });
    }

    function getIntrinsicOrder(id) {
      const idx = records.findIndex((r) => r.id === id);
      return idx === -1 ? records.length + 1 : idx + 1;
    }

    function updateSummaryStats() {
      const total = records.length;
      const countLaki = records.filter((r) => r.jenisKelamin === 'Laki-laki').length;
      const countPerempuan = records.filter((r) => r.jenisKelamin === 'Perempuan').length;
      const uniqueKk = new Set(
        records.map((r) => String(r.nomorKk || '').trim()).filter(Boolean)
      ).size;
      const countKepala = records.filter(
        (r) => r.kedudukanKeluarga === 'Kepala Keluarga'
      ).length;

      if (statTotalPenduduk) statTotalPenduduk.textContent = total.toLocaleString('id-ID');
      if (statKomposisiGender) statKomposisiGender.textContent = `${countLaki} L / ${countPerempuan} P`;
      if (statTotalKkUnik) statTotalKkUnik.textContent = uniqueKk.toLocaleString('id-ID');
      if (statKepalaKeluarga) statKepalaKeluarga.textContent = countKepala.toLocaleString('id-ID');
    }

    function syncSortHeadersUI(state) {
      if (!tableEl) return;
      tableEl.querySelectorAll('th [data-sort-key]').forEach((btn) => {
        const key = btn.getAttribute('data-sort-key');
        const th = btn.closest('th');
        const icon = btn.querySelector('.th-sort-icon');
        const isSorted = state.sortKey === key;

        btn.classList.toggle('is-sorted', isSorted);
        if (th) {
          th.setAttribute(
            'aria-sort',
            !isSorted ? 'none' : state.sortDirection === 'asc' ? 'ascending' : 'descending'
          );
        }
        if (icon) {
          icon.className = `fa-solid ${
            !isSorted ? 'fa-sort' : state.sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down'
          } th-sort-icon no-print`;
        }
      });
    }

    function renderRowsHtml(rowsList, startIndex = 0, hasActiveQuery = false) {
      if (rowsList.length === 0) {
        return `
          <tr>
            <td colspan="16" class="p-0">
              <div class="table-empty-state">
                <div class="table-empty-icon">
                  <i class="fa-solid fa-users-slash" aria-hidden="true"></i>
                </div>
                <div class="fw-bold mb-1" style="color:var(--simdes-text); font-size:0.95rem;">
                  ${hasActiveQuery ? 'Data penduduk tidak ditemukan' : 'Belum ada data pada Buku Induk Penduduk'}
                </div>
                <div class="mb-3" style="font-size:0.82rem;">
                  ${
                    hasActiveQuery
                      ? 'Tidak ada penduduk yang cocok dengan kata kunci pencarian, jenis kelamin, status perkawinan, atau agama yang dipilih.'
                      : 'Silakan tambahkan data penduduk melalui formulir atau gunakan tombol Import Excel.'
                  }
                </div>
                ${
                  hasActiveQuery
                    ? `<button type="button" class="btn-simdes-outline" data-empty-action="reset-filter">
                         <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                         <span>Reset Pencarian &amp; Filter</span>
                       </button>`
                    : `<div class="d-inline-flex flex-wrap justify-content-center gap-2">
                         <button type="button" class="btn-simdes-excel" data-empty-action="import-excel">
                           <i class="fa-solid fa-file-excel" aria-hidden="true"></i>
                           <span>Import Excel</span>
                         </button>
                         <button type="button" class="btn-simdes-primary" data-empty-action="add-new">
                           <i class="fa-solid fa-plus" aria-hidden="true"></i>
                           <span>Tambah Penduduk</span>
                         </button>
                       </div>`
                }
              </div>
            </td>
          </tr>
        `;
      }

      return rowsList
        .map((row, idx) => {
          // Nomor urut selalu dibuat otomatis dan berurutan (tidak pernah menggunakan NIK)
          const noUrut = startIndex + idx + 1;
          const tglLahirStr = row.tanggalLahir ? esc(formatDate(row.tanggalLahir)) : '&ndash;';
          const genderBadge =
            row.jenisKelamin === 'Perempuan'
              ? '<span class="badge-simdes badge-simdes-accent no-print">Perempuan</span><span class="print-only-inline">Perempuan</span>'
              : '<span class="badge-simdes badge-simdes-primary no-print">Laki-laki</span><span class="print-only-inline">Laki-laki</span>';
          const maritalClass = getMaritalBadgeClass(row.statusPerkawinan);
          const bacaStr = row.dapatMembaca || 'Ya';

          return `
            <tr>
              <!-- 1. NOMOR URUT -->
              <td class="col-no-urut sticky-col-first group-divider-end">${noUrut}</td>
              <!-- 2. NAMA LENGKAP/PANGGILAN -->
              <td>
                <button type="button" class="tentang-title-btn" data-row-action="detail" data-id="${esc(row.id)}" title="Lihat detail ${esc(row.namaLengkap)}">
                  ${esc(row.namaLengkap)}
                </button>
              </td>
              <!-- 3. JENIS KELAMIN -->
              <td class="text-center" style="white-space:nowrap;">${genderBadge}</td>
              <!-- 4. STATUS PERKAWINAN -->
              <td class="text-center group-divider-end" style="white-space:nowrap;">
                <span class="${maritalClass} no-print">${esc(row.statusPerkawinan)}</span>
                <span class="print-only-inline">${esc(row.statusPerkawinan)}</span>
              </td>
              <!-- 5. TEMPAT LAHIR -->
              <td>${esc(row.tempatLahir || '-')}</td>
              <!-- 6. TGL -->
              <td class="tabular-nums text-center group-divider-end" style="white-space:nowrap;">${tglLahirStr}</td>
              <!-- 7. AGAMA -->
              <td class="text-center" style="white-space:nowrap;">${esc(row.agama || '-')}</td>
              <!-- 8. PENDIDIKAN TERAKHIR -->
              <td>${esc(row.pendidikanTerakhir || '-')}</td>
              <!-- 9. PEKERJAAN -->
              <td>${esc(row.pekerjaan || '-')}</td>
              <!-- 10. DAPAT MEMBACA HURUF -->
              <td class="text-center fw-semibold" style="white-space:nowrap;">${esc(bacaStr)}</td>
              <!-- 11. KEWARGANEGARAAN -->
              <td class="text-center fw-semibold" style="white-space:nowrap;">${esc(row.kewarganegaraan || 'WNI')}</td>
              <!-- 12. ALAMAT LENGKAP -->
              <td>${esc(row.alamatLengkap || '-')}</td>
              <!-- 13. KEDUDUKAN DLM KELUARGA -->
              <td style="white-space:nowrap;">
                <span class="fw-semibold" style="color:var(--simdes-deep-green);">${esc(row.kedudukanKeluarga || '-')}</span>
              </td>
              <!-- 14. NIK -->
              <td class="tabular-nums text-center" style="white-space:nowrap;">
                <span class="doc-num-badge mb-0">${esc(row.nik || '-')}</span>
              </td>
              <!-- 15. NOMOR KK -->
              <td class="tabular-nums text-center group-divider-end" style="white-space:nowrap;">
                <span class="doc-num-badge report-badge mb-0">${esc(row.nomorKk || '-')}</span>
              </td>
              <!-- 16. KET -->
              <td>
                <div class="ket-cell-content">
                  <div class="ket-text">
                    ${row.keterangan ? esc(row.keterangan) : '<span class="text-simdes-muted">&ndash;</span>'}
                  </div>
                  <div class="row-action-group no-print" role="group" aria-label="Aksi baris ${noUrut}">
                    <button type="button"
                            class="btn-row-action"
                            data-row-action="detail"
                            data-id="${esc(row.id)}"
                            title="Lihat Detail Penduduk"
                            aria-label="Lihat detail ${esc(row.namaLengkap)}">
                      <i class="fa-regular fa-eye" aria-hidden="true"></i>
                    </button>
                    <button type="button"
                            class="btn-row-action"
                            data-row-action="edit"
                            data-id="${esc(row.id)}"
                            title="Edit Data Penduduk"
                            aria-label="Edit data ${esc(row.namaLengkap)}">
                      <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
                    </button>
                    <button type="button"
                            class="btn-row-action action-delete"
                            data-row-action="delete"
                            data-id="${esc(row.id)}"
                            title="Hapus Data Penduduk"
                            aria-label="Hapus data ${esc(row.namaLengkap)}">
                      <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
                    </button>
                  </div>
                </div>
              </td>
            </tr>
          `;
        })
        .join('');
    }

    function compareIsoDates(daRaw, dbRaw) {
      const da = String(daRaw || '').trim();
      const db = String(dbRaw || '').trim();
      if (!da && !db) return 0;
      if (!da) return -1;
      if (!db) return 1;
      return da.localeCompare(db);
    }

    const tableCtrl = window.SimdesTable.create({
      data: records,
      pageSize: 10,
      defaultSortKey: 'urutan',
      defaultSortDirection: 'asc',
      searchFields: ['namaLengkap', 'nik', 'nomorKk'],
      comparators: {
        urutan: (a, b) => getIntrinsicOrder(a.id) - getIntrinsicOrder(b.id),
        tanggalLahir: (a, b) => compareIsoDates(a.tanggalLahir, b.tanggalLahir)
      },
      onRender: (state) => {
        syncSortHeadersUI(state);

        if (resetFilterBtn) {
          resetFilterBtn.disabled = !state.hasActiveQuery;
        }

        if (tbodyEl) {
          tbodyEl.innerHTML = renderRowsHtml(state.rows, state.startIndex, state.hasActiveQuery);
        }

        if (paginationInfoEl) {
          paginationInfoEl.innerHTML =
            state.totalItems === 0
              ? 'Menampilkan <strong>0</strong> dari <strong>0</strong> data penduduk'
              : `Menampilkan <strong>${state.startEntry}&ndash;${state.endEntry}</strong> dari <strong>${state.totalItems}</strong> penduduk${
                  state.hasActiveQuery ? ` (disaring dari total ${state.totalAllItems} penduduk)` : ''
                }`;
        }

        window.SimdesTable.renderPagination(paginationListEl, state, (page) => {
          tableCtrl.setPage(page);
        });
      }
    });

    // Enforce numeric characters on NIK & Nomor KK while typing
    [fNik, fNomorKk].forEach((numInput) => {
      numInput?.addEventListener('input', () => {
        const cleaned = numInput.value.replace(/\D+/g, '').slice(0, 16);
        if (numInput.value !== cleaned) {
          numInput.value = cleaned;
        }
      });
    });

    // Search & Filters
    function handleResetAllFilters() {
      if (searchInput) searchInput.value = '';
      clearSearchBtn?.classList.remove('visible');
      if (filterGenderSelect) filterGenderSelect.value = 'ALL';
      if (filterMaritalSelect) filterMaritalSelect.value = 'ALL';
      if (filterAgamaSelect) filterAgamaSelect.value = 'ALL';
      tableCtrl.resetAll();
    }

    searchInput?.addEventListener('input', () => {
      const val = searchInput.value;
      clearSearchBtn?.classList.toggle('visible', val.length > 0);
      tableCtrl.setSearch(val);
    });

    clearSearchBtn?.addEventListener('click', () => {
      if (!searchInput) return;
      searchInput.value = '';
      clearSearchBtn.classList.remove('visible');
      tableCtrl.setSearch('');
      searchInput.focus();
    });

    filterGenderSelect?.addEventListener('change', () => {
      const val = filterGenderSelect.value;
      tableCtrl.setFilter('jenisKelamin', val === 'ALL' ? null : val);
    });

    filterMaritalSelect?.addEventListener('change', () => {
      const val = filterMaritalSelect.value;
      tableCtrl.setFilter('statusPerkawinan', val === 'ALL' ? null : val);
    });

    filterAgamaSelect?.addEventListener('change', () => {
      const val = filterAgamaSelect.value;
      tableCtrl.setFilter('agama', val === 'ALL' ? null : val);
    });

    pageSizeSelect?.addEventListener('change', () => {
      tableCtrl.setPageSize(Number(pageSizeSelect.value) || 10);
    });

    resetFilterBtn?.addEventListener('click', handleResetAllFilters);

    tableEl?.querySelectorAll('th [data-sort-key]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const key = btn.getAttribute('data-sort-key');
        if (key) tableCtrl.toggleSort(key);
      });
    });

    // Form Modal Helpers
    function hideFormBanner() {
      formAlertBox?.classList.remove('show');
    }

    function showFormBanner(msg) {
      if (!formAlertBox || !formAlertText) return;
      formAlertText.textContent = msg;
      formAlertBox.classList.add('show');
    }

    function openCreateModal() {
      if (!formEl || !formModalEl) return;
      formEl.reset();
      fId.value = '';
      setRadioValue(formEl, 'jenisKelamin', '');
      setRadioValue(formEl, 'dapatMembaca', 'Ya');
      setRadioValue(formEl, 'kewarganegaraan', 'WNI');
      if (fNomorUrutPreview) {
        fNomorUrutPreview.value = `Otomatis (#${records.length + 1})`;
      }
      hideFormBanner();

      if (formModalTitle) {
        formModalTitle.innerHTML =
          '<i class="fa-solid fa-plus-circle text-simdes-primary me-2" aria-hidden="true"></i>Tambah Data Penduduk';
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML =
          '<i class="fa-solid fa-floppy-disk" aria-hidden="true"></i><span>Simpan Data Penduduk</span>';
      }

      window.bootstrap?.Modal.getOrCreateInstance(formModalEl).show();
      formModalEl.addEventListener('shown.bs.modal', () => fNama?.focus(), { once: true });
    }

    function openEditModal(id) {
      const idx = records.findIndex((r) => r.id === id);
      const item = idx >= 0 ? records[idx] : null;
      if (!item || !formEl || !formModalEl) return;

      formEl.reset();
      hideFormBanner();

      fId.value = item.id;
      if (fNomorUrutPreview) {
        fNomorUrutPreview.value = `#${idx + 1} (Otomatis)`;
      }
      fNama.value = item.namaLengkap || '';
      setRadioValue(formEl, 'jenisKelamin', item.jenisKelamin || '');
      fStatusPerkawinan.value = item.statusPerkawinan || '';

      fTempatLahir.value = item.tempatLahir || '';
      fTanggalLahir.value = item.tanggalLahir || '';
      fAgama.value = item.agama || '';
      fPendidikan.value = item.pendidikanTerakhir || '';
      fPekerjaan.value = item.pekerjaan || '';
      setRadioValue(formEl, 'dapatMembaca', item.dapatMembaca || 'Ya');
      setRadioValue(formEl, 'kewarganegaraan', item.kewarganegaraan || 'WNI');

      fAlamat.value = item.alamatLengkap || '';
      fKedudukan.value = item.kedudukanKeluarga || '';
      fNik.value = item.nik || '';
      fNomorKk.value = item.nomorKk || '';
      fKeterangan.value = item.keterangan || '';

      if (formModalTitle) {
        formModalTitle.innerHTML =
          '<i class="fa-regular fa-pen-to-square text-simdes-primary me-2" aria-hidden="true"></i>Edit Data Penduduk';
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML =
          '<i class="fa-solid fa-check" aria-hidden="true"></i><span>Simpan Perubahan</span>';
      }

      window.bootstrap?.Modal.getOrCreateInstance(formModalEl).show();
      formModalEl.addEventListener('shown.bs.modal', () => fNama?.focus(), { once: true });
    }

    function openDetailModal(id) {
      const idx = records.findIndex((r) => r.id === id);
      const item = idx >= 0 ? records[idx] : null;
      if (!item || !detailModalEl) return;
      activeDetailId = item.id;

      const setText = (elId, val) => {
        const el = document.getElementById(elId);
        if (el) el.textContent = val;
      };

      setText('detIndukNomorUrut', `#${idx + 1}`);
      setText('detIndukNama', item.namaLengkap || '-');
      setText('detIndukGender', item.jenisKelamin || '-');
      setText('detIndukStatusPerkawinan', item.statusPerkawinan || '-');

      setText('detIndukTempatLahir', item.tempatLahir || '-');
      setText('detIndukTanggalLahir', item.tanggalLahir ? formatDate(item.tanggalLahir) : '-');
      setText('detIndukAgama', item.agama || '-');
      setText('detIndukPendidikan', item.pendidikanTerakhir || '-');
      setText('detIndukPekerjaan', item.pekerjaan || '-');
      setText('detIndukDapatMembaca', item.dapatMembaca || 'Ya');
      setText('detIndukKewarganegaraan', item.kewarganegaraan || 'WNI');

      setText('detIndukAlamat', item.alamatLengkap || '-');
      setText('detIndukKedudukan', item.kedudukanKeluarga || '-');
      setText('detIndukNik', item.nik || '-');
      setText('detIndukNomorKk', item.nomorKk || '-');
      setText('detIndukKeterangan', item.keterangan || 'Tidak ada keterangan tambahan');

      window.bootstrap?.Modal.getOrCreateInstance(detailModalEl).show();
    }

    function openDeleteModal(id) {
      const idx = records.findIndex((r) => r.id === id);
      const item = idx >= 0 ? records[idx] : null;
      if (!item || !deleteModalEl) return;
      pendingDeleteId = item.id;

      if (deleteTargetNameEl) {
        deleteTargetNameEl.textContent = `#${idx + 1} • ${item.namaLengkap}`;
      }
      if (deleteTargetKedudukanEl) {
        deleteTargetKedudukanEl.textContent = `${item.kedudukanKeluarga || '-'} (${item.jenisKelamin || '-'})`;
      }
      if (deleteTargetMetaEl) {
        deleteTargetMetaEl.textContent = `NIK: ${item.nik} • No. KK: ${item.nomorKk}`;
      }

      window.bootstrap?.Modal.getOrCreateInstance(deleteModalEl).show();
    }

    /* ----------------------------------------------------------------------
       Import Excel Modal Workflow
       ---------------------------------------------------------------------- */
    function hideImportAlert() {
      importAlertBox?.classList.remove('show');
    }

    function showImportAlert(msg) {
      if (!importAlertBox || !importAlertText) return;
      importAlertText.textContent = msg;
      importAlertBox.classList.add('show');
    }

    function resetImportModalState() {
      cachedImportMatrix = null;
      cachedImportFileName = '';
      evaluatedImportBatch = { validRows: [], previewRows: [], skippedCount: 0 };
      hideImportAlert();
      if (importFormEl) importFormEl.reset();
      setRadioValue(importFormEl, 'importMode', 'append');
      if (importPreviewSection) importPreviewSection.hidden = true;
      if (importPreviewBody) importPreviewBody.innerHTML = '';
      if (confirmImportBtnText) confirmImportBtnText.textContent = 'Import Data ke Buku Induk';
    }

    function renderImportPreview() {
      if (!cachedImportMatrix || !importPreviewSection || !importPreviewBody) return;
      const mode = getRadioValue(importFormEl, 'importMode', 'append');
      evaluatedImportBatch = evaluateImportMatrix(cachedImportMatrix, records, mode);

      const { validRows, previewRows, skippedCount } = evaluatedImportBatch;
      importPreviewSection.hidden = false;

      if (importFileBadge) importFileBadge.textContent = cachedImportFileName || 'Berkas Excel';
      if (importValidBadge) importValidBadge.textContent = `${validRows.length} siap diimport`;
      if (importSkipBadge) {
        importSkipBadge.hidden = skippedCount === 0;
        importSkipBadge.textContent = `${skippedCount} dilewati`;
      }
      if (confirmImportBtnText) {
        confirmImportBtnText.textContent =
          validRows.length > 0
            ? `Import ${validRows.length} Data Penduduk`
            : 'Import Data ke Buku Induk';
      }

      if (previewRows.length === 0) {
        importPreviewBody.innerHTML = `
          <tr>
            <td colspan="7" class="text-center text-simdes-muted py-3">
              Tidak ditemukan baris data penduduk pada berkas yang dipilih.
            </td>
          </tr>
        `;
        if (importPreviewNote) importPreviewNote.textContent = '';
        return;
      }

      const shownRows = previewRows.slice(0, 8);
      importPreviewBody.innerHTML = shownRows
        .map(({ rowNumber, item, ok, reason }) => {
          const ttl = item.tanggalLahir
            ? `${esc(item.tempatLahir)}, ${esc(formatDate(item.tanggalLahir))}`
            : esc(item.tempatLahir);
          const statusBadge = ok
            ? `<span class="badge-simdes badge-simdes-success">${esc(reason)}</span>`
            : `<span class="badge-simdes badge-simdes-warning">${esc(reason)}</span>`;

          return `
            <tr>
              <td class="tabular-nums text-center fw-semibold">#${rowNumber}</td>
              <td class="fw-semibold">${esc(item.namaLengkap || '-')}</td>
              <td class="text-center">${item.jenisKelamin === 'Perempuan' ? 'P' : item.jenisKelamin === 'Laki-laki' ? 'L' : '-'}</td>
              <td style="white-space:nowrap;">${ttl}</td>
              <td class="tabular-nums">${esc(item.nik)}</td>
              <td class="tabular-nums">${esc(item.nomorKk)}</td>
              <td style="white-space:nowrap;">${statusBadge}</td>
            </tr>
          `;
        })
        .join('');

      if (importPreviewNote) {
        importPreviewNote.textContent =
          previewRows.length > shownRows.length
            ? `Menampilkan ${shownRows.length} dari total ${previewRows.length} baris yang terbaca pada berkas.`
            : `Menampilkan seluruh ${previewRows.length} baris yang terbaca pada berkas.`;
      }
    }

    async function handleSelectedExcelFile(file) {
      hideImportAlert();
      if (!file) {
        resetImportModalState();
        return;
      }

      try {
        const matrix = await parseSpreadsheetFile(file);
        if (!matrix || matrix.length === 0) {
          showImportAlert('Berkas kosong atau tidak memiliki baris tabel yang dapat dibaca.');
          return;
        }
        cachedImportMatrix = matrix;
        cachedImportFileName = file.name;
        renderImportPreview();

        if (evaluatedImportBatch.validRows.length === 0) {
          showImportAlert(
            'Belum ada baris yang memenuhi syarat import (pastikan Nama, Jenis Kelamin, NIK 16 digit, dan Nomor KK 16 digit terisi dan belum duplikat).'
          );
        }
      } catch (err) {
        cachedImportMatrix = null;
        if (importPreviewSection) importPreviewSection.hidden = true;
        showImportAlert(
          err instanceof Error
            ? err.message
            : 'Gagal membaca berkas Excel. Pastikan format berkas adalah .xlsx, .xls, atau .csv.'
        );
      }
    }

    function openImportModal() {
      if (!importModalEl) return;
      resetImportModalState();
      window.bootstrap?.Modal.getOrCreateInstance(importModalEl).show();
      importModalEl.addEventListener('shown.bs.modal', () => importFileInput?.focus(), { once: true });
    }

    importModalEl?.addEventListener('hidden.bs.modal', resetImportModalState);

    btnImportExcel?.addEventListener('click', openImportModal);

    btnDownloadTemplate?.addEventListener('click', () => {
      const xlsxBytes = buildBukuIndukTemplateXlsxBytes();
      const blob = new Blob([xlsxBytes], {
        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
      });
      const url = URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.href = url;
      link.download = 'Template_Buku_Induk_Penduduk_Desa_Tiaro.xlsx';
      document.body.appendChild(link);
      link.click();
      link.remove();
      window.setTimeout(() => URL.revokeObjectURL(url), 1000);

      window.SimdesApp?.showToast({
        title: 'Template Excel Diunduh',
        message: 'Berkas Template_Buku_Induk_Penduduk_Desa_Tiaro.xlsx siap diisi di Microsoft Excel.',
        variant: 'primary'
      });
    });

    importFileInput?.addEventListener('change', () => {
      const file = importFileInput.files?.[0] || null;
      handleSelectedExcelFile(file);
    });

    importFormEl?.querySelectorAll('input[name="importMode"]').forEach((radio) => {
      radio.addEventListener('change', () => {
        hideImportAlert();
        if (cachedImportMatrix) {
          renderImportPreview();
        }
      });
    });

    if (importDropzone && importFileInput) {
      ['dragenter', 'dragover'].forEach((evtName) => {
        importDropzone.addEventListener(evtName, (e) => {
          e.preventDefault();
          importDropzone.classList.add('is-dragover');
        });
      });

      ['dragleave', 'dragend', 'drop'].forEach((evtName) => {
        importDropzone.addEventListener(evtName, (e) => {
          e.preventDefault();
          importDropzone.classList.remove('is-dragover');
        });
      });

      importDropzone.addEventListener('drop', (e) => {
        const droppedFile = e.dataTransfer?.files?.[0];
        if (!droppedFile) return;
        try {
          const dt = new DataTransfer();
          dt.items.add(droppedFile);
          importFileInput.files = dt.files;
        } catch (_) {
          // Proceed directly with dropped File reference
        }
        handleSelectedExcelFile(droppedFile);
      });
    }

    importFormEl?.addEventListener('submit', (e) => {
      e.preventDefault();
      hideImportAlert();

      if (!cachedImportMatrix) {
        showImportAlert('Silakan pilih berkas Excel (.xlsx, .xls, atau .csv) terlebih dahulu sebelum melakukan import.');
        importFileInput?.focus();
        return;
      }

      const mode = getRadioValue(importFormEl, 'importMode', 'append');
      evaluatedImportBatch = evaluateImportMatrix(cachedImportMatrix, records, mode);
      const { validRows, skippedCount } = evaluatedImportBatch;

      if (validRows.length === 0) {
        showImportAlert(
          'Tidak ada baris valid yang dapat diimport. Periksa kembali format NIK/Nomor KK (16 digit) atau duplikasi NIK pada pratinjau di bawah.'
        );
        importFileInput?.focus();
        return;
      }

      if (mode === 'replace') {
        records = [...validRows];
      } else {
        records = [...records, ...validRows];
      }

      saveRecords(records);
      handleResetAllFilters();
      updateSummaryStats();
      tableCtrl.setData(records, false);

      if (mode === 'append') {
        const nextState = tableCtrl.getState();
        if (nextState.sortKey === 'urutan' && nextState.sortDirection === 'asc' && nextState.totalPages > 1) {
          tableCtrl.setPage(nextState.totalPages);
        }
      }

      window.bootstrap?.Modal.getOrCreateInstance(importModalEl)?.hide();
      window.SimdesApp?.showToast({
        title: 'Import Excel Berhasil',
        message: `${validRows.length} data penduduk berhasil diimport ke Buku Induk Penduduk${
          skippedCount > 0 ? ` (${skippedCount} baris dilewati)` : ''
        }.`,
        variant: 'success'
      });
    });

    /* ----------------------------------------------------------------------
       Manual Form Validation & Submission
       ---------------------------------------------------------------------- */
    if (formEl && window.SimdesForms?.InteractionValidator) {
      window.SimdesForms.InteractionValidator.bindForm(formEl);

      formEl.addEventListener('submit', (e) => {
        e.preventDefault();
        hideFormBanner();

        const validBase = window.SimdesForms.InteractionValidator.validateAll(formEl);
        if (!validBase) {
          showFormBanner('Mohon lengkapi seluruh kolom wajib penduduk sesuai format yang diminta.');
          return;
        }

        const existingId = fId.value.trim();
        const nikVal = fNik.value.trim();

        // Cek duplikasi NIK pada buku induk
        const duplicateNik = records.find((r) => r.nik === nikVal && r.id !== existingId);
        if (duplicateNik) {
          window.SimdesForms.InteractionValidator.syncFieldState(
            fNik,
            `NIK ${nikVal} sudah terdaftar atas nama ${duplicateNik.namaLengkap}.`
          );
          showFormBanner(`NIK ${nikVal} sudah digunakan pada Buku Induk Penduduk.`);
          fNik.focus();
          return;
        }

        const payload = {
          id: existingId || `bip-${Date.now()}`,
          namaLengkap: fNama.value.trim(),
          jenisKelamin: getRadioValue(formEl, 'jenisKelamin', 'Laki-laki'),
          statusPerkawinan: fStatusPerkawinan.value,
          tempatLahir: fTempatLahir.value.trim(),
          tanggalLahir: fTanggalLahir.value,
          agama: fAgama.value,
          pendidikanTerakhir: fPendidikan.value,
          pekerjaan: fPekerjaan.value.trim(),
          dapatMembaca: getRadioValue(formEl, 'dapatMembaca', 'Ya'),
          kewarganegaraan: getRadioValue(formEl, 'kewarganegaraan', 'WNI'),
          alamatLengkap: fAlamat.value.trim(),
          kedudukanKeluarga: fKedudukan.value,
          nik: nikVal,
          nomorKk: fNomorKk.value.trim(),
          keterangan: fKeterangan.value.trim()
        };

        if (existingId) {
          records = records.map((r) => (r.id === existingId ? { ...r, ...payload } : r));
        } else {
          records = [...records, payload];
        }

        saveRecords(records);
        updateSummaryStats();
        tableCtrl.setData(records, Boolean(existingId));

        if (!existingId) {
          const nextState = tableCtrl.getState();
          if (nextState.sortKey === 'urutan' && nextState.sortDirection === 'asc' && nextState.totalPages > 1) {
            tableCtrl.setPage(nextState.totalPages);
          }
        }

        window.bootstrap?.Modal.getOrCreateInstance(formModalEl).hide();
        window.SimdesApp?.showToast({
          title: existingId ? 'Data Penduduk Diperbarui' : 'Penduduk Baru Ditambahkan',
          message: `Data "${payload.namaLengkap}" berhasil disimpan ke Buku Induk Penduduk.`,
          variant: 'success'
        });
      });
    }

    // Table Body Event Delegation
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
        if (act === 'reset-filter') handleResetAllFilters();
        else if (act === 'add-new') openCreateModal();
        else if (act === 'import-excel') openImportModal();
      }
    });

    btnTambah?.addEventListener('click', openCreateModal);

    btnEditFromDetail?.addEventListener('click', () => {
      if (!activeDetailId) return;
      const targetId = activeDetailId;
      window.bootstrap?.Modal.getOrCreateInstance(detailModalEl)?.hide();
      window.setTimeout(() => openEditModal(targetId), 180);
    });

    confirmDeleteBtn?.addEventListener('click', () => {
      if (!pendingDeleteId) return;
      const target = records.find((r) => r.id === pendingDeleteId);
      records = records.filter((r) => r.id !== pendingDeleteId);
      pendingDeleteId = null;

      saveRecords(records);
      updateSummaryStats();
      tableCtrl.setData(records, true);

      window.bootstrap?.Modal.getOrCreateInstance(deleteModalEl)?.hide();
      window.SimdesApp?.showToast({
        title: 'Data Penduduk Dihapus',
        message: target
          ? `"${target.namaLengkap}" telah dihapus dan nomor urut disesuaikan otomatis.`
          : 'Data penduduk berhasil dihapus.',
        variant: 'warning'
      });
    });

    resetSampleBtn?.addEventListener('click', () => {
      records = cloneSeedData();
      saveRecords(records);
      handleResetAllFilters();
      updateSummaryStats();
      tableCtrl.setData(records, false);
      window.SimdesApp?.showToast({
        title: 'Data Contoh Dipulihkan',
        message: '12 data contoh fiktif Buku Induk Penduduk berhasil dimuat ulang.',
        variant: 'primary'
      });
    });

    // Full-Table Landscape Print Support
    function preparePrintView() {
      const state = tableCtrl.getState();
      if (tbodyEl) {
        tbodyEl.innerHTML = renderRowsHtml(state.allFilteredRows, 0, state.hasActiveQuery);
      }
      if (printMetaEl) {
        try {
          const nowStr = new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
          }).format(new Date());
          printMetaEl.textContent = `Total penduduk tercetak: ${state.allFilteredRows.length} jiwa • Dicetak pada ${nowStr}`;
        } catch (_) {
          printMetaEl.textContent = `Total penduduk tercetak: ${state.allFilteredRows.length} jiwa`;
        }
      }
    }

    window.addEventListener('beforeprint', preparePrintView);
    window.addEventListener('afterprint', () => tableCtrl.render());

    btnCetak?.addEventListener('click', () => {
      preparePrintView();
      window.print();
    });

    // Initial Boot
    updateSummaryStats();
    tableCtrl.render();
  }

  document.addEventListener('DOMContentLoaded', initBukuIndukPenduduk);
})();
