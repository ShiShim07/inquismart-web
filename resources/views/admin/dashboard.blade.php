@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')

{{-- KPI Row --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Tickets</div>
                    <div class="stat-value" style="color:#0F172A;">{{ $totalTickets }}</div>
                    <div class="stat-sub"><i class="bi bi-arrow-up-right me-1 text-success"></i>All time</div>
                </div>
                <div class="stat-icon" style="background:#EFF6FF;">
                    <i class="bi bi-ticket-perforated" style="color:#3B82F6;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Pending</div>
                    <div class="stat-value" style="color:#1E40AF;">{{ $pendingTickets }}</div>
                    <div class="stat-sub">Awaiting response</div>
                </div>
                <div class="stat-icon" style="background:#EFF6FF;">
                    <i class="bi bi-hourglass-split" style="color:#3B82F6;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Urgent</div>
                    <div class="stat-value" style="color:#991B1B;">{{ $urgentTickets }}</div>
                    <div class="stat-sub" style="color:#991B1B;">Needs immediate attention</div>
                </div>
                <div class="stat-icon" style="background:#FEF2F2;">
                    <i class="bi bi-exclamation-triangle" style="color:#DC2626;"></i>
                </div>
            </div>
            {{-- Urgent pulse indicator --}}
            @if($urgentTickets > 0)
            <div style="position:absolute;top:16px;right:16px;width:8px;height:8px;background:#EF4444;border-radius:50%;box-shadow:0 0 0 3px rgba(239,68,68,0.2);"></div>
            @endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Resolved</div>
                    <div class="stat-value" style="color:#166534;">{{ $resolvedTickets }}</div>
                    <div class="stat-sub" style="color:#166534;">Successfully closed</div>
                </div>
                <div class="stat-icon" style="background:#F0FDF4;">
                    <i class="bi bi-check-circle" style="color:#22C55E;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Secondary Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#F5F3FF;flex-shrink:0;">
                <i class="bi bi-people" style="color:#7C3AED;"></i>
            </div>
            <div>
                <div class="stat-label">Total Customers</div>
                <div class="stat-value" style="color:#4C1D95;font-size:24px;">{{ $totalCustomers }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#FFFBEB;flex-shrink:0;">
                <i class="bi bi-arrow-repeat" style="color:#D97706;"></i>
            </div>
            <div>
                <div class="stat-label">Processing</div>
                <div class="stat-value" style="color:#92400E;font-size:24px;">{{ $processingTickets }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#F0FDFA;flex-shrink:0;">
                <i class="bi bi-question-circle" style="color:#0D9488;"></i>
            </div>
            <div>
                <div class="stat-label">Active FAQs</div>
                <div class="stat-value" style="color:#115E59;font-size:24px;">{{ $totalFaqs }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Sentiment Overview --}}
<div class="card-surface mb-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h6 class="section-header">
            <i class="bi bi-robot"></i> AI Sentiment Overview
        </h6>
        <span style="font-size:11.5px;color:var(--text-muted);">Active tickets only</span>
    </div>
    <div class="row g-3">
        <div class="col-md-4">
            <div style="background:#FEF2F2;border-radius:12px;padding:20px;border:1px solid #FECACA;text-align:center;">
                <div style="font-size:26px;font-weight:600;color:#991B1B;letter-spacing:-0.5px;">{{ $urgentTickets }}</div>
                <div style="font-size:13px;font-weight:600;color:#991B1B;margin:4px 0 6px;display:flex;align-items:center;justify-content:center;gap:5px;">
                    <span style="width:8px;height:8px;background:#EF4444;border-radius:50%;display:inline-block;"></span> Urgent
                </div>
                <div style="font-size:11.5px;color:#B91C1C;">Immediate attention needed</div>
            </div>
        </div>
        <div class="col-md-4">
            <div style="background:#FFFBEB;border-radius:12px;padding:20px;border:1px solid #FCD34D;text-align:center;">
                <div style="font-size:26px;font-weight:600;color:#92400E;letter-spacing:-0.5px;">{{ $frustratedTickets }}</div>
                <div style="font-size:13px;font-weight:600;color:#92400E;margin:4px 0 6px;display:flex;align-items:center;justify-content:center;gap:5px;">
                    <span style="width:8px;height:8px;background:#F59E0B;border-radius:50%;display:inline-block;"></span> Frustrated
                </div>
                <div style="font-size:11.5px;color:#B45309;">Customer needs reassurance</div>
            </div>
        </div>
        <div class="col-md-4">
            <div style="background:#EFF6FF;border-radius:12px;padding:20px;border:1px solid #BFDBFE;text-align:center;">
                <div style="font-size:26px;font-weight:600;color:#1E40AF;letter-spacing:-0.5px;">{{ $neutralTickets }}</div>
                <div style="font-size:13px;font-weight:600;color:#1E40AF;margin:4px 0 6px;display:flex;align-items:center;justify-content:center;gap:5px;">
                    <span style="width:8px;height:8px;background:#3B82F6;border-radius:50%;display:inline-block;"></span> Neutral
                </div>
                <div style="font-size:11.5px;color:#1D4ED8;">Standard inquiry</div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Tickets --}}
<div class="card-surface">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h6 class="section-header">
            <i class="bi bi-clock-history"></i> Recent Tickets
        </h6>
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-primary btn-sm">
            View all <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Ticket</th>
                    <th>Customer</th>
                    <th>Subject</th>
                    <th>Sentiment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTickets as $ticket)
                <tr onclick="window.location='{{ route('admin.tickets.show', $ticket) }}'">
                    <td><span class="ticket-id">#{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</span></td>
                    <td style="font-weight:500;">{{ $ticket->user->name ?? 'Unknown' }}</td>
                    <td style="color:var(--text-muted);">{{ Str::limit($ticket->subject, 38) }}</td>
                    <td>
                        <span class="badge-sentiment badge-{{ strtolower($ticket->sentiment) }}">
                            {{ $ticket->sentiment }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-status badge-{{ strtolower($ticket->status) }}">
                            {{ $ticket->status }}
                        </span>
                    </td>
                    <td style="color:var(--text-muted);font-size:13px;">{{ $ticket->created_at->format('M d, Y') }}</td>
                    <td onclick="event.stopPropagation()">
                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-outline-primary btn-sm">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted);">
                        <i class="bi bi-inbox" style="font-size:28px;display:block;margin-bottom:8px;color:var(--text-xs);"></i>
                        No tickets yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
