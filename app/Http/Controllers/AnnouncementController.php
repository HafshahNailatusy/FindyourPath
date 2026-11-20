<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [5, 10, 25, 50])) {
            $perPage = 10;
        }

        $announcements = Announcement::orderByDesc('created_at')->paginate($perPage);
        $categories    = ['Penting', 'Jadwal', 'Hasil Seleksi', 'Umum'];
        $activeMenu    = 'announcement';
        $adminName     = auth()->user()->name ?? session('user.name', 'Admin');

        return view('admin.announcements.index', compact(
            'announcements',
            'categories',
            'activeMenu',
            'adminName'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
            'category' => 'required|in:Penting,Jadwal,Hasil Seleksi,Umum',
        ]);

        Announcement::create($validated);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function update(Request $request, $id_announcement)
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
            'category' => 'required|in:Penting,Jadwal,Hasil Seleksi,Umum',
        ]);

        $announcement = Announcement::findOrFail($id_announcement);
        $announcement->update($validated);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy($id_announcement)
    {
        $announcement = Announcement::findOrFail($id_announcement);
        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}