<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran Lomba - FindYourPath</title>
    <link rel="stylesheet" href="{{ asset('css/RegistrationForm.css') }}?v=6">
</head>
<body>

<div class="container">

    <a href="{{ route('explore-program') }}" class="back-link">
        &larr; Kembali ke Explore Program
    </a>

    <h1>Form Pendaftaran Lomba</h1>

    @if (!$program)
        <div class="message error">
            Program tidak ditemukan.
        </div>

    @elseif (session('success'))
        <div class="message success">
            <h2>Pendaftaran Berhasil!</h2>
            <p>{{ session('success') }}</p>
        </div>

        <a href="{{ route('registrations.index') }}" class="btn-primary" style="display:inline-block; margin-top:12px;">
            Lihat Riwayat Pendaftaran
        </a>

    @elseif ($existingRegistration)
        <div class="message warning">
            <h2>Kamu Sudah Terdaftar</h2>
            <p>Kamu sudah pernah mendaftar pada program ini.</p>
            <p>
                Status Pembayaran:
                <span class="badge-status {{ $existingRegistration->status === 'paid' ? 'badge-paid' : 'badge-unpaid' }}">
                    {{ $existingRegistration->status === 'paid' ? 'Lunas' : 'Belum Bayar' }}
                </span>
            </p>
            <a href="{{ route('registrations.index') }}" class="btn-primary" style="display:inline-block; margin-top:12px;">
                Lihat Riwayat Pendaftaran
            </a>
        </div>

    @else

        @if (session('error'))
            <div class="message error">
                {{ session('error') }}
            </div>
        @endif

        <div class="program-info">
            <h2>{{ $program->nama_program }}</h2>
            <p><strong>Penyelenggara:</strong> {{ $program->penyelenggara }}</p>
            <p><strong>Kategori:</strong> {{ $program->bidang }}</p>
        </div>

        <form
            id="registrationForm"
            class="form-card"
            novalidate
            action="{{ route('registrations.store', $program->id_program) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            <h3>Data Peserta / Tim</h3>

            <div class="form-group">
                <label>Nama Lengkap (Ketua / Individu)</label>
                <input type="text" value="{{ auth()->user()->name }}" readonly>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" value="{{ auth()->user()->email }}" readonly>
            </div>

            <div class="form-group">
                <label for="team_name">Nama Tim <small class="form-hint">(Kosongkan jika mendaftar perorangan)</small></label>
                <input
                    type="text"
                    id="team_name"
                    name="team_name"
                    placeholder="Contoh: Tim Inovasi Juara"
                    value="{{ old('team_name') }}"
                >
                @error('team_name')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="university">Universitas / Perguruan Tinggi <span class="required">*</span></label>
                    <input
                        type="text"
                        id="university"
                        name="university"
                        placeholder="Contoh: Universitas Indonesia"
                        value="{{ old('university') }}"
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
                        placeholder="Contoh: Fakultas Ilmu Komputer"
                        value="{{ old('faculty') }}"
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
                    placeholder="08xxxxxxxxxx"
                    value="{{ old('phone') }}"
                    required
                >
                @error('phone')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            <h3>Berkas Pendaftaran Lomba</h3>

            <div class="form-group file-upload-box">
                <label for="document">Unggah Dokumen / Proposal Lomba</label>
                <input
                    type="file"
                    id="document"
                    name="document"
                    accept=".pdf,.zip,.rar"
                >
                <small class="form-hint">Format didukung: PDF, ZIP, RAR (Maksimal 20 MB).</small>
                @error('document')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group file-upload-box">
                <label for="payment_receipt">Unggah Bukti Pembayaran / Transfer</label>
                <input
                    type="file"
                    id="payment_receipt"
                    name="payment_receipt"
                    accept=".pdf,.jpg,.jpeg,.png"
                >
                <small class="form-hint">Format didukung: PDF, JPG, PNG (Maksimal 5 MB).</small>
                @error('payment_receipt')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit">
                Kirim Pendaftaran
            </button>
        </form>

    @endif

    <section class="history-section">
        <h2>Riwayat Pendaftaran Saya</h2>

        @forelse ($riwayatPendaftaran as $riwayat)
            <div class="history-item">
                <h3>{{ $riwayat->nama_program ?? 'Program' }}</h3>

                <p><strong>Kategori:</strong> {{ $riwayat->bidang ?? '-' }}</p>
                @if ($riwayat->team_name)
                    <p><strong>Nama Tim:</strong> {{ $riwayat->team_name }}</p>
                @endif
                <p>
                    <strong>Tanggal Daftar:</strong>
                    {{ $riwayat->tanggal_daftar ? \Carbon\Carbon::parse($riwayat->tanggal_daftar)->format('d-m-Y H:i') : '-' }}
                </p>
                <p>
                    <strong>Status:</strong>
                    <span class="badge-status {{ $riwayat->status === 'paid' ? 'badge-paid' : 'badge-unpaid' }}">
                        {{ $riwayat->status === 'paid' ? 'Lunas / Terbayar' : 'Belum Bayar' }}
                    </span>
                </p>

                <div class="history-actions">
                    <a
                        href="{{ route('explore-program') }}"
                        class="history-link"
                    >
                        Lihat Program
                    </a>

                    @if ($riwayat->status === 'unpaid')
                        <a
                            href="{{ route('registrations.edit', $riwayat->registration_id) }}"
                            class="edit-registration-button"
                        >
                            Edit / Bayar
                        </a>

                        <form
                            method="POST"
                            action="{{ route('registrations.cancel', $riwayat->registration_id) }}"
                            onsubmit="return confirm('Yakin ingin membatalkan pendaftaran ini?');"
                        >
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="cancel-registration-button"
                            >
                                Batalkan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <p>Belum ada riwayat pendaftaran.</p>
        @endforelse
    </section>

</div>

<script src="{{ asset('js/RegistrationForm.js') }}?v=6"></script>

</body>
</html>