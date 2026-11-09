<x-actions-table>
    @if ($row->deleted_at === null)
        @include('pages.villagepublication.show', ['data' => $row])
        @include('pages.villagepublication.edit', ['data' => $row])
    @else
        <x-action-restore :id="$row->id" href="{{ route('village-publications.restore', $row->id) }}" />
    @endif
    <x-action-delete :id="$row->id" :href="route('village-publications.destroy', $row->id)" />
</x-actions-table>


