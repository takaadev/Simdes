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
                'route' => 'dashboard',
            ],
            [
                'type' => 'link',
                'label' => 'Master Data',
                'icon' => 'database',
                'route' => 'village-positions.index',
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
                'items' => [
                    ['no' => '1', 'route' => 'village-regulations.index', 'label' => 'Peraturan di Desa', 'ready' => true],
                    ['no' => '2', 'route' => 'village-decrees.index', 'label' => 'Keputusan Kepala Desa', 'ready' => true],
                    ['no' => '3', 'route' => 'village-assets.index', 'label' => 'Inventaris dan Kekayaan Desa', 'ready' => true],
                    ['no' => '4', 'route' => 'village-officials.index', 'label' => 'Aparat Pemerintah Desa', 'ready' => true],
                    ['no' => '5', 'route' => 'village-treasury-lands.index', 'label' => 'Tanah Kas Desa', 'ready' => true],
                    ['no' => '6', 'route' => 'village-lands.index', 'label' => 'Tanah Desa', 'ready' => true],
                    ['no' => '9', 'route' => 'village-publications.index', 'label' => 'Lembaran Desa dan Berita Desa', 'ready' => true],
                ],
            ],

            // Administrasi Penduduk
            [
                'type' => 'group',
                'group' => 'penduduk',
                'label' => 'Adm Penduduk',
                'icon' => 'users',
                'tooltip' => 'Administrasi Penduduk (5 Buku)',
                'items' => [
                    ['no' => '1', 'route' => 'residents.index', 'label' => 'Buku Induk Penduduk', 'ready' => true],
                    ['no' => '2', 'route' => 'mutations.index', 'label' => 'Buku Mutasi Penduduk', 'ready' => true],
                    ['no' => '3', 'route' => 'recapts.index', 'label' => 'Buku Rekapitulasi Penduduk', 'ready' => true],
                    ['no' => '4', 'route' => 'temporaryresidents.index', 'label' => 'Buku Penduduk Sementara', 'ready' => true],
                    ['no' => '5', 'route' => 'familycards.index', 'label' => 'Buku KTP & KK', 'ready' => true],
                ],
            ],

            // Arsip Digital
            [
                'type' => 'group',
                'group' => 'arsip-surat',
                'label' => 'Arsip Digital',
                'icon' => 'envelope-open-text',
                'tooltip' => 'Arsip Surat',
                'items' => [
                    ['no' => '1', 'route' => 'agendas.index', 'label' => 'Buku Agenda', 'ready' => true],
                    ['no' => '2', 'route' => 'expeditions.index', 'label' => 'Buku Ekspedisi', 'ready' => true],
                ],
            ],

            [
                'type' => 'label',
                'label' => 'Pengaturan Sistem',
            ],
            [
                'type' => 'link',
                'label' => 'Pengaturan',
                'icon' => 'gear',
                'route' => 'settings.index',
            ],
            [
                'type' => 'link',
                'label' => 'Pengguna',
                'icon' => 'user',
                'route' => 'users.index',
            ],
        ];
    }
    public function render(): View|Closure|string
    {
        return view('components.menu');
    }
}
