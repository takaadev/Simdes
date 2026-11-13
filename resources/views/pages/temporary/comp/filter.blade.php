<x-filters :url="route('temporaryresidents.index')" table="#temporaryResidentTable">
    <x-slot:filter>
        {{-- Urutkan Nama --}}
        <x-filter-item label="Urutkan Nama" id="filterSortName" name="sort_name" :options="[
            'asc' => 'A - Z',
            'desc' => 'Z - A',
        ]" placeholder="Urutan Nama" />

        {{-- Jenis Kelamin --}}
        <x-filter-item label="Jenis Kelamin" id="filterGenderSelect" name="jenis_kelamin" :options="\App\Enums\GenderList::options()" />

        {{-- Kewarganegaraan --}}
        <x-filter-item label="Kewarganegaraan" id="filterCitizenshipSelect" name="kewarganegaraan" :options="\App\Enums\CitizenshipList::options()" />

        {{-- Pekerjaan --}}
        <x-filter-item label="Pekerjaan" id="filterOccupationSelect" name="pekerjaan" :options="\App\Enums\OccupationList::options()" />

        {{-- Status Tinggal --}}
        <x-filter-item label="Status Tinggal" id="filterStayStatusSelect" name="status_tinggal" :options="[
            'staying' => 'Masih Tinggal',
            'departed' => 'Sudah Pergi',
        ]" />

        {{-- Tahun Kedatangan --}}
        <x-filter-item label="Tahun Datang" id="filterYearSelect" name="year" :options="\App\Enums\ListYear::options()" />

        {{-- Status Data --}}
        <x-filter-item label="Status Data" id="filterDeletedSelect" name="deleted" :options="[
            'active' => 'Aktif',
            'trashed' => 'Dihapus',
        ]" />
    </x-slot:filter>

    <x-slot:actions>
        <button type="button" id="btnCetakPendudukSementara" class="btn-simdes-outline">
            <i class="fa-solid fa-print" aria-hidden="true"></i>
            <span>Cetak</span>
        </button>
        <x-action-import-excel href="{{ route('temporaryresidents.importexcel') }}" />
        @include('pages.temporary.create')
    </x-slot:actions>
</x-filters>