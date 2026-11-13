<?php

namespace App\Http\Controllers\Residents;

use App\Http\Controllers\Concerns\HandlesRestore;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResidentMutation\StoreMutationToResidentRequest;
use App\Http\Requests\ResidentMutation\StoreResidentMutationRequest;
use App\Http\Requests\ResidentMutation\UpdateResidentMutationRequest;
use App\Models\ResidentMutation;
use App\Services\ResidentMutationService;
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

    public function store(StoreResidentMutationRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->service->getStore($request->validated());
    }

    public function update(UpdateResidentMutationRequest $request, ResidentMutation $mutation): \Illuminate\Http\JsonResponse
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

    public function mutationToResident(StoreMutationToResidentRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->service->getMutationToResident($request->validated());
    }

    // public function importexcel(ImportExcelResidentRequest $request)
    // {
    //     return $this->service->getImportExcel($request);
    // }
}
