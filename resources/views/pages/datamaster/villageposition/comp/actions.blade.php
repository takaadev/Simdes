<x-actions-table>
    @if ($row->deleted_at === null)
        @include('pages.datamaster.villageposition.edit', ['data' => $row])
    @else
        <x-action-restore :id="$row->id" href="{{ route('village-positions.restore', $row->id) }}" />
    @endif
    <x-action-delete :id="$row->id" :href="route('village-positions.destroy', $row->id)" />
</x-actions-table>
