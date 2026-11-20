<?php

namespace App\Support;

use App\Enums\MutationTypeList;
use App\Models\ResidentMutation;

class MutationToResidentOptions
{
    public static function options(): array
    {
        return ResidentMutation::query()
            ->with('resident:id,nama_lengkap')
            ->where('tipe_mutasi', MutationTypeList::ADD->value)
            ->whereNull('resident_id')
            ->orderBy('tgl_mutasi')
            ->get([
                'id',
                'resident_id',
                'tgl_mutasi',
                'kategori_mutasi',
            ])
            ->mapWithKeys(fn($mutation) => [
                $mutation->id => (
                    $mutation->resident?->nama_lengkap ?? 'Penduduk tidak ditemukan'
                )
                    . ' - '
                    . $mutation->tgl_mutasi->format('d/m/Y')
                    . ' (' . ($mutation->kategori_mutasi?->label() ?? '-') . ')',
            ])
            ->all();
    }
}
