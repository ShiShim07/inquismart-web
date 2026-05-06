@extends('admin.layout')
@section('title', 'Ticket Detail')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
        <i class="bi bi-arrow-left"></i> Back to Tickets
    </a>
</div>

<div class="row g-3">
    <!-- Left Column -->
    <div class="col-md-8">
        <!-- Ticket Info -->
        <div class="stat-card mb-3">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div style="font-size:12px;color:#666;font-weight:600;">#TKT-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</div>
                    <h5 style="font-weight:700;color:#1A1A2E;margin:4px 0;">{{ $ticket->subject }}</h5>
                    <div style="font-size:12px;color:#666;">{{ $ticket->created_at->format('M d, Y h:i A') }}</div>
                </div>
                <div class="d-flex gap-2">
                    @if($ticket->sentiment == 'Urgent')
                        <span class="badge badge-urgent fs-6">🔴 Urgent</span>
                    @elseif($ticket->sentiment == 'Frustrated')
                        <span class="badge badge-frustrated fs-6">🟡 Frustrated</span>
                    @else
                        <span class="badge badge-neutral fs-6">🔵 Neutral</span>
                    @endif

                    @if($ticket->status == 'Resolved')
                        <span class="badge badge-resolved fs-6">✅ Resolved</span>
                    @elseif($ticket->status == 'Processing')
                        <span class="badge badge-processing fs-6">⏳ Processing</span>
                    @else
                        <span class="badge badge-pending fs-6">🔵 Pending</span>
                    @endif
                </div>
            </div>

            <!-- Customer Message -->
            <div style="background:#F5F7FA;border-radius:12px;padding:16px;margin-bottom:16px;">
                <div style="font-size:12px;color:#666;font-weight:600;margin-bottom:8px;">
                    <i class="bi bi-person-circle me-1"></i> Customer Message
                </div>
                <p style="margin:0;font-size:14px;color:#1A1A2E;line-height:1.6;">{{ $ticket->description }}</p>
            </div>

            <!-- Staff Response Form -->
            @if($ticket->status != 'Resolved')
            <div style="border-top:1px solid #E8ECEF;padding-top:16px;">
                <div style="font-size:13px;font-weight:600;color:#1A1A2E;margin-bottom:12px;">
                    <i class="bi bi-reply me-1 text-primary"></i> Send Response
                </div>
                <form method="POST" action="{{ route('admin.tickets.respond', $ticket) }}">
                    @csrf
                    <textarea name="staff_response" rows="4" class="form-control mb-3" placeholder="Type your response here..." style="border-radius:10px;font-size:14px;" required>{{ old('staff_response') }}</textarea>
                    @error('staff_response')
                        <div class="text-danger mb-2" style="font-size:12px;">{{ $message }}</div>
                    @enderror
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" style="border-radius:8px;">
                            <i class="bi bi-send me-1"></i> Send & Resolve Ticket
                        </button>
                    </div>
                </form>
            </div>
            @else
            <!-- Show Staff Response -->
            <div style="background:#E8F5E9;border-radius:12px;padding:16px;border-left:4px solid #2E7D32;">
                <div style="font-size:12px;color:#2E7D32;font-weight:600;margin-bottom:8px;">
                    <i class="bi bi-check-circle me-1"></i> Staff Response — {{ $ticket->responded_at ? $ticket->responded_at->format('M d, Y h:i A') : '' }}
                </div>
                <p style="margin:0;font-size:14px;color:#1A1A2E;line-height:1.6;">{{ $ticket->staff_response }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-md-4">
        <!-- Customer Info -->
        <div class="stat-card mb-3">
            <h6 style="font-weight:700;color:#1A1A2E;margin-bottom:16px;">
                <i class="bi bi-person me-2 text-primary"></i>Customer Info
            </h6>
            <div style="font-size:14px;">
                <div class="mb-2">
                    <strong>Name:</strong><br>
                    <span style="color:#666;">{{ $ticket->user->name ?? 'Unknown' }}</span>
                </div>
                <div class="mb-2">
                    <strong>Email:</strong><br>
                    <span style="color:#666;">{{ $ticket->user->email ?? 'N/A' }}</span>
                </div>
                <div class="mb-2">
                    <strong>Phone:</strong><br>
                    <span style="color:#666;">{{ $ticket->user->phone ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Update Status -->
        <div class="stat-card mb-3">
            <h6 style="font-weight:700;color:#1A1A2E;margin-bottom:16px;">
                <i class="bi bi-arrow-repeat me-2 text-primary"></i>Update Status
            </h6>
            <form method="POST" action="{{ route('admin.tickets.status', $ticket) }}">
                @csrf
                @method('PATCH')
                <select name="status" class="form-select form-select-sm mb-2">
                    <option value="Pending" {{ $ticket->status == 'Pending' ? 'selected' : '' }}>🔵 Pending</option>
                    <option value="Processing" {{ $ticket->status == 'Processing' ? 'selected' : '' }}>⏳ Processing</option>
                    <option value="Resolved" {{ $ticket->status == 'Resolved' ? 'selected' : '' }}>✅ Resolved</option>
                </select>
                <button type="submit" class="btn btn-sm btn-outline-primary w-100" style="border-radius:8px;">
                    Update Status
                </button>
            </form>
        </div>

        <!-- AI Sentiment Analysis Info -->
        <div class="stat-card">
            <h6 style="font-weight:700;color:#1A1A2E;margin-bottom:16px;">
                <i class="bi bi-robot me-2 text-primary"></i>AI Analysis
            </h6>
            <div class="text-center p-3 rounded-3 mb-2"
                style="background:{{ $ticket->sentiment == 'Urgent' ? '#FFEBEE' : ($ticket->sentiment == 'Frustrated' ? '#FFF8E1' : '#E3F2FD') }}">
                <div style="font-size:32px;">
                    {{ $ticket->sentiment == 'Urgent' ? '🔴' : ($ticket->sentiment == 'Frustrated' ? '🟡' : '🔵') }}
                </div>
                <div style="font-weight:700;font-size:16px;color:{{ $ticket->sentiment == 'Urgent' ? '#C62828' : ($ticket->sentiment == 'Frustrated' ? '#F57F17' : '#1565C0') }}">
                    {{ $ticket->sentiment }}
                </div>
                <div style="font-size:12px;color:#666;margin-top:4px;">
                    @if($ticket->sentiment == 'Urgent')
                        Customer needs immediate attention
                    @elseif($ticket->sentiment == 'Frustrated')
                        Customer needs reassurance
                    @else
                        Standard inquiry
                    @endif
                </div>
            </div>
            <div style="font-size:11px;color:#666;text-align:center;">
                Analyzed by InquiSmart AI Engine
            </div>
        </div>
    </div>
</div>
@endsection
