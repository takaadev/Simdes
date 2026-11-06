<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Menu extends Component
{
    public array $menus;

    public function __construct()
    {
        $this->menus = $this->getMenus();
    }

    protected function getMenus(): array
    {
        return [
            [
                'type' => 'label',
                'label' => 'DASHBOARD',
            ],
            [
                'type' => 'link',
                'label' => 'Dashboard',
                'icon' => 'table-columns',
                'route' => route('dashboard'),
            ],

            [
                'type' => 'label',
                'label' => 'MODUL ADMINISTRASI',
            ],

            // Administrasi Umum
            [
                'type' => 'group',
                'group' => 'umum',
                'label' => 'Adm Umum',
                'icon' => 'book-bookmark',
                'tooltip' => 'Administrasi Umum (9 Buku)',
                'expanded' => request()->is('admumum', 'admumum/*'),
                'items' => [
                    ['no' => '1', 'slug' => 'buku-peraturan-di-desa', 'route' => route('village-regulations.index'), 'label' => 'Peraturan di Desa', 'ready' => true],
                    ['no' => '2', 'slug' => 'buku-keputusan-kepala-desa', 'route' => route('village-decrees.index'), 'label' => 'Keputusan Kepala Desa', 'ready' => true],
                    ['no' => '3', 'slug' => 'buku-inventaris-dan-kekayaan-desa', 'route' => '#', 'label' => 'Inventaris dan Kekayaan Desa', 'ready' => true],
                    ['no' => '4', 'slug' => 'buku-aparat-pemerintah-desa', 'route' => '#', 'label' => 'Aparat Pemerintah Desa', 'ready' => true],
                    ['no' => '5', 'slug' => 'buku-tanah-kas-desa', 'route' => '#', 'label' => 'Tanah Kas Desa', 'ready' => true],
                    ['no' => '6', 'slug' => 'buku-tanah-di-desa', 'route' => '#', 'label' => 'Tanah Desa', 'ready' => true],
                    ['no' => '8', 'slug' => 'buku-ekspedisi', 'route' => '#', 'label' => 'Ekspedisi', 'ready' => true],
                    ['no' => '9', 'slug' => 'buku-lembaran-desa-dan-berita-desa', 'route' => '#', 'label' => 'Lembaran Desa dan Berita Desa', 'ready' => true],
                ],
            ],

            // Administrasi Penduduk
            [
                'type' => 'group',
                'group' => 'penduduk',
                'label' => 'Adm Penduduk',
                'icon' => 'users',
                'tooltip' => 'Administrasi Penduduk (5 Buku)',
                'expanded' => '',
                'items' => [
                    ['no' => '1', 'slug' => 'buku-induk-penduduk', 'route' => '#', 'label' => 'Buku IP', 'ready' => true],
                    ['no' => '2', 'slug' => 'buku-mutasi-penduduk-desa', 'route' => '#', 'label' => 'Buku MPD', 'ready' => true],
                    ['no' => '3', 'slug' => 'buku-rekapitulasi-jumlah-penduduk', 'route' => '#', 'label' => 'Buku RJP', 'ready' => true],
                    ['no' => '4', 'slug' => 'buku-penduduk-sementara', 'route' => '#', 'label' => 'Buku PS', 'ready' => true],
                    ['no' => '5', 'slug' => 'buku-ktp-dan-kk', 'route' => '#', 'label' => 'Buku KTP & KK', 'ready' => true],
                ],
            ],

            // Arsip Digital
            [
                'type' => 'group',
                'group' => 'arsip-surat',
                'label' => 'Arsip Digital',
                'icon' => 'envelope-open-text',
                'tooltip' => 'Arsip Surat',
                'expanded' => '',
                'items' => [
                    ['no' => '1', 'slug' => 'buku-agenda', 'route' => '#', 'label' => 'Buku Agenda', 'ready' => true, 'enabled' => true],
                    ['no' => '2', 'slug' => 'surat-masuk', 'route' => '#', 'label' => 'Surat Masuk', 'ready' => true, 'enabled' => true],
                    ['no' => '3', 'slug' => 'surat-keluar', 'route' => '#', 'label' => 'Surat Keluar', 'ready' => true, 'enabled' => true],
                    ['no' => '4', 'slug' => 'klasifikasi-surat', 'route' => '#', 'label' => 'Klasifikasi Surat', 'ready' => true, 'enabled' => true],
                    ['no' => '5', 'slug' => 'arsip-dokumen-surat', 'route' => '#', 'label' => 'Arsip Dokumen Surat', 'ready' => true, 'enabled' => true],
                ],
            ],
        ];
    }

    public function render(): View|Closure|string
    {
        return view('components.menu');
    }
}
