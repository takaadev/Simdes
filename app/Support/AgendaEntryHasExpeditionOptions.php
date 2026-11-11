<?php

namespace App\Support;

use App\Enums\AgendaTypeList;
use App\Models\AgendaEntry;

class AgendaEntryHasExpeditionOptions
{
    public static function options(?int $expeditionId = null): array
    {
        return AgendaEntry::query()
            ->where('has_expedition', true)
            ->where('tipe_agenda', AgendaTypeList::OUTGOING->value)
            ->whereNotExists(function ($query) use ($expeditionId) {
                $query->select(\DB::raw(1))
                    ->from('expedition_entries')
                    ->whereColumn(
                        'expedition_entries.agenda_entry_id',
                        'agenda_entries.id'
                    )
                    ->whereNull('expedition_entries.deleted_at')
                    ->when(
                        $expeditionId,
                        fn($query) => $query->where(
                            'expedition_entries.id',
                            '!=',
                            $expeditionId
                        )
                    );
            })
            ->orderByDesc('tanggal_kirim')
            ->orderByDesc('id')
            ->get([
                'id',
                'nomor_surat',
                'tanggal_kirim',
                'penerima',
            ])
            ->mapWithKeys(fn($agenda) => [
                $agenda->id => $agenda->nomor_surat
                    . ' - '
                    . $agenda->penerima
                    . ' (' . $agenda->tanggal_kirim->format('d/m/Y') . ')',
            ])
            ->all();
    }
}
