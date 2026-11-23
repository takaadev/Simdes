<x-filters :url="route('village-treasury-lands.index')" table="#villageTreasuryLandTable">
    <x-slot:filter>
        <x-filter-item label="Tahun" id="filterYearSelect" name="year" :options="\App\Enums\ListYear::options()" />
    </x-slot:filter>

    <x-slot:actions>
        <x-action-print id="btnCetakAsset"/>
        @include('pages.villageasset.create')
    </x-slot:actions>
</x-filters>
