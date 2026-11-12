<x-actions-table>
    @if ($row->deleted_at === null)
        @include('pages.mutation.show', ['data' => $row])
        @include('pages.mutation.edit', ['data' => $row])
    @else
        <x-action-restore :id="$row->id" href="{{ route('mutations.restore', $row->id) }}" />
    @endif
    <x-action-delete :id="$row->id" :href="route('mutations.destroy', $row->id)" />
</x-actions-table>
