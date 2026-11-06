<x-actions-table>
    @include('pages.villageregulation.show', [
        'data' => $row,
    ])
    @include('pages.villageregulation.edit', [
        'data' => $row,
    ])
    <x-action-delete :id="$row->id" :href="route('village-regulations.destroy', $row->id)" />
    
</x-actions-table>
