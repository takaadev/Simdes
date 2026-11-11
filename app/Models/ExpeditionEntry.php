<?php

namespace App\Models;

use App\Contracts\Restorable;
use App\Models\Concerns\RestoresSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpeditionEntry extends Model implements Restorable
{
    /** @use HasFactory<\Database\Factories\ExpeditionEntryFactory> */
    use HasFactory, SoftDeletes, RestoresSoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'tanggal_kirim' => 'date',
            'tgl_surat' => 'date',
        ];
    }

    /**
     * Agenda surat keluar yang terkait.
     */
    public function agendaEntry(): BelongsTo
    {
        return $this->belongsTo(AgendaEntry::class);
    }

    /**
     * Pembuat data ekspedisi.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
