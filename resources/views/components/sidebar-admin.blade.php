@php
    $menuItems = [
        [
            'key' => 'dashboard',    
            'title' => 'Dashboard',    
            'url' => url('/admin/dashboard')
        ],
        [
            'key' => 'program',
            'title' => 'Program',
            'url' => url('/admin/program-management')
        ],
        [
            'key' => 'announcement',
            'title' => 'Announcement',
            'url' => url('/admin/announcements')
        ],
        [
            'key' => 'schedule',
            'title' => 'Schedule',
            'url' => url('/admin/schedule')
        ],
        [
            'key' => 'profile',
            'title' => 'Profile',
            'url' => url('/admin/profile')
        ],
    ];
@endphp

@vite(['resources/css/sidebarAdmin.css', 'resources/js/sidebarAdmin.js'])

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>
<aside class="fyp-sidebar" id="fypSidebar">
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <img src="{{ asset('images/LOGO.png') }}" alt="FindYourPath Logo" class="brand-logo">
        </a>
        <button type="button" class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Tutup Menu">&times;</button>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-list">
            @foreach ($menuItems as $item)
                <li class="nav-item">
                    <a href="{{ $item['url'] }}" 
                       class="nav-link {{ ($activeMenu ?? '') === $item['key'] ? 'active' : '' }}">
                        <span class="nav-title">{{ $item['title'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link logout-link" style="background:none; border:none; width:100%; text-align:left; cursor:pointer;">
                <span class="nav-title">Keluar</span>
            </button>
        </form>
    </div>
</aside>