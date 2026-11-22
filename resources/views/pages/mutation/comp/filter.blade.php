<x-filters :url="route('mutations.index')" table="#residentMutationTable">
    <x-slot:filter>
        {{-- Urutkan Nama --}}
        <x-filter-item label="Urutkan Nama" id="filterSortName" name="sort_name" :options="[
            'asc' => 'A - Z',
            'desc' => 'Z - A',
        ]"
            placeholder="Urutan Nama" />

        {{-- Jenis Kelamin --}}
        <x-filter-item label="Jenis Kelamin" id="filterGenderSelect" name="jenis_kelamin" :options="\App\Enums\GenderList::options()"
            placeholder="Semua Jenis Kelamin" />

        {{-- Kewarganegaraan --}}
        <x-filter-item label="Kewarganegaraan" id="filterCitizenshipSelect" name="kewarganegaraan" :options="\App\Enums\CitizenshipList::options()"
            placeholder="Semua Kewarganegaraan" />

        {{-- Tipe Mutasi --}}
        <x-filter-item label="Tipe Mutasi" id="filterMutationTypeSelect" name="tipe_mutasi" :options="\App\Enums\MutationTypeList::options()"
            placeholder="Semua Tipe Mutasi" />

        {{-- Kategori Mutasi --}}
        <x-filter-item label="Kategori Mutasi" id="filterMutationCategorySelect" name="kategori_mutasi"
            :options="\App\Enums\MutationCategoryList::options()" placeholder="Semua Kategori" />

        {{-- Status Data --}}
        <x-filter-item label="Status Data" id="filterDeletedSelect" name="deleted" :options="[
            'active' => 'Aktif',
            'trashed' => 'Dihapus',
        ]"
            placeholder="Status Data" />
    </x-slot:filter>

    <x-slot:actions>
        <x-action-print id="btnCetakMutasi"/>
        @include('pages.mutation.outgoing')
        @include('pages.mutation.create')
    </x-slot:actions>
</x-filters>