<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Concerns\HandlesRestore;
use App\Http\Controllers\Controller;
use App\Http\Requests\VillageAsset\StoreVillageAssetRequest;
use App\Http\Requests\VillageAsset\UpdateVillageAssetRequest;
use App\Models\VillageAsset;
use App\Services\VillageAssetService;
use Illuminate\Http\Request;

class VillageAssetController extends Controller
{
    public function __construct(protected VillageAssetService $service) {}
    public function index(Request $request)
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return view('pages.villageasset.index');
        }
        return $this->service->getData($request);
    }
    public function store(StoreVillageAssetRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->service->getStore($request->validated());
    }
    public function update(UpdateVillageAssetRequest $request, VillageAsset $villageAsset): \Illuminate\Http\JsonResponse
    {
        return $this->service->getUpdate($villageAsset, $request->validated());
    }
    public function destroy(Request $request, VillageAsset $villageAsset)
    {
        return $this->service->getDestroy($request, $villageAsset);
    }
    public function restore(int $id)
    {
        return HandlesRestore::restore(VillageAsset::onlyTrashed()->findOrFail($id));
    }
}
