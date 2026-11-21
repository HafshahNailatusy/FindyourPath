<?php

namespace App\Http\Controllers;

use App\Models\ProgramManagement;
use Illuminate\View\View;

class ExploreProgramController extends Controller
{
    public function index(): View
    {
        $programs = ProgramManagement::orderBy('id_program', 'desc')->get();

        return view('explore.index', compact('programs'));
    }
}
