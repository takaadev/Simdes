<x-filters :url="route('familycards.index')" table="#familyCardTable">
    <x-slot:filter>
        {{-- Urutkan Data --}}
        <x-filter-item
            label="Urutkan"
            id="filterSortDate"
            name="sort_date"
            placeholder="Urutan Data"
            :options="[
                'desc' => 'Data Terbaru',
                'asc' => 'Data Terlama',
            ]"
        />

        {{-- Tahun --}}
        <x-filter-item
            label="Tahun"
            id="filterYearSelect"
            name="year"
            placeholder="Semua Tahun"
            :options="\App\Enums\ListYear::options()"
        />

        {{-- Status Data --}}
        <x-filter-item
            label="Status Data"
            id="filterDeletedSelect"
            name="deleted"
            placeholder="Status Data"
            :options="[
                'active' => 'Aktif',
                'trashed' => 'Dihapus',
            ]"
        />
    </x-slot:filter>

    <x-slot:actions>
        <x-action-print id="btnCetakFamilyCard" />
        @include('pages.familycard.family-card-mutation')
        @include('pages.familycard.create')
    </x-slot:actions>
</x-filters>