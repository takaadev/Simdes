<?php

namespace App\Http\Controllers\Generals;

use App\Http\Controllers\Concerns\HandlesRestore;
use App\Http\Controllers\Controller;
use App\Http\Requests\VillageRegulation\StoreVillageRegulationRequest;
use App\Http\Requests\VillageRegulation\UpdateVillageRegulationRequest;
use App\Models\VillageRegulation;
use App\Services\VillageRegulationService;
use Illuminate\Http\Request;

class VillageRegulationController extends Controller
{
    public function __construct(protected VillageRegulationService $service) {}
    public function index(Request $request)
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return view('pages.villageregulation.index');
        }
        return $this->service->getData($request);
    }
    public function store(StoreVillageRegulationRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->service->getStore($request->validated());
    }
    public function update(UpdateVillageRegulationRequest $request, VillageRegulation $villageRegulation): \Illuminate\Http\JsonResponse
    {
        return $this->service->getUpdate($villageRegulation, $request->validated());
    }
    public function destroy(Request $request, VillageRegulation $villageRegulation)
    {
        return $this->service->getDestroy($request, $villageRegulation);
    }
    public function restore(int $id)
    {
        return HandlesRestore::restore(VillageRegulation::onlyTrashed()->findOrFail($id));
    }
}
