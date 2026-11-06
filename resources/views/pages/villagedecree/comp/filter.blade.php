<x-filters :url="route('village-decrees.index')" table="#villageDecreesTable">
    <x-slot:filter>
        <x-filter-item label="Status" id="filterStatusSelect" name="status" :options="\App\Enums\DecreeStatusList::options()" />
        <x-filter-item label="Tahun" id="filterYearSelect" name="year" :options="\App\Enums\ListYear::options()" />
    </x-slot:filter>

    <x-slot:actions>
        <button type="button" id="btnCetakPeraturan" class="btn-simdes-outline">
            <i class="fa-solid fa-print" aria-hidden="true"></i>
            <span>Cetak</span>
        </button>
        @include('pages.villagedecree.create')
    </x-slot:actions>
</x-filters>
