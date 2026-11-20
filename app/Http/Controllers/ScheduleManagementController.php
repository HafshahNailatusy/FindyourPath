<?php

namespace App\Http\Controllers;

use App\Models\ScheduleManagement;
use App\Models\ProgramManagement;
use Illuminate\Http\Request;

class ScheduleManagementController extends Controller
{
    public function index()
    {
        $programs = ProgramManagement::orderBy('nama_program')->get();

        $schedules = ScheduleManagement::with('program')
            ->orderBy('id_schedule', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id'          => $item->id_schedule,
                    'id_schedule' => $item->id_schedule,
                    'id_program'  => $item->id_program,
                    'title'       => $item->program?->nama_program ?? 'Program #' . $item->id_program,
                    'stage_name'  => $item->stage_name,
                    'category'    => $item->program?->bidang ?? 'Umum',
                    'status'      => $item->status,
                    'start_date'  => $item->start_date,
                    'end_date'    => $item->end_date,
                    'event_date'  => $item->event_date,
                    'location'    => $item->program?->penyelenggara ?? 'Online',
                    'description' => $item->stage_name,
                ];
            });

        $totalPrograms = $schedules->count();

        return view('admin.schedule-management.index', compact('schedules', 'programs', 'totalPrograms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_program' => 'required|exists:program_management,id_program',
            'stage_name' => 'required|string|max:50',
            'status'     => 'required|in:Akan Datang,Sedang Berjalan,Selesai',
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
            'event_date' => 'required|date',
        ]);

        ScheduleManagement::create($validated);

        return redirect()->route('admin.schedule.index')->with('success', 'Jadwal program berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $schedule = ScheduleManagement::findOrFail($id);

        $validated = $request->validate([
            'id_program' => 'required|exists:program_management,id_program',
            'stage_name' => 'required|string|max:50',
            'status'     => 'required|in:Akan Datang,Sedang Berjalan,Selesai',
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
            'event_date' => 'required|date',
        ]);

        $schedule->update($validated);

        return redirect()->route('admin.schedule.index')->with('success', 'Jadwal program berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $schedule = ScheduleManagement::findOrFail($id);
        $schedule->delete();

        return redirect()->route('admin.schedule.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}