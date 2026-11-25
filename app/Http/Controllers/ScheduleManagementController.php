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
            'end_date'   => 'required|date|after_or_equal:start_date',
            'event_date' => 'required|date|after_or_equal:end_date',
        ]);

        try {
            ScheduleManagement::create($validated);

            return redirect()->route('admin.schedule.index')
            ->with('success', 'Jadwal program berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan jadwal program: ' . $e->getMessage());
        }

    }

    public function update(Request $request, $id)
    {
        try {
            $schedule = ScheduleManagement::findOrFail($id);
        }catch (\Exception $e) {
            return redirect()->route('admin.schedule.index')->with('error', 'Jadwal program tidak ditemukan: '. $e->getMessage());
        }

        $validated = $request->validate([
            'id_program' => 'required|exists:program_management,id_program',
            'stage_name' => 'required|string|max:50',
            'status'     => 'required|in:Akan Datang,Sedang Berjalan,Selesai',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'event_date' => 'required|date|after_or_equal:end_date',
        ]);

        try{
            $schedule->update($validated);

            return redirect()->route('admin.schedule.index')->with('success', 'Jadwal program berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->route('admin.schedule.index')->withInput()->with('error', 'Gagal memperbarui jadwal program: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $schedule = ScheduleManagement::findOrFail($id);
            $schedule->delete();

            return redirect()->route('admin.schedule.index')->with('success', 'Jadwal berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.schedule.index')->with('error', 'Gagal menghapus jadwal program: ' . $e->getMessage());
        }
    }
}