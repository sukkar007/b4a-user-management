@extends('layouts.admin')

@section('title', 'إدارة دعوات الوكالات')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-envelope text-warning me-2"></i>
                إدارة دعوات الوكالات
            </h1>
            <p class="text-muted mb-0">إدارة وموافقة على دعوات انضمام الأعضاء للوكالات</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.agencies.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-building me-1"></i>
                عودة للوكالات
            </a>
            <button class="btn btn-outline-success" onclick="bulkAction('accept')">
                <i class="fas fa-check me-1"></i>
                قبول المحدد
            </button>
            <button class="btn btn-outline-danger" onclick="bulkAction('decline')">
                <i class="fas fa-times me-1"></i>
                رفض المحدد
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                دعوات معلقة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($invitationStats['pending']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                دعوات مقبولة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($invitationStats['accepted']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                دعوات مرفوضة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($invitationStats['declined']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times fa-2x text-gray-300"></i>
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
                                إجمالي الدعوات
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($invitationStats['total']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>
                البحث والفلترة
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.agencies.invitations') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">البحث في اسم المستخدم</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="اكتب اسم المستخدم...">
                </div>
                
                <div class="col-md-3">
                    <label for="status" class="form-label">حالة الدعوة</label>
                    <select class="form-select" id="status" name="status">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>
                            جميع الحالات
                        </option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            معلقة
                        </option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>
                            مقبولة
                        </option>
                        <option value="declined" {{ request('status') == 'declined' ? 'selected' : '' }}>
                            مرفوضة
                        </option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="date_from" class="form-label">من تاريخ</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="{{ request('date_from') }}">
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

    <!-- Invitations List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>
                قائمة الدعوات ({{ count($invitations) }})
            </h6>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                <label class="form-check-label" for="selectAll">
                    تحديد الكل
                </label>
            </div>
        </div>
        <div class="card-body">
            @if(count($invitations) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">
                                    <input type="checkbox" class="form-check-input" id="selectAllHeader" onchange="toggleSelectAll()">
                                </th>
                                <th>المدعو</th>
                                <th>المضيف</th>
                                <th>تاريخ الدعوة</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invitations as $invitation)
                                <tr class="invitation-row" data-id="{{ $invitation->getObjectId() }}">
                                    <td>
                                        <input type="checkbox" class="form-check-input invitation-checkbox" 
                                               value="{{ $invitation->getObjectId() }}"
                                               {{ $invitation->get('invitation_status') !== 'pending' ? 'disabled' : '' }}>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                @if($invitation->get('agent')->get('avatar'))
                                                    <img src="{{ $invitation->get('agent')->get('avatar')->getURL() }}" 
                                                         alt="Avatar" class="rounded-circle" width="40" height="40">
                                                @else
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-weight-bold">
                                                    {{ $invitation->get('agent')->get('fullName') ?? $invitation->get('agent')->get('username') }}
                                                </div>
                                                <small class="text-muted">
                                                    @{{ $invitation->get('agent')->get('username') }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                @if($invitation->get('host')->get('avatar'))
                                                    <img src="{{ $invitation->get('host')->get('avatar')->getURL() }}" 
                                                         alt="Avatar" class="rounded-circle" width="40" height="40">
                                                @else
                                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-weight-bold">
                                                    {{ $invitation->get('host')->get('fullName') ?? $invitation->get('host')->get('username') }}
                                                </div>
                                                <small class="text-muted">
                                                    @{{ $invitation->get('host')->get('username') }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            {{ $invitation->get('createdAt')->format('Y/m/d') }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $invitation->get('createdAt')->diffForHumans() }}
                                        </small>
                                    </td>
                                    <td>
                                        @php
                                            $status = $invitation->get('invitation_status');
                                            $statusClass = $status === 'accepted' ? 'success' : ($status === 'pending' ? 'warning' : 'danger');
                                            $statusText = $status === 'accepted' ? 'مقبولة' : ($status === 'pending' ? 'معلقة' : 'مرفوضة');
                                            $statusIcon = $status === 'accepted' ? 'check' : ($status === 'pending' ? 'clock' : 'times');
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($invitation->get('invitation_status') === 'pending')
                                            <div class="btn-group" role="group">
                                                <form method="POST" 
                                                      action="{{ route('admin.agencies.invitations.accept', $invitation->getObjectId()) }}" 
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" 
                                                            title="قبول الدعوة"
                                                            onclick="return confirm('هل أنت متأكد من قبول هذه الدعوة؟')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" 
                                                      action="{{ route('admin.agencies.invitations.decline', $invitation->getObjectId()) }}" 
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            title="رفض الدعوة"
                                                            onclick="return confirm('هل أنت متأكد من رفض هذه الدعوة؟')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                        type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" 
                                                           href="{{ route('admin.users.show', $invitation->get('agent')->getObjectId()) }}">
                                                            <i class="fas fa-user me-2"></i>
                                                            ملف المدعو
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" 
                                                           href="{{ route('admin.agencies.show', $invitation->get('host')->getObjectId()) }}">
                                                            <i class="fas fa-building me-2"></i>
                                                            ملف الوكالة
                                                        </a>
                                                    </li>
                                                    @if($invitation->get('invitation_status') === 'accepted')
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <button class="dropdown-item text-warning" 
                                                                    onclick="revertInvitation('{{ $invitation->getObjectId() }}')">
                                                                <i class="fas fa-undo me-2"></i>
                                                                إعادة للمعلق
                                                            </button>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination would go here if needed -->
                
            @else
                <div class="text-center py-5">
                    <i class="fas fa-envelope fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">لا توجد دعوات</h5>
                    <p class="text-muted">لم يتم العثور على دعوات تطابق معايير البحث</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Stats -->
    @if(count($invitations) > 0)
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-pie me-2"></i>
                            توزيع الدعوات
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="invitationChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-clock me-2"></i>
                            الدعوات الأخيرة
                        </h6>
                    </div>
                    <div class="card-body">
                        @foreach($invitations->take(5) as $invitation)
                            <div class="d-flex align-items-center mb-3 {{ $loop->last ? '' : 'border-bottom pb-3' }}">
                                <div class="me-3">
                                    @php
                                        $status = $invitation->get('invitation_status');
                                        $statusClass = $status === 'accepted' ? 'success' : ($status === 'pending' ? 'warning' : 'danger');
                                    @endphp
                                    <div class="badge bg-{{ $statusClass }} rounded-circle p-2">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold">
                                        {{ $invitation->get('agent')->get('username') }}
                                    </div>
                                    <small class="text-muted">
                                        دعوة من {{ $invitation->get('host')->get('username') }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">
                                        {{ $invitation->get('createdAt')->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
.border-left-success { border-left: 0.25rem solid #1cc88a !important; }
.border-left-danger { border-left: 0.25rem solid #e74a3b !important; }
.border-left-info { border-left: 0.25rem solid #36b9cc !important; }

.avatar-sm img {
    object-fit: cover;
}

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.invitation-row:hover {
    background-color: #f8f9fc;
}

.btn-group .btn {
    margin-right: 2px;
}

.badge.rounded-circle {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Toggle select all checkboxes
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.invitation-checkbox:not([disabled])');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

// Bulk actions
function bulkAction(action) {
    const selectedIds = [];
    const checkboxes = document.querySelectorAll('.invitation-checkbox:checked');
    
    checkboxes.forEach(checkbox => {
        selectedIds.push(checkbox.value);
    });
    
    if (selectedIds.length === 0) {
        alert('يرجى تحديد دعوة واحدة على الأقل');
        return;
    }
    
    const actionText = action === 'accept' ? 'قبول' : 'رفض';
    if (confirm(`هل أنت متأكد من ${actionText} ${selectedIds.length} دعوة؟`)) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.agencies.invitations') }}/bulk-${action}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'invitation_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Revert invitation
function revertInvitation(invitationId) {
    if (confirm('هل أنت متأكد من إعادة هذه الدعوة إلى حالة المعلق؟')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.agencies.invitations') }}/${invitationId}/revert`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

// Initialize chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('invitationChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['معلقة', 'مقبولة', 'مرفوضة'],
                datasets: [{
                    data: [
                        {{ $invitationStats['pending'] }},
                        {{ $invitationStats['accepted'] }},
                        {{ $invitationStats['declined'] }}
                    ],
                    backgroundColor: [
                        '#f6c23e',
                        '#1cc88a',
                        '#e74a3b'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    
    // Auto-submit form on status change
    document.getElementById('status').addEventListener('change', function() {
        this.form.submit();
    });
});
</script>
@endsection

