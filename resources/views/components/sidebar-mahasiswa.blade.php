@php

    $menuItems = $menuItems ?? [
        [
            'key' => 'dashboard',
            'title' => 'Dashboard',
            'url' => 'dashboard-user'
        ],
        [
            'key' => 'explore-program',
            'title' => 'Explore Program',
            'url' => 'explore-program'
        ],
        [
            'key' => 'find-opportunities',
            'title' => 'Find Opportunities',
            'url' => 'find-opportunities'
        ],
        [
            'key' => 'profile',
            'title' => 'Profile',
            'url' => 'profile'
        ],
    ];
@endphp
@vite(['resources/css/sidebarMahasiswa.css', 'resources/js/sidebarMahasiswa.js'])

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>
<aside class="fyp-sidebar" id="fypSidebar">
    <div class="sidebar-header">
        <a href="{{ route('explore-program') }}" class="sidebar-brand">
            <img src="{{ asset('images/LOGO.png') }}" alt="FindYourPath Logo" class="brand-logo">
        </a>
        <button type="button" class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Tutup menu">&times;</button>
    </div>

    <nav class="sidebar-nav" aria-label="Navigasi mahasiswa">
        <ul class="nav-list">
            @foreach ($menuItems as $item)
                <li class="nav-item">
                    <a href="{{ $item['url'] }}" class="nav-link {{ $active === $item['key'] ? 'active' : '' }}">
                        <span class="nav-title">{{ $item['title'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link logout-link logout-btn">
                <span class="nav-title">Keluar</span>
            </button>
        </form>
    </div>
</aside>
