@extends('layouts.admin')

@section('title', 'العمليات المجمعة')

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
                    <li class="breadcrumb-item active">العمليات المجمعة</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tasks me-2"></i>العمليات المجمعة
            </h1>
            <p class="text-muted">تنفيذ عمليات على مجموعة من المستخدمين</p>
        </div>
    </div>

    <!-- Action Selection -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cog me-2"></i>اختيار العملية
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bulkAction" class="form-label fw-bold">نوع العملية:</label>
                            <select class="form-select" id="bulkAction" onchange="showActionForm()">
                                <option value="">اختر العملية</option>
                                <option value="send_notification">إرسال إشعار جماعي</option>
                                <option value="update_vip">تحديث حالة VIP</option>
                                <option value="add_coins">إضافة عملات</option>
                                <option value="add_diamonds">إضافة ماس</option>
                                <option value="update_role">تغيير الدور</option>
                                <option value="hide_accounts">إخفاء الحسابات</option>
                                <option value="verify_photos">تأكيد الصور</option>
                                <option value="export_data">تصدير البيانات</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="userSelection" class="form-label fw-bold">اختيار المستخدمين:</label>
                            <select class="form-select" id="userSelection" onchange="showUserFilters()">
                                <option value="">اختر طريقة الاختيار</option>
                                <option value="all">جميع المستخدمين</option>
                                <option value="active">المستخدمون النشطون فقط</option>
                                <option value="vip">مستخدمو VIP فقط</option>
                                <option value="non_vip">المستخدمون العاديون</option>
                                <option value="by_gender">حسب الجنس</option>
                                <option value="by_country">حسب البلد</option>
                                <option value="by_age">حسب العمر</option>
                                <option value="custom">اختيار مخصص</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Filters -->
    <div id="userFilters" class="row mb-4" style="display: none;">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-filter me-2"></i>فلاتر المستخدمين
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row" id="filterContent">
                        <!-- سيتم ملء المحتوى ديناميكياً -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Form -->
    <div id="actionForm" class="row mb-4" style="display: none;">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit me-2"></i>تفاصيل العملية
                    </h6>
                </div>
                <div class="card-body">
                    <div id="formContent">
                        <!-- سيتم ملء المحتوى ديناميكياً -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Section -->
    <div id="previewSection" class="row mb-4" style="display: none;">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-eye me-2"></i>معاينة العملية
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>تحذير:</strong> ستؤثر هذه العملية على <span id="affectedCount">0</span> مستخدم.
                        يرجى المراجعة بعناية قبل التنفيذ.
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped" id="previewTable">
                            <thead>
                                <tr>
                                    <th>اسم المستخدم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الحالة الحالية</th>
                                    <th>التغيير المطلوب</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- سيتم ملء البيانات ديناميكياً -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-center mt-4">
                        <button class="btn btn-success btn-lg" onclick="executeBulkAction()">
                            <i class="fas fa-play me-2"></i>تنفيذ العملية
                        </button>
                        <button class="btn btn-secondary btn-lg ms-2" onclick="resetForm()">
                            <i class="fas fa-undo me-2"></i>إعادة تعيين
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Section -->
    <div id="progressSection" class="row mb-4" style="display: none;">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-spinner fa-spin me-2"></i>تقدم العملية
                    </h6>
                </div>
                <div class="card-body">
                    <div class="progress mb-3" style="height: 25px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             role="progressbar" style="width: 0%" id="progressBar">
                            0%
                        </div>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="h5 mb-0 text-primary" id="processedCount">0</div>
                            <small class="text-muted">تم المعالجة</small>
                        </div>
                        <div class="col-md-3">
                            <div class="h5 mb-0 text-success" id="successCount">0</div>
                            <small class="text-muted">نجح</small>
                        </div>
                        <div class="col-md-3">
                            <div class="h5 mb-0 text-danger" id="errorCount">0</div>
                            <small class="text-muted">فشل</small>
                        </div>
                        <div class="col-md-3">
                            <div class="h5 mb-0 text-info" id="remainingCount">0</div>
                            <small class="text-muted">متبقي</small>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <div class="alert alert-info" id="currentOperation">
                            جاري التحضير...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Section -->
    <div id="resultsSection" class="row mb-4" style="display: none;">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-check-circle me-2"></i>نتائج العملية
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-success" id="successMessage">
                        <i class="fas fa-check-circle me-2"></i>
                        تم تنفيذ العملية بنجاح!
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped" id="resultsTable">
                            <thead>
                                <tr>
                                    <th>اسم المستخدم</th>
                                    <th>الحالة</th>
                                    <th>التفاصيل</th>
                                    <th>الوقت</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- سيتم ملء النتائج ديناميكياً -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-center mt-4">
                        <button class="btn btn-primary" onclick="downloadResults()">
                            <i class="fas fa-download me-2"></i>تحميل التقرير
                        </button>
                        <button class="btn btn-secondary ms-2" onclick="startNewOperation()">
                            <i class="fas fa-plus me-2"></i>عملية جديدة
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let selectedUsers = [];
let currentAction = '';

// إظهار نموذج العملية
function showActionForm() {
    const action = document.getElementById('bulkAction').value;
    const formDiv = document.getElementById('actionForm');
    const formContent = document.getElementById('formContent');
    
    if (!action) {
        formDiv.style.display = 'none';
        return;
    }
    
    currentAction = action;
    formDiv.style.display = 'block';
    
    // إنشاء النموذج حسب نوع العملية
    switch(action) {
        case 'send_notification':
            formContent.innerHTML = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="notificationTitle" class="form-label">عنوان الإشعار:</label>
                        <input type="text" class="form-control" id="notificationTitle" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="notificationType" class="form-label">نوع الإشعار:</label>
                        <select class="form-select" id="notificationType">
                            <option value="info">معلومات</option>
                            <option value="warning">تحذير</option>
                            <option value="success">نجاح</option>
                            <option value="promotion">ترويج</option>
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="notificationMessage" class="form-label">نص الإشعار:</label>
                        <textarea class="form-control" id="notificationMessage" rows="4" required></textarea>
                    </div>
                </div>
            `;
            break;
            
        case 'update_vip':
            formContent.innerHTML = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="vipType" class="form-label">نوع VIP:</label>
                        <select class="form-select" id="vipType">
                            <option value="normal">VIP عادي</option>
                            <option value="super">Super VIP</option>
                            <option value="diamond">Diamond VIP</option>
                            <option value="remove">إزالة VIP</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="vipDuration" class="form-label">المدة (بالأيام):</label>
                        <input type="number" class="form-control" id="vipDuration" value="30" min="1" max="365">
                    </div>
                </div>
            `;
            break;
            
        case 'add_coins':
            formContent.innerHTML = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="coinsAmount" class="form-label">كمية العملات:</label>
                        <input type="number" class="form-control" id="coinsAmount" min="1" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="coinsReason" class="form-label">سبب الإضافة:</label>
                        <input type="text" class="form-control" id="coinsReason" placeholder="مثال: مكافأة شهرية">
                    </div>
                </div>
            `;
            break;
            
        case 'add_diamonds':
            formContent.innerHTML = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="diamondsAmount" class="form-label">كمية الماس:</label>
                        <input type="number" class="form-control" id="diamondsAmount" min="1" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="diamondsReason" class="form-label">سبب الإضافة:</label>
                        <input type="text" class="form-control" id="diamondsReason" placeholder="مثال: مكافأة خاصة">
                    </div>
                </div>
            `;
            break;
            
        case 'update_role':
            formContent.innerHTML = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="newRole" class="form-label">الدور الجديد:</label>
                        <select class="form-select" id="newRole">
                            <option value="user">مستخدم عادي</option>
                            <option value="admin">مدير</option>
                        </select>
                    </div>
                </div>
            `;
            break;
            
        case 'hide_accounts':
            formContent.innerHTML = `
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="hideAccounts" checked>
                            <label class="form-check-label" for="hideAccounts">
                                إخفاء الحسابات المحددة
                            </label>
                        </div>
                        <small class="text-muted">الحسابات المخفية لن تظهر في نتائج البحث</small>
                    </div>
                </div>
            `;
            break;
            
        case 'verify_photos':
            formContent.innerHTML = `
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="verifyPhotos" checked>
                            <label class="form-check-label" for="verifyPhotos">
                                تأكيد صور المستخدمين المحددين
                            </label>
                        </div>
                        <small class="text-muted">سيتم تمييز هؤلاء المستخدمين كمؤكدين</small>
                    </div>
                </div>
            `;
            break;
            
        case 'export_data':
            formContent.innerHTML = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="exportFormat" class="form-label">تنسيق التصدير:</label>
                        <select class="form-select" id="exportFormat">
                            <option value="csv">CSV</option>
                            <option value="excel">Excel</option>
                            <option value="json">JSON</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="exportFields" class="form-label">الحقول المطلوبة:</label>
                        <select class="form-select" id="exportFields" multiple>
                            <option value="username">اسم المستخدم</option>
                            <option value="email">البريد الإلكتروني</option>
                            <option value="name">الاسم الكامل</option>
                            <option value="gender">الجنس</option>
                            <option value="age">العمر</option>
                            <option value="country">البلد</option>
                            <option value="vip_status">حالة VIP</option>
                            <option value="coins">العملات</option>
                            <option value="diamonds">الماس</option>
                        </select>
                    </div>
                </div>
            `;
            break;
    }
    
    // إضافة زر المعاينة
    formContent.innerHTML += `
        <div class="text-center mt-4">
            <button class="btn btn-primary" onclick="previewAction()">
                <i class="fas fa-eye me-2"></i>معاينة العملية
            </button>
        </div>
    `;
}

// إظهار فلاتر المستخدمين
function showUserFilters() {
    const selection = document.getElementById('userSelection').value;
    const filtersDiv = document.getElementById('userFilters');
    const filterContent = document.getElementById('filterContent');
    
    if (!selection || selection === 'all' || selection === 'active' || selection === 'vip' || selection === 'non_vip') {
        filtersDiv.style.display = 'none';
        return;
    }
    
    filtersDiv.style.display = 'block';
    
    switch(selection) {
        case 'by_gender':
            filterContent.innerHTML = `
                <div class="col-md-6 mb-3">
                    <label for="genderFilter" class="form-label">الجنس:</label>
                    <select class="form-select" id="genderFilter">
                        <option value="male">ذكور</option>
                        <option value="female">إناث</option>
                    </select>
                </div>
            `;
            break;
            
        case 'by_country':
            filterContent.innerHTML = `
                <div class="col-md-6 mb-3">
                    <label for="countryFilter" class="form-label">البلد:</label>
                    <select class="form-select" id="countryFilter">
                        <option value="SA">السعودية</option>
                        <option value="EG">مصر</option>
                        <option value="AE">الإمارات</option>
                        <option value="JO">الأردن</option>
                        <option value="LB">لبنان</option>
                        <option value="other">أخرى</option>
                    </select>
                </div>
            `;
            break;
            
        case 'by_age':
            filterContent.innerHTML = `
                <div class="col-md-6 mb-3">
                    <label for="minAge" class="form-label">العمر الأدنى:</label>
                    <input type="number" class="form-control" id="minAge" min="18" max="100" value="18">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="maxAge" class="form-label">العمر الأعلى:</label>
                    <input type="number" class="form-control" id="maxAge" min="18" max="100" value="65">
                </div>
            `;
            break;
            
        case 'custom':
            filterContent.innerHTML = `
                <div class="col-12 mb-3">
                    <label for="customUserIds" class="form-label">معرفات المستخدمين (مفصولة بفواصل):</label>
                    <textarea class="form-control" id="customUserIds" rows="3" 
                              placeholder="مثال: user1, user2, user3"></textarea>
                </div>
            `;
            break;
    }
}

// معاينة العملية
function previewAction() {
    // التحقق من اكتمال البيانات
    if (!validateForm()) {
        return;
    }
    
    // إظهار قسم المعاينة
    document.getElementById('previewSection').style.display = 'block';
    
    // محاكاة جلب البيانات
    const mockUsers = [
        { username: 'user1', email: 'user1@example.com', current: 'عادي', change: 'VIP' },
        { username: 'user2', email: 'user2@example.com', current: 'عادي', change: 'VIP' },
        { username: 'user3', email: 'user3@example.com', current: 'عادي', change: 'VIP' }
    ];
    
    // تحديث عدد المستخدمين المتأثرين
    document.getElementById('affectedCount').textContent = mockUsers.length;
    
    // ملء جدول المعاينة
    const tbody = document.querySelector('#previewTable tbody');
    tbody.innerHTML = '';
    
    mockUsers.forEach(user => {
        const row = tbody.insertRow();
        row.innerHTML = `
            <td>${user.username}</td>
            <td>${user.email}</td>
            <td><span class="badge bg-secondary">${user.current}</span></td>
            <td><span class="badge bg-primary">${user.change}</span></td>
        `;
    });
    
    // التمرير إلى قسم المعاينة
    document.getElementById('previewSection').scrollIntoView({ behavior: 'smooth' });
}

// تنفيذ العملية المجمعة
function executeBulkAction() {
    if (!confirm('هل أنت متأكد من تنفيذ هذه العملية؟ لا يمكن التراجع عنها.')) {
        return;
    }
    
    // إخفاء قسم المعاينة وإظهار قسم التقدم
    document.getElementById('previewSection').style.display = 'none';
    document.getElementById('progressSection').style.display = 'block';
    
    // محاكاة تنفيذ العملية
    simulateExecution();
}

// محاكاة تنفيذ العملية
function simulateExecution() {
    const totalUsers = 50;
    let processed = 0;
    let success = 0;
    let errors = 0;
    
    const interval = setInterval(() => {
        processed += Math.floor(Math.random() * 5) + 1;
        success += Math.floor(Math.random() * 4) + 1;
        errors += Math.floor(Math.random() * 2);
        
        if (processed > totalUsers) {
            processed = totalUsers;
            success = Math.min(success, totalUsers);
            errors = processed - success;
        }
        
        const percentage = Math.round((processed / totalUsers) * 100);
        
        // تحديث شريط التقدم
        const progressBar = document.getElementById('progressBar');
        progressBar.style.width = percentage + '%';
        progressBar.textContent = percentage + '%';
        
        // تحديث الإحصائيات
        document.getElementById('processedCount').textContent = processed;
        document.getElementById('successCount').textContent = success;
        document.getElementById('errorCount').textContent = errors;
        document.getElementById('remainingCount').textContent = totalUsers - processed;
        
        // تحديث العملية الحالية
        document.getElementById('currentOperation').textContent = 
            `جاري معالجة المستخدم ${processed} من ${totalUsers}...`;
        
        if (processed >= totalUsers) {
            clearInterval(interval);
            showResults();
        }
    }, 200);
}

// إظهار النتائج
function showResults() {
    document.getElementById('progressSection').style.display = 'none';
    document.getElementById('resultsSection').style.display = 'block';
    
    // ملء جدول النتائج
    const tbody = document.querySelector('#resultsTable tbody');
    tbody.innerHTML = '';
    
    const mockResults = [
        { username: 'user1', status: 'نجح', details: 'تم تحديث الحالة', time: new Date().toLocaleTimeString() },
        { username: 'user2', status: 'نجح', details: 'تم تحديث الحالة', time: new Date().toLocaleTimeString() },
        { username: 'user3', status: 'فشل', details: 'خطأ في الاتصال', time: new Date().toLocaleTimeString() }
    ];
    
    mockResults.forEach(result => {
        const row = tbody.insertRow();
        const statusClass = result.status === 'نجح' ? 'success' : 'danger';
        row.innerHTML = `
            <td>${result.username}</td>
            <td><span class="badge bg-${statusClass}">${result.status}</span></td>
            <td>${result.details}</td>
            <td>${result.time}</td>
        `;
    });
    
    document.getElementById('resultsSection').scrollIntoView({ behavior: 'smooth' });
}

// التحقق من صحة النموذج
function validateForm() {
    const action = document.getElementById('bulkAction').value;
    const selection = document.getElementById('userSelection').value;
    
    if (!action) {
        alert('يرجى اختيار نوع العملية');
        return false;
    }
    
    if (!selection) {
        alert('يرجى اختيار المستخدمين');
        return false;
    }
    
    return true;
}

// إعادة تعيين النموذج
function resetForm() {
    document.getElementById('bulkAction').value = '';
    document.getElementById('userSelection').value = '';
    document.getElementById('actionForm').style.display = 'none';
    document.getElementById('userFilters').style.display = 'none';
    document.getElementById('previewSection').style.display = 'none';
    document.getElementById('progressSection').style.display = 'none';
    document.getElementById('resultsSection').style.display = 'none';
}

// تحميل النتائج
function downloadResults() {
    const csvData = [
        ['اسم المستخدم', 'الحالة', 'التفاصيل', 'الوقت'],
        ['user1', 'نجح', 'تم تحديث الحالة', new Date().toLocaleTimeString()],
        ['user2', 'نجح', 'تم تحديث الحالة', new Date().toLocaleTimeString()],
        ['user3', 'فشل', 'خطأ في الاتصال', new Date().toLocaleTimeString()]
    ];
    
    const csvContent = csvData.map(row => row.join(',')).join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'bulk_operation_results_' + new Date().toISOString().split('T')[0] + '.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// بدء عملية جديدة
function startNewOperation() {
    resetForm();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
@endsection

@section('styles')
<style>
.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.progress {
    background-color: #f8f9fc;
}

.progress-bar-animated {
    animation: progress-bar-stripes 1s linear infinite;
}

@keyframes progress-bar-stripes {
    0% {
        background-position: 1rem 0;
    }
    100% {
        background-position: 0 0;
    }
}

.table-responsive {
    max-height: 400px;
    overflow-y: auto;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "←";
}

.form-label {
    font-weight: 600;
    color: #5a5c69;
}

.alert {
    border: none;
    border-radius: 0.35rem;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endsection

