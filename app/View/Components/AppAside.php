<?php

namespace App\View\Components;

use App\Models\AppSetting;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppAside extends Component
{
    public ?AppSetting $appSetting;

    public function __construct(?AppSetting $appSetting = null)
    {
        $this->appSetting = $appSetting ?? AppSetting::first();
    }

    public function render(): View|Closure|string
    {
        return view('components.app-aside');
    }
}