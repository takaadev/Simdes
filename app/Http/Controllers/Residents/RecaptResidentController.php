<?php

namespace App\Http\Controllers\Residents;

use App\Http\Controllers\Controller;
use App\Models\Recapt;
use App\Models\RecaptDetail;
use App\Services\ResidentRecaptService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecaptResidentController extends Controller
{
    public function __construct(protected ResidentRecaptService $service) {}
    public function index(Request $request)
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return view('pages.recapt.index');
        }
        return $this->service->getData($request);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
            'bulan' => ['required', 'integer', 'min:1', 'max:12'],
        ]);
        $this->service->generate((int) $validated['tahun'], (int) $validated['bulan'], auth()->id());
        return response()->json([
            'message' => 'Rekapitulasi penduduk berhasil dibuat.',
        ]);
    }


    public function destroy(Recapt $recapt): JsonResponse
    {
        return $this->service->getDestroy($recapt);
    }

    // Di RecaptResidentController
    public function updateNote(Request $request, $detail): JsonResponse
    {
        $recaptDetail = RecaptDetail::findOrFail($detail);

        $validated = $request->validate([
            'catatan' => ['nullable', 'string', 'max:1000'],
        ], [
            'catatan.string' => 'Catatan harus berupa teks.',
            'catatan.max' => 'Catatan maksimal 1000 karakter.',
        ]);

        return $this->service->getUpdateNote($recaptDetail, $validated);
    }
}
