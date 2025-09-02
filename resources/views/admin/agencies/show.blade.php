@extends('layouts.admin')

@section('title', 'تفاصيل الوكالة - ' . ($host->get('fullName') ?? $host->get('username')))

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">لوحة التحكم</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.agencies.index') }}">الوكالات</a>
            </li>
            <li class="breadcrumb-item active">
                {{ $host->get('fullName') ?? $host->get('username') }}
            </li>
        </ol>
    </nav>

    <!-- Host Profile Header -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="avatar-lg">
                        @if($host->get('avatar'))
                            <img src="{{ $host->get('avatar')->getURL() }}" 
                                 alt="Avatar" class="rounded-circle" width="80" height="80">
                        @else
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col">
                    <h2 class="mb-1">{{ $host->get('fullName') ?? $host->get('username') }}</h2>
                    <p class="text-muted mb-2">@{{ $host->get('username') }}</p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-primary">
                            <i class="fas fa-building me-1"></i>
                            مضيف وكالة
                        </span>
                        @if($host->get('emailVerified'))
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>
                                موثق
                            </span>
                        @endif
                        <span class="badge bg-info">
                            <i class="fas fa-calendar me-1"></i>
                            انضم {{ $host->get('createdAt')->format('Y/m/d') }}
                        </span>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown">
                            <i class="fas fa-cog me-1"></i>
                            إجراءات
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.users.show', $host->getObjectId()) }}">
                                    <i class="fas fa-user me-2"></i>
                                    عرض الملف الشخصي
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.users.edit', $host->getObjectId()) }}">
                                    <i class="fas fa-edit me-2"></i>
                                    تعديل البيانات
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" onclick="confirmAction('dissolve')">
                                    <i class="fas fa-times me-2"></i>
                                    حل الوكالة
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Agency Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي الأعضاء
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $agencyStats['total_members'] }}
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
                                إجمالي الأرباح
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($agencyStats['total_earnings']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                                متوسط المستوى
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $agencyStats['avg_level'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
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
                                {{ count($pendingInvitations) }}
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

    <div class="row">
        <!-- Members List -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users me-2"></i>
                        أعضاء الوكالة ({{ count($members) }})
                    </h6>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="fas fa-filter me-1"></i>
                            فلترة
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="exportMembers()">
                            <i class="fas fa-download me-1"></i>
                            تصدير
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($members) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>العضو</th>
                                        <th>المستوى</th>
                                        <th>إجمالي الأرباح</th>
                                        <th>مدة البث</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($members as $member)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3">
                                                        @if($member->get('agent')->get('avatar'))
                                                            <img src="{{ $member->get('agent')->get('avatar')->getURL() }}" 
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
                                                            {{ $member->get('agent')->get('fullName') ?? $member->get('agent')->get('username') }}
                                                        </div>
                                                        <small class="text-muted">
                                                            @{{ $member->get('agent')->get('username') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    المستوى {{ $member->get('level') ?? 1 }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong class="text-success">
                                                    {{ number_format($member->get('total_points_earnings') ?? 0) }}
                                                </strong>
                                                <small class="text-muted d-block">نقطة</small>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ number_format(($member->get('live_duration') ?? 0) / 60, 1) }} ساعة
                                                </small>
                                            </td>
                                            <td>
                                                @php
                                                    $status = $member->get('client_status');
                                                    $statusClass = $status === 'joined' ? 'success' : ($status === 'pending' ? 'warning' : 'danger');
                                                    $statusText = $status === 'joined' ? 'نشط' : ($status === 'pending' ? 'معلق' : 'غادر');
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                            type="button" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" 
                                                               href="{{ route('admin.users.show', $member->get('agent')->getObjectId()) }}">
                                                                <i class="fas fa-eye me-2"></i>
                                                                عرض الملف
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item" 
                                                                    onclick="editMember('{{ $member->getObjectId() }}', {{ $member->get('level') ?? 1 }})">
                                                                <i class="fas fa-edit me-2"></i>
                                                                تعديل المستوى
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item" 
                                                                    onclick="editEarnings('{{ $member->getObjectId() }}')">
                                                                <i class="fas fa-coins me-2"></i>
                                                                تعديل الأرباح
                                                            </button>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <button class="dropdown-item text-danger" 
                                                                    onclick="removeMember('{{ $member->getObjectId() }}', '{{ $member->get('agent')->get('username') }}')">
                                                                <i class="fas fa-times me-2"></i>
                                                                إزالة من الوكالة
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-600">لا يوجد أعضاء</h5>
                            <p class="text-muted">لم يتم العثور على أعضاء في هذه الوكالة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Top Earners -->
            @if(count($agencyStats['top_earners']) > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-trophy me-2"></i>
                            أفضل الأعضاء
                        </h6>
                    </div>
                    <div class="card-body">
                        @foreach($agencyStats['top_earners'] as $index => $earner)
                            <div class="d-flex align-items-center mb-3 {{ $loop->last ? '' : 'border-bottom pb-3' }}">
                                <div class="me-3">
                                    @if($index === 0)
                                        <i class="fas fa-crown text-warning fa-lg"></i>
                                    @elseif($index === 1)
                                        <i class="fas fa-medal text-secondary fa-lg"></i>
                                    @elseif($index === 2)
                                        <i class="fas fa-award text-warning fa-lg"></i>
                                    @else
                                        <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold">
                                        {{ $earner['member']->get('agent')->get('username') }}
                                    </div>
                                    <small class="text-success">
                                        {{ number_format($earner['earnings']) }} نقطة
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Pending Invitations -->
            @if(count($pendingInvitations) > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">
                            <i class="fas fa-envelope me-2"></i>
                            الدعوات المعلقة
                        </h6>
                    </div>
                    <div class="card-body">
                        @foreach($pendingInvitations as $invitation)
                            <div class="d-flex align-items-center justify-content-between mb-3 {{ $loop->last ? '' : 'border-bottom pb-3' }}">
                                <div>
                                    <div class="font-weight-bold">
                                        {{ $invitation->get('agent')->get('username') }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $invitation->get('createdAt')->diffForHumans() }}
                                    </small>
                                </div>
                                <div class="d-flex gap-1">
                                    <form method="POST" 
                                          action="{{ route('admin.agencies.invitations.accept', $invitation->getObjectId()) }}" 
                                          class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="قبول">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form method="POST" 
                                          action="{{ route('admin.agencies.invitations.decline', $invitation->getObjectId()) }}" 
                                          class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" title="رفض">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Activity Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-chart-line me-2"></i>
                        ملخص النشاط
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted">مدة البث المباشر</small>
                            <strong>{{ number_format($agencyStats['total_live_duration'] / 60, 1) }} ساعة</strong>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-primary" style="width: 70%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted">مدة الحفلات</small>
                            <strong>{{ number_format($agencyStats['total_party_duration'] / 60, 1) }} ساعة</strong>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success" style="width: 50%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-0">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted">مدة المطابقة</small>
                            <strong>{{ number_format($agencyStats['total_match_duration'] / 60, 1) }} ساعة</strong>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-info" style="width: 30%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Member Level Modal -->
<div class="modal fade" id="editLevelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل مستوى العضو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editLevelForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="level" class="form-label">المستوى الجديد</label>
                        <input type="number" class="form-control" id="level" name="level" 
                               min="1" max="10" required>
                        <div class="form-text">المستوى يجب أن يكون بين 1 و 10</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Earnings Modal -->
<div class="modal fade" id="editEarningsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل أرباح العضو</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editEarningsForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="earning_type" class="form-label">نوع الأرباح</label>
                        <select class="form-select" id="earning_type" name="earning_type" required>
                            <option value="live_earnings">أرباح البث المباشر</option>
                            <option value="match_earnings">أرباح المطابقة</option>
                            <option value="party_earnings">أرباح الحفلات</option>
                            <option value="game_gratuities">إكراميات الألعاب</option>
                            <option value="platform_reward">مكافآت المنصة</option>
                            <option value="p_coin_earnings">أرباح العملة الخاصة</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">المبلغ</label>
                        <input type="number" class="form-control" id="amount" name="amount" 
                               min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="action" class="form-label">الإجراء</label>
                        <select class="form-select" id="action" name="action" required>
                            <option value="add">إضافة</option>
                            <option value="subtract">خصم</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.border-left-primary { border-left: 0.25rem solid #4e73df !important; }
.border-left-success { border-left: 0.25rem solid #1cc88a !important; }
.border-left-info { border-left: 0.25rem solid #36b9cc !important; }
.border-left-warning { border-left: 0.25rem solid #f6c23e !important; }

.avatar-lg img, .avatar-sm img {
    object-fit: cover;
}

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.progress {
    background-color: #f8f9fc;
}

.card:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}
</style>

<script>
function editMember(memberId, currentLevel) {
    document.getElementById('editLevelForm').action = 
        `{{ route('admin.agencies.members.update-level', '') }}/${memberId}`;
    document.getElementById('level').value = currentLevel;
    new bootstrap.Modal(document.getElementById('editLevelModal')).show();
}

function editEarnings(memberId) {
    document.getElementById('editEarningsForm').action = 
        `{{ route('admin.agencies.members.update-earnings', '') }}/${memberId}`;
    new bootstrap.Modal(document.getElementById('editEarningsModal')).show();
}

function removeMember(memberId, username) {
    if (confirm(`هل أنت متأكد من إزالة العضو "${username}" من الوكالة؟`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.agencies.members.remove', '') }}/${memberId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

function exportMembers() {
    window.location.href = `{{ route('admin.agencies.export') }}?host_id={{ $host->getObjectId() }}&format=csv`;
}
</script>
@endsection

