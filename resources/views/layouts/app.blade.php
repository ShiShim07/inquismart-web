<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'InquiSmart Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4/dist/full.min.css" rel="stylesheet" type="text/css" />
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        html, body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden !important;
        }
        #app-wrapper {
            display: flex !important;
            height: 100vh !important;
            overflow: hidden !important;
        }
        #sidebar {
            width: 288px !important;
            min-width: 288px !important;
            background: #1e3a8a !important;
            color: white !important;
            display: flex !important;
            flex-direction: column !important;
            height: 100vh !important;
            overflow: hidden !important;
            flex-shrink: 0 !important;
        }
        #sidebar-logo {
            flex-shrink: 0 !important;
            padding: 20px 24px !important;
            border-bottom: 1px solid #1e40af !important;
        }
        #sidebar-nav {
            flex: 1 1 0% !important;
            overflow-y: auto !important;
            min-height: 0 !important;
            padding: 8px !important;
        }
        #sidebar-admin {
            flex-shrink: 0 !important;
            padding: 12px 16px !important;
            border-top: 1px solid #1e40af !important;
        }
        #main-content {
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            overflow: hidden !important;
            min-width: 0 !important;
        }
        #main-body {
            flex: 1 !important;
            overflow-y: auto !important;
            padding: 32px !important;
            background: #f9fafb !important;
        }
    </style>
</head>
<body>

<div id="app-wrapper">

    <!-- Sidebar -->
    <div id="sidebar">

        <!-- Logo -->
        <div id="sidebar-logo">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/nan-logo.png') }}" alt="NAN Logo" class="w-12 h-12 rounded-full object-contain bg-white p-1">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">InquiSmart</h1>
                    <p class="text-xs text-blue-300">NaN Cellphone Shop</p>
                </div>
            </div>
        </div>

        <!-- Nav -->
        <nav id="sidebar-nav">

            {{-- MAIN --}}
            <p class="text-xs text-blue-400 uppercase font-semibold px-4 pt-2 pb-1 tracking-wider">Main</p>
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                <i class="fas fa-tachometer-alt w-4"></i>
                <span class="font-medium text-sm">Dashboard</span>
            </a>

            {{-- TICKETS --}}
            <p class="text-xs text-blue-400 uppercase font-semibold px-4 pt-3 pb-1 tracking-wider">Tickets</p>
            <a href="{{ route('admin.tickets.index') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.tickets.index') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                <i class="fas fa-ticket-alt w-4"></i>
                <span class="font-medium text-sm">All Tickets</span>
            </a>
            <a href="{{ route('admin.tickets.negative') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.tickets.negative') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                <i class="fas fa-exclamation-triangle w-4"></i>
                <span class="font-medium text-sm">Negative Tickets</span>
            </a>

            {{-- AI & ANALYTICS --}}
            <p class="text-xs text-blue-400 uppercase font-semibold px-4 pt-3 pb-1 tracking-wider">AI & Analytics</p>
            <a href="{{ route('admin.chatbot-logs') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.chatbot-logs') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                <i class="fas fa-robot w-4"></i>
                <span class="font-medium text-sm">Chatbot Logs</span>
            </a>
            <a href="{{ route('admin.analytics') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.analytics') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                <i class="fas fa-chart-bar w-4"></i>
                <span class="font-medium text-sm">Service Analytics</span>
            </a>

            {{-- MANAGEMENT --}}
            <p class="text-xs text-blue-400 uppercase font-semibold px-4 pt-3 pb-1 tracking-wider">Management</p>
            <a href="{{ route('admin.faqs.index') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl transition-colors {{ request()->routeIs('admin.faqs.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                <i class="fas fa-question-circle w-4"></i>
                <span class="font-medium text-sm">FAQ Management</span>
            </a>

        </nav>

        <!-- Admin Info -->
        <div id="sidebar-admin">
            <div class="flex items-center gap-3 px-2 py-2">
                <div class="w-8 h-8 bg-white text-blue-800 rounded-full flex items-center justify-center font-bold flex-shrink-0">
                    N
                </div>
                <div class="flex-1 text-sm min-w-0">
                    <p class="font-medium truncate">NAN Admin</p>
                    <p class="text-blue-300 text-xs truncate">admin@nancellphone.com</p>
                    <p class="text-blue-400 text-xs uppercase tracking-wider">Admin</p>
                </div>
            </div>
            <a href="{{ route('logout') }}" class="btn btn-error btn-sm w-full mt-1">
                <i class="fas fa-sign-out-alt mr-1"></i> Logout
            </a>
        </div>

    </div>

    <!-- Main Content -->
    <div id="main-content">
        <header class="bg-white border-b px-8 py-5 flex items-center justify-between shadow-sm" style="flex-shrink:0;">
            <h2 class="text-2xl font-semibold text-gray-800">@yield('page_title', 'Dashboard')</h2>
            <div class="text-sm text-gray-500">{{ now()->format('F j, Y') }}</div>
        </header>

        <div id="main-body">
            @yield('content')
        </div>
    </div>

</div>

</body>
</html>