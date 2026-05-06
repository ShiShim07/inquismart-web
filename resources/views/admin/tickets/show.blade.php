<!DOCTYPE html>
<html>
<head>
    <title>Ticket Detail — InquiSmart Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="max-w-4xl mx-auto py-10 px-4">

    {{-- Back Button --}}
    <a href="{{ route('admin.tickets.index') }}"
       class="inline-flex items-center text-blue-600 hover:underline mb-6">
        ← Back to All Tickets
    </a>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Ticket Info --}}
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-gray-400">#TKT-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</p>
                <h1 class="text-2xl font-bold text-gray-800 mt-1">{{ $ticket->subject }}</h1>
                <p class="text-sm text-gray-400 mt-1">{{ $ticket->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div class="flex flex-col items-end gap-2">
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
                @endphp
                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusColor }}">
                    {{ $ticket->status }}
                </span>
                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $sentimentColor }}">
                    🧠 {{ $ticket->sentiment }}
                </span>
            </div>
        </div>
    </div>

    {{-- Customer Info --}}
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <h2 class="text-sm font-bold text-gray-500 uppercase mb-3">Customer</h2>
        <p class="text-gray-800 font-semibold">{{ $ticket->user->name ?? 'Unknown' }}</p>
        <p class="text-gray-500 text-sm">{{ $ticket->user->email ?? '' }}</p>
    </div>

    {{-- Customer Message --}}
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <h2 class="text-sm font-bold text-gray-500 uppercase mb-3">Customer Message</h2>
        <div class="bg-gray-50 rounded-xl p-4 text-gray-700 leading-relaxed">
            {{ $ticket->description }}
        </div>
    </div>

    {{-- Staff Response --}}
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <h2 class="text-sm font-bold text-gray-500 uppercase mb-3">Staff Response</h2>

        @if($ticket->staff_response)
            <div class="bg-green-50 rounded-xl p-4 text-gray-700 leading-relaxed mb-4">
                {{ $ticket->staff_response }}
            </div>
        @else
            <div class="bg-yellow-50 rounded-xl p-4 text-yellow-700 mb-4">
                ⏳ No response yet.
            </div>
        @endif

        {{-- ✅ Response Form — standalone, hindi nested --}}
        <form action="{{ route('admin.tickets.respond', $ticket) }}" method="POST">
            @csrf
            <textarea
                name="staff_response"
                rows="4"
                placeholder="Type your response here..."
                class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:border-blue-400"
            >{{ old('staff_response', $ticket->staff_response) }}</textarea>

            @error('staff_response')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <button type="submit"
                class="mt-3 bg-blue-600 text-white px-6 py-2 rounded-xl text-sm font-semibold hover:bg-blue-700">
                Send Response & Resolve
            </button>
        </form>

        {{-- ✅ Status Form — SEPARATE, hindi na nested sa loob ng respond form --}}
        <form action="{{ route('admin.tickets.status', $ticket) }}" method="POST" class="mt-3">
            @csrf
            @method('PATCH')
            <label class="text-sm text-gray-500 mr-2">Update Status:</label>
            <select name="status" onchange="this.form.submit()"
                class="border border-gray-200 rounded-xl px-3 py-2 text-sm text-gray-600 focus:outline-none">
                <option value="Pending"    {{ $ticket->status == 'Pending'    ? 'selected' : '' }}>Pending</option>
                <option value="Processing" {{ $ticket->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                <option value="Resolved"   {{ $ticket->status == 'Resolved'   ? 'selected' : '' }}>Resolved</option>
            </select>
        </form>
    </div>

</div>
</body>
</html>