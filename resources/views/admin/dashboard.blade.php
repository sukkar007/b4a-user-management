@extends('layouts.admin')

@section('title', 'لوحة التحكم الرئيسية')

@section('content')
<div class="container-fluid">
    <!-- ترحيب -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tachometer-alt me-2"></i>
            لوحة التحكم الرئيسية
        </h1>
        <div class="text-muted">
            <i class="fas fa-calendar-alt me-1"></i>
            {{ now()->format('Y-m-d H:i') }}
        </div>
    </div>

    <!-- رسالة ترحيب -->
    <div class="row">
        <div class="col-12">
            <div class="card border-left-primary shadow h-100 py-2 mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                مرحباً بك في لوحة التحكم
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                أهلاً وسهلاً، {{ Auth::user()->fullName ?? Auth::user()->getUsername() }}!
                            </div>
                            <div class="text-muted mt-2">
                                <i class="fas fa-envelope me-1"></i>
                                بريدك الإلكتروني: {{ Auth::user()->getEmail() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- بطاقات سريعة -->
    <div class="row">
        <!-- إدارة المستخدمين -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إدارة المستخدمين
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                عرض وإدارة
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i>
                            عرض المستخدمين
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- الإحصائيات -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                الإحصائيات
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                تقارير مفصلة
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-area fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.users.global-statistics') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-chart-line me-1"></i>
                            عرض الإحصائيات
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- العمليات المجمعة -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                العمليات المجمعة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                إدارة متقدمة
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tasks fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.users.bulk-actions') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-cogs me-1"></i>
                            العمليات المجمعة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- إضافة مستخدم -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                إضافة مستخدم
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                مستخدم جديد
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            إضافة مستخدم
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- محتوى لوحة التحكم -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات النظام
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary">
                                <i class="fas fa-shield-alt me-2"></i>
                                نظام إدارة المستخدمين
                            </h5>
                            <p class="text-muted">
                                نظام شامل لإدارة المستخدمين باستخدام Laravel و Back4App مع واجهة احترافية تدعم اللغة العربية.
                            </p>
                            
                            <h6 class="text-secondary mt-4">الميزات الرئيسية:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>إدارة شاملة للمستخدمين</li>
                                <li><i class="fas fa-check text-success me-2"></i>بحث وفلترة متقدمة</li>
                                <li><i class="fas fa-check text-success me-2"></i>إحصائيات تفاعلية</li>
                                <li><i class="fas fa-check text-success me-2"></i>عمليات مجمعة</li>
                                <li><i class="fas fa-check text-success me-2"></i>واجهة متجاوبة</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-secondary">الروابط السريعة:</h6>
                            <div class="list-group">
                                <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-users me-2"></i>
                                    قائمة المستخدمين
                                </a>
                                <a href="{{ route('admin.users.create') }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-user-plus me-2"></i>
                                    إضافة مستخدم جديد
                                </a>
                                <a href="{{ route('admin.users.global-statistics') }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    الإحصائيات العامة
                                </a>
                                <a href="{{ route('admin.users.bulk-actions') }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-tasks me-2"></i>
                                    العمليات المجمعة
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

