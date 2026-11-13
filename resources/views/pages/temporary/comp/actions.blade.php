<x-actions-table>
    @if ($row->deleted_at === null)
        @include('pages.temporary.outgoing-confirmed', ['data' => $row])
        @include('pages.temporary.show', ['data' => $row])
        @include('pages.temporary.edit', ['data' => $row])
    @else
        <x-action-restore :id="$row->id" href="{{ route('temporaryresidents.restore', $row->id) }}" />
    @endif
    <x-action-delete :id="$row->id" :href="route('temporaryresidents.destroy', $row->id)" />
</x-actions-table>
