<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $service) {}

    public function __invoke(Request $request): View|JsonResponse
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return view('pages.dashboard.index');
        }

        return response()->json([
            'success'    => true,
            'dataWidget' => $this->service->getDataWidget(),
        ]);
    }
}