<?php

namespace App\Http\Controllers\Residents;

use App\Http\Controllers\Concerns\HandlesRestore;
use App\Http\Controllers\Controller;
use App\Http\Requests\Resident\ImportExcelResidentRequest;
use App\Http\Requests\Resident\StoreResidentRequest;
use App\Http\Requests\Resident\UpdateResidentRequest;
use App\Models\Resident;
use App\Services\ResidentService;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function __construct(protected ResidentService $service) {}

    public function index(Request $request)
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return view('pages.resident.index');
        }
        return $this->service->getData($request);
    }

    public function store(StoreResidentRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->service->getStore($request->validated());
    }

    public function update(UpdateResidentRequest $request, Resident $resident): \Illuminate\Http\JsonResponse
    {
        return $this->service->getUpdate($resident, $request->validated());
    }

    public function destroy(Request $request, Resident $resident)
    {
        return $this->service->getDestroy($request, $resident);
    }

    public function restore(int $id)
    {
        return HandlesRestore::restore(Resident::onlyTrashed()->findOrFail($id));
    }

    public function importexcel(ImportExcelResidentRequest $request)
    {
        return $this->service->getImportExcel($request);
    }
}
