<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RegistrationController extends Controller {

    public function create(int $programId): View|RedirectResponse {
        $user = auth()->user();
        $userId = $user->id;

        $program = DB::table('program_management')
            ->where('id_program', $programId)
            ->first();

        abort_if(!$program, 404, 'Program lomba tidak ditemukan.');

        $existingRegistration = Registration::getExistingRegistration($userId, $programId);

        $riwayatPendaftaran = DB::table('registrations')
            ->leftJoin('program_management', 'registrations.program_id', '=', 'program_management.id_program')
            ->where('registrations.student_id', $userId)
            ->select('registrations.*', 'program_management.nama_program', 'program_management.bidang')
            ->orderByDesc('registrations.tanggal_daftar')
            ->get();

        return view('registration.form', [
            'program'              => $program,
            'existingRegistration' => $existingRegistration,
            'riwayatPendaftaran'   => $riwayatPendaftaran,
        ]);
    }

    public function store(Request $request, int $programId): RedirectResponse {
        $user = auth()->user();
        $userId = $user->id;

        $program = DB::table('program_management')
            ->where('id_program', $programId)
            ->first();

        abort_if(!$program, 404, 'Program tidak ditemukan.');

        $existing = Registration::getExistingRegistration($userId, $programId);
        if ($existing) {
            return back()->with('error', 'Kamu sudah pernah mendaftar program ini.');
        }

        $validated = $request->validate([
            'phone'           => ['required', 'string', 'max:20'],
            'university'      => ['required', 'string', 'max:150'],
            'faculty'         => ['required', 'string', 'max:100'],
            'team_name'       => ['nullable', 'string', 'max:100'],
            'document'        => ['nullable', 'file', 'mimes:pdf,zip,rar', 'max:20480'], // max 20MB
            'payment_receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'], // max 5MB
        ]);

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('registration_documents', 'public');
        }

        $paymentPath = null;
        if ($request->hasFile('payment_receipt')) {
            $paymentPath = $request->file('payment_receipt')->store('payment_receipts', 'public');
        }

        $initialStatus = $paymentPath ? 'paid' : 'unpaid';

        Registration::create([
            'program_id'      => $programId,
            'student_id'      => $userId,
            'full_name'       => $user->name,
            'email'           => $user->email,
            'phone'           => $validated['phone'],
            'university'      => $validated['university'],
            'faculty'         => $validated['faculty'],
            'team_name'       => $validated['team_name'] ?? null,
            'document'        => $documentPath,
            'payment_receipt' => $paymentPath,
            'status'          => $initialStatus,
            'tanggal_daftar'  => now(),
        ]);

        return redirect()
            ->route('registrations.create', $programId)
            ->with('success', 'Pendaftaran lomba berhasil dikirim!');
    }

    public function edit(int $registrationId): View {
        $userId = auth()->user()->id;
        $pendaftaran = Registration::getByIdForStudent($registrationId, $userId);

        abort_if(!$pendaftaran, 404, 'Pendaftaran tidak ditemukan.');

        $program = DB::table('program_management')
            ->where('id_program', $pendaftaran->program_id)
            ->first();

        return view('registration.edit', [
            'pendaftaran' => $pendaftaran,
            'program'     => $program,
            'canEdit'     => ($pendaftaran->status === 'unpaid'),
        ]);
    }

    public function update(Request $request, int $registrationId): RedirectResponse {
        $userId = auth()->user()->id;
        $pendaftaran = Registration::getByIdForStudent($registrationId, $userId);

        abort_if(!$pendaftaran, 404, 'Pendaftaran tidak ditemukan.');

        $validated = $request->validate([
            'phone'      => ['required', 'string', 'max:20'],
            'university' => ['required', 'string', 'max:150'],
            'faculty'    => ['required', 'string', 'max:100'],
            'team_name'  => ['nullable', 'string', 'max:100'],
            'document'   => ['nullable', 'file', 'mimes:pdf,zip,rar', 'max:20480'],
            'payment_receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        if ($request->hasFile('document')) {
            if ($pendaftaran->document) {
                Storage::disk('public')->delete($pendaftaran->document);
            }
            $validated['document'] = $request->file('document')->store('registration_documents', 'public');
        }

        if ($request->hasFile('payment_receipt')) {
            if ($pendaftaran->payment_receipt) {
                Storage::disk('public')->delete($pendaftaran->payment_receipt);
            }
            $validated['payment_receipt'] = $request->file('payment_receipt')->store('payment_receipts', 'public');
            $validated['status'] = 'paid';
        }

        $pendaftaran->update($validated);

        return redirect()
            ->route('registrations.index')
            ->with('success', 'Data pendaftaran berhasil diperbarui.');
    }

    public function cancel(int $registrationId): RedirectResponse {
        $userId = auth()->user()->id;
        $pendaftaran = Registration::getByIdForStudent($registrationId, $userId);

        abort_if(!$pendaftaran, 404, 'Pendaftaran tidak ditemukan.');

        if ($pendaftaran->document) Storage::disk('public')->delete($pendaftaran->document);
        if ($pendaftaran->payment_receipt) Storage::disk('public')->delete($pendaftaran->payment_receipt);

        $pendaftaran->delete();

        return redirect()->route('registrations.index')->with('success', 'Pendaftaran berhasil dibatalkan.');
    }

    public function index(): View {
        $userId = auth()->user()->id;

        $riwayatPendaftaran = DB::table('registrations')
            ->leftJoin('program_management', 'registrations.program_id', '=', 'program_management.id_program')
            ->where('registrations.student_id', $userId)
            ->select('registrations.*', 'program_management.nama_program', 'program_management.bidang')
            ->orderByDesc('registrations.tanggal_daftar')
            ->get();

        return view('registration.history', compact('riwayatPendaftaran'));
    }
}