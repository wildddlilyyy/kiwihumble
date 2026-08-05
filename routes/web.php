<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Backend\ClassShirtOrderExportController as BackendClassShirtOrderExportController;
use App\Http\Controllers\Backend\DashboardController as BackendDashboardController;
use App\Http\Controllers\Backend\MemberController as BackendMemberController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Member\ClassShirtOrderController as MemberClassShirtOrderController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::redirect('/login', '/member/login');
Route::redirect('/admin', '/backend');

Route::get('/member/login', [AuthenticatedSessionController::class, 'createMember'])->name('member.login');
Route::post('/member/login', [AuthenticatedSessionController::class, 'storeMember'])->name('member.login.store');
Route::get('/backend/login', [AuthenticatedSessionController::class, 'createBackend'])->name('backend.login');
Route::post('/backend/login', [AuthenticatedSessionController::class, 'storeBackend'])->name('backend.login.store');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::post('/member/logout', [AuthenticatedSessionController::class, 'destroyMember'])
    ->middleware('auth:member')
    ->name('member.logout');

Route::post('/backend/logout', [AuthenticatedSessionController::class, 'destroyBackend'])
    ->middleware('auth:backend')
    ->name('backend.logout');

Route::middleware(['auth:member', 'member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/', MemberDashboardController::class)->name('dashboard');
    Route::put('/', [MemberDashboardController::class, 'update'])->name('profile.update');
    Route::post('/class-shirt-orders', [MemberClassShirtOrderController::class, 'store'])->name('class-shirt-orders.store');
    Route::put('/class-shirt-orders/{order}', [MemberClassShirtOrderController::class, 'update'])->name('class-shirt-orders.update');
    Route::delete('/class-shirt-orders/{order}', [MemberClassShirtOrderController::class, 'destroy'])->name('class-shirt-orders.destroy');
});

Route::middleware(['auth:backend', 'admin'])->prefix('backend')->name('backend.')->group(function () {
    Route::get('/', BackendDashboardController::class)->name('dashboard');
    Route::post('/settings', [BackendDashboardController::class, 'update'])->name('settings.update');
    Route::get('/class-shirt-orders/export', BackendClassShirtOrderExportController::class)->name('class-shirt-orders.export');
    Route::get('/members', [BackendMemberController::class, 'index'])->name('members.index');
    Route::get('/members/create', [BackendMemberController::class, 'create'])->name('members.create');
    Route::post('/members', [BackendMemberController::class, 'store'])->name('members.store');
    Route::get('/members/{member}/edit', [BackendMemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{member}', [BackendMemberController::class, 'update'])->name('members.update');
    Route::post('/members/{member}/password', [BackendMemberController::class, 'updatePassword'])->name('members.password.update');
});
