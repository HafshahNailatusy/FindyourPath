<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - FindYourPath</title>
    <link rel="icon" href="data:,">
    @vite(['resources/css/admin-dashboard.css', 'resources/js/admin-dashboard.js'])
</head>
<body>

    <x-sidebar-admin active="dashboard" />

    <div class="main-wrapper">
        <header class="header">

            <div class="header-left">
                <button type="button" class="hamburger-btn" id="sidebarToggleBtn" aria-label="Buka menu">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <h1 class="header-title">Dashboard Admin</h1>
            </div>

            <div class="header-right">
                <button type="button" class="header-notif" title="Notifikasi" aria-label="Notifikasi">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <path d="M7.70006 15.7506C7.83172 15.9786 8.02108 16.168 8.24911 16.2996C8.47713 16.4313 8.73579 16.5006 8.99909 16.5006C9.26239 16.5006 9.52105 16.4313 9.74907 16.2996C9.9771 16.168 10.1665 15.9786 10.2981 15.7506M2.44596 11.4947C2.34798 11.6021 2.28332 11.7357 2.25984 11.8792C2.23637 12.0226 2.25509 12.1698 2.31373 12.3029C2.37237 12.4359 2.46841 12.549 2.59015 12.6285C2.71189 12.7079 2.8541 12.7502 2.99947 12.7504H14.9997C15.1451 12.7504 15.2873 12.7082 15.4091 12.6289C15.5309 12.5496 15.6271 12.4366 15.6859 12.3037C15.7447 12.1708 15.7636 12.0236 15.7403 11.8801C15.717 11.7366 15.6525 11.603 15.5547 11.4955C14.5572 10.4672 13.4997 9.37432 13.4997 5.99978C13.4997 4.80621 13.0256 3.66152 12.1816 2.81754C11.3377 1.97355 10.1931 1.4994 8.99959 1.4994C7.80609 1.4994 6.66148 1.97355 5.81755 2.81754C4.97361 3.66152 4.4995 4.80621 4.4995 5.99978C4.4995 9.37432 3.44123 10.4672 2.44596 11.4947Z" stroke="#27566A" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </button>

                <div class="header-user">
                    <div class="header-avatar">{{ strtoupper(substr($adminName ?? 'A', 0, 1)) }}</div>
                    <div class="header-userinfo">
                        <span class="header-username">{{ $adminName ?? 'Admin' }}</span>
                        <span class="header-userrole">Administrator</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="main-content">
            <h2 class="greeting-title">Hi, {{ $adminName ?? 'Admin' }}!</h2>
            <p class="greeting-sub">Ringkasan data program, pengguna mahasiswa, dan pengumuman FindYourPath.</p>

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert-error">{{ session('error') }}</div>
            @endif

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <span class="stat-card-title">Total Program</span>
                        <div class="stat-card-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-card-value">{{ $totalProgram }}</div>
                    <div class="stat-card-trend">
                        <span class="trend-up">&#8599; {{ $programGrowth }}%</span> dari bulan lalu
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <span class="stat-card-title">Total Schedule</span>
                        <div class="stat-card-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-card-value">{{ $totalSchedule }}</div>
                    <div class="stat-card-trend">
                        <span class="trend-up">&#8599; {{ $scheduleGrowth }}%</span> dari bulan lalu
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <span class="stat-card-title">Schedule Terdekat</span>
                        <div class="stat-card-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-card-value" style="{{ ($nearestSchedule['isUrgent'] ?? false) ? 'color: #1E293B;' : '' }}">
                        {{ $nearestSchedule['badgeText'] ?? '-' }}
                    </div>
                    <div class="stat-card-trend" title="{{ $nearestSchedule['fullList'] ?? '' }}" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $nearestSchedule['label'] ?? 'Tidak ada agenda mendatang' }}
                        @if(($nearestSchedule['extraCount'] ?? 0) > 0)
                            <span class="trend-up" style="font-weight: 600;">+{{ $nearestSchedule['extraCount'] }}</span>
                        @endif
                    </div>
                </div>

                {{-- Kartu 4: Total Mahasiswa Aktif --}}
                <div class="stat-card">
                    <div class="stat-card-header">
                        <span class="stat-card-title">Total Mahasiswa Aktif</span>
                        <div class="stat-card-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-card-value">{{ $totalMahasiswa }}</div>
                    <div class="stat-card-trend">
                        <span class="trend-up">&#8599; {{ $mahasiswaGrowth }}%</span> dari bulan lalu
                    </div>
                </div>
            </div>

            <div class="charts-grid">
                <div class="chart-card" style="background: linear-gradient(180deg, #F5FAFD 0%, #FFFFFF 42%);">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px; flex-wrap:wrap; margin-bottom:14px;">
                        <div>
                            <div class="chart-title">Tren Mahasiswa FindYourPath</div>
                            <div class="chart-subtitle" style="margin-bottom:0;">Grafik akun mahasiswa terdaftar tahun <strong>{{ $selectedYear ?? date('Y') }}</strong></div>
                        </div>

                        <div style="display:flex; align-items:center; gap:8px;">
                            <form method="GET" action="{{ route('admin.dashboard') }}" style="margin:0;">
                                <select name="year" onchange="this.form.submit()" style="padding:5px 10px; border:1px solid #E2E8F0; border-radius:8px; font-size:12px; font-weight:600; color:#334155; background:#FFFFFF; cursor:pointer; outline:none;">
                                    @foreach(($availableYears ?? range(date('Y') + 1, 2022)) as $yr)
                                        <option value="{{ $yr }}" {{ $yr == ($selectedYear ?? date('Y')) ? 'selected' : '' }}>{{ $yr }}</option>
                                    @endforeach
                                </select>
                            </form>

                            <div style="display:flex; align-items:center; gap:4px;">
                                <button type="button" id="btnPrevMonth" title="Geser bulan sebelumnya" style="width:28px; height:28px; border:1px solid #E2E8F0; border-radius:8px; background:#FFFFFF; color:#334155; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center;">&#8249;</button>
                                <span id="chartMonthRange" style="font-size:12px; font-weight:600; color:#64748B; min-width:68px; text-align:center;">Jul - Nov</span>
                                <button type="button" id="btnNextMonth" title="Geser bulan berikutnya" style="width:28px; height:28px; border:1px solid #E2E8F0; border-radius:8px; background:#FFFFFF; color:#334155; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center;">&#8250;</button>
                            </div>
                        </div>
                    </div>

                    <div class="chart-area" id="studentTrendArea" style="cursor: grab;">
                        <div class="chart-y-axis">
                            <span>{{ $chartMaxY }}</span>
                            <span>{{ (int) ($chartMaxY * 0.75) }}</span>
                            <span>{{ (int) ($chartMaxY * 0.5) }}</span>
                            <span>{{ (int) ($chartMaxY * 0.25) }}</span>
                            <span>0</span>
                        </div>

                        <div class="chart-grid-line chart-grid-line--q1"></div>
                        <div class="chart-grid-line chart-grid-line--q2"></div>
                        <div class="chart-grid-line chart-grid-line--q3"></div>
                        <div class="chart-grid-line chart-grid-line--bottom"></div>

                        <div id="chartSvgContainer" class="chart-svg-container"></div>

                        <div class="chart-x-axis" id="chartXAxis">
                            @foreach(array_slice(array_keys($monthlyTrends), -5) as $bulanLabel)
                                <span>{{ $bulanLabel }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="category-card">
                    <div class="chart-title">Bidang Program</div>
                    <div class="chart-subtitle">Distribusi program berdasarkan bidang</div>

                    @php
                        $gradientStops = [];
                        $cumulativePct = 0;
                        foreach ($bidangPrograms as $item) {
                            $start = $cumulativePct;
                            $end   = $cumulativePct + $item['pct'];
                            $gradientStops[] = "{$item['color']} {$start}% {$end}%";
                            $cumulativePct = $end;
                        }
                        $conicGradient = !empty($gradientStops)
                            ? 'conic-gradient(' . implode(', ', $gradientStops) . ')'
                            : 'conic-gradient(#E5E7EB 0% 100%)';
                    @endphp

                    <div class="category-content">
                        <div class="donut-chart" style="background: {{ $conicGradient }};">
                            <div class="donut-inner">
                                <span class="donut-value">{{ $totalBidangProgram }}</span>
                                <span class="donut-label">Total</span>
                            </div>
                        </div>

                        <div class="legend">
                            @forelse($bidangPrograms as $item)
                                <div class="legend-item">
                                    <span class="legend-dot" style="background-color: {{ $item['color'] }};"></span>
                                    <span class="legend-name">{{ $item['label'] }}</span>
                                    <span class="legend-count">{{ $item['total'] }}</span>
                                    <span class="legend-pct">{{ $item['pct'] }}%</span>
                                </div>
                            @empty
                                <span style="font-size: 12px; color: #9CA3AF;">Belum ada data bidang program.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <div class="table-card-header">
                    <div>
                        <div class="table-card-title">Announcement Terbaru</div>
                        <div class="table-card-subtitle">Daftar pengumuman terbaru yang dipublikasikan untuk mahasiswa</div>
                    </div>
                    <a href="{{ route('admin.announcements.index') }}" class="view-all-link">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="dashboard-table-wrap">
                    <table class="registrations-table" id="dashboardTable">
                        <thead>
                            <tr class="table-header-row">
                                <th>Judul Pengumuman</th>
                                <th>Program</th>
                                <th>Ringkasan Isi</th>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAnnouncements as $ann)
                                <tr class="table-row">
                                    <td>
                                        <div class="student-cell">
                                            <div class="student-avatar student-avatar--teal">
                                                {{ strtoupper(substr($ann['title'], 0, 2)) }}
                                            </div>
                                            <span class="student-name">{{ $ann['title'] }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="program-badge">{{ $ann['program'] ?? 'Umum' }}</span>
                                    </td>
                                    <td class="date-cell">
                                        {{ \Illuminate\Support\Str::limit($ann['content'], 60) }}
                                    </td>
                                    <td class="date-cell">{{ $ann['date'] }}</td>
                                    <td>
                                        <span class="status-badge {{ $ann['badge'] }}">{{ $ann['category'] }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 28px; color: #9CA3AF;">
                                        Belum ada data pengumuman terbaru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        const monthlyTrendData = @json($monthlyTrends);
        const chartMaxY = {{ $chartMaxY }};
        const currentMonthNum = {{ (int) date('n') }};
    </script>
</body>
</html>