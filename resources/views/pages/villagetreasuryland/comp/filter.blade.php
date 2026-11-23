<x-filters :url="route('village-treasury-lands.index')" table="#villageTreasuryLandTable">
    <x-slot:filter>
        <x-filter-item label="Tahun" id="filterYearSelect" name="year" :options="\App\Enums\ListYear::options()" />
        <x-filter-item label="Kelas Tanah" id="filterLandClassListSelect" name="kelas" :options="\App\Enums\LandClassList::options()" />
    </x-slot:filter>

    <x-slot:actions>
        <x-action-print id="btnCetakTanahKas"/>
        @include('pages.villagetreasuryland.create')
    </x-slot:actions>
</x-filters>
