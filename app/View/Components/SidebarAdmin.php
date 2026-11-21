<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarAdmin extends Component
{
    public string $active;
    public array $menuItems;

    public function __construct(string $active = '')
    {
        $this->active = $active;
        $this->menuItems = [
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
    }

    public function render(): View|Closure|string
    {
        return view('components.sidebar-admin');
    }
}
