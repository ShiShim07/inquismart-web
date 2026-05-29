@extends('admin.layout')
@section('title', 'Ticket Detail')

@section('content')

{{-- Back --}}
<a href="{{ route('admin.tickets.index') }}"
   style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:#1565C0;text-decoration:none;margin-bottom:20px;">
    <i class="bi bi-arrow-left"></i> Back to All Tickets
</a>

<div class="row g-4">

    {{-- LEFT COLUMN --}}
    <div class="col-lg-8">

        {{-- Ticket Header --}}
        <div class="card-surface mb-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span style="font-size:12px;color:var(--text-muted);font-weight:600;">
                        #{{ $ticket->ticket_number ?? 'TKT-'.str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}
                    </span>
                    <h5 style="font-size:20px;font-weight:700;color:#0F172A;margin:6px 0 4px;">{{ $ticket->subject }}</h5>
                    <span style="font-size:13px;color:var(--text-muted);">
                        <i class="bi bi-calendar3 me-1"></i>{{ $ticket->created_at->format('M d, Y h:i A') }}
                    </span>
                </div>
                <div class="d-flex flex-column align-items-end gap-2">
                    <span class="badge-status badge-{{ strtolower($ticket->status) }}">{{ $ticket->status }}</span>
                    <span class="badge-sentiment badge-{{ strtolower($ticket->sentiment) }}">
                        @if($ticket->sentiment == 'Negative') 😠
                        @elseif($ticket->sentiment == 'Positive') 😊
                        @else 😐
                        @endif
                        {{ $ticket->sentiment }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Customer Message --}}
        <div class="card-surface mb-4">
            <h6 class="section-header mb-3">
                <i class="bi bi-person-circle" style="color:#1565C0;"></i> Customer Message
            </h6>
            <div style="background:#F8FAFC;border-radius:10px;padding:16px;font-size:14px;color:#374151;line-height:1.7;border:1px solid #E2E8F0;">
                {{ $ticket->description }}
            </div>
        </div>

        {{-- Staff Response --}}
        <div class="card-surface">
            <h6 class="section-header mb-3">
                <i class="bi bi-headset" style="color:#0D9488;"></i> Staff Response
            </h6>

            @if($ticket->staff_response)
            <div style="background:#F0FDF4;border-radius:10px;padding:16px;font-size:14px;color:#374151;line-height:1.7;border:1px solid #BBF7D0;margin-bottom:16px;">
                {{ $ticket->staff_response }}
            </div>
            @else
            <div style="background:#FFFBEB;border-radius:10px;padding:14px 16px;font-size:13px;color:#92400E;border:1px solid #FCD34D;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <i class="bi bi-hourglass-top"></i> No response yet.
            </div>
            @endif

            {{-- Response Form --}}
            <form action="{{ route('admin.tickets.respond', $ticket) }}" method="POST">
                @csrf
                <textarea name="staff_response" rows="4"
                    placeholder="Type your response here..."
                    style="width:100%;padding:12px 14px;border:1.5px solid #E2E8F0;border-radius:10px;font-size:14px;color:#0F172A;background:#F8FAFC;outline:none;font-family:inherit;resize:vertical;transition:border-color 0.2s;"
                    onfocus="this.style.borderColor='#1565C0'" onblur="this.style.borderColor='#E2E8F0'"
                >{{ old('staff_response', $ticket->staff_response) }}</textarea>

                @error('staff_response')
                    <p style="color:#DC2626;font-size:12px;margin-top:4px;">{{ $message }}</p>
                @enderror

                <button type="submit"
                    style="margin-top:10px;padding:10px 20px;background:#1565C0;color:white;border:none;border-radius:9px;font-size:13.5px;font-weight:600;cursor:pointer;font-family:inherit;display:inline-flex;align-items:center;gap:7px;transition:background 0.2s;"
                    onmouseover="this.style.background='#0D47A1'" onmouseout="this.style.background='#1565C0'">
                    <i class="bi bi-send"></i> Send Response & Resolve
                </button>
            </form>
        </div>

    </div>

    {{-- RIGHT COLUMN --}}
    <div class="col-lg-4">

        {{-- Customer Info --}}
        <div class="card-surface mb-4">
            <h6 class="section-header mb-3">
                <i class="bi bi-person" style="color:#7C3AED;"></i> Customer
            </h6>
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:42px;height:42px;background:#F5F3FF;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <span style="font-size:17px;font-weight:700;color:#7C3AED;">
                        {{ strtoupper(substr($ticket->user->name ?? 'U', 0, 1)) }}
                    </span>
                </div>
                <div>
                    <div style="font-weight:600;font-size:14px;color:#0F172A;">{{ $ticket->user->name ?? 'Unknown' }}</div>
                    <div style="font-size:12px;color:var(--text-muted);">{{ $ticket->user->email ?? '' }}</div>
                </div>
            </div>
        </div>

        {{-- Update Status --}}
        <div class="card-surface mb-4">
            <h6 class="section-header mb-3">
                <i class="bi bi-arrow-repeat" style="color:#D97706;"></i> Update Status
            </h6>
            <form action="{{ route('admin.tickets.status', $ticket) }}" method="POST">
                @csrf
                @method('PATCH')
                <select name="status" onchange="this.form.submit()"
                    style="width:100%;padding:10px 12px;border:1.5px solid #E2E8F0;border-radius:9px;font-size:13.5px;color:#0F172A;background:#F8FAFC;outline:none;font-family:inherit;cursor:pointer;">
                    <option value="Pending"    {{ $ticket->status == 'Pending'    ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="Processing" {{ $ticket->status == 'Processing' ? 'selected' : '' }}>🔄 Processing</option>
                    <option value="Resolved"   {{ $ticket->status == 'Resolved'   ? 'selected' : '' }}>✅ Resolved</option>
                </select>
            </form>
        </div>

        {{-- Ticket Timeline --}}
        <div class="card-surface">
            <h6 class="section-header mb-3">
                <i class="bi bi-clock-history" style="color:#0D9488;"></i> Timeline
            </h6>
            @php
                $steps = [
                    ['label' => 'Submitted',  'done' => true,                                                              'time' => $ticket->created_at],
                    ['label' => 'Processing', 'done' => in_array($ticket->status, ['Processing','Resolved']),             'time' => $ticket->processing_at],
                    ['label' => 'Resolved',   'done' => $ticket->status == 'Resolved',                                   'time' => $ticket->resolved_at],
                ];
            @endphp
            @foreach($steps as $i => $step)
            <div style="display:flex;align-items:flex-start;gap:12px;{{ !$loop->last ? 'margin-bottom:4px;' : '' }}">
                <div style="display:flex;flex-direction:column;align-items:center;">
                    <div style="width:26px;height:26px;border-radius:50%;background:{{ $step['done'] ? '#22C55E' : '#E2E8F0' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-{{ $step['done'] ? 'check' : 'circle' }}" style="color:white;font-size:12px;"></i>
                    </div>
                    @if(!$loop->last)
                    <div style="width:2px;height:28px;background:{{ $step['done'] ? 'rgba(34,197,94,0.3)' : '#E2E8F0' }};margin:2px 0;"></div>
                    @endif
                </div>
                <div style="padding-top:3px;">
                    <div style="font-size:13px;font-weight:600;color:{{ $step['done'] ? '#166534' : '#9CA3AF' }};">{{ $step['label'] }}</div>
                    @if($step['time'])
                    <div style="font-size:11px;color:var(--text-muted);">{{ \Carbon\Carbon::parse($step['time'])->format('M d, Y h:i A') }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>

@endsection