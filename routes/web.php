<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;

// الصفحة الرئيسية
Route::get('/', function () {
    return redirect()->route('login');
});

// مسارات المصادقة
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.submit');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// مسارات لوحة التحكم المحمية
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // لوحة التحكم الرئيسية
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // مسارات المستخدمين
    Route::resource('users', UserController::class);
    Route::get('users/{id}/statistics', [UserController::class, 'statistics'])->name('users.statistics');
    Route::post('users/{id}/update-vip', [UserController::class, 'updateVip'])->name('users.update-vip');
    Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::get('users-statistics', [UserController::class, 'globalStatistics'])->name('users.global-statistics');
    Route::get('bulk-actions', [UserController::class, 'bulkActions'])->name('users.bulk-actions');
    Route::post('bulk-actions/execute', [UserController::class, 'executeBulkAction'])->name('users.bulk-actions.execute');
});

