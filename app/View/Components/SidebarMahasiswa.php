<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarMahasiswa extends Component
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
                'url' => route('mahasiswa.dashboard'),
            ],
            [
                'key' => 'explore-program',
                'title' => 'Explore Program',
                'url' => route('explore-program'),
            ],
            [
                'key' => 'find-opportunities',
                'title' => 'Find Opportunities',
                'url' => route('opportunities.index'),
            ],
            [
                'key' => 'profile',
                'title' => 'Profile',
                'url' => route('mahasiswa.profile'),
            ],
        ];
    }

    public function render(): View|Closure|string
    {
        return view('components.sidebar-mahasiswa');
    }
}
