<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $adminName     = Auth::user()?->name ?? session('user.name', 'Admin');

        return response()
            ->view('admin.announcements.index', compact(
                'announcements',
                'categories',
                'activeMenu',
                'adminName'
            ))
            ->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'        => 'no-cache',
                'Expires'       => 'Sat, 01 Jan 2000 00:00:00 GMT',
            ]);
    }

    public function store(Request $request)
    {
        $request->merge([
            'title'   => trim((string) $request->input('title')),
            'content' => trim((string) $request->input('content')),
        ]);

        $validated = $request->validate($this->rules(), $this->messages());

        Announcement::create($validated);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function update(Request $request, $id_announcement)
    {
        if (!ctype_digit((string) $id_announcement)) {
            return redirect()
                ->route('admin.announcements.index')
                ->with('error', 'ID pengumuman tidak valid.');
        }

        $request->merge([
            'title'   => trim((string) $request->input('title')),
            'content' => trim((string) $request->input('content')),
        ]);

        $validated = $request->validate($this->rules(), $this->messages());

        $announcement = Announcement::findOrFail((int) $id_announcement);
        $announcement->update($validated);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy($id_announcement)
    {
        if (!ctype_digit((string) $id_announcement)) {
            return redirect()
                ->route('admin.announcements.index')
                ->with('error', 'ID pengumuman tidak valid.');
        }

        $announcement = Announcement::findOrFail((int) $id_announcement);
        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'title'    => 'required|string|min:5|max:255',
            'content'  => 'required|string|min:10|max:5000',
            'category' => 'required|in:Penting,Jadwal,Hasil Seleksi,Umum',
        ];
    }

    private function messages(): array
    {
        return [
            'title.required'    => 'Judul pengumuman wajib diisi.',
            'title.string'      => 'Format judul pengumuman tidak valid.',
            'title.min'         => 'Judul pengumuman minimal terdiri dari 5 karakter.',
            'title.max'         => 'Judul pengumuman maksimal 255 karakter.',
            'content.required'  => 'Isi pengumuman wajib diisi.',
            'content.string'    => 'Format isi pengumuman tidak valid.',
            'content.min'       => 'Isi pengumuman terlalu singkat, minimal 10 karakter.',
            'content.max'       => 'Isi pengumuman melebihi batas maksimal 5000 karakter.',
            'category.required' => 'Kategori pengumuman wajib dipilih.',
            'category.in'       => 'Kategori pengumuman yang dipilih tidak valid.',
        ];
    }
}