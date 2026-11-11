<?php

namespace App\Models;

use App\Enums\AgendaCategoryList;
use Illuminate\Database\Eloquent\Model;

class AgendaNumberSequence extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'category' => AgendaCategoryList::class,
            'year' => 'integer',
            'last_number' => 'integer',
        ];
    }
}
