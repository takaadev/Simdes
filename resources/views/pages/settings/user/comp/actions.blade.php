<x-actions-table>
    @if ($row->deleted_at === null)
        {{-- @include('pages.villageofficial.show', ['data' => $row]) --}}
        @include('pages.settings.user.update-password', ['data' => $row])
        @include('pages.settings.user.edit', ['data' => $row])
    @else
        <x-action-restore :id="$row->id" href="{{ route('users.restore', $row->id) }}" />
    @endif
    <x-action-delete :id="$row->id" :href="route('settings.users.destroy', $row->id)" />
</x-actions-table>
