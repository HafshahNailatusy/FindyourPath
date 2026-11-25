<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExploreProgramController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\ProgramManagementController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\ScheduleManagementController;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\DetailProgramController;
use App\Http\Controllers\ProfileUserController;
use App\Http\Middleware\PreventBackHistory;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('login.attempt');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:10,1')
        ->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:mahasiswa', PreventBackHistory::class])->group(function () {

    Route::get('/dashboard', [DashboardUserController::class, 'index'])
        ->name('mahasiswa.dashboard');

    Route::get('/dashboard-user', [DashboardUserController::class, 'index']);

    Route::get('/profile', [ProfileUserController::class, 'index'])
        ->name('mahasiswa.profile');

    Route::put('/profile', [ProfileUserController::class, 'update'])
        ->name('mahasiswa.profile.update');

    Route::get('/program/{id}', [DetailProgramController::class, 'show'])
        ->whereNumber('id')
        ->name('programs.show');

    Route::get('/explore-program', [ExploreProgramController::class, 'index'])
        ->name('explore-program');

    Route::get('/find-opportunities', [OpportunityController::class, 'index'])
        ->name('opportunities.index');

    Route::post('/find-opportunities', [OpportunityController::class, 'handleRequest'])
        ->name('opportunity.handle');

    Route::prefix('registrations')->name('registrations.')->group(function () {

        Route::get('/', [RegistrationController::class, 'index'])
            ->name('index');

        Route::get('/create/{programId}', [RegistrationController::class, 'create'])
            ->whereNumber('programId')
            ->name('create');

        Route::post('/create/{programId}', [RegistrationController::class, 'store'])
            ->whereNumber('programId')
            ->name('store');

        Route::get('/{registrationId}/edit', [RegistrationController::class, 'edit'])
            ->whereNumber('registrationId')
            ->name('edit');

        Route::put('/{registrationId}', [RegistrationController::class, 'update'])
            ->whereNumber('registrationId')
            ->name('update');

        Route::patch('/{registrationId}/cancel', [RegistrationController::class, 'cancel'])
            ->whereNumber('registrationId')
            ->name('cancel');
    });
});

Route::middleware(['auth', 'role:admin', PreventBackHistory::class])
    ->prefix('admin')
    ->group(function () {

        Route::get('/dashboard', [DashboardAdminController::class, 'index'])
            ->name('admin.dashboard');

        Route::get('/announcements', [AnnouncementController::class, 'index'])
            ->name('admin.announcements.index');

        Route::post('/announcements', [AnnouncementController::class, 'store'])
            ->name('admin.announcements.store');

        Route::put('/announcements/{id_announcement}', [AnnouncementController::class, 'update'])
            ->name('admin.announcements.update');

        Route::delete('/announcements/{id_announcement}', [AnnouncementController::class, 'destroy'])
            ->name('admin.announcements.destroy');

        Route::resource('/program-management', ProgramManagementController::class)
            ->except(['create', 'show', 'edit'])
            ->names([
                'index'   => 'admin.program-management.index',
                'store'   => 'admin.program-management.store',
                'update'  => 'admin.program-management.update',
                'destroy' => 'admin.program-management.destroy',
            ]);

        Route::get('/profile', [AdminProfileController::class, 'index'])
            ->name('admin.profile');

        Route::post('/profile', [AdminProfileController::class, 'update'])
            ->name('admin.profile.update');

        Route::put('/profile', [AdminProfileController::class, 'update'])
            ->name('admin.profile.update');

        Route::delete('/profile/avatar', [AdminProfileController::class, 'deleteAvatar'])
            ->name('admin.profile.avatar.delete');

        Route::get('/schedule', [ScheduleManagementController::class, 'index'])
            ->name('admin.schedule.index');

        Route::post('/schedule', [ScheduleManagementController::class, 'store'])
            ->name('admin.schedule.store');

        Route::put('/schedule/{id}', [ScheduleManagementController::class, 'update'])
            ->name('admin.schedule.update');

        Route::delete('/schedule/{id}', [ScheduleManagementController::class, 'destroy'])
            ->name('admin.schedule.destroy');
    });