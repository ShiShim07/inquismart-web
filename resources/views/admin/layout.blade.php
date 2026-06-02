<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InquiBot — @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary:       #1A3FC4;
            --primary-dark:  #0F2A9E;
            --primary-light: #3B5BF6;
            --accent:        #00E5C4;
            --danger:        #FF3B5C;
            --warning:       #FFB547;
            --success:       #00C896;
            --sidebar-w:     272px;
            --bg:            #F0F2F8;
            --surface:       #FFFFFF;
            --border:        #E4E8F2;
            --text-1:        #0D1B3E;
            --text-2:        #5A6485;
            --text-3:        #9BA5C0;
            --radius-lg:     18px;
            --radius-md:     12px;
            --shadow-sm:     0 2px 8px rgba(26,63,196,0.06);
            --shadow-md:     0 4px 24px rgba(26,63,196,0.10);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            background: var(--bg);
            font-family: 'DM Sans', sans-serif;
            color: var(--text-1);
            margin: 0;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 99px; }

        /* ══════════════ SIDEBAR ══════════════ */
        .sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--primary-dark);
            position: fixed;
            top: 0; left: 0;
            z-index: 200;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .sidebar::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 240px; height: 240px;
            background: radial-gradient(circle, rgba(0,229,196,0.15) 0%, transparent 70%);
            pointer-events: none;
        }
        .sidebar::after {
            content: '';
            position: absolute;
            bottom: 60px; left: -60px;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(59,91,246,0.2) 0%, transparent 70%);
            pointer-events: none;
        }

        .sidebar-brand {
            padding: 22px 24px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            flex-shrink: 0;
        }
        .brand-icon {
            width: 38px; height: 38px;
            background: var(--accent);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .brand-icon i { color: var(--primary-dark); font-size: 18px; }
        .brand-name {
            font-family: 'Syne', sans-serif;
            font-size: 17px;
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
        }
        .brand-sub {
            font-size: 11px;
            color: rgba(255,255,255,0.45);
            margin-top: 1px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 0;
            overflow-y: auto;
        }
        .nav-section-label {
            padding: 10px 24px 4px;
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
        }
        .nav-link-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 2px 12px;
            padding: 10px 12px;
            border-radius: 10px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.18s ease;
            position: relative;
        }
        .nav-link-item i { font-size: 17px; width: 22px; flex-shrink: 0; }
        .nav-link-item:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }
        .nav-link-item.active {
            background: rgba(0,229,196,0.15);
            color: var(--accent);
        }
        .nav-link-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 8px; bottom: 8px;
            width: 3px;
            background: var(--accent);
            border-radius: 0 3px 3px 0;
            margin-left: -12px;
        }
        .nav-badge {
            margin-left: auto;
            background: var(--danger);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            min-width: 18px; height: 18px;
            border-radius: 9px;
            padding: 0 5px;
            display: flex; align-items: center; justify-content: center;
        }

        /* ── Sidebar Footer ── */
        .sidebar-footer {
            padding: 16px 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.08);
            flex-shrink: 0;
        }
        .user-card {
            display: flex; align-items: center; gap: 10px;
            padding: 10px;
            border-radius: 12px;
            background: rgba(255,255,255,0.06);
            margin-bottom: 10px;
        }
        .user-avatar {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 15px;
            color: var(--primary-dark);
            flex-shrink: 0;
        }
        .user-name { font-size: 13px; font-weight: 600; color: #fff; line-height: 1.2; }
        .user-email { font-size: 10.5px; color: rgba(255,255,255,0.4); }
        .user-role {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--accent);
            margin-top: 1px;
        }
        .logout-btn {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            width: 100%;
            padding: 8px;
            background: rgba(255,255,255,0.06);
            border: none;
            border-radius: 10px;
            color: rgba(255,255,255,0.5);
            font-size: 12.5px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.18s;
        }
        .logout-btn:hover { background: rgba(255,59,92,0.2); color: #FF3B5C; }

        /* ══════════════ MAIN ══════════════ */
        .main-wrap {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Topbar ── */
        .topbar {
            position: sticky; top: 0; z-index: 100;
            background: rgba(240,242,248,0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 14px 28px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .topbar-title {
            font-family: 'Syne', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: var(--text-1);
        }
        .topbar-date {
            display: flex; align-items: center; gap: 6px;
            font-size: 12.5px;
            color: var(--text-2);
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 6px 12px;
            border-radius: 8px;
        }
        .topbar-date i { color: var(--primary); }

        /* ── Content ── */
        .content-wrap { padding: 24px 28px; flex: 1; }

        /* ══════════════ CARDS ══════════════ */
        .surface {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }
        .surface-pad { padding: 24px; }

        /* ── Stat cards ── */
        .stat-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            padding: 20px;
            position: relative;
            overflow: hidden;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
        .stat-card-accent {
            position: absolute;
            top: 0; right: 0;
            width: 80px; height: 80px;
            border-radius: 0 var(--radius-lg) 0 80px;
            opacity: 0.08;
        }
        .stat-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-2);
            letter-spacing: 0.3px;
        }
        .stat-value {
            font-family: 'Syne', sans-serif;
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1;
            margin: 6px 0 4px;
        }
        .stat-sub { font-size: 12px; color: var(--text-3); }
        .stat-icon {
            width: 44px; height: 44px;
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        /* ── Section headers ── */
        .section-title {
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-1);
            display: flex; align-items: center; gap: 8px;
        }
        .section-title i { color: var(--primary); }

        /* ── Sentiment boxes ── */
        .sentiment-box {
            border-radius: var(--radius-md);
            padding: 20px;
            text-align: center;
            transition: transform 0.2s;
        }
        .sentiment-box:hover { transform: translateY(-2px); }
        .sentiment-negative { background: #FFF0F2; border: 1px solid #FFD0D8; }
        .sentiment-positive { background: #EDFFF9; border: 1px solid #C0F5E4; }
        .sentiment-neutral  { background: #EEF2FF; border: 1px solid #C7D2FE; }
        .sentiment-count {
            font-family: 'Syne', sans-serif;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -1px;
        }
        .sentiment-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 4px;
        }
        .sentiment-label {
            font-size: 12.5px;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            gap: 5px;
            margin: 6px 0 4px;
        }
        .sentiment-desc { font-size: 11.5px; }

        /* ══════════════ BADGES ══════════════ */
        .chip {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
        }
        .chip-negative  { background: #FFF0F2; color: #C0123A; }
        .chip-positive  { background: #EDFFF9; color: #076B4A; }
        .chip-neutral   { background: #EEF2FF; color: #3B4FCC; }
        .chip-urgent    { background: #FFF0F2; color: #C0123A; }
        .chip-frustrated{ background: #FFF8EE; color: #9A5A0A; }
        .chip-pending   { background: #EEF2FF; color: #3B4FCC; }
        .chip-processing{ background: #FFF8EE; color: #9A5A0A; }
        .chip-resolved  { background: #EDFFF9; color: #076B4A; }

        /* ══════════════ TABLE ══════════════ */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead th {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--text-3);
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            background: #FAFBFF;
        }
        .data-table thead th:first-child { border-radius: var(--radius-md) 0 0 0; }
        .data-table thead th:last-child  { border-radius: 0 var(--radius-md) 0 0; }
        .data-table tbody tr {
            border-bottom: 1px solid #F3F5FB;
            cursor: pointer;
            transition: background 0.14s;
        }
        .data-table tbody tr:hover { background: #F7F9FF; }
        .data-table tbody td {
            padding: 13px 14px;
            font-size: 13.5px;
            vertical-align: middle;
        }
        .td-id {
            font-family: 'Syne', sans-serif;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-3);
        }

        /* ══════════════ ALERTS ══════════════ */
        .alert-custom {
            border-radius: var(--radius-md);
            padding: 12px 16px;
            font-size: 13.5px;
            display: flex; align-items: center; gap: 10px;
            border: none;
            margin-bottom: 20px;
        }
        .alert-success-custom { background: #EDFFF9; color: #076B4A; }
        .alert-danger-custom  { background: #FFF0F2; color: #C0123A; }

        /* ══════════════ PULSE ══════════════ */
        @keyframes pulse-ring {
            0%   { box-shadow: 0 0 0 0 rgba(255,59,92,0.4); }
            70%  { box-shadow: 0 0 0 8px rgba(255,59,92,0); }
            100% { box-shadow: 0 0 0 0 rgba(255,59,92,0); }
        }
        .pulse-dot {
            width: 9px; height: 9px;
            background: var(--danger);
            border-radius: 50%;
            animation: pulse-ring 1.8s infinite;
            flex-shrink: 0;
        }

        /* ══════════════ FORMS ══════════════ */
        .form-ctrl {
            width: 100%;
            padding: 9px 12px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13.5px;
            color: var(--text-1);
            background: #fff;
            transition: border-color 0.18s, box-shadow 0.18s;
            outline: none;
        }
        .form-ctrl:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(59,91,246,0.1);
        }
        .form-label-sm {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--text-2);
            display: block;
            margin-bottom: 5px;
            letter-spacing: 0.2px;
        }

        /* ══════════════ BUTTONS ══════════════ */
        .btn-primary-custom {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 18px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.18s, box-shadow 0.18s, transform 0.15s;
            text-decoration: none;
        }
        .btn-primary-custom:hover {
            background: var(--primary-light);
            box-shadow: 0 4px 16px rgba(59,91,246,0.3);
            transform: translateY(-1px);
            color: #fff;
            text-decoration: none;
        }
        .btn-outline-custom {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 7px 14px;
            background: transparent;
            color: var(--primary);
            border: 1.5px solid var(--border);
            border-radius: 9px;
            font-family: 'DM Sans', sans-serif;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.18s;
            text-decoration: none;
        }
        .btn-outline-custom:hover {
            border-color: var(--primary);
            background: #EEF2FF;
            color: var(--primary);
            text-decoration: none;
        }
        .btn-ghost {
            background: transparent;
            border: none;
            color: var(--text-2);
            font-size: 12.5px;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            padding: 7px 10px;
            border-radius: 8px;
            transition: all 0.15s;
        }
        .btn-ghost:hover { background: var(--bg); color: var(--text-1); }

        /* ══════════════ PAGINATION ══════════════ */
        .pagination .page-link {
            border-radius: 8px !important;
            margin: 0 2px;
            border: 1.5px solid var(--border);
            color: var(--text-2);
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
        }
        .pagination .page-item.active .page-link {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        /* ══════════════ ANIMATIONS ══════════════ */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.35s ease both; }
        .delay-1 { animation-delay: 0.05s; }
        .delay-2 { animation-delay: 0.10s; }
        .delay-3 { animation-delay: 0.15s; }
        .delay-4 { animation-delay: 0.20s; }
    </style>
    @stack('styles')
</head>
<body>

<!-- ══ SIDEBAR ══ -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2">
            <div class="brand-icon"><i class="bi bi-headset"></i></div>
            <div>
                <div class="brand-name">InquiBot</div>
                <div class="brand-sub">NaN Cellphone Shop</div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>

        <div class="nav-section-label">Tickets</div>
        <a href="{{ route('admin.tickets.index') }}"
           class="nav-link-item {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
            <i class="bi bi-ticket-perforated-fill"></i> All Tickets
        </a>
        <a href="{{ route('admin.tickets.index', ['sentiment' => 'Negative']) }}"
           class="nav-link-item">
            <i class="bi bi-exclamation-triangle-fill"></i> Negative Tickets
        </a>

        <div class="nav-section-label">AI & Analytics</div>
        <a href="{{ route('admin.chatbot.logs') }}"
           class="nav-link-item {{ request()->routeIs('admin.chatbot.*') ? 'active' : '' }}">
            <i class="bi bi-robot"></i> Chatbot Logs
        </a>
        <a href="{{ route('admin.analytics') }}"
           class="nav-link-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line-fill"></i> Service Analytics
        </a>

        <div class="nav-section-label">Management</div>
        <a href="{{ route('admin.faqs.index') }}"
           class="nav-link-item {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
            <i class="bi bi-question-circle-fill"></i> FAQ Management
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div style="min-width:0;">
                <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="user-email">{{ auth()->user()->email ?? '' }}</div>
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

<!-- ══ MAIN ══ -->
<div class="main-wrap">
    <header class="topbar">
        <div class="topbar-title">@yield('title', 'Dashboard')</div>
        <div class="d-flex align-items-center gap-3">
            <div class="topbar-date">
                <i class="bi bi-calendar3"></i>
                <span>{{ date('M d, Y') }}</span>
            </div>
        </div>
    </header>

    <main class="content-wrap">
        @if(session('success'))
            <div class="alert-custom alert-success-custom fade-up">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert-custom alert-danger-custom fade-up">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
