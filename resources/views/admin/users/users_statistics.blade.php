@extends('layouts.admin')

@section('title', 'إحصائيات المستخدمين')

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
                    <li class="breadcrumb-item active">الإحصائيات</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-chart-line me-2"></i>إحصائيات المستخدمين
                    </h1>
                    <p class="text-muted">تحليل شامل لبيانات المستخدمين والنشاط</p>
                </div>
                <div>
                    <button class="btn btn-primary" onclick="refreshStats()">
                        <i class="fas fa-sync-alt me-2"></i>تحديث
                    </button>
                    <button class="btn btn-success" onclick="exportStats()">
                        <i class="fas fa-download me-2"></i>تصدير
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Overview Cards -->
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
                                {{ number_format($stats['total_users']) }}
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
                                {{ number_format($stats['active_users']) }}
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
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="vipUsers">
                                        {{ number_format($stats['vip_users']) }}
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar"
                                             style="width: {{ $stats['vip_percentage'] }}%"
                                             aria-valuenow="{{ $stats['vip_percentage'] }}" 
                                             aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
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
                                التسجيلات الجديدة (هذا الشهر)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="newUsers">
                                {{ number_format($stats['new_users_this_month']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- User Registration Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">تسجيلات المستخدمين (آخر 12 شهر)</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow">
                            <div class="dropdown-header">خيارات الرسم البياني:</div>
                            <a class="dropdown-item" href="#" onclick="changeChartType('line')">خط</a>
                            <a class="dropdown-item" href="#" onclick="changeChartType('bar')">أعمدة</a>
                            <a class="dropdown-item" href="#" onclick="changeChartType('area')">منطقة</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="registrationChart" width="100%" height="40"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gender Distribution -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">توزيع الجنس</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="genderChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> ذكور ({{ $stats['male_percentage'] }}%)
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> إناث ({{ $stats['female_percentage'] }}%)
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Age Distribution -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">توزيع الأعمار</h6>
                </div>
                <div class="card-body">
                    <canvas id="ageChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>

        <!-- VIP Types Distribution -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">توزيع أنواع VIP</h6>
                </div>
                <div class="card-body">
                    <canvas id="vipChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Countries -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">أكثر البلدان نشاطاً</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>البلد</th>
                                    <th>عدد المستخدمين</th>
                                    <th>النسبة</th>
                                    <th>التقدم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['top_countries'] as $country)
                                <tr>
                                    <td>{{ $country['name'] }}</td>
                                    <td>{{ number_format($country['count']) }}</td>
                                    <td>{{ $country['percentage'] }}%</td>
                                    <td>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-primary" role="progressbar"
                                                 style="width: {{ $country['percentage'] }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Statistics -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">إحصائيات النشاط</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="h4 mb-0 text-primary">{{ number_format($stats['total_posts']) }}</div>
                                <small class="text-muted">إجمالي المنشورات</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="h4 mb-0 text-success">{{ number_format($stats['total_messages']) }}</div>
                                <small class="text-muted">إجمالي الرسائل</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="h4 mb-0 text-info">{{ number_format($stats['total_gifts']) }}</div>
                                <small class="text-muted">إجمالي الهدايا</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="h4 mb-0 text-warning">{{ number_format($stats['verified_users']) }}</div>
                                <small class="text-muted">المستخدمون المؤكدون</small>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>معدل النشاط اليومي</span>
                                <span class="badge bg-success">{{ $stats['daily_activity_rate'] }}%</span>
                            </div>
                            <div class="progress mt-2">
                                <div class="progress-bar bg-success" role="progressbar"
                                     style="width: {{ $stats['daily_activity_rate'] }}%"></div>
                            </div>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>معدل الاحتفاظ بالمستخدمين</span>
                                <span class="badge bg-info">{{ $stats['retention_rate'] }}%</span>
                            </div>
                            <div class="progress mt-2">
                                <div class="progress-bar bg-info" role="progressbar"
                                     style="width: {{ $stats['retention_rate'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Statistics -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-coins me-2"></i>الإحصائيات المالية
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card bg-primary text-white shadow">
                                <div class="card-body">
                                    <div class="text-white-50 small">إجمالي العملات</div>
                                    <div class="text-white h4">{{ number_format($stats['total_coins']) }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card bg-success text-white shadow">
                                <div class="card-body">
                                    <div class="text-white-50 small">إجمالي الماس</div>
                                    <div class="text-white h4">{{ number_format($stats['total_diamonds']) }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card bg-info text-white shadow">
                                <div class="card-body">
                                    <div class="text-white-50 small">إجمالي النقاط</div>
                                    <div class="text-white h4">{{ number_format($stats['total_points']) }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card bg-warning text-white shadow">
                                <div class="card-body">
                                    <div class="text-white-50 small">قيمة الهدايا</div>
                                    <div class="text-white h4">${{ number_format($stats['total_gifts_value']) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// بيانات الرسوم البيانية
const chartData = {
    registration: @json($stats['registration_chart']),
    gender: @json($stats['gender_chart']),
    age: @json($stats['age_chart']),
    vip: @json($stats['vip_chart'])
};

// رسم بياني للتسجيلات
let registrationChart;
function initRegistrationChart() {
    const ctx = document.getElementById('registrationChart').getContext('2d');
    registrationChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.registration.labels,
            datasets: [{
                label: 'تسجيلات جديدة',
                data: chartData.registration.data,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            }
        }
    });
}

// رسم بياني للجنس
function initGenderChart() {
    const ctx = document.getElementById('genderChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: chartData.gender.labels,
            datasets: [{
                data: chartData.gender.data,
                backgroundColor: ['#4e73df', '#1cc88a'],
                hoverBackgroundColor: ['#2e59d9', '#17a673'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%'
        }
    });
}

// رسم بياني للأعمار
function initAgeChart() {
    const ctx = document.getElementById('ageChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.age.labels,
            datasets: [{
                label: 'عدد المستخدمين',
                data: chartData.age.data,
                backgroundColor: '#36b9cc',
                borderColor: '#36b9cc',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// رسم بياني لأنواع VIP
function initVipChart() {
    const ctx = document.getElementById('vipChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: chartData.vip.labels,
            datasets: [{
                data: chartData.vip.data,
                backgroundColor: ['#f6c23e', '#e74a3b', '#5a5c69', '#1cc88a'],
                hoverBackgroundColor: ['#dda20a', '#be2617', '#484848', '#17a673']
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

// تغيير نوع الرسم البياني
function changeChartType(type) {
    registrationChart.destroy();
    const ctx = document.getElementById('registrationChart').getContext('2d');
    
    let config = {
        type: type,
        data: {
            labels: chartData.registration.labels,
            datasets: [{
                label: 'تسجيلات جديدة',
                data: chartData.registration.data,
                borderColor: '#4e73df',
                backgroundColor: type === 'line' ? 'rgba(78, 115, 223, 0.1)' : '#4e73df',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    };
    
    if (type === 'line') {
        config.data.datasets[0].fill = true;
        config.data.datasets[0].tension = 0.3;
    } else if (type === 'area') {
        config.type = 'line';
        config.data.datasets[0].fill = true;
        config.data.datasets[0].tension = 0.3;
    }
    
    registrationChart = new Chart(ctx, config);
}

// تحديث الإحصائيات
function refreshStats() {
    // إظهار مؤشر التحميل
    const refreshBtn = document.querySelector('button[onclick="refreshStats()"]');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري التحديث...';
    refreshBtn.disabled = true;
    
    // محاكاة تحديث البيانات
    setTimeout(() => {
        location.reload();
    }, 2000);
}

// تصدير الإحصائيات
function exportStats() {
    // إنشاء بيانات CSV
    const csvData = [
        ['الإحصائية', 'القيمة'],
        ['إجمالي المستخدمين', '{{ $stats["total_users"] }}'],
        ['المستخدمون النشطون', '{{ $stats["active_users"] }}'],
        ['مستخدمو VIP', '{{ $stats["vip_users"] }}'],
        ['التسجيلات الجديدة هذا الشهر', '{{ $stats["new_users_this_month"] }}'],
        ['نسبة الذكور', '{{ $stats["male_percentage"] }}%'],
        ['نسبة الإناث', '{{ $stats["female_percentage"] }}%']
    ];
    
    // تحويل إلى CSV
    const csvContent = csvData.map(row => row.join(',')).join('\n');
    
    // تحميل الملف
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'user_statistics_' + new Date().toISOString().split('T')[0] + '.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// تهيئة الرسوم البيانية عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    initRegistrationChart();
    initGenderChart();
    initAgeChart();
    initVipChart();
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

.progress-sm {
    height: 0.5rem;
}

.chart-area {
    position: relative;
    height: 400px;
}

.chart-pie {
    position: relative;
    height: 300px;
}

.text-xs {
    font-size: 0.7rem;
}

.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.table-borderless th,
.table-borderless td {
    border: none;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "←";
}

@media (max-width: 768px) {
    .chart-area {
        height: 300px;
    }
    
    .chart-pie {
        height: 250px;
    }
}
</style>
@endsection

