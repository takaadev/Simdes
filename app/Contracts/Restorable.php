<?php

namespace App\Contracts;

interface Restorable
{
    public function restoreRecord(): bool;
}
