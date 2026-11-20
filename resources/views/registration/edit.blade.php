<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pendaftaran - FindYourPath</title>

    <link rel="stylesheet" href="{{ asset('css/RegistrationForm.css') }}?v=5">
</head>
<body>

<div class="form-container edit-registration-page">

    <a
        href="{{ route('registrations.index') }}"
        class="back-link"
    >
        &larr; Kembali ke Riwayat
    </a>

    <h1>Edit Pendaftaran</h1>

    <p>
        Program:
        <strong>{{ $program->judul ?? '-' }}</strong>
    </p>

    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>

        <a href="{{ route('registrations.index') }}">
            Kembali ke Riwayat Pendaftaran
        </a>

    @elseif (!$canEdit)
        <p>
            Pendaftaran ini tidak dapat diedit karena statusnya
            sudah bukan Menunggu Verifikasi.
        </p>

        <a href="{{ route('registrations.index') }}">
            Kembali ke Riwayat Pendaftaran
        </a>

    @else

        @if (session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif

        <form
            action="{{ route('registrations.update', $pendaftaran->registration_id) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input
                    type="text"
                    value="{{ $pendaftaran->full_name }}"
                    readonly
                >
            </div>

            <div class="form-group">
                <label>Email</label>
                <input
                    type="email"
                    value="{{ $pendaftaran->email }}"
                    readonly
                >
            </div>

            <div class="form-group">
                <label for="phone">Nomor Telepon</label>
                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="{{ old('phone', $pendaftaran->phone) }}"
                    required
                >

                @error('phone')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="university">Universitas</label>
                <input
                    type="text"
                    id="university"
                    name="university"
                    value="{{ old('university', $pendaftaran->university) }}"
                    required
                >

                @error('university')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="study_program">Program Studi</label>
                <input
                    type="text"
                    id="study_program"
                    name="study_program"
                    value="{{ old('study_program', $pendaftaran->study_program) }}"
                    required
                >

                @error('study_program')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="reason">Alasan Mengikuti Program</label>
                <textarea
                    id="reason"
                    name="reason"
                    rows="5"
                    required
                >{{ old('reason', $pendaftaran->reason) }}</textarea>

                @error('reason')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="edit-form-actions">
                <button type="submit">
                    Simpan Perubahan
                </button>

                <a href="{{ route('registrations.index') }}">
                    Batal
                </a>
            </div>
        </form>

    @endif

</div>

</body>
</html>
