<x-filters :url="route('village-treasury-lands.index')" table="#villageTreasuryLandTable">
    <x-slot:filter>
        <x-filter-item label="Tahun" id="filterYearSelect" name="year" :options="\App\Enums\ListYear::options()" />
        <x-filter-item label="Kelas Tanah" id="filterLandClassListSelect" name="kelas" :options="\App\Enums\LandClassList::options()" />
        <x-filter-item
            label="Status Data"
            id="filterDeletedSelect"
            name="deleted"
            :options="[
                'active' => 'Aktif',
                'trashed' => 'Dihapus',
            ]"
        />
    </x-slot:filter>

    <x-slot:actions>
        <button type="button" id="btnCetakPeraturan" class="btn-simdes-outline">
            <i class="fa-solid fa-print" aria-hidden="true"></i>
            <span>Cetak</span>
        </button>
        @include('pages.villagetreasuryland.create')
    </x-slot:actions>
</x-filters>
