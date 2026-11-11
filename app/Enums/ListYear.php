<?php

namespace App\Enums;

use Carbon\Carbon;

enum ListYear
{
    public static function options(
        ?int $startYear = null,
        ?int $endYear = null
    ): array {
        $startYear ??= 1900;
        $endYear ??= Carbon::now()->year;

        return array_column(
            array_map(
                fn(int $year) => [
                    'value' => (string) $year,
                    'label' => (string) $year,
                ],
                range($endYear, $startYear)
            ),
            'label',
            'value'
        );
    }
}
