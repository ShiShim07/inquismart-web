@extends('admin.layout')
@section('title', 'Ticket Detail')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Back to Tickets
    </a>
</div>

<div class="row g-4">
    {{-- LEFT: Main ticket --}}
    <div class="col-md-8">
        <div class="card-surface mb-4">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <span class="ticket-id" style="font-size:13px;">#{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</span>
                    <h4 style="font-size:18px;font-weight:600;color:var(--text-primary);margin:8px 0 4px;letter-spacing:-0.3px;">
                        {{ $ticket->subject }}
                    </h4>
                    <div style="font-size:12.5px;color:var(--text-muted);">
                        <i class="bi bi-clock me-1"></i>{{ $ticket->created_at->format('M d, Y · h:i A') }}
                    </div>
                </div>
                <div class="d-flex gap-2 flex-shrink-0">
                    @if($ticket->sentiment == 'Urgent')
                        <span class="badge-sentiment badge-urgent" style="font-size:12px;padding:5px 12px;">Urgent</span>
                    @elseif($ticket->sentiment == 'Frustrated')
                        <span class="badge-sentiment badge-frustrated" style="font-size:12px;padding:5px 12px;">Frustrated</span>
                    @else
                        <span class="badge-sentiment badge-neutral" style="font-size:12px;padding:5px 12px;">Neutral</span>
                    @endif

                    @if($ticket->status == 'Resolved')
                        <span class="badge-status badge-resolved" style="font-size:12px;padding:5px 12px;">Resolved</span>
                    @elseif($ticket->status == 'Processing')
                        <span class="badge-status badge-processing" style="font-size:12px;padding:5px 12px;">Processing</span>
                    @else
                        <span class="badge-status badge-pending" style="font-size:12px;padding:5px 12px;">Pending</span>
                    @endif
                </div>
            </div>

            {{-- Customer Message --}}
            <div style="background:var(--surface);border-radius:12px;padding:18px;margin-bottom:20px;border:1px solid var(--border);">
                <div style="font-size:11.5px;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:10px;">
                    <i class="bi bi-person-circle me-1"></i> Customer Message
                </div>
                <p style="margin:0;font-size:14px;color:var(--text-primary);line-height:1.65;">{{ $ticket->description }}</p>
            </div>

            {{-- Response Area --}}
            @if($ticket->status != 'Resolved')
            <div style="border-top:1px solid var(--border);padding-top:20px;">
                <div style="font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:12px;display:flex;align-items:center;gap:7px;">
                    <i class="bi bi-reply" style="color:var(--accent);"></i> Send Response
                </div>
                <form method="POST" action="{{ route('admin.tickets.respond', $ticket) }}">
                    @csrf
                    <textarea name="staff_response" rows="4" class="form-control mb-3"
                        placeholder="Type your response here..." required>{{ old('staff_response') }}</textarea>
                    @error('staff_response')
                        <div style="color:#DC2626;font-size:12px;margin-bottom:10px;">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i> Send & Resolve Ticket
                    </button>
                </form>
            </div>
            @else
            {{-- Resolved response --}}
            <div style="background:#F0FDF4;border-radius:12px;padding:18px;border:1px solid #BBF7D0;border-left:3px solid #22C55E;">
                <div style="font-size:11.5px;color:#166534;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:10px;">
                    <i class="bi bi-check-circle-fill me-1"></i>
                    Staff Response · {{ $ticket->responded_at ? $ticket->responded_at->format('M d, Y · h:i A') : '' }}
                </div>
                <p style="margin:0;font-size:14px;color:var(--text-primary);line-height:1.65;">{{ $ticket->staff_response }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- RIGHT: Sidebar panels --}}
    <div class="col-md-4">

        {{-- Customer Info --}}
        <div class="card-surface mb-3">
            <h6 class="section-header mb-3">
                <i class="bi bi-person-badge"></i> Customer Info
            </h6>
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div>
                    <div style="font-size:11.5px;color:var(--text-muted);margin-bottom:2px;">Full name</div>
                    <div style="font-size:13.5px;font-weight:500;">{{ $ticket->user->name ?? 'Unknown' }}</div>
                </div>
                <div>
                    <div style="font-size:11.5px;color:var(--text-muted);margin-bottom:2px;">Email</div>
                    <div style="font-size:13.5px;">{{ $ticket->user->email ?? 'N/A' }}</div>
                </div>
                <div>
                    <div style="font-size:11.5px;color:var(--text-muted);margin-bottom:2px;">Phone</div>
                    <div style="font-size:13.5px;">{{ $ticket->user->phone ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        {{-- Update Status --}}
        <div class="card-surface mb-3">
            <h6 class="section-header mb-3">
                <i class="bi bi-arrow-repeat"></i> Update Status
            </h6>
            <form method="POST" action="{{ route('admin.tickets.status', $ticket) }}">
                @csrf @method('PATCH')
                <select name="status" class="form-select form-select-sm mb-2">
                    <option value="Pending" {{ $ticket->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Processing" {{ $ticket->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                    <option value="Resolved" {{ $ticket->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
                <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                    <i class="bi bi-check2"></i> Update
                </button>
            </form>
        </div>

        {{-- AI Sentiment Analysis --}}
        <div class="card-surface">
            <h6 class="section-header mb-3">
                <i class="bi bi-robot"></i> AI Analysis
            </h6>
            <div class="text-center p-4 rounded-3 mb-3"
                style="background:{{ $ticket->sentiment == 'Urgent' ? 'var(--urgent-bg)' : ($ticket->sentiment == 'Frustrated' ? 'var(--frustrated-bg)' : 'var(--neutral-bg)') }};
                       border:1px solid {{ $ticket->sentiment == 'Urgent' ? 'var(--urgent-bd)' : ($ticket->sentiment == 'Frustrated' ? 'var(--frustrated-bd)' : 'var(--neutral-bd)') }};">
                <div style="font-size:36px;margin-bottom:6px;">
                    {{ $ticket->sentiment == 'Urgent' ? '🔴' : ($ticket->sentiment == 'Frustrated' ? '🟡' : '🔵') }}
                </div>
                <div style="font-size:16px;font-weight:600;color:{{ $ticket->sentiment == 'Urgent' ? 'var(--urgent-text)' : ($ticket->sentiment == 'Frustrated' ? 'var(--frustrated-text)' : 'var(--neutral-text)') }}">
                    {{ $ticket->sentiment }}
                </div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:5px;">
                    @if($ticket->sentiment == 'Urgent') Immediate attention needed
                    @elseif($ticket->sentiment == 'Frustrated') Customer needs reassurance
                    @else Standard inquiry
                    @endif
                </div>
            </div>
            <div style="font-size:11px;color:var(--text-xs);text-align:center;">
                Analyzed by InquiSmart AI Engine
            </div>
        </div>

    </div>
</div>

@endsection
