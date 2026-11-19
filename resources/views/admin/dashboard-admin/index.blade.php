<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - FindYourPath</title>
    @vite(['resources/css/admin-dashboard.css', 'resources/js/admin-dashboard.js'])
</head>
<body>

    {{-- Sidebar Admin --}}
    @includeIf('components.sidebar-admin', ['activeMenu' => 'dashboard'])

    <div class="main-wrapper">
        {{-- Top Header --}}
        <header class="header">
            <h1 class="header-title">Dashboard Admin</h1>

            <div class="search-box">
                <span class="search-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <input type="text" class="search-input" placeholder="Cari pengumuman terbaru...">
            </div>

            <div class="header-right">
                <button type="button" class="header-notif" title="Notifikasi">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
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

        {{-- Main Content --}}
        <main class="main-content">
            <h2 class="greeting-title">Hi, {{ $adminName ?? 'Admin' }}!</h2>
            <p class="greeting-sub">Ringkasan data program, pengguna mahasiswa, dan pengumuman FindYourPath.</p>

            {{-- 4 Stat Cards --}}
            <div class="stats-grid">
                {{-- Card 1: Total Program (id_program) --}}
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

                {{-- Card 2: Total Lomba (Dikosongkan sementara) --}}
                <div class="stat-card">
                    <div class="stat-card-header">
                        <span class="stat-card-title">Total Lomba</span>
                        <div class="stat-card-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                                <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                                <path d="M4 22h16"></path>
                                <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                                <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                                <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-card-value">-</div>
                    <div class="stat-card-trend">
                        Belum terhubung ke data
                    </div>
                </div>

                {{-- Card 3: Total Beasiswa (Dikosongkan sementara) --}}
                <div class="stat-card">
                    <div class="stat-card-header">
                        <span class="stat-card-title">Total Beasiswa</span>
                        <div class="stat-card-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-card-value">-</div>
                    <div class="stat-card-trend">
                        Belum terhubung ke data
                    </div>
                </div>

                {{-- Card 4: Total Mahasiswa Aktif (id_mahasiswa) --}}
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

            {{-- Charts Grid --}}
            <div class="charts-grid">
                {{-- Line Chart: Tren Mahasiswa Terdaftar / Login --}}
                <div class="chart-card">
                    <div class="chart-title">Tren Mahasiswa FindYourPath</div>
                    <div class="chart-subtitle">Grafik akun mahasiswa yang terdaftar dalam 5 bulan terakhir</div>

                    <div class="chart-area">
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

                        <div class="chart-x-axis">
                            @foreach(array_keys($monthlyTrends) as $bulanLabel)
                                <span>{{ $bulanLabel }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Donut Chart: Bidang Program --}}
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

            {{-- Bottom Table: Announcement Terbaru --}}
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

                <table class="registrations-table" id="dashboardTable">
                    <thead>
                        <tr class="table-header-row">
                            <th>Judul Pengumuman</th>
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
                                <td class="date-cell">
                                    {{ \Illuminate\Support\Str::limit($ann['content'], 65) }}
                                </td>
                                <td class="date-cell">{{ $ann['date'] }}</td>
                                <td>
                                    <span class="status-badge {{ $ann['badge'] }}">{{ $ann['category'] }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 28px; color: #9CA3AF;">
                                    Belum ada data pengumuman terbaru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        const monthlyTrendData = @json($monthlyTrends);
        const chartMaxY = {{ $chartMaxY }};
    </script>
</body>
</html>