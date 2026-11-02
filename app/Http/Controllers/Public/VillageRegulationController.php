<?php

namespace App\Http\Controllers\Public;

use App\Enums\RegulationTypeList;
use App\Http\Controllers\Controller;
use App\Http\Requests\VillageRegulation\StoreVillageRegulationRequest;
use App\Http\Requests\VillageRegulation\UpdateVillageRegulationRequest;
use App\Models\VillageRegulation;
use App\Services\VillageRegulationService;
use Yajra\DataTables\Facades\DataTables;

class VillageRegulationController extends Controller
{
     public function __construct(
        protected VillageRegulationService $service
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.villageregulation.index');
    }

    public function data()
    {
        $query = $this->service->getData();

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('regulation_type', function ($row) {
                return $row->regulation_type?->label() ?? '-';
            })->editColumn('stipulation_date', function ($row) {
                return $row->stipulation_date?->format('d-m-Y') ?? '-';
            })
            ->editColumn('promulgation_date', function ($row) {
                return $row->promulgation_date?->locale('id')->translatedFormat('d F Y') ?? '-';
            })
            ->addColumn('action', function ($row) {
                return view('pages.villageregulation.actions', [
                    'row' => $row,
                ])->render();
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVillageRegulationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UpdateVillageRegulationRequest $villageRegulation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VillageRegulation $villageRegulation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVillageRegulationRequest $request, VillageRegulation $villageRegulation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VillageRegulation $villageRegulation)
    {
        //
    }
}
