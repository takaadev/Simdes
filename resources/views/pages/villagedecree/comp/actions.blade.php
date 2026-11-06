<x-actions-table>
    {{-- @include('pages.villagedecree.show', [
        'data' => $row,
    ])
    @include('pages.villagedecree.edit', [
        'data' => $row,
    ]) --}}
    <x-action-delete :id="$row->id" :href="route('village-decrees.destroy', $row->id)" />

</x-actions-table>
