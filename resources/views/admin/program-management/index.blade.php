<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Program Management - FindYourPath</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/programManagement.css', 'resources/js/programManagement.js'])
</head>

<body>
    <div class="layout-wrapper">
        <x-sidebar-admin active="program" />

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
                    <h1 class="header-title">Program Management</h1>
                </div>

                <div class="header-right">
                    <button type="button" class="header-notif" aria-label="Notifikasi">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.70006 15.7506C7.83172 15.9786 8.02108 16.168 8.24911 16.2996C8.47713 16.4313 8.73579 16.5006 8.99909 16.5006C9.26239 16.5006 9.52105 16.4313 9.74907 16.2996C9.9771 16.168 10.1665 15.9786 10.2981 15.7506M2.44596 11.4947C2.34798 11.6021 2.28332 11.7357 2.25984 11.8792C2.23637 12.0226 2.25509 12.1698 2.31373 12.3029C2.37237 12.4359 2.46841 12.549 2.59015 12.6285C2.71189 12.7079 2.8541 12.7502 2.99947 12.7504H14.9997C15.1451 12.7504 15.2873 12.7082 15.4091 12.6289C15.5309 12.5496 15.6271 12.4366 15.6859 12.3037C15.7447 12.1708 15.7636 12.0236 15.7403 11.8801C15.717 11.7366 15.6525 11.603 15.5547 11.4955C14.5572 10.4672 13.4997 9.37432 13.4997 5.99978C13.4997 4.80621 13.0256 3.66152 12.1816 2.81754C11.3377 1.97355 10.1931 1.4994 8.99959 1.4994C7.80609 1.4994 6.66148 1.97355 5.81755 2.81754C4.97361 3.66152 4.4995 4.80621 4.4995 5.99978C4.4995 9.37432 3.44123 10.4672 2.44596 11.4947Z" stroke="#27566A" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </button>
                    <div class="header-user">
                        <div class="header-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'Admin', 0, 1)) }}</div>
                        <div class="header-userinfo">
                            <span class="header-username">{{ auth()->user()->name ?? 'Admin' }}</span>
                            <span class="header-userrole">Administrator</span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="main-content">
                @if(session('success'))
                <div class="pm-alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if ($errors->any())
                <div class="pm-alert-error">
                    <strong style="display: block; margin-bottom: 6px;">Terdapat kesalahan pada formulir:</strong>
                    <ul style="margin: 0; padding-left: 20px; font-size: 13.5px; line-height: 1.5;">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <section class="pm-welcome-section">
                    <h2 class="pm-welcome-title">Hi, {{ $adminName ?? 'Admin' }}!</h2>
                    <p class="pm-welcome-subtitle">Kelola dan pantau seluruh informasi serta kegiatan perlombaan secara terstruktur, sistematis, dan efisien.</p>
                </section>

                <section class="pm-stats-container">
                    <div class="pm-stat-card">
                        <p>Total Program</p>
                        <h2>{{ $stats['total'] }}</h2>
                    </div>
                    <div class="pm-stat-card">
                        <p>Program Aktif</p>
                        <h2>{{ $stats['active'] }}</h2>
                    </div>
                    <div class="pm-stat-card">
                        <p>Akan Datang</p>
                        <h2>{{ $stats['upcoming'] }}</h2>
                    </div>
                    <div class="pm-stat-card">
                        <p>Selesai</p>
                        <h2>{{ $stats['finished'] }}</h2>
                    </div>
                </section>

                <section class="pm-toolbar">
                    <div class="pm-search-box">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" id="searchProgram" placeholder="Cari nama program atau penyelenggara...">
                    </div>

                    <select id="bidangFilter" class="pm-filter-select">
                        <option value="">Semua Bidang</option>
                        <option value="Pendidikan">Pendidikan</option>
                        <option value="Essay">Essay</option>
                        <option value="Bisnis">Bisnis</option>
                        <option value="Teknologi">Teknologi</option>
                    </select>

                    <button type="button" id="addProgramButton" class="pm-btn-primary">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah Program
                    </button>
                </section>

                <section class="pm-table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Poster</th>
                                <th>Nama Program</th>
                                <th>Penyelenggara</th>
                                <th>Bidang</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="programTableBody">
                            @forelse ($programs as $index => $program)
                            <tr
                                data-id="{{ $program->id_program }}"
                                data-name="{{ $program->nama_program }}"
                                data-organizer="{{ $program->penyelenggara }}"
                                data-bidang="{{ $program->bidang }}"
                                data-desc="{{ $program->deskripsi }}"
                                data-poster="{{ $program->poster ? asset('uploads/posters/' . $program->poster) : '' }}">

                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($program->poster)
                                    <img src="{{ asset('uploads/posters/' . $program->poster) }}" alt="Poster" class="table-poster-thumb">
                                    @else
                                    <div class="table-no-poster">No Image</div>
                                    @endif
                                </td>
                                <td class="program-name">
                                    <strong>{{ $program->nama_program }}</strong>
                                </td>
                                <td>{{ $program->penyelenggara }}</td>
                                <td>
                                    <span class="pm-badge-bidang bidang-{{ strtolower($program->bidang) }}">
                                        {{ $program->bidang }}
                                    </span>
                                </td>
                                <td class="text-truncate" title="{{ $program->deskripsi }}">
                                    {{ Str::limit($program->deskripsi, 50, '...') ?: '-' }}
                                </td>
                                <td>
                                    <div class="pm-action-buttons">
                                        <button type="button" class="edit-program" title="Edit Program" aria-label="Edit">
                                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                            </svg>
                                        </button>
                                        <button type="button" class="delete-program" title="Hapus Program" aria-label="Hapus">
                                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align:center; color:#94a3b8; padding: 24px;">Belum ada data program yang dibuat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <p id="programResult">Menampilkan {{ count($programs) }} program</p>
                </section>
            </main>
        </div>
    </div>

    <div class="pm-modal" id="programModal">
        <div class="pm-modal-content">
            <div class="pm-modal-header">
                <h2 id="modalTitle">Tambah Program</h2>
                <button type="button" id="closeModal">&times;</button>
            </div>

            <form id="programForm" method="POST" action="{{ route('admin.program-management.store') }}" enctype="multipart/form-data">
                @csrf
                <div id="methodContainer"></div>

                <div class="pm-form-group">
                    <label for="programName">Nama Program *</label>
                    <input type="text" name="nama_program" id="programName" required placeholder="Contoh: Kompetisi Esai Nasional 2026">
                </div>

                <div class="pm-form-group">
                    <label for="programOrganizer">Penyelenggara *</label>
                    <input type="text" name="penyelenggara" id="programOrganizer" required placeholder="Contoh: BEM Universitas Gadjah Mada">
                </div>

                <div class="pm-form-group">
                    <label for="programBidang">Bidang *</label>
                    <select name="bidang" id="programBidang" required>
                        <option value="">Pilih Bidang</option>
                        <option value="Pendidikan">Pendidikan</option>
                        <option value="Essay">Essay</option>
                        <option value="Bisnis">Bisnis</option>
                        <option value="Teknologi">Teknologi</option>
                    </select>
                </div>

                <div class="pm-form-group">
                    <label for="programDesc">Deskripsi Program</label>
                    <textarea name="deskripsi" id="programDesc" rows="3" placeholder="Jelaskan ringkasan informasi program..."></textarea>
                </div>

                <div class="pm-form-group">
                    <label for="programPoster">Poster / Gambar Lomba</label>
                    <input type="file" name="poster" id="programPoster" accept="image/*">
                    <small id="posterHint" class="form-hint">Format: JPG, PNG, WEBP (Maks. 3MB)</small>
                    <div id="currentPosterPreview" class="poster-preview-box" style="display: none;">
                        <span>Poster Saat Ini:</span>
                        <img id="previewImg" src="" alt="Preview Poster">
                    </div>
                </div>

                <div class="pm-modal-actions">
                    <button type="button" id="cancelModal">Batal</button>
                    <button type="submit" class="pm-btn-primary" id="btnSubmitModal">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <form id="deleteForm" method="POST" action="" hidden>
        @csrf
        @method('DELETE')
    </form>

</body>

</html>