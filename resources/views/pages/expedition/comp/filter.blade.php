<x-filters :url="route('expeditions.index')" table="#expeditionEntriesTable">
    <x-slot:filter>
        <x-filter-item label="Tahun" id="filterYearSelect" name="year" :options="\App\Enums\ListYear::options()" />
    </x-slot:filter>

    <x-slot:actions>
        <x-action-print id="btnCetakEkspedisi"/>
        @include('pages.expedition.create')
    </x-slot:actions>
</x-filters>
