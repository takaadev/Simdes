<x-filters :url="route('users.index')" table="#userTable">
    <x-slot:filter>
        {{-- Filter Role Type --}}
        <x-filter-item
            label="Role"
            id="filterRoleSelect"
            name="role"
            placeholder="Semua Role"
            :options="\App\Enums\RoleAccessType::options()"
        />

        {{-- Filter Status Login --}}
        <x-filter-item
            label="Status Login"
            id="filterLoginStatusSelect"
            name="login_status"
            placeholder="Semua Status Login"
            :options="[
                'online' => 'Online',
                'offline' => 'Offline',
                'never' => 'Belum Pernah Login',
            ]"
        />

        {{-- Filter Status Data (SoftDeletes) --}}
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
        @include('pages.user.create')
    </x-slot:actions>
</x-filters>