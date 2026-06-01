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
</head>
<body class="bg-gray-50">

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div class="w-72 bg-[#1e3a8a] text-white flex flex-col">

        <!-- Logo -->
        <div class="p-6 border-b border-blue-800 flex-shrink-0">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/nan-logo.png') }}" alt="NAN Logo" class="w-12 h-12 rounded-full object-contain bg-white p-1">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">InquiSmart</h1>
                    <p class="text-xs text-blue-300">NaN Cellphone Shop</p>
                </div>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto min-h-0">

            {{-- MAIN --}}
            <p class="text-xs text-blue-400 uppercase font-semibold px-4 pt-2 pb-1 tracking-wider">Main</p>
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                <i class="fas fa-tachometer-alt w-4"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            {{-- TICKETS --}}
            <p class="text-xs text-blue-400 uppercase font-semibold px-4 pt-4 pb-1 tracking-wider">Tickets</p>
            <a href="{{ route('admin.tickets.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.tickets.index') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                <i class="fas fa-ticket-alt w-4"></i>
                <span class="font-medium">All Tickets</span>
            </a>
            <a href="{{ route('admin.tickets.negative') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.tickets.negative') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                <i class="fas fa-exclamation-triangle w-4"></i>
                <span class="font-medium">Negative Tickets</span>
            </a>

            {{-- AI & ANALYTICS --}}
            <p class="text-xs text-blue-400 uppercase font-semibold px-4 pt-4 pb-1 tracking-wider">AI & Analytics</p>
            <a href="{{ route('admin.chatbot-logs') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.chatbot-logs') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                <i class="fas fa-robot w-4"></i>
                <span class="font-medium">Chatbot Logs</span>
            </a>
            <a href="{{ route('admin.analytics') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.analytics') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                <i class="fas fa-chart-bar w-4"></i>
                <span class="font-medium">Service Analytics</span>
            </a>

            {{-- MANAGEMENT --}}
            <p class="text-xs text-blue-400 uppercase font-semibold px-4 pt-4 pb-1 tracking-wider">Management</p>
            <a href="{{ route('admin.faqs.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.faqs.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                <i class="fas fa-question-circle w-4"></i>
                <span class="font-medium">FAQ Management</span>
            </a>

        </nav>

        <!-- Admin Info -->
        <div class="p-4 border-t border-blue-800 flex-shrink-0">
            <div class="flex items-center gap-3 px-4 py-3">
                <div class="w-8 h-8 bg-white text-blue-800 rounded-full flex items-center justify-center font-bold flex-shrink-0">
                    N
                </div>
                <div class="flex-1 text-sm min-w-0">
                    <p class="font-medium truncate">NAN Admin</p>
                    <p class="text-blue-300 text-xs truncate">admin@nancellphone.com</p>
                </div>
            </div>
            <a href="{{ route('logout') }}" class="btn btn-error btn-sm w-full mt-2">
                <i class="fas fa-sign-out-alt mr-1"></i> Logout
            </a>
        </div>

    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b px-8 py-5 flex items-center justify-between shadow-sm flex-shrink-0">
            <h2 class="text-2xl font-semibold text-gray-800">@yield('page_title', 'Dashboard')</h2>
            <div class="text-sm text-gray-500">{{ now()->format('F j, Y') }}</div>
        </header>

        <main class="flex-1 overflow-auto p-8 bg-gray-50">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>