<x-filters :url="route('mutations.index')" table="#residentMutationTable">
    <x-slot:filter>
        {{-- Urutkan Nama --}}
        <x-filter-item label="Urutkan Nama" id="filterSortName" name="sort_name" :options="[
            'asc' => 'A - Z',
            'desc' => 'Z - A',
        ]"
            placeholder="Urutan Nama" />

        {{-- Jenis Kelamin --}}
        <x-filter-item label="Jenis Kelamin" id="filterGenderSelect" name="jenis_kelamin" :options="\App\Enums\GenderList::options()" />

        {{-- Kewarganegaraan --}}
        <x-filter-item label="Kewarganegaraan" id="filterCitizenshipSelect" name="kewarganegaraan" :options="\App\Enums\CitizenshipList::options()" />

        {{-- Tipe Mutasi --}}
        <x-filter-item label="Tipe Mutasi" id="filterMutationTypeSelect" name="tipe_mutasi" :options="\App\Enums\MutationTypeList::options()" />

        {{-- Kategori Mutasi --}}
        <x-filter-item label="Kategori Mutasi" id="filterMutationCategorySelect" name="kategori_mutasi"
            :options="\App\Enums\MutationCategoryList::options()" />

        {{-- Status Data --}}
        <x-filter-item label="Status Data" id="filterDeletedSelect" name="deleted" :options="[
            'active' => 'Aktif',
            'trashed' => 'Dihapus',
        ]" />
    </x-slot:filter>

    <x-slot:actions>
        <button type="button" id="btnCetakMutasi" class="btn-simdes-outline">
            <i class="fa-solid fa-print" aria-hidden="true"></i>
            <span>Cetak</span>
        </button>

        {{-- <x-action-import-excel href="{{ route('mutations.importexcel') }}" /> --}}

        @include('pages.mutation.create')
    </x-slot:actions>
</x-filters>
