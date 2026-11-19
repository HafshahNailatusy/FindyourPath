<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExploreProgramController;
use App\Http\Controllers\OpportunityController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::view('/dashboard', 'mahasiswa.placeholder', ['title' => 'Dashboard', 'menu' => 'dashboard'])
        ->name('mahasiswa.dashboard');
    Route::view('/profile', 'mahasiswa.placeholder', ['title' => 'Profile', 'menu' => 'profile'])
        ->name('mahasiswa.profile');

    Route::get('/explore-program', [ExploreProgramController::class, 'index'])
        ->name('explore-program');

    Route::prefix('find-opportunities')->name('opportunities.')->group(function () {
        $type = 'goals|projects|achievements|career';

        Route::get('/', [OpportunityController::class, 'index'])->name('index');
        Route::post('/{type}', [OpportunityController::class, 'store'])
            ->where('type', $type)->name('store');
        Route::put('/{type}/{id}', [OpportunityController::class, 'update'])
            ->where(['type' => $type, 'id' => '[0-9]+'])->name('update');
        Route::delete('/{type}/{id}', [OpportunityController::class, 'destroy'])
            ->where(['type' => $type, 'id' => '[0-9]+'])->name('destroy');

        Route::get('/find-opportunities', [OpportunityController::class, 'index'])->name('opportunity.index');
        Route::post('/find-opportunities', [OpportunityController::class, 'handleRequest'])->name('opportunity.handle');
    });
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::view('/dashboard', 'admin.placeholder')->name('admin.dashboard');
});
