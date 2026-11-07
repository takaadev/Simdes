<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Concerns\HandlesRestore;
use App\Http\Controllers\Controller;
use App\Http\Requests\VillageDecree\StoreVillageDecreeRequest;
use App\Http\Requests\VillageDecree\UpdateVillageDecreeRequest;
use App\Models\VillageDecree;
use App\Services\VillageDecreeService;
use Illuminate\Http\Request;

class VillageDecreeController extends Controller
{
    public function __construct(protected VillageDecreeService $service) {}
    public function index(Request $request)
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return view('pages.villagedecree.index');
        }
        return $this->service->getData($request);
    }
    public function store(StoreVillageDecreeRequest $request)
    {
        $this->service->getStore($request->validated());
    }
    public function update(UpdateVillageDecreeRequest $request, VillageDecree $villageDecree)
    {
        $this->service->getUpdate($villageDecree, $request->validated());
    }
    public function destroy(Request $request, VillageDecree $villageDecree)
    {
        return $this->service->getDestroy($request, $villageDecree);
    }
    public function restore(int $id)
    {
        return HandlesRestore::restore(VillageDecree::onlyTrashed()->findOrFail($id));
    }
}
