@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')
<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div style="font-size:13px;color:#666;margin-bottom:6px;">Total Tickets</div>
                    <div style="font-size:28px;font-weight:700;color:#1A1A2E;">{{ $totalTickets }}</div>
                </div>
                <div class="icon" style="background:#E3F2FD;">
                    <i class="bi bi-ticket-perforated" style="color:#1565C0;"></i>
                </div>
            </div>
            <div style="font-size:12px;color:#666;margin-top:12px;">
                <span class="text-success"><i class="bi bi-arrow-up"></i> All time</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div style="font-size:13px;color:#666;margin-bottom:6px;">Pending</div>
                    <div style="font-size:28px;font-weight:700;color:#1565C0;">{{ $pendingTickets }}</div>
                </div>
                <div class="icon" style="background:#E3F2FD;">
                    <i class="bi bi-hourglass" style="color:#1565C0;"></i>
                </div>
            </div>
            <div style="font-size:12px;color:#666;margin-top:12px;">Awaiting response</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div style="font-size:13px;color:#666;margin-bottom:6px;">Urgent</div>
                    <div style="font-size:28px;font-weight:700;color:#C62828;">{{ $urgentTickets }}</div>
                </div>
                <div class="icon" style="background:#FFEBEE;">
                    <i class="bi bi-exclamation-triangle" style="color:#C62828;"></i>
                </div>
            </div>
            <div style="font-size:12px;color:#C62828;margin-top:12px;">Needs immediate attention</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div style="font-size:13px;color:#666;margin-bottom:6px;">Resolved</div>
                    <div style="font-size:28px;font-weight:700;color:#2E7D32;">{{ $resolvedTickets }}</div>
                </div>
                <div class="icon" style="background:#E8F5E9;">
                    <i class="bi bi-check-circle" style="color:#2E7D32;"></i>
                </div>
            </div>
            <div style="font-size:12px;color:#2E7D32;margin-top:12px;">Successfully handled</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div style="font-size:13px;color:#666;margin-bottom:6px;">Total Customers</div>
            <div style="font-size:24px;font-weight:700;color:#7B1FA2;">{{ $totalCustomers }}</div>
            <div style="font-size:12px;color:#666;">Registered users</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div style="font-size:13px;color:#666;margin-bottom:6px;">Processing</div>
            <div style="font-size:24px;font-weight:700;color:#F57F17;">{{ $processingTickets }}</div>
            <div style="font-size:12px;color:#666;">In progress</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div style="font-size:13px;color:#666;margin-bottom:6px;">Total FAQs</div>
            <div style="font-size:24px;font-weight:700;color:#00897B;">{{ $totalFaqs }}</div>
            <div style="font-size:12px;color:#666;">Active FAQ entries</div>
        </div>
    </div>
</div>

<!-- Sentiment Summary -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="stat-card">
            <h6 class="mb-3" style="font-weight:700;color:#1A1A2E;">
                <i class="bi bi-psychology me-2 text-primary"></i>AI Sentiment Overview (Active Tickets)
            </h6>
            <div class="row text-center">
                <div class="col-4">
                    <div class="p-3 rounded-3" style="background:#FFEBEE;">
                        <div style="font-size:28px;font-weight:700;color:#C62828;">{{ $urgentTickets }}</div>
                        <div style="font-size:13px;color:#C62828;font-weight:600;">🔴 Urgent</div>
                        <div style="font-size:11px;color:#666;">Immediate attention needed</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-3 rounded-3" style="background:#FFF8E1;">
                        <div style="font-size:28px;font-weight:700;color:#F57F17;">{{ $frustratedTickets }}</div>
                        <div style="font-size:13px;color:#F57F17;font-weight:600;">🟡 Frustrated</div>
                        <div style="font-size:11px;color:#666;">Customer needs reassurance</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-3 rounded-3" style="background:#E3F2FD;">
                        <div style="font-size:28px;font-weight:700;color:#1565C0;">{{ $neutralTickets }}</div>
                        <div style="font-size:13px;color:#1565C0;font-weight:600;">🔵 Neutral</div>
                        <div style="font-size:11px;color:#666;">Standard inquiry</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Tickets -->
<div class="stat-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 style="font-weight:700;color:#1A1A2E;margin:0;">
            <i class="bi bi-clock-history me-2 text-primary"></i>Recent Tickets
        </h6>
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-primary" style="border-radius:8px;">
            View All <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead style="background:#F8F9FA;">
                <tr style="font-size:12px;color:#666;text-transform:uppercase;">
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
                @forelse($recentTickets as $ticket)
                <tr class="ticket-row" style="font-size:13px;">
                    <td><strong>#TKT-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</strong></td>
                    <td>{{ $ticket->user->name ?? 'Unknown' }}</td>
                    <td>{{ Str::limit($ticket->subject, 35) }}</td>
                    <td>
                        <span class="badge badge-{{ strtolower($ticket->sentiment) }}">
                            {{ $ticket->sentiment }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ strtolower($ticket->status) }}">
                            {{ $ticket->status }}
                        </span>
                    </td>
                    <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary" style="border-radius:6px;font-size:11px;">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No tickets yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection