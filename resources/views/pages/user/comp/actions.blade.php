<x-actions-table>
    @if ($row->deleted_at === null)
        {{-- @include('pages.villageofficial.show', ['data' => $row]) --}}
        @include('pages.user.update-password', ['data' => $row])
        @include('pages.user.edit', ['data' => $row])
    @else
        <x-action-restore :id="$row->id" href="{{ route('users.restore', $row->id) }}" />
    @endif
    <x-action-delete :id="$row->id" :href="route('users.destroy', $row->id)" />
</x-actions-table>
