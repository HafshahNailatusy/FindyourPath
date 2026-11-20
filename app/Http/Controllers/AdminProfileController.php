<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('admin');
        return view('admin.admin-profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'      => 'nullable|string|max:20',
            'address'    => 'nullable|string',
            'birth_date' => 'nullable|string',
            'gender'     => 'nullable|in:Laki-laki,Perempuan',
            'admin_id'   => 'nullable|string|max:50',
            'position'   => 'nullable|string|max:100',
            'avatar'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        $avatarPath = $user->admin?->avatar;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $fileName = 'avatar_admin_' . $user->id . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            $destination = public_path('uploads/avatars');
            if (!File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true, true);
            }

            if ($avatarPath && File::exists(public_path($avatarPath))) {
                File::delete(public_path($avatarPath));
            }

            $file->move($destination, $fileName);
            $avatarPath = 'uploads/avatars/' . $fileName;
        }

        $user->admin()->updateOrCreate(
            [
                'user_id' => $user->id
            ],
            [
                'admin_id'   => $request->admin_id,
                'position'   => $request->position ?? 'Administrator',
                'phone'      => $request->phone,
                'address'    => $request->address,
                'birth_date' => $request->birth_date,
                'gender'     => $request->gender,
                'avatar'     => $avatarPath,
            ]
        );

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function deleteAvatar()
    {
        $user = auth()->user();
        if ($user->admin && $user->admin->avatar) {
            if (File::exists(public_path($user->admin->avatar))) {
                File::delete(public_path($user->admin->avatar));
            }
            $user->admin()->update(['avatar' => null]);
            return redirect()->back()->with('success', 'Foto profil berhasil dihapus!');
        }

        return redirect()->back()->with('error', 'Tidak ada foto profil untuk dihapus.');
    }
}