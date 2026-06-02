@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')

{{-- KPI Row --}}
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card fade-up delay-1">
            <div class="stat-card-accent" style="background:var(--primary);"></div>
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Tickets</div>
                    <div class="stat-value" style="color:var(--text-1);">{{ $totalTickets }}</div>
                    <div class="stat-sub">
                        <i class="bi bi-arrow-up-right" style="color:var(--success);"></i>
                        All time
                    </div>
                </div>
                <div class="stat-icon" style="background:#EEF2FF;">
                    <i class="bi bi-ticket-perforated-fill" style="color:var(--primary);"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card fade-up delay-2">
            <div class="stat-card-accent" style="background:#3B4FCC;"></div>
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Pending</div>
                    <div class="stat-value" style="color:#3B4FCC;">{{ $pendingTickets }}</div>
                    <div class="stat-sub">Awaiting response</div>
                </div>
                <div class="stat-icon" style="background:#EEF2FF;">
                    <i class="bi bi-hourglass-split" style="color:#3B4FCC;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card fade-up delay-3">
            <div class="stat-card-accent" style="background:var(--danger);"></div>
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Negative</div>
                    <div class="stat-value" style="color:var(--danger);">{{ $negativeTickets }}</div>
                    <div class="stat-sub" style="color:var(--danger);">Needs attention</div>
                </div>
                <div class="d-flex flex-column align-items-end gap-2">
                    @if($negativeTickets > 0)
                        <div class="pulse-dot"></div>
                    @endif
                    <div class="stat-icon" style="background:#FFF0F2;">
                        <i class="bi bi-exclamation-triangle-fill" style="color:var(--danger);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card fade-up delay-4">
            <div class="stat-card-accent" style="background:var(--success);"></div>
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Resolved</div>
                    <div class="stat-value" style="color:var(--success);">{{ $resolvedTickets }}</div>
                    <div class="stat-sub" style="color:var(--success);">Successfully closed</div>
                </div>
                <div class="stat-icon" style="background:#EDFFF9;">
                    <i class="bi bi-check-circle-fill" style="color:var(--success);"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Secondary Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4 col-sm-6">
        <div class="stat-card fade-up delay-1">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#F3F0FF;flex-shrink:0;">
                    <i class="bi bi-people-fill" style="color:#7C3AED;"></i>
                </div>
                <div>
                    <div class="stat-label">Total Customers</div>
                    <div class="stat-value" style="color:#7C3AED;font-size:24px;">{{ $totalCustomers }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="stat-card fade-up delay-2">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#FFF8EE;flex-shrink:0;">
                    <i class="bi bi-arrow-repeat" style="color:var(--warning);"></i>
                </div>
                <div>
                    <div class="stat-label">Processing</div>
                    <div class="stat-value" style="color:#9A5A0A;font-size:24px;">{{ $processingTickets }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="stat-card fade-up delay-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#EDFFF9;flex-shrink:0;">
                    <i class="bi bi-question-circle-fill" style="color:var(--success);"></i>
                </div>
                <div>
                    <div class="stat-label">Active FAQs</div>
                    <div class="stat-value" style="color:#076B4A;font-size:24px;">{{ $totalFaqs }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Sentiment Overview --}}
<div class="surface surface-pad mb-4 fade-up">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h6 class="section-title"><i class="bi bi-robot"></i> AI Sentiment Overview</h6>
        <span style="font-size:11.5px;color:var(--text-3);background:var(--bg);padding:4px 10px;border-radius:6px;">Active tickets only</span>
    </div>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="sentiment-box sentiment-negative">
                <div class="sentiment-count" style="color:#C0123A;">{{ $negativeTickets }}</div>
                <div class="sentiment-label" style="color:#C0123A;">
                    <span class="sentiment-dot" style="background:var(--danger);"></span> Negative
                </div>
                <div class="sentiment-desc" style="color:#9B1C38;">Needs immediate attention</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="sentiment-box sentiment-positive">
                <div class="sentiment-count" style="color:#076B4A;">{{ $positiveTickets }}</div>
                <div class="sentiment-label" style="color:#076B4A;">
                    <span class="sentiment-dot" style="background:var(--success);"></span> Positive
                </div>
                <div class="sentiment-desc" style="color:#068554;">Customer is satisfied</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="sentiment-box sentiment-neutral">
                <div class="sentiment-count" style="color:#3B4FCC;">{{ $neutralTickets }}</div>
                <div class="sentiment-label" style="color:#3B4FCC;">
                    <span class="sentiment-dot" style="background:#5B72F8;"></span> Neutral
                </div>
                <div class="sentiment-desc" style="color:#4B5ECC;">Standard inquiry</div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Tickets --}}
<div class="surface fade-up">
    <div class="surface-pad" style="border-bottom:1px solid var(--border);padding-bottom:16px;">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="section-title"><i class="bi bi-clock-history"></i> Recent Tickets</h6>
            <a href="{{ route('admin.tickets.index') }}" class="btn-outline-custom">
                View all <i class="bi bi-arrow-right"></i>
            </a>
        </div>
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
                    <td><span class="td-id">#{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</span></td>
                    <td style="font-weight:600;">{{ $ticket->user->name ?? 'Unknown' }}</td>
                    <td style="color:var(--text-2);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        {{ Str::limit($ticket->subject, 38) }}
                    </td>
                    <td>
                        <span class="chip chip-{{ strtolower($ticket->sentiment) }}">
                            <span class="sentiment-dot" style="background:{{ match(strtolower($ticket->sentiment)) { 'negative' => 'var(--danger)', 'positive' => 'var(--success)', default => '#5B72F8' } }};width:6px;height:6px;"></span>
                            {{ $ticket->sentiment }}
                        </span>
                    </td>
                    <td>
                        <span class="chip chip-{{ strtolower($ticket->status) }}">
                            {{ $ticket->status }}
                        </span>
                    </td>
                    <td style="color:var(--text-3);font-size:12.5px;">{{ $ticket->created_at->format('M d, Y') }}</td>
                    <td onclick="event.stopPropagation()">
                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn-outline-custom" style="font-size:12px;padding:5px 12px;">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:48px;color:var(--text-3);">
                        <i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:10px;opacity:0.4;"></i>
                        No tickets yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
