<?php

namespace App\Http\Controllers\Archieves;

use App\Http\Controllers\Concerns\HandlesRestore;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExpeditionEntry\StoreExpeditionEntryRequest;
use App\Http\Requests\ExpeditionEntry\UpdateExpeditionEntryRequest;
use App\Models\ExpeditionEntry;
use App\Services\ExpeditionEntryService;
use Illuminate\Http\Request;

class ExpeditionEntryController extends Controller
{
    public function __construct(protected ExpeditionEntryService $service) {}

    public function index(Request $request)
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return view('pages.expedition.index');
        }
        return $this->service->getData($request);
    }

    public function store(StoreExpeditionEntryRequest $request):\Illuminate\Http\JsonResponse
    {
        //
    }

    public function update(UpdateExpeditionEntryRequest $request, ExpeditionEntry $expedition):\Illuminate\Http\JsonResponse
    {
        return $this->service->getUpdate($expedition, $request->validated());
    }

    public function destroy(Request $request, ExpeditionEntry $expedition)
    {
        return $this->service->getDestroy($request, $expedition);
    }

    public function restore(int $id)
    {
        return HandlesRestore::restore(ExpeditionEntry::onlyTrashed()->findOrFail($id));
    }
}
