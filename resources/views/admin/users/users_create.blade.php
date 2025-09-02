@extends('layouts.admin')

@section('title', 'إضافة مستخدم جديد')

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
                    <li class="breadcrumb-item active">إضافة مستخدم جديد</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-plus me-2"></i>إضافة مستخدم جديد
            </h1>
            <p class="text-muted">إنشاء حساب مستخدم جديد في النظام</p>
        </div>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" id="createUserForm">
        @csrf
        
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
                        <!-- Avatar Upload -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">الصورة الشخصية:</label>
                            
                            <!-- Default Avatar Display -->
                            <div class="mb-3">
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto shadow" 
                                     style="width: 120px; height: 120px;" id="defaultAvatar">
                                    <i class="fas fa-user fa-3x text-white"></i>
                                </div>
                            </div>
                            
                            <!-- Avatar Upload Input -->
                            <div class="mb-3">
                                <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                                       id="avatar" name="avatar" accept="image/*" 
                                       onchange="previewImage(this, 'avatarPreview')">
                                <small class="text-muted">الحد الأقصى: 2MB، الأنواع المقبولة: JPG, PNG, GIF</small>
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Avatar Preview -->
                            <div id="avatarPreview" class="mt-3" style="display: none;">
                                <label class="form-label fw-bold">معاينة الصورة:</label>
                                <div>
                                    <img id="avatarPreviewImg" class="rounded-circle shadow" 
                                         width="100" height="100" style="object-fit: cover;">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Cover Upload -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">صورة الغلاف:</label>
                            
                            <!-- Default Cover Display -->
                            <div class="mb-3">
                                <div class="bg-light border rounded d-flex align-items-center justify-content-center" 
                                     style="height: 150px;" id="defaultCover">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            </div>
                            
                            <!-- Cover Upload Input -->
                            <div class="mb-3">
                                <input type="file" class="form-control @error('cover') is-invalid @enderror" 
                                       id="cover" name="cover" accept="image/*" 
                                       onchange="previewImage(this, 'coverPreview')">
                                <small class="text-muted">الحد الأقصى: 5MB، الأنواع المقبولة: JPG, PNG, GIF</small>
                                @error('cover')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Cover Preview -->
                            <div id="coverPreview" class="mt-3" style="display: none;">
                                <label class="form-label fw-bold">معاينة الغلاف:</label>
                                <div>
                                    <img id="coverPreviewImg" class="img-fluid rounded shadow" 
                                         style="max-height: 120px; width: 100%; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Settings Card -->
                <div class="card shadow mt-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-user-cog me-2"></i>إعدادات الحساب
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="role" class="form-label">دور المستخدم:</label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                                <option value="user" {{ old('role', 'user') == 'user' ? 'selected' : '' }}>مستخدم عادي</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>مدير</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="account_hidden" name="account_hidden" 
                                   {{ old('account_hidden') ? 'checked' : '' }}>
                            <label class="form-check-label" for="account_hidden">
                                إخفاء الحساب
                            </label>
                            <small class="form-text text-muted d-block">
                                الحساب المخفي لن يظهر في نتائج البحث
                            </small>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="photo_verified" name="photo_verified" 
                                   {{ old('photo_verified') ? 'checked' : '' }}>
                            <label class="form-check-label" for="photo_verified">
                                صورة مؤكدة
                            </label>
                            <small class="form-text text-muted d-block">
                                تمييز الحساب كصورة مؤكدة
                            </small>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="email_verified" name="email_verified" 
                                   {{ old('email_verified') ? 'checked' : '' }}>
                            <label class="form-check-label" for="email_verified">
                                بريد إلكتروني مؤكد
                            </label>
                            <small class="form-text text-muted d-block">
                                تمييز البريد الإلكتروني كمؤكد
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Information Section -->
            <div class="col-xl-8 col-lg-7">
                <!-- Required Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-user me-2"></i>المعلومات المطلوبة
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">
                                    اسم المستخدم: <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       id="username" name="username" value="{{ old('username') }}" 
                                       required minlength="3" maxlength="20">
                                <small class="text-muted">3-20 حرف، أحرف إنجليزية وأرقام فقط</small>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    البريد الإلكتروني: <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    كلمة المرور: <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required minlength="6">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                        <i class="fas fa-eye" id="passwordToggleIcon"></i>
                                    </button>
                                </div>
                                <small class="text-muted">الحد الأدنى: 6 أحرف</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    تأكيد كلمة المرور: <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye" id="passwordConfirmationToggleIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    الاسم الكامل: <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">
                                    الجنس: <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                    <option value="">اختر الجنس</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Optional Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle me-2"></i>المعلومات الاختيارية
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="age" class="form-label">العمر:</label>
                                <input type="number" class="form-control @error('age') is-invalid @enderror" 
                                       id="age" name="age" value="{{ old('age') }}" 
                                       min="18" max="100">
                                @error('age')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone_number" class="form-label">رقم الهاتف:</label>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                                       id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">البلد:</label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                       id="country" name="country" value="{{ old('country') }}">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">المدينة:</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city') }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="bio" class="form-label">النبذة الشخصية:</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" 
                                          id="bio" name="bio" rows="3" maxlength="500">{{ old('bio') }}</textarea>
                                <small class="text-muted">الحد الأقصى: 500 حرف</small>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Initial Financial Settings -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-coins me-2"></i>الإعدادات المالية الأولية
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="coins" class="form-label">العملات الأولية:</label>
                                <input type="number" class="form-control @error('coins') is-invalid @enderror" 
                                       id="coins" name="coins" value="{{ old('coins', 100) }}" 
                                       min="0">
                                <small class="text-muted">القيمة الافتراضية: 100</small>
                                @error('coins')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="diamonds" class="form-label">الماس الأولي:</label>
                                <input type="number" class="form-control @error('diamonds') is-invalid @enderror" 
                                       id="diamonds" name="diamonds" value="{{ old('diamonds', 0) }}" 
                                       min="0">
                                @error('diamonds')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="user_points" class="form-label">النقاط الأولية:</label>
                                <input type="number" class="form-control @error('user_points') is-invalid @enderror" 
                                       id="user_points" name="user_points" value="{{ old('user_points', 0) }}" 
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
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>إنشاء المستخدم
                                </button>
                                <button type="reset" class="btn btn-secondary btn-lg ms-2">
                                    <i class="fas fa-undo me-2"></i>إعادة تعيين
                                </button>
                            </div>
                            <div>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-lg">
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

// إظهار/إخفاء كلمة المرور
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + 'ToggleIcon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
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

// التحقق من تطابق كلمة المرور
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmation = this.value;
    
    if (password !== confirmation) {
        this.classList.add('is-invalid');
        this.setCustomValidity('كلمات المرور غير متطابقة');
    } else {
        this.classList.remove('is-invalid');
        this.setCustomValidity('');
    }
});

// التحقق من اسم المستخدم (أحرف إنجليزية وأرقام فقط)
document.getElementById('username').addEventListener('input', function() {
    const username = this.value;
    const regex = /^[a-zA-Z0-9_]+$/;
    
    if (username && !regex.test(username)) {
        this.classList.add('is-invalid');
        this.setCustomValidity('اسم المستخدم يجب أن يحتوي على أحرف إنجليزية وأرقام فقط');
    } else {
        this.classList.remove('is-invalid');
        this.setCustomValidity('');
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
    if (!confirm('هل أنت متأكد من إعادة تعيين جميع البيانات؟')) {
        e.preventDefault();
    } else {
        // إخفاء معاينات الصور
        document.getElementById('avatarPreview').style.display = 'none';
        document.getElementById('coverPreview').style.display = 'none';
    }
});

// التحقق من صحة النموذج قبل الإرسال
document.getElementById('createUserForm').addEventListener('submit', function(e) {
    const requiredFields = ['username', 'email', 'password', 'password_confirmation', 'name', 'gender'];
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
    
    // التحقق من تطابق كلمة المرور
    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;
    
    if (password !== confirmation) {
        document.getElementById('password_confirmation').classList.add('is-invalid');
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
        alert('يرجى ملء جميع الحقول المطلوبة والتأكد من صحة البيانات');
        
        // التمرير إلى أول حقل خاطئ
        const firstInvalid = document.querySelector('.is-invalid');
        if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalid.focus();
        }
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

.text-danger {
    color: #e74a3b !important;
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

.input-group .btn {
    border-left: none;
}

.input-group .form-control:focus + .btn {
    border-color: #86b7fe;
}
</style>
@endsection

