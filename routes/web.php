<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Customer;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PushSubscriptionController;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ─────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/live-status', [HomeController::class, 'live'])->name('home.live');

// ─── Health Check (Railway) ─────────────────────────────────────────────────
Route::get('/health', function () {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $db = 'ok';
    } catch (\Throwable $e) {
        return response()->json(['status' => 'degraded', 'app' => config('app.name'), 'db' => 'down'], 503);
    }
    return response()->json(['status' => 'ok', 'app' => config('app.name'), 'db' => $db]);
});

// ─── Auth Routes ───────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store'])->middleware('throttle:10,1');
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:10,1');

    // ── Lupa Password via OTP 6 digit (WA prioritas + email cadangan) ────
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('throttle:5,1')->name('password.email');
    Route::get('verify-otp', [PasswordResetLinkController::class, 'showOtpForm'])->name('password.otp');
    Route::post('verify-otp', [PasswordResetLinkController::class, 'verifyOtp'])->middleware('throttle:10,1')->name('password.otp.verify');
    Route::get('new-password', [PasswordResetLinkController::class, 'showNewPasswordForm'])->name('password.new');
    Route::post('new-password', [PasswordResetLinkController::class, 'storeNewPassword'])->middleware('throttle:10,1')->name('password.new.store');
    // Tautan reset lama (nonaktif — dipertahankan agar URL lama tidak 404).
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// ─── Verifikasi Email Pendaftaran (OTP 6 digit via Brevo) ───────────────
// notice/send/verify: untuk user baru (guest pasca-daftar) maupun user
// login yang belum verifikasi. Throttle kirim 3/menit, tebak 10/menit.
Route::get('verify-email', [EmailVerificationController::class, 'notice'])->name('verification.notice');
Route::post('verify-email/send', [EmailVerificationController::class, 'send'])->middleware('throttle:3,1')->name('verification.send');
Route::post('verify-email', [EmailVerificationController::class, 'verify'])->middleware('throttle:10,1')->name('verification.verify');

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // ── Profil ───────────────────────────────────────────────────────────
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
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

        // ── Rekap Kinerja ─────────────────────────────────────────────
        Route::get('rekap', [Admin\RekapController::class, 'index'])->name('rekap.index');

        // ── Walk-in Queue (tanpa akun customer) — harus sebelum queues/{queue} ──
        Route::get('queues/walkin', [Admin\WalkinQueueController::class, 'create'])->name('queues.walkin');
        Route::post('queues/walkin', [Admin\WalkinQueueController::class, 'store'])->name('queues.walkin.store');

        Route::get('queues/{queue}', [Admin\QueueController::class, 'show'])->name('queues.show');

        // ── Kelola Antrean (board per barber) ─────────────────────────────
        Route::get('manage', [Admin\QueueController::class, 'manage'])->name('queues.manage');
        Route::get('manage/poll', [Admin\QueueController::class, 'poll'])->name('queues.poll');
        Route::post('queues/{queue}/call', [Admin\QueueController::class, 'call'])->name('queues.call');
        Route::post('queues/{queue}/complete', [Admin\QueueController::class, 'complete'])->name('queues.complete');
        Route::post('queues/{queue}/skip', [Admin\QueueController::class, 'skip'])->name('queues.skip');
        Route::get('notifications/poll', [Admin\QueueController::class, 'notificationPoll'])->name('notifications.poll');

        // ── Loket Check-in ────────────────────────────────────────────────
        Route::get('checkin', fn() => redirect()->route('admin.queues.manage'))->name('checkin.index');
        Route::post('checkin/search', [Admin\CheckinController::class, 'search'])->name('checkin.search');
        Route::get('checkin/{token}', [Admin\CheckinController::class, 'confirm'])->name('checkin.confirm');
        Route::post('checkin/{queue}/validate', [Admin\CheckinController::class, 'validate_checkin'])->name('checkin.validate');
    });

// ─── QR Scan Check-in (Customer scans admin's QR) ─────────────────────────
// Must be outside auth middleware so unauthenticated users get redirected to login
// Laravel's Authenticate middleware will redirect back here after login
Route::get('customer/checkin/{branch}', [Customer\QueueController::class, 'scanCheckin'])
    ->middleware(['auth', 'role:customer', 'verified.email'])
    ->name('customer.checkin.scan');

// ─── Customer Routes ───────────────────────────────────────────────────────
// verified.email: email wajib terverifikasi sebelum bisa antre.
Route::middleware(['auth', 'role:customer', 'verified.email'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        Route::get('dashboard', [Customer\QueueController::class, 'dashboard'])->name('dashboard');

        Route::get('branches/{branch}/queue/take', [Customer\QueueController::class, 'take'])->name('queue.take');
        Route::post('branches/{branch}/queue', [Customer\QueueController::class, 'store'])->name('queue.store');

        // Static routes MUST be before {queue} wildcard routes
        Route::get('queue/history', [Customer\QueueController::class, 'history'])->name('queue.history');

        Route::get('queue/{queue}/status', [Customer\QueueController::class, 'status'])->name('queue.status');
        Route::get('queue/{queue}/poll', [Customer\QueueController::class, 'poll'])->name('queue.poll');

        // Push notifications
        Route::post('push/subscribe',   [PushSubscriptionController::class, 'subscribe'])->name('push.subscribe');
        Route::post('push/unsubscribe',  [PushSubscriptionController::class, 'unsubscribe'])->name('push.unsubscribe');
        Route::post('push/check',       [PushSubscriptionController::class, 'check'])->name('push.check');
        Route::post('push/test',        [PushSubscriptionController::class, 'test'])->name('push.test');

        // Diagnosa notifikasi (sementara — untuk debug HP)
        Route::get('push/diagnose', function () {
            return view('customer.push-diagnose');
        })->name('push.diagnose');
    });
