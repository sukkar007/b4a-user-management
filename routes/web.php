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


// === مسارات إدارة الوكالات ===
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // مسارات الوكالات
    Route::prefix('agencies')->name('agencies.')->group(function () {
        
        // الصفحة الرئيسية لإدارة الوكالات
        Route::get('/', [App\Http\Controllers\Admin\AgencyController::class, 'index'])->name('index');
        
        // عرض تفاصيل وكالة محددة
        Route::get('/{hostId}', [App\Http\Controllers\Admin\AgencyController::class, 'show'])->name('show');
        
        // إنشاء وكالة جديدة
        Route::get('/create/new', [App\Http\Controllers\Admin\AgencyController::class, 'create'])->name('create');
        Route::post('/create/store', [App\Http\Controllers\Admin\AgencyController::class, 'store'])->name('store');
        
        // إدارة الدعوات
        Route::get('/invitations/manage', [App\Http\Controllers\Admin\AgencyController::class, 'invitations'])->name('invitations');
        Route::post('/invitations/{invitationId}/accept', [App\Http\Controllers\Admin\AgencyController::class, 'acceptInvitation'])->name('invitations.accept');
        Route::post('/invitations/{invitationId}/decline', [App\Http\Controllers\Admin\AgencyController::class, 'declineInvitation'])->name('invitations.decline');
        Route::post('/invitations/send', [App\Http\Controllers\Admin\AgencyController::class, 'sendInvitation'])->name('invitations.send');
        
        // إدارة الأعضاء
        Route::post('/members/{memberId}/remove', [App\Http\Controllers\Admin\AgencyController::class, 'removeMember'])->name('members.remove');
        Route::post('/members/{memberId}/update-level', [App\Http\Controllers\Admin\AgencyController::class, 'updateMemberLevel'])->name('members.update-level');
        Route::post('/members/{memberId}/update-earnings', [App\Http\Controllers\Admin\AgencyController::class, 'updateMemberEarnings'])->name('members.update-earnings');
        Route::post('/members/transfer', [App\Http\Controllers\Admin\AgencyController::class, 'transferMembers'])->name('members.transfer');
        
        // الإحصائيات
        Route::get('/statistics/overview', [App\Http\Controllers\Admin\AgencyController::class, 'statistics'])->name('statistics');
        
        // العمليات المجمعة
        Route::get('/bulk-actions', [App\Http\Controllers\Admin\AgencyController::class, 'bulkActions'])->name('bulk-actions');
        Route::post('/bulk-actions/execute', [App\Http\Controllers\Admin\AgencyController::class, 'executeBulkAction'])->name('bulk-actions.execute');
        
        // التصدير
        Route::get('/export/data', [App\Http\Controllers\Admin\AgencyController::class, 'export'])->name('export');
        
        // API endpoints للإحصائيات (للرسوم البيانية)
        Route::get('/api/stats/earnings', [App\Http\Controllers\Admin\AgencyController::class, 'getEarningsStats'])->name('api.earnings');
        Route::get('/api/stats/members', [App\Http\Controllers\Admin\AgencyController::class, 'getMembersStats'])->name('api.members');
        Route::get('/api/stats/activities', [App\Http\Controllers\Admin\AgencyController::class, 'getActivitiesStats'])->name('api.activities');
        Route::get('/api/stats/performance', [App\Http\Controllers\Admin\AgencyController::class, 'getPerformanceStats'])->name('api.performance');
    });
});

