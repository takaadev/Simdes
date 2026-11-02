# PROMPT ANTIGRAVITY — IMPLEMENTASI HALAMAN B.4 DAN B.5 ADMINISTRASI PENDUDUK SIMDES TIARO

Lanjutkan pengembangan frontend **SIMDES TIARO (Sistem Informasi Manajemen Desa)** dengan mengimplementasikan dua halaman berikut pada kelompok **B. ADMINISTRASI PENDUDUK**:

1. **B.4 — Buku Penduduk Sementara**
2. **B.5 — Buku Kartu Tanda Penduduk dan Buku Kartu Keluarga**

Kedua halaman harus dibuat secara lengkap dan mengikuti standar desain, layout, komponen, serta pola interaksi yang sudah digunakan pada halaman buku administrasi sebelumnya.

**PENTING:** Fokus hanya pada dua halaman tersebut. Jangan mengubah halaman lain, struktur navigasi yang sudah ada, atau membuat modul tambahan.

---

# 1. KETENTUAN UMUM TEKNOLOGI

Gunakan teknologi berikut:

- HTML5.
- CSS3.
- Vanilla JavaScript.
- Bootstrap 5 melalui CDN.
- Font Awesome melalui CDN.
- Font Plus Jakarta Sans atau Inter.
- `localStorage` untuk simulasi penyimpanan data.
- Data dummy yang sepenuhnya fiktif.

Jangan menggunakan:
- React.
- Vue.
- Angular.
- Tailwind CSS.
- jQuery.
- Filament.
- Backend.
- API.
- Database.

Implementasikan sebagai prototipe frontend yang seluruh interaksinya dapat digunakan tanpa koneksi backend.

# 2. IDENTITAS VISUAL DAN KONSISTENSI

Pertahankan identitas visual SIMDES TIARO:

| Elemen | Warna |
|---|---|
| Primary | `#174C3A` |
| Deep Green | `#10382D` |
| Secondary | `#477568` |
| Background | `#F7F8F4` |
| Surface | `#FFFFFF` |
| Border | `#E4E9E3` |
| Accent | `#D9AE52` |
| Text | `#263A32` |

Gunakan desain Modern Government Administration Dashboard yang profesional, bersih, elegan, dan minimalis. Hindari desain dashboard generik atau tampilan yang terlihat seperti template AI.

Pertahankan komponen global yang sudah tersedia:

- Sidebar.
- Topbar.
- Profil pengguna.
- Breadcrumb.
- Layout konten.
- Tombol dan komponen global.
- Sistem notifikasi.
- Modal.
- Pagination.
- Search dan filter.

Jangan membuat ulang layout global apabila sudah tersedia.

Pastikan kedua halaman konsisten dengan halaman buku sebelumnya dan responsif pada desktop, laptop, tablet, serta mobile.

---

# 3. HALAMAN B.4 — BUKU PENDUDUK SEMENTARA

## A. Identitas Halaman

- Kode: B.4.
- Judul: Buku Penduduk Sementara.
- Kelompok: B. ADMINISTRASI PENDUDUK.
- Breadcrumb: Dashboard / Administrasi Penduduk / Buku Penduduk Sementara.

Deskripsi:

Buku Penduduk Sementara digunakan untuk mencatat identitas orang yang tinggal atau berkunjung sementara di wilayah desa, termasuk informasi kedatangan, tujuan kunjungan, pihak yang didatangi, serta tanggal kedatangan dan kepergian.

Judul laporan harus mengikuti format:

**BUKU PENDUDUK SEMENTARA TAHUN [TAHUN]**

Sediakan pilihan tahun pada bagian atas halaman.

Ketika tahun berubah:
- Judul laporan harus menyesuaikan tahun terpilih.
- Data yang ditampilkan harus mengikuti tahun terpilih.
- Ringkasan informasi harus diperbarui.
- Data dari tahun lain tidak boleh tercampur.

## B. Struktur Tabel — WAJIB SESUAI SUMBER

Tabel harus mempertahankan tepat **15 posisi kolom** sesuai referensi sumber.

Gunakan susunan posisi berikut:

| No. | Nama Kolom |
|---:|---|
| 1 | NOMOR URUT |
| 2 | NAMA LENGKAP |
| 3 | L |
| 4 | P |
| 5 | KEBANGSAAN |
| 6 | KETURUNAN |
| 7 | TEMPAT DAN TANGGAL LAHIR/UMUR |
| 8 | PEKERJAAN |
| 9 | KEWARGANEGARAAN |
| 10 | DATANG DARI |
| 11 | MAKSUD DAN TUJUAN KEDATANGAN |
| 12 | NAMA DAN ALAMAT YG DIDATANGI |
| 13 | DATANG TANGGAL |
| 14 | PERGI TANGGAL |
| 15 | KET |

### Hierarki Header Tabel

Buat header bertingkat menggunakan HTML `<thead>` dengan dua baris.

**Baris header pertama:**

- NOMOR URUT — `rowspan="2"`.
- NAMA LENGKAP — `rowspan="2"`.
- JENIS KELAMIN — `colspan="2"`.
- NOMOR IDENTITAS/TANDA PENGENAL — `colspan="2"`.
- TEMPAT DAN TANGGAL LAHIR/UMUR — `rowspan="2"`.
- PEKERJAAN — `rowspan="2"`.
- KEWARGANEGARAAN — `rowspan="2"`.
- DATANG DARI — `rowspan="2"`.
- MAKSUD DAN TUJUAN KEDATANGAN — `rowspan="2"`.
- NAMA DAN ALAMAT YG DIDATANGI — `rowspan="2"`.
- DATANG TANGGAL — `rowspan="2"`.
- PERGI TANGGAL — `rowspan="2"`.
- KET — `rowspan="2"`.

**Baris header kedua:**

Di bawah JENIS KELAMIN:
- L.
- P.

Di bawah NOMOR IDENTITAS/TANDA PENGENAL:
- KEBANGSAAN.
- KETURUNAN.

### Ketentuan Jumlah Kolom

Jumlah kolom fisik berdasarkan hierarki header yang diketahui adalah **15 kolom**.

Pastikan:
- Jumlah kolom akhir tepat 15.
- `colspan` dan `rowspan` konsisten.
- Tidak ada header yang bergeser.
- Jangan menambahkan kolom yang tidak tersedia.
- Jangan membuat posisi tambahan yang tidak didukung struktur header sumber.

Catatan: Nomor identitas/tanda pengenal dipecah menjadi dua subkolom, yaitu Kebangsaan dan Keturunan. Tempat dan tanggal lahir/umur tetap menjadi satu kolom tabel.

## C. Form Tambah dan Edit Penduduk Sementara

Buat modal form berukuran besar atau halaman form yang nyaman digunakan.

Kelompokkan input sebagai berikut.

### 1. Identitas Penduduk Sementara

- Nomor Urut — otomatis.
- Nama Lengkap.
- Jenis Kelamin:
  - Laki-laki.
  - Perempuan.
- Nomor Identitas/Tanda Pengenal.
- Kebangsaan.
- Keturunan.
- Tempat Lahir.
- Tanggal Lahir.
- Umur.
- Pekerjaan.
- Kewarganegaraan.

Catatan:
- Tempat Lahir, Tanggal Lahir, dan Umur merupakan informasi dalam satu kelompok kolom sumber.
- Pada form, input boleh dipisahkan agar mudah diisi.
- Jangan menjadikannya kolom tabel tambahan.
- Kebangsaan dan Keturunan harus disimpan terpisah sesuai subkolom tabel.

### 2. Informasi Kedatangan

- Datang Dari.
- Maksud dan Tujuan Kedatangan.
- Nama dan Alamat yang Didatangi.
- Tanggal Datang.

### 3. Informasi Kepergian

- Tanggal Pergi.

Tanggal pergi boleh dikosongkan apabila penduduk sementara tersebut belum meninggalkan wilayah desa.

### 4. Keterangan

- Keterangan — opsional.

Gunakan:
- Input teks untuk nama, pekerjaan, asal, dan alamat.
- Date picker untuk tanggal lahir, tanggal datang, dan tanggal pergi.
- Input angka untuk umur jika diperlukan.
- Select atau radio untuk jenis kelamin.
- Textarea untuk tujuan kedatangan dan keterangan jika diperlukan.

## D. Fitur Halaman B.4

Implementasikan seluruh fitur berikut:

1. Menampilkan data penduduk sementara dalam tabel.
2. Tombol Tambah Penduduk Sementara.
3. Modal tambah data.
4. Edit data.
5. Detail data.
6. Hapus data dengan konfirmasi.
7. Pencarian berdasarkan nama, nomor identitas, atau nama pihak yang didatangi.
8. Filter berdasarkan jenis kelamin.
9. Filter berdasarkan kewarganegaraan.
10. Filter berdasarkan status kedatangan atau kepergian.
11. Filter berdasarkan tahun.
12. Tombol reset filter.
13. Pagination.
14. Sorting berdasarkan kolom yang relevan.
15. Tombol cetak buku.
16. Validasi input.
17. Penyimpanan data menggunakan `localStorage`.

Pastikan pencarian, filter, sorting, dan pagination dapat digunakan secara bersamaan.

## E. Status Penduduk Sementara

Sediakan indikator status berdasarkan tanggal pergi:

- **Masih Tinggal:** tanggal pergi belum diisi.
- **Sudah Pergi:** tanggal pergi sudah diisi.

Status ini hanya indikator tambahan pada antarmuka, bukan kolom resmi baru dalam tabel.

Jangan menambahkan kolom status ke dalam struktur tabel resmi.

## F. Ringkasan Informasi B.4

Tambahkan ringkasan sederhana di atas tabel:

- Total Penduduk Sementara.
- Jumlah Laki-laki.
- Jumlah Perempuan.
- Masih Tinggal.
- Sudah Pergi.

Seluruh angka harus mengikuti tahun dan filter yang sedang dipilih.

## G. Tampilan Detail B.4

Buat tampilan detail yang mengelompokkan informasi:

**Identitas Penduduk**
- Nama lengkap.
- Jenis kelamin.
- Nomor identitas/tanda pengenal.
- Kebangsaan.
- Keturunan.
- Tempat dan tanggal lahir.
- Umur.
- Pekerjaan.
- Kewarganegaraan.

**Informasi Kedatangan**
- Datang dari.
- Maksud dan tujuan kedatangan.
- Nama dan alamat yang didatangi.
- Tanggal datang.

**Informasi Kepergian**
- Tanggal pergi.
- Status masih tinggal atau sudah pergi.

**Keterangan**
- Keterangan tambahan.

Nilai yang kosong ditampilkan dengan tanda `—`.

## H. Data Dummy B.4

Buat beberapa data penduduk sementara fiktif dengan variasi:

- Jenis kelamin.
- Kebangsaan.
- Keturunan.
- Pekerjaan.
- Kewarganegaraan.
- Daerah asal.
- Tujuan kedatangan.
- Nama dan alamat pihak yang didatangi.
- Tanggal datang.
- Tanggal pergi.

Sertakan contoh penduduk yang masih tinggal dan yang sudah pergi.

Jangan menggunakan identitas pribadi atau nomor tanda pengenal milik orang sungguhan.

---

# 4. HALAMAN B.5 — BUKU KARTU TANDA PENDUDUK DAN BUKU KARTU KELUARGA

## A. Identitas Halaman

- Kode: B.5.
- Judul: Buku Kartu Tanda Penduduk dan Buku Kartu Keluarga.
- Kelompok: B. ADMINISTRASI PENDUDUK.
- Breadcrumb: Dashboard / Administrasi Penduduk / Buku Kartu Tanda Penduduk dan Buku Kartu Keluarga.

Deskripsi:

Buku ini digunakan untuk mencatat informasi Kartu Tanda Penduduk dan Kartu Keluarga penduduk desa, meliputi identitas pribadi, informasi keluarga, status perkawinan, kewarganegaraan, serta informasi penerbitan dokumen dan mulai tinggal di desa.

Judul laporan mengikuti format:

**BUKU KARTU TANDA PENDUDUK TAHUN [TAHUN] DAN BUKU KARTU KELUARGA**

Sediakan pilihan tahun pada bagian atas halaman.

Perubahan tahun harus memperbarui:
- Judul laporan.
- Data yang ditampilkan.
- Ringkasan informasi.

## B. Struktur Tabel — WAJIB SESUAI SUMBER

Tabel harus memiliki tepat **19 kolom** dengan urutan sebagai berikut:

| No. | Nama Kolom |
|---:|---|
| 1 | NOMOR URUT |
| 2 | NO. KK |
| 3 | NAMA LENGKAP |
| 4 | NIK |
| 5 | JENIS KELAMIN |
| 6 | TEMPAT/TANGGAL LAHIR |
| 7 | GOL. DARAH |
| 8 | AGAMA |
| 9 | PENDIDIKAN |
| 10 | PEKERJAAN |
| 11 | ALAMAT |
| 12 | STATUS PERKAWINAN |
| 13 | TEMPAT DAN TANGGAL DIKELUARKAN |
| 14 | STATUS HUB. KELUARGA |
| 15 | KEWARGANEGARAAN |
| 16 | AYAH |
| 17 | IBU |
| 18 | TGL MULAI TINGGAL DI DESA |
| 19 | KET |

## C. Hierarki Header Tabel

Gunakan header bertingkat dengan dua baris.

**Baris pertama:**

- NOMOR URUT — `rowspan="2"`.
- NO. KK — `rowspan="2"`.
- NAMA LENGKAP — `rowspan="2"`.
- NIK — `rowspan="2"`.
- JENIS KELAMIN — `rowspan="2"`.
- TEMPAT/TANGGAL LAHIR — `rowspan="2"`.
- Gol. Darah — `rowspan="2"`.
- AGAMA — `rowspan="2"`.
- PENDIDIKAN — `rowspan="2"`.
- PEKERJAAN — `rowspan="2"`.
- ALAMAT — `rowspan="2"`.
- STATUS PERKAWINAN — `rowspan="2"`.
- TEMPAT DAN TANGGAL DIKELUARKAN — `rowspan="2"`.
- STATUS HUB. KELUARGA — `rowspan="2"`.
- KEWARGANEGARAAN — `rowspan="2"`.
- ORANG TUA — `colspan="2"`.
- TGL MULAI TINGGAL DI DESA — `rowspan="2"`.
- KET — `rowspan="2"`.

**Baris kedua:**

Di bawah kelompok ORANG TUA:
- AYAH.
- IBU.

Pastikan:
- Jumlah kolom data tepat 19.
- Kelompok ORANG TUA hanya merupakan header pengelompokan.
- ORANG TUA bukan kolom tambahan.
- AYAH dan IBU merupakan dua kolom data yang berbeda.
- `rowspan` dan `colspan` tersusun dengan benar.

## D. Form Tambah dan Edit B.5

Buat modal form besar dengan beberapa kelompok input.

### 1. Identitas Penduduk

- Nomor Urut — otomatis.
- Nomor KK.
- Nama Lengkap.
- NIK.
- Jenis Kelamin.
- Tempat Lahir.
- Tanggal Lahir.
- Golongan Darah.
- Agama.

### 2. Pendidikan dan Pekerjaan

- Pendidikan.
- Pekerjaan.

### 3. Informasi Keluarga dan Alamat

- Alamat.
- Status Perkawinan.
- Status Hubungan Keluarga.
- Nama Ayah.
- Nama Ibu.

### 4. Informasi Dokumen

- Tempat Dikeluarkan.
- Tanggal Dikeluarkan.
- Kewarganegaraan.

### 5. Informasi Domisili

- Tanggal Mulai Tinggal di Desa.

### 6. Keterangan

- Keterangan — opsional.

### Ketentuan Form

1. Tempat Lahir dan Tanggal Lahir boleh dipisahkan menjadi dua input.
2. Tempat Dikeluarkan dan Tanggal Dikeluarkan boleh dipisahkan menjadi dua input.
3. Nama Ayah dan Nama Ibu harus menjadi dua input berbeda.
4. Nomor KK dan NIK harus menggunakan input teks agar angka nol di depan tidak hilang.
5. NIK dan Nomor KK harus memiliki validasi panjang angka yang sesuai, yaitu 16 digit.
6. Nomor Urut dibuat otomatis.
7. Data yang disimpan harus tetap dapat dipetakan ke 19 kolom tabel sumber.

Gunakan pilihan yang sesuai untuk:

**Jenis Kelamin**
- Laki-laki.
- Perempuan.

**Golongan Darah**
- A.
- B.
- AB.
- O.
- Tidak Diketahui.

**Agama**
- Gunakan pilihan agama yang relevan dan dapat dikonfigurasi.

**Pendidikan**
- Gunakan pilihan jenjang pendidikan yang relevan.

**Status Perkawinan**
- Belum Kawin.
- Kawin.
- Cerai Hidup.
- Cerai Mati.

**Status Hubungan Keluarga**
- Kepala Keluarga.
- Istri.
- Anak.
- Anggota Keluarga Lainnya.

**Kewarganegaraan**
- WNI.
- WNA.

Jangan menambahkan kategori tersebut sebagai kolom tabel baru.

## E. Fitur Halaman B.5

Implementasikan seluruh fitur berikut:

1. Menampilkan data KTP dan KK dalam tabel.
2. Tombol Tambah Data Penduduk.
3. Modal tambah data.
4. Edit data.
5. Detail data penduduk.
6. Hapus data dengan konfirmasi.
7. Pencarian berdasarkan nama, NIK, atau Nomor KK.
8. Filter berdasarkan jenis kelamin.
9. Filter berdasarkan agama.
10. Filter berdasarkan status perkawinan.
11. Filter berdasarkan kewarganegaraan.
12. Filter berdasarkan tahun.
13. Tombol reset filter.
14. Pagination.
15. Pengurutan berdasarkan kolom yang relevan.
16. Tombol cetak buku.
17. Validasi input.
18. Penyimpanan data menggunakan `localStorage`.

Pastikan pencarian NIK dan Nomor KK berjalan dengan benar.

## F. Ringkasan Informasi B.5

Tambahkan ringkasan informasi di atas tabel:

- Total Penduduk Terdaftar.
- Jumlah Laki-laki.
- Jumlah Perempuan.
- Jumlah WNI.
- Jumlah WNA.
- Jumlah Kartu Keluarga.

Seluruh angka mengikuti periode dan filter yang aktif.

**Ketentuan penghitungan:**

Jumlah Kartu Keluarga harus dihitung berdasarkan Nomor KK unik, bukan jumlah baris penduduk.

Jika beberapa anggota keluarga memiliki Nomor KK yang sama, hitung sebagai satu Kartu Keluarga.

## G. Tampilan Detail B.5

Buat tampilan detail dengan kelompok informasi:

**Identitas Penduduk**
- Nomor KK.
- Nama lengkap.
- NIK.
- Jenis kelamin.
- Tempat dan tanggal lahir.
- Golongan darah.
- Agama.

**Pendidikan dan Pekerjaan**
- Pendidikan.
- Pekerjaan.

**Informasi Keluarga dan Alamat**
- Alamat.
- Status perkawinan.
- Status hubungan keluarga.
- Nama ayah.
- Nama ibu.

**Informasi Dokumen**
- Tempat dan tanggal dikeluarkan.
- Kewarganegaraan.

**Informasi Domisili**
- Tanggal mulai tinggal di desa.

**Keterangan**
- Keterangan tambahan.

Nilai kosong ditampilkan dengan tanda `—`.

## H. Data Dummy B.5

Buat beberapa data penduduk fiktif dengan variasi:

- Jenis kelamin.
- Golongan darah.
- Agama.
- Pendidikan.
- Pekerjaan.
- Status perkawinan.
- Status hubungan keluarga.
- Kewarganegaraan.

Gunakan beberapa Nomor KK yang sama untuk anggota keluarga dalam satu keluarga agar fitur penghitungan KK unik dapat diuji.

Gunakan NIK dan Nomor KK dummy yang jelas bukan identitas penduduk sungguhan.

---

# 5. DESAIN TABEL DAN RESPONSIVITAS

Kedua halaman memiliki tabel dengan banyak kolom. Prioritaskan keterbacaan dan kesesuaian struktur sumber.

Ketentuan:

- Gunakan Bootstrap `table-responsive`.
- Pertahankan seluruh kolom yang telah ditentukan.
- Gunakan header bertingkat dengan `rowspan` dan `colspan`.
- Gunakan ukuran font tabel yang proporsional.
- Gunakan `white-space: nowrap` pada kolom yang membutuhkan.
- Berikan lebar lebih besar untuk kolom yang berisi nama, alamat, tujuan, dan informasi panjang lainnya.
- Gunakan pembungkusan teks pada kolom yang sesuai.
- Sediakan horizontal scrolling pada layar kecil.
- Jangan mengecilkan font secara berlebihan.
- Nilai kosong ditampilkan menggunakan tanda `—`.
- Tampilkan tanggal dengan format Indonesia yang konsisten.
- Gunakan warna header yang konsisten dengan identitas visual SIMDES TIARO.
- Gunakan border tabel yang halus.
- Gunakan sticky header jika sesuai dan tidak mengganggu layout.

**Jangan menambahkan kolom aksi sebagai bagian dari struktur tabel resmi.**

Gunakan menu aksi pada baris atau mekanisme lain di luar struktur kolom sumber untuk:
- Detail.
- Edit.
- Hapus.

---

# 6. PENGELOLAAN DATA DAN LOCALSTORAGE

Kedua halaman harus memiliki penyimpanan terpisah agar data tidak tercampur.

Gunakan key:

- B.4: `simdes_tiaro_buku_penduduk_sementara`
- B.5: `simdes_tiaro_buku_ktp_kk`

Ketentuan:

1. Setiap data memiliki ID unik.
2. Nomor urut dibuat otomatis.
3. Tambah data tidak boleh menimpa data lain.
4. Edit harus memperbarui data berdasarkan ID.
5. Hapus harus meminta konfirmasi.
6. Data tetap tersedia setelah halaman dimuat ulang.
7. Perubahan tahun dan filter tidak boleh menghapus data.
8. Data dari halaman B.4 tidak boleh tercampur dengan B.5.
9. Gunakan struktur objek JavaScript yang konsisten dan mudah dipelihara.
10. Data dummy hanya dibuat sebagai data awal apabila penyimpanan belum tersedia.

---

# 7. PENCARIAN, FILTER, SORTING, DAN PAGINATION

Pada kedua halaman, pastikan seluruh fungsi bekerja secara terintegrasi.

Urutan pemrosesan data yang disarankan:

1. Ambil seluruh data dari `localStorage`.
2. Terapkan filter tahun.
3. Terapkan pencarian.
4. Terapkan filter kategori.
5. Terapkan sorting.
6. Terapkan pagination.
7. Render tabel dan ringkasan berdasarkan hasil yang sesuai.

Ketentuan tambahan:

- Tombol reset filter harus mengembalikan seluruh filter ke kondisi awal.
- Pencarian tidak boleh hanya mencari data pada halaman pagination yang sedang terbuka.
- Pagination harus diperbarui ketika hasil pencarian atau filter berubah.
- Jika tidak ada hasil, tampilkan empty state yang informatif.
- Sorting harus mempertahankan hasil filter yang sedang aktif.
- Ringkasan informasi harus dihitung berdasarkan tahun dan filter yang sedang dipilih.

---

# 8. FITUR CETAK BUKU

Sediakan tombol cetak pada kedua halaman.

Ketentuan:

- Judul laporan harus sesuai nama buku dan tahun terpilih.
- Tampilkan identitas SIMDES TIARO.
- Tampilkan tanggal cetak.
- Gunakan struktur header tabel yang sama dengan tampilan halaman.
- Pertahankan seluruh kolom sumber.
- Gunakan orientasi landscape untuk tabel yang lebar.
- Atur ukuran kertas dan margin agar tabel dapat dicetak dengan baik.
- Sembunyikan sidebar, topbar, tombol aksi, filter, dan komponen navigasi saat mencetak.
- Jangan mencetak modal atau elemen antarmuka yang tidak diperlukan.

---

# 9. KETENTUAN TEKNIS PROYEK

- Ikuti struktur folder dan pola penamaan file yang sudah digunakan proyek.
- Pisahkan HTML, CSS, dan JavaScript sesuai pola proyek.
- Gunakan komponen global yang sudah tersedia.
- Jangan menggandakan kode layout global.
- Hindari duplikasi CSS dan JavaScript.
- Buat fungsi JavaScript yang terstruktur dan mudah dipelihara.
- Gunakan konfigurasi data apabila diperlukan.
- Gunakan event handling yang konsisten.
- Pastikan semua tombol dan interaksi frontend berfungsi.
- Pastikan tidak ada error JavaScript di console.
- Pastikan kedua halaman dapat dibuka melalui navigasi yang sudah tersedia.

Jangan mengubah:
- Halaman buku lain yang sudah selesai.
- Struktur sidebar.
- Topbar.
- Breadcrumb global.
- Layout global.
- Identitas visual SIMDES TIARO.
- Fitur yang sudah berjalan pada halaman lain.

Jangan membuat modul atau halaman tambahan di luar B.4 dan B.5.

---

# 10. VALIDASI AKHIR

Sebelum menyelesaikan pekerjaan, lakukan pemeriksaan menyeluruh.

### Pemeriksaan Halaman B.4

1. Halaman B.4 berhasil dibuat.
2. Judul, deskripsi, dan breadcrumb benar.
3. Pilihan tahun berfungsi.
4. Judul laporan berubah sesuai tahun.
5. Struktur tabel memiliki tepat 15 kolom.
6. Header memiliki dua tingkat.
7. Kelompok JENIS KELAMIN memiliki dua subkolom L dan P.
8. Kelompok NOMOR IDENTITAS/TANDA PENGENAL memiliki dua subkolom KEBANGSAAN dan KETURUNAN.
9. Tempat dan tanggal lahir/umur tetap satu kolom tabel.
10. Tanggal datang dan tanggal pergi tetap menjadi dua kolom berbeda.
11. Status masih tinggal dan sudah pergi berfungsi.
12. Ringkasan informasi sesuai tahun dan filter.
13. Tambah, edit, detail, dan hapus berfungsi.
14. Pencarian dan seluruh filter berfungsi.
15. Pagination dan sorting berfungsi.
16. Data tersimpan di localStorage.
17. Cetak buku berfungsi.

### Pemeriksaan Halaman B.5

1. Halaman B.5 berhasil dibuat.
2. Judul, deskripsi, dan breadcrumb benar.
3. Pilihan tahun berfungsi.
4. Judul laporan berubah sesuai tahun.
5. Struktur tabel memiliki tepat 19 kolom.
6. Header memiliki dua tingkat.
7. Kelompok ORANG TUA menggunakan `colspan="2"`.
8. AYAH dan IBU merupakan dua kolom terpisah.
9. Nomor KK dan NIK menggunakan input teks.
10. Validasi panjang Nomor KK dan NIK berfungsi.
11. Nomor KK dan NIK tidak kehilangan angka nol di depan.
12. Jumlah KK dihitung berdasarkan Nomor KK unik.
13. Ringkasan informasi sesuai tahun dan filter.
14. Tambah, edit, detail, dan hapus berfungsi.
15. Pencarian dan seluruh filter berfungsi.
16. Pagination dan sorting berfungsi.
17. Data tersimpan di localStorage.
18. Cetak buku berfungsi.

### Pemeriksaan Umum

1. Kedua halaman konsisten dengan desain SIMDES TIARO.
2. Kedua halaman responsif.
3. Tabel dapat digulir horizontal.
4. Tidak ada kolom resmi yang ditambahkan atau dihilangkan.
5. Seluruh tombol dan interaksi berfungsi.
6. Tidak ada error JavaScript di console.
7. Tidak ada halaman lain yang berubah.
8. Tidak ada menu atau modul tambahan yang dibuat.

# 11. HASIL AKHIR

Implementasikan dua halaman berikut secara lengkap:

- **B.4 — Buku Penduduk Sementara**
- **B.5 — Buku Kartu Tanda Penduduk dan Buku Kartu Keluarga**

Keduanya merupakan prototipe frontend SIMDES TIARO dengan struktur tabel sesuai sumber administrasi, fitur CRUD, pencarian, filter, sorting, pagination, ringkasan informasi, cetak buku, dan penyimpanan localStorage.

**PRIORITAS UTAMA: KESESUAIAN STRUKTUR TABEL SUMBER DAN KONSISTENSI DENGAN HALAMAN ADMINISTRASI YANG SUDAH ADA.**

Kerjakan langsung pada proyek yang sedang terbuka. Jangan hanya membuat mockup atau contoh tampilan. Implementasikan seluruh interaksi yang diminta, lalu berikan ringkasan file yang dibuat atau diubah serta hasil pemeriksaan kedua halaman.