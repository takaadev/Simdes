<?php

namespace App\Http\Controllers\Archieves;

use App\Enums\AgendaCategoryList;
use App\Http\Controllers\Concerns\HandlesRestore;
use App\Http\Controllers\Controller;
use App\Http\Requests\AgendaEntry\StoreAgendaEntryRequest;
use App\Http\Requests\AgendaEntry\UpdateAgendaEntryRequest;
use App\Models\AgendaEntry;
use App\Services\AgendaEntryService;
use Illuminate\Http\Request;

class AgendaEntryController extends Controller
{

    public function __construct(protected AgendaEntryService $service) {}

    public function index(Request $request)
    {
        if (! $request->expectsJson() && ! $request->ajax()) {
            return view('pages.agenda.index');
        }
        return $this->service->getData($request);
    }

    public function generateNumber(Request $request)
    {
        $validated = $request->validate([
            'kategori_agenda' => [
                'required',
                \Illuminate\Validation\Rule::enum(AgendaCategoryList::class),
            ],
            'tgl_surat' => [
                'required',
                'date',
            ],
        ]);

        $category = AgendaCategoryList::from(
            $validated['kategori_agenda']
        );

        $date = \Carbon::parse($validated['tgl_surat']);

        $number = $this->service->getGenerateNumber(category: $category, date: $date, villageCode: 'DS');

        return response()->json([
            'success' => true,
            'nomor_surat' => $number,
        ]);
    }

    public function store(StoreAgendaEntryRequest $request) {}

    public function update(UpdateAgendaEntryRequest $request) {}

    public function destroy(Request $request, AgendaEntry $agenda)
    {
        //
    }

    public function restore(int $id)
    {
        return HandlesRestore::restore(AgendaEntry::onlyTrashed()->findOrFail($id));
    }
}
