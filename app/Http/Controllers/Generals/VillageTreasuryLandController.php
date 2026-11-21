<?php

namespace App\Http\Controllers\Generals;

use App\Http\Controllers\Concerns\HandlesRestore;
use App\Http\Controllers\Controller;
use App\Http\Requests\VillageTreasuryLand\StoreVillageLandRequest;
use App\Http\Requests\VillageTreasuryLand\UpdateVillageTreasuryLandRequest;
use App\Models\VillageTreasuryLand;
use App\Services\VillageTreasuryLandService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VillageTreasuryLandController extends Controller
{

    public function __construct(protected VillageTreasuryLandService $service) {}
    public function index(Request $request)
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return view('pages.villagetreasuryland.index');
        }
        return $this->service->getData($request);
    }


    public function store(StoreVillageLandRequest $request): JsonResponse
    {
        return $this->service->getStore($request->validated());
    }

    public function update(UpdateVillageTreasuryLandRequest $request, VillageTreasuryLand $villageTreasuryLand): JsonResponse
    {
        return $this->service->getUpdate($villageTreasuryLand, $request->validated());
    }

    public function destroy(Request $request, VillageTreasuryLand $villageTreasuryLand)
    {
        return $this->service->getDestroy($request, $villageTreasuryLand);
    }

    public function restore(int $id)
    {
        return HandlesRestore::restore(VillageTreasuryLand::onlyTrashed()->findOrFail($id));
    }

    public function print(Request $request)
    {
        $records = $this->service->getPrintData($request);

        return view('pages.villagetreasuryland.print', [
            'records' => $records,
            'filterSummary' => $this->service->getFilterSummary($request),
        ]);
    }
}
