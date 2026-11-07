<x-actions-table>
    @if ($row->deleted_at === null)
        @include('pages.villageregulation.show', ['data' => $row])
        @include('pages.villageregulation.edit', ['data' => $row])
    @else
        <x-action-restore :id="$row->id" href="{{ route('village-regulations.restore', $row->id) }}" />
    @endif
    <x-action-delete :id="$row->id" :href="route('village-regulations.destroy', $row->id)" />
</x-actions-table>


