<x-filters :url="route('residents.index')" table="#residentTable">
    <x-slot:filter>
        {{-- Jenis Kelamin --}}
        <x-filter-item
            label="Jenis Kelamin"
            id="filterGenderSelect"
            name="jenis_kelamin"
            :options="\App\Enums\GenderList::options()"
        />

        {{-- Kewarganegaraan --}}
        <x-filter-item
            label="Kewarganegaraan"
            id="filterCitizenshipSelect"
            name="kewarganegaraan"
            :options="\App\Enums\CitizenshipList::options()"
        />

        {{-- Status Perkawinan --}}
        <x-filter-item
            label="Status Perkawinan"
            id="filterMaritalStatusSelect"
            name="status_perkawinan"
            :options="\App\Enums\MaritalStatusList::options()"
        />

        {{-- Pendidikan --}}
        <x-filter-item
            label="Pendidikan"
            id="filterEducationSelect"
            name="pendidikan"
            :options="\App\Enums\EducationList::options()"
        />

        {{-- Agama --}}
        <x-filter-item
            label="Agama"
            id="filterReligionSelect"
            name="agama"
            :options="\App\Enums\ReligionList::options()"
        />

        {{-- Pekerjaan --}}
        <x-filter-item
            label="Pekerjaan"
            id="filterOccupationSelect"
            name="pekerjaan"
            :options="\App\Enums\OccupationList::options()"
        />

        {{-- Status dalam Keluarga --}}
        {{-- <x-filter-item
            label="Status dalam Keluarga"
            id="filterFamilyStatusSelect"
            name="status_dalam_keluarga"
            :options="\App\Enums\FamilyStatusList::options()"
        /> --}}

        {{-- Tahun Kelahiran --}}
        <x-filter-item
            label="Tahun Kelahiran"
            id="filterYearSelect"
            name="year"
            :options="\App\Enums\ListYear::options()"
        />

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
        <button type="button" id="btnCetakPenduduk" class="btn-simdes-outline">
            <i class="fa-solid fa-print" aria-hidden="true"></i>
            <span>Cetak</span>
        </button>

        @include('pages.resident.create')
    </x-slot:actions>
</x-filters>