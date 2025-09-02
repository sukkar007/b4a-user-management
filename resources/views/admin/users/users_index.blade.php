@extends('layouts.admin')

@section('title', 'إدارة المستخدمين')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-users me-2"></i>إدارة المستخدمين
                    </h1>
                    <p class="text-muted">إدارة وعرض جميع المستخدمين المسجلين في التطبيق</p>
                </div>
                <div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#statsModal">
                        <i class="fas fa-chart-bar me-2"></i>الإحصائيات
                    </button>
                    <button class="btn btn-success" onclick="exportUsers()">
                        <i class="fas fa-download me-2"></i>تصدير
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي المستخدمين
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalUsers">
                                {{ $totalUsers }}
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
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                المستخدمون النشطون
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="activeUsers">
                                {{ $totalUsers - ($usersData | where('account_deleted', true) | count) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
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
                                مستخدمو VIP
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="vipUsers">
                                {{ $usersData | where('is_vip', true) | count }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-crown fa-2x text-gray-300"></i>
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
                                المديرون
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="adminUsers">
                                {{ $usersData | where('role', 'admin') | count }}
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

    <!-- Filters Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>البحث والفلترة
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">البحث</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="اسم المستخدم، البريد، أو الاسم الكامل"
                               value="{{ request('search') }}">
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label for="gender" class="form-label">الجنس</label>
                        <select class="form-select" id="gender" name="gender">
                            <option value="">الكل</option>
                            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label for="role" class="form-label">الدور</label>
                        <select class="form-select" id="role" name="role">
                            <option value="">الكل</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>مستخدم</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>مدير</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label for="vip_status" class="form-label">حالة VIP</label>
                        <select class="form-select" id="vip_status" name="vip_status">
                            <option value="">الكل</option>
                            <option value="vip" {{ request('vip_status') == 'vip' ? 'selected' : '' }}>VIP</option>
                            <option value="regular" {{ request('vip_status') == 'regular' ? 'selected' : '' }}>عادي</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label for="per_page" class="form-label">عدد النتائج</label>
                        <select class="form-select" id="per_page" name="per_page">
                            <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    
                    <div class="col-md-1 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>قائمة المستخدمين
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="usersTable">
                    <thead class="table-dark">
                        <tr>
                            <th>الصورة</th>
                            <th>اسم المستخدم</th>
                            <th>الاسم الكامل</th>
                            <th>البريد الإلكتروني</th>
                            <th>العمر</th>
                            <th>الجنس</th>
                            <th>الدور</th>
                            <th>حالة VIP</th>
                            <th>آخر ظهور</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usersData as $user)
                        <tr class="{{ $user['account_deleted'] ? 'table-warning' : '' }}">
                            <td class="text-center">
                                @if(isset($user['avatar_url']))
                                    <img src="{{ $user['avatar_url'] }}" alt="Avatar" 
                                         class="rounded-circle" width="40" height="40"
                                         style="object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $user['username'] }}</strong>
                                @if($user['email_verified'])
                                    <i class="fas fa-check-circle text-success ms-1" title="بريد مؤكد"></i>
                                @endif
                            </td>
                            <td>{{ $user['name'] ?? 'غير محدد' }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td>{{ $user['age'] ?? 'غير محدد' }}</td>
                            <td>
                                @if($user['gender'] == 'male')
                                    <span class="badge bg-primary">ذكر</span>
                                @elseif($user['gender'] == 'female')
                                    <span class="badge bg-pink">أنثى</span>
                                @else
                                    <span class="badge bg-secondary">غير محدد</span>
                                @endif
                            </td>
                            <td>
                                @if($user['role'] == 'admin')
                                    <span class="badge bg-danger">مدير</span>
                                @else
                                    <span class="badge bg-info">مستخدم</span>
                                @endif
                            </td>
                            <td>
                                @if($user['is_vip'])
                                    @if($user['vip_type'] == 'diamond')
                                        <span class="badge bg-gradient-primary">
                                            <i class="fas fa-gem me-1"></i>Diamond
                                        </span>
                                    @elseif($user['vip_type'] == 'super')
                                        <span class="badge bg-gradient-warning">
                                            <i class="fas fa-star me-1"></i>Super
                                        </span>
                                    @else
                                        <span class="badge bg-gradient-success">
                                            <i class="fas fa-crown me-1"></i>VIP
                                        </span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">عادي</span>
                                @endif
                            </td>
                            <td>
                                @if($user['last_online'])
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($user['last_online'])->diffForHumans() }}
                                    </small>
                                @else
                                    <small class="text-muted">غير محدد</small>
                                @endif
                            </td>
                            <td>
                                @if($user['account_deleted'])
                                    <span class="badge bg-danger">محذوف</span>
                                @else
                                    <span class="badge bg-success">نشط</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.users.show', $user['id']) }}" 
                                       class="btn btn-sm btn-outline-info" title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user['id']) }}" 
                                       class="btn btn-sm btn-outline-primary" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user['account_deleted'])
                                        <button onclick="restoreUser('{{ $user['id'] }}')" 
                                                class="btn btn-sm btn-outline-success" title="استعادة">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    @else
                                        <button onclick="deleteUser('{{ $user['id'] }}')" 
                                                class="btn btn-sm btn-outline-danger" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <p>لا توجد مستخدمون لعرضهم</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($totalPages > 1)
            <nav aria-label="User pagination">
                <ul class="pagination justify-content-center">
                    @if($hasPrevPage)
                        <li class="page-item">
                            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $page - 1]) }}">
                                السابق
                            </a>
                        </li>
                    @endif

                    @for($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++)
                        <li class="page-item {{ $i == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">
                                {{ $i }}
                            </a>
                        </li>
                    @endfor

                    @if($hasNextPage)
                        <li class="page-item">
                            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $page + 1]) }}">
                                التالي
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
            @endif
        </div>
    </div>
</div>

<!-- Stats Modal -->
<div class="modal fade" id="statsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-chart-bar me-2"></i>إحصائيات مفصلة
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="statsContent">
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">جاري تحميل الإحصائيات...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// تحميل الإحصائيات
function loadStats() {
    fetch('{{ route("admin.users.stats") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('statsContent').innerHTML = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h3>${data.total_users}</h3>
                                <p>إجمالي المستخدمين</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h3>${data.active_users}</h3>
                                <p>المستخدمون النشطون</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h3>${data.vip_users}</h3>
                                <p>مستخدمو VIP</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h3>${data.male_users}</h3>
                                <p>المستخدمون الذكور</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            document.getElementById('statsContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    خطأ في تحميل الإحصائيات
                </div>
            `;
        });
}

// حذف المستخدم
function deleteUser(userId) {
    if (confirm('هل أنت متأكد من حذف هذا المستخدم؟')) {
        fetch(`/admin/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('خطأ في حذف المستخدم');
            }
        });
    }
}

// استعادة المستخدم
function restoreUser(userId) {
    if (confirm('هل أنت متأكد من استعادة هذا المستخدم؟')) {
        fetch(`/admin/users/${userId}/restore`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('خطأ في استعادة المستخدم');
            }
        });
    }
}

// تصدير المستخدمين
function exportUsers() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'csv');
    window.open(`{{ route('admin.users.index') }}?${params.toString()}`);
}

// تحميل الإحصائيات عند فتح المودال
document.getElementById('statsModal').addEventListener('show.bs.modal', loadStats);

// تطبيق الفلاتر تلقائياً
document.querySelectorAll('#filterForm select, #filterForm input').forEach(element => {
    element.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});
</script>
@endsection

@section('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.bg-pink {
    background-color: #e83e8c !important;
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #4e73df, #224abe);
}
.bg-gradient-success {
    background: linear-gradient(45deg, #1cc88a, #13855c);
}
.bg-gradient-warning {
    background: linear-gradient(45deg, #f6c23e, #dda20a);
}

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,.075);
}

.btn-group .btn {
    margin-right: 2px;
}

.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}
</style>
@endsection

