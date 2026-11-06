<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\VillageDecree\StoreVillageDecreeRequest;
use App\Http\Requests\VillageDecree\UpdateVillageDecreeRequest;
use App\Models\VillageDecree;
use App\Services\VillageDecreeService;
use Illuminate\Http\Request;

class VillageDecreeController extends Controller
{

    public function __construct(
        protected VillageDecreeService $service
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return view('pages.villagedecree.index');
        }
        return $this->service->getData($request);
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
    public function store(StoreVillageDecreeRequest $request)
    {
        try {
            $this->service->getStore($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Keputusan kepala desa berhasil ditambahkan.',
            ], 201);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan keputusan kepala desa.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(VillageDecree $villageDecree)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VillageDecree $villageDecree)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVillageDecreeRequest $request, VillageDecree $villageDecree)
    {
        try {
            $this->service->getUpdate($villageDecree, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Keputusan kepala desa berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui keputusan kepala desa.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, VillageDecree $villageDecree)
    {
        try {
            $forceDelete = $request->boolean('confirm_delete');
            $this->service->getDestroy($villageRegulation, $forceDelete);
            return response()->json([
                'success' => true,
                'message' => $forceDelete
                    ? 'Keputusan kepala desa dan dokumennya berhasil dihapus permanen.'
                    : 'Keputusan kepala desa berhasil dipindahkan ke sampah.',
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus keputusan kepala desa.',
            ], 500);
        }
    }
}
