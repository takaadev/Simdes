<x-actions-table>
    @if ($row->deleted_at === null)
        @include('pages.villageofficial.show', ['data' => $row])
        @include('pages.villageofficial.edit', ['data' => $row])
    @else
        <x-action-restore :id="$row->id" href="{{ route('village-officials.restore', $row->id) }}" />
    @endif
    <x-action-delete :id="$row->id" :href="route('village-officials.destroy', $row->id)" />
</x-actions-table>
