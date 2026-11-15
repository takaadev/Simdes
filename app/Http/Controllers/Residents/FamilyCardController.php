<?php

namespace App\Http\Controllers\Residents;

use App\Http\Controllers\Concerns\HandlesRestore;
use App\Http\Controllers\Controller;
use App\Http\Requests\FamilyCard\MutationFamilyCardRequest;
use App\Http\Requests\FamilyCard\StoreFamilyCardRequest;
use App\Http\Requests\FamilyCard\UpdateFamilyCardRequest;
use App\Models\FamilyCard;
use App\Services\FamilyCardService;
use Illuminate\Http\Request;

class FamilyCardController extends Controller
{
    public function __construct(protected FamilyCardService $service) {}

    public function index(Request $request)
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return view('pages.familycard.index');
        }
        return $this->service->getData($request);
    }

    public function store(StoreFamilyCardRequest $request)
    {
        return $this->service->getStore($request->validated());
    }

    public function update(UpdateFamilyCardRequest $request, FamilyCard $familycard)
    {
        return $this->service->getUpdate($familycard, $request->validated());
    }

    public function destroy(Request $request, FamilyCard $familycard)
    {
        return $this->service->getDestroy($request, $familycard);
    }

    public function restore(int $id)
    {
        return HandlesRestore::restore(FamilyCard::onlyTrashed()->findOrFail($id));
    }

    public function mutation(MutationFamilyCardRequest $request)
    {
        return $this->service->getMutation($request);
    }
}
