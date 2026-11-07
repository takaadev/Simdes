<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Concerns\HandlesRestore;
use App\Http\Controllers\Controller;
use App\Http\Requests\VillageTreasuryLand\StoreVillageTreasuryLandRequest;
use App\Http\Requests\VillageTreasuryLand\UpdateVillageTreasuryLandRequest;
use App\Models\VillageTreasuryLand;
use App\Services\VillageTreasuryLandService;
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

    public function store(StoreVillageTreasuryLandRequest $request):\Illuminate\Http\JsonResponse
    {
        return $this->service->getStore($request->validated());
    }

    public function update(UpdateVillageTreasuryLandRequest $request, VillageTreasuryLand $villageTreasuryLand):\Illuminate\Http\JsonResponse
    {
        return $this->service->getUpdate($villageTreasuryLand, $request->validated());
    }

    public function destroy(VillageTreasuryLand $villageTreasuryLand)
    {
        return $this->service->getDestroy($request, $villageTreasuryLand);
    }

    public function restore(int $id)
    {
        return HandlesRestore::restore(VillageTreasuryLand::onlyTrashed()->findOrFail($id));
    }
}
