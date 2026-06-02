@extends('admin.layout')
@section('title', 'All Tickets')

@section('content')

{{-- Header + Filter bar --}}
<div class="surface surface-pad mb-4 fade-up">
    <form method="GET" action="{{ route('admin.tickets.index') }}" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label-sm">Search</label>
            <div style="position:relative;">
                <i class="bi bi-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-3);font-size:14px;"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search subject or customer..."
                       class="form-ctrl" style="padding-left:34px;">
            </div>
        </div>
        <div class="col-md-2">
            <label class="form-label-sm">Status</label>
            <select name="status" class="form-ctrl">
                <option value="">All Status</option>
                <option value="Pending"    {{ request('status') == 'Pending'    ? 'selected' : '' }}>Pending</option>
                <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                <option value="Resolved"   {{ request('status') == 'Resolved'   ? 'selected' : '' }}>Resolved</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label-sm">Sentiment</label>
            <select name="sentiment" class="form-ctrl">
                <option value="">All Sentiment</option>
                <option value="Negative"  {{ request('sentiment') == 'Negative'  ? 'selected' : '' }}>Negative</option>
                <option value="Positive"  {{ request('sentiment') == 'Positive'  ? 'selected' : '' }}>Positive</option>
                <option value="Neutral"   {{ request('sentiment') == 'Neutral'   ? 'selected' : '' }}>Neutral</option>
                <option value="Urgent"    {{ request('sentiment') == 'Urgent'    ? 'selected' : '' }}>Urgent</option>
                <option value="Frustrated"{{ request('sentiment') == 'Frustrated'? 'selected' : '' }}>Frustrated</option>
            </select>
        </div>
        <div class="col-md-auto">
            <button type="submit" class="btn-primary-custom">
                <i class="bi bi-funnel-fill"></i> Filter
            </button>
        </div>
        <div class="col-md-auto">
            <a href="{{ route('admin.tickets.index') }}" class="btn-ghost">
                <i class="bi bi-x-circle"></i> Clear
            </a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="surface fade-up delay-1">
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
                    @php
                        $sentKey = strtolower($ticket->sentiment);
                        $dotColor = match($sentKey) {
                            'negative','urgent'     => 'var(--danger)',
                            'positive'              => 'var(--success)',
                            'frustrated'            => 'var(--warning)',
                            default                 => '#5B72F8',
                        };
                    @endphp
                    <tr onclick="window.location='{{ route('admin.tickets.show', $ticket) }}'">
                        <td><span class="td-id">#TKT-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</span></td>
                        <td>
                            <div style="font-weight:600;font-size:13.5px;">{{ $ticket->user->name ?? 'Unknown' }}</div>
                            <div style="font-size:11.5px;color:var(--text-3);">{{ $ticket->user->email ?? '' }}</div>
                        </td>
                        <td style="color:var(--text-2);max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $ticket->subject }}
                        </td>
                        <td>
                            <span class="chip chip-{{ $sentKey }}">
                                <span style="width:6px;height:6px;border-radius:50%;background:{{ $dotColor }};flex-shrink:0;display:inline-block;"></span>
                                {{ $ticket->sentiment }}
                            </span>
                        </td>
                        <td>
                            <span class="chip chip-{{ strtolower($ticket->status) }}">
                                {{ $ticket->status }}
                            </span>
                        </td>
                        <td style="color:var(--text-3);font-size:12.5px;">
                            {{ $ticket->created_at->format('M d, Y') }}
                        </td>
                        <td onclick="event.stopPropagation()">
                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn-primary-custom" style="font-size:12px;padding:6px 14px;">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:56px;color:var(--text-3);">
                            <i class="bi bi-inbox" style="font-size:36px;display:block;margin-bottom:12px;opacity:0.3;"></i>
                            No tickets found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($tickets->hasPages())
        <div style="padding:16px 20px;border-top:1px solid var(--border);">
            {{ $tickets->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection
