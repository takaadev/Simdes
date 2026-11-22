<x-filters :url="route('village-officials.index')" table="#villageOfficialTable">
    <x-slot:filter>
        {{-- Status Aparat --}}
        <x-filter-item
            label="Status Aparat"
            id="filterStatusSelect"
            name="status"
            :options="\App\Enums\VillageOfficialStatusList::options()"
        />

        {{-- Jenis Kelamin --}}
        <x-filter-item
            label="Jenis Kelamin"
            id="filterGenderSelect"
            name="jenis_kelamin"
            :options="\App\Enums\GenderList::options()"
        />
        <x-filter-item label="Tahun Pengangkatan" id="filterYearSelect" name="year" :options="\App\Enums\ListYear::options()" />
        {{-- Status Data --}}
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
        <x-action-print id="btnCetakAparat"/>
        @include('pages.villageofficial.dismissal')
        @include('pages.villageofficial.create')
    </x-slot:actions>
</x-filters>
