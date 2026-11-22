<?php

namespace App\View\Components\Layouts;

use App\Models\AppSetting;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppLayout extends Component
{
    public ?AppSetting $appSetting;

    public function __construct()
    {
        $this->appSetting = AppSetting::first();
    }

    public function render(): View|Closure|string
    {
        return view('layouts.app-layout');
    }
}