<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RegistrationController extends Controller {

    public function create(int $programId): View|RedirectResponse {
        $user = auth()->user();
        $userId = $user->id;
        $program = DB::table('program_management')
            ->where('id_program', $programId)
            ->first();

        abort_if(!$program, 404, 'Program tidak ditemukan.');

        $documents = DB::table('program_detail')
            ->where('program_id', $programId)
            ->where('jenis', 'dokumen')
            ->orderBy('id')
            ->get();

        $existingRegistration = Registration::getExistingRegistration(
            $user->id,
            $programId
        );

        if ($existingRegistration) {
            return redirect()
                ->route('registrations.index')
                ->with('error', 'Kamu sudah terdaftar pada program ini.');
        }

        $riwayatPendaftaran = DB::table('registrations')
            ->leftJoin(
                'program_management',
                'registrations.program_id',
                '=',
                'program_management.id_program'
            )
            ->where('registrations.student_id', $userId)
            ->select(
                'registrations.*',
                'program_management.nama_program as nama_program',
                'program_management.bidang'
            )
            ->orderByDesc('registrations.registered_at')
            ->get();

        return view('registration.form', [
            'program' => $program,
            'documents' => $documents,
            'existingRegistration' => $existingRegistration,
            'riwayatPendaftaran' => $riwayatPendaftaran,
        ]);
    }

    public function store(Request $request, int $programId): RedirectResponse {
        $user = auth()->user();
        $userId = $user->id;

        $program = DB::table('program_management')
            ->where('id_program', $programId)
            ->first();

        abort_if(!$program, 404, 'Program tidak ditemukan.');

        $existing = Registration::getExistingRegistration(
            $userId,
            $programId
        );

        if ($existing) {
            return back()->with('error', 'Kamu sudah pernah mendaftar program ini.');
        }

        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'university' => ['required', 'string', 'max:255'],
            'study_program' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string'],

            'documents' => ['required', 'array'],
            'documents.*' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:102400',
            ],
        ]);

        $requiredDocuments = DB::table('program_detail')
            ->where('program_id', $programId)
            ->where('jenis', 'dokumen')
            ->get();

        if ($requiredDocuments->isEmpty()) {
            return back()->with('error', 'Persyaratan dokumen program belum tersedia.');
        }

        $uploadedFiles = [];
        try {
            foreach ($requiredDocuments as $document) {
                $file = $request->file('documents.' . $document->id);

                if (!$file || !$file->isValid()) {
                    throw new \RuntimeException(
                        'Dokumen "' . $document->isi . '" wajib diunggah.'
                    );
                }

                $path = $file->store('pendaftaran', 'public');

                if (!$path) {
                    throw new \RuntimeException(
                        'Gagal menyimpan dokumen.'
                    );
                }

                $uploadedFiles[] = [
                    'program_detail_id' => $document->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ];
            }

            $registration = Registration::createRegistration([
                'program_id' => $programId,
                'student_id' => $userId,
                'full_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => $validated['phone'],
                'university' => $validated['university'],
                'study_program' => $validated['study_program'],
                'reason' => $validated['reason'],
            ], $uploadedFiles);

        } catch (\Throwable $error) {

            foreach ($uploadedFiles as $file) {
                Storage::disk('public')->delete($file['file_path']);
            }

            if ($error instanceof \Illuminate\Validation\ValidationException) {
                throw $error;
            }

            report($error);

            return back()
                ->withInput()
                ->with('error', 'Pendaftaran gagal disimpan. Silakan coba lagi.');
        }

        return redirect()
            ->route('registrations.index')
            ->with('success', 'Pendaftaran berhasil dikirim.')
            ->with('registration_code', $registration->registration_code);
    }

    public function edit(int $registrationId): View {
    $user = auth()->user();
    $userId = $user->id;

        $pendaftaran = Registration::getByIdForStudent(
            $registrationId,
            $userId
        );

        abort_if(!$pendaftaran, 404, 'Pendaftaran tidak ditemukan.');

        $program = DB::table('program_management')
            ->where('id_program', $pendaftaran->program_id)
            ->first();

        $canEdit = $pendaftaran->status === 'Menunggu Verifikasi';

        return view('registration.edit', [
            'pendaftaran' => $pendaftaran,
            'program' => $program,
            'canEdit' => $canEdit,
        ]);
    }

    public function update(Request $request, int $registrationId): RedirectResponse {
        $user = auth()->user();
        $userId = $user->id;

        $pendaftaran = Registration::getByIdForStudent(
            $registrationId,
            $userId
        );

        abort_if(!$pendaftaran, 404, 'Pendaftaran tidak ditemukan.');

        if (!$pendaftaran->updateRegistration(
            $request->validate([
                'phone' => ['required', 'string', 'max:30'],
                'university' => ['required', 'string', 'max:255'],
                'study_program' => ['required', 'string', 'max:255'],
                'reason' => ['required', 'string'],
            ])
        )) {
            return back()->with('error', 'Pendaftaran tidak dapat diedit.');
        }

        return redirect()
            ->route('registrations.index')
            ->with('success', 'Perubahan data berhasil disimpan.');
    }

    public function cancel(int $registrationId): RedirectResponse {
        $user = auth()->user();
        $userId = $user->id;

        $pendaftaran = Registration::getByIdForStudent(
            $registrationId,
            $userId
        );

        abort_if(!$pendaftaran, 404, 'Pendaftaran tidak ditemukan.');

        if (!$pendaftaran->cancelRegistration()) {
            return back()->with(
                'error',
                'Pendaftaran tidak dapat dibatalkan karena sudah diproses.'
            );
        }

        return redirect()
            ->route('registrations.index')
            ->with('success', 'Pendaftaran berhasil dibatalkan.');
    }

    public function index(): View {
        $user = auth()->user();
        $userId = $user->id;

        $riwayatPendaftaran = DB::table('registrations')
            ->leftJoin(
                'program_management',
                'registrations.program_id',
                '=',
                'program_management.id_program'
            )
            ->where('registrations.student_id', $userId)
            ->select(
                'registrations.*',
                'program_management.nama_program as nama_program',
                'program_management.bidang'
            )
            ->orderByDesc('registrations.registered_at')
            ->get();

        return view('registration.history', [
            'riwayatPendaftaran' => $riwayatPendaftaran,
        ]);
    }
}