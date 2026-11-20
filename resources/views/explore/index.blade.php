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
            <label>Bidang Minat</label>
            <select id="categoryFilter">
                <option value="Semua">Semua</option>
                <option value="Teknologi">Teknologi</option>
                <option value="Pendidikan">Pendidikan</option>
                <option value="Bisnis">Bisnis</option>
                <option value="Essay">Essay</option>
            </select>
        </div>
    </div>
</section>

<section class="program-grid" id="programGrid">
    @forelse ($programs as $prog)
    <div class="program-card"
        data-title="{{ Str::lower($prog->nama_program) }}"
        data-category="{{ Str::lower($prog->bidang) }}">
        <div class="card-image">
            @if ($prog->poster)
            <img src="{{ asset('uploads/posters/' . $prog->poster) }}" alt="{{ $prog->nama_program }}">
            @else
            <img src="{{ asset('images/default-program.png') }}" alt="{{ $prog->nama_program }}">
            @endif
            <span class="type-badge">{{ $prog->bidang }}</span>
        </div>
        <div class="card-content">
            <h3 class="card-title">{{ $prog->nama_program }}</h3>
            <p class="card-organizer">{{ $prog->penyelenggara }}</p>

            @if ($prog->deskripsi)
            <p class="card-desc">{{ Str::limit($prog->deskripsi, 120) }}</p>
            @endif

            <a href="#" class="detail-link">Lihat Detail &rarr;</a>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <div>
            <i class="bi bi-folder-plus"></i>
        </div>
        <h3>Belum ada program untuk saat ini!</h3>
    </div>
    @endforelse
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/explore-program.js') }}"></script>
@endpush