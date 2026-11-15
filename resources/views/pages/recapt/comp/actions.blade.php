<x-actions-table>
    @if ($row->deleted_at === null)
        @include('pages.villagetreasuryland.show', ['data' => $row])
        @include('pages.villagetreasuryland.edit', ['data' => $row])
    @else
        <x-action-restore :id="$row->id" href="{{ route('village-treasury-lands.restore', $row->id) }}" />
    @endif
    <x-action-delete :id="$row->id" :href="route('village-treasury-lands.destroy', $row->id)" />
</x-actions-table>
