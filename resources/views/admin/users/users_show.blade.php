@extends('layouts.admin')

@section('title', 'تفاصيل المستخدم - ' . $userData['username'])

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">لوحة التحكم</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.users.index') }}">المستخدمون</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $userData['username'] }}</li>
                        </ol>
                    </nav>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-user me-2"></i>تفاصيل المستخدم
                    </h1>
                </div>
                <div>
                    <a href="{{ route('admin.users.edit', $userData['id']) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>تعديل
                    </a>
                    @if($userData['account_deleted'])
                        <button onclick="restoreUser('{{ $userData['id'] }}')" class="btn btn-success">
                            <i class="fas fa-undo me-2"></i>استعادة
                        </button>
                    @else
                        <button onclick="deleteUser('{{ $userData['id'] }}')" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>حذف
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- User Profile Card -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-id-card me-2"></i>الملف الشخصي
                    </h6>
                </div>
                <div class="card-body text-center">
                    <!-- Avatar -->
                    <div class="mb-4">
                        @if(isset($userData['avatar_url']))
                            <img src="{{ $userData['avatar_url'] }}" alt="Avatar" 
                                 class="rounded-circle shadow" width="120" height="120"
                                 style="object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto shadow" 
                                 style="width: 120px; height: 120px;">
                                <i class="fas fa-user fa-3x text-white"></i>
                            </div>
                        @endif
                        
                        @if($userData['photo_verified'])
                            <div class="mt-2">
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>صورة مؤكدة
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Basic Info -->
                    <h4 class="mb-1">{{ $userData['name'] ?? $userData['username'] }}</h4>
                    <p class="text-muted mb-2">{{ '@' . $userData['username'] }}</p>
                    
                    <!-- Status Badges -->
                    <div class="mb-3">
                        @if($userData['role'] == 'admin')
                            <span class="badge bg-danger me-2">
                                <i class="fas fa-shield-alt me-1"></i>مدير
                            </span>
                        @endif
                        
                        @if($userData['is_vip'])
                            @if($userData['vip_type'] == 'diamond')
                                <span class="badge bg-gradient-primary me-2">
                                    <i class="fas fa-gem me-1"></i>Diamond VIP
                                </span>
                            @elseif($userData['vip_type'] == 'super')
                                <span class="badge bg-gradient-warning me-2">
                                    <i class="fas fa-star me-1"></i>Super VIP
                                </span>
                            @else
                                <span class="badge bg-gradient-success me-2">
                                    <i class="fas fa-crown me-1"></i>VIP
                                </span>
                            @endif
                        @endif
                        
                        @if($userData['account_deleted'])
                            <span class="badge bg-danger">
                                <i class="fas fa-ban me-1"></i>محذوف
                            </span>
                        @else
                            <span class="badge bg-success">
                                <i class="fas fa-check me-1"></i>نشط
                            </span>
                        @endif
                    </div>

                    <!-- Bio -->
                    @if($userData['bio'])
                        <div class="text-start">
                            <h6 class="text-primary">نبذة شخصية:</h6>
                            <p class="text-muted">{{ $userData['bio'] }}</p>
                        </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="row mt-4">
                        <div class="col-6">
                            <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#vipModal">
                                <i class="fas fa-crown me-1"></i>إدارة VIP
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-outline-info btn-sm w-100" onclick="sendNotification()">
                                <i class="fas fa-bell me-1"></i>إرسال إشعار
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cover Image Card -->
            @if(isset($userData['cover_url']))
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-image me-2"></i>صورة الغلاف
                    </h6>
                </div>
                <div class="card-body p-0">
                    <img src="{{ $userData['cover_url'] }}" alt="Cover" 
                         class="img-fluid w-100" style="max-height: 200px; object-fit: cover;">
                </div>
            </div>
            @endif
        </div>

        <!-- User Details -->
        <div class="col-xl-8 col-lg-7">
            <!-- Personal Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>المعلومات الشخصية
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>البريد الإلكتروني:</strong>
                            <p class="mb-1">{{ $userData['email'] }}</p>
                            @if($userData['email_verified'])
                                <small class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>مؤكد
                                </small>
                            @else
                                <small class="text-danger">
                                    <i class="fas fa-times-circle me-1"></i>غير مؤكد
                                </small>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>رقم الهاتف:</strong>
                            <p>{{ $userData['phone_number'] ?? 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>العمر:</strong>
                            <p>{{ $userData['age'] ?? 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>الجنس:</strong>
                            <p>
                                @if($userData['gender'] == 'male')
                                    <span class="badge bg-primary">ذكر</span>
                                @elseif($userData['gender'] == 'female')
                                    <span class="badge bg-pink">أنثى</span>
                                @else
                                    غير محدد
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>المدينة:</strong>
                            <p>{{ $userData['city'] ?? 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>البلد:</strong>
                            <p>{{ $userData['country'] ?? 'غير محدد' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>الإحصائيات
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="text-center">
                                <div class="h4 mb-0 text-primary">{{ $userData['popularity'] ?? 0 }}</div>
                                <small class="text-muted">الشعبية</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="text-center">
                                <div class="h4 mb-0 text-success">{{ $userData['followers'] ?? 0 }}</div>
                                <small class="text-muted">المتابعون</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="text-center">
                                <div class="h4 mb-0 text-info">{{ $userData['following'] ?? 0 }}</div>
                                <small class="text-muted">يتابع</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="text-center">
                                <div class="h4 mb-0 text-warning">{{ $userData['user_points'] ?? 0 }}</div>
                                <small class="text-muted">النقاط</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-coins me-2"></i>المعلومات المالية
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="text-center">
                                <div class="h4 mb-0 text-warning">{{ $userData['coins'] ?? 0 }}</div>
                                <small class="text-muted">العملات</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-center">
                                <div class="h4 mb-0 text-primary">{{ $userData['diamonds'] ?? 0 }}</div>
                                <small class="text-muted">الماس</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-center">
                                <div class="h4 mb-0 text-success">{{ $userData['gifts_amount'] ?? 0 }}</div>
                                <small class="text-muted">قيمة الهدايا</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cog me-2"></i>معلومات الحساب
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>تاريخ التسجيل:</strong>
                            <p>{{ \Carbon\Carbon::parse($userData['created_at'])->format('Y-m-d H:i') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>آخر تحديث:</strong>
                            <p>{{ \Carbon\Carbon::parse($userData['updated_at'])->format('Y-m-d H:i') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>آخر ظهور:</strong>
                            <p>
                                @if($userData['last_online'])
                                    {{ \Carbon\Carbon::parse($userData['last_online'])->diffForHumans() }}
                                @else
                                    غير محدد
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>حالة الحساب:</strong>
                            <p>
                                @if($userData['account_hidden'])
                                    <span class="badge bg-warning">مخفي</span>
                                @else
                                    <span class="badge bg-success">ظاهر</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- VIP Management Modal -->
<div class="modal fade" id="vipModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-crown me-2"></i>إدارة حالة VIP
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.update-vip', $userData['id']) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="vip_type" class="form-label">نوع VIP</label>
                        <select class="form-select" id="vip_type" name="vip_type" required>
                            <option value="normal" {{ $userData['vip_type'] == 'normal' ? 'selected' : '' }}>
                                VIP عادي
                            </option>
                            <option value="super" {{ $userData['vip_type'] == 'super' ? 'selected' : '' }}>
                                Super VIP
                            </option>
                            <option value="diamond" {{ $userData['vip_type'] == 'diamond' ? 'selected' : '' }}>
                                Diamond VIP
                            </option>
                            <option value="remove">إزالة VIP</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="durationField">
                        <label for="duration" class="form-label">المدة (بالأيام)</label>
                        <input type="number" class="form-control" id="duration" name="duration" 
                               value="30" min="1" max="365">
                    </div>
                    
                    @if($userData['is_vip'] && $userData['vip_expiry'])
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        تنتهي حالة VIP الحالية في: 
                        {{ \Carbon\Carbon::parse($userData['vip_expiry'])->format('Y-m-d H:i') }}
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
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
        .then(response => {
            if (response.ok) {
                window.location.href = '{{ route("admin.users.index") }}';
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
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('خطأ في استعادة المستخدم');
            }
        });
    }
}

// إرسال إشعار
function sendNotification() {
    const message = prompt('أدخل نص الإشعار:');
    if (message) {
        // هنا يمكن إضافة كود إرسال الإشعار
        alert('تم إرسال الإشعار بنجاح');
    }
}

// إخفاء/إظهار حقل المدة حسب نوع VIP
document.getElementById('vip_type').addEventListener('change', function() {
    const durationField = document.getElementById('durationField');
    if (this.value === 'remove') {
        durationField.style.display = 'none';
    } else {
        durationField.style.display = 'block';
    }
});
</script>
@endsection

@section('styles')
<style>
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

.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}
</style>
@endsection

