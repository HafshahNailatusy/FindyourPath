@extends('layouts.mahasiswa')

@section('title', 'Dashboard')
@section('menu', 'dashboard')

@push('styles')
@vite(['resources/css/dashboardUser.css'])
@endpush

@section('content')
<div class="user-dashboard">
    <section class="welcome-banner">
        <div class="welcome-text">
            <h1 class="welcome-title">Selamat Datang, {{ ucwords($nama) }}! <span>👋🏻</span></h1>
            <p class="welcome-subtitle">Pantau pengumuman terbaru, agenda kegiatan, serta peluang program terbaik untuk mendukung perjalananmu.</p>
        </div>
    </section>

    <div class="dashboard-top-grid">

        <section class="dash-card announcement-card">
            <div class="card-header">
                <div class="header-title-wrap">
                    <div class="header-icon-box announcement-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                    </div>
                    <div>
                        <h2>Pengumuman</h2>
                        <span class="sub-header-text">Informasi penting dari sistem & admin</span>
                    </div>
                </div>
            </div>

            <div class="announcement-list">
                @forelse ($announcements as $announcement)
                <div class="announcement-item">
                    <div class="announcement-meta">
                        <span class="category-badge {{ Str::slug($announcement->category ?? 'umum') }}">
                            {{ $announcement->category ?? 'Umum' }}
                        </span>
                        <span class="announcement-date">
                            {{ $announcement->created_at ? $announcement->created_at->format('d M Y') : '-' }}
                        </span>
                    </div>
                    <h3 class="announcement-title">{{ $announcement->title }}</h3>
                    <p class="announcement-content">{{ Str::limit($announcement->content, 140) }}</p>
                </div>
                @empty
                <div class="empty-state-box">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.5">
                        <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>Belum ada pengumuman saat ini.</p>
                </div>
                @endforelse
            </div>
        </section>

        <section class="dash-card schedule-card">
            <div class="card-header">
                <div class="header-title-wrap">
                    <div class="header-icon-box schedule-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div>
                        <h2>Jadwal &amp; Pengingat</h2>
                        <span class="sub-header-text">Agenda &amp; tenggat waktu tahapan program</span>
                    </div>
                </div>
            </div>

            <div class="schedule-list">
                @forelse ($schedules as $schedule)
                <div class="schedule-item">
                    <div class="schedule-indicator"></div>
                    <div class="schedule-info">
                        <h3 class="schedule-title">{{ $schedule->stage_name }}</h3>
                        <p class="schedule-program">
                            {{ $schedule->program->nama_program ?? 'Program #' . $schedule->id_program }}
                        </p>
                        <div class="schedule-footer">
                            <span class="schedule-time">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                {{ $schedule->start_date ? \Carbon\Carbon::parse($schedule->start_date)->format('d M Y') : '-' }}
                                @if($schedule->end_date)
                                – {{ \Carbon\Carbon::parse($schedule->end_date)->format('d M Y') }}
                                @endif
                            </span>
                            @php
                            $statusClass = match(strtolower($schedule->status ?? '')) {
                            'selesai' => 'status-selesai',
                            'sedang berjalan' => 'status-berjalan',
                            default => 'status-akan-datang',
                            };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">
                                {{ $schedule->status ?? 'Akan Datang' }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state-box">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.5">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                    </svg>
                    <p>Belum ada jadwal atau agenda mendatang.</p>
                </div>
                @endforelse
            </div>
        </section>

    </div>

    <section class="dash-card opportunities-section">
        <div class="card-header">
            <div class="header-title-wrap">
                <div class="header-icon-box opp-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </div>
                <div>
                    <h2>Peluang / Kesempatan Lainnya</h2>
                    <span class="sub-header-text">Daftar lomba &amp; program yang siap kamu ikuti</span>
                </div>
            </div>
            <a href="{{ route('explore-program') }}" class="view-all-link">
                Lihat Semua
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>

        <div class="opportunity-grid">
            @forelse ($programs as $program)
            <div class="opportunity-card">
                <div class="opportunity-poster">
                    @if ($program->poster)
                    <img src="{{ asset('uploads/posters/' . $program->poster) }}" alt="{{ $program->nama_program }}">
                    @else
                    <div class="poster-placeholder">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#27566A" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                    </div>
                    @endif
                    @if($program->bidang)
                    <span class="field-badge">{{ $program->bidang }}</span>
                    @endif
                </div>
                <div class="opportunity-details">
                    <h3 class="program-name">{{ $program->nama_program }}</h3>
                    <p class="program-organizer">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        {{ $program->penyelenggara }}
                    </p>
                    <a href="{{ route('programs.show', $program->id_program) }}" class="opportunity-detail-btn">
                        Lihat Detail &rarr;
                    </a>
                </div>
            </div>
            @empty
            <div class="empty-state-box full-width">
                <p>Belum ada program yang tersedia saat ini.</p>
            </div>
            @endforelse
        </div>
    </section>
</div>
@endsection