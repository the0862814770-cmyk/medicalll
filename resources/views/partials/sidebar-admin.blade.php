{{-- Sidebar for ผู้ดูแลระบบ --}}
<div class="nav-section">เมนูหลัก</div>
<a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="bi bi-grid-1x2-fill"></i> แดชบอร์ด
</a>

<div class="nav-section mt-3">จัดการผู้ใช้</div>
<a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
    <i class="bi bi-people"></i> ผู้ใช้ทั้งหมด
</a>
<a href="{{ route('admin.users.create') }}" class="nav-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
    <i class="bi bi-person-plus"></i> เพิ่มผู้ใช้
</a>

<div class="nav-section mt-3">บัญชีผู้ใช้</div>
<a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
    <i class="bi bi-person-gear"></i> จัดการโปรไฟล์
</a>
