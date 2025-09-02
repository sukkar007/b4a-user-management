@extends('layouts.admin')

@section('title', 'إنشاء وكالة جديدة')

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
            <li class="breadcrumb-item active">إنشاء وكالة جديدة</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus-circle text-success me-2"></i>
                إنشاء وكالة جديدة
            </h1>
            <p class="text-muted mb-0">إنشاء وكالة جديدة وتعيين مضيف لها</p>
        </div>
        <a href="{{ route('admin.agencies.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-1"></i>
            العودة للوكالات
        </a>
    </div>

    <!-- Create Agency Form -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-building me-2"></i>
                        معلومات الوكالة الجديدة
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.agencies.store') }}" id="createAgencyForm">
                        @csrf
                        
                        <!-- Host Selection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-user-tie me-2"></i>
                                    اختيار المضيف
                                </h5>
                                
                                <div class="mb-3">
                                    <label for="host_search" class="form-label">البحث عن المضيف</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="host_search" 
                                               placeholder="ابحث باسم المستخدم أو البريد الإلكتروني...">
                                        <button class="btn btn-outline-primary" type="button" onclick="searchHosts()">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">ابحث عن المستخدم الذي تريد تعيينه كمضيف للوكالة</div>
                                </div>

                                <!-- Search Results -->
                                <div id="hostSearchResults" class="mb-3" style="display: none;">
                                    <label class="form-label">نتائج البحث</label>
                                    <div class="border rounded p-3 bg-light" style="max-height: 300px; overflow-y: auto;">
                                        <div id="hostResultsList">
                                            <!-- Search results will be populated here -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Selected Host -->
                                <div id="selectedHostContainer" style="display: none;">
                                    <label class="form-label">المضيف المحدد</label>
                                    <div class="card border-success">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-md me-3">
                                                    <img id="selectedHostAvatar" src="" alt="Avatar" 
                                                         class="rounded-circle" width="50" height="50">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1" id="selectedHostName"></h6>
                                                    <p class="text-muted mb-0" id="selectedHostUsername"></p>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="clearSelectedHost()">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="selected_host_id" name="host_id" required>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Agency Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-cogs me-2"></i>
                                    إعدادات الوكالة
                                </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_members" class="form-label">الحد الأقصى للأعضاء</label>
                                    <input type="number" class="form-control" id="max_members" name="max_members" 
                                           value="50" min="1" max="200" required>
                                    <div class="form-text">عدد الأعضاء الأقصى المسموح في الوكالة (1-200)</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="commission_rate" class="form-label">معدل العمولة (%)</label>
                                    <input type="number" class="form-control" id="commission_rate" name="commission_rate" 
                                           value="10" min="0" max="50" step="0.1" required>
                                    <div class="form-text">نسبة العمولة التي تحصل عليها الوكالة (0-50%)</div>
                                </div>
                            </div>
                        </div>

                        <!-- Agency Permissions -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    صلاحيات الوكالة
                                </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="can_invite_members" 
                                           name="can_invite_members" checked>
                                    <label class="form-check-label" for="can_invite_members">
                                        <strong>دعوة أعضاء جدد</strong>
                                        <div class="form-text">السماح للمضيف بدعوة أعضاء جدد للوكالة</div>
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="can_remove_members" 
                                           name="can_remove_members" checked>
                                    <label class="form-check-label" for="can_remove_members">
                                        <strong>إزالة الأعضاء</strong>
                                        <div class="form-text">السماح للمضيف بإزالة الأعضاء من الوكالة</div>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="can_manage_earnings" 
                                           name="can_manage_earnings" checked>
                                    <label class="form-check-label" for="can_manage_earnings">
                                        <strong>إدارة الأرباح</strong>
                                        <div class="form-text">السماح للمضيف بإدارة أرباح الأعضاء</div>
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="can_view_analytics" 
                                           name="can_view_analytics" checked>
                                    <label class="form-check-label" for="can_view_analytics">
                                        <strong>عرض الإحصائيات</strong>
                                        <div class="form-text">السماح للمضيف بعرض إحصائيات الوكالة</div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-sliders-h me-2"></i>
                                    إعدادات إضافية
                                </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="auto_approve_invitations" class="form-label">الموافقة التلقائية على الدعوات</label>
                                    <select class="form-select" id="auto_approve_invitations" name="auto_approve_invitations">
                                        <option value="0">تتطلب موافقة يدوية</option>
                                        <option value="1">موافقة تلقائية</option>
                                    </select>
                                    <div class="form-text">تحديد ما إذا كانت الدعوات تحتاج موافقة يدوية أم لا</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="agency_status" class="form-label">حالة الوكالة</label>
                                    <select class="form-select" id="agency_status" name="agency_status">
                                        <option value="active">نشطة</option>
                                        <option value="inactive">غير نشطة</option>
                                        <option value="suspended">معلقة</option>
                                    </select>
                                    <div class="form-text">حالة الوكالة الأولية</div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">ملاحظات إدارية</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3" 
                                              placeholder="أضف أي ملاحظات إدارية حول هذه الوكالة..."></textarea>
                                    <div class="form-text">ملاحظات داخلية للإدارة (لن تظهر للمضيف أو الأعضاء)</div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.agencies.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        إلغاء
                                    </a>
                                    <div>
                                        <button type="button" class="btn btn-outline-primary me-2" onclick="previewAgency()">
                                            <i class="fas fa-eye me-1"></i>
                                            معاينة
                                        </button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save me-1"></i>
                                            إنشاء الوكالة
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>
                    معاينة الوكالة الجديدة
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="previewContent">
                    <!-- Preview content will be populated here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <button type="button" class="btn btn-success" onclick="submitForm()">
                    <i class="fas fa-save me-1"></i>
                    إنشاء الوكالة
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-md img {
    object-fit: cover;
}

.host-result-item {
    cursor: pointer;
    transition: all 0.3s ease;
}

.host-result-item:hover {
    background-color: #e3f2fd;
    transform: translateY(-1px);
}

.host-result-item.selected {
    background-color: #c8e6c9;
    border-color: #4caf50;
}

.form-check-label strong {
    color: #495057;
}

.card:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.border-success {
    border-color: #28a745 !important;
}

#hostSearchResults {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
let selectedHost = null;

// Search for hosts
function searchHosts() {
    const searchTerm = document.getElementById('host_search').value.trim();
    
    if (searchTerm.length < 2) {
        alert('يرجى إدخال حرفين على الأقل للبحث');
        return;
    }
    
    // Show loading
    const resultsContainer = document.getElementById('hostSearchResults');
    const resultsList = document.getElementById('hostResultsList');
    
    resultsContainer.style.display = 'block';
    resultsList.innerHTML = '<div class="text-center py-3"><i class="fas fa-spinner fa-spin"></i> جاري البحث...</div>';
    
    // Simulate API call (replace with actual AJAX call)
    setTimeout(() => {
        // Mock search results
        const mockResults = [
            {
                id: 'user1',
                username: 'ahmed_host',
                fullName: 'أحمد محمد',
                email: 'ahmed@example.com',
                avatar: null,
                verified: true,
                canBeHost: true
            },
            {
                id: 'user2',
                username: 'sara_manager',
                fullName: 'سارة أحمد',
                email: 'sara@example.com',
                avatar: null,
                verified: true,
                canBeHost: true
            }
        ];
        
        displaySearchResults(mockResults);
    }, 1000);
}

// Display search results
function displaySearchResults(results) {
    const resultsList = document.getElementById('hostResultsList');
    
    if (results.length === 0) {
        resultsList.innerHTML = '<div class="text-center py-3 text-muted">لم يتم العثور على نتائج</div>';
        return;
    }
    
    let html = '';
    results.forEach(user => {
        const avatarHtml = user.avatar 
            ? `<img src="${user.avatar}" alt="Avatar" class="rounded-circle" width="40" height="40">`
            : `<div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fas fa-user"></i></div>`;
        
        const verifiedBadge = user.verified 
            ? '<span class="badge bg-success ms-2"><i class="fas fa-check"></i> موثق</span>'
            : '';
        
        const canHostBadge = user.canBeHost 
            ? '<span class="badge bg-info ms-2"><i class="fas fa-star"></i> مؤهل للاستضافة</span>'
            : '<span class="badge bg-warning ms-2"><i class="fas fa-exclamation"></i> غير مؤهل</span>';
        
        html += `
            <div class="host-result-item border rounded p-3 mb-2 ${!user.canBeHost ? 'opacity-50' : ''}" 
                 onclick="${user.canBeHost ? `selectHost('${user.id}', '${user.username}', '${user.fullName}', '${user.email}', '${user.avatar || ''}')` : ''}">
                <div class="d-flex align-items-center">
                    <div class="me-3">${avatarHtml}</div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">${user.fullName}</h6>
                        <p class="text-muted mb-1">@${user.username}</p>
                        <small class="text-muted">${user.email}</small>
                    </div>
                    <div class="text-end">
                        ${verifiedBadge}
                        ${canHostBadge}
                    </div>
                </div>
            </div>
        `;
    });
    
    resultsList.innerHTML = html;
}

// Select a host
function selectHost(id, username, fullName, email, avatar) {
    selectedHost = { id, username, fullName, email, avatar };
    
    // Update selected host display
    document.getElementById('selected_host_id').value = id;
    document.getElementById('selectedHostName').textContent = fullName;
    document.getElementById('selectedHostUsername').textContent = `@${username}`;
    
    const avatarElement = document.getElementById('selectedHostAvatar');
    if (avatar) {
        avatarElement.src = avatar;
        avatarElement.style.display = 'block';
    } else {
        avatarElement.style.display = 'none';
    }
    
    // Show selected host container
    document.getElementById('selectedHostContainer').style.display = 'block';
    
    // Hide search results
    document.getElementById('hostSearchResults').style.display = 'none';
    
    // Clear search input
    document.getElementById('host_search').value = '';
}

// Clear selected host
function clearSelectedHost() {
    selectedHost = null;
    document.getElementById('selectedHostContainer').style.display = 'none';
    document.getElementById('selected_host_id').value = '';
}

// Preview agency
function previewAgency() {
    if (!selectedHost) {
        alert('يرجى اختيار مضيف للوكالة أولاً');
        return;
    }
    
    const formData = new FormData(document.getElementById('createAgencyForm'));
    
    let previewHtml = `
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-primary mb-3">معلومات المضيف</h6>
                <div class="card border-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                ${selectedHost.avatar 
                                    ? `<img src="${selectedHost.avatar}" alt="Avatar" class="rounded-circle" width="50" height="50">`
                                    : `<div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;"><i class="fas fa-user"></i></div>`
                                }
                            </div>
                            <div>
                                <h6 class="mb-1">${selectedHost.fullName}</h6>
                                <p class="text-muted mb-0">@${selectedHost.username}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h6 class="text-primary mb-3">إعدادات الوكالة</h6>
                <ul class="list-unstyled">
                    <li><strong>الحد الأقصى للأعضاء:</strong> ${formData.get('max_members')}</li>
                    <li><strong>معدل العمولة:</strong> ${formData.get('commission_rate')}%</li>
                    <li><strong>الحالة:</strong> ${getStatusText(formData.get('agency_status'))}</li>
                    <li><strong>الموافقة التلقائية:</strong> ${formData.get('auto_approve_invitations') === '1' ? 'نعم' : 'لا'}</li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <h6 class="text-primary mb-3">الصلاحيات</h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li>${formData.get('can_invite_members') ? '✅' : '❌'} دعوة أعضاء جدد</li>
                            <li>${formData.get('can_remove_members') ? '✅' : '❌'} إزالة الأعضاء</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li>${formData.get('can_manage_earnings') ? '✅' : '❌'} إدارة الأرباح</li>
                            <li>${formData.get('can_view_analytics') ? '✅' : '❌'} عرض الإحصائيات</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    if (formData.get('notes')) {
        previewHtml += `
            <hr>
            <div class="row">
                <div class="col-12">
                    <h6 class="text-primary mb-3">الملاحظات الإدارية</h6>
                    <p class="text-muted">${formData.get('notes')}</p>
                </div>
            </div>
        `;
    }
    
    document.getElementById('previewContent').innerHTML = previewHtml;
    new bootstrap.Modal(document.getElementById('previewModal')).show();
}

// Submit form
function submitForm() {
    document.getElementById('createAgencyForm').submit();
}

// Helper function
function getStatusText(status) {
    const statusMap = {
        'active': 'نشطة',
        'inactive': 'غير نشطة',
        'suspended': 'معلقة'
    };
    return statusMap[status] || status;
}

// Form validation
document.getElementById('createAgencyForm').addEventListener('submit', function(e) {
    if (!selectedHost) {
        e.preventDefault();
        alert('يرجى اختيار مضيف للوكالة');
        return false;
    }
    
    // Additional validation can be added here
    return true;
});

// Auto-search on Enter key
document.getElementById('host_search').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        searchHosts();
    }
});
</script>
@endsection

