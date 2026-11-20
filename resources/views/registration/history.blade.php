<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pendaftaran - FindYourPath</title>

    <link rel="stylesheet" href="{{ asset('css/RegistrationForm.css') }}?v=5">
</head>
<body>

<div class="container">
    <h1>Riwayat Pendaftaran Saya</h1>
    @if (session('success'))
        <div class="message success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="message error">
            {{ session('error') }}
        </div>
    @endif

    @forelse ($riwayatPendaftaran as $riwayat)
        <div class="history-item">
            <h3>{{ $riwayat->nama_program ?? 'Program tidak tersedia' }}</h3>
            <p>Kode: {{ $riwayat->registration_code }}</p>
            <p>Kategori: {{ $riwayat->kategori ?? '-' }}</p>
            <p>
                Tanggal:
                {{ $riwayat->registered_at
                    ? \Carbon\Carbon::parse($riwayat->registered_at)->format('d-m-Y')
                    : '-' }}
            </p>
            <p>Status: {{ $riwayat->status }}</p>

            @if ($riwayat->biaya_program > 0)
                <p>
                    Biaya Pendaftaran:
                    Rp {{ number_format($riwayat->biaya_program, 0, ',', '.') }}
                </p>

                <p>
                    Status Pembayaran:
                    {{ $riwayat->payment_status ?? 'Belum ada bukti pembayaran' }}
                </p>

                @if ($riwayat->payment_proof)
                    <p>
                        Bukti Pembayaran:
                        <a
                            href="{{ asset('storage/' . $riwayat->payment_proof) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Lihat Bukti Pembayaran
                        </a>
                    </p>
                @endif
            @else
                <p>Biaya Pendaftaran: Gratis</p>
            @endif
    
            <div class="history-actions">
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
                        onsubmit="return confirm('Yakin ingin membatalkan pendaftaran ini?')"
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

    <a href="{{ route('explore-program') }}">
        Kembali ke Explore Program
    </a>

</div>
</body>
</html>