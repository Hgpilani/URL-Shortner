<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UrlController;
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
});

Route::middleware('auth')->group(function () {
    Route::get('/urls', [UrlController::class, 'index'])->name('urls.index');
    Route::get('/s/{code}', [UrlController::class, 'resolve'])->name('urls.resolve');

    Route::get('/superadmin/dashboard', function () {
        abort_unless(DB::table('roles')->where('id', auth()->user()->role_id)->value('name') === 'SuperAdmin', 403);

        return view('dashboards.superadmin');
    })->name('superadmin.dashboard');

    Route::get('/admin/dashboard', function () {
        abort_unless(DB::table('roles')->where('id', auth()->user()->role_id)->value('name') === 'Admin', 403);

        return view('dashboards.admin');
    })->name('admin.dashboard');

    Route::get('/member/dashboard', function () {
        abort_unless(DB::table('roles')->where('id', auth()->user()->role_id)->value('name') === 'Member', 403);

        return view('dashboards.member');
    })->name('member.dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
