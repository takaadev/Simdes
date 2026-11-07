<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Concerns\HandlesRestore;
use App\Http\Controllers\Controller;
use App\Http\Requests\VillageOfficial\StoreVillageOfficialRequest;
use App\Http\Requests\VillageOfficial\UpdateVillageOfficialRequest;
use App\Models\VillageOfficial;
use App\Services\VillageOfficialService;
use Illuminate\Http\Request;

class VillageOfficialController extends Controller
{
    public function __construct(protected VillageOfficialService $service) {}
    public function index(Request $request)
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return view('pages.villageofficial.index');
        }
        return $this->service->getData($request);
    }

    public function store(StoreVillageOfficialRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->service->getStore($request->validated());
    }

    public function update(UpdateVillageOfficialRequest $request, VillageOfficial $villageOfficial): \Illuminate\Http\JsonResponse
    {
        return $this->service->getUpdate($villageOfficial, $request->validated());
    }

    public function destroy(Request $request, VillageOfficial $villageOfficial)
    {
        return $this->service->getDestroy($request, $villageOfficial);
    }

    public function restore(int $id)
    {
        return HandlesRestore::restore(VillageOfficial::onlyTrashed()->findOrFail($id));
    }
}
