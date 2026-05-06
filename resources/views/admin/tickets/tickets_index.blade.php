@extends('admin.layout')
@section('title', 'Ticket Management')

@section('content')
<!-- Filters -->
<div class="stat-card mb-4">
    <form method="GET" action="{{ route('admin.tickets.index') }}" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label style="font-size:12px;color:#666;font-weight:600;">Search</label>
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by subject..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <label style="font-size:12px;color:#666;font-weight:600;">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
            </select>
        </div>
        <div class="col-md-3">
            <label style="font-size:12px;color:#666;font-weight:600;">Sentiment</label>
            <select name="sentiment" class="form-select form-select-sm">
                <option value="">All Sentiment</option>
                <option value="Urgent" {{ request('sentiment') == 'Urgent' ? 'selected' : '' }}>🔴 Urgent</option>
                <option value="Frustrated" {{ request('sentiment') == 'Frustrated' ? 'selected' : '' }}>🟡 Frustrated</option>
                <option value="Neutral" {{ request('sentiment') == 'Neutral' ? 'selected' : '' }}>🔵 Neutral</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-sm w-100">
                <i class="bi bi-search"></i> Filter
            </button>
        </div>
    </form>
</div>

<!-- Tickets Table -->
<div class="stat-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 style="font-weight:700;color:#1A1A2E;margin:0;">
            <i class="bi bi-ticket-perforated me-2 text-primary"></i>
            All Tickets <span class="badge bg-primary ms-1">{{ $tickets->total() }}</span>
        </h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead style="background:#F8F9FA;">
                <tr style="font-size:12px;color:#666;text-transform:uppercase;">
                    <th>ID</th>
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
                <tr class="ticket-row" style="font-size:13px;" onclick="window.location='{{ route('admin.tickets.show', $ticket) }}'">
                    <td><strong>#TKT-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</strong></td>
                    <td>
                        <div style="font-weight:600;">{{ $ticket->user->name ?? 'Unknown' }}</div>
                        <div style="font-size:11px;color:#666;">{{ $ticket->user->email ?? '' }}</div>
                    </td>
                    <td>{{ Str::limit($ticket->subject, 40) }}</td>
                    <td>
                        @if($ticket->sentiment == 'Urgent')
                            <span class="badge badge-urgent">🔴 Urgent</span>
                        @elseif($ticket->sentiment == 'Frustrated')
                            <span class="badge badge-frustrated">🟡 Frustrated</span>
                        @else
                            <span class="badge badge-neutral">🔵 Neutral</span>
                        @endif
                    </td>
                    <td>
                        @if($ticket->status == 'Resolved')
                            <span class="badge badge-resolved">✅ Resolved</span>
                        @elseif($ticket->status == 'Processing')
                            <span class="badge badge-processing">⏳ Processing</span>
                        @else
                            <span class="badge badge-pending">🔵 Pending</span>
                        @endif
                    </td>
                    <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                    <td onclick="event.stopPropagation()">
                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-sm btn-primary" style="border-radius:6px;font-size:11px;">
                            <i class="bi bi-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        No tickets found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $tickets->links() }}
    </div>
</div>
@endsection