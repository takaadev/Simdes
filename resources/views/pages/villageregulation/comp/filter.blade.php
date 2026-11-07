<x-filters :url="route('village-regulations.index')" table="#villageRegulationsTable">
    <x-slot:filter>
        <x-filter-item label="Jenis Peraturan" id="filterJenisSelect" name="jenis_peraturan" :options="\App\Enums\RegulationTypeList::options()" />
        <x-filter-item label="Status" id="filterStatusSelect" name="status" :options="\App\Enums\RegulationStatusList::options()" />
        <x-filter-item label="Tahun" id="filterYearSelect" name="year" :options="\App\Enums\ListYear::options()" />
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
        @include('pages.villageregulation.create')
    </x-slot:actions>
</x-filters>
