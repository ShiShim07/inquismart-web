<!DOCTYPE html>
<html>
<head>
    <title>All Tickets — InquiSmart Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="max-w-6xl mx-auto py-10 px-4">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">All Tickets</h1>
            <p class="text-sm text-gray-400 mt-1">Manage and respond to customer inquiries</p>
        </div>
        <a href="{{ route('admin.dashboard') }}"
           class="text-blue-600 hover:underline text-sm">← Back to Dashboard</a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.tickets.index') }}"
          class="bg-white rounded-2xl shadow p-4 mb-6 flex flex-wrap gap-3 items-end">

        <div>
            <label class="text-xs text-gray-500 block mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search subject..."
                   class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-400">
        </div>

        <div>
            <label class="text-xs text-gray-500 block mb-1">Status</label>
            <select name="status" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none">
                <option value="">All Status</option>
                <option value="Pending"    {{ request('status') == 'Pending'    ? 'selected' : '' }}>Pending</option>
                <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                <option value="Resolved"   {{ request('status') == 'Resolved'   ? 'selected' : '' }}>Resolved</option>
            </select>
        </div>

        <div>
            <label class="text-xs text-gray-500 block mb-1">Sentiment</label>
            <select name="sentiment" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none">
                <option value="">All Sentiment</option>
                <option value="Urgent"     {{ request('sentiment') == 'Urgent'     ? 'selected' : '' }}>🔴 Urgent</option>
                <option value="Frustrated" {{ request('sentiment') == 'Frustrated' ? 'selected' : '' }}>🟡 Frustrated</option>
                <option value="Neutral"    {{ request('sentiment') == 'Neutral'    ? 'selected' : '' }}>🔵 Neutral</option>
            </select>
        </div>

        <button type="submit"
            class="bg-blue-600 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-blue-700">
            Filter
        </button>

        <a href="{{ route('admin.tickets.index') }}"
           class="text-gray-500 text-sm hover:underline py-2">Clear</a>
    </form>

    {{-- Tickets Table --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4 text-left">Ticket ID</th>
                    <th class="px-6 py-4 text-left">Customer</th>
                    <th class="px-6 py-4 text-left">Subject</th>
                    <th class="px-6 py-4 text-left">Sentiment</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-left">Date</th>
                    <th class="px-6 py-4 text-left">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tickets as $ticket)
                    @php
                        $statusColor = match($ticket->status) {
                            'Resolved'   => 'bg-green-100 text-green-700',
                            'Processing' => 'bg-yellow-100 text-yellow-700',
                            default      => 'bg-blue-100 text-blue-700',
                        };
                        $sentimentColor = match($ticket->sentiment) {
                            'Urgent'     => 'bg-red-100 text-red-700',
                            'Frustrated' => 'bg-orange-100 text-orange-700',
                            default      => 'bg-gray-100 text-gray-600',
                        };
                        $sentimentIcon = match($ticket->sentiment) {
                            'Urgent'     => '🔴',
                            'Frustrated' => '🟡',
                            default      => '🔵',
                        };
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-400 font-mono">
                            #TKT-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-800">{{ $ticket->user->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-400">{{ $ticket->user->email ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-700 max-w-xs truncate">
                            {{ $ticket->subject }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $sentimentColor }}">
                                {{ $sentimentIcon }} {{ $ticket->sentiment }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                {{ $ticket->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-xs">
                            {{ $ticket->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.tickets.show', $ticket) }}"
                               class="bg-blue-600 text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-blue-700">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <p class="text-4xl mb-2">📭</p>
                            <p>No tickets found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($tickets->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $tickets->withQueryString()->links() }}
            </div>
        @endif
    </div>

</div>
</body>
</html>