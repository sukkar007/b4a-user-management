<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AgencyController;

// الصفحة الرئيسية
Route::get('/', function () {
    return redirect()->route('login');
});

// مسارات المصادقة
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.post');
Route::get('login-submit', [LoginController::class, 'login'])->name('login.submit');
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
        Route::get('/', [AgencyController::class, 'index'])->name('index');
        
        // عرض تفاصيل وكالة محددة
        Route::get('/{hostId}', [AgencyController::class, 'show'])->name('show');
        
        // إنشاء وكالة جديدة
        Route::get('/create/new', [AgencyController::class, 'create'])->name('create');
        Route::post('/create/store', [AgencyController::class, 'store'])->name('store');
        
        // إدارة الدعوات
        Route::get('/invitations/manage', [AgencyController::class, 'invitations'])->name('invitations');
        Route::post('/invitations/{invitationId}/accept', [AgencyController::class, 'acceptInvitation'])->name('invitations.accept');
        Route::post('/invitations/{invitationId}/decline', [AgencyController::class, 'declineInvitation'])->name('invitations.decline');
        Route::post('/invitations/send', [AgencyController::class, 'sendInvitation'])->name('invitations.send');
        
        // إدارة الأعضاء
        Route::post('/members/{memberId}/remove', [AgencyController::class, 'removeMember'])->name('members.remove');
        Route::post('/members/{memberId}/update-level', [AgencyController::class, 'updateMemberLevel'])->name('members.update-level');
        Route::post('/members/{memberId}/update-earnings', [AgencyController::class, 'updateMemberEarnings'])->name('members.update-earnings');
        Route::post('/members/transfer', [AgencyController::class, 'transferMembers'])->name('members.transfer');
        
        // الإحصائيات
        Route::get('/statistics/overview', [AgencyController::class, 'statistics'])->name('statistics');
        
        // العمليات المجمعة
        Route::get('/bulk-actions', [AgencyController::class, 'bulkActions'])->name('bulk-actions');
        Route::post('/bulk-actions/execute', [AgencyController::class, 'executeBulkAction'])->name('bulk-actions.execute');
        
        // التصدير
        Route::get('/export/data', [AgencyController::class, 'export'])->name('export');
        
        // API endpoints للإحصائيات (للرسوم البيانية)
        Route::get('/api/stats/earnings', [AgencyController::class, 'getEarningsStats'])->name('api.earnings');
        Route::get('/api/stats/members', [AgencyController::class, 'getMembersStats'])->name('api.members');
        Route::get('/api/stats/activities', [AgencyController::class, 'getActivitiesStats'])->name('api.activities');
        Route::get('/api/stats/performance', [AgencyController::class, 'getPerformanceStats'])->name('api.performance');
    });
});

