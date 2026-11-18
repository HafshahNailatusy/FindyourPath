@extends('layouts.mahasiswa')

@section('title', 'Find Opportunities')
@section('menu', 'find-opportunities')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/find-opportunity.css') }}">
@endpush

@php
    $sections = [
        'goals' => [
            'title' => 'My Goals & Skills',
            'subtitle' => 'Catat target dan kemampuan yang ingin kamu kembangkan.',
            'icon' => '🎯',
            'records' => $goals,
        ],
        'projects' => [
            'title' => 'My Projects',
            'subtitle' => 'Dokumentasikan proyek dan karya yang pernah kamu buat.',
            'icon' => '💻',
            'records' => $projects,
        ],
        'achievements' => [
            'title' => 'My Achievements',
            'subtitle' => 'Simpan pencapaian dan pengalaman berhargamu.',
            'icon' => '🏆',
            'records' => $achievements,
        ],
        'career' => [
            'title' => 'Career Roadmap',
            'subtitle' => 'Rencanakan langkah menuju karier impianmu.',
            'icon' => '🚀',
            'records' => $careerPlans,
        ],
    ];

    $totalRecords = $goals->count() + $projects->count() + $achievements->count() + $careerPlans->count();
    $completedItems = $goals->filter->isCompleted()->count() + $projects->filter->isCompleted()->count();
    $activeTab = array_key_exists(session('tab'), $sections) ? session('tab') : 'goals';

    // Status di database berupa teks bebas ("In Progress"), CSS memakai planned / in_progress / completed.
    $statusClass = fn (?string $s) => match (strtolower((string) $s)) {
        'in progress' => 'in_progress',
        'completed' => 'completed',
        default => 'planned',
    };

    // Kalau form gagal validasi, modal dibuka lagi dengan isian sebelumnya.
    $reopen = session('reopen');
    $reopenConfig = $reopen ? [
        'type' => $reopen['type'],
        'action' => $reopen['id']
            ? route('opportunities.update', [$reopen['type'], $reopen['id']])
            : route('opportunities.store', $reopen['type']),
        'method' => $reopen['id'] ? 'PUT' : 'POST',
        'record' => collect(old())->except(['_token', '_method', 'certificate_file'])->all(),
    ] : null;
@endphp

@section('content')
    <div class="page-content">

        @if (session('success'))
            <div class="flash success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="flash error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="opportunity-hero">
            <div class="hero-text">
                <span class="hero-badge">Personal Growth Planner</span>
                <h2>Kenali Potensimu, Bangun Masa Depanmu!</h2>
                <p>Catat perkembangan dirimu, dokumentasikan pencapaian, dan susun langkah untuk meraih karier impianmu.</p>
            </div>
            <div class="hero-decoration">✦</div>
        </section>

        <section class="summary-grid">
            <div class="summary-card">
                <div class="summary-icon blue">🎯</div>
                <div>
                    <span>Total Goals & Skills</span>
                    <h3>{{ $goals->count() }}</h3>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon teal">💻</div>
                <div>
                    <span>Total Projects</span>
                    <h3>{{ $projects->count() }}</h3>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon purple">🏆</div>
                <div>
                    <span>Achievements</span>
                    <h3>{{ $achievements->count() }}</h3>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon orange">🚀</div>
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
            <span class="total-badge">{{ $totalRecords }} Data Tersimpan</span>
        </section>

        <nav class="opportunity-tabs">
            @foreach ($sections as $key => $section)
                <button type="button" class="tab-btn {{ $key === $activeTab ? 'active' : '' }}" data-tab="{{ $key }}">
                    {{ $section['title'] }}
                </button>
            @endforeach
        </nav>

        @foreach ($sections as $key => $section)
            <section class="tab-content {{ $key === $activeTab ? 'active' : '' }}" id="tab-{{ $key }}">

                <div class="content-heading">
                    <div>
                        <h3>{{ $section['title'] }}</h3>
                        <p>{{ $section['subtitle'] }}</p>
                    </div>
                    <button type="button" class="btn-add" data-create="{{ $key }}"
                        data-action="{{ route('opportunities.store', $key) }}">
                        + Tambah Data
                    </button>
                </div>

                <div class="record-grid">
                    @forelse ($section['records'] as $record)
                        @php
                            $recordTitle = match ($key) {
                                'projects' => $record->project_name,
                                'career' => $record->career_title,
                                default => $record->title,
                            };

                            $status = in_array($key, ['projects', 'career']) ? $record->status : null;

                            $date = match ($key) {
                                'achievements' => $record->achievement_date,
                                'goals', 'career' => $record->target_date,
                                default => null,
                            };

                            $subtitle = match ($key) {
                                'achievements' => $record->organizer,
                                'projects' => $record->technologies,
                                'goals' => collect([$record->current_level, $record->target_level])->filter()->implode(' → '),
                                default => null,
                            };
                        @endphp

                        <article class="record-card">
                            <div class="record-top">
                                <span class="record-category">
                                    {{ $section['icon'] }}
                                    {{ $key === 'career' ? $section['title'] : ($record->category ?: $section['title']) }}
                                </span>

                                @if ($status)
                                    <span class="status-badge {{ $statusClass($status) }}">{{ $status }}</span>
                                @endif
                            </div>

                            <h4>{{ $recordTitle }}</h4>

                            @if ($subtitle)
                                <p class="record-subtitle">{{ $subtitle }}</p>
                            @endif

                            @if ($key === 'goals')
                                <div class="progress-track" title="Progress {{ $record->progress }}%">
                                    <div class="progress-bar" style="width: {{ min(100, max(0, (int) $record->progress)) }}%"></div>
                                </div>
                            @endif

                            <p class="record-description">{{ $record->description ?: 'Belum ada deskripsi.' }}</p>

                            @if ($key === 'projects' && $record->project_url)
                                <p class="record-extra"><a href="{{ $record->project_url }}" target="_blank" rel="noopener noreferrer">Buka link proyek ↗</a></p>
                            @endif

                            @if ($key === 'achievements' && $record->certificate_file)
                                <p class="record-extra"><a href="{{ $record->certificate_url }}" target="_blank" rel="noopener noreferrer">Lihat sertifikat ↗</a></p>
                            @endif

                            <div class="record-date">
                                @if ($date)
                                    <span>◷ {{ $date->format('d M Y') }}</span>
                                @endif
                            </div>

                            <div class="record-actions">
                                <button type="button" class="btn-edit"
                                    data-edit="{{ $key }}"
                                    data-action="{{ route('opportunities.update', [$key, $record->id]) }}"
                                    data-record="{{ $record->toJson() }}">
                                    Edit
                                </button>

                                <form method="POST" action="{{ route('opportunities.destroy', [$key, $record->id]) }}"
                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Hapus</button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="empty-state">
                            <div>✦</div>
                            <h3>Belum ada data</h3>
                            <p>Mulai catat perkembanganmu dan bangun perjalanan masa depanmu!</p>
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
                <button type="button" id="closeModal" class="modal-close">&times;</button>
            </div>

            {{-- action & _method diisi oleh find-opportunity.js (create = POST, edit = PUT) --}}
            <form method="POST" action="" id="recordForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div id="formFields"></div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" id="cancelModal">Batal</button>
                    <button type="submit" class="btn-save">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.opportunityReopen = @json($reopenConfig);
    </script>
    <script src="{{ asset('js/find-opportunity.js') }}"></script>
@endpush
