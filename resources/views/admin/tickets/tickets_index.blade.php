@extends('admin.layout')
@section('title', 'Ticket Management')

@section('content')

{{-- Filter Bar --}}
<div class="card-surface mb-4">
    <form method="GET" action="{{ route('admin.tickets.index') }}" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label>Search tickets</label>
            <div style="position:relative;">
                <i class="bi bi-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:14px;"></i>
                <input type="text" name="search" class="form-control form-control-sm" style="padding-left:32px;" placeholder="Search by subject or customer..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <label>Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All Statuses</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
            </select>
        </div>
        <div class="col-md-3">
            <label>Sentiment</label>
            <select name="sentiment" class="form-select form-select-sm">
                <option value="">All Sentiments</option>
                <option value="Urgent" {{ request('sentiment') == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                <option value="Frustrated" {{ request('sentiment') == 'Frustrated' ? 'selected' : '' }}>Frustrated</option>
                <option value="Neutral" {{ request('sentiment') == 'Neutral' ? 'selected' : '' }}>Neutral</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm w-100">
                <i class="bi bi-funnel"></i> Filter
            </button>
            @if(request()->hasAny(['search','status','sentiment']))
            <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary btn-sm" title="Clear filters">
                <i class="bi bi-x-lg"></i>
            </a>
            @endif
        </div>
    </form>
</div>

{{-- Tickets Table --}}
<div class="card-surface">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h6 class="section-header">
            <i class="bi bi-ticket-perforated"></i>
            All Tickets
            <span style="background:#EFF6FF;color:#1E40AF;font-size:11px;font-weight:600;padding:2px 8px;border-radius:20px;border:1px solid #BFDBFE;">
                {{ $tickets->total() }}
            </span>
        </h6>
    </div>

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
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr onclick="window.location='{{ route('admin.tickets.show', $ticket) }}'">
                    <td>
                        <span class="ticket-id">#{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</span>
                    </td>
                    <td>
                        <div style="font-weight:500;font-size:13.5px;">{{ $ticket->user->name ?? 'Unknown' }}</div>
                        <div style="font-size:11.5px;color:var(--text-muted);">{{ $ticket->user->email ?? '' }}</div>
                    </td>
                    <td style="color:var(--text-muted);">{{ Str::limit($ticket->subject, 42) }}</td>
                    <td>
                        @if($ticket->sentiment == 'Urgent')
                            <span class="badge-sentiment badge-urgent">Urgent</span>
                        @elseif($ticket->sentiment == 'Frustrated')
                            <span class="badge-sentiment badge-frustrated">Frustrated</span>
                        @else
                            <span class="badge-sentiment badge-neutral">Neutral</span>
                        @endif
                    </td>
                    <td>
                        @if($ticket->status == 'Resolved')
                            <span class="badge-status badge-resolved">Resolved</span>
                        @elseif($ticket->status == 'Processing')
                            <span class="badge-status badge-processing">Processing</span>
                        @else
                            <span class="badge-status badge-pending">Pending</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);font-size:13px;white-space:nowrap;">
                        {{ $ticket->created_at->format('M d, Y') }}
                    </td>
                    <td onclick="event.stopPropagation()">
                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:52px;color:var(--text-muted);">
                        <i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:10px;color:var(--text-xs);"></i>
                        <div style="font-weight:500;margin-bottom:4px;">No tickets found</div>
                        <div style="font-size:12.5px;">Try adjusting your filters</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($tickets->hasPages())
    <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
        {{ $tickets->links() }}
    </div>
    @endif
</div>

@endsection
