<x-actions-table>
    @if ($row->deleted_at === null)
        @include('pages.resident.show', ['data' => $row])
        @include('pages.resident.edit', ['data' => $row])
    @else
        <x-action-restore :id="$row->id" href="{{ route('residents.restore', $row->id) }}" />
    @endif
    <x-action-delete :id="$row->id" :href="route('residents.destroy', $row->id)" />
</x-actions-table>
