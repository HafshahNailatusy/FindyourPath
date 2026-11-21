<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Management - FindYourPath</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/sidebarAdmin.css', 'resources/css/schedule-management.css', 'resources/js/schedule-management.js'])
</head>
<body>
    <div class="layout-wrapper">
        <x-sidebar-admin active="schedule" />

        <div class="main-wrapper">
            <header class="header">
                <div class="header-left">
                    <button type="button" class="mobile-toggle-btn" id="sidebarToggleBtn" aria-label="Menu">
                        <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2" fill="none">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    <h1 class="header-title">Schedule Management</h1>
                </div>

                <div class="header-right">
                    <button class="header-notif" aria-label="Notifikasi">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M7.70006 15.7506C7.83172 15.9786 8.02108 16.168 8.24911 16.2996C8.47713 16.4313 8.73579 16.5006 8.99909 16.5006C9.26239 16.5006 9.52105 16.4313 9.74907 16.2996C9.9771 16.168 10.1665 15.9786 10.2981 15.7506M2.44596 11.4947C2.34798 11.6021 2.28332 11.7357 2.25984 11.8792C2.23637 12.0226 2.25509 12.1698 2.31373 12.3029C2.37237 12.4359 2.46841 12.549 2.59015 12.6285C2.71189 12.7079 2.8541 12.7502 2.99947 12.7504H14.9997C15.1451 12.7504 15.2873 12.7082 15.4091 12.6289C15.5309 12.5496 15.6271 12.4366 15.6859 12.3037C15.7447 12.1708 15.7636 12.0236 15.7403 11.8801C15.717 11.7366 15.6525 11.603 15.5547 11.4955C14.5572 10.4672 13.4997 9.37432 13.4997 5.99978C13.4997 4.80621 13.0256 3.66152 12.1816 2.81754C11.3377 1.97355 10.1931 1.4994 8.99959 1.4994C7.80609 1.4994 6.66148 1.97355 5.81755 2.81754C4.97361 3.66152 4.4995 4.80621 4.4995 5.99978C4.4995 9.37432 3.44123 10.4672 2.44596 11.4947Z" stroke="#27566A" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </button>
                    <div class="header-user">
                        <div class="header-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                        <div class="header-userinfo">
                            <span class="header-username">{{ auth()->user()->name ?? 'Admin' }}</span>
                            <span class="header-userrole">Administrator</span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="main-content">
                @if (session('success'))
                    <div class="toast-alert show success">
                        {{ session('success') }}
                    </div>
                @endif

                 @if ($errors->any())
                    <div class="toast-alert show" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #f87171; margin-bottom: 16px; padding: 12px 16px; border-radius: 8px;">
                        <strong>Gagal menyimpan jadwal:</strong>
                        <ul style="margin: 6px 0 0 18px;">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <section class="welcome-section">
                    <h2 class="welcome-title">Hi, {{ auth()->user()->name ?? 'Admin' }}!</h2>
                    <p class="welcome-subtitle">Kelola dan pantau jadwal program, kompetisi, beasiswa dalam satu tempat.</p>
                </section>

                <section class="toolbar-section">
                    <div class="search-box">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" class="search-icon">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" id="searchInput" placeholder="Cari program, kompetisi, beasiswa...">
                    </div>

                    <button type="button" class="btn-primary-action" id="btnOpenAddModal">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.5" fill="none">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Tambah Jadwal</span>
                    </button>
                </section>

                <section class="content-card">
                    <div class="card-header-bar">
                        <div class="card-title-group">
                            <h3 class="card-title">Jadwal Program</h3>
                            <span class="count-badge" id="programCountBadge">{{ $totalPrograms }}</span>
                        </div>
                        <span class="card-meta-text">Terbaru diperbarui</span>
                    </div>

                    <div class="table-responsive">
                        <table class="program-table" id="programTable">
                            <thead>
                                <tr>
                                    <th class="th-program">PROGRAM</th>
                                    <th class="th-jenis">TAHAP / STAGE</th>
                                    <th class="th-status">STATUS</th>
                                    <th class="th-periode">PERIODE</th>
                                    <th class="th-lokasi">TGL PELAKSANAAN</th>
                                    <th class="th-aksi">AKSI</th>
                                </tr>
                            </thead>
                            <tbody id="programTableBody">
                                @forelse ($schedules as $item)
                                    @php
                                        $statusClass = 'status-aktif';
                                        if ($item['status'] === 'Akan Datang') {
                                            $statusClass = 'status-akan-datang';
                                        } elseif ($item['status'] === 'Selesai') {
                                            $statusClass = 'status-selesai';
                                        }

                                        $rawStart = !empty($item['start_date']) ? date('Y-m-d', strtotime($item['start_date'])) : '';
                                        $rawEnd   = !empty($item['end_date'])   ? date('Y-m-d', strtotime($item['end_date']))   : '';
                                        $rawEvent = !empty($item['event_date']) ? date('Y-m-d', strtotime($item['event_date'])) : '';
                                    @endphp
                                    <tr class="program-row" 
                                        data-id="{{ $item['id'] }}"
                                        data-id-program="{{ $item['id_program'] }}"
                                        data-stage="{{ $item['stage_name'] }}"
                                        data-status="{{ $item['status'] }}"
                                        data-start="{{ $rawStart }}"
                                        data-end="{{ $rawEnd }}"
                                        data-event="{{ $rawEvent }}">
                                        
                                        <td class="td-program">
                                            <span class="program-title-text">{{ $item['title'] }}</span>
                                        </td>
                                        <td>{{ $item['stage_name'] }}</td>
                                        <td class="td-status">
                                            <span class="badge-status {{ $statusClass }}">{{ $item['status'] }}</span>
                                        </td>
                                        <td class="td-periode">
                                            {{ $item['start_date'] ? date('d M Y', strtotime($item['start_date'])) : '-' }} - 
                                            {{ $item['end_date'] ? date('d M Y', strtotime($item['end_date'])) : '-' }}
                                        </td>
                                        <td class="td-lokasi">{{ date('d M Y', strtotime($item['event_date'])) }}</td>
                                        <td class="td-aksi">
                                            <button type="button" class="btn-action-edit" title="Edit">
                                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </button>
                                            <button type="button" class="btn-action-delete" title="Hapus">
                                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="empty-cell">Belum ada jadwal program tersedia. Klik tombol "+ Tambah Jadwal" untuk membuat data baru.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="content-card calendar-card">
                    <div class="card-header-bar calendar-header-bar">
                        <div class="card-title-group">
                            <h3 class="card-title">Kalender</h3>
                            <span class="count-badge" id="calendarCountBadge">{{ $totalPrograms }}</span>
                        </div>

                        <div class="calendar-nav-bar">
                            <button type="button" class="btn-cal-nav" id="btnPrevMonth" title="Bulan Sebelumnya" aria-label="Bulan Sebelumnya">
                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none"><polyline points="15 18 9 12 15 6"></polyline></svg>
                            </button>
                            <span class="calendar-month-heading" id="calMonthTitle">Bulan Ini</span>
                            <button type="button" class="btn-cal-nav" id="btnNextMonth" title="Bulan Selanjutnya" aria-label="Bulan Selanjutnya">
                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </button>
                        </div>
                    </div>

                    <div class="calendar-wrapper">
                        <div class="calendar-weekdays-row">
                            <div class="weekday-col">Mon</div><div class="weekday-col">Tue</div><div class="weekday-col">Wed</div><div class="weekday-col">Thu</div><div class="weekday-col">Fri</div><div class="weekday-col">Sat</div><div class="weekday-col">Sun</div>
                        </div>
                        <div class="calendar-grid" id="calendarGrid"></div>
                    </div>
                </section>
            </main>
        </div>
    </div>

    <div class="modal-overlay" id="programModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Tambah Jadwal</h3>
                <button type="button" class="modal-close-btn" id="btnCloseModal">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.schedule.store') }}" id="programForm">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="id" id="formId" value="">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="selectProgram">Pilih Program <span class="required">*</span></label>
                        <select id="selectProgram" name="id_program" required>
                            <option value="">Pilih Program</option>
                            @foreach ($programs as $prog)
                                <option value="{{ $prog->id_program }}">{{ $prog->nama_program }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="inputStage">Nama Tahap / Stage <span class="required">*</span></label>
                            <input type="text" id="inputStage" name="stage_name" required placeholder="Contoh: Pendaftaran / Semifinal">
                        </div>
                        <div class="form-group">
                            <label for="inputStatus">Status <span class="required">*</span></label>
                            <select id="inputStatus" name="status" required>
                                <option value="Akan Datang">Akan Datang</option>
                                <option value="Sedang Berjalan">Sedang Berjalan</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="inputStartDate">Mulai Stage<span class="required">*</span></label>
                            <input type="date" id="inputStartDate" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="inputEndDate">Selesai Stage<span class="required">*</span></label>
                            <input type="date" id="inputEndDate" name="end_date" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEventDate">Tanggal Pelaksanaan<span class="required">*</span></label>
                        <input type="date" id="inputEventDate" name="event_date" required>
                        <small class="form-helper">Tanggal ini akan ditandai pada Kalender.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" id="btnCancelModal">Batal</button>
                    <button type="submit" class="btn-save" id="btnSubmitForm">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>

    <form id="deleteForm" method="POST" action="" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        const initialSchedules = @json($schedules);
        const scheduleStoreUrl = "{{ route('admin.schedule.store') }}";
        const scheduleBaseUrl  = "{{ url('/admin/schedule') }}";
    </script>
</body>
</html>