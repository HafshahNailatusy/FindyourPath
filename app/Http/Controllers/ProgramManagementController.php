<?php

namespace App\Http\Controllers;

use App\Models\ProgramManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ProgramManagementController extends Controller
{
    public function index()
    {
        $programs = ProgramManagement::orderBy('id_program', 'desc')->get();

        $totalProgram = $programs->count();

        $activeCount = 0;
        $upcomingCount = 0;
        $finishedCount = 0;

        if (Schema::hasTable('schedules_management')) {
            $activeCount   = DB::table('schedules_management')->where('status', 'Sedang Berjalan')->count();
            $upcomingCount = DB::table('schedules_management')->where('status', 'Akan Datang')->count();
            $finishedCount = DB::table('schedules_management')->where('status', 'Selesai')->count();
        }

        $stats = [
            'total'    => $totalProgram,
            'active'   => $activeCount,
            'upcoming' => $upcomingCount,
            'finished' => $finishedCount,
        ];

        return view('admin.program-management.index', compact('programs', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_program'  => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'bidang'        => 'required|in:Pendidikan,Essay,Bisnis,Teknologi',
            'deskripsi'     => 'nullable|string',
            'poster'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $destination = public_path('uploads/posters');
            if (!File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true, true);
            }

            $file->move($destination, $filename);
            $validated['poster'] = $filename;
        }

        ProgramManagement::create($validated);

        return redirect()->route('admin.program-management.index')
            ->with('success', 'Program baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $program = ProgramManagement::findOrFail($id);

        $validated = $request->validate([
            'nama_program'  => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'bidang'        => 'required|in:Pendidikan,Essay,Bisnis,Teknologi',
            'deskripsi'     => 'nullable|string',
            'poster'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        if ($request->hasFile('poster')) {

            if ($program->poster && File::exists(public_path('uploads/posters/' . $program->poster))) {
                File::delete(public_path('uploads/posters/' . $program->poster));
            }

            $file = $request->file('poster');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $destination = public_path('uploads/posters');
            if (!File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true, true);
            }

            $file->move($destination, $filename);
            $validated['poster'] = $filename;
        } else {
            unset($validated['poster']);
        }

        $program->update($validated);

        return redirect()->route('admin.program-management.index')
            ->with('success', 'Data program berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $program = ProgramManagement::findOrFail($id);

        if (method_exists($program, 'schedules')) {
            $program->schedules()->delete();
        }

        if ($program->poster && File::exists(public_path('uploads/posters/' . $program->poster))) {
            File::delete(public_path('uploads/posters/' . $program->poster));
        }

        $program->delete();

        return redirect()->route('admin.program-management.index')
            ->with('success', 'Program dan jadwal terkait berhasil dihapus!');
    }
}
