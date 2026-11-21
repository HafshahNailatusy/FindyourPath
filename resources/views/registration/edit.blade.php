<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pendaftaran - FindYourPath</title>
    <link rel="stylesheet" href="{{ asset('css/RegistrationForm.css') }}?v=6">
</head>
<body>

<div class="container edit-registration-page">

    <a href="{{ route('registrations.index') }}" class="back-link">
        &larr; Kembali ke Riwayat
    </a>

    <h1>Edit Pendaftaran Lomba</h1>

    <p>
        Program:
        <strong>{{ $program->nama_program ?? '-' }}</strong>
    </p>

    @if (session('success'))
        <div class="message success">
            {{ session('success') }}
        </div>
        <a href="{{ route('registrations.index') }}" class="btn-primary" style="display:inline-block; margin-top:12px;">
            Kembali ke Riwayat Pendaftaran
        </a>

    @elseif (!$canEdit)
        <div class="message warning">
            <p>Pendaftaran ini sudah lunas atau tidak dapat diubah lagi.</p>
        </div>
        <a href="{{ route('registrations.index') }}" class="btn-primary" style="display:inline-block; margin-top:12px;">
            Kembali ke Riwayat Pendaftaran
        </a>

    @else

        @if (session('error'))
            <div class="message error">
                {{ session('error') }}
            </div>
        @endif

        <form
            action="{{ route('registrations.update', $pendaftaran->registration_id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="form-card"
        >
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" value="{{ $pendaftaran->full_name }}" readonly>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" value="{{ $pendaftaran->email }}" readonly>
            </div>

            <div class="form-group">
                <label for="team_name">Nama Tim <small class="form-hint">(Kosongkan jika perorangan)</small></label>
                <input
                    type="text"
                    id="team_name"
                    name="team_name"
                    value="{{ old('team_name', $pendaftaran->team_name) }}"
                >
                @error('team_name')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="university">Universitas <span class="required">*</span></label>
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
                    <label for="faculty">Fakultas <span class="required">*</span></label>
                    <input
                        type="text"
                        id="faculty"
                        name="faculty"
                        value="{{ old('faculty', $pendaftaran->faculty) }}"
                        required
                    >
                    @error('faculty')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="phone">Nomor Telepon / WhatsApp <span class="required">*</span></label>
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

            <div class="form-group file-upload-box">
                <label for="document">Perbarui Dokumen / Proposal Lomba</label>
                @if ($pendaftaran->document)
                    <p class="form-hint" style="margin-bottom: 6px;">File saat ini: <a href="{{ asset('storage/' . $pendaftaran->document) }}" target="_blank">Lihat Berkas</a></p>
                @endif
                <input type="file" id="document" name="document" accept=".pdf,.zip,.rar">
                <small class="form-hint">Biarkan kosong jika tidak ingin mengubah dokumen.</small>
                @error('document')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group file-upload-box">
                <label for="payment_receipt">Unggah / Perbarui Bukti Pembayaran</label>
                @if ($pendaftaran->payment_receipt)
                    <p class="form-hint" style="margin-bottom: 6px;">Bukti saat ini: <a href="{{ asset('storage/' . $pendaftaran->payment_receipt) }}" target="_blank">Lihat Bukti</a></p>
                @endif
                <input type="file" id="payment_receipt" name="payment_receipt" accept=".pdf,.jpg,.jpeg,.png">
                <small class="form-hint">Biarkan kosong jika tidak ingin mengubah bukti transfer.</small>
                @error('payment_receipt')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit">
                Simpan Perubahan
            </button>
        </form>

    @endif

</div>

<script src="{{ asset('js/RegistrationForm.js') }}?v=6"></script>

</body>
</html>