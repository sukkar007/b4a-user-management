@extends('layouts.admin')

@section('title', 'العمليات المجمعة للوكالات')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tasks text-warning me-2"></i>
                العمليات المجمعة للوكالات
            </h1>
            <p class="text-muted mb-0">تنفيذ عمليات متعددة على الوكالات والأعضاء</p>
        </div>
        <a href="{{ route('admin.agencies.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للوكالات
        </a>
    </div>

    <!-- Operation Selection -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cog me-2"></i>
                        اختيار نوع العملية
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="operation-card" onclick="selectOperation('send_notifications')">
                                <div class="card h-100 border-primary">
                                    <div class="card-body text-center">
                                        <i class="fas fa-bell fa-3x text-primary mb-3"></i>
                                        <h6 class="card-title">إرسال إشعارات</h6>
                                        <p class="card-text text-muted">إرسال إشعارات جماعية للمضيفين أو الأعضاء</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="operation-card" onclick="selectOperation('update_commissions')">
                                <div class="card h-100 border-success">
                                    <div class="card-body text-center">
                                        <i class="fas fa-percentage fa-3x text-success mb-3"></i>
                                        <h6 class="card-title">تحديث العمولات</h6>
                                        <p class="card-text text-muted">تحديث معدلات العمولة للوكالات المحددة</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="operation-card" onclick="selectOperation('manage_status')">
                                <div class="card h-100 border-warning">
                                    <div class="card-body text-center">
                                        <i class="fas fa-toggle-on fa-3x text-warning mb-3"></i>
                                        <h6 class="card-title">إدارة الحالة</h6>
                                        <p class="card-text text-muted">تفعيل أو تعطيل أو تعليق الوكالات</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="operation-card" onclick="selectOperation('transfer_members')">
                                <div class="card h-100 border-info">
                                    <div class="card-body text-center">
                                        <i class="fas fa-exchange-alt fa-3x text-info mb-3"></i>
                                        <h6 class="card-title">نقل الأعضاء</h6>
                                        <p class="card-text text-muted">نقل أعضاء من وكالة إلى أخرى</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="operation-card" onclick="selectOperation('bulk_invitations')">
                                <div class="card h-100 border-secondary">
                                    <div class="card-body text-center">
                                        <i class="fas fa-envelope-open fa-3x text-secondary mb-3"></i>
                                        <h6 class="card-title">دعوات جماعية</h6>
                                        <p class="card-text text-muted">إرسال دعوات جماعية للمستخدمين</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="operation-card" onclick="selectOperation('update_limits')">
                                <div class="card h-100 border-dark">
                                    <div class="card-body text-center">
                                        <i class="fas fa-users fa-3x text-dark mb-3"></i>
                                        <h6 class="card-title">تحديث الحدود</h6>
                                        <p class="card-text text-muted">تحديث الحد الأقصى للأعضاء</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="operation-card" onclick="selectOperation('export_data')">
                                <div class="card h-100 border-danger">
                                    <div class="card-body text-center">
                                        <i class="fas fa-download fa-3x text-danger mb-3"></i>
                                        <h6 class="card-title">تصدير البيانات</h6>
                                        <p class="card-text text-muted">تصدير بيانات الوكالات والأعضاء</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="operation-card" onclick="selectOperation('cleanup_inactive')">
                                <div class="card h-100 border-warning">
                                    <div class="card-body text-center">
                                        <i class="fas fa-broom fa-3x text-warning mb-3"></i>
                                        <h6 class="card-title">تنظيف البيانات</h6>
                                        <p class="card-text text-muted">إزالة الوكالات والأعضاء غير النشطين</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Operation Forms -->
    <div id="operationForms" style="display: none;">
        <!-- Send Notifications Form -->
        <div id="send_notifications_form" class="operation-form" style="display: none;">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bell me-2"></i>
                        إرسال إشعارات جماعية
                    </h6>
                </div>
                <div class="card-body">
                    <form id="notificationsForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">المستهدفون</label>
                                <select class="form-select" name="target_type" required>
                                    <option value="">اختر المستهدفين</option>
                                    <option value="all_hosts">جميع المضيفين</option>
                                    <option value="all_members">جميع الأعضاء</option>
                                    <option value="active_agencies">الوكالات النشطة فقط</option>
                                    <option value="inactive_agencies">الوكالات غير النشطة</option>
                                    <option value="specific_agencies">وكالات محددة</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">نوع الإشعار</label>
                                <select class="form-select" name="notification_type" required>
                                    <option value="">اختر نوع الإشعار</option>
                                    <option value="announcement">إعلان عام</option>
                                    <option value="warning">تحذير</option>
                                    <option value="update">تحديث</option>
                                    <option value="promotion">ترويج</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">عنوان الإشعار</label>
                            <input type="text" class="form-control" name="notification_title" 
                                   placeholder="أدخل عنوان الإشعار..." required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">محتوى الإشعار</label>
                            <textarea class="form-control" name="notification_content" rows="4" 
                                      placeholder="أدخل محتوى الإشعار..." required></textarea>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="send_push" checked>
                                    <label class="form-check-label">إرسال إشعار فوري</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="send_email">
                                    <label class="form-check-label">إرسال بريد إلكتروني</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Commissions Form -->
        <div id="update_commissions_form" class="operation-form" style="display: none;">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-percentage me-2"></i>
                        تحديث معدلات العمولة
                    </h6>
                </div>
                <div class="card-body">
                    <form id="commissionsForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">نوع التحديث</label>
                                <select class="form-select" name="update_type" required>
                                    <option value="">اختر نوع التحديث</option>
                                    <option value="all_agencies">جميع الوكالات</option>
                                    <option value="by_performance">حسب الأداء</option>
                                    <option value="by_member_count">حسب عدد الأعضاء</option>
                                    <option value="specific_agencies">وكالات محددة</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">معدل العمولة الجديد (%)</label>
                                <input type="number" class="form-control" name="new_commission_rate" 
                                       min="0" max="50" step="0.1" placeholder="10.5" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">سبب التحديث</label>
                            <textarea class="form-control" name="update_reason" rows="3" 
                                      placeholder="أدخل سبب تحديث معدل العمولة..." required></textarea>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="notify_hosts" checked>
                            <label class="form-check-label">إشعار المضيفين بالتحديث</label>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Manage Status Form -->
        <div id="manage_status_form" class="operation-form" style="display: none;">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-toggle-on me-2"></i>
                        إدارة حالة الوكالات
                    </h6>
                </div>
                <div class="card-body">
                    <form id="statusForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">الحالة الجديدة</label>
                                <select class="form-select" name="new_status" required>
                                    <option value="">اختر الحالة الجديدة</option>
                                    <option value="active">نشطة</option>
                                    <option value="inactive">غير نشطة</option>
                                    <option value="suspended">معلقة</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">تطبيق على</label>
                                <select class="form-select" name="apply_to" required>
                                    <option value="">اختر النطاق</option>
                                    <option value="all_agencies">جميع الوكالات</option>
                                    <option value="inactive_only">غير النشطة فقط</option>
                                    <option value="low_performance">ضعيفة الأداء</option>
                                    <option value="specific_agencies">وكالات محددة</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">سبب تغيير الحالة</label>
                            <textarea class="form-control" name="status_reason" rows="3" 
                                      placeholder="أدخل سبب تغيير الحالة..." required></textarea>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="notify_affected" checked>
                            <label class="form-check-label">إشعار المضيفين المتأثرين</label>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Transfer Members Form -->
        <div id="transfer_members_form" class="operation-form" style="display: none;">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-exchange-alt me-2"></i>
                        نقل الأعضاء بين الوكالات
                    </h6>
                </div>
                <div class="card-body">
                    <form id="transferForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">الوكالة المصدر</label>
                                <select class="form-select" name="source_agency" required>
                                    <option value="">اختر الوكالة المصدر</option>
                                    <!-- Will be populated dynamically -->
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الوكالة الهدف</label>
                                <select class="form-select" name="target_agency" required>
                                    <option value="">اختر الوكالة الهدف</option>
                                    <!-- Will be populated dynamically -->
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">معايير النقل</label>
                            <select class="form-select" name="transfer_criteria" required>
                                <option value="">اختر معايير النقل</option>
                                <option value="all_members">جميع الأعضاء</option>
                                <option value="inactive_members">الأعضاء غير النشطين</option>
                                <option value="low_performers">ضعيفي الأداء</option>
                                <option value="specific_members">أعضاء محددين</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">سبب النقل</label>
                            <textarea class="form-control" name="transfer_reason" rows="3" 
                                      placeholder="أدخل سبب نقل الأعضاء..." required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="notify_members" checked>
                                    <label class="form-check-label">إشعار الأعضاء المنقولين</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="preserve_earnings" checked>
                                    <label class="form-check-label">الحفاظ على الأرباح</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Other forms would be similar... -->
    </div>

    <!-- Agency Selection -->
    <div id="agencySelection" style="display: none;">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-check-square me-2"></i>
                    اختيار الوكالات المستهدفة
                </h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="agencySearch" 
                                   placeholder="البحث في الوكالات...">
                            <button class="btn btn-outline-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-success" onclick="selectAllAgencies()">
                                <i class="fas fa-check-double me-1"></i>
                                تحديد الكل
                            </button>
                            <button class="btn btn-outline-warning" onclick="clearSelection()">
                                <i class="fas fa-times me-1"></i>
                                إلغاء التحديد
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th width="50">
                                    <input type="checkbox" id="selectAllCheckbox" onchange="toggleAllSelection()">
                                </th>
                                <th>المضيف</th>
                                <th>الأعضاء</th>
                                <th>الحالة</th>
                                <th>العمولة</th>
                                <th>الأرباح</th>
                            </tr>
                        </thead>
                        <tbody id="agenciesTableBody">
                            <!-- Will be populated dynamically -->
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="selectionCount">لم يتم تحديد أي وكالة</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview and Execute -->
    <div id="previewSection" style="display: none;">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-eye me-2"></i>
                    معاينة العملية
                </h6>
            </div>
            <div class="card-body">
                <div id="previewContent">
                    <!-- Preview content will be populated here -->
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button class="btn btn-secondary" onclick="goBack()">
                        <i class="fas fa-arrow-right me-1"></i>
                        العودة للتعديل
                    </button>
                    <div>
                        <button class="btn btn-outline-primary me-2" onclick="saveAsDraft()">
                            <i class="fas fa-save me-1"></i>
                            حفظ كمسودة
                        </button>
                        <button class="btn btn-success" onclick="executeOperation()">
                            <i class="fas fa-play me-1"></i>
                            تنفيذ العملية
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Section -->
    <div id="progressSection" style="display: none;">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-cogs me-2"></i>
                    تنفيذ العملية
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>التقدم العام</span>
                        <span id="progressPercentage">0%</span>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" 
                             style="width: 0%"></div>
                    </div>
                </div>
                
                <div id="progressLog" class="border rounded p-3 bg-light" style="height: 300px; overflow-y: auto;">
                    <div class="text-muted">جاري بدء العملية...</div>
                </div>
                
                <div class="mt-3">
                    <button id="cancelOperation" class="btn btn-outline-danger" onclick="cancelOperation()">
                        <i class="fas fa-stop me-1"></i>
                        إيقاف العملية
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Section -->
    <div id="resultsSection" style="display: none;">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-check-circle me-2"></i>
                    نتائج العملية
                </h6>
            </div>
            <div class="card-body">
                <div id="resultsContent">
                    <!-- Results will be populated here -->
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button class="btn btn-outline-primary" onclick="downloadReport()">
                        <i class="fas fa-download me-1"></i>
                        تحميل التقرير
                    </button>
                    <div>
                        <button class="btn btn-outline-secondary me-2" onclick="startNewOperation()">
                            <i class="fas fa-plus me-1"></i>
                            عملية جديدة
                        </button>
                        <a href="{{ route('admin.agencies.index') }}" class="btn btn-primary">
                            <i class="fas fa-building me-1"></i>
                            العودة للوكالات
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.operation-card {
    cursor: pointer;
    transition: all 0.3s ease;
}

.operation-card:hover {
    transform: translateY(-5px);
}

.operation-card .card {
    transition: all 0.3s ease;
}

.operation-card:hover .card {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.operation-card.selected .card {
    background-color: #f8f9fc;
    border-width: 2px;
}

.operation-form {
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.sticky-top {
    position: sticky;
    top: 0;
    z-index: 10;
}

.progress-bar-animated {
    animation: progress-bar-stripes 1s linear infinite;
}

#progressLog {
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
}

.log-entry {
    margin-bottom: 5px;
    padding: 2px 0;
}

.log-success { color: #28a745; }
.log-error { color: #dc3545; }
.log-warning { color: #ffc107; }
.log-info { color: #17a2b8; }
</style>

<script>
let currentOperation = null;
let selectedAgencies = [];
let operationData = {};

// Select operation
function selectOperation(operation) {
    currentOperation = operation;
    
    // Remove previous selections
    document.querySelectorAll('.operation-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Add selection to current card
    event.currentTarget.classList.add('selected');
    
    // Hide all forms
    document.querySelectorAll('.operation-form').forEach(form => {
        form.style.display = 'none';
    });
    
    // Show selected form
    document.getElementById(operation + '_form').style.display = 'block';
    document.getElementById('operationForms').style.display = 'block';
    
    // Show agency selection if needed
    if (needsAgencySelection(operation)) {
        document.getElementById('agencySelection').style.display = 'block';
        loadAgencies();
    } else {
        document.getElementById('agencySelection').style.display = 'none';
    }
    
    // Scroll to form
    document.getElementById('operationForms').scrollIntoView({ behavior: 'smooth' });
}

// Check if operation needs agency selection
function needsAgencySelection(operation) {
    const operationsNeedingSelection = [
        'update_commissions', 'manage_status', 'transfer_members', 
        'bulk_invitations', 'update_limits'
    ];
    return operationsNeedingSelection.includes(operation);
}

// Load agencies for selection
function loadAgencies() {
    // Mock data - replace with actual AJAX call
    const mockAgencies = [
        {
            id: 'agency1',
            hostName: 'أحمد محمد',
            hostUsername: 'ahmed_host',
            memberCount: 25,
            status: 'active',
            commissionRate: 10.5,
            totalEarnings: 15000
        },
        {
            id: 'agency2',
            hostName: 'سارة أحمد',
            hostUsername: 'sara_manager',
            memberCount: 18,
            status: 'active',
            commissionRate: 12.0,
            totalEarnings: 12500
        }
    ];
    
    const tbody = document.getElementById('agenciesTableBody');
    tbody.innerHTML = '';
    
    mockAgencies.forEach(agency => {
        const statusBadge = getStatusBadge(agency.status);
        const row = `
            <tr>
                <td>
                    <input type="checkbox" class="agency-checkbox" value="${agency.id}" 
                           onchange="updateSelection()">
                </td>
                <td>
                    <div>
                        <strong>${agency.hostName}</strong>
                        <br>
                        <small class="text-muted">@${agency.hostUsername}</small>
                    </div>
                </td>
                <td>
                    <span class="badge bg-primary">${agency.memberCount}</span>
                </td>
                <td>${statusBadge}</td>
                <td>${agency.commissionRate}%</td>
                <td>
                    <strong class="text-success">${agency.totalEarnings.toLocaleString()}</strong>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

// Get status badge
function getStatusBadge(status) {
    const badges = {
        'active': '<span class="badge bg-success">نشطة</span>',
        'inactive': '<span class="badge bg-warning">غير نشطة</span>',
        'suspended': '<span class="badge bg-danger">معلقة</span>'
    };
    return badges[status] || status;
}

// Update selection
function updateSelection() {
    const checkboxes = document.querySelectorAll('.agency-checkbox:checked');
    selectedAgencies = Array.from(checkboxes).map(cb => cb.value);
    
    const count = selectedAgencies.length;
    const countText = count === 0 ? 'لم يتم تحديد أي وكالة' : 
                     count === 1 ? 'تم تحديد وكالة واحدة' : 
                     `تم تحديد ${count} وكالة`;
    
    document.getElementById('selectionCount').textContent = countText;
    
    // Show preview button if agencies are selected and form is filled
    if (count > 0 && isFormValid()) {
        showPreviewButton();
    }
}

// Select all agencies
function selectAllAgencies() {
    document.querySelectorAll('.agency-checkbox').forEach(cb => {
        cb.checked = true;
    });
    updateSelection();
}

// Clear selection
function clearSelection() {
    document.querySelectorAll('.agency-checkbox').forEach(cb => {
        cb.checked = false;
    });
    document.getElementById('selectAllCheckbox').checked = false;
    updateSelection();
}

// Toggle all selection
function toggleAllSelection() {
    const selectAll = document.getElementById('selectAllCheckbox').checked;
    document.querySelectorAll('.agency-checkbox').forEach(cb => {
        cb.checked = selectAll;
    });
    updateSelection();
}

// Check if form is valid
function isFormValid() {
    const currentForm = document.getElementById(currentOperation + '_form');
    const requiredFields = currentForm.querySelectorAll('[required]');
    
    for (let field of requiredFields) {
        if (!field.value.trim()) {
            return false;
        }
    }
    return true;
}

// Show preview button
function showPreviewButton() {
    if (!document.getElementById('previewButton')) {
        const button = document.createElement('button');
        button.id = 'previewButton';
        button.className = 'btn btn-primary mt-3';
        button.innerHTML = '<i class="fas fa-eye me-1"></i> معاينة العملية';
        button.onclick = showPreview;
        
        document.getElementById('agencySelection').appendChild(button);
    }
}

// Show preview
function showPreview() {
    // Collect form data
    const currentForm = document.getElementById(currentOperation + '_form');
    const formData = new FormData(currentForm.querySelector('form'));
    
    operationData = {
        operation: currentOperation,
        agencies: selectedAgencies,
        formData: Object.fromEntries(formData)
    };
    
    // Generate preview content
    const previewHtml = generatePreviewContent();
    document.getElementById('previewContent').innerHTML = previewHtml;
    
    // Show preview section
    document.getElementById('previewSection').style.display = 'block';
    document.getElementById('previewSection').scrollIntoView({ behavior: 'smooth' });
}

// Generate preview content
function generatePreviewContent() {
    const operationNames = {
        'send_notifications': 'إرسال إشعارات جماعية',
        'update_commissions': 'تحديث معدلات العمولة',
        'manage_status': 'إدارة حالة الوكالات',
        'transfer_members': 'نقل الأعضاء',
        'bulk_invitations': 'دعوات جماعية',
        'update_limits': 'تحديث الحدود',
        'export_data': 'تصدير البيانات',
        'cleanup_inactive': 'تنظيف البيانات'
    };
    
    let html = `
        <div class="alert alert-info">
            <h5><i class="fas fa-info-circle me-2"></i>معاينة العملية: ${operationNames[currentOperation]}</h5>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-primary mb-3">الوكالات المستهدفة</h6>
                <p><strong>عدد الوكالات:</strong> ${selectedAgencies.length}</p>
                <p><strong>المعرفات:</strong> ${selectedAgencies.join(', ')}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-primary mb-3">تفاصيل العملية</h6>
    `;
    
    // Add operation-specific details
    Object.entries(operationData.formData).forEach(([key, value]) => {
        if (value) {
            html += `<p><strong>${getFieldLabel(key)}:</strong> ${value}</p>`;
        }
    });
    
    html += `
            </div>
        </div>
        
        <div class="alert alert-warning mt-3">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>تحذير:</strong> هذه العملية ستؤثر على ${selectedAgencies.length} وكالة. 
            تأكد من صحة البيانات قبل التنفيذ.
        </div>
    `;
    
    return html;
}

// Get field label
function getFieldLabel(fieldName) {
    const labels = {
        'target_type': 'المستهدفون',
        'notification_type': 'نوع الإشعار',
        'notification_title': 'عنوان الإشعار',
        'notification_content': 'محتوى الإشعار',
        'new_commission_rate': 'معدل العمولة الجديد',
        'update_reason': 'سبب التحديث',
        'new_status': 'الحالة الجديدة',
        'status_reason': 'سبب تغيير الحالة'
    };
    return labels[fieldName] || fieldName;
}

// Execute operation
function executeOperation() {
    document.getElementById('previewSection').style.display = 'none';
    document.getElementById('progressSection').style.display = 'block';
    document.getElementById('progressSection').scrollIntoView({ behavior: 'smooth' });
    
    // Start progress simulation
    simulateProgress();
}

// Simulate progress
function simulateProgress() {
    let progress = 0;
    const progressBar = document.getElementById('progressBar');
    const progressPercentage = document.getElementById('progressPercentage');
    const progressLog = document.getElementById('progressLog');
    
    const interval = setInterval(() => {
        progress += Math.random() * 10;
        if (progress > 100) progress = 100;
        
        progressBar.style.width = progress + '%';
        progressPercentage.textContent = Math.round(progress) + '%';
        
        // Add log entries
        if (progress < 30) {
            addLogEntry('info', 'جاري تحضير البيانات...');
        } else if (progress < 60) {
            addLogEntry('info', 'جاري معالجة الوكالات...');
        } else if (progress < 90) {
            addLogEntry('success', 'تم معالجة وكالة بنجاح');
        } else if (progress >= 100) {
            addLogEntry('success', 'تم إكمال العملية بنجاح!');
            clearInterval(interval);
            setTimeout(showResults, 1000);
        }
    }, 500);
}

// Add log entry
function addLogEntry(type, message) {
    const progressLog = document.getElementById('progressLog');
    const timestamp = new Date().toLocaleTimeString('ar-SA');
    const entry = document.createElement('div');
    entry.className = `log-entry log-${type}`;
    entry.innerHTML = `[${timestamp}] ${message}`;
    progressLog.appendChild(entry);
    progressLog.scrollTop = progressLog.scrollHeight;
}

// Show results
function showResults() {
    document.getElementById('progressSection').style.display = 'none';
    document.getElementById('resultsSection').style.display = 'block';
    document.getElementById('resultsSection').scrollIntoView({ behavior: 'smooth' });
    
    // Generate results content
    const resultsHtml = `
        <div class="alert alert-success">
            <h5><i class="fas fa-check-circle me-2"></i>تم إكمال العملية بنجاح!</h5>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <h3 class="text-success">${selectedAgencies.length}</h3>
                        <p class="text-muted mb-0">وكالة تم معالجتها</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-info">
                    <div class="card-body text-center">
                        <h3 class="text-info">${selectedAgencies.length}</h3>
                        <p class="text-muted mb-0">عملية ناجحة</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-warning">
                    <div class="card-body text-center">
                        <h3 class="text-warning">0</h3>
                        <p class="text-muted mb-0">أخطاء</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <h6 class="text-primary mb-3">تفاصيل النتائج</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>الوكالة</th>
                            <th>الحالة</th>
                            <th>الوقت</th>
                            <th>الملاحظات</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${selectedAgencies.map(id => `
                            <tr>
                                <td>${id}</td>
                                <td><span class="badge bg-success">نجح</span></td>
                                <td>${new Date().toLocaleTimeString('ar-SA')}</td>
                                <td>تم التنفيذ بنجاح</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        </div>
    `;
    
    document.getElementById('resultsContent').innerHTML = resultsHtml;
}

// Other functions
function goBack() {
    document.getElementById('previewSection').style.display = 'none';
}

function saveAsDraft() {
    alert('تم حفظ العملية كمسودة');
}

function cancelOperation() {
    if (confirm('هل أنت متأكد من إيقاف العملية؟')) {
        location.reload();
    }
}

function downloadReport() {
    alert('سيتم تحميل التقرير قريباً');
}

function startNewOperation() {
    location.reload();
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    // Add form validation listeners
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('input', function() {
            if (selectedAgencies.length > 0 && isFormValid()) {
                showPreviewButton();
            }
        });
    });
});
</script>
@endsection

