<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\VillageRegulation\StoreVillageRegulationRequest;
use App\Http\Requests\VillageRegulation\UpdateVillageRegulationRequest;
use App\Models\VillageRegulation;
use App\Services\VillageRegulationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\Facades\DataTables;

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
    if (! $request->ajax()) {
        return view('pages.villageregulation.index');
    }

    $query = $this->service->getData();

        return DataTables::eloquent($query)
            ->addIndexColumn()

            // Jenis Peraturan
            ->editColumn('jenis_peraturan', function ($row) {
                return $row->jenis_peraturan?->label() ?? '-';
            })

            // Nomor Peraturan
            ->editColumn('nomor_peraturan', function ($row) {
                return $row->nomor_peraturan ?: '-';
            })

            // Tanggal Ditetapkan
            ->editColumn('tgl_ditetapkan', function ($row) {
                return $row->tgl_ditetapkan
                    ?->locale('id')
                    ->translatedFormat('d F Y') ?? '-';
            })

            // Nama Peraturan
            ->editColumn('nama_peraturan', function ($row) {
                return view('components.tooltip', [
                    'value' => $row->nama_peraturan,
                ])->render();
            })

            // Uraian
            ->editColumn('uraian', function ($row) {
                return view('components.tooltip', [
                    'value' => $row->uraian,
                ])->render();
            })

            // Tanggal Kesepakatan
            ->editColumn('tgl_kesepakatan', function ($row) {
                return $row->tgl_kesepakatan
                    ?->locale('id')
                    ->translatedFormat('d F Y') ?? '-';
            })

            // Nomor Dilaporkan
            ->editColumn('nomor_dilaporkan', function ($row) {
                return $row->nomor_dilaporkan ?: '-';
            })

            // Tanggal Dilaporkan
            ->editColumn('tgl_dilaporkan', function ($row) {
                return $row->tgl_dilaporkan
                    ?->locale('id')
                    ->translatedFormat('d F Y') ?? '-';
            })

            // Nomor Diundang
            ->editColumn('nomor_diundang', function ($row) {
                return $row->nomor_diundang ?: '-';
            })

            // Tanggal Diundang
            ->editColumn('tgl_diundang', function ($row) {
                return $row->tgl_diundang
                    ?->locale('id')
                    ->translatedFormat('d F Y') ?? '-';
            })

            // Nomor Berita Desa
            ->editColumn('nomor_berita', function ($row) {
                return $row->nomor_berita ?: '-';
            })

            // Tanggal Berita Desa
            ->editColumn('tgl_berita', function ($row) {
                return $row->tgl_berita
                    ?->locale('id')
                    ->translatedFormat('d F Y') ?? '-';
            })

            // Dokumen
            ->editColumn('dokumen', function ($row) {
                return $row->dokumen ?: '-';
            })

            // Keterangan
            ->editColumn('catatan', function ($row) {
                return view('components.tooltip', [
                    'value' => $row->catatan,
                ])->render();
            })

            // Status
            ->editColumn('status', function ($row) {
                $status = $row->status;

                $color = match ($status?->value) {
                    'draft'   => 'gray',
                    'active'  => 'green',
                    'revoked' => 'red',
                    'amended' => 'orange',
                    default   => 'blue',
                };

                return Blade::render(
                    '<x-badge :color="$color">{{ $label }}</x-badge>',
                    [
                        'color' => $color,
                        'label' => $status?->label() ?? '-',
                    ]
                );
            })

            // Aksi
            ->addColumn('action', function ($row) {
                return view('pages.villageregulation.actions', [
                    'row' => $row,
                ])->render();
            })

            // Kolom HTML
            ->rawColumns([
                'nama_peraturan',
                'uraian',
                'catatan',
                'status',
                'action',
            ])

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
