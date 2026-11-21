<x-filters :url="route('residents.index')" table="#residentTable">
    <x-slot:filter>
        {{-- Urutkan Nama --}}
        <x-filter-item
            label="Urutkan Nama"
            id="filterSortName"
            name="sort_name"
            placeholder="Urutan Nama"
            :options="[
                'asc' => 'A - Z',
                'desc' => 'Z - A',
            ]"
        />

        {{-- Jenis Kelamin --}}
        <x-filter-item
            label="Jenis Kelamin"
            id="filterGenderSelect"
            name="jenis_kelamin"
            placeholder="Semua Jenis Kelamin"
            :options="\App\Enums\GenderList::options()"
        />

        {{-- Kewarganegaraan --}}
        <x-filter-item
            label="Kewarganegaraan"
            id="filterCitizenshipSelect"
            name="kewarganegaraan"
            placeholder="Semua Kewarganegaraan"
            :options="\App\Enums\CitizenshipList::options()"
        />

        {{-- Status Perkawinan --}}
        <x-filter-item
            label="Status Perkawinan"
            id="filterMaritalStatusSelect"
            name="status_perkawinan"
            placeholder="Semua Status Perkawinan"
            :options="\App\Enums\MaritalStatusList::options()"
        />

        {{-- Pendidikan --}}
        <x-filter-item
            label="Pendidikan"
            id="filterEducationSelect"
            name="pendidikan"
            placeholder="Semua Pendidikan"
            :options="\App\Enums\EducationList::options()"
        />

        {{-- Agama --}}
        <x-filter-item
            label="Agama"
            id="filterReligionSelect"
            name="agama"
            placeholder="Semua Agama"
            :options="\App\Enums\ReligionList::options()"
        />

        {{-- Pekerjaan --}}
        <x-filter-item
            label="Pekerjaan"
            id="filterOccupationSelect"
            name="pekerjaan"
            placeholder="Semua Pekerjaan"
            :options="\App\Enums\OccupationList::options()"
        />

        {{-- RT --}}
        <x-filter-item
            label="RT"
            id="filterRtSelect"
            name="rt"
            placeholder="Semua RT"
            :options="\App\Enums\RtList::options()"
        />

        {{-- Tahun Kelahiran --}}
        <x-filter-item
            label="Tahun Kelahiran"
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
        <x-action-print id="btnCetakPenduduk" />
        <x-action-import-excel href="{{ route('residents.importexcel') }}" />
        @include('pages.resident.create')
    </x-slot:actions>
</x-filters>