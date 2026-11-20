<?php

use App\Models\Recapt;
use App\Services\ResidentRecaptService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $target = now()->subMonthNoOverflow()->startOfMonth();
    $alreadyExists = Recapt::query()->where('tahun', (string) $target->year)->whereDate('bulan', $target->toDateString())->exists();
    if ($alreadyExists) {
        return;
    }
    app(ResidentRecaptService::class)->generate(
        $target->year,
        $target->month
    );
})->monthlyOn(5, '00:10');
