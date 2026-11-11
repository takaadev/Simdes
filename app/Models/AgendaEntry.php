<?php

namespace App\Models;

use App\Contracts\Restorable;
use App\Enums\AgendaCategoryList;
use App\Enums\AgendaTypeList;
use App\Models\Concerns\RestoresSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgendaEntry extends Model implements Restorable
{
    /** @use HasFactory<\Database\Factories\AgendaEntryFactory> */
    use HasFactory, SoftDeletes, RestoresSoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'tipe_agenda' => AgendaTypeList::class,
            'kategori_agenda' => AgendaCategoryList::class,
            'tanggal_terima' => 'date',
            'tanggal_kirim' => 'date',
            'tgl_surat' => 'date',
            'tgl_disposisi' => 'date',
            'has_expedition' => 'boolean',
        ];
    }

    /**
     * Pembuat data agenda.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Daftar ekspedisi surat keluar.
     */
    public function expeditionEntries(): HasMany
    {
        return $this->hasMany(ExpeditionEntry::class);
    }
}
