<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InquiSmart Admin — @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #1565C0;
            --sidebar-width: 260px;
        }
        body { background: #F5F7FA; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #0D47A1 0%, #1565C0 100%);
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-brand h5 { color: white; font-weight: 700; margin: 0; }
        .sidebar-brand small { color: rgba(255,255,255,0.6); font-size: 11px; }
        .sidebar-nav { padding: 16px 0; }
        .nav-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
        }
        .nav-item a:hover, .nav-item a.active {
            background: rgba(255,255,255,0.15);
            color: white;
        }
        .nav-item a i { font-size: 18px; width: 24px; }
        .nav-section {
            padding: 8px 20px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.4);
            margin-top: 8px;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        .topbar {
            background: white;
            padding: 14px 24px;
            border-bottom: 1px solid #E8ECEF;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }
        .topbar h6 { margin: 0; color: #1A1A2E; font-weight: 600; }
        .content-area { padding: 24px; }
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }
        .stat-card .icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }
        .badge-urgent { background: #FFEBEE; color: #C62828; }
        .badge-frustrated { background: #FFF8E1; color: #F57F17; }
        .badge-neutral { background: #E3F2FD; color: #1565C0; }
        .badge-pending { background: #E3F2FD; color: #1565C0; }
        .badge-processing { background: #FFF8E1; color: #F57F17; }
        .badge-resolved { background: #E8F5E9; color: #2E7D32; }
        .ticket-row:hover { background: #F8F9FA; cursor: pointer; }
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-headset text-white fs-4"></i>
            <div>
                <h5>InquiSmart</h5>
                <small>NaN Cellphone Shop</small>
            </div>
        </div>
    </div>

    <div class="sidebar-nav">
        <div class="nav-section">Main</div>
        <div class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
        </div>

        <div class="nav-section">Tickets</div>
        <div class="nav-item">
            <a href="{{ route('admin.tickets.index') }}" class="{{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated"></i> All Tickets
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.tickets.index', ['sentiment' => 'Urgent']) }}">
                <i class="bi bi-exclamation-triangle"></i> Urgent Tickets
            </a>
        </div>

        <div class="nav-section">Management</div>
        <div class="nav-item">
            <a href="{{ route('admin.faqs.index') }}" class="{{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                <i class="bi bi-question-circle"></i> FAQ Management
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.analytics') }}" class="{{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <i class="bi bi-bar-chart"></i> Analytics
            </a>
        </div>
    </div>

    {{-- ✅ User info + logout sa sidebar footer --}}
    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                <span style="color:#1565C0;font-weight:700;font-size:15px;">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </span>
            </div>
            <div>
                <div style="color:white;font-size:13px;font-weight:600;">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div style="color:rgba(255,255,255,0.5);font-size:11px;">{{ auth()->user()->email ?? '' }}</div>
                <div style="color:rgba(255,255,255,0.4);font-size:10px;text-transform:uppercase;letter-spacing:0.5px;">{{ auth()->user()->role ?? 'Staff' }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm w-100" style="background:rgba(255,255,255,0.1);color:white;border:none;">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    {{-- ✅ Topbar — walang Urgent badge na --}}
    <div class="topbar">
        <h6>@yield('title', 'Dashboard')</h6>
        <div class="d-flex align-items-center gap-3">
            <span style="font-size:13px;color:#666;">
                <i class="bi bi-calendar3 me-1"></i>{{ date('M d, Y') }}
            </span>
        </div>
    </div>

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

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>