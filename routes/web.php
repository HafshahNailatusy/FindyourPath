<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExploreProgramController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\ProgramManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\AnnouncementController;


Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('login.attempt');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:10,1')
        ->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::view('/dashboard', 'mahasiswa.placeholder', ['title' => 'Dashboard', 'menu' => 'dashboard'])
        ->name('mahasiswa.dashboard');
    Route::view('/profile', 'mahasiswa.placeholder', ['title' => 'Profile', 'menu' => 'profile'])
        ->name('mahasiswa.profile');

    Route::get('/explore-program', [ExploreProgramController::class, 'index'])
        ->name('explore-program');

    Route::get('/find-opportunities', [OpportunityController::class, 'index'])
        ->name('opportunities.index');
    Route::post('/find-opportunities', [OpportunityController::class, 'handleRequest'])
        ->name('opportunity.handle');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('admin.announcements.index');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('admin.announcements.store');
    Route::put('/announcements/{id_announcement}', [AnnouncementController::class, 'update'])->name('admin.announcements.update');
    Route::delete('/announcements/{id_announcement}', [AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');

    Route::resource('/program-management', ProgramManagementController::class)
        ->except(['create', 'show', 'edit'])
        ->names([
            'index'   => 'admin.program-management.index',
            'store'   => 'admin.program-management.store',
            'update'  => 'admin.program-management.update',
            'destroy' => 'admin.program-management.destroy',
        ]);
});
