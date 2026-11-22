<?php

namespace App\View\Components;

use App\Models\AppSetting;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class AppHeading extends Component
{
    public array $breadcrumbs = [];
    public string $title;
    public string $description;
    public ?AppSetting $appSetting;

    public function __construct(?AppSetting $appSetting = null)
    {
        $this->appSetting = $appSetting ?? AppSetting::first();

        $namaDesa = $this->resolveNamaDesa();
        $pages    = $this->getPages($namaDesa);
        $labels   = $this->getLabels();
        $segments = request()->segments();

        $routeName = request()->route()?->getName();
        $routeKey  = $routeName ? Str::before($routeName, '.') : null;

        $currentPage = ($routeKey && isset($pages[$routeKey]))
            ? $routeKey
            : ($segments ? end($segments) : 'dashboard');

        $fallbackTitle = $labels[$currentPage] ?? Str::headline($currentPage);
        $lowerTitle    = strtolower($fallbackTitle);

        $suffixDesa = Str::endsWith($lowerTitle, 'desa')
            ? preg_replace('/^desa\s+/i', '', $namaDesa)
            : $namaDesa;

        $page = $pages[$currentPage] ?? [
            'title'       => $fallbackTitle,
            'description' => "Kelola data {$lowerTitle} {$suffixDesa}.",
        ];

        $this->title       = $page['title'] ?? $fallbackTitle;
        $this->description = $page['description'] ?? '';

        $this->breadcrumbs = collect($segments)
            ->map(fn(string $segment) => [
                'label' => $labels[$segment] ?? Str::headline($segment),
            ])
            ->values()
            ->all();
    }

    /**
     * Memastikan awalan "Desa" tidak dobel (misal "Desa Desa Tiaro").
     */
    private function resolveNamaDesa(): string
    {
        $nama = trim((string) ($this->appSetting?->nama_desa ?? ''));

        if ($nama === '') {
            return 'Desa';
        }

        return Str::startsWith(strtolower($nama), 'desa ') ? $nama : "Desa {$nama}";
    }

    /**
     * Konfigurasi judul dan deskripsi per halaman.
     */
    private function getPages(string $namaDesa): array
    {
        $appName = config('app.name', 'SIMDES');

        return [
            // Dashboard
            'dashboard' => [
                'title'       => 'Dashboard Pemerintahan Desa',
                'description' => "Ringkasan administrasi umum dan kependudukan Kantor {$namaDesa}.",
            ],

            // Administrasi Umum
            'village-regulations' => [
                'title'       => 'Buku Peraturan Desa',
                'description' => "Kelola dan dokumentasikan seluruh peraturan yang ditetapkan di {$namaDesa}.",
            ],
            'village-decrees' => [
                'title'       => 'Buku Keputusan Kepala Desa',
                'description' => "Kelola dan dokumentasikan seluruh keputusan yang ditetapkan oleh Kepala {$namaDesa}.",
            ],
            'village-assets' => [
                'title'       => 'Buku Inventaris dan Kekayaan Desa',
                'description' => "Catat, kelola, dan pantau seluruh inventaris serta kekayaan milik Pemerintah {$namaDesa}.",
            ],
            'village-officials' => [
                'title'       => 'Buku Aparat Pemerintah Desa',
                'description' => 'Kelola data aparat pemerintah desa beserta informasi jabatan dan administrasi kepegawaiannya.',
            ],
            'village-treasury-lands' => [
                'title'       => 'Buku Tanah Kas Desa',
                'description' => "Kelola pencatatan dan dokumentasi tanah kas yang menjadi aset milik {$namaDesa}.",
            ],
            'village-lands' => [
                'title'       => 'Buku Tanah di Desa',
                'description' => "Kelola data administrasi pertanahan dan pencatatan informasi tanah di wilayah {$namaDesa}.",
            ],
            'village-publications' => [
                'title'       => 'Buku Lembaran Desa dan Berita Desa',
                'description' => 'Kelola dokumentasi lembaran desa dan berita desa sebagai bagian dari administrasi pemerintahan.',
            ],

            // Administrasi Penduduk
            'residents' => [
                'title'       => 'Buku Induk Penduduk',
                'description' => "Kelola dan perbarui data induk penduduk sebagai basis administrasi kependudukan {$namaDesa}.",
            ],
            'mutations' => [
                'title'       => 'Buku Mutasi Penduduk Desa',
                'description' => 'Catat dan dokumentasikan perubahan data penduduk, termasuk kelahiran, kematian, kedatangan, dan kepindahan.',
            ],
            'recapts' => [
                'title'       => 'Buku Rekapitulasi Jumlah Penduduk',
                'description' => "Sajikan rekapitulasi jumlah penduduk berdasarkan data administrasi kependudukan {$namaDesa}.",
            ],
            'temporaryresidents' => [
                'title'       => 'Buku Penduduk Sementara',
                'description' => "Kelola pencatatan dan dokumentasi penduduk sementara yang berada di wilayah {$namaDesa}.",
            ],
            'familycards' => [
                'title'       => 'Buku KTP dan Kartu Keluarga',
                'description' => 'Kelola pencatatan administrasi dokumen kependudukan berupa Kartu Tanda Penduduk dan Kartu Keluarga.',
            ],

            // Arsip Digital
            'agendas' => [
                'title'       => 'Buku Agenda',
                'description' => "Kelola pencatatan agenda kegiatan dan administrasi surat-menyurat Pemerintah {$namaDesa}.",
            ],
            'expeditions' => [
                'title'       => 'Buku Ekspedisi',
                'description' => 'Catat dan pantau pengiriman serta pendistribusian surat dan dokumen resmi pemerintah desa.',
            ],

            // Pengaturan Sistem
            'pengaturan' => [
                'title'       => 'Pengaturan Aplikasi',
                'description' => "Kelola konfigurasi aplikasi {$appName} dan informasi profil Pemerintahan {$namaDesa}.",
            ],
            'pengguna' => [
                'title'       => 'Manajemen Pengguna',
                'description' => "Kelola akun pengguna, informasi akses, dan hak akses untuk menggunakan aplikasi {$appName}.",
            ],
        ];
    }

    /**
     * Label breadcrumb berdasarkan segmen URL.
     */
    private function getLabels(): array
    {
        return [
            'dashboard'              => 'Dashboard',

            // Administrasi Umum
            'admumum'                => 'Adm Umum',
            'village-regulations'    => 'Peraturan di Desa',
            'village-decrees'        => 'Keputusan Kepala Desa',
            'village-assets'         => 'Inventaris dan Kekayaan Desa',
            'village-officials'      => 'Aparat Pemerintah Desa',
            'village-treasury-lands' => 'Tanah Kas Desa',
            'village-lands'          => 'Tanah Desa',
            'village-publications'   => 'Lembaran Desa dan Berita Desa',

            // Administrasi Penduduk
            'admpenduduk'            => 'Adm Penduduk',
            'residents'              => 'Buku Induk Penduduk',
            'mutations'              => 'Buku Mutasi Penduduk',
            'recapts'                => 'Buku Rekapitulasi Penduduk',
            'temporaryresidents'     => 'Buku Penduduk Sementara',
            'familycards'            => 'Buku KTP & KK',

            // Arsip Digital
            'agendas'                => 'Buku Agenda',
            'expeditions'            => 'Buku Ekspedisi',

            // Pengaturan Sistem
            'pengaturan'             => 'Pengaturan',
            'pengguna'               => 'Pengguna',
        ];
    }

    public function render(): View|Closure|string
    {
        return view('components.app-heading');
    }
}