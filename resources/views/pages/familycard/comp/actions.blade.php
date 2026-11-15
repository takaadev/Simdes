<x-actions-table>
    @if ($row->deleted_at === null)

        @if ($familyCard)
            @include('pages.familycard.show', [
                'data' => $familyCard,
            ])

            @include('pages.familycard.edit', [
                'data' => $familyCard,
            ])

            <x-action-delete
                :id="$familyCard->id"
                :href="route('familycards.destroy', $familyCard->id)"
            />
        @endif

    @else
        <x-action-restore
            :id="$row->id"
            href="{{ route('familycards.restore', $row->id) }}"
        />
    @endif
</x-actions-table>