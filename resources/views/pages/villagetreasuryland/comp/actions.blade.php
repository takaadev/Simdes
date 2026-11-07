<x-actions-table>
    @if ($row->deleted_at === null)
        @include('pages.villageasset.show', ['data' => $row])
        @include('pages.villageasset.edit', ['data' => $row])
    @else
        <x-action-restore :id="$row->id" href="{{ route('village-assets.restore', $row->id) }}" />
    @endif
    <x-action-delete :id="$row->id" :href="route('village-assets.destroy', $row->id)" />
</x-actions-table>
