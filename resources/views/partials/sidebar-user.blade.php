{{-- Sidebar for ผู้ใช้บริการ --}}
<div class="nav-section">เมนูหลัก</div>
<a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
    <i class="bi bi-grid-1x2-fill"></i> แดชบอร์ด
</a>

<div class="nav-section mt-3">คำร้อง</div>
<a href="{{ route('user.medicine-requests.create') }}" class="nav-link {{ request()->routeIs('user.medicine-requests.create') ? 'active' : '' }}">
    <i class="bi bi-plus-circle"></i> ขอรับยา
</a>
<a href="{{ route('user.medicine-requests.index') }}" class="nav-link {{ request()->routeIs('user.medicine-requests.index') ? 'active' : '' }}">
    <i class="bi bi-capsule"></i> คำร้องขอยา
</a>
<a href="{{ route('user.kit-requests.create') }}" class="nav-link {{ request()->routeIs('user.kit-requests.create') ? 'active' : '' }}">
    <i class="bi bi-bag-plus"></i> ขอยืมกระเป๋าปฐมพยาบาล
</a>
<a href="{{ route('user.kit-requests.index') }}" class="nav-link {{ request()->routeIs('user.kit-requests.index') ? 'active' : '' }}">
    <i class="bi bi-bag-check"></i> คำร้องยืมกระเป๋า
</a>

<div class="nav-section mt-3">บัญชีผู้ใช้</div>
<a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
    <i class="bi bi-person-gear"></i> จัดการโปรไฟล์
</a>
