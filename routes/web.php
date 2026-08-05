<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Customer;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ─────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ─── Auth Routes ───────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// ─── Admin Routes ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('branches', Admin\BranchController::class);
        Route::resource('barbers', Admin\BarberController::class);
        Route::resource('services', Admin\ServiceController::class)->except(['show', 'create']);
        Route::get('services/create', [Admin\ServiceController::class, 'create'])->name('services.create');

        // ── Antrean: list & detail ─────────────────────────────────────────
        Route::get('queues', [Admin\QueueController::class, 'index'])->name('queues.index');
        Route::get('queues/{queue}', [Admin\QueueController::class, 'show'])->name('queues.show');

        // ── Kelola Antrean (board per barber) ─────────────────────────────
        Route::get('manage', [Admin\QueueController::class, 'manage'])->name('queues.manage');
        Route::get('manage/poll', [Admin\QueueController::class, 'poll'])->name('queues.poll');
        Route::post('queues/{queue}/call', [Admin\QueueController::class, 'call'])->name('queues.call');
        Route::post('queues/{queue}/complete', [Admin\QueueController::class, 'complete'])->name('queues.complete');
        Route::post('queues/{queue}/skip', [Admin\QueueController::class, 'skip'])->name('queues.skip');

        // ── Loket Check-in ────────────────────────────────────────────────
        Route::get('checkin', [Admin\CheckinController::class, 'index'])->name('checkin.index');
        Route::post('checkin/search', [Admin\CheckinController::class, 'search'])->name('checkin.search');
        Route::get('checkin/{token}', [Admin\CheckinController::class, 'confirm'])->name('checkin.confirm');
        Route::post('checkin/{queue}/validate', [Admin\CheckinController::class, 'validate_checkin'])->name('checkin.validate');
    });

// ─── QR Scan Check-in (Customer scans admin's QR) ─────────────────────────
// Must be outside auth middleware so unauthenticated users get redirected to login
// Laravel's Authenticate middleware will redirect back here after login
Route::get('customer/checkin/{branch}', [Customer\QueueController::class, 'scanCheckin'])
    ->middleware(['auth', 'role:customer'])
    ->name('customer.checkin.scan');

// ─── Customer Routes ───────────────────────────────────────────────────────
Route::middleware(['auth', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        Route::get('dashboard', [Customer\QueueController::class, 'dashboard'])->name('dashboard');

        Route::get('branches/{branch}/queue/take', [Customer\QueueController::class, 'take'])->name('queue.take');
        Route::post('branches/{branch}/queue', [Customer\QueueController::class, 'store'])->name('queue.store');
        Route::get('queue/{queue}/status', [Customer\QueueController::class, 'status'])->name('queue.status');
        Route::get('queue/{queue}/poll', [Customer\QueueController::class, 'poll'])->name('queue.poll');

        // Push notification subscription
        Route::post('push/subscribe',   [Customer\PushSubscriptionController::class, 'store'])->name('push.subscribe');
        Route::post('push/unsubscribe', [Customer\PushSubscriptionController::class, 'destroy'])->name('push.unsubscribe');
    });
