<?php

namespace App\Http\Controllers\Residents;

use App\Http\Controllers\Concerns\HandlesRestore;
use App\Http\Controllers\Controller;
use App\Http\Requests\TemporaryResident\StoreTemporaryResidentRequest;
use App\Http\Requests\TemporaryResident\UpdateTemporaryResidentRequest;
use App\Http\Requests\TemporaryResident\UpdateTemporaryResidentOutgoingRequest;
use App\Models\TemporaryResident;
use App\Services\TemporaryResidentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TemporaryResidentController extends Controller
{
    public function __construct(protected TemporaryResidentService $service) {}

    public function index(Request $request)
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return view('pages.temporary.index');
        }
        return $this->service->getData($request);
    }

    public function store(StoreTemporaryResidentRequest $request): JsonResponse
    {
        return $this->service->getStore($request->validated());
    }

    public function update(UpdateTemporaryResidentRequest $request, TemporaryResident $temporary): JsonResponse
    {
        return $this->service->getUpdate($temporary, $request->validated());
    }

    public function outgoingConfirmed(UpdateTemporaryResidentOutgoingRequest $request, $id): JsonResponse
    {
        return $this->service->getOutgoingConfirmed($id, $request->validated());
    }

    public function destroy(Request $request, TemporaryResident $temporary)
    {
        return $this->service->getDestroy($request, $temporary);
    }

    public function restore(int $id)
    {
        return HandlesRestore::restore(TemporaryResident::onlyTrashed()->findOrFail($id));
    }
}
