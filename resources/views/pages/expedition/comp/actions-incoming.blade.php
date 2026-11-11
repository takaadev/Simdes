<x-actions-table>
    @if ($row->deleted_at === null)
        @include('pages.agenda.incoming.show', ['data' => $row])
        @include('pages.agenda.incoming.edit', ['data' => $row])
    @else
        <x-action-restore :id="$row->id" href="{{ route('agendas.restore', $row->id) }}" />
    @endif
    <x-action-delete :id="$row->id" :href="route('agendas.destroy', $row->id)" />
</x-actions-table>
