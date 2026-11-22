<?php

namespace App\Http\Controllers;

use App\Http\Requests\Settings\AppSettingRequest;
use App\Http\Requests\Settings\VillageProfileSettingRequest;
use App\Models\AppSetting;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct(protected SettingService $service) {}

    public function __invoke(Request $request): View|JsonResponse
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return view('pages.settings.index');
        }

        return response()->json([
            'success' => true,
            'data'    => $this->service->getData(),
        ]);
    }

    public function updateProfile(VillageProfileSettingRequest $request, AppSetting $setting): JsonResponse
    {
        return $this->service->getUpdateProfile($setting, $request->validated());
    }

    public function updateSystem(AppSettingRequest $request, AppSetting $setting): JsonResponse
    {
        return $this->service->getUpdateSystem($setting, $request->validated());
    }
}
