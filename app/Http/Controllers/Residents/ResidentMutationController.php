<?php

namespace App\Http\Controllers\Residents;

use App\Http\Controllers\Concerns\HandlesRestore;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResidentMutation\StoreMutationOutgoingRequest;
use App\Http\Requests\ResidentMutation\StoreResidentMutationRequest;
use App\Http\Requests\ResidentMutation\UpdateResidentMutationRequest;
use App\Models\ResidentMutation;
use App\Services\ResidentMutationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResidentMutationController extends Controller
{
    public function __construct(protected ResidentMutationService $service) {}

    public function index(Request $request)
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return view('pages.mutation.index');
        }
        return $this->service->getData($request);
    }

    public function mutationOutgoing(StoreMutationOutgoingRequest $request)
    {
        return $this->service->getMutationOutgoing($request->validated());
    }

    public function store(StoreResidentMutationRequest $request): JsonResponse
    {
        return $this->service->getStore($request->validated());
    }

    public function update(UpdateResidentMutationRequest $request, ResidentMutation $mutation): JsonResponse
    {
        return $this->service->getUpdate($mutation, $request->validated());
    }

    public function destroy(Request $request, ResidentMutation $mutation)
    {
        return $this->service->getDestroy($request, $mutation);
    }

    public function restore(int $id)
    {
        return HandlesRestore::restore(ResidentMutation::onlyTrashed()->findOrFail($id));
    }

    public function print(Request $request)
    {
        $records = $this->service->getPrintData($request);

        return view('pages.mutation.print', [
            'records' => $records,
            'filterSummary' => $this->service->getFilterSummary($request),
        ]);
    }
}
