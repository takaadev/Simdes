<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Concerns\HandlesRestore;
use App\Http\Controllers\Controller;
use App\Http\Requests\VillagePosition\StoreVillagePositionRequest;
use App\Http\Requests\VillagePosition\UpdateVillagePositionRequest;
use App\Models\VillagePosition;
use App\Services\VillagePositionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VillagePositionController extends Controller
{
    public function __construct(protected VillagePositionService $service) {}

    public function index(Request $request)
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return view('pages.datamaster.villageposition.index');
        }
        return $this->service->getData($request);
    }

    public function store(StoreVillagePositionRequest $request): JsonResponse
    {
        return $this->service->getStore($request->validated());
    }

    public function update(UpdateVillagePositionRequest $request, VillagePosition $villagePosition): JsonResponse
    {
        return $this->service->getUpdate($villagePosition, $request->validated());
    }

    public function destroy(Request $request, VillagePosition $villagePosition): JsonResponse
    {
        return $this->service->getDestroy($request, $villagePosition);
    }

    public function restore(int $id): JsonResponse
    {
       return HandlesRestore::restore(VillagePosition::onlyTrashed()->findOrFail($id));
    }
}
