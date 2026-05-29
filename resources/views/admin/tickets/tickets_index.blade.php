@extends('admin.layout')
@section('title', 'All Tickets')

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 style="font-weight:700;color:#0F172A;margin:0;">All Tickets</h5>
        <p style="font-size:13px;color:var(--text-muted);margin:2px 0 0;">Manage and respond to customer inquiries</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" style="font-size:13px;color:#1565C0;text-decoration:none;">
        ← Back to Dashboard
    </a>
</div>

{{-- Filters --}}
<div class="card-surface mb-4">
    <form method="GET" action="{{ route('admin.tickets.index') }}" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label style="font-size:12px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Search</label>
            <div style="position:relative;">
                <i class="bi bi-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#CBD5E1;font-size:14px;"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search subject..."
                    style="width:100%;padding:9px 12px 9px 34px;border:1.5px solid #E2E8F0;border-radius:8px;font-size:13px;color:#0F172A;background:#F8FAFC;outline:none;font-family:inherit;"
                    onfocus="this.style.borderColor='#1565C0'" onblur="this.style.borderColor='#E2E8F0'">
            </div>
        </div>
        <div class="col-md-3">
            <label style="font-size:12px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Status</label>
            <select name="status" style="width:100%;padding:9px 12px;border:1.5px solid #E2E8F0;border-radius:8px;font-size:13px;color:#0F172A;background:#F8FAFC;outline:none;font-family:inherit;">
                <option value="">All Status</option>
                <option value="Pending"    {{ request('status') == 'Pending'    ? 'selected' : '' }}>Pending</option>
                <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                <option value="Resolved"   {{ request('status') == 'Resolved'   ? 'selected' : '' }}>Resolved</option>
            </select>
        </div>
        <div class="col-md-3">
            <label style="font-size:12px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Sentiment</label>
            <select name="sentiment" style="width:100%;padding:9px 12px;border:1.5px solid #E2E8F0;border-radius:8px;font-size:13px;color:#0F172A;background:#F8FAFC;outline:none;font-family:inherit;">
                <option value="">All Sentiment</option>
                <option value="Negative" {{ request('sentiment') == 'Negative' ? 'selected' : '' }}>😠 Negative</option>
                <option value="Positive" {{ request('sentiment') == 'Positive' ? 'selected' : '' }}>😊 Positive</option>
                <option value="Neutral"  {{ request('sentiment') == 'Neutral'  ? 'selected' : '' }}>😐 Neutral</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" style="padding:9px 18px;background:#1565C0;color:white;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;flex:1;">
                Filter
            </button>
            <a href="{{ route('admin.tickets.index') }}" style="padding:9px 12px;background:#F1F5F9;color:#64748B;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;text-decoration:none;display:flex;align-items:center;">
                ✕
            </a>
        </div>
    </form>
</div>

{{-- Tickets Table --}}
<div class="card-surface">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Customer</th>
                    <th>Subject</th>
                    <th>Sentiment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr onclick="window.location='{{ route('admin.tickets.show', $ticket) }}'">
                    <td><span class="ticket-id">#{{ $ticket->ticket_number ?? 'TKT-'.str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</span></td>
                    <td>
                        <div style="font-weight:600;font-size:14px;color:#0F172A;">{{ $ticket->user->name ?? 'Unknown' }}</div>
                        <div style="font-size:12px;color:var(--text-muted);">{{ $ticket->user->email ?? '' }}</div>
                    </td>
                    <td style="color:var(--text-muted);max-width:220px;">{{ Str::limit($ticket->subject, 40) }}</td>
                    <td>
                        <span class="badge-sentiment badge-{{ strtolower($ticket->sentiment) }}">
                            @if($ticket->sentiment == 'Negative') 😠
                            @elseif($ticket->sentiment == 'Positive') 😊
                            @else 😐
                            @endif
                            {{ $ticket->sentiment }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-status badge-{{ strtolower($ticket->status) }}">
                            {{ $ticket->status }}
                        </span>
                    </td>
                    <td style="font-size:13px;color:var(--text-muted);">{{ $ticket->created_at->format('M d, Y') }}</td>
                    <td onclick="event.stopPropagation()">
                        <a href="{{ route('admin.tickets.show', $ticket) }}"
                           style="padding:6px 14px;background:#1565C0;color:white;border-radius:7px;font-size:12px;font-weight:600;text-decoration:none;">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:48px;color:var(--text-muted);">
                        <i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:10px;color:var(--text-xs);"></i>
                        No tickets found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($tickets->hasPages())
    <div style="padding:16px 0 0;">
        {{ $tickets->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection