<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarAdmin extends Component
{
    public string $active;
    public array $menuItems;

    public function __construct(string $active = '', string $activeMenu = '')
    {
        $this->active = $active ?: $activeMenu;
        $this->menuItems = [
            [
                'key' => 'dashboard',
                'title' => 'Dashboard',
                'url' => route('admin.dashboard'),
            ],
            [
                'key' => 'program',
                'title' => 'Program',
                'url' => route('admin.program-management.index'),
            ],
            [
                'key' => 'announcement',
                'title' => 'Announcement',
                'url' => route('admin.announcements.index'),
            ],
            [
                'key' => 'schedule',
                'title' => 'Schedule',
                'url' => route('admin.schedule.index'),
            ],
            [
                'key' => 'profile',
                'title' => 'Profile',
                'url' => route('admin.profile'),
            ],
        ];
    }

    public function render(): View|Closure|string
    {
        return view('components.sidebar-admin');
    }
}
