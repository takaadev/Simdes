<?php

namespace App\Models\Concerns;

trait RestoresSoftDeletes
{
    public function restoreRecord(): bool
    {
        if (! $this->trashed()) {
            return false;
        }

        return $this->restore();
    }
}
