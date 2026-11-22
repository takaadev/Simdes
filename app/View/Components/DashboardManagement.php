<?php

namespace App\View\Components;

use App\Models\AppSetting;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class DashboardManagement extends Component
{
    public string $namaUser;
    public string $sapaan;
    public ?AppSetting $appSetting;

    public function __construct(?AppSetting $appSetting = null)
    {
        $this->namaUser = Auth::user()?->name ?? 'Administrator';
        $this->appSetting = $appSetting ?? AppSetting::first();
        $jam = now()->setTimezone(config('app.timezone'))->hour;
        $this->sapaan = match (true) {
            $jam >= 4 && $jam < 11 => 'Selamat Pagi',
            $jam >= 11 && $jam < 15 => 'Selamat Siang',
            $jam >= 15 && $jam < 18 => 'Selamat Sore',
            default => 'Selamat Malam',
        };
    }
    public function render(): View|Closure|string
    {
        return view('pages.dashboard.dashboard-management');
    }
}
