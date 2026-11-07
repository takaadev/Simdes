<x-actions-table>
    @if ($row->deleted_at === null)
        @include('pages.villagedecree.show', ['data' => $row])
        @include('pages.villagedecree.edit', ['data' => $row])
    @else
        <x-action-restore :id="$row->id" href="{{ route('village-decrees.restore', $row->id) }}" />
    @endif
    <x-action-delete :id="$row->id" :href="route('village-decrees.destroy', $row->id)" />
</x-actions-table>

