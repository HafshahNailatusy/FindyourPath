<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $email = Str::lower(trim((string) $request->input('email')));
        $password = (string) $request->input('password');

        if ($email === '' || $password === '') {
            return $this->loginFailed($email, 'Email dan password tidak boleh kosong!');
        }

        if (! Auth::attempt(['email' => $email, 'password' => $password])) {
            return $this->loginFailed($email, 'Email atau password yang Anda masukkan salah!');
        }

        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'password' => $data['password'], 
                'role' => User::ROLE_MAHASISWA,
            ]);

            Student::create(['user_id' => $user->id]);
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil! Mengalihkan ke halaman login...',
            ]);
        }

        return redirect()->route('login')
            ->with('success', 'Pendaftaran berhasil! Silakan login.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function loginFailed(string $email, string $message): RedirectResponse
    {
        return back()->withInput(['email' => $email])->with('error', $message);
    }
}
