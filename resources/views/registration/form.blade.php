<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran - FindYourPath</title>

    <link rel="stylesheet" href="{{ asset('css/RegistrationForm.css') }}?v=5">
</head>
<body>

<div class="container">

    <a href="{{ route('explore-program') }}">
        &larr; Kembali ke Explore Program
    </a>

    <h1>Form Pendaftaran</h1>

    @if (!$program)
        <div class="message error">
            Program tidak ditemukan.
        </div>

    @elseif (session('success'))
        <div class="message success">
            <h2>Pendaftaran Berhasil!</h2>
            <p>{{ session('registration_code') }}</p>
            <p>Pendaftaran kamu berhasil dikirim dan sedang menunggu verifikasi.</p>
        </div>

        <a href="{{ route('explore-program') }}">
            Kembali ke Explore Program
        </a>

    @elseif ($existingRegistration)
        <div class="message warning">
            <h2>Kamu Sudah Terdaftar</h2>
            <p>Kamu sudah pernah mendaftar pada program ini.</p>
            <p>
                Kode Pendaftaran:
                <strong>{{ $existingRegistration->registration_code }}</strong>
            </p>
        </div>

    @else

        @if (session('error'))
            <div class="message error">
                {{ session('error') }}
            </div>
        @endif

        <div class="program-info">
            <h2>{{ $program->judul }}</h2>
            <p>Penyelenggara: {{ $program->penyelenggara }}</p>
            <p>Kategori: {{ $program->kategori }}</p>
        </div>

        <form
            id="registrationForm"
            class="form-card"
            novalidate
            action="{{ route('registrations.store', $program->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            <h3>Data Mahasiswa</h3>

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" value="{{ auth()->user()->name }}" readonly>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" value="{{ auth()->user()->email }}" readonly>
            </div>

            <div class="form-group">
                <label for="phone">Nomor Telepon</label>
                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="{{ old('phone') }}"
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
                    value="{{ old('university') }}"
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
                    value="{{ old('study_program') }}"
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
                >{{ old('reason') }}</textarea>

                @error('reason')
                    <small class="field-error">{{ $message }}</small>
                @enderror
            </div>

            @if ($program->biaya > 0)
                <h3>Bukti Pembayaran</h3>

                <div class="form-group">
                    <label>Biaya Pendaftaran</label>

                    <input
                        type="text"
                        value="Rp {{ number_format($program->biaya, 0, ',', '.') }}"
                        readonly
                    >
                </div>

                <div class="form-group">
                    <label for="bukti_pembayaran">
                        Upload Bukti Pembayaran
                    </label>

                    <input
                        type="file"
                        id="bukti_pembayaran"
                        name="bukti_pembayaran"
                        accept=".pdf,.jpg,.jpeg,.png"
                    >

                    @error('bukti_pembayaran')
                        <small class="field-error">{{ $message }}</small>
                    @enderror

                    <small>
                        Format PDF, JPG, atau PNG. Maksimal 100 MB.
                    </small>
                </div>
            @endif

            @if (!empty($documents) && count($documents) > 0)
                <h3>Dokumen Persyaratan</h3>

                @foreach ($documents as $document)
                    <div class="form-group">
                        <label for="document_{{ $document->id }}">
                            {{ $document->isi }}
                        </label>

                        <input
                            type="file"
                            id="document_{{ $document->id }}"
                            name="documents[{{ $document->id }}]"
                            accept=".pdf,.jpg,.jpeg,.png"
                        >

                        @error('documents.' . $document->id)
                            <small class="field-error">{{ $message }}</small>
                        @enderror

                        <small>
                            Format PDF, JPG, atau PNG. Maksimal 100 MB per file.
                        </small>
                    </div>
                @endforeach
            @endif

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

                <p>Kode: {{ $riwayat->registration_code }}</p>
                <p>Kategori: {{ $riwayat->kategori ?? '-' }}</p>
                <p>
                    Tanggal:
                    {{ $riwayat->registered_at?->format('d-m-Y') ?? '-' }}
                </p>
                <p>Status: {{ $riwayat->status }}</p>

                <div class="history-actions">
                    <a
                        href="{{ route('explore-program') }}"
                        class="history-link"
                    >
                        Lihat Program
                    </a>

                    @if ($riwayat->status === 'Menunggu Verifikasi')
                        <a
                            href="{{ route('registrations.edit', $riwayat->registration_id) }}"
                            class="edit-registration-button"
                        >
                            Edit Pendaftaran
                        </a>

                        <form
                            method="POST"
                            action="{{ route('registrations.cancel', $riwayat->registration_id) }}"
                            onsubmit="return confirm('Yakin ingin membatalkan pendaftaran ini? Riwayat pendaftaran tetap akan tersimpan.');"
                        >
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="cancel-registration-button"
                            >
                                Batalkan Pendaftaran
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

<script src="{{ asset('js/RegistrationForm.js') }}"></script>

</body>
</html>