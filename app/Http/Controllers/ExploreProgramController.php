<?php

namespace App\Http\Controllers;

use App\Models\ProgramManagement;
use Illuminate\View\View;

class ExploreProgramController extends Controller
{
    public function index(): View
    {
        return view('explore.index', [
            'programs' => ProgramManagement::all(),
        ]);
    }
}
