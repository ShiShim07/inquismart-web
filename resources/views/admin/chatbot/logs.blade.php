@extends('admin.layout')
@section('title', 'Chatbot Logs')

@section('content')

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Messages</div>
                    <div class="stat-value" style="color:#1E40AF;">{{ $totalMessages }}</div>
                    <div class="stat-sub">All chatbot interactions</div>
                </div>
                <div class="stat-icon" style="background:#EFF6FF;">
                    <i class="bi bi-chat-dots" style="color:#3B82F6;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Users Served</div>
                    <div class="stat-value" style="color:#166534;">{{ $totalUsers }}</div>
                    <div class="stat-sub">Unique customers</div>
                </div>
                <div class="stat-icon" style="background:#F0FDF4;">
                    <i class="bi bi-people" style="color:#22C55E;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Top Intent</div>
                    <div class="stat-value" style="color:#7C3AED;font-size:18px;">
                        {{ $topIntent ? ucfirst($topIntent->intent) : 'N/A' }}
                    </div>
                    <div class="stat-sub">Most asked topic</div>
                </div>
                <div class="stat-icon" style="background:#F5F3FF;">
                    <i class="bi bi-robot" style="color:#7C3AED;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card-surface mb-4">
    <form method="GET" action="{{ route('admin.chatbot.logs') }}" class="row g-3 align-items-end">
        <div class="col-md-6">
            <label class="form-label" style="font-size:13px;font-weight:600;">Filter by User</label>
            <select name="user_id" class="form-select form-select-sm">
                <option value="">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label" style="font-size:13px;font-weight:600;">Filter by Intent</label>
            <select name="intent" class="form-select form-select-sm">
                <option value="">All Intents</option>
                @foreach($intents as $intent)
                    <option value="{{ $intent }}" {{ request('intent') == $intent ? 'selected' : '' }}>
                        {{ ucfirst($intent) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            <a href="{{ route('admin.chatbot.logs') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-x-circle"></i>
            </a>
        </div>
    </form>
</div>

{{-- Conversations Table --}}
<div class="card-surface mb-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h6 class="section-header">
            <i class="bi bi-people"></i> Customer Conversations
        </h6>
        <span style="font-size:12px;color:var(--text-muted);">Click "View" to open a conversation</span>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Messages</th>
                    <th>Last Message</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($conversations as $conv)
                <tr>
                    <td style="font-weight:500;">
                        {{ $conv->user->name ?? 'Unknown' }}
                        <div style="font-size:11px;color:var(--text-muted);">{{ $conv->user->email ?? '' }}</div>
                    </td>
                    <td>
                        <span style="font-weight:600;">{{ $conv->message_count }}</span>
                        <span style="font-size:12px;color:var(--text-muted);"> messages</span>
                    </td>
                    <td style="color:var(--text-muted);font-size:13px;">
                        {{ \Carbon\Carbon::parse($conv->last_message_at)->format('M d, Y h:i A') }}
                    </td>
                    <td>
                        @if($conv->escalated_count > 0)
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-ticket-perforated me-1"></i>Submitted Ticket
                            </span>
                        @else
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>Bot Handled
                            </span>
                        @endif
                    </td>
                    <td>
                        {{-- View only — no reply --}}
                        <a href="{{ route('admin.chatbot.conversation', $conv->user_id) }}"
                           class="btn btn-sm btn-primary">
                            <i class="bi bi-eye me-1"></i> View
                        </a>
                        <form action="{{ route('admin.chatbot.destroy', $conv->user_id) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Clear all chat history for this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger ms-1">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted);">
                        <i class="bi bi-chat-square" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                        No chatbot conversations yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($conversations->hasPages())
    <div class="mt-4">
        {{ $conversations->links() }}
    </div>
    @endif
</div>

{{-- Full Message Log --}}
<div class="card-surface">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h6 class="section-header">
            <i class="bi bi-chat-square-text"></i> All Message Logs
        </h6>
        <span style="font-size:12px;color:var(--text-muted);">{{ $logs->total() }} total messages</span>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Role</th>
                    <th>Message</th>
                    <th>Intent</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td style="font-weight:500;">
                        {{ $log->user->name ?? 'Unknown' }}
                        <div style="font-size:11px;color:var(--text-muted);">{{ $log->user->email ?? '' }}</div>
                    </td>
                    <td>
                        @if($log->role === 'user')
                            <span class="badge-sentiment badge-neutral">
                                <i class="bi bi-person-fill me-1"></i>Customer
                            </span>
                        @else
                            <span style="background:#F5F3FF;color:#7C3AED;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">
                                <i class="bi bi-robot me-1"></i>Bot
                            </span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);max-width:320px;">
                        {{ Str::limit($log->message, 80) }}
                        @if($log->needs_human)
                            <span class="badge bg-warning text-dark ms-1" style="font-size:10px;">
                                🎫 Prompted to Submit Ticket
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($log->intent)
                            <span style="background:#F5F3FF;color:#7C3AED;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">
                                {{ ucfirst($log->intent) }}
                            </span>
                        @else
                            <span style="color:var(--text-muted);font-size:12px;">—</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);font-size:13px;">
                        {{ $log->created_at->format('M d, Y h:i A') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted);">
                        <i class="bi bi-chat-square" style="font-size:28px;display:block;margin-bottom:8px;color:var(--text-xs);"></i>
                        No chatbot conversations yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div class="mt-4">
        {{ $logs->links() }}
    </div>
    @endif
</div>

@endsection