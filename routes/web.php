<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $roleName = DB::table('roles')->where('id', auth()->user()->role_id)->value('name');

    if ($roleName === 'SuperAdmin') {
        return redirect()->route('superadmin.dashboard');
    }

    if ($roleName === 'Admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('member.dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

    Route::get('/invite/{token}', [InvitationController::class, 'acceptForm'])->name('invitation.accept');
    Route::post('/invite/{token}', [InvitationController::class, 'acceptStore'])->name('invitation.accept.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('/urls', [UrlController::class, 'index'])->name('urls.index');
    Route::get('/urls/download', [UrlController::class, 'downloadCsv'])->name('urls.download');
    Route::post('/urls/generate-request', [UrlController::class, 'requestGenerate'])->name('urls.generate-request');
    Route::get('/s/{code}', [UrlController::class, 'resolve'])->name('urls.resolve');

    Route::prefix('/superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/companies', [SuperAdminController::class, 'companies'])->name('companies');
        Route::get('/invitations', [SuperAdminController::class, 'invitations'])->name('invitations');
        Route::get('/invitations/create', [InvitationController::class, 'superAdminCreate'])->name('invitations.create');
        Route::post('/invitations', [InvitationController::class, 'superAdminStore'])->name('invitations.store');
        Route::get('/clients', [SuperAdminController::class, 'clients'])->name('clients');
        Route::get('/stats', [SuperAdminController::class, 'stats'])->name('stats');
    });

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/team-members', [AdminController::class, 'teamMembers'])->name('admin.team-members');
    Route::get('/admin/invitations', [InvitationController::class, 'adminIndex'])->name('admin.invitations');
    Route::post('/admin/invitations', [InvitationController::class, 'adminStore'])->name('admin.invitations.store');

    Route::get('/member/dashboard', function () {
        abort_unless(DB::table('roles')->where('id', auth()->user()->role_id)->value('name') === 'Member', 403);

        return view('dashboards.member');
    })->name('member.dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
