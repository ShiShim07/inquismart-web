@extends('admin.layout')
@section('title', 'Conversation with ' . $customer->name)

@section('content')
<div class="container-fluid py-3" style="max-width:860px;">

    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.chatbot.logs') }}" class="btn btn-sm btn-outline-secondary">
            ← Back to Logs
        </a>
        <div>
            <h5 class="mb-0 fw-bold">{{ $customer->name }}</h5>
            <small class="text-muted">{{ $customer->email }}</small>
        </div>
        <span class="badge bg-primary ms-auto">{{ $messages->count() }} messages</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Info banner --}}
    <div class="alert alert-info d-flex align-items-center gap-2 mb-4" role="alert">
        <span>ℹ️</span>
        <span>
            This is a <strong>read-only</strong> view of the customer's chatbot conversation.
            For concerns that need staff action, ask the customer to
            <strong>Submit a Ticket</strong> from the mobile app.
        </span>
    </div>

    {{-- Chat Window --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-semibold">💬 Chat History</h6>
            <small class="text-muted">Bot-only conversation</small>
        </div>
        <div class="card-body p-0">
            <div id="chat-window" style="height:560px; overflow-y:auto; padding:20px; background:#f8f9fa;">
                @forelse($messages as $msg)
                    @if($msg->role === 'user')
                        {{-- Customer message (right) --}}
                        <div class="d-flex justify-content-end mb-3">
                            <div style="max-width:70%;">
                                <div class="bg-primary text-white px-3 py-2"
                                     style="border-radius:18px 18px 4px 18px;">
                                    {{ $msg->message }}
                                </div>
                                <div class="text-end mt-1">
                                    <small class="text-muted">
                                        {{ $customer->name }} • {{ $msg->created_at->format('M d, h:i A') }}
                                    </small>
                                </div>
                            </div>
                            <div class="ms-2 d-flex align-items-end mb-3">
                                <div style="width:32px;height:32px;background:#0d6efd;border-radius:50%;
                                            display:flex;align-items:center;justify-content:center;
                                            color:white;font-size:13px;font-weight:600;">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                            </div>
                        </div>

                    @elseif($msg->role === 'bot')
                        {{-- Bot message (left) --}}
                        <div class="d-flex mb-3">
                            <div class="me-2 d-flex align-items-end mb-3">
                                <div style="width:32px;height:32px;background:#6c757d;border-radius:50%;
                                            display:flex;align-items:center;justify-content:center;
                                            color:white;font-size:13px;">
                                    🤖
                                </div>
                            </div>
                            <div style="max-width:70%;">
                                <div class="bg-white border px-3 py-2"
                                     style="border-radius:18px 18px 18px 4px;">
                                    {!! nl2br(e($msg->message)) !!}
                                    @if($msg->needs_human)
                                        <div class="mt-2">
                                            <span class="badge bg-warning text-dark" style="font-size:10px;">
                                                ⚡ Customer was prompted to Submit a Ticket
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-1">
                                    <small class="text-muted">
                                        InquiBot • {{ $msg->created_at->format('M d, h:i A') }}
                                    </small>
                                    @if($msg->intent)
                                        <span class="badge bg-light text-secondary ms-1" style="font-size:10px;">
                                            {{ $msg->intent }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="text-center text-muted py-5">No messages yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Footer note --}}
    <div class="text-center text-muted small">
        <p>
            💡 If this customer needs further assistance, they can tap
            <strong>"Submit a Ticket"</strong> in the mobile app and the ticket will appear
            under <a href="{{ route('admin.tickets.index') }}">Ticket Management</a>.
        </p>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Auto-scroll to bottom of chat
    const chatWindow = document.getElementById('chat-window');
    if (chatWindow) {
        chatWindow.scrollTop = chatWindow.scrollHeight;
    }
</script>
@endpush