<x-filters :url="route('village-lands.index')" table="#villageLandTable">
    <x-slot:filter>
        {{-- Filter Status Hak Tanah --}}
        <x-filter-item label="Status Hak" id="filterHakSelect" name="status_hak" placeholder="Semua Status Hak"
            :options="[
                'GROUP_SUDAH' => 'Sudah Bersertifikat',
                'GROUP_BELUM' => 'Belum Bersertifikat',
                'hm' => 'HM',
                'hgb' => 'HGB',
                'hp' => 'HP',
                'hgu' => 'HGU',
                'hpl' => 'HPL',
                'ma' => 'MA',
                'vi' => 'VI',
                'tn' => 'TN',
            ]" />

        {{-- Filter Penggunaan Tanah --}}
        <x-filter-item label="Penggunaan Tanah" id="filterPenggunaanSelect" name="penggunaan"
            placeholder="Semua Penggunaan Tanah" :options="[
                'GROUP_NON_PERTANIAN' => 'Kelompok Non Pertanian',
                'GROUP_PERTANIAN' => 'Kelompok Pertanian',
                'GROUP_LAINNYA' => 'Tanah Kosong & Lain-lain',
                'perumahan' => 'Perumahan',
                'perdaganganJasa' => 'Perdagangan dan Jasa',
                'perkantoran' => 'Perkantoran',
                'industri' => 'Industri',
                'fasilitasUmum' => 'Fasilitas Umum',
                'sawah' => 'Sawah',
                'tegalan' => 'Tegalan',
                'perkebunan' => 'Perkebunan',
                'peternakanPerikanan' => 'Peternakan/Perikanan',
                'hutanBelukar' => 'Hutan Belukar',
                'hutanLebatLindung' => 'Hutan Lebat/Lindung',
                'tanahKosong' => 'Tanah Kosong',
                'lainLain' => 'Lain-lain',
            ]" />

        {{-- Filter Status Data --}}
        <x-filter-item label="Status Data" id="filterDeletedSelect" name="deleted" :options="[
            'active' => 'Aktif',
            'trashed' => 'Dihapus',
        ]" />
    </x-slot:filter>
    <x-slot:actions>
        <x-action-print id="btnCetakTanah"/>
        @include('pages.villageland.create')
    </x-slot:actions>
</x-filters>
