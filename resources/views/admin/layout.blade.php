<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InquiSmart — @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 256px;
            --navy: #0B1D3A;
            --accent: #3B82F6;
            --accent-glow: rgba(59,130,246,0.15);
            --surface: #F8FAFC;
            --card-bg: #FFFFFF;
            --border: rgba(0,0,0,0.07);
            --text-primary: #0F172A;
            --text-muted: #64748B;
            --text-xs: #94A3B8;
            --urgent-bg: #FEF2F2; --urgent-text: #991B1B; --urgent-bd: #FECACA;
            --frustrated-bg: #FFFBEB; --frustrated-text: #92400E; --frustrated-bd: #FCD34D;
            --neutral-bg: #EFF6FF; --neutral-text: #1E40AF; --neutral-bd: #BFDBFE;
            --pending-bg: #EFF6FF; --pending-text: #1E40AF;
            --processing-bg: #FFFBEB; --processing-text: #92400E;
            --resolved-bg: #F0FDF4; --resolved-text: #166534;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'DM Sans', system-ui, sans-serif; background: var(--surface); color: var(--text-primary); margin: 0; font-size: 14px; -webkit-font-smoothing: antialiased; }

        /* SIDEBAR */
        .sidebar { width: var(--sidebar-w); height: 100vh; background: var(--navy); position: fixed; top: 0; left: 0; z-index: 200; display: flex; flex-direction: column; overflow: hidden; }
        .sidebar::after { content: ''; position: absolute; top: -60px; right: -40px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(59,130,246,0.1) 0%, transparent 70%); pointer-events: none; }

        .sidebar-brand { padding: 20px 20px 16px; border-bottom: 1px solid rgba(255,255,255,0.06); flex-shrink: 0; }
        .brand-icon { width: 34px; height: 34px; background: var(--accent); border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 16px; color: white; flex-shrink: 0; }
        .brand-name { font-size: 14.5px; font-weight: 600; color: #fff; letter-spacing: -0.2px; }
        .brand-sub { font-size: 11px; color: rgba(255,255,255,0.32); margin-top: 1px; font-weight: 300; }

        .sidebar-nav { flex: 1; padding: 10px 0; overflow-y: auto; scrollbar-width: none; }
        .sidebar-nav::-webkit-scrollbar { display: none; }

        .nav-section-label { padding: 10px 20px 3px; font-size: 9.5px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.2px; color: rgba(255,255,255,0.2); }

        .nav-link-item { display: flex; align-items: center; gap: 10px; padding: 9px 20px; color: rgba(255,255,255,0.52); text-decoration: none; font-size: 13.5px; font-weight: 400; position: relative; transition: color 0.15s, background 0.15s; }
        .nav-link-item i { font-size: 16px; flex-shrink: 0; width: 20px; }
        .nav-link-item:hover { color: rgba(255,255,255,0.88); background: rgba(255,255,255,0.05); }
        .nav-link-item.active { color: #fff; background: rgba(59,130,246,0.18); }
        .nav-link-item.active::before { content: ''; position: absolute; left: 0; top: 5px; bottom: 5px; width: 3px; background: var(--accent); border-radius: 0 3px 3px 0; }
        .nav-link-item.nav-urgent { color: rgba(252,165,165,0.7); }
        .nav-link-item.nav-urgent:hover { color: #FCA5A5; background: rgba(252,165,165,0.07); }

        .sidebar-footer { border-top: 1px solid rgba(255,255,255,0.06); padding: 14px 20px; flex-shrink: 0; }
        .user-avatar { width: 32px; height: 32px; background: var(--accent-glow); border: 1px solid rgba(59,130,246,0.35); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600; color: #93C5FD; flex-shrink: 0; }
        .user-name { font-size: 13px; font-weight: 500; color: rgba(255,255,255,0.88); line-height: 1.3; }
        .user-role { font-size: 10.5px; color: rgba(255,255,255,0.28); }
        .logout-btn { width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.45); font-size: 12px; padding: 6px 12px; border-radius: 7px; cursor: pointer; transition: all 0.15s; margin-top: 10px; font-family: inherit; display: flex; align-items: center; justify-content: center; gap: 6px; }
        .logout-btn:hover { background: rgba(239,68,68,0.12); border-color: rgba(239,68,68,0.22); color: #FCA5A5; }

        /* MAIN */
        .main-content { margin-left: var(--sidebar-w); min-height: 100vh; }
        .topbar { background: var(--card-bg); padding: 0 28px; height: 60px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; }
        .topbar-title { font-size: 15px; font-weight: 600; color: var(--text-primary); letter-spacing: -0.2px; }
        .topbar-date { font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 6px; background: var(--surface); padding: 5px 11px; border-radius: 7px; border: 1px solid var(--border); }
        .content-area { padding: 28px; }

        /* CARDS */
        .card-surface { background: var(--card-bg); border-radius: 14px; border: 1px solid var(--border); padding: 22px; }
        .stat-card { background: var(--card-bg); border-radius: 14px; border: 1px solid var(--border); padding: 20px 22px; position: relative; overflow: hidden; transition: box-shadow 0.2s, transform 0.2s; }
        .stat-card:hover { box-shadow: 0 4px 18px rgba(0,0,0,0.07); transform: translateY(-1px); }
        .stat-label { font-size: 12px; color: var(--text-muted); font-weight: 500; }
        .stat-value { font-size: 30px; font-weight: 600; letter-spacing: -1px; line-height: 1.1; margin: 5px 0 8px; }
        .stat-sub { font-size: 11.5px; color: var(--text-muted); }
        .stat-icon { width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 20px; }

        /* BADGES */
        .badge-sentiment, .badge-status { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 6px; font-size: 11.5px; font-weight: 500; white-space: nowrap; }
        .badge-urgent { background: var(--urgent-bg); color: var(--urgent-text); border: 1px solid var(--urgent-bd); }
        .badge-frustrated { background: var(--frustrated-bg); color: var(--frustrated-text); border: 1px solid var(--frustrated-bd); }
        .badge-neutral { background: var(--neutral-bg); color: var(--neutral-text); border: 1px solid var(--neutral-bd); }
        .badge-pending { background: var(--pending-bg); color: var(--pending-text); }
        .badge-processing { background: var(--processing-bg); color: var(--processing-text); }
        .badge-resolved { background: var(--resolved-bg); color: var(--resolved-text); }

        /* TABLES */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { font-size: 10.5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.7px; color: var(--text-xs); padding: 10px 14px; border-bottom: 1px solid var(--border); background: var(--surface); text-align: left; }
        .data-table td { padding: 12px 14px; border-bottom: 1px solid rgba(0,0,0,0.04); font-size: 13.5px; vertical-align: middle; }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tbody tr { transition: background 0.12s; cursor: pointer; }
        .data-table tbody tr:hover { background: rgba(59,130,246,0.03); }

        /* FORMS */
        .form-control, .form-select { border: 1px solid rgba(0,0,0,0.11); border-radius: 8px; font-family: inherit; font-size: 13.5px; color: var(--text-primary); background: var(--card-bg); padding: 8px 12px; transition: border-color 0.15s, box-shadow 0.15s; width: 100%; }
        .form-control:focus, .form-select:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        textarea.form-control { resize: vertical; }
        .form-control-sm, .form-select-sm { padding: 6px 10px; font-size: 13px; }
        label { font-size: 12.5px; font-weight: 500; color: var(--text-primary); display: block; margin-bottom: 5px; }

        /* BUTTONS */
        .btn { display: inline-flex; align-items: center; gap: 6px; font-family: inherit; font-weight: 500; cursor: pointer; transition: all 0.15s; border-radius: 9px; padding: 8px 16px; font-size: 13.5px; text-decoration: none; border: none; }
        .btn:active { transform: scale(0.98); }
        .btn-primary { background: var(--accent); color: white; }
        .btn-primary:hover { background: #2563EB; color: white; }
        .btn-outline-primary { background: transparent; border: 1px solid rgba(59,130,246,0.35); color: var(--accent); }
        .btn-outline-primary:hover { background: rgba(59,130,246,0.07); color: var(--accent); border-color: var(--accent); }
        .btn-outline-secondary { background: transparent; border: 1px solid var(--border); color: var(--text-muted); }
        .btn-outline-secondary:hover { border-color: rgba(0,0,0,0.18); color: var(--text-primary); }
        .btn-outline-danger { background: transparent; border: 1px solid rgba(239,68,68,0.3); color: #DC2626; }
        .btn-outline-danger:hover { background: rgba(239,68,68,0.06); }
        .btn-sm { padding: 5px 11px; font-size: 12px; border-radius: 7px; }
        .w-100 { width: 100%; justify-content: center; }

        /* ALERTS */
        .alert { border-radius: 10px; padding: 11px 16px; font-size: 13.5px; display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
        .alert-success { background: #F0FDF4; color: #166534; border: 1px solid #BBF7D0; }
        .alert-danger { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }
        .btn-close { background: none; border: none; cursor: pointer; padding: 4px; opacity: 0.5; margin-left: auto; font-size: 16px; }
        .btn-close:hover { opacity: 1; }
        .btn-close::before { content: '×'; font-size: 18px; }

        /* MISC */
        .ticket-id { font-family: 'DM Mono', monospace; font-size: 12px; color: var(--text-muted); background: var(--surface); border: 1px solid var(--border); border-radius: 5px; padding: 2px 7px; }
        .section-header { font-size: 13.5px; font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 8px; margin: 0; }
        .section-header i { color: var(--accent); }
        .modal-content { border-radius: 16px; border: 1px solid var(--border); box-shadow: 0 20px 60px rgba(0,0,0,0.12); }
        .modal-header { border-bottom: 1px solid var(--border); padding: 18px 22px; }
        .modal-footer { border-top: 1px solid var(--border); padding: 14px 22px; }
        .modal-title { font-size: 15px; font-weight: 600; }
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2">
            <div class="brand-icon"><i class="bi bi-headset"></i></div>
            <div>
                <div class="brand-name">InquiSmart</div>
                <div class="brand-sub">NaN Cellphone Shop</div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <a href="{{ route('admin.analytics') }}" class="nav-link-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow"></i> Analytics
        </a>

        <div class="nav-section-label" style="margin-top:6px;">Tickets</div>
        <a href="{{ route('admin.tickets.index') }}" class="nav-link-item {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
            <i class="bi bi-ticket-perforated"></i> All Tickets
        </a>
        <a href="{{ route('admin.tickets.index', ['sentiment' => 'Urgent']) }}" class="nav-link-item nav-urgent">
            <i class="bi bi-exclamation-circle"></i> Urgent Tickets
        </a>

        <div class="nav-section-label" style="margin-top:6px;">Content</div>
        <a href="{{ route('admin.faqs.index') }}" class="nav-link-item {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
            <i class="bi bi-chat-square-text"></i> FAQ Management
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div style="min-width:0;">
                <div class="user-name text-truncate">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="user-role">{{ auth()->user()->role ?? 'Staff' }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="bi bi-box-arrow-right"></i> Sign out
            </button>
        </form>
    </div>
</aside>

<div class="main-content">
    <header class="topbar">
        <div class="topbar-title">@yield('title', 'Dashboard')</div>
        <div class="topbar-date">
            <i class="bi bi-calendar3"></i> {{ date('M d, Y') }}
        </div>
    </header>

    <div class="content-area">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ session('error') }}
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
