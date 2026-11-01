<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\VillageRegulation\StoreVillageRegulationRequest;
use App\Http\Requests\VillageRegulation\UpdateVillageRegulationRequest;
use App\Models\VillageRegulation;

class VillageRegulationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.villageregulation.index');
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
