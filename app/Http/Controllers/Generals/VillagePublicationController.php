<?php

namespace App\Http\Controllers\Generals;

use App\Http\Controllers\Concerns\HandlesRestore;
use App\Http\Controllers\Controller;
use App\Http\Requests\VillagePublication\StoreVillagePublicationRequest;
use App\Http\Requests\VillagePublication\UpdateVillagePublicationRequest;
use App\Models\VillagePublication;
use App\Services\VillagePublicationService;
use Illuminate\Http\Request;

class VillagePublicationController extends Controller
{
    public function __construct(protected VillagePublicationService $service) {}

    public function index(Request $request)
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return view('pages.villagepublication.index');
        }
        return $this->service->getData($request);
    }

    public function store(StoreVillagePublicationRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->service->getStore($request->validated());
    }

    public function update(UpdateVillagePublicationRequest $request, VillagePublication $villagePublication): \Illuminate\Http\JsonResponse
    {
        return $this->service->getUpdate($villagePublication, $request->validated());
    }

    public function destroy(Request $request, VillagePublication $villagePublication)
    {
        return $this->service->getDestroy($request, $villagePublication);
    }

    public function restore(int $id)
    {
        return HandlesRestore::restore(VillagePublication::onlyTrashed()->findOrFail($id));
    }
}
