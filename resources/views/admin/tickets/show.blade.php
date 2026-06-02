@extends('admin.layout')
@section('title', 'Ticket Detail')

@section('content')

{{-- Back + Header --}}
<div class="d-flex align-items-center gap-3 mb-4 fade-up">
    <a href="{{ route('admin.tickets.index') }}" class="btn-ghost" style="padding:7px 12px;">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
    <div>
        <span style="font-size:11.5px;font-weight:700;color:var(--text-3);">
            #TKT-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}
        </span>
        <h1 style="font-family:'Syne',sans-serif;font-size:20px;font-weight:800;color:var(--text-1);margin:2px 0 0;">
            {{ $ticket->subject }}
        </h1>
    </div>
</div>

<div class="row g-4">
    {{-- Left column --}}
    <div class="col-md-8">

        {{-- Customer Message --}}
        <div class="surface surface-pad mb-4 fade-up delay-1">
            <h6 class="section-title mb-3"><i class="bi bi-chat-left-text-fill"></i> Customer Message</h6>
            <div style="background:var(--bg);border-radius:12px;padding:16px;font-size:14px;line-height:1.7;color:var(--text-1);">
                {{ $ticket->description }}
            </div>
            <div style="font-size:11.5px;color:var(--text-3);margin-top:10px;">
                <i class="bi bi-clock me-1"></i>{{ $ticket->created_at->format('M d, Y h:i A') }}
            </div>
        </div>

        {{-- Staff Response --}}
        <div class="surface surface-pad mb-4 fade-up delay-2">
            <h6 class="section-title mb-3"><i class="bi bi-reply-fill"></i> Staff Response</h6>

            @if($ticket->staff_response)
                <div style="background:#EDFFF9;border-radius:12px;padding:16px;font-size:14px;line-height:1.7;color:var(--text-1);border-left:3px solid var(--success);margin-bottom:16px;">
                    {{ $ticket->staff_response }}
                </div>
            @else
                <div style="background:#FFF8EE;border-radius:12px;padding:14px 16px;font-size:13.5px;color:#9A5A0A;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-hourglass-split"></i> No response yet.
                </div>
            @endif

            <form action="{{ route('admin.tickets.respond', $ticket) }}" method="POST">
                @csrf
                <label class="form-label-sm">Write your response</label>
                <textarea name="staff_response" rows="5"
                    placeholder="Type your response here..."
                    class="form-ctrl"
                    style="resize:vertical;">{{ old('staff_response', $ticket->staff_response) }}</textarea>
                @error('staff_response')
                    <div style="color:var(--danger);font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
                <div class="mt-3">
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-send-fill"></i> Send Response & Resolve
                    </button>
                </div>
            </form>
        </div>

        {{-- Status Update --}}
        <div class="surface surface-pad fade-up delay-3">
            <h6 class="section-title mb-3"><i class="bi bi-arrow-repeat"></i> Update Status</h6>
            <form action="{{ route('admin.tickets.status', $ticket) }}" method="POST" class="d-flex align-items-center gap-3">
                @csrf
                @method('PATCH')
                <label class="form-label-sm mb-0" style="white-space:nowrap;">Change status to:</label>
                <select name="status" class="form-ctrl" style="max-width:180px;" onchange="this.form.submit()">
                    <option value="Pending"    {{ $ticket->status == 'Pending'    ? 'selected' : '' }}>Pending</option>
                    <option value="Processing" {{ $ticket->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                    <option value="Resolved"   {{ $ticket->status == 'Resolved'   ? 'selected' : '' }}>Resolved</option>
                </select>
                <span class="chip chip-{{ strtolower($ticket->status) }}">Current: {{ $ticket->status }}</span>
            </form>
        </div>

    </div>

    {{-- Right column --}}
    <div class="col-md-4">

        {{-- Ticket Meta --}}
        <div class="surface surface-pad mb-4 fade-up delay-1">
            <h6 class="section-title mb-3"><i class="bi bi-info-circle-fill"></i> Ticket Info</h6>
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div>
                    <div class="form-label-sm">Status</div>
                    <span class="chip chip-{{ strtolower($ticket->status) }}">{{ $ticket->status }}</span>
                </div>
                <div>
                    <div class="form-label-sm">AI Sentiment</div>
                    @php
                        $sentKey = strtolower($ticket->sentiment);
                        $dotColor = match($sentKey) {
                            'negative','urgent' => 'var(--danger)',
                            'positive' => 'var(--success)',
                            'frustrated' => 'var(--warning)',
                            default => '#5B72F8',
                        };
                    @endphp
                    <span class="chip chip-{{ $sentKey }}">
                        <span style="width:6px;height:6px;border-radius:50%;background:{{ $dotColor }};display:inline-block;"></span>
                        {{ $ticket->sentiment }}
                    </span>
                </div>
                <div>
                    <div class="form-label-sm">Ticket ID</div>
                    <span style="font-family:'Syne',sans-serif;font-size:12px;font-weight:700;color:var(--text-2);">
                        #TKT-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
                <div>
                    <div class="form-label-sm">Submitted</div>
                    <span style="font-size:13px;color:var(--text-2);">{{ $ticket->created_at->format('M d, Y h:i A') }}</span>
                </div>
            </div>
        </div>

        {{-- Customer --}}
        <div class="surface surface-pad fade-up delay-2">
            <h6 class="section-title mb-3"><i class="bi bi-person-fill"></i> Customer</h6>
            <div class="d-flex align-items-center gap-3">
                <div style="width:42px;height:42px;border-radius:12px;background:#EEF2FF;display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:800;font-size:16px;color:var(--primary);flex-shrink:0;">
                    {{ strtoupper(substr($ticket->user->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <div style="font-weight:600;font-size:14px;">{{ $ticket->user->name ?? 'Unknown' }}</div>
                    <div style="font-size:12px;color:var(--text-3);">{{ $ticket->user->email ?? '' }}</div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
