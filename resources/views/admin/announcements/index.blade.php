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

    <x-sidebar-admin active="announcement" />

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
                <h1 class="header-title">Announcement Management</h1>
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
            <p class="greeting-sub">Kelola dan publikasikan pengumuman penting untuk mahasiswa dalam satu tempat.</p>

            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

           @if(session('error'))
                <div class="alert-error">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert-error">
                    <strong>Gagal menyimpan pengumuman:</strong>
                    <ul style="margin: 6px 0 0 18px;">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
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

                <div class="table-wrap">
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

                    @php
                        $currentPage = $announcements->currentPage();
                        $lastPage    = $announcements->lastPage();

                        // Hitung jendela maksimal 3 halaman yang bergeser (1-3, 3-5, dst.)
                        if ($lastPage <= 3) {
                            $startPage = 1;
                            $endPage   = $lastPage;
                        } elseif ($currentPage <= 2) {
                            $startPage = 1;
                            $endPage   = 3;
                        } elseif ($currentPage >= $lastPage - 1) {
                            $startPage = $lastPage - 2;
                            $endPage   = $lastPage;
                        } else {
                            $startPage = $currentPage - 1;
                            $endPage   = $currentPage + 1;
                        }
                    @endphp

                    <div class="pagination-pages">
                        @if($announcements->onFirstPage())
                            <span class="page-btn page-btn--nav" style="opacity: 0.4; pointer-events: none;">&lsaquo;</span>
                        @else
                            <a href="{{ $announcements->previousPageUrl() }}" class="page-btn page-btn--nav" style="text-decoration: none;">&lsaquo;</a>
                        @endif

                        @for($p = $startPage; $p <= $endPage; $p++)
                            <a href="{{ $announcements->url($p) }}"
                               class="page-btn {{ $p == $currentPage ? 'page-btn--active' : '' }}"
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

    @php
        $hasValidationErrors = $errors->any();
        $oldAction = old('form_action', route('admin.announcements.store'));
        $oldMethod = old('_method', 'POST');
    @endphp

    <div id="announcementModal" class="modal-overlay {{ $hasValidationErrors ? 'active' : '' }}" style="z-index: 9999;">
        <div class="modal-box" style="position: relative; z-index: 10000;">
            <div class="modal-header">
                <h3 id="modalTitle" class="modal-title">
                    {{ $oldMethod === 'PUT' ? 'Perbarui Pengumuman' : 'Tambah Pengumuman' }}
                </h3>
                <button type="button" id="closeModalBtn" class="modal-close">&times;</button>
            </div>

            <form id="announcementForm" action="{{ $oldAction }}" method="POST">
                @csrf
                <input type="hidden" id="formMethod" name="_method" value="{{ $oldMethod }}">
                <input type="hidden" id="formActionInput" name="form_action" value="{{ $oldAction }}">

                <div class="modal-body">
                    @if($hasValidationErrors)
                        <div class="alert-error" style="margin-bottom: 4px; padding: 10px 12px; font-size: 12.5px;">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="inputTitle" class="form-label">Judul Pengumuman</label>
                        <input type="text" id="inputTitle" name="title" class="form-control"
                               value="{{ old('title') }}"
                               placeholder="Masukkan judul pengumuman (min. 5 karakter)..."
                               required minlength="5" maxlength="255">
                    </div>

                    <div class="form-group">
                        <label for="inputCategory" class="form-label">Kategori</label>
                        <select id="inputCategory" name="category" class="form-control" required>
                            @foreach(['Umum', 'Penting', 'Jadwal', 'Hasil Seleksi'] as $catOption)
                                <option value="{{ $catOption }}" {{ old('category', 'Umum') === $catOption ? 'selected' : '' }}>
                                    {{ $catOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="inputContent" class="form-label">Isi Pengumuman</label>
                        <textarea id="inputContent" name="content" class="form-control form-textarea" rows="4"
                                  placeholder="Tulis detail isi pengumuman di sini (min. 10 karakter)..."
                                  required minlength="10" maxlength="5000">{{ old('content') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="inputTanggal" class="form-label">Tanggal Publikasi</label>
                        <input type="text" id="inputTanggal" name="display_date"
                               value="{{ old('display_date', now()->format('d M Y')) }}"
                               class="form-control form-control--readonly" readonly>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="cancelModalBtn" class="btn btn--outline">Batal</button>
                    <button type="submit" id="submitModalBtn" class="btn btn--sidebar-teal">
                        {{ $oldMethod === 'PUT' ? 'Simpan Perubahan' : 'Publikasikan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const storeAnnouncementUrl = "{{ route('admin.announcements.store') }}";
    </script>
</body>
</html>