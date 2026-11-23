<x-filters :url="route('village-decrees.index')" table="#villageDecreesTable">
    <x-slot:filter>
        <x-filter-item label="Status" id="filterStatusSelect" name="status" :options="\App\Enums\DecreeStatusList::options()" />
        <x-filter-item label="Tahun" id="filterYearSelect" name="year" :options="\App\Enums\ListYear::options()" />
    </x-slot:filter>

    <x-slot:actions>
        <x-action-print id="btnCetakKeputusan"/>
        @include('pages.villagedecree.create')
    </x-slot:actions>
</x-filters>
