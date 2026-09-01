<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ระบบบริหารคลังเวชภัณฑ์') - มรภ.นครศรีธรรมราช</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <meta name="description" content="ระบบสารสนเทศบริหารคลังเวชภัณฑ์ มหาวิทยาลัยราชภัฏนครศรีธรรมราช">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Deep indigo sidebar + purple-to-blue hero gradient */
            --primary: #5b4fe0;
            --primary-light: #7c6ef2;
            --primary-dark: #2d2470;
            --accent: #38bdf8;
            --accent-light: #5ee3f7;
            --success: #2f9e5c;
            --danger: #d8574f;
            --sidebar-width: 270px;
            --gradient-sidebar: linear-gradient(180deg, #2d2470 0%, #241c5e 100%);
            --gradient-primary: linear-gradient(135deg, #6d5ce8 0%, #4a90e2 100%);
            --gradient-accent: linear-gradient(135deg, #38bdf8 0%, #5ee3f7 100%);
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.12);
            --shadow-lg: 0 8px 32px rgba(45,36,112,0.18);
            --radius: 14px;
            --radius-sm: 9px;
        }

        * { box-sizing: border-box; }

        ::selection { background: var(--primary-light); color: #fff; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Sarabun', sans-serif;
            background:
                radial-gradient(circle at 100% 0%, rgba(91,79,224,0.06) 0%, transparent 45%),
                radial-gradient(circle at 0% 100%, rgba(56,189,248,0.05) 0%, transparent 45%),
                #f3f2fb;
            background-attachment: fixed;
            margin: 0;
            min-height: 100vh;
            color: #2a2a35;
        }

        /* ===== Sidebar ===== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--gradient-sidebar);
            color: #fff;
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease;
            box-shadow: 4px 0 24px rgba(0,0,0,0.18);
        }

        .sidebar-brand {
            padding: 28px 20px 24px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.12);
            background:
                linear-gradient(180deg, rgba(0,0,0,0.18) 0%, rgba(0,0,0,0.05) 100%);
            position: relative;
        }

        .sidebar-brand .logo-icon {
            width: 68px;
            height: 68px;
            background: #ffffff;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            padding: 7px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.3), 0 0 0 4px rgba(255,255,255,0.08);
        }

        .sidebar-brand .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .sidebar-brand h6 {
            font-size: 14px;
            font-weight: 600;
            margin: 0;
            line-height: 1.4;
            opacity: 0.97;
        }

        .sidebar-brand small {
            font-size: 11px;
            opacity: 0.65;
            letter-spacing: 0.3px;
        }

        .sidebar-nav {
            padding: 16px 0 24px;
        }

        .nav-section {
            padding: 14px 20px 6px;
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: 1.6px;
            opacity: 0.5;
            font-weight: 700;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            padding: 11px 20px;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            margin: 2px 10px 2px 0;
            border-radius: 0 10px 10px 0;
        }

        .sidebar-nav .nav-link:hover {
            background: rgba(255,255,255,0.09);
            color: #fff;
            border-left-color: var(--accent-light);
            padding-left: 24px;
        }

        .sidebar-nav .nav-link.active {
            background: linear-gradient(90deg, rgba(255,255,255,0.18) 0%, rgba(255,255,255,0.06) 100%);
            color: #fff;
            border-left-color: var(--accent-light);
            font-weight: 600;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.06);
        }

        .sidebar-nav .nav-link i {
            width: 22px;
            margin-right: 12px;
            font-size: 16px;
            text-align: center;
        }

        .sidebar-nav .badge {
            margin-left: auto;
            font-size: 10.5px;
            padding: 3px 8px;
            border-radius: 20px;
            font-weight: 600;
        }

        /* ===== Main Content ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        /* ===== Top Navbar ===== */
        .top-navbar {
            background: rgba(255,255,255,0.85);
            backdrop-filter: saturate(180%) blur(10px);
            -webkit-backdrop-filter: saturate(180%) blur(10px);
            padding: 0 28px;
            height: 66px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid rgba(0,0,0,0.04);
        }

        .top-navbar .page-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
            letter-spacing: 0.2px;
        }

        .top-navbar .user-menu {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            background: var(--gradient-primary);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 3px 10px rgba(91,79,224,0.35), 0 0 0 3px rgba(91,79,224,0.08);
        }

        .user-info {
            text-align: right;
        }

        .user-info .name {
            font-size: 14px;
            font-weight: 600;
            color: #2a2a35;
        }

        .user-info .role {
            font-size: 11.5px;
            color: #8a8a97;
        }

        /* ===== Content Area ===== */
        .content-area {
            padding: 26px 28px 40px;
        }

        /* ===== Cards ===== */
        .stat-card {
            background: #fff;
            border-radius: var(--radius);
            padding: 22px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.04);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
        }

        .stat-card.blue::before { background: var(--gradient-primary); }
        .stat-card.gold::before { background: var(--gradient-accent); }
        .stat-card.green::before { background: linear-gradient(135deg, #2f9e5c, #5cbf85); }
        .stat-card.red::before { background: linear-gradient(135deg, #d8574f, #ea837c); }
        .stat-card.purple::before { background: linear-gradient(135deg, #7c5cb8, #9b81cf); }
        .stat-card.cyan::before { background: linear-gradient(135deg, #2b90c9, #5db3e0); }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 14px;
        }

        .stat-card.blue .stat-icon { background: rgba(91,79,224,0.1); color: var(--primary); }
        .stat-card.gold .stat-icon { background: rgba(56,189,248,0.15); color: #0284c7; }
        .stat-card.green .stat-icon { background: rgba(47,158,92,0.1); color: #2f9e5c; }
        .stat-card.red .stat-icon { background: rgba(216,87,79,0.1); color: #d8574f; }
        .stat-card.purple .stat-icon { background: rgba(124,92,184,0.1); color: #7c5cb8; }
        .stat-card.cyan .stat-icon { background: rgba(43,144,201,0.1); color: #2b90c9; }

        .stat-card .stat-value {
            font-size: 29px;
            font-weight: 700;
            color: #1a1a1a;
            line-height: 1;
            letter-spacing: -0.3px;
        }

        .stat-card .stat-label {
            font-size: 13px;
            color: #8a8a97;
            margin-top: 6px;
        }

        /* ===== Panel ===== */
        .panel {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(0,0,0,0.04);
            overflow: hidden;
            transition: box-shadow 0.25s ease;
        }

        .panel:hover { box-shadow: var(--shadow-md); }

        .panel-header {
            padding: 18px 22px;
            border-bottom: 1px solid #eef2f1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(180deg, #fbfbfe 0%, #ffffff 100%);
        }

        .panel-header h5 {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
            color: #1a1a1a;
        }

        .panel-body {
            padding: 22px;
        }

        /* ===== Tables ===== */
        .table-modern {
            margin: 0;
        }

        .table-modern thead th {
            background: #f8f9fb;
            border-bottom: 2px solid #e5e7eb;
            font-weight: 700;
            font-size: 12.5px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #6b7280;
            padding: 12px 16px;
            white-space: nowrap;
        }

        .table-modern tbody td {
            padding: 13px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
        }

        .table-modern tbody tr {
            transition: background 0.15s ease;
        }

        .table-modern tbody tr:hover {
            background: #f8f9fb;
        }

        /* ===== Badges ===== */
        .badge-status {
            padding: 5px 13px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        /* ===== Buttons ===== */
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            border-radius: var(--radius-sm);
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(91,79,224,0.35);
            background: var(--primary-light);
        }

        .btn-accent {
            background: var(--gradient-accent);
            border: none;
            color: #fff;
            border-radius: var(--radius-sm);
        }

        .btn-outline-custom {
            border: 1.5px solid var(--primary);
            color: var(--primary);
            border-radius: var(--radius-sm);
            transition: all 0.2s;
        }

        .btn-outline-custom:hover {
            background: var(--primary);
            color: #fff;
        }

        .btn-outline-danger {
            border-radius: var(--radius-sm);
            transition: all 0.2s;
        }

        .btn-outline-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(216,87,79,0.3);
        }

        /* ===== Form Controls ===== */
        .form-control, .form-select {
            border-radius: var(--radius-sm);
            border: 1.5px solid #e0e0e0;
            padding: 10px 14px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(124,110,242,0.12);
        }

        .form-label {
            font-weight: 500;
            color: #444;
            font-size: 14px;
            margin-bottom: 6px;
        }

        /* ===== Alert ===== */
        .alert {
            border-radius: var(--radius-sm);
            border: none;
            font-size: 14px;
            border-left: 4px solid transparent;
            box-shadow: var(--shadow-sm);
        }
        .alert-success { border-left-color: var(--success); }
        .alert-danger { border-left-color: var(--danger); }

        /* ===== Mobile ===== */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--primary);
            cursor: pointer;
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar-toggle {
                display: block;
            }
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }
            .sidebar-overlay.show {
                display: block;
            }
        }

        /* ===== Animations ===== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-in {
            animation: fadeInUp 0.4s ease forwards;
        }

        .animate-in:nth-child(1) { animation-delay: 0.05s; }
        .animate-in:nth-child(2) { animation-delay: 0.1s; }
        .animate-in:nth-child(3) { animation-delay: 0.15s; }
        .animate-in:nth-child(4) { animation-delay: 0.2s; }

        /* ===== Pagination ===== */
        .pagination .page-link {
            border-radius: 8px;
            margin: 0 2px;
            font-size: 14px;
            color: var(--primary);
            border-color: rgba(0,0,0,0.06);
        }
        .pagination .page-item.active .page-link {
            background: var(--primary);
            border-color: var(--primary);
        }

        /* ===== Scrollbar ===== */
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }

        /* ===== Empty State ===== */
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: #a3a3ae;
        }
        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.35;
            display: block;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="logo-icon"><img src="{{ asset('images/logo.png') }}" alt="ตราสัญลักษณ์มหาวิทยาลัยราชภัฏนครศรีธรรมราช"></div>
            <h6>ระบบบริหารคลังเวชภัณฑ์</h6>
            <small>มรภ.นครศรีธรรมราช</small>
        </div>
        <nav class="sidebar-nav">
            @yield('sidebar')
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">@yield('page-title', 'แดชบอร์ด')</h1>
            </div>
            <div class="user-menu">
                <a href="{{ route('profile.edit') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                    <div class="user-info d-none d-md-block">
                        <div class="name">{{ Auth::user()->name }}</div>
                        <div class="role">
                            @php
                                $roleLabels = ['user' => 'ผู้ใช้บริการ', 'staff' => 'เจ้าหน้าที่', 'executive' => 'ผู้บริหาร', 'admin' => 'ผู้ดูแลระบบ'];
                            @endphp
                            {{ $roleLabels[Auth::user()->role] ?? Auth::user()->role }}
                        </div>
                    </div>
                    <div class="user-avatar" style="overflow: hidden;">
                        @if(Auth::user()->profile_photo_path)
                            <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            {{ mb_substr(Auth::user()->name, 0, 1) }}
                        @endif
                    </div>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm" title="ออกจากระบบ">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </header>

        <!-- Content -->
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }

        // SweetAlert for Login Success
        @if(session('login_success'))
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: '{{ session('login_success') }}',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif

        // Optional: you can also use SweetAlert for normal success/error messages if preferred,
        // but here we just added it specifically for login_success (which can be a toast or full alert).
        // If the user wants a big alert for login:
        /*
        @if(session('login_success'))
            Swal.fire({
                icon: 'success',
                title: 'ยินดีต้อนรับ',
                text: '{{ session('login_success') }}',
                confirmButtonColor: '#5b4fe0'
            });
        @endif
        */
    </script>
    
    <!-- Let's actually use the big alert since they asked for it specifically when logging in -->
    <script>
        @if(session('login_success'))
            Swal.fire({
                icon: 'success',
                title: 'เข้าสู่ระบบสำเร็จ',
                text: '{{ session('login_success') }}',
                confirmButtonColor: '#5b4fe0',
                confirmButtonText: 'ตกลง'
            });
        @endif
    </script>
    @stack('scripts')
</body>
</html>