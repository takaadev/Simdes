<?php namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class AppHeading extends Component
{
    public array $breadcrumbs = [];

    public string $title;

    public string $description;

    public function __construct()
    {
        /*
        |--------------------------------------------------------------------------
        | Konfigurasi Halaman
        |--------------------------------------------------------------------------
        */

        $pages = [
            // Dashboard
            'dashboard' => [
                'title' => 'Dashboard Pemerintahan Desa',
                'description' => 'Ringkasan eksekutif penyelenggaraan administrasi umum dan kependudukan Kantor Desa Tiaro.',
            ],

            // =====================================================
            // ADMINISTRASI UMUM
            // =====================================================

            'buku-peraturan-desa' => [
                'title' => 'Buku Peraturan Desa',
                'description' => 'Kelola dan dokumentasikan seluruh peraturan yang ditetapkan di Desa Tiaro.',
            ],

            'buku-keputusan-kepala-desa' => [
                'title' => 'Buku Keputusan Kepala Desa',
                'description' => 'Kelola dan dokumentasikan seluruh keputusan yang ditetapkan oleh Kepala Desa Tiaro.',
            ],

            'buku-inventaris-dan-kekayaan-desa' => [
                'title' => 'Buku Inventaris dan Kekayaan Desa',
                'description' => 'Catat, kelola, dan pantau seluruh inventaris serta kekayaan milik Pemerintah Desa Tiaro.',
            ],

            'buku-aparat-pemerintah-desa' => [
                'title' => 'Buku Aparat Pemerintah Desa',
                'description' => 'Kelola data aparat pemerintah desa beserta informasi jabatan dan administrasi kepegawaiannya.',
            ],

            'buku-tanah-kas-desa' => [
                'title' => 'Buku Tanah Kas Desa',
                'description' => 'Kelola pencatatan dan dokumentasi tanah kas yang menjadi aset milik Desa Tiaro.',
            ],

            'buku-tanah-di-desa' => [
                'title' => 'Buku Tanah di Desa',
                'description' => 'Kelola data administrasi pertanahan dan pencatatan informasi tanah di wilayah Desa Tiaro.',
            ],

            'buku-agenda' => [
                'title' => 'Buku Agenda',
                'description' => 'Kelola pencatatan agenda kegiatan dan administrasi surat-menyurat Pemerintah Desa Tiaro.',
            ],

            'buku-ekspedisi' => [
                'title' => 'Buku Ekspedisi',
                'description' => 'Catat dan pantau pengiriman serta pendistribusian surat dan dokumen resmi pemerintah desa.',
            ],

            'buku-lembaran-desa-dan-berita-desa' => [
                'title' => 'Buku Lembaran Desa dan Berita Desa',
                'description' => 'Kelola dokumentasi lembaran desa dan berita desa sebagai bagian dari administrasi pemerintahan.',
            ],

            // =====================================================
            // ADMINISTRASI PENDUDUK
            // =====================================================

            'buku-induk-penduduk' => [
                'title' => 'Buku Induk Penduduk',
                'description' => 'Kelola dan perbarui data induk penduduk sebagai basis administrasi kependudukan Desa Tiaro.',
            ],

            'buku-mutasi-penduduk-desa' => [
                'title' => 'Buku Mutasi Penduduk Desa',
                'description' => 'Catat dan dokumentasikan perubahan data penduduk, termasuk kelahiran, kematian, kedatangan, dan kepindahan.',
            ],

            'buku-rekapitulasi-jumlah-penduduk' => [
                'title' => 'Buku Rekapitulasi Jumlah Penduduk',
                'description' => 'Sajikan rekapitulasi jumlah penduduk berdasarkan data administrasi kependudukan Desa Tiaro.',
            ],

            'buku-penduduk-sementara' => [
                'title' => 'Buku Penduduk Sementara',
                'description' => 'Kelola pencatatan dan dokumentasi penduduk sementara yang berada di wilayah Desa Tiaro.',
            ],

            'buku-ktp-dan-kk' => [
                'title' => 'Buku KTP dan Kartu Keluarga',
                'description' => 'Kelola pencatatan administrasi dokumen kependudukan berupa Kartu Tanda Penduduk dan Kartu Keluarga.',
            ],

            // =====================================================
            // ARSIP DIGITAL / SURAT
            // =====================================================

            'surat-masuk' => [
                'title' => 'Surat Masuk',
                'description' => 'Kelola pencatatan, penerimaan, dan dokumentasi surat yang masuk ke Pemerintah Desa Tiaro.',
            ],

            'surat-keluar' => [
                'title' => 'Surat Keluar',
                'description' => 'Kelola pembuatan, pencatatan, dan dokumentasi surat resmi yang diterbitkan Pemerintah Desa Tiaro.',
            ],

            'klasifikasi-surat' => [
                'title' => 'Klasifikasi Surat',
                'description' => 'Kelola pengelompokan dan kode klasifikasi surat untuk mendukung tertib administrasi persuratan desa.',
            ],

            'arsip-dokumen-surat' => [
                'title' => 'Arsip Dokumen Surat',
                'description' => 'Simpan, kelola, dan telusuri arsip digital dokumen surat Pemerintah Desa Tiaro.',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Label Breadcrumb
        |--------------------------------------------------------------------------
        */

        $labels = [
            'dashboard' => 'Dashboard',

            // Administrasi Umum
            'admumum' => 'Adm Umum',
            'buku-peraturan-desa' => 'Buku Peraturan Desa',
            'buku-keputusan-kepala-desa' => 'Buku Keputusan Kepala Desa',
            'buku-inventaris-dan-kekayaan-desa' => 'Buku Inventaris dan Kekayaan Desa',
            'buku-aparat-pemerintah-desa' => 'Buku Aparat Pemerintah Desa',
            'buku-tanah-kas-desa' => 'Buku Tanah Kas Desa',
            'buku-tanah-di-desa' => 'Buku Tanah Desa',
            'buku-agenda' => 'Buku Agenda',
            'buku-ekspedisi' => 'Buku Ekspedisi',
            'buku-lembaran-desa-dan-berita-desa' => 'Buku Lembaran Desa dan Berita Desa',

            // Administrasi Penduduk
            'admpenduduk' => 'Adm Penduduk',
            'buku-induk-penduduk' => 'Buku Induk Penduduk',
            'buku-mutasi-penduduk-desa' => 'Buku Mutasi Penduduk Desa',
            'buku-rekapitulasi-jumlah-penduduk' => 'Buku Rekapitulasi Jumlah Penduduk',
            'buku-penduduk-sementara' => 'Buku Penduduk Sementara',
            'buku-ktp-dan-kk' => 'Buku KTP & KK',

            // Arsip Surat
            'arsip-surat' => 'Arsip Surat',
            'surat-masuk' => 'Surat Masuk',
            'surat-keluar' => 'Surat Keluar',
            'klasifikasi-surat' => 'Klasifikasi Surat',
            'arsip-dokumen-surat' => 'Arsip Dokumen Surat',
        ];

        /*
        |--------------------------------------------------------------------------
        | Menentukan Halaman Aktif
        |--------------------------------------------------------------------------
        */

        $segments = request()->segments();

        $currentPage = $segments ? end($segments) : 'dashboard';

        /*
        |--------------------------------------------------------------------------
        | Mengambil Judul dan Deskripsi
        |--------------------------------------------------------------------------
        */

        $page = $pages[$currentPage] ?? [
            'title' => $labels[$currentPage] ?? Str::headline($currentPage),
            'description' => 'Kelola data ' . strtolower($labels[$currentPage] ?? Str::headline($currentPage)) . ' Desa Tiaro.',
        ];

        $this->title = $page['title'];

        $this->description = $page['description'];

        /*
        |--------------------------------------------------------------------------
        | Membuat Breadcrumb Otomatis
        |--------------------------------------------------------------------------
        */

        $this->breadcrumbs = collect($segments)
            ->map(
                fn($segment) => [
                    'label' => $labels[$segment] ?? Str::headline($segment),
                ],
            )
            ->values()
            ->all();
    }

    public function render(): View|Closure|string
    {
        return view('components.app-heading');
    }
}
