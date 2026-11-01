<?php

namespace App\Models;

use App\Enums\AgendaCategoryList;
use App\Enums\AgendaTypeList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgendaEntry extends Model
{
    /** @use HasFactory<\Database\Factories\AgendaEntryFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'agenda_type' => AgendaTypeList::class,
            'agenda_category' => AgendaCategoryList::class,
            'letter_date' => 'date',
            'received_date' => 'date',
            'disposition_date' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
