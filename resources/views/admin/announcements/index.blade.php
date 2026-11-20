<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcement Management - FindYourPath</title>
    <link rel="icon" href="data:,">
    @vite(['resources/css/announcements.css', 'resources/js/announcements.js'])
</head>
<body>

    @includeIf('components.sidebar-admin', ['activeMenu' => 'announcement'])

    <div class="main-wrapper">
        <header class="header">
            <h1 class="header-title">Announcement Management</h1>

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

        <main class="main-content">
            <h2 class="greeting-title">Hi, {{ $adminName ?? 'Admin' }}!</h2>
            <p class="greeting-sub">Kelola dan publikasikan pengumuman penting untuk mahasiswa dalam satu tempat.</p>

            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="panel" style="background: linear-gradient(180deg, #F5FAFD 0%, #FFFFFF 36%); overflow: visible;">
                <h3 class="panel-title">Daftar Pengumuman</h3>

                <div class="filter-bar">
                    <div class="search-wrap">
                        <span class="search-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                        <input type="text" id="searchInput" class="search-input" placeholder="Cari judul atau isi pengumuman...">
                    </div>

                    <select id="categoryFilter" class="filter-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>

                    <select id="dateFilter" class="filter-select">
                        <option value="">Semua Waktu</option>
                        <option value="7">7 Hari Terakhir</option>
                        <option value="30">30 Hari Terakhir</option>
                        <option value="90">90 Hari Terakhir</option>
                    </select>

                    <div class="filter-actions">
                        <button type="button" id="exportBtn" class="btn btn--outline">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Ekspor CSV
                        </button>

                        <button type="button" id="openAddModalBtn" class="btn btn--sidebar-teal">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah
                        </button>
                    </div>
                </div>

                <div class="table-meta">
                    <div class="table-meta-left">
                        <span class="table-list-title">Announcement List</span>
                        <button type="button" class="refresh-btn" onclick="window.location.reload()" title="Muat Ulang">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="23 4 23 10 17 10"></polyline>
                                <polyline points="1 20 1 14 7 14"></polyline>
                                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                            </svg>
                        </button>
                    </div>
                    <span class="table-count">
                        Showing {{ $announcements->count() }} of {{ $announcements->total() }} announcements
                    </span>
                </div>

                <div class="table-wrap" style="overflow: visible;">
                    <table class="reg-table" id="announcementTable">
                        <thead>
                            <tr>
                                <th>Judul Pengumuman</th>
                                <th>Isi Pengumuman</th>
                                <th>Kategori</th>
                                <th>Tanggal Dibuat</th>
                                <th>Lainnya</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($announcements as $item)
                                @php
                                    $badgeClass = match($item->category) {
                                        'Penting'       => 'badge--rejected',
                                        'Jadwal'        => 'badge--pending',
                                        'Hasil Seleksi' => 'badge--approved',
                                        default         => 'badge--general',
                                    };
                                    $formattedDate = $item->created_at ? $item->created_at->format('d M Y') : '-';
                                @endphp
                                <tr>
                                    <td>
                                        <div class="user-cell">
                                            <div class="avatar avatar--teal">
                                                {{ strtoupper(substr($item->title, 0, 2)) }}
                                            </div>
                                            <div class="user-info">
                                                <span class="user-name">{{ $item->title }}</span>
                                                <span class="user-email">ID #{{ $item->id_announcement }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="announcement-content-cell">
                                        {{ \Illuminate\Support\Str::limit($item->content, 90) }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $badgeClass }}">{{ $item->category }}</span>
                                    </td>
                                    <td class="reg-date" data-raw-date="{{ $item->created_at ? $item->created_at->format('Y-m-d') : '' }}">
                                        {{ $formattedDate }}
                                    </td>
                                    <td style="position: relative; overflow: visible;">
                                        <div class="action-dropdown-wrap" style="position: relative; z-index: 30;">
                                            <button type="button" class="action-btn edit-menu-trigger" title="Opsi">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                                    <circle cx="12" cy="5" r="2"></circle>
                                                    <circle cx="12" cy="12" r="2"></circle>
                                                    <circle cx="12" cy="19" r="2"></circle>
                                                </svg>
                                            </button>

                                            <div class="action-dropdown-menu" style="z-index: 999; background: #FFFFFF;">
                                                <button type="button"
                                                        class="dropdown-item dropdown-item--edit"
                                                        data-id="{{ $item->id_announcement }}"
                                                        data-title="{{ $item->title }}"
                                                        data-content="{{ $item->content }}"
                                                        data-category="{{ $item->category }}"
                                                        data-date="{{ $formattedDate }}"
                                                        data-update-url="{{ route('admin.announcements.update', $item->id_announcement) }}">
                                                    Edit Pengumuman
                                                </button>

                                                <form action="{{ route('admin.announcements.destroy', $item->id_announcement) }}"
                                                      method="POST"
                                                      class="delete-form"
                                                      onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item dropdown-item--delete">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 36px; color: #7a9bac;">
                                        Belum ada data pengumuman saat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-bar">
                    <span class="pagination-info">
                        Halaman {{ $announcements->currentPage() }} dari {{ $announcements->lastPage() }}
                    </span>

                    <div class="pagination-pages">
                        @if($announcements->onFirstPage())
                            <span class="page-btn page-btn--nav" style="opacity: 0.4; pointer-events: none;">&lsaquo;</span>
                        @else
                            <a href="{{ $announcements->previousPageUrl() }}" class="page-btn page-btn--nav" style="text-decoration: none;">&lsaquo;</a>
                        @endif

                        @for($p = 1; $p <= $announcements->lastPage(); $p++)
                            <a href="{{ $announcements->url($p) }}"
                               class="page-btn {{ $p == $announcements->currentPage() ? 'page-btn--active' : '' }}"
                               style="text-decoration: none;">
                                {{ $p }}
                            </a>
                        @endfor

                        @if($announcements->hasMorePages())
                            <a href="{{ $announcements->nextPageUrl() }}" class="page-btn page-btn--nav" style="text-decoration: none;">&rsaquo;</a>
                        @else
                            <span class="page-btn page-btn--nav" style="opacity: 0.4; pointer-events: none;">&rsaquo;</span>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="announcementModal" class="modal-overlay" style="z-index: 9999;">
        <div class="modal-box" style="position: relative; z-index: 10000;">
            <div class="modal-header">
                <h3 id="modalTitle" class="modal-title">Tambah Pengumuman</h3>
                <button type="button" id="closeModalBtn" class="modal-close">&times;</button>
            </div>

            <form id="announcementForm" action="{{ route('admin.announcements.store') }}" method="POST">
                @csrf
                <input type="hidden" id="formMethod" name="_method" value="POST">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputTitle" class="form-label">Judul Pengumuman</label>
                        <input type="text" id="inputTitle" name="title" class="form-control" placeholder="Masukkan judul pengumuman..." required maxlength="255">
                    </div>

                    <div class="form-group">
                        <label for="inputCategory" class="form-label">Kategori</label>
                        <select id="inputCategory" name="category" class="form-control" required>
                            <option value="Umum">Umum</option>
                            <option value="Penting">Penting</option>
                            <option value="Jadwal">Jadwal</option>
                            <option value="Hasil Seleksi">Hasil Seleksi</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="inputContent" class="form-label">Isi Pengumuman</label>
                        <textarea id="inputContent" name="content" class="form-control form-textarea" rows="4" placeholder="Tulis detail isi pengumuman di sini..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="inputTanggal" class="form-label">Tanggal Publikasi</label>
                        <input type="text" id="inputTanggal" class="form-control form-control--readonly" readonly>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="cancelModalBtn" class="btn btn--outline">Batal</button>
                    <button type="submit" id="submitModalBtn" class="btn btn--sidebar-teal">Simpan Pengumuman</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const storeAnnouncementUrl = "{{ route('admin.announcements.store') }}";
    </script>
</body>
</html>