<x-filters :url="route('village-positions.index')" table="#villagePositionTable">
    <x-slot:filter>
        {{-- Status Jabatan --}}
        <x-filter-item
            label="Status Jabatan"
            id="filterStatusSelect"
            name="status"
            :options="[
                'active' => 'Aktif',
                'inactive' => 'Tidak Aktif',
            ]"
        />
    </x-slot:filter>

    <x-slot:actions>
        @include('pages.datamaster.villageposition.create')
    </x-slot:actions>
</x-filters>