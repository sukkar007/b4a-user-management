@extends('layouts.admin')

@section('title', 'إحصائيات الوكالات')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chart-bar text-info me-2"></i>
                إحصائيات الوكالات
            </h1>
            <p class="text-muted mb-0">تحليل شامل لأداء الوكالات والأعضاء</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.agencies.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-building me-1"></i>
                عودة للوكالات
            </a>
            <button class="btn btn-outline-success" onclick="exportStatistics()">
                <i class="fas fa-download me-1"></i>
                تصدير التقرير
            </button>
            <button class="btn btn-outline-info" onclick="refreshData()">
                <i class="fas fa-sync me-1"></i>
                تحديث البيانات
            </button>
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
                                إجمالي الوكالات
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_agencies']) }}
                            </div>
                            <div class="text-xs text-success">
                                <i class="fas fa-arrow-up"></i>
                                +{{ $stats['new_agencies_this_month'] }} هذا الشهر
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
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
                                إجمالي الأعضاء
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_members']) }}
                            </div>
                            <div class="text-xs text-success">
                                <i class="fas fa-arrow-up"></i>
                                +{{ $stats['new_members_this_month'] }} هذا الشهر
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
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                إجمالي الأرباح
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_earnings']) }}
                            </div>
                            <div class="text-xs text-info">
                                <i class="fas fa-coins"></i>
                                نقطة
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
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                متوسط الأعضاء/وكالة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['avg_members_per_agency'], 1) }}
                            </div>
                            <div class="text-xs text-muted">
                                من أصل {{ $stats['max_members_limit'] }} عضو
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Agency Growth Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-area me-2"></i>
                        نمو الوكالات والأعضاء
                    </h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown">
                            <i class="fas fa-calendar me-1"></i>
                            آخر 12 شهر
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="updateChart('6months')">آخر 6 أشهر</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateChart('12months')">آخر 12 شهر</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateChart('24months')">آخر 24 شهر</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="growthChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Agency Status Distribution -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>
                        توزيع حالة الوكالات
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" width="400" height="300"></canvas>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-success">
                                <i class="fas fa-circle me-1"></i>
                                نشطة
                            </span>
                            <strong>{{ $stats['active_agencies'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-warning">
                                <i class="fas fa-circle me-1"></i>
                                غير نشطة
                            </span>
                            <strong>{{ $stats['inactive_agencies'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-danger">
                                <i class="fas fa-circle me-1"></i>
                                معلقة
                            </span>
                            <strong>{{ $stats['suspended_agencies'] }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Tables -->
    <div class="row mb-4">
        <!-- Top Performing Agencies -->
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-trophy me-2"></i>
                        أفضل الوكالات أداءً
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>الترتيب</th>
                                    <th>المضيف</th>
                                    <th>الأعضاء</th>
                                    <th>الأرباح</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['top_agencies'] as $index => $agency)
                                    <tr>
                                        <td>
                                            @if($index === 0)
                                                <i class="fas fa-crown text-warning"></i>
                                            @elseif($index === 1)
                                                <i class="fas fa-medal text-secondary"></i>
                                            @elseif($index === 2)
                                                <i class="fas fa-award text-warning"></i>
                                            @else
                                                <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    @if($agency['host']->get('avatar'))
                                                        <img src="{{ $agency['host']->get('avatar')->getURL() }}" 
                                                             alt="Avatar" class="rounded-circle" width="30" height="30">
                                                    @else
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 30px; height: 30px; font-size: 12px;">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold" style="font-size: 0.9rem;">
                                                        {{ $agency['host']->get('username') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $agency['member_count'] }}</span>
                                        </td>
                                        <td>
                                            <strong class="text-success">
                                                {{ number_format($agency['total_earnings']) }}
                                            </strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clock me-2"></i>
                        النشاطات الأخيرة
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($stats['recent_activities'] as $activity)
                        <div class="d-flex align-items-center mb-3 {{ $loop->last ? '' : 'border-bottom pb-3' }}">
                            <div class="me-3">
                                @php
                                    $iconClass = $activity['type'] === 'new_agency' ? 'building text-success' : 
                                                ($activity['type'] === 'new_member' ? 'user-plus text-info' : 
                                                ($activity['type'] === 'invitation' ? 'envelope text-warning' : 'cog text-secondary'));
                                @endphp
                                <div class="badge bg-light text-dark rounded-circle p-2">
                                    <i class="fas fa-{{ $iconClass }}"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="font-weight-bold" style="font-size: 0.9rem;">
                                    {{ $activity['description'] }}
                                </div>
                                <small class="text-muted">
                                    {{ $activity['time'] }}
                                </small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Analytics -->
    <div class="row mb-4">
        <!-- Earnings Analysis -->
        <div class="col-xl-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>
                        تحليل الأرباح حسب نوع النشاط
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="earningsChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="col-xl-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        مؤشرات الأداء الرئيسية
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">معدل نمو الوكالات</span>
                            <strong class="text-success">+{{ $stats['agency_growth_rate'] }}%</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ min($stats['agency_growth_rate'], 100) }}%"></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">معدل نمو الأعضاء</span>
                            <strong class="text-info">+{{ $stats['member_growth_rate'] }}%</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: {{ min($stats['member_growth_rate'], 100) }}%"></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">معدل قبول الدعوات</span>
                            <strong class="text-warning">{{ $stats['invitation_acceptance_rate'] }}%</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: {{ $stats['invitation_acceptance_rate'] }}%"></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">متوسط النشاط اليومي</span>
                            <strong class="text-primary">{{ $stats['avg_daily_activity'] }}%</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: {{ $stats['avg_daily_activity'] }}%"></div>
                        </div>
                    </div>

                    <hr>

                    <div class="text-center">
                        <h6 class="text-muted mb-2">إجمالي العمولات المحصلة</h6>
                        <h4 class="text-success mb-0">
                            {{ number_format($stats['total_commissions']) }}
                            <small class="text-muted">نقطة</small>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-download me-2"></i>
                خيارات التصدير والتقارير
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <button class="btn btn-outline-success w-100" onclick="exportData('csv')">
                        <i class="fas fa-file-csv me-2"></i>
                        تصدير CSV
                    </button>
                </div>
                <div class="col-md-3 mb-3">
                    <button class="btn btn-outline-info w-100" onclick="exportData('excel')">
                        <i class="fas fa-file-excel me-2"></i>
                        تصدير Excel
                    </button>
                </div>
                <div class="col-md-3 mb-3">
                    <button class="btn btn-outline-danger w-100" onclick="exportData('pdf')">
                        <i class="fas fa-file-pdf me-2"></i>
                        تقرير PDF
                    </button>
                </div>
                <div class="col-md-3 mb-3">
                    <button class="btn btn-outline-primary w-100" onclick="scheduleReport()">
                        <i class="fas fa-clock me-2"></i>
                        جدولة تقرير
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary { border-left: 0.25rem solid #4e73df !important; }
.border-left-success { border-left: 0.25rem solid #1cc88a !important; }
.border-left-info { border-left: 0.25rem solid #36b9cc !important; }
.border-left-warning { border-left: 0.25rem solid #f6c23e !important; }

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

.progress {
    background-color: #f8f9fc;
}

.card:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.badge.rounded-circle {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart data
const chartData = {
    growth: {
        labels: {!! json_encode($stats['growth_chart']['labels']) !!},
        agencies: {!! json_encode($stats['growth_chart']['agencies']) !!},
        members: {!! json_encode($stats['growth_chart']['members']) !!}
    },
    status: {
        labels: ['نشطة', 'غير نشطة', 'معلقة'],
        data: [{{ $stats['active_agencies'] }}, {{ $stats['inactive_agencies'] }}, {{ $stats['suspended_agencies'] }}]
    },
    earnings: {
        labels: {!! json_encode($stats['earnings_chart']['labels']) !!},
        data: {!! json_encode($stats['earnings_chart']['data']) !!}
    }
};

// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    initializeGrowthChart();
    initializeStatusChart();
    initializeEarningsChart();
});

function initializeGrowthChart() {
    const ctx = document.getElementById('growthChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.growth.labels,
            datasets: [{
                label: 'الوكالات',
                data: chartData.growth.agencies,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.3,
                fill: true
            }, {
                label: 'الأعضاء',
                data: chartData.growth.members,
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28, 200, 138, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
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

function initializeStatusChart() {
    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: chartData.status.labels,
            datasets: [{
                data: chartData.status.data,
                backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

function initializeEarningsChart() {
    const ctx = document.getElementById('earningsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.earnings.labels,
            datasets: [{
                label: 'الأرباح',
                data: chartData.earnings.data,
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#36b9cc',
                    '#f6c23e',
                    '#e74a3b'
                ],
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

// Export functions
function exportStatistics() {
    window.location.href = '{{ route("admin.agencies.statistics.export") }}?format=pdf';
}

function exportData(format) {
    window.location.href = `{{ route("admin.agencies.statistics.export") }}?format=${format}`;
}

function refreshData() {
    location.reload();
}

function updateChart(period) {
    // This would make an AJAX call to get new data
    console.log('Updating chart for period:', period);
}

function scheduleReport() {
    // This would open a modal for scheduling reports
    alert('ميزة جدولة التقارير ستكون متاحة قريباً');
}
</script>
@endsection

