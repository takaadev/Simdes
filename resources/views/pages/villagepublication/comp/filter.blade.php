<x-filters :url="route('village-publications.index')" table="#villagePublicationTable">
    <x-slot:filter>
        <x-filter-item label="Jenis Peraturan" id="filterJenisSelect" name="jenis_peraturan" :options="\App\Enums\RegulationTypeList::options()" />
        <x-filter-item label="Status" id="filterStatusSelect" name="status" :options="\App\Enums\RegulationStatusList::options()" />
        <x-filter-item label="Tahun" id="filterYearSelect" name="year" :options="\App\Enums\ListYear::options()" />
    </x-slot:filter>

    <x-slot:actions>
        <x-action-print id="btnCetakPublikasi"/>
        @include('pages.villagepublication.create')
    </x-slot:actions>
</x-filters>
