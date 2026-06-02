@extends('admin.layout')
@section('title', 'Chatbot Logs')

@section('content')

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card fade-up delay-1">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Messages</div>
                    <div class="stat-value" style="color:var(--primary);">{{ $totalMessages }}</div>
                    <div class="stat-sub">All chatbot interactions</div>
                </div>
                <div class="stat-icon" style="background:#EEF2FF;">
                    <i class="bi bi-chat-dots-fill" style="color:var(--primary);"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card fade-up delay-2">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Users Served</div>
                    <div class="stat-value" style="color:var(--success);">{{ $totalUsers }}</div>
                    <div class="stat-sub">Unique customers</div>
                </div>
                <div class="stat-icon" style="background:#EDFFF9;">
                    <i class="bi bi-people-fill" style="color:var(--success);"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card fade-up delay-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Top Intent</div>
                    <div class="stat-value" style="color:#7C3AED;font-size:18px;line-height:1.2;margin-top:4px;">
                        {{ $topIntent ? ucfirst($topIntent->intent) : 'N/A' }}
                    </div>
                    <div class="stat-sub">Most asked topic</div>
                </div>
                <div class="stat-icon" style="background:#F3F0FF;">
                    <i class="bi bi-robot" style="color:#7C3AED;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="surface surface-pad mb-4 fade-up">
    <form method="GET" action="{{ route('admin.chatbot.logs') }}" class="row g-3 align-items-end">
        <div class="col-md-5">
            <label class="form-label-sm">Filter by User</label>
            <select name="user_id" class="form-ctrl">
                <option value="">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label-sm">Filter by Intent</label>
            <select name="intent" class="form-ctrl">
                <option value="">All Intents</option>
                @foreach($intents as $intent)
                    <option value="{{ $intent }}" {{ request('intent') == $intent ? 'selected' : '' }}>
                        {{ ucfirst($intent) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-auto d-flex gap-2">
            <button type="submit" class="btn-primary-custom">
                <i class="bi bi-funnel-fill"></i> Filter
            </button>
            <a href="{{ route('admin.chatbot.logs') }}" class="btn-ghost">
                <i class="bi bi-x-circle"></i>
            </a>
        </div>
    </form>
</div>

{{-- Conversations Table --}}
<div class="surface mb-4 fade-up delay-1">
    <div class="surface-pad" style="border-bottom:1px solid var(--border);padding-bottom:16px;">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="section-title"><i class="bi bi-people-fill"></i> Customer Conversations</h6>
            <span style="font-size:12px;color:var(--text-3);">Click View to open a conversation</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Messages</th>
                    <th>Last Message</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($conversations as $conv)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $conv->user->name ?? 'Unknown' }}</div>
                        <div style="font-size:11.5px;color:var(--text-3);">{{ $conv->user->email ?? '' }}</div>
                    </td>
                    <td>
                        <span style="font-weight:700;font-family:'Syne',sans-serif;">{{ $conv->message_count }}</span>
                        <span style="font-size:12px;color:var(--text-3);"> msgs</span>
                    </td>
                    <td style="color:var(--text-2);font-size:12.5px;">
                        {{ \Carbon\Carbon::parse($conv->last_message_at)->format('M d, Y h:i A') }}
                    </td>
                    <td>
                        @if($conv->escalated_count > 0)
                            <span class="chip chip-processing">
                                <i class="bi bi-ticket-perforated-fill"></i> Ticket Submitted
                            </span>
                        @else
                            <span class="chip chip-positive">
                                <i class="bi bi-check-circle-fill"></i> Bot Handled
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.chatbot.conversation', $conv->user_id) }}"
                               class="btn-outline-custom" style="font-size:12px;padding:5px 12px;">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <form action="{{ route('admin.chatbot.destroy', $conv->user_id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Clear all chat history for this user?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="display:inline-flex;align-items:center;gap:4px;padding:5px 10px;background:#FFF0F2;color:var(--danger);border:1px solid #FFD0D8;border-radius:8px;font-size:12px;font-family:'DM Sans',sans-serif;cursor:pointer;transition:all 0.15s;" onmouseover="this.style.background='#FFD0D8'" onmouseout="this.style.background='#FFF0F2'">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:48px;color:var(--text-3);">
                        <i class="bi bi-chat-square" style="font-size:32px;display:block;margin-bottom:10px;opacity:0.3;"></i>
                        No chatbot conversations yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($conversations->hasPages())
        <div style="padding:14px 20px;border-top:1px solid var(--border);">
            {{ $conversations->links() }}
        </div>
    @endif
</div>

{{-- Message Log --}}
<div class="surface fade-up delay-2">
    <div class="surface-pad" style="border-bottom:1px solid var(--border);padding-bottom:16px;">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="section-title"><i class="bi bi-chat-square-text-fill"></i> All Message Logs</h6>
            <span style="font-size:12px;color:var(--text-3);background:var(--bg);padding:4px 10px;border-radius:6px;">
                {{ $logs->total() }} total messages
            </span>
        </div>
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
                    <td>
                        <div style="font-weight:600;">{{ $log->user->name ?? 'Unknown' }}</div>
                        <div style="font-size:11.5px;color:var(--text-3);">{{ $log->user->email ?? '' }}</div>
                    </td>
                    <td>
                        @if($log->role === 'user')
                            <span class="chip chip-neutral">
                                <i class="bi bi-person-fill"></i> Customer
                            </span>
                        @else
                            <span class="chip" style="background:#F3F0FF;color:#7C3AED;">
                                <i class="bi bi-robot"></i> Bot
                            </span>
                        @endif
                    </td>
                    <td style="color:var(--text-2);max-width:320px;">
                        {{ Str::limit($log->message, 80) }}
                        @if($log->needs_human)
                            <span class="chip chip-processing" style="margin-left:4px;font-size:10px;">
                                🎫 Prompted Ticket
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($log->intent)
                            <span class="chip" style="background:#F3F0FF;color:#7C3AED;">
                                {{ ucfirst($log->intent) }}
                            </span>
                        @else
                            <span style="color:var(--text-3);font-size:12px;">—</span>
                        @endif
                    </td>
                    <td style="color:var(--text-3);font-size:12.5px;">
                        {{ $log->created_at->format('M d, Y h:i A') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:48px;color:var(--text-3);">
                        <i class="bi bi-chat-square" style="font-size:32px;display:block;margin-bottom:10px;opacity:0.3;"></i>
                        No messages yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
        <div style="padding:14px 20px;border-top:1px solid var(--border);">
            {{ $logs->links() }}
        </div>
    @endif
</div>

@endsection
