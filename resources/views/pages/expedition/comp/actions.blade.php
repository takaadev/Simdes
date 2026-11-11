<x-actions-table>
    @if ($row->deleted_at === null)
        @include('pages.expedition.show', ['data' => $row])
        @include('pages.expedition.edit', ['data' => $row])
    @else
        <x-action-restore :id="$row->id" href="{{ route('expeditions.restore', $row->id) }}" />
    @endif
    <x-action-delete :id="$row->id" :href="route('expeditions.destroy', $row->id)" />
</x-actions-table>
