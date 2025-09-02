@extends('layouts.admin')

@section('title', 'إدارة الوكالات')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-building text-primary me-2"></i>
                إدارة الوكالات
            </h1>
            <p class="text-muted mb-0">إدارة شاملة للوكالات والأعضاء والدعوات</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.agencies.invitations') }}" class="btn btn-outline-primary">
                <i class="fas fa-envelope me-1"></i>
                إدارة الدعوات
            </a>
            <a href="{{ route('admin.agencies.statistics') }}" class="btn btn-outline-success">
                <i class="fas fa-chart-bar me-1"></i>
                الإحصائيات
            </a>
            <a href="{{ route('admin.agencies.export') }}" class="btn btn-outline-info">
                <i class="fas fa-download me-1"></i>
                تصدير البيانات
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي الوكالات
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_agencies']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                الأعضاء النشطين
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_active_members']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                الدعوات المعلقة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['pending_invitations']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                إجمالي الأرباح
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_earnings']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>
                البحث والفلترة
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.agencies.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">البحث في اسم المضيف</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="اكتب اسم المضيف...">
                </div>
                
                <div class="col-md-3">
                    <label for="min_members" class="form-label">الحد الأدنى للأعضاء</label>
                    <input type="number" class="form-control" id="min_members" name="min_members" 
                           value="{{ request('min_members') }}" min="0">
                </div>
                
                <div class="col-md-3">
                    <label for="sort_by" class="form-label">ترتيب حسب</label>
                    <select class="form-select" id="sort_by" name="sort_by">
                        <option value="members_count" {{ request('sort_by') == 'members_count' ? 'selected' : '' }}>
                            عدد الأعضاء
                        </option>
                        <option value="total_earnings" {{ request('sort_by') == 'total_earnings' ? 'selected' : '' }}>
                            إجمالي الأرباح
                        </option>
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>
                            تاريخ الإنشاء
                        </option>
                    </select>
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i>
                        بحث
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Agencies List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>
                قائمة الوكالات ({{ count($agencies) }})
            </h6>
        </div>
        <div class="card-body">
            @if(count($agencies) > 0)
                <div class="row">
                    @foreach($agencies as $agency)
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card border-left-primary shadow-sm h-100">
                                <div class="card-body">
                                    <!-- Host Info -->
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-circle bg-primary text-white me-3">
                                            @if($agency['host']->get('avatar'))
                                                <img src="{{ $agency['host']->get('avatar')->getURL() }}" 
                                                     alt="Avatar" class="rounded-circle" width="50" height="50">
                                            @else
                                                <i class="fas fa-user fa-lg"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 font-weight-bold">
                                                {{ $agency['host']->get('fullName') ?? $agency['host']->get('username') }}
                                            </h6>
                                            <p class="text-muted mb-0 small">
                                                @{{ $agency['host']->get('username') }}
                                            </p>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                    type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" 
                                                       href="{{ route('admin.agencies.show', $agency['host']->getObjectId()) }}">
                                                        <i class="fas fa-eye me-2"></i>
                                                        عرض التفاصيل
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" 
                                                       href="{{ route('admin.users.show', $agency['host']->getObjectId()) }}">
                                                        <i class="fas fa-user me-2"></i>
                                                        ملف المضيف
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Agency Stats -->
                                    <div class="row text-center mb-3">
                                        <div class="col-4">
                                            <div class="border-end">
                                                <div class="h6 mb-0 text-primary font-weight-bold">
                                                    {{ $agency['member_count'] }}
                                                </div>
                                                <small class="text-muted">عضو</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="border-end">
                                                <div class="h6 mb-0 text-success font-weight-bold">
                                                    {{ number_format($agency['total_earnings']) }}
                                                </div>
                                                <small class="text-muted">نقطة</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="h6 mb-0 text-info font-weight-bold">
                                                {{ $agency['member_count'] > 0 ? round($agency['total_earnings'] / $agency['member_count']) : 0 }}
                                            </div>
                                            <small class="text-muted">متوسط</small>
                                        </div>
                                    </div>

                                    <!-- Top Members Preview -->
                                    @if(count($agency['members']) > 0)
                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-2">أفضل الأعضاء:</small>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach(array_slice($agency['members'], 0, 3) as $member)
                                                    <span class="badge bg-light text-dark border">
                                                        {{ $member->get('agent')->get('username') }}
                                                        <small class="text-success">
                                                            ({{ number_format($member->get('total_points_earnings') ?? 0) }})
                                                        </small>
                                                    </span>
                                                @endforeach
                                                @if(count($agency['members']) > 3)
                                                    <span class="badge bg-secondary">
                                                        +{{ count($agency['members']) - 3 }} أكثر
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Action Button -->
                                    <div class="d-grid">
                                        <a href="{{ route('admin.agencies.show', $agency['host']->getObjectId()) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>
                                            عرض التفاصيل الكاملة
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-building fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">لا توجد وكالات</h5>
                    <p class="text-muted">لم يتم العثور على أي وكالات تطابق معايير البحث</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.badge {
    font-size: 0.75em;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.dropdown-item {
    font-size: 0.85rem;
}

.dropdown-item:hover {
    background-color: #f8f9fc;
}

@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .d-flex.gap-2 {
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on select change
    document.getElementById('sort_by').addEventListener('change', function() {
        this.form.submit();
    });
    
    // Add loading state to buttons
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>جاري البحث...';
                submitBtn.disabled = true;
            }
        });
    });
});
</script>
@endsection

