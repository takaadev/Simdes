<x-actions-table>
    @include('pages.recapt.edit', ['data' => $row])
    @if ($showDelete && $recapt)
        <x-action-delete :id="$recapt->id" :href="route('recapts.destroy', $recapt->id)" />
    @endif
</x-actions-table>