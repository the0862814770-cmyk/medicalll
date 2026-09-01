@extends('layouts.app')
@section('title', 'แดชบอร์ด - ผู้ดูแลระบบ')
@section('page-title', 'แดชบอร์ดผู้ดูแลระบบ')
@section('sidebar') @include('partials.sidebar-admin') @endsection

@section('content')
<style>
    /* Dashboard Specific Styles */
    .welcome-banner {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
        border-radius: var(--radius);
        padding: 30px;
        color: white;
        margin-bottom: 25px;
        box-shadow: var(--shadow-md);
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
    }
    .welcome-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .welcome-subtitle {
        font-size: 15px;
        opacity: 0.9;
        font-weight: 300;
    }
    
    .modern-stat-card {
        background: white;
        border-radius: var(--radius);
        padding: 24px;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .modern-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }
    .modern-stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 4px; height: 100%;
    }
    .stat-blue::before { background: #3b82f6; }
    .stat-green::before { background: #10b981; }
    .stat-red::before { background: #ef4444; }
    .stat-purple::before { background: #8b5cf6; }

    .stat-icon-wrapper {
        width: 54px;
        height: 54px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-right: 16px;
    }
    .stat-blue .stat-icon-wrapper { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .stat-green .stat-icon-wrapper { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .stat-red .stat-icon-wrapper { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .stat-purple .stat-icon-wrapper { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }

    .stat-info .stat-value {
        font-size: 26px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.1;
        margin-bottom: 4px;
    }
    .stat-info .stat-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .premium-panel {
        background: white;
        border-radius: var(--radius);
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: none;
        height: 100%;
    }
    .premium-panel-header {
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .premium-panel-header h5 {
        margin: 0;
        font-weight: 700;
        color: #334155;
        font-size: 17px;
    }
    
    .table-premium th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        padding: 12px 16px;
        border-bottom: 2px solid #e2e8f0;
    }
    .table-premium td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #475569;
        font-size: 14px;
    }
    .table-premium tbody tr {
        transition: background-color 0.2s;
    }
    .table-premium tbody tr:hover {
        background-color: #f8fafc;
    }
    
    .role-badge {
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }
    .role-user { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .role-staff { background: rgba(245, 158, 11, 0.1); color: #d97706; }
    .role-executive { background: rgba(16, 185, 129, 0.1); color: #059669; }
    .role-admin { background: rgba(139, 92, 246, 0.1); color: #7c3aed; }

    .status-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 6px;
    }
    .status-dot.active { background: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2); }
    .status-dot.suspended { background: #ef4444; box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2); }
</style>

<!-- Welcome Banner -->
<div class="welcome-banner animate-in">
    <div class="welcome-title">สวัสดี, {{ Auth::user()->name }}! 👋</div>
    <div class="welcome-subtitle">ภาพรวมระบบผู้ใช้งานประจำวันที่ {{ \Carbon\Carbon::now()->locale('th')->translatedFormat('d M Y') }}</div>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-sm-6 animate-in" style="animation-delay: 0.1s;">
        <div class="modern-stat-card stat-blue">
            <div class="stat-icon-wrapper"><i class="bi bi-people-fill"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
                <div class="stat-label">ผู้ใช้งานทั้งหมด</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 animate-in" style="animation-delay: 0.15s;">
        <div class="modern-stat-card stat-green">
            <div class="stat-icon-wrapper"><i class="bi bi-person-check-fill"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ number_format($stats['active_users']) }}</div>
                <div class="stat-label">บัญชีใช้งานปกติ</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 animate-in" style="animation-delay: 0.2s;">
        <div class="modern-stat-card stat-red">
            <div class="stat-icon-wrapper"><i class="bi bi-person-x-fill"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ number_format($stats['suspended_users']) }}</div>
                <div class="stat-label">บัญชีที่ถูกระงับ</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 animate-in" style="animation-delay: 0.25s;">
        <div class="modern-stat-card stat-purple">
            <div class="stat-icon-wrapper"><i class="bi bi-shield-lock-fill"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ number_format($stats['admin_count']) }}</div>
                <div class="stat-label">ผู้ดูแลระบบ</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Chart Section -->
    <div class="col-lg-4 animate-in" style="animation-delay: 0.3s;">
        <div class="premium-panel">
            <div class="premium-panel-header">
                <h5><i class="bi bi-pie-chart-fill me-2 text-primary"></i>สัดส่วนบทบาท</h5>
            </div>
            <div class="panel-body d-flex align-items-center justify-content-center" style="min-height: 320px;">
                <canvas id="roleChart" style="max-height: 280px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Users Table -->
    <div class="col-lg-8 animate-in" style="animation-delay: 0.35s;">
        <div class="premium-panel">
            <div class="premium-panel-header">
                <h5><i class="bi bi-person-lines-fill me-2 text-primary"></i>ผู้ใช้ลงทะเบียนล่าสุด</h5>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">ดูทั้งหมด</a>
            </div>
            <div class="panel-body p-0">
                <div class="table-responsive">
                    <table class="table table-premium mb-0">
                        <thead>
                            <tr>
                                <th>ผู้ใช้งาน</th>
                                <th>อีเมล</th>
                                <th>บทบาท</th>
                                <th>สถานะ</th>
                                <th class="text-end">วันที่สมัคร</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($recentUsers as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar-sm me-3" style="width: 36px; height: 36px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #64748b; overflow: hidden;">
                                            @if($user->profile_photo_path)
                                                <img src="{{ Storage::url($user->profile_photo_path) }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                                            @else
                                                {{ mb_substr($user->name, 0, 1) }}
                                            @endif
                                        </div>
                                        <strong>{{ $user->name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @php
                                        $roleMap = [
                                            'user' => ['label' => 'ผู้ใช้ทั่วไป', 'class' => 'role-user'],
                                            'staff' => ['label' => 'เจ้าหน้าที่', 'class' => 'role-staff'],
                                            'executive' => ['label' => 'ผู้บริหาร', 'class' => 'role-executive'],
                                            'admin' => ['label' => 'ผู้ดูแลระบบ', 'class' => 'role-admin'],
                                        ];
                                        $r = $roleMap[$user->role] ?? ['label' => $user->role, 'class' => 'role-user'];
                                    @endphp
                                    <span class="role-badge {{ $r['class'] }}">{{ $r['label'] }}</span>
                                </td>
                                <td>
                                    @if($user->status === 'active')
                                        <span class="status-dot active"></span> <span style="font-size: 13px; color: #475569;">ใช้งาน</span>
                                    @else
                                        <span class="status-dot suspended"></span> <span style="font-size: 13px; color: #475569;">ระงับ</span>
                                    @endif
                                </td>
                                <td class="text-end" style="font-size: 13px;">{{ $user->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">ยังไม่มีข้อมูลผู้ใช้</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('roleChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['ผู้ใช้ทั่วไป', 'เจ้าหน้าที่', 'ผู้บริหาร', 'ผู้ดูแลระบบ'],
                datasets: [{
                    data: [{{ $stats['user_count'] }}, {{ $stats['staff_count'] }}, {{ $stats['executive_count'] }}, {{ $stats['admin_count'] }}],
                    backgroundColor: ['#3b82f6', '#f59e0b', '#10b981', '#8b5cf6'],
                    borderWidth: 0,
                    hoverOffset: 12
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { 
                    legend: { 
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: { family: "'Sarabun', sans-serif", size: 13 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        padding: 12,
                        titleFont: { family: "'Sarabun', sans-serif", size: 14 },
                        bodyFont: { family: "'Sarabun', sans-serif", size: 13 },
                        cornerRadius: 8,
                    }
                } 
            }
        });
    }
});
</script>
@endpush
