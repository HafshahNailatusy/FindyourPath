@extends('layouts.mahasiswa')

@section('title', 'Detail Program')
@section('menu', 'explore-program')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/DetailProgram.css') }}">
@endpush

@section('content')

@php
    $kategori = strtolower(trim($program->kategori ?? ''));

    $categoryClass = match ($kategori) {
        'beasiswa' => 'scholarship',
        'lomba' => 'competition',
        default => 'other',
    };

    $isOpen = strtolower(trim($program->status ?? '')) === 'dibuka';
    $statusClass = $isOpen ? 'status-open' : 'status-closed';
@endphp

<div class="detail-program-page">
<div class="detail-topbar">
    <a href="{{ route('explore-program') }}" class="back-link">
        <span aria-hidden="true">←</span>
        Kembali ke Explore Program
    </a>
</div>

    <main class="page-container">
        <section class="program-banner">
            @if (!empty($program->gambar))
                <img
                    src="{{ asset($program->gambar) }}"
                    alt="Banner {{ $program->judul }}"
                    class="banner-image"
                >
            @endif

            <div class="banner-overlay"></div>
            <div class="banner-content">

                <span class="category-badge {{ $categoryClass }}">
                    {{ $program->kategori }}
                </span>

                <h1>{{ $program->judul }}</h1>

                <p class="banner-organizer">
                    {{ $program->penyelenggara }}
                </p>

            </div>
        </section>

        <section class="quick-info">

            <div class="quick-info-item">
                <span class="info-icon">⌖</span>
                <div>
                    <span class="info-label">Lokasi</span>
                    <strong>{{ $program->lokasi ?? '-' }}</strong>
                </div>
            </div>

            <div class="quick-info-item">
                <span class="info-icon">▦</span>
                <div>
                    <span class="info-label">Mulai pendaftaran</span>
                    <strong>{{ $program->tanggal_mulai ?? '-' }}</strong>
                </div>
            </div>

            <div class="quick-info-item deadline-info">
                <span class="info-icon">◷</span>
                <div>
                    <span class="info-label">Batas pendaftaran</span>
                    <strong>{{ $program->deadline ?? '-' }}</strong>
                </div>
            </div>

        </section>

        <div class="detail-layout">

            <div class="detail-main">

                <section class="content-card">
                    <h2>Tentang Program</h2>

                    <p class="description">
                        {{ $program->deskripsi ?? 'Belum ada deskripsi program.' }}
                    </p>
                </section>

                <section class="content-card">
                    <h2>Persyaratan Peserta</h2>

                    @if (!empty($program->persyaratan))
                        <ul class="detail-list">
                            @foreach ($program->persyaratan as $item)
                                <li>
                                    <span class="list-check">✓</span>
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="description">
                            Belum ada persyaratan yang ditambahkan.
                        </p>
                    @endif
                </section>

                <section class="content-card">
                    <h2>Benefit Program</h2>

                    @if (!empty($program->benefit))
                        <ul class="detail-list">
                            @foreach ($program->benefit as $item)
                                <li>
                                    <span class="list-check">✓</span>
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="description">
                            Belum ada informasi benefit program.
                        </p>
                    @endif
                </section>

                <section class="content-card">
                    <h2>Hadiah atau Pendanaan</h2>

                    <p class="description">
                        {{ $program->hadiah ?? '-' }}
                    </p>
                </section>

                <section class="content-card">
                    <h2>Dokumen yang Dibutuhkan</h2>

                    @if (!empty($program->dokumen))
                        <ul class="detail-list">
                            @foreach ($program->dokumen as $item)
                                <li>
                                    <span class="document-icon">▤</span>
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="description">
                            Tidak ada dokumen unggahan yang ditentukan.
                        </p>
                    @endif
                </section>

            </div>

            <aside class="registration-panel">

                <div class="registration-card">

                    <span class="panel-eyebrow">
                        INFORMASI PENDAFTARAN
                    </span>

                    <h2>Siap mengikuti program ini?</h2>

                    <p class="panel-description">
                        Periksa persyaratan dan siapkan dokumen yang dibutuhkan
                        sebelum melanjutkan pendaftaran.
                    </p>

                    <div class="status-row">
                        <span>Status program</span>

                        <span class="status-badge {{ $statusClass }}">
                            {{ $program->status }}
                        </span>
                    </div>

                    <div class="deadline-box">
                        <span class="deadline-icon">◷</span>

                        <div>
                            <span class="info-label">Batas pendaftaran</span>
                            <strong>{{ $program->deadline ?? '-' }}</strong>
                        </div>
                    </div>

                    @if ($isOpen)
                        <a
                            href="{{ route('registrations.create', $program->id) }}"
                            class="register-button"
                        >
                            Daftar Sekarang →
                        </a>
                    @else
                        <button
                            type="button"
                            class="register-button"
                            disabled
                        >
                            Pendaftaran Ditutup
                        </button>
                    @endif

                    <p class="panel-note">
                        Pastikan kamu memenuhi seluruh ketentuan yang ditetapkan
                        penyelenggara.
                    </p>

                </div>

            </aside>

        </div>

        <footer class="page-footer">
            <p>
                FindYourPath — Temukan peluang, kembangkan potensimu.
            </p>
        </footer>

    </main>

</div>
@endsection