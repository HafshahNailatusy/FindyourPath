@extends('layouts.mahasiswa')

@section('title', 'Explore Program')
@section('menu', 'explore-program')

@push('styles')
    @vite(['resources/css/explore-program.css', 'resources/js/explore-program.js'])
@endpush

@section('content')
    <section class="hero-banner">
        <div class="banner-content">
            <span class="badge-tag">Explore Program</span>
            <h2>Temukan Lomba & Beasiswa Terbaik untuk Masa Depanmu!</h2>
            <p>Jelajahi berbagai program kompetisi dan beasiswa dari dalam dan luar negeri, serta wujudkan potensi terbaikmu.</p>
        </div>
    </section>

    <section class="filter-section">
        <div class="search-box">
            <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari program berdasarkan kata kunci...">
        </div>

        <div class="select-group">
            <div class="select-wrapper">
                <label>Jenis</label>
                <select id="typeFilter">
                    <option value="Semua">Semua</option>
                    <option value="Lomba">Lomba</option>
                    <option value="Beasiswa">Beasiswa</option>
                </select>
            </div>

            <div class="select-wrapper">
                <label>Bidang Minat</label>
                <select id="categoryFilter">
                    <option value="Semua">Semua</option>
                    <option value="Teknologi">Teknologi</option>
                    <option value="Pendidikan">Pendidikan</option>
                    <option value="Bisnis">Bisnis</option>
                </select>
            </div>
        </div>
    </section>

    <section class="program-grid" id="programGrid">
        @forelse ($programs as $prog)
            <div class="program-card"
                data-title="{{ Str::lower($prog->title) }}"
                data-type="{{ Str::lower($prog->type) }}"
                data-category="{{ Str::lower($prog->category) }}">
                <div class="card-image">
                    <img src="{{ $prog->image }}" alt="{{ $prog->title }}">
                    <span class="type-badge {{ Str::lower($prog->type) }}">{{ $prog->type }}</span>
                    <button type="button" class="bookmark-btn" title="Simpan Bookmark">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </button>
                </div>
                <div class="card-content">
                    <h3 class="card-title">{{ $prog->title }}</h3>
                    <p class="card-organizer">{{ $prog->organizer }}</p>

                    <div class="card-meta">
                        <span class="meta-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            {{ $prog->location }}
                        </span>
                        <span class="meta-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            {{ $prog->date }}
                        </span>
                    </div>

                    <p class="card-desc">{{ $prog->desc }}</p>

                    {{-- Halaman detail program belum dikonversi --}}
                    <a href="#" class="detail-link">Lihat Detail &rarr;</a>
                </div>
            </div>
        @empty
            <div class="empty-state">Belum ada program yang tersedia saat ini.</div>
        @endforelse
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/explore-program.js') }}"></script>
@endpush
