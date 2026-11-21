<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pendaftaran - FindYourPath</title>
    <link rel="stylesheet" href="{{ asset('css/RegistrationForm.css') }}?v=6">
</head>
<body>

<div class="container">
    <a href="{{ route('explore-program') }}" class="back-link">&larr; Kembali ke Explore Program</a>
    <h1 style="margin-top: 16px;">Riwayat Pendaftaran Lomba Saya</h1>

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

    <div class="history-list" style="margin-top: 24px;">
        @forelse ($riwayatPendaftaran as $riwayat)
            <div class="history-item">
                <h3>{{ $riwayat->nama_program ?? 'Program Lomba' }}</h3>
                <p><strong>Kategori:</strong> {{ $riwayat->bidang ?? '-' }}</p>
                @if ($riwayat->team_name)
                    <p><strong>Nama Tim:</strong> {{ $riwayat->team_name }}</p>
                @endif
                <p><strong>Universitas:</strong> {{ $riwayat->university }} ({{ $riwayat->faculty }})</p>
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

                <div style="margin: 8px 0; font-size: 14px;">
                    @if ($riwayat->document)
                        <span style="margin-right: 12px;">📁 <a href="{{ asset('storage/' . $riwayat->document) }}" target="_blank">Lihat Dokumen</a></span>
                    @endif
                    @if ($riwayat->payment_receipt)
                        <span>🧾 <a href="{{ asset('storage/' . $riwayat->payment_receipt) }}" target="_blank">Lihat Bukti Bayar</a></span>
                    @endif
                </div>

                <div class="history-actions">
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
                            onsubmit="return confirm('Yakin ingin membatalkan pendaftaran ini?')"
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
    </div>
</div>

</body>
</html>