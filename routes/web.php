<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Backend\DashboardController as BackendDashboardController;
use App\Http\Controllers\Backend\MemberController as BackendMemberController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::redirect('/login', '/member/login');
Route::redirect('/admin', '/backend');

Route::middleware('guest')->group(function () {
    Route::get('/member/login', [AuthenticatedSessionController::class, 'createMember'])->name('member.login');
    Route::post('/member/login', [AuthenticatedSessionController::class, 'storeMember'])->name('member.login.store');
    Route::get('/backend/login', [AuthenticatedSessionController::class, 'createBackend'])->name('backend.login');
    Route::post('/backend/login', [AuthenticatedSessionController::class, 'storeBackend'])->name('backend.login.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/', MemberDashboardController::class)->name('dashboard');
});

Route::middleware(['auth', 'admin'])->prefix('backend')->name('backend.')->group(function () {
    Route::get('/', BackendDashboardController::class)->name('dashboard');
    Route::post('/settings', [BackendDashboardController::class, 'update'])->name('settings.update');
    Route::get('/members', [BackendMemberController::class, 'index'])->name('members.index');
    Route::get('/members/create', [BackendMemberController::class, 'create'])->name('members.create');
    Route::post('/members', [BackendMemberController::class, 'store'])->name('members.store');
    Route::get('/members/{member}/edit', [BackendMemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{member}', [BackendMemberController::class, 'update'])->name('members.update');
    Route::post('/members/{member}/password', [BackendMemberController::class, 'updatePassword'])->name('members.password.update');
});
