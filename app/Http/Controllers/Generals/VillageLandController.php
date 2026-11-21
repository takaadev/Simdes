<?php

namespace App\Http\Controllers\Generals;

use App\Http\Controllers\Concerns\HandlesRestore;
use App\Http\Controllers\Controller;
use App\Http\Requests\VillageLand\StoreVillageLandRequest;
use App\Http\Requests\VillageLand\UpdateVillageLandRequest;
use App\Models\VillageLand;
use App\Services\VillageLandService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VillageLandController extends Controller
{
    public function __construct(protected VillageLandService $service) {}

    public function index(Request $request)
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return view('pages.villageland.index');
        }
        return $this->service->getData($request);
    }

    public function store(StoreVillageLandRequest $request): JsonResponse
    {
        return $this->service->getStore($request->validated());
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVillageLandRequest $request, VillageLand $villageLand): JsonResponse
    {
        return $this->service->getUpdate($villageLand, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, VillageLand $villageLand)
    {
        return $this->service->getDestroy($request, $villageLand);
    }
    public function restore(int $id)
    {
        return HandlesRestore::restore(VillageLand::onlyTrashed()->findOrFail($id));
    }

    public function print(Request $request)
    {
        $records = $this->service->getPrintData($request);

        return view('pages.villageland.print', [
            'records' => $records,
            'filterSummary' => $this->service->getFilterSummary($request),
        ]);
    }
}
