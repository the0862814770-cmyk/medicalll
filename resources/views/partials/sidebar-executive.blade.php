{{-- Sidebar for ผู้บริหาร --}}
<div class="nav-section">เมนูหลัก</div>
<a href="{{ route('executive.dashboard') }}" class="nav-link {{ request()->routeIs('executive.dashboard') ? 'active' : '' }}">
    <i class="bi bi-grid-1x2-fill"></i> แดชบอร์ด
</a>

<div class="nav-section mt-3">รายงาน</div>
<a href="{{ route('executive.reports.stock') }}" class="nav-link {{ request()->routeIs('executive.reports.stock') ? 'active' : '' }}">
    <i class="bi bi-box-seam"></i> สต็อกเวชภัณฑ์
</a>
<a href="{{ route('executive.reports.dispensing') }}" class="nav-link {{ request()->routeIs('executive.reports.dispensing') ? 'active' : '' }}">
    <i class="bi bi-graph-up"></i> เบิก-จ่ายย้อนหลัง
</a>

<div class="nav-section mt-3">อนุมัติคำร้อง</div>
<a href="{{ route('executive.requests.medicine') }}" class="nav-link {{ request()->routeIs('executive.requests.medicine*') ? 'active' : '' }}">
    <i class="bi bi-file-earmark-medical"></i> คำร้องขอรับยา
</a>
<a href="{{ route('executive.requests.kit') }}" class="nav-link {{ request()->routeIs('executive.requests.kit*') ? 'active' : '' }}">
    <i class="bi bi-bag-heart"></i> คำร้องยืมกระเป๋า
</a>

<div class="nav-section mt-3">บัญชีผู้ใช้</div>
<a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
    <i class="bi bi-person-gear"></i> จัดการโปรไฟล์
</a>
