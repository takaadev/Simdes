# PENGEMBANGAN HALAMAN PENGATURAN, PROFIL PENGGUNA, MANAJEMEN PENGGUNA, DAN MANAJEMEN JABATAN SIMDES TIARO

Kembangkan empat halaman pendukung pada aplikasi **SIMDES TIARO (Sistem Informasi Manajemen Desa)**:

1. Pengaturan
2. Profil Pengguna
3. Manajemen Pengguna (CRUD)
4. Manajemen Jabatan/Posisi (CRUD)

Implementasikan seluruh halaman dengan desain profesional, modern, konsisten, dan sesuai identitas visual SIMDES TIARO.

**PENTING: HALAMAN LOGIN SUDAH TERSEDIA. JANGAN MEMBUAT, MENGGANTI, MENDESAIN ULANG, ATAU MENGUBAH HALAMAN LOGIN YANG SUDAH ADA.**

Gunakan sistem login dan mekanisme sesi yang telah tersedia sebagai acuan integrasi.

---

# BAGIAN 1 — ANALISIS PROYEK SEBELUM IMPLEMENTASI

Sebelum mulai mengerjakan:

1. Periksa struktur direktori proyek.
2. Periksa seluruh halaman yang sudah tersedia.
3. Periksa layout utama, sidebar, topbar, dropdown profil, dan sistem navigasi.
4. Periksa stylesheet, JavaScript, serta komponen yang sudah digunakan.
5. Identifikasi mekanisme login dan penyimpanan sesi yang sudah tersedia.
6. Identifikasi pola tabel, modal, formulir, notifikasi, dan pagination dari halaman administrasi yang sudah selesai.
7. Periksa struktur data pengguna dan konfigurasi role yang sudah tersedia.
8. Periksa apakah menu atau halaman manajemen jabatan sudah pernah dibuat.

Gunakan kembali komponen dan pola kode yang tersedia. Hindari membuat komponen atau fungsi duplikat.

**Jangan merusak, menghapus, atau mengubah struktur maupun fungsionalitas halaman administrasi yang sudah selesai dibuat.**

Jangan membuat sistem login baru karena sistem login sudah tersedia.

---

# BAGIAN 2 — HALAMAN PENGATURAN

## 1. Identitas halaman

- Nama halaman: Pengaturan
- Menu: Pengaturan

Halaman ini digunakan untuk mengelola konfigurasi dasar aplikasi SIMDES TIARO.

Gunakan navigasi tab atau sidebar internal agar setiap kelompok pengaturan mudah diakses.

## 2. Kelompok pengaturan

### A. Pengaturan Identitas Desa

Sediakan formulir:

- Nama Desa.
- Kecamatan.
- Kabupaten.
- Provinsi.
- Kode Pos.
- Alamat Kantor Desa.
- Nomor Telepon.
- Email Desa.
- Nama Kepala Desa.
- Logo Desa.

Fitur:

- Edit informasi desa.
- Upload atau ganti logo desa.
- Preview logo sebelum disimpan.
- Validasi input.
- Tombol Simpan Perubahan.
- Tombol Reset.

Logo yang diunggah harus memiliki preview dan dapat diganti.

### B. Pengaturan Aplikasi

Sediakan pengaturan:

- Nama Aplikasi.
- Deskripsi Aplikasi.
- Versi Aplikasi.
- Tahun Administrasi.
- Format Tanggal.
- Zona Waktu.

Pilihan zona waktu Indonesia:

- Asia/Jakarta.
- Asia/Makassar.
- Asia/Jayapura.

### C. Pengaturan Tampilan

Sediakan pengaturan:

- Tema: Terang dan Gelap.
- Ukuran tampilan: Normal dan Compact.
- Sidebar: Expanded dan Collapsed.
- Warna aksen jika memang diperlukan.

Ketentuan:

- Tema dan preferensi tampilan dapat diterapkan langsung.
- Preferensi tetap tersimpan setelah halaman dimuat ulang.
- Jangan mengubah identitas warna utama SIMDES TIARO ketika pengguna mengganti preferensi tampilan.
- Pastikan perubahan tema tidak merusak kontras, keterbacaan, atau tampilan komponen.

### D. Pengaturan Akun dan Keamanan

Sediakan:

- Ubah Password.
- Password Lama.
- Password Baru.
- Konfirmasi Password Baru.
- Tombol Tampilkan/Sembunyikan Password.
- Informasi sesi pengguna.
- Tombol Keluar dari Semua Sesi (simulasi frontend).

Validasi:

- Password lama wajib diisi.
- Password baru dan konfirmasi harus cocok.
- Password baru harus memenuhi ketentuan minimal keamanan.
- Tampilkan pesan berhasil atau gagal.

Catatan: Proyek masih frontend-only. Proses keamanan hanya berupa simulasi antarmuka. Jangan mengklaim bahwa pengamanan akun atau sesi benar-benar diterapkan di server.

Jangan membuat mekanisme autentikasi baru. Sesuaikan tampilan pengaturan keamanan dengan struktur akun yang sudah tersedia.

## 3. Interaksi dan penyimpanan

- Setiap tab dapat dibuka.
- Setiap formulir dapat diedit.
- Perubahan dapat disimpan menggunakan localStorage.
- Tampilkan notifikasi ketika perubahan berhasil disimpan.
- Tampilkan konfirmasi sebelum reset.
- Data pengaturan tetap tersedia setelah halaman dimuat ulang.
- Data awal hanya digunakan jika belum tersedia di localStorage.
- Jangan menimpa data yang sudah disimpan setiap kali halaman dimuat.

---

# BAGIAN 3 — HALAMAN PROFIL PENGGUNA

## 1. Identitas halaman

- Nama halaman: Profil Saya.
- Menu akses: Dropdown profil pengguna pada topbar.

Halaman ini digunakan untuk melihat dan memperbarui informasi akun pengguna yang sedang login.

Integrasikan dengan mekanisme pengguna dan sesi yang sudah tersedia. Jangan membuat akun aktif atau sesi login baru yang terpisah dari sistem yang sudah ada.

## 2. Desain halaman profil

Buat tampilan profil yang elegan, profesional, dan tidak terlalu padat.

Bagian atas menampilkan:

- Foto profil.
- Nama lengkap.
- Username.
- Role atau hak akses.
- Jabatan.
- Status akun.
- Tombol Edit Profil.

Gunakan layout dua bagian.

**Bagian kiri:**

- Foto profil.
- Nama pengguna.
- Username.
- Jabatan.
- Role.
- Status akun.

**Bagian kanan:**

- Informasi detail akun.
- Form pengeditan profil.

## 3. Informasi profil

Sediakan informasi:

- Nama Lengkap.
- Username.
- Email.
- Nomor Telepon.
- Alamat.
- Foto Profil.
- Jabatan.
- Role/Hak Akses.
- Tanggal Dibuat.
- Terakhir Login.

Ketentuan:

- Role dan status akun hanya ditampilkan sebagai informasi.
- Pengguna tidak boleh mengubah role atau hak akses melalui halaman profil.
- Username, tanggal dibuat, dan informasi lain yang hanya boleh dikelola administrator harus read-only.
- Jabatan ditampilkan sebagai informasi profil dan tidak dapat diubah sendiri oleh pengguna.
- Jabatan diambil dari data master jabatan/posisi yang dikelola melalui Manajemen Jabatan.
- Jangan menampilkan password pengguna.

## 4. Fitur profil

Implementasikan:

1. Melihat detail profil.
2. Mengubah nama lengkap.
3. Mengubah email.
4. Mengubah nomor telepon.
5. Mengubah alamat.
6. Mengganti foto profil.
7. Preview foto sebelum disimpan.
8. Mengubah password.
9. Validasi formulir.
10. Notifikasi berhasil atau gagal.
11. Penyimpanan perubahan melalui localStorage.

Buat bagian khusus untuk mengubah password:

- Password Lama.
- Password Baru.
- Konfirmasi Password Baru.
- Tombol Tampilkan/Sembunyikan Password.

Password lama tidak boleh ditampilkan atau diisikan otomatis ke dalam formulir.

## 5. Informasi aktivitas akun

Tambahkan bagian aktivitas akun sederhana:

- Aktivitas login terakhir.
- Waktu login terakhir.
- Status akun.

Gunakan data dummy atau data sesi yang tersedia. Jangan membuat sistem audit atau pencatatan aktivitas server.

---

# BAGIAN 4 — HALAMAN MANAJEMEN PENGGUNA (CRUD)

## 1. Identitas halaman

- Nama halaman: Manajemen Pengguna.
- Menu: Pengguna.

Halaman ini digunakan untuk mengelola akun pengguna yang dapat mengakses aplikasi SIMDES TIARO.

Buat halaman manajemen pengguna dengan pola tabel dan modal yang konsisten dengan halaman buku administrasi lainnya.

## 2. Tabel pengguna

Tampilkan tabel dengan kolom:

| No. | Kolom |
|---|---|
| 1 | NO. |
| 2 | FOTO |
| 3 | NAMA LENGKAP |
| 4 | USERNAME |
| 5 | EMAIL |
| 6 | NOMOR TELEPON |
| 7 | JABATAN |
| 8 | ROLE |
| 9 | STATUS |
| 10 | TERAKHIR LOGIN |
| 11 | AKSI |

Kolom aksi menyediakan:

- Detail.
- Edit.
- Hapus.
- Aktifkan/Nonaktifkan.

Gunakan badge atau indikator visual yang konsisten untuk membedakan role dan status akun.

Jabatan diambil dari relasi data master `VillagePosition`, bukan ditulis secara manual.

## 3. Form tambah pengguna

Buat modal tambah pengguna dengan beberapa bagian.

### A. Informasi Pengguna

- Nama Lengkap.
- Username.
- Email.
- Nomor Telepon.
- Alamat.
- Foto Profil.

### B. Informasi Kepegawaian

- Jabatan/Posisi.
- Role/Hak Akses.

Jabatan harus menggunakan pilihan dari data master `village_positions`.

Jangan mencampur jabatan dengan role. Contoh:

- Kepala Desa adalah jabatan.
- Sekretaris Desa adalah jabatan.
- KAUR Keuangan adalah jabatan.
- Administrator adalah role.
- Operator Desa adalah role.

### C. Informasi Akun

- Password.
- Konfirmasi Password.
- Status Akun.

Role harus berasal dari konfigurasi data aplikasi. Jangan menganggap bahwa seluruh role tertentu sudah menjadi ketentuan resmi SIMDES TIARO.

Untuk data dummy, sediakan contoh role:

- Administrator.
- Operator Desa.
- Kepala Desa.

Ketiga role tersebut merupakan contoh konfigurasi awal, bukan ketentuan hak akses final.

### D. Status Akun

Pilihan:

- Aktif.
- Nonaktif.

Pengguna nonaktif harus ditampilkan dengan indikator status yang jelas.

## 4. Fitur CRUD pengguna

### Create

- Tambah pengguna.
- Validasi seluruh input wajib.
- Validasi username unik.
- Validasi email unik.
- Validasi password dan konfirmasi password.
- Pilihan jabatan dari data master.
- Preview foto profil.

### Read

- Menampilkan seluruh pengguna.
- Melihat detail pengguna.
- Menampilkan informasi akun.
- Menampilkan jabatan, role, dan status akun.

### Update

- Edit data pengguna.
- Ganti foto profil.
- Ubah jabatan.
- Ubah role.
- Ubah status akun.
- Ubah informasi akun.
- Password boleh dikosongkan ketika edit jika tidak ingin mengganti password.

### Delete

- Hapus pengguna.
- Tampilkan dialog konfirmasi.
- Jangan izinkan pengguna menghapus akun yang sedang digunakan untuk login.
- Jangan izinkan penghapusan pengguna terakhir dengan role Administrator dalam simulasi.

## 5. Pencarian dan filter

Sediakan:

- Pencarian berdasarkan nama.
- Pencarian berdasarkan username.
- Pencarian berdasarkan email.
- Filter berdasarkan jabatan.
- Filter berdasarkan role.
- Filter berdasarkan status akun.
- Tombol Reset Filter.
- Pagination.
- Pengurutan tabel.

Pastikan pencarian, filter, dan pagination bekerja berdasarkan data pengguna yang sebenarnya tersimpan, bukan hanya mengubah tampilan tabel.

## 6. Ringkasan pengguna

Tambahkan kartu ringkasan:

- Total Pengguna.
- Pengguna Aktif.
- Pengguna Nonaktif.
- Jumlah Administrator.

Seluruh angka harus dihitung secara dinamis berdasarkan data pengguna yang tersedia.

## 7. Simulasi hak akses

Buat simulasi sederhana berdasarkan role pengguna yang sedang login.

Ketentuan:

- Administrator dapat mengakses Manajemen Pengguna.
- Pengguna non-administrator tidak ditampilkan menu Manajemen Pengguna.
- Pengguna non-administrator tidak dapat membuka halaman Manajemen Pengguna melalui navigasi biasa.
- Pengguna yang dinonaktifkan tidak boleh melanjutkan simulasi penggunaan aplikasi.
- Setelah status akun berubah menjadi nonaktif, sesi pengguna tersebut harus dianggap tidak valid pada pemeriksaan frontend berikutnya.

Catatan: Ini hanya simulasi frontend. Pemeriksaan JavaScript bukan sistem otorisasi yang aman untuk aplikasi produksi. Otorisasi sesungguhnya nantinya harus diterapkan di backend Laravel.

---

# BAGIAN 5 — HALAMAN MANAJEMEN JABATAN/POSISI (CRUD)

## 1. Identitas halaman

- Nama halaman: Manajemen Jabatan.
- Menu: Jabatan/Posisi.
- Nama tabel: `village_positions`.

Halaman ini digunakan untuk mengelola data master jabatan atau posisi dalam struktur pemerintahan desa dan lembaga desa.

**PENTING: Jabatan/Posisi berbeda dari Role/Hak Akses pengguna.**

Jabatan menunjukkan kedudukan seseorang dalam organisasi desa, sedangkan role menentukan hak akses pengguna terhadap aplikasi.

Contoh:

- Kepala Desa adalah jabatan.
- Sekretaris Desa adalah jabatan.
- Kaur Keuangan adalah jabatan.
- Ketua BPD adalah jabatan.
- Administrator adalah role.
- Operator Desa adalah role.

Jangan mencampurkan kedua konsep tersebut.

## 2. Struktur tabel

Gunakan struktur tabel yang sudah tersedia pada proyek:

```php
Schema::create('village_positions', function (Blueprint $table) {
    $table->id();
    $table->string('name', 150);
    $table->string('code', 50)->nullable()->unique();
    $table->string('category', 50);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    $table->softDeletes();

    $table->index(['category', 'is_active']);
});
```

Jangan mengubah struktur database yang sudah tersedia tanpa alasan teknis yang jelas.

## 3. Kategori jabatan

Gunakan enum `VillagePositionCategoryList` yang sudah tersedia.

Kategori:

| Kode | Kategori |
|---|---|
| 10 | Pemerintah Desa (Pemdes) |
| 20 | Kepala Urusan (KAUR) |
| 30 | Badan Permusyawaratan Desa (BPD) |
| 40 | Lembaga Adat Desa (LAD) |

Gunakan label kategori dari enum, bukan menulis label secara berulang di berbagai komponen.

## 4. Pola kode jabatan

Gunakan sistem kode bertingkat sesuai kategori.

Contoh:

| Kode | Nama Jabatan | Kategori |
|---|---|---|
| 101 | Kepala Desa | 10 |
| 102 | Sekretaris Desa | 10 |
| 103 | Bendahara Desa | 10 |
| 201 | Kepala KAUR Keuangan | 20 |
| 202 | Kepala KAUR Tata Usaha dan Umum | 20 |
| 203 | Kepala KAUR Perencanaan | 20 |
| 301 | Ketua BPD | 30 |
| 302 | Wakil Ketua BPD | 30 |
| 303 | Sekretaris BPD | 30 |
| 304 | Anggota BPD | 30 |
| 401 | Ketua LAD | 40 |
| 402 | Wakil Ketua LAD | 40 |
| 403 | Sekretaris LAD | 40 |
| 404 | Anggota LAD | 40 |

Ketentuan:

- Kode jabatan harus unik.
- Kode jabatan disimpan sebagai string.
- Kode harus sesuai dengan kategori yang dipilih.
- Jangan menganggap digit terakhir sebagai satu-satunya penentu jabatan pimpinan.
- Pengguna dapat menambahkan kode jabatan baru sesuai kebutuhan.
- Jangan membuat kode secara acak jika pola kode bertingkat sudah digunakan.

## 5. Tampilan halaman

Buat halaman manajemen jabatan dengan desain yang konsisten dengan halaman administrasi lainnya.

Bagian atas:

- Judul halaman: Manajemen Jabatan.
- Deskripsi singkat.
- Tombol Tambah Jabatan.

Tambahkan kartu ringkasan:

- Total Jabatan.
- Jabatan Pemerintah Desa.
- Jabatan BPD.
- Jabatan LAD.
- Jabatan Aktif.

Jumlah ditampilkan secara dinamis berdasarkan data yang tersedia.

## 6. Tabel jabatan

Tampilkan tabel dengan kolom:

| No. | Kolom |
|---|---|
| 1 | NO. |
| 2 | KODE JABATAN |
| 3 | NAMA JABATAN |
| 4 | KATEGORI |
| 5 | JUMLAH PENGGUNA |
| 6 | STATUS |
| 7 | AKSI |

Kolom aksi:

- Detail.
- Edit.
- Hapus.
- Aktifkan/Nonaktifkan.

Jumlah pengguna menunjukkan banyaknya akun pengguna yang menggunakan jabatan tersebut, apabila relasi jabatan pada data pengguna tersedia.

Jika belum tersedia relasinya, jangan membuat jumlah pengguna palsu. Tampilkan nilai nol atau indikator belum digunakan.

## 7. Form tambah jabatan

Buat modal tambah jabatan dengan field:

- Nama Jabatan.
- Kode Jabatan.
- Kategori.
- Status.

Ketentuan:

- Nama jabatan wajib diisi.
- Kode jabatan boleh kosong jika mengikuti struktur tabel yang tersedia.
- Jika kode diisi, kode harus unik.
- Kategori wajib dipilih.
- Status default Aktif.
- Tampilkan preview kategori yang dipilih.
- Berikan validasi kode berdasarkan kategori.

Contoh:

Jika kategori Pemdes dipilih, kode yang digunakan mengikuti kelompok `10`, seperti `101`, `102`, dan seterusnya.

Jika kategori KAUR dipilih, kode yang digunakan mengikuti kelompok `20`, seperti `201`, `202`, dan seterusnya.

Lakukan validasi yang jelas ketika kode tidak sesuai kategori.

## 8. Fitur CRUD jabatan

### Create

- Tambah jabatan.
- Validasi nama jabatan.
- Validasi kode unik.
- Validasi kategori.
- Validasi pola kode sesuai kategori.

### Read

- Menampilkan seluruh jabatan.
- Melihat detail jabatan.
- Menampilkan kategori.
- Menampilkan status.
- Menampilkan jumlah pengguna jika relasinya tersedia.

### Update

- Edit nama jabatan.
- Edit kode jabatan.
- Ubah kategori.
- Ubah status.

### Delete

- Hapus jabatan.
- Tampilkan dialog konfirmasi.
- Jangan menghapus jabatan yang masih digunakan oleh pengguna.
- Jika jabatan masih digunakan, tampilkan pesan bahwa jabatan tersebut tidak dapat dihapus sebelum relasinya dipindahkan.
- Gunakan soft delete sesuai struktur database yang sudah tersedia.

## 9. Pencarian dan filter

Sediakan:

- Pencarian berdasarkan nama jabatan.
- Pencarian berdasarkan kode.
- Filter berdasarkan kategori.
- Filter berdasarkan status.
- Tombol Reset Filter.
- Pagination.
- Pengurutan tabel.

Pastikan seluruh pencarian dan filter bekerja berdasarkan data yang tersimpan.

## 10. Data awal jabatan

Jika belum ada data jabatan, sediakan data dummy awal berdasarkan kategori Pemdes, KAUR, BPD, dan LAD.

Data awal hanya dibuat ketika belum tersedia.

Jangan menimpa jabatan yang sudah ditambahkan atau diubah pengguna.

Jangan membuat data awal berulang setiap kali halaman dibuka.

---

# BAGIAN 6 — INTEGRASI DENGAN SISTEM LOGIN YANG SUDAH ADA

**Jangan membuat halaman login baru. Jangan mengubah desain maupun fungsionalitas halaman login yang telah tersedia.**

Lakukan integrasi empat halaman yang dikembangkan dengan sistem login yang sudah ada.

Ketentuan:

1. Gunakan mekanisme login dan sesi yang telah tersedia.
2. Jangan membuat penyimpanan sesi baru yang bertentangan dengan mekanisme login saat ini.
3. Pastikan informasi pengguna aktif dapat digunakan oleh topbar, profil, dan manajemen pengguna.
4. Nama pengguna muncul di topbar.
5. Foto profil muncul di topbar.
6. Role pengguna ditampilkan.
7. Jabatan pengguna ditampilkan jika tersedia.
8. Dropdown profil menyediakan akses ke Profil Saya.
9. Dropdown tetap menggunakan tombol Logout yang sudah tersedia.
10. Menu Manajemen Pengguna hanya muncul untuk role yang memiliki akses sesuai simulasi.
11. Menu Manajemen Jabatan hanya muncul untuk role Administrator.
12. Informasi pengguna aktif harus konsisten pada seluruh halaman.
13. Perubahan profil harus tercermin pada topbar tanpa perlu membuat sesi pengguna baru.
14. Pengguna yang sudah logout tidak boleh mengakses halaman yang membutuhkan sesi aktif.

Jika mekanisme login yang ada belum mendukung suatu kebutuhan integrasi, sesuaikan secara minimal tanpa merusak implementasi login yang sudah berjalan.

---

# BAGIAN 7 — STANDAR DESAIN GLOBAL

Seluruh halaman wajib menggunakan identitas visual SIMDES TIARO.

## Palet warna

- Primary: `#174C3A`
- Deep Green: `#10382D`
- Secondary: `#477568`
- Background: `#F7F8F4`
- Surface: `#FFFFFF`
- Border: `#E4E9E3`
- Accent: `#D9AE52`
- Text: `#263A32`

## Tipografi

Gunakan:

- Plus Jakarta Sans atau Inter.

## Karakter desain

- Modern Government Administration Dashboard.
- Profesional.
- Bersih.
- Elegan.
- Minimalis.
- Mudah digunakan.
- Tidak terlihat seperti template dashboard generik.
- Tidak menggunakan dekorasi berlebihan.
- Memiliki hierarki visual yang jelas.

## Teknologi antarmuka

Gunakan:

- HTML5.
- CSS3.
- JavaScript Vanilla.
- Bootstrap 5 melalui CDN.
- Font Awesome melalui CDN.

Jangan menggunakan:

- Tailwind CSS.
- React.
- Vue.
- Angular.
- jQuery.

Pertahankan teknologi dan pola implementasi yang sudah digunakan proyek jika terdapat kebutuhan kompatibilitas. Jangan melakukan migrasi teknologi hanya untuk mengerjakan halaman ini.

---

# BAGIAN 8 — STANDAR TEKNIS DAN STRUKTUR KODE

1. Gunakan kembali layout dan komponen yang sudah tersedia.
2. Pisahkan HTML, CSS, dan JavaScript secara terstruktur sesuai pola proyek.
3. Hindari duplikasi CSS dan JavaScript.
4. Gunakan fungsi JavaScript yang dapat digunakan kembali.
5. Gunakan localStorage untuk data dummy apabila sesuai dengan mekanisme penyimpanan proyek.
6. Gunakan data awal hanya jika data belum tersedia.
7. Jangan menimpa data yang sudah tersimpan setiap kali halaman dimuat.
8. Gunakan validasi formulir.
9. Tampilkan notifikasi yang konsisten.
10. Pastikan semua tombol memiliki fungsi nyata.
11. Pastikan seluruh modal dapat dibuka dan ditutup.
12. Pastikan halaman responsif.
13. Jangan mengubah struktur halaman buku administrasi yang sudah selesai.
14. Jangan membuat backend, API, atau database baru.
15. Jangan membuat ulang halaman login.
16. Jangan membuat mekanisme sesi yang terpisah dari sistem login yang sudah ada.
17. Pastikan data profil dan data pengguna memiliki sumber data yang konsisten.
18. Gunakan penyimpanan foto secara efisien dan berikan batas ukuran file yang wajar.
19. Tangani data localStorage yang tidak valid agar halaman tidak mengalami error.
20. Pastikan data dummy tidak menimpa data yang sudah dimodifikasi pengguna.
21. Gunakan satu sumber data master jabatan untuk seluruh halaman yang membutuhkan informasi jabatan.
22. Perubahan data jabatan harus tercermin pada pilihan jabatan di formulir pengguna dan informasi profil.
23. Jangan menyimpan nama jabatan secara manual jika sudah tersedia relasi ke data master jabatan.
24. Pisahkan data jabatan dengan data role pengguna.
25. Jangan menyimpan password dalam bentuk teks biasa di localStorage.

Catatan: localStorage hanya digunakan untuk simulasi frontend. Jangan menyimpan password sebagai teks biasa dan menganggapnya sebagai autentikasi produksi. Jangan mengklaim bahwa simulasi keamanan frontend sudah aman untuk penggunaan nyata.

---

# BAGIAN 9 — PENGUJIAN AKHIR

Setelah implementasi, lakukan pemeriksaan berikut.

## A. Pengaturan

- Semua tab dapat dibuka.
- Semua input dapat diedit.
- Perubahan berhasil disimpan.
- Data tetap tersedia setelah halaman dimuat ulang.
- Preview dan penggantian logo berfungsi.
- Preferensi tampilan dapat diterapkan.

## B. Profil Pengguna

- Informasi profil tampil dengan benar.
- Profil sesuai dengan akun yang sedang login.
- Edit profil berfungsi.
- Foto dapat diganti.
- Preview foto berfungsi.
- Ubah password memiliki validasi.
- Role dan informasi read-only tidak dapat diubah oleh pengguna biasa.
- Jabatan sesuai dengan data master.
- Perubahan profil tercermin pada topbar.

## C. Manajemen Pengguna

- Tambah pengguna berfungsi.
- Tabel menampilkan data pengguna.
- Detail pengguna berfungsi.
- Edit pengguna berfungsi.
- Hapus pengguna berfungsi.
- Pencarian dan filter berfungsi.
- Pagination berfungsi.
- Username dan email unik.
- Jabatan diambil dari data master.
- Pengguna yang sedang login tidak dapat menghapus akunnya sendiri.
- Administrator terakhir tidak dapat dihapus.
- Simulasi role berjalan.
- Status aktif dan nonaktif berfungsi.

## D. Manajemen Jabatan

- Halaman manajemen jabatan dapat dibuka.
- Data jabatan ditampilkan dengan benar.
- Tambah jabatan berfungsi.
- Edit jabatan berfungsi.
- Detail jabatan berfungsi.
- Hapus jabatan berfungsi.
- Jabatan yang masih digunakan tidak dapat dihapus.
- Kode jabatan unik.
- Validasi kode sesuai kategori berfungsi.
- Filter kategori berfungsi.
- Filter status berfungsi.
- Pencarian berfungsi.
- Pagination berfungsi.
- Data jabatan tetap tersimpan setelah halaman dimuat ulang.
- Data awal tidak menimpa data yang sudah dimodifikasi.
- Perubahan jabatan tercermin pada formulir pengguna.

## E. Integrasi Login

- Sistem login yang sudah ada tetap berfungsi.
- Informasi akun aktif terbaca dengan benar.
- Nama dan foto profil muncul di topbar.
- Dropdown profil berfungsi.
- Akses Profil Saya berfungsi.
- Logout tetap menggunakan mekanisme yang sudah tersedia.
- Pengguna nonaktif tidak dapat melanjutkan penggunaan aplikasi.
- Pengguna tanpa sesi aktif tidak dapat mengakses halaman yang memerlukan login.
- Tidak ada duplikasi halaman atau mekanisme login.

## F. Konsistensi dan kualitas

- Sidebar dan topbar tetap konsisten.
- Navigasi antarhalaman berfungsi.
- Desain sesuai identitas SIMDES TIARO.
- Tampilan responsif pada desktop, tablet, dan mobile.
- Tidak ada error JavaScript.
- Tidak ada CSS duplikat yang tidak diperlukan.
- Tidak ada perubahan yang merusak halaman administrasi sebelumnya.
- Tidak ada perubahan yang tidak diperlukan pada halaman login.

---

# HASIL AKHIR

Implementasikan empat halaman berikut:

1. **Pengaturan**
2. **Profil Pengguna**
3. **Manajemen Pengguna (CRUD)**
4. **Manajemen Jabatan/Posisi (CRUD)**

Keempat halaman harus terintegrasi dengan sistem login dan dashboard yang sudah tersedia.

Prioritaskan:

- Kualitas UI/UX.
- Konsistensi desain.
- Fungsionalitas seluruh interaksi.
- Struktur kode yang rapi dan mudah dipelihara.
- Penggunaan ulang komponen yang sudah tersedia.
- Konsistensi data pengguna, jabatan, dan sesi.
- Kemudahan pengembangan ke backend Laravel pada tahap berikutnya.

**Jangan membuat halaman login baru, jangan mengubah halaman login yang sudah ada, dan jangan merusak halaman administrasi yang telah selesai dibuat.**

Selesaikan implementasi dan lakukan pengujian pada keempat halaman sebelum menyatakan pekerjaan selesai.