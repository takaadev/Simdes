<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\VillageRegulation\StoreVillageRegulationRequest;
use App\Http\Requests\VillageRegulation\UpdateVillageRegulationRequest;
use App\Models\VillageRegulation;
use App\Services\VillageRegulationService;
use Illuminate\Http\Request;

class VillageRegulationController extends Controller
{
    public function __construct(
        protected VillageRegulationService $service
    ) {}
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return view('pages.villageregulation.index');
        }
        return $this->service->getDataTable();
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
        try {
            $this->service->getStore($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Peraturan desa berhasil ditambahkan.',
            ], 201);

        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan peraturan desa.',
            ], 500);
        }
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
