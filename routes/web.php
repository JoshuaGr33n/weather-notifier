<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\UserCityController;
use App\Http\Controllers\{WeatherController, NotificationController};

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user-cities', [UserCityController::class, 'index'])->name('user-cities.index');
    Route::post('/user-cities', [UserCityController::class, 'store'])->name('user-cities.store');
    Route::post('pause-notifications', [NotificationController::class, 'pauseNotifications'])->name('pauseNotifications');
    Route::post('restore-notifications', [NotificationController::class, 'restoreNotifications'])
        ->name('restoreNotifications');
    Route::get('notifications', [NotificationController::class, 'showPauseNotificationsForm'])
        ->name('notificationSettings');
});

Route::get('/dashboard', [WeatherController::class, 'showDashboard'])->name('dashboard');
