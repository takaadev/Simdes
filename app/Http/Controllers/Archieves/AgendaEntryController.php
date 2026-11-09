<?php

namespace App\Http\Controllers\Archieves;

use App\Http\Controllers\Controller;
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

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(AgendaEntry $agendaEntry)
    {
        //
    }

    public function edit(AgendaEntry $agendaEntry)
    {
        //
    }

    public function update(Request $request, AgendaEntry $agendaEntry)
    {
        //
    }

    public function destroy(AgendaEntry $agendaEntry)
    {
        //
    }
}
