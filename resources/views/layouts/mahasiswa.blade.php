<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - FindYourPath</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @stack('styles')
</head>

@php
    $isDetailProgram = trim($__env->yieldContent('menu')) === 'detail-program';
@endphp

<body class="{{ $isDetailProgram ? 'detail-program-layout' : '' }}">
    <div class="dashboard-container">

        @unless($isDetailProgram)
            <x-sidebar-mahasiswa :active="trim($__env->yieldContent('menu'))" />
        @endunless

        <main class="main-content">

            @unless($isDetailProgram)
                <header class="header">
                    {{-- Pertahankan seluruh isi header yang lama di sini --}}
                </header>
            @endunless

            @yield('content')

        </main>
    </div>

    @stack('scripts')
</body>