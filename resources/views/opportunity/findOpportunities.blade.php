@extends('layouts.mahasiswa')

@section('title', 'Find Opportunities')
@section('menu', 'find-opportunities')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/find-opportunity.css'])
@endpush

@section('content')
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
@endsection

@push('scripts')
    @if (!empty($errorMessage))
        <script>
            document.body.dataset.error = "{{ $errorMessage }}";
        </script>
    @endif
    @vite('resources/js/find-opportunity.js')
@endpush