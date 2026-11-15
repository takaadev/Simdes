<?php

namespace App\Http\Controllers\Residents;

use App\Http\Controllers\Controller;

class RecaptResidentController extends Controller
{
    public function index()
    {
        return view('pages.recapt.index');
    }
}
