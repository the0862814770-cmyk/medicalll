{{-- Sidebar for เจ้าหน้าที่ห้องพยาบาล --}}
<div class="nav-section">เมนูหลัก</div>
<a href="{{ route('staff.dashboard') }}" class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
    <i class="bi bi-grid-1x2-fill"></i> แดชบอร์ด
</a>

<div class="nav-section mt-3">จัดการเวชภัณฑ์</div>
<a href="{{ route('staff.supplies.index') }}" class="nav-link {{ request()->routeIs('staff.supplies.*') ? 'active' : '' }}">
    <i class="bi bi-capsule"></i> เวชภัณฑ์
</a>
<a href="{{ route('staff.categories.index') }}" class="nav-link {{ request()->routeIs('staff.categories.*') ? 'active' : '' }}">
    <i class="bi bi-tags"></i> หมวดหมู่
</a>
<a href="{{ route('staff.kits.index') }}" class="nav-link {{ request()->routeIs('staff.kits.*') ? 'active' : '' }}">
    <i class="bi bi-briefcase"></i> กระเป๋าปฐมพยาบาล
</a>

<div class="nav-section mt-3">ธุรกรรม</div>
<a href="{{ route('staff.transactions.create') }}" class="nav-link {{ request()->routeIs('staff.transactions.create') ? 'active' : '' }}">
    <i class="bi bi-arrow-left-right"></i> รับเข้า/เบิกจ่าย
</a>
<a href="{{ route('staff.transactions.index') }}" class="nav-link {{ request()->routeIs('staff.transactions.index') ? 'active' : '' }}">
    <i class="bi bi-clock-history"></i> ประวัติธุรกรรม
</a>

<div class="nav-section mt-3">คำร้อง</div>
<a href="{{ route('staff.requests.medicine') }}" class="nav-link {{ request()->routeIs('staff.requests.medicine*') ? 'active' : '' }}">
    <i class="bi bi-file-earmark-medical"></i> คำร้องขอยา
    @php $pendingMed = \App\Models\MedicineRequest::where('status', 'pending')->count(); @endphp
    @if($pendingMed > 0)
        <span class="badge bg-danger">{{ $pendingMed }}</span>
    @endif
</a>
<a href="{{ route('staff.requests.kit') }}" class="nav-link {{ request()->routeIs('staff.requests.kit*') ? 'active' : '' }}">
    <i class="bi bi-bag-heart"></i> คำร้องยืมกระเป๋า
    @php $pendingKit = \App\Models\KitRequest::whereIn('status', ['pending', 'return_pending'])->count(); @endphp
    @if($pendingKit > 0)
        <span class="badge bg-danger">{{ $pendingKit }}</span>
    @endif
</a>

<div class="nav-section mt-3">รายงาน</div>
<a href="{{ route('staff.reports.stock') }}" class="nav-link {{ request()->routeIs('staff.reports.stock') ? 'active' : '' }}">
    <i class="bi bi-box-seam"></i> สต็อกเวชภัณฑ์
</a>
<a href="{{ route('staff.reports.dispensing') }}" class="nav-link {{ request()->routeIs('staff.reports.dispensing') ? 'active' : '' }}">
    <i class="bi bi-graph-up"></i> รายงานเบิก-จ่าย
</a>
<a href="{{ route('staff.reports.expiry') }}" class="nav-link {{ request()->routeIs('staff.reports.expiry') ? 'active' : '' }}">
    <i class="bi bi-calendar-x"></i> วันหมดอายุ
</a>

<div class="nav-section mt-3">บัญชีผู้ใช้</div>
<a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
    <i class="bi bi-person-gear"></i> จัดการโปรไฟล์
</a>
