<?php

namespace App\Http\Controllers;

use App\Models\ProfileUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileUserController extends Controller {
    private ProfileUser $profileModel;

    public function __construct() {
        $this->profileModel = new ProfileUser();
    }

    public function index() {
        $userId = Auth::id();

        $profileData = $this->profileModel->getByUserId($userId);

        if (!$profileData) {
            abort(404, 'Data profil mahasiswa tidak ditemukan.');
        }

        $profile = [
            'nama' => $profileData->name,
            'nim' => $profileData->nim,
            'email' => $profileData->email,
            'telepon' => $profileData->phone,
            'gender' => $profileData->gender,
            'lahir' => $profileData->birth_date,
            'domisili' => $profileData->address,
            'universitas' => $profileData->university,
            'fakultas' => $profileData->faculty,
            'prodi' => $profileData->major,
            'angkatan' => $profileData->year,
            'semester' => $profileData->semester,
            'ipk' => $profileData->gpa,
            'avatar' => $profileData->avatar,
            'skills' => [],
        ];

        return view('mahasiswa.profile', compact('profile'));
    }

    public function update(Request $request) {
        $userId = Auth::id();
        $action = $request->input('action');

        try {
            if ($action === 'update_profile') {

                $validated = $request->validate([
                    'nama' => 'required|string|max:255',
                    'nim' => 'required|string|max:30',
                    'telepon' => 'nullable|string|max:20',
                    'domisili' => 'nullable|string|max:255',
                    'gender' => 'required|in:Perempuan,Laki-laki',
                    'lahir' => 'nullable|date',
                ]);

                $this->profileModel->updatePersonal(
                    $userId,
                    $validated['nama'],
                    $validated['nim'],
                    $validated['telepon'] ?? '',
                    $validated['domisili'] ?? '',
                    $validated['gender'],
                    $validated['lahir'] ?? null
                );

                DB::table('users')
                    ->where('id', $userId)
                    ->update(['name' => $validated['nama']]);

                return redirect()
                    ->route('mahasiswa.profile')
                    ->with('success', 'Profil berhasil diperbarui.');
            }

            if ($action === 'update_academic') {

                $validated = $request->validate([
                    'universitas' => 'required|string|max:255',
                    'fakultas' => 'required|string|max:255',
                    'prodi' => 'required|string|max:255',
                    'angkatan' => 'required|integer|min:1900',
                    'semester' => 'required|integer|min:1|max:14',
                    'ipk' => 'required|numeric|min:0|max:4',
                ]);

                $this->profileModel->updateAcademic(
                    $userId,
                    $validated['universitas'],
                    $validated['fakultas'],
                    $validated['prodi'],
                    $validated['angkatan'],
                    $validated['semester'],
                    (float) $validated['ipk']
                );

                return redirect()
                    ->route('mahasiswa.profile')
                    ->with('success', 'Data akademik berhasil diperbarui.');
            }

            return redirect()
                ->route('mahasiswa.profile')
                ->with('error', 'Aksi tidak dikenali.');

        } catch (\DomainException $e) {
            return redirect()
                ->route('mahasiswa.profile')
                ->with('error', $e->getMessage());

        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('mahasiswa.profile')
                ->with('error', 'Gagal memperbarui profil. Silakan coba lagi.');
        }
    }
}