<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\ProgramManagement;
use App\Models\ScheduleManagement;
use Illuminate\Http\Request;

class DashboardUserController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $schedules = ScheduleManagement::with('program')
            ->orderBy('start_date', 'asc')
            ->take(6)
            ->get();

        $programs = ProgramManagement::orderBy('id_program', 'desc')
            ->take(6)
            ->get();

        $nama = auth()->user()->name ?? 'Nina Ninu';

        return view('mahasiswa.dashboard', compact(
            'announcements',
            'schedules',
            'programs',
            'nama'
        ));
    }
}
