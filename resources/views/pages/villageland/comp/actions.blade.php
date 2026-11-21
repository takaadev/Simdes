<x-actions-table>
    @if ($row->deleted_at === null)
        @include('pages.villageland.show', ['data' => $row])
        @include('pages.villageland.edit', ['data' => $row])
    @else
        <x-action-restore :id="$row->id" href="{{ route('village-lands.restore', $row->id) }}" />
    @endif
    <x-action-delete :id="$row->id" :href="route('village-lands.destroy', $row->id)" />
</x-actions-table>
