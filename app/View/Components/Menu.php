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
                    ['no' => '6', 'route' => '#', 'label' => 'Tanah Desa', 'ready' => true],
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
                    ['no' => '1', 'route' => '#', 'label' => 'Buku IP', 'ready' => true],
                    ['no' => '2', 'route' => '#', 'label' => 'Buku MPD', 'ready' => true],
                    ['no' => '3', 'route' => '#', 'label' => 'Buku RJP', 'ready' => true],
                    ['no' => '4', 'route' => '#', 'label' => 'Buku PS', 'ready' => true],
                    ['no' => '5', 'route' => '#', 'label' => 'Buku KTP & KK', 'ready' => true],
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
                    ['no' => '4', 'route' => '#', 'label' => 'Buku Ekspedisi', 'ready' => true],
                    ['no' => '5', 'route' => '#', 'label' => 'Arsip Dokumen Surat', 'ready' => true],
                ],
            ],
        ];
    }
    public function render(): View|Closure|string
    {
        return view('components.menu');
    }
}
