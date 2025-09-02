@extends('layouts.admin')

@section('title', 'تعديل المستخدم - ' . $userData['username'])

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">لوحة التحكم</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.users.index') }}">المستخدمون</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.users.show', $userData['id']) }}">{{ $userData['username'] }}</a>
                    </li>
                    <li class="breadcrumb-item active">تعديل</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-edit me-2"></i>تعديل المستخدم
            </h1>
            <p class="text-muted">تعديل بيانات المستخدم {{ $userData['username'] }}</p>
        </div>
    </div>

    <form action="{{ route('admin.users.update', $userData['id']) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- User Profile Section -->
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-image me-2"></i>الصور الشخصية
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <!-- Current Avatar -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">الصورة الشخصية الحالية:</label>
                            <div class="mb-3">
                                @if(isset($userData['avatar_url']))
                                    <img src="{{ $userData['avatar_url'] }}" alt="Avatar" 
                                         class="rounded-circle shadow" width="120" height="120"
                                         style="object-fit: cover;" id="currentAvatar">
                                @else
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto shadow" 
                                         style="width: 120px; height: 120px;" id="currentAvatar">
                                        <i class="fas fa-user fa-3x text-white"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Avatar Upload -->
                            <div class="mb-3">
                                <label for="avatar" class="form-label">تغيير الصورة الشخصية:</label>
                                <input type="file" class="form-control" id="avatar" name="avatar" 
                                       accept="image/*" onchange="previewImage(this, 'avatarPreview')">
                                <small class="text-muted">الحد الأقصى: 2MB، الأنواع المقبولة: JPG, PNG, GIF</small>
                            </div>
                            
                            <!-- Avatar Preview -->
                            <div id="avatarPreview" class="mt-3" style="display: none;">
                                <label class="form-label fw-bold">معاينة الصورة الجديدة:</label>
                                <div>
                                    <img id="avatarPreviewImg" class="rounded-circle shadow" 
                                         width="100" height="100" style="object-fit: cover;">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Current Cover -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">صورة الغلاف الحالية:</label>
                            <div class="mb-3">
                                @if(isset($userData['cover_url']))
                                    <img src="{{ $userData['cover_url'] }}" alt="Cover" 
                                         class="img-fluid rounded shadow" style="max-height: 150px; width: 100%; object-fit: cover;" 
                                         id="currentCover">
                                @else
                                    <div class="bg-light border rounded d-flex align-items-center justify-content-center" 
                                         style="height: 150px;" id="currentCover">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Cover Upload -->
                            <div class="mb-3">
                                <label for="cover" class="form-label">تغيير صورة الغلاف:</label>
                                <input type="file" class="form-control" id="cover" name="cover" 
                                       accept="image/*" onchange="previewImage(this, 'coverPreview')">
                                <small class="text-muted">الحد الأقصى: 5MB، الأنواع المقبولة: JPG, PNG, GIF</small>
                            </div>
                            
                            <!-- Cover Preview -->
                            <div id="coverPreview" class="mt-3" style="display: none;">
                                <label class="form-label fw-bold">معاينة الغلاف الجديد:</label>
                                <div>
                                    <img id="coverPreviewImg" class="img-fluid rounded shadow" 
                                         style="max-height: 120px; width: 100%; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Status Card -->
                <div class="card shadow mt-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-user-cog me-2"></i>حالة الحساب
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="role" class="form-label">دور المستخدم:</label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                                <option value="user" {{ $userData['role'] == 'user' ? 'selected' : '' }}>مستخدم عادي</option>
                                <option value="admin" {{ $userData['role'] == 'admin' ? 'selected' : '' }}>مدير</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="account_hidden" name="account_hidden" 
                                   {{ $userData['account_hidden'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="account_hidden">
                                إخفاء الحساب
                            </label>
                            <small class="form-text text-muted d-block">
                                الحساب المخفي لن يظهر في نتائج البحث
                            </small>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="photo_verified" name="photo_verified" 
                                   {{ $userData['photo_verified'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="photo_verified">
                                صورة مؤكدة
                            </label>
                            <small class="form-text text-muted d-block">
                                تمييز الحساب كصورة مؤكدة
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Information Section -->
            <div class="col-xl-8 col-lg-7">
                <!-- Basic Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-user me-2"></i>المعلومات الأساسية
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">اسم المستخدم:</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       id="username" name="username" value="{{ old('username', $userData['username']) }}" 
                                       readonly>
                                <small class="text-muted">لا يمكن تغيير اسم المستخدم</small>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني:</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $userData['email']) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">الاسم الكامل:</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $userData['name']) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="age" class="form-label">العمر:</label>
                                <input type="number" class="form-control @error('age') is-invalid @enderror" 
                                       id="age" name="age" value="{{ old('age', $userData['age']) }}" 
                                       min="18" max="100">
                                @error('age')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">الجنس:</label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                    <option value="">اختر الجنس</option>
                                    <option value="male" {{ old('gender', $userData['gender']) == 'male' ? 'selected' : '' }}>ذكر</option>
                                    <option value="female" {{ old('gender', $userData['gender']) == 'female' ? 'selected' : '' }}>أنثى</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone_number" class="form-label">رقم الهاتف:</label>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                                       id="phone_number" name="phone_number" value="{{ old('phone_number', $userData['phone_number']) }}">
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="bio" class="form-label">النبذة الشخصية:</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" 
                                          id="bio" name="bio" rows="3" maxlength="500">{{ old('bio', $userData['bio']) }}</textarea>
                                <small class="text-muted">الحد الأقصى: 500 حرف</small>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-map-marker-alt me-2"></i>معلومات الموقع
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">البلد:</label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                       id="country" name="country" value="{{ old('country', $userData['country']) }}">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">المدينة:</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city', $userData['city']) }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                <label for="coins" class="form-label">العملات:</label>
                                <input type="number" class="form-control @error('coins') is-invalid @enderror" 
                                       id="coins" name="coins" value="{{ old('coins', $userData['coins']) }}" 
                                       min="0">
                                @error('coins')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="diamonds" class="form-label">الماس:</label>
                                <input type="number" class="form-control @error('diamonds') is-invalid @enderror" 
                                       id="diamonds" name="diamonds" value="{{ old('diamonds', $userData['diamonds']) }}" 
                                       min="0">
                                @error('diamonds')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="user_points" class="form-label">النقاط:</label>
                                <input type="number" class="form-control @error('user_points') is-invalid @enderror" 
                                       id="user_points" name="user_points" value="{{ old('user_points', $userData['user_points']) }}" 
                                       min="0">
                                @error('user_points')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>حفظ التغييرات
                                </button>
                                <button type="reset" class="btn btn-secondary btn-lg ms-2">
                                    <i class="fas fa-undo me-2"></i>إعادة تعيين
                                </button>
                            </div>
                            <div>
                                <a href="{{ route('admin.users.show', $userData['id']) }}" class="btn btn-outline-info btn-lg">
                                    <i class="fas fa-eye me-2"></i>عرض المستخدم
                                </a>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-lg ms-2">
                                    <i class="fas fa-arrow-left me-2"></i>العودة للقائمة
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
// معاينة الصور قبل الرفع
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const previewDiv = document.getElementById(previewId);
            const previewImg = document.getElementById(previewId + 'Img');
            
            previewImg.src = e.target.result;
            previewDiv.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// التحقق من حجم الملف
document.getElementById('avatar').addEventListener('change', function() {
    const file = this.files[0];
    if (file && file.size > 2 * 1024 * 1024) { // 2MB
        alert('حجم الصورة الشخصية يجب أن يكون أقل من 2 ميجابايت');
        this.value = '';
        document.getElementById('avatarPreview').style.display = 'none';
    }
});

document.getElementById('cover').addEventListener('change', function() {
    const file = this.files[0];
    if (file && file.size > 5 * 1024 * 1024) { // 5MB
        alert('حجم صورة الغلاف يجب أن يكون أقل من 5 ميجابايت');
        this.value = '';
        document.getElementById('coverPreview').style.display = 'none';
    }
});

// عداد الأحرف للنبذة الشخصية
document.getElementById('bio').addEventListener('input', function() {
    const maxLength = 500;
    const currentLength = this.value.length;
    const remaining = maxLength - currentLength;
    
    // إنشاء أو تحديث عداد الأحرف
    let counter = document.getElementById('bioCounter');
    if (!counter) {
        counter = document.createElement('small');
        counter.id = 'bioCounter';
        counter.className = 'text-muted';
        this.parentNode.appendChild(counter);
    }
    
    counter.textContent = `المتبقي: ${remaining} حرف`;
    
    if (remaining < 0) {
        counter.className = 'text-danger';
        this.classList.add('is-invalid');
    } else {
        counter.className = 'text-muted';
        this.classList.remove('is-invalid');
    }
});

// تأكيد إعادة التعيين
document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
    if (!confirm('هل أنت متأكد من إعادة تعيين جميع التغييرات؟')) {
        e.preventDefault();
    } else {
        // إخفاء معاينات الصور
        document.getElementById('avatarPreview').style.display = 'none';
        document.getElementById('coverPreview').style.display = 'none';
    }
});

// التحقق من صحة النموذج قبل الإرسال
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = ['email', 'name'];
    let isValid = true;
    
    requiredFields.forEach(function(fieldName) {
        const field = document.getElementById(fieldName);
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('يرجى ملء جميع الحقول المطلوبة');
    }
});
</script>
@endsection

@section('styles')
<style>
.form-label {
    font-weight: 600;
    color: #5a5c69;
}

.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
}

.is-invalid {
    border-color: #e74a3b;
}

.invalid-feedback {
    display: block;
}

#avatarPreview, #coverPreview {
    border: 2px dashed #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
    background-color: #f8f9fc;
}

.form-check-label {
    font-weight: 500;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "←";
}
</style>
@endsection

