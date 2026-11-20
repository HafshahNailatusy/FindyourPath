<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Find Opportunities - FindYourPath</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/sidebarMahasiswa.css', 'resources/css/find-opportunity.css', ])
</head>

<body data-error="{{ $errorMessage }}">

    <div class="dashboard-container">

        @include('components.sidebar-mahasiswa', ['active' => 'find-opportunities'])

        <main class="main-content">

            <header class="header">
                <div class="header-left">
                    <button class="hamburger-btn" id="sidebarToggleBtn" aria-label="Buka menu">☰</button>
                    <h1 class="header-title">Find Opportunities</h1>
                </div>

                <div class="header-right">
                    <button class="header-notif" aria-label="Notifikasi">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.70006 15.7506C7.83172 15.9786 8.02108 16.168 8.24911 16.2996C8.47713 16.4313 8.73579 16.5006 8.99909 16.5006C9.26239 16.5006 9.52105 16.4313 9.74907 16.2996C9.9771 16.168 10.1665 15.9786 10.2981 15.7506M2.44596 11.4947C2.34798 11.6021 2.28332 11.7357 2.25984 11.8792C2.23637 12.0226 2.25509 12.1698 2.31373 12.3029C2.37237 12.4359 2.46841 12.549 2.59015 12.6285C2.71189 12.7079 2.8541 12.7502 2.99947 12.7504H14.9997C15.1451 12.7504 15.2873 12.7082 15.4091 12.6289C15.5309 12.5496 15.6271 12.4366 15.6859 12.3037C15.7447 12.1708 15.7636 12.0236 15.7403 11.8801C15.717 11.7366 15.6525 11.603 15.5547 11.4955C14.5572 10.4672 13.4997 9.37432 13.4997 5.99978C13.4997 4.80621 13.0256 3.66152 12.1816 2.81754C11.3377 1.97355 10.1931 1.4994 8.99959 1.4994C7.80609 1.4994 6.66148 1.97355 5.81755 2.81754C4.97361 3.66152 4.4995 4.80621 4.4995 5.99978C4.4995 9.37432 3.44123 10.4672 2.44596 11.4947Z" stroke="#27566A" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </button>

                    <div class="header-user">
                        <div class="header-avatar">
                            {{ $userInitial }}
                        </div>

                        <div class="header-userinfo">
                            <span class="header-username">
                                {{ $userName }}
                            </span>
                            <span class="header-userrole">Mahasiswa</span>
                        </div>
                    </div>
                </div>
            </header>

            <div class="page-content">

                <section class="opportunity-hero">
                    <div class="hero-text">
                        <span class="hero-badge">Personal Growth Planner</span>
                        <h2>Kenali Potensimu, Bangun Masa Depanmu!</h2>
                        <p>
                            Catat perkembangan dirimu, dokumentasikan pencapaian,
                            dan susun langkah untuk meraih karier impianmu.
                        </p>
                    </div>

                    <div class="hero-decoration">
                        <i class="bi bi-compass"></i>
                    </div>
                </section>

                <section class="summary-grid">
                    <div class="summary-card">
                        <div class="summary-icon blue">
                            <i class="bi bi-bullseye"></i>
                        </div>
                        <div>
                            <span>Total Goals & Skills</span>
                            <h3>{{ $goalsCount }}</h3>
                        </div>
                    </div>

                    <div class="summary-card">
                        <div class="summary-icon teal">
                            <i class="bi bi-folder2-open"></i>
                        </div>
                        <div>
                            <span>Total Projects</span>
                            <h3>{{ $projectsCount }}</h3>
                        </div>
                    </div>

                    <div class="summary-card">
                        <div class="summary-icon purple">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <div>
                            <span>Achievements</span>
                            <h3>{{ $achievementsCount }}</h3>
                        </div>
                    </div>

                    <div class="summary-card">
                        <div class="summary-icon orange">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <span>Completed Items</span>
                            <h3>{{ $completedItems }}</h3>
                        </div>
                    </div>
                </section>

                <section class="section-heading">
                    <div>
                        <h2>My Personal Development</h2>
                        <p>Semua perjalanan dan perkembanganmu dalam satu tempat.</p>
                    </div>

                    <span class="total-badge">
                        {{ $totalRecords }} Data Tersimpan
                    </span>
                </section>

                <nav class="opportunity-tabs">
                    @foreach ($sections as $key => $section)
                    <button
                        type="button"
                        class="tab-btn {{ $key === $activeTab ? 'active' : '' }}"
                        data-tab="{{ $key }}">
                        {{ $section['title'] }}
                    </button>
                    @endforeach
                </nav>

                @foreach ($sections as $key => $section)
                <section
                    class="tab-content {{ $key === $activeTab ? 'active' : '' }}"
                    id="tab-{{ $key }}">

                    <div class="content-heading">
                        <div>
                            <h3>{{ $section['title'] }}</h3>
                            <p>{{ $section['subtitle'] }}</p>
                        </div>

                        <button
                            type="button"
                            class="btn-add"
                            data-create="{{ $key }}"
                            data-action="{{ route('opportunity.handle') }}">
                            + Tambah Data
                        </button>
                    </div>

                    <div class="record-grid">
                        @forelse ($section['records'] as $record)
                        <article class="record-card">

                            <div class="record-top">
                                <span class="record-category">
                                    {{ $record['display_category'] }}
                                </span>

                                @if ($record['has_status'])
                                <span class="status-badge {{ $record['status_class'] }}">
                                    {{ $record['status_label'] }}
                                </span>
                                @endif
                            </div>

                            <h4>
                                {{ $record['display_title'] }}
                            </h4>

                            @if (!empty($record['organizer']))
                            <p class="record-subtitle">
                                {{ $record['organizer'] }}
                            </p>
                            @endif

                            <p class="record-description">
                                {{ $record['description'] ?? 'Belum ada deskripsi.' }}
                            </p>

                            <div class="record-date">
                                @if ($record['display_date'])
                                <span>◷ {{ $record['display_date'] }}</span>
                                @endif
                            </div>

                            <div class="record-actions">

                                <button
                                    type="button"
                                    class="btn-edit"
                                    data-edit="{{ $key }}"
                                    data-action="{{ route('opportunity.handle') }}"
                                    data-record="{{ $record['json_payload'] }}">
                                    Edit
                                </button>

                                <form method="POST"
                                    action="{{ route('opportunity.handle') }}"
                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">

                                    @csrf

                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="type" value="{{ $key }}">
                                    <input type="hidden" name="id" value="{{ $record['id'] }}">

                                    <button type="submit" class="btn-delete">
                                        Hapus
                                    </button>

                                </form>

                            </div>

                        </article>
                        @empty
                        <div class="empty-state">
                            <div>
                                <i class="bi bi-folder-plus"></i>
                            </div>
                            <h3>Belum ada data</h3>
                            <p>
                                Mulai catat perkembanganmu dan bangun
                                perjalanan masa depanmu!
                            </p>
                        </div>
                        @endforelse
                    </div>

                </section>
                @endforeach

            </div>

        </main>

    </div>

    <div class="modal-overlay" id="recordModal">

        <div class="modal-card">

            <div class="modal-header">
                <div>
                    <span class="modal-label">PERSONAL DEVELOPMENT</span>
                    <h2 id="modalTitle">Tambah Data</h2>
                </div>

                <button type="button" id="closeModal" class="modal-close">
                    &times;
                </button>
            </div>

            <form method="POST" action="{{ route('opportunity.handle') }}" id="recordForm" enctype="multipart/form-data">

                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <input type="hidden" name="action" id="formAction" value="create">
                <input type="hidden" name="type" id="formType">
                <input type="hidden" name="id" id="formId">

                <div id="formFields"></div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" id="cancelModal">
                        Batal
                    </button>

                    <button type="submit" class="btn-save">
                        Simpan Data
                    </button>
                </div>

            </form>

        </div>

    </div>
    @vite('resources/js/find-opportunity.js')
</body>

</html>