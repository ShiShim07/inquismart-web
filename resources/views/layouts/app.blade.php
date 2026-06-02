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
        <div class="p-6 border-b border-blue-800">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/nan-logo.png') }}" alt="NAN Logo" class="w-12 h-12 rounded-full object-contain bg-white p-1">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">InquiSmart</h1>
                    <p class="text-xs text-blue-300">NaN Cellphone Shop</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-700 text-white">
                <i class="fas fa-tachometer-alt"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="{{ route('admin.tickets.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-blue-700 transition-colors">
                <i class="fas fa-ticket-alt"></i>
                <span class="font-medium">All Tickets</span>
            </a>
            <a href="{{ route('admin.negative-tickets.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-blue-700 transition-colors">
                <i class="fas fa-exclamation-triangle"></i>
                <span class="font-medium">Negative Tickets</span>
            </a>
            <!-- FAQ Management -->
            <a href="{{ route('admin.faq.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-blue-700 transition-colors">
                <i class="fas fa-question-circle"></i>
                <span class="font-medium">FAQ Management</span>
            </a>
        </nav>

        <!-- Bottom Admin Section with added space -->
        <div class="p-4 border-t border-blue-800 mt-8">   <!-- ← Increased spacing here -->
            <div class="flex items-center gap-3 px-4 py-3">
                <div class="w-8 h-8 bg-white text-blue-800 rounded-full flex items-center justify-center font-bold">
                    N
                </div>
                <div class="flex-1 text-sm">
                    <p class="font-medium">NAN Admin</p>
                    <p class="text-blue-300 text-xs">admin@nancellphone.com</p>
                </div>
            </div>
            <a href="{{ route('logout') }}" class="btn btn-error btn-sm w-full mt-2">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b px-8 py-5 flex items-center justify-between shadow-sm">
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